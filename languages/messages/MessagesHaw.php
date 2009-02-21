<?php
/** Hawaiian (Hawai`i)
 *
 * @ingroup Language
 * @file
 *
 * @author Kalani
 * @author Node ue
 * @author Singularity
 */

$namespaceNames = array(
	NS_MEDIA            => 'Pāpaho',
	NS_SPECIAL          => 'Papa_nui',
	NS_TALK             => 'Kūkākūkā',
	NS_USER             => 'Mea_hoʻohana',
	NS_USER_TALK        => 'Kūkākūkā_o_mea_hoʻohana',
	NS_PROJECT_TALK     => 'Kūkākūkā_o_Wikipikia',
	NS_FILE             => 'Waihona',
	NS_FILE_TALK        => 'Kūkākūkā_o_waihona',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Kūkākūkā_o_MediaWiki',
	NS_TEMPLATE         => 'Anakuhi',
	NS_TEMPLATE_TALK    => 'Kūkākūkā_o_anakuhi',
	NS_HELP             => 'Kōkua',
	NS_HELP_TALK        => 'Kūkākūkā_o_kōkua',
	NS_CATEGORY         => 'Māhele',
	NS_CATEGORY_TALK    => 'Kūkākūkā_o_māhele',
);

$namespaceAliases = array(
	'Kiʻi' => NS_FILE,
	'Kūkākūkā_o_kiʻi' => NS_FILE_TALK,
);

$magicWords = array(
	'currentmonth'          => array( '1', 'KĒIAMAHINA', 'CURRENTMONTH' ),
	'currentmonthname'      => array( '1', 'KĒIAINOAMAHINA', 'CURRENTMONTHNAME' ),
	'currentday'            => array( '1', 'KĒIALĀ', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'KĒIALĀ2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'KĒIAINOALĀ', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'KĒIAMAKAHIKI', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'KĒIAMANAWA', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'KĒIAHOLA', 'CURRENTHOUR' ),
	'img_right'             => array( '1', 'ʻākau', 'right' ),
	'img_left'              => array( '1', 'hema', 'left' ),
	'img_none'              => array( '1', 'ʻaʻohe', 'none' ),
	'currentweek'           => array( '1', 'KĒIAPULE', 'CURRENTWEEK' ),
	'language'              => array( '0', '#ʻŌLELO', '#LANGUAGE:' ),
);

$messages = array(
# User preference toggles
'tog-underline'            => 'Kahalalo i nā loulou:',
'tog-justify'              => 'Ho‘okaulihi i nā paukū',
'tog-hideminor'            => 'E hūnā i nā ho‘opololei iki ma nā loli hou',
'tog-editondblclick'       => 'Ho‘opololei i nā ‘ao‘ao ma ke kōmi pālua (JavaScript)',
'tog-showtoc'              => 'Hō‘ike i ka papa kuhikuhi',
'tog-rememberpassword'     => 'Ho‘omana‘o i ko‘u hua‘ōlelo huna i loko o kēia kamepuila',
'tog-editwidth'            => 'He ākea piha kō ka pahu ho‘opololei.',
'tog-watchcreations'       => 'Ho‘ohui i nā ‘ao‘ao i hana ai au i ka‘u papa nānā pono',
'tog-watchdefault'         => 'Ho‘ohui i nā ‘ao‘ao i ho‘opololei ai au i ka‘u papa nānā pono',
'tog-watchmoves'           => 'Ho‘ohui i nā ‘ao‘ao i ne‘e ai au i ka‘u papa nānā pono',
'tog-watchdeletion'        => 'Ho‘ohui i nā ‘ao‘ao i kāpae ai au i ka‘u papa nānā pono',
'tog-previewontop'         => 'Hō‘ike i ka nāmua mamua o ke kau ho‘opololei',
'tog-previewonfirst'       => 'Hō‘ike i ka nāmua ma ka ho‘opololei mua',
'tog-enotifwatchlistpages' => 'Ke loli kekahi ‘ao‘ao ma ka‘u papa nānā pono, leka uila ia‘u',
'tog-enotifusertalkpages'  => 'Ke loli ka‘u ‘ōlelo, leka uila ia‘u',
'tog-enotifminoredits'     => 'No nā ho‘opololei ‘ana, leka uila ia‘u',
'tog-enotifrevealaddr'     => 'Hō‘ike i ko‘u leka uila ma nā leka uila hō‘ike',
'tog-shownumberswatching'  => 'Hō‘ike i ka heluna o nā mea ho‘ohana e nānā ai',
'tog-fancysig'             => 'Nā kākau inoa kūlohelohe (‘a‘ole me ka loulou hana nona iho)',
'tog-forceeditsummary'     => 'Ke kāhuakomo au i he ho‘ulu‘ulu mana‘o ‘ole, ha‘i mai',
'tog-watchlisthideown'     => 'E hūnā i ko‘u mau ho‘opololei ma ka papa nānā pono',
'tog-watchlisthidebots'    => 'Hūnā i nā ho‘opololei ‘ana o nā lopako mai ka papa nānā pono',
'tog-watchlisthideminor'   => 'E hūnā i nā ho‘opololei iki ma ka papa nānā pono',
'tog-ccmeonemails'         => 'Hā‘awi mai i nā kope o nā leka uila i hā‘awi ai au i kekahi mau mea ho‘ohana.',
'tog-showhiddencats'       => 'Hō‘ike i nā mahele huna',

'underline-always' => 'Mau',
'underline-never'  => '‘A‘ole loa',

# Dates
'sunday'        => 'Lāpule',
'monday'        => 'Pō‘akahi',
'tuesday'       => 'Pō‘alua',
'wednesday'     => 'Pō‘akolu',
'thursday'      => 'Pō‘ahā',
'friday'        => 'Pō‘alima',
'saturday'      => 'Pō‘aono',
'sun'           => 'Lāpule',
'mon'           => 'Pōʻakahi',
'tue'           => 'Pō‘alua',
'wed'           => 'Pō‘akolu',
'thu'           => 'Pō‘ahā',
'fri'           => 'Pō‘alima',
'sat'           => 'Pō‘aono',
'january'       => 'Ianuali',
'february'      => 'Pepeluali',
'march'         => 'Malaki',
'april'         => '‘Apelila',
'may_long'      => 'Mei',
'june'          => 'Iune',
'july'          => 'Iulai',
'august'        => '‘Aukake',
'september'     => 'Kepakemapa',
'october'       => '‘Okakopa',
'november'      => 'Nowemapa',
'december'      => 'Kēkēmapa',
'january-gen'   => 'Ianuali',
'february-gen'  => 'Pepeluali',
'march-gen'     => 'Malaki',
'april-gen'     => '‘Apelila',
'may-gen'       => 'Mei',
'june-gen'      => 'Iune',
'july-gen'      => 'Iulai',
'august-gen'    => '‘Aukake',
'september-gen' => 'Kepakemapa',
'october-gen'   => '‘Okakopa',
'november-gen'  => 'Nowemapa',
'december-gen'  => 'Kēkēmapa',
'jan'           => 'Ian',
'feb'           => 'Pep',
'mar'           => 'Mal',
'apr'           => 'Ape',
'may'           => 'Mei',
'jun'           => 'Iun',
'jul'           => 'Iul',
'aug'           => 'Auk',
'sep'           => 'Kep',
'oct'           => 'Oka',
'nov'           => 'Now',
'dec'           => 'Kek',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|Mahele|Nā mahele}}',
'category_header'          => 'Nā mo‘olelo maloko o ka mahele "$1"',
'subcategories'            => 'Nā lalo-mahele',
'category-media-header'    => 'Nā pāpaho maloko o ka mahele "$1"',
'category-empty'           => "''‘A‘ohe mau mo‘olelo o kēia mahele.''",
'hidden-categories'        => '{{PLURAL:$1|Mahele hūnā|Nā mahele hūnā}}',
'hidden-category-category' => 'Nā mahele hūnā', # Name of the category where hidden categories will be listed
'listingcontinuesabbrev'   => '(ho‘omau ‘ia)',

'mainpagetext' => "<big>'''Pono ka ho‘ouka ‘ana o MediaWiki.'''</big>",

'about'          => 'E pili ana',
'cancel'         => 'Ho‘ōki',
'qbfind'         => 'Loa‘a',
'qbedit'         => 'E ho‘opololei',
'qbpageoptions'  => 'Keia ‘ao‘ao',
'qbpageinfo'     => 'Pō‘aiapili',
'qbmyoptions'    => 'Ka‘u mau ‘ao‘ao',
'qbspecialpages' => 'Nā ‘ao‘ao kūikawā',
'moredotdotdot'  => 'Hou...',
'mypage'         => 'Ko‘u ‘ao‘ao',
'mytalk'         => 'Ka‘u ‘ōlelo',
'anontalk'       => 'Ke kūkākūkā no kēia IP',
'navigation'     => 'Ka papa huli mea',
'and'            => '&#32;a me',

'errorpagetitle'   => 'Hewa',
'returnto'         => 'Ho‘i iā $1.',
'tagline'          => 'Mai {{SITENAME}}',
'help'             => 'Kōkua',
'search'           => 'Huli',
'searchbutton'     => 'Huli',
'go'               => 'E huli',
'searcharticle'    => 'Hele',
'history'          => 'Mo‘olelo o ka ‘ao‘ao',
'history_short'    => 'Mo‘olelo',
'info_short'       => 'Hō‘ike',
'printableversion' => "Ke 'ano hiki ke pa'i",
'permalink'        => "Ka loulou pa'a",
'print'            => 'Pa‘i',
'edit'             => 'E ho‘opololei',
'create'           => 'Hana',
'editthispage'     => 'E ho‘opololei i kēia ‘ao‘ao',
'create-this-page' => 'Hana i keia ‘ao‘ao',
'delete'           => 'E kāpae',
'deletethispage'   => 'E kāpae i kēia mo‘olelo',
'undelete_short'   => 'Wehe-kāpae i {{PLURAL:$1|kekahi ho‘opololei|$1 ho‘opololei}}',
'protect'          => 'E ho‘omalu',
'protectthispage'  => 'E ho‘omalu i kēia ‘ao‘ao',
'unprotect'        => 'E wehe ho‘omalu',
'newpage'          => '‘Ao‘ao hou',
'talkpage'         => 'Kūkākūkā i keia ‘ao‘ao',
'talkpagelinktext' => 'Kūkākūkā',
'specialpage'      => '‘Ao‘ao kūikawā',
'personaltools'    => 'Nā mea hana pilikino',
'talk'             => 'Kūkākūkā',
'views'            => 'Nā nānaina',
'toolbox'          => 'Pahu mea hana',
'userpage'         => 'Nānā i ka ‘ao‘ao-mea ho‘ohana',
'projectpage'      => 'Nānā i ka ‘ao‘ao papahana',
'imagepage'        => 'Nānā i ka ‘ao‘ao pāpaho',
'mediawikipage'    => 'Nānā i ka ‘ao‘ao memo',
'templatepage'     => 'Nānā i ka ‘ao‘ao anakuhi',
'viewhelppage'     => 'Nānā i ka ‘ao‘ao kōkua',
'categorypage'     => 'Nānā i ka ‘ao‘ao mahele',
'viewtalkpage'     => 'Nānā i ke kūkākūkā',
'otherlanguages'   => "Ma nā leo 'ē a'e",
'redirectedfrom'   => '(Hoʻoili mai $1)',
'redirectpagesub'  => '‘Ao‘ao e alaka‘i ai',
'protectedpage'    => '‘Ao‘ao ho‘omalu',
'jumpto'           => 'Lele i:',
'jumptonavigation' => 'ho‘okele ‘ana',
'jumptosearch'     => 'huli',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'E pili ana iā {{SITENAME}}',
'aboutpage'            => 'Project:E pili ana',
'copyrightpagename'    => '{{SITENAME}} palapala ho‘okuleana',
'copyrightpage'        => '{{ns:project}}:Palapala ho‘okuleana',
'currentevents'        => 'Ka nū hou',
'currentevents-url'    => 'Project:Ka nū hou',
'disclaimers'          => 'Nā a‘o ‘ana laulā',
'disclaimerpage'       => 'Project:Nā a‘o ‘ana laulā',
'edithelp'             => 'Kōkua me ka ho‘ololi ‘ana',
'edithelppage'         => 'Help:Ho‘ololi',
'helppage'             => 'Help:Papa kuhikuhi',
'mainpage'             => 'Ka papa kinohi',
'mainpage-description' => 'Ka papa kinohi',
'policy-url'           => 'Project:Palapala',
'portal'               => 'Ka hui kaiaulu',
'portal-url'           => 'Project:Ka hui kaiaulu',
'privacy'              => 'Palapala pilikino',
'privacypage'          => 'Project:Palapala pilikino',

'badaccess' => 'Hewa ‘ae',

'ok'                      => 'Hiki nō',
'retrievedfrom'           => 'Loa‘a mai "$1"',
'youhavenewmessages'      => 'He $1 ($2) kāu.',
'newmessageslink'         => 'mau memo hou',
'newmessagesdifflink'     => 'loli hope',
'youhavenewmessagesmulti' => 'He mau memo kou ma $1',
'editsection'             => 'e ho‘opololei',
'editold'                 => 'e ho‘opololei',
'viewsourceold'           => 'nānā i ke kumu kanawai',
'editsectionhint'         => 'E ho‘opololei i ka paukū: $1',
'toc'                     => 'Papa kuhikuhi',
'showtoc'                 => 'hō‘ike',
'hidetoc'                 => 'hūnā',
'thisisdeleted'           => 'Nānā ai‘ole hō‘āla i $1?',
'viewdeleted'             => 'Nānā i $1?',
'restorelink'             => '{{PLURAL:$1|kekahi ho‘opololei kāpae|nā ho‘opololei kāpae $1}}',
'site-rss-feed'           => 'RSS Feed o $1',
'site-atom-feed'          => 'Atom Feed o $1',
'page-rss-feed'           => 'RSS Feed o "$1"',
'page-atom-feed'          => 'Atom Feed o "$1"',
'red-link-title'          => '$1 (‘a‘ole kākau ‘ia)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'akikala',
'nstab-user'      => 'Inoa mea ho‘ohana',
'nstab-media'     => 'Pāpaho',
'nstab-special'   => 'Kūikawā',
'nstab-project'   => 'Papahana',
'nstab-image'     => 'Waihona',
'nstab-mediawiki' => 'Memo',
'nstab-template'  => 'Anakuhi',
'nstab-help'      => 'Kōkua',
'nstab-category'  => 'Mahele',

# General errors
'error'               => 'Hewa',
'readonly'            => 'Laka ‘ia ka hōkeo ‘ikepili',
'missingarticle-diff' => '(Loli hope: $1, $2)',
'filedeleteerror'     => '‘A‘ole hiki ke kāpae i ka waihona "$1".',
'filenotfound'        => '‘A‘ole hiki ke loa‘a waihona "$1".',
'badtitle'            => 'Inoa ‘ino',
'viewsource'          => 'E nānā i ke kumu kanawai',
'viewsourcefor'       => 'no $1',
'cascadeprotected'    => 'Ho‘omalu ‘ia kēia ‘ao‘ao mai e ho‘opololei ana, no ka mea, hoʻokomo pū ‘ia ‘oia ma aia {{PLURAL:$1|‘ao‘ao|nā ‘ao‘ao}} i lalo, ho‘omalu ‘ia me ka "e wailele ana" koho:
$2',
'ns-specialprotected' => '‘A‘ole hiki ke ho‘opololei i nā ‘ao‘ao kūikawā',

# Login and logout pages
'logouttitle'               => 'E haʻalele ka mea hoʻohana',
'welcomecreation'           => '== E komo mai, $1! ==
Hoʻokumu ʻia i kou waihona.
Mai poina e loli kāu makemake ma {{SITENAME}}.',
'loginpagetitle'            => 'ʻEʻe mea hoʻohana',
'yourname'                  => "Inoa mea ho'ohana",
'yourpassword'              => 'ʻŌlelo hūnā:',
'yourpasswordagain'         => "Hua'ōlelo huna hou",
'remembermypassword'        => "Ho'omana'o ia'u",
'login'                     => 'E komo',
'nav-login-createaccount'   => 'E komo mai / E hana',
'loginprompt'               => 'Pono ʻoe e hoʻā i nā makana (cookies) no ka ʻeʻe ʻana i {{SITENAME}}.',
'userlogin'                 => 'E komo / E hana',
'logout'                    => "E ha'alele",
'userlogout'                => "E ha'alele",
'notloggedin'               => 'Mai ‘e‘e',
'nologin'                   => 'ʻAʻohe āu waihona? $1.',
'nologinlink'               => "Lilo i mea ho'ohana",
'createaccount'             => 'E hana',
'gotaccount'                => 'He waihona kou ʻē? $1.',
'gotaccountlink'            => 'E komo',
'createaccountmail'         => 'no ka leka uila',
'userexists'                => 'Lilo ka inoa mea ho‘ohana.
E koho i kekahi inoa, ke ‘olu‘olu.',
'youremail'                 => 'Leka uila:',
'username'                  => "Inoa mea ho'ohana:",
'yourrealname'              => 'Inoa maoli:',
'yourlanguage'              => 'Kou ʻōlelo:',
'yournick'                  => 'Inoa kapakapa:',
'email'                     => 'Leka uila',
'prefs-help-email-required' => 'Koina ka leka uila.',
'loginsuccesstitle'         => 'ʻEʻe kūleʻa',
'loginsuccess'              => "'''ʻEʻe ʻia ʻoe, ʻo \"\$1\", iā {{SITENAME}}.'''",
'nouserspecified'           => 'Pono ʻoe e kāhuakomo i ka ʻōlelo ʻeʻe.',
'wrongpassword'             => 'Hewa ka ʻōlelo hūnā.
E ʻoluʻolu, e kūlia hou.',
'wrongpasswordempty'        => 'Hakahaka ka ʻōlelo hūnā.
E ʻoluʻolu, e kūlia hou.',
'mailmypassword'            => 'Leka uila i ka huaʻōlelo huna',
'passwordremindertitle'     => "He 'ōlelo hūnā kūikawā no {{SITENAME}}",
'emailauthenticated'        => 'Ua hō‘oia ‘ia kāu leka uila ma ka lā $2 i ka hola $3.',
'emailconfirmlink'          => 'E hō‘oia i kāu leka uila',
'accountcreated'            => 'Ua lilo ‘ia ka mea ho‘ohana',
'accountcreatedtext'        => 'Ua lilo ‘ia ka mea ho‘ohana no $1.',
'loginlanguagelabel'        => "Kou 'ōlelo: $1",

# Password reset dialog
'newpassword'       => 'ʻŌlelo hūnā hou:',
'resetpass_success' => 'Ua loli ‘ia kāu hua‘ōlelo huna! E ‘e‘e iā‘oe...',

# Edit page toolbar
'bold_sample'     => 'Ho‘okā‘ele',
'bold_tip'        => 'Ho‘okā‘ele',
'italic_sample'   => 'Ho‘ohiō',
'italic_tip'      => 'Ho‘ohiō',
'link_sample'     => 'Inoa loulou',
'extlink_tip'     => 'Loulou kūwaho (e ho‘omana‘o i ka poʻo pāʻālua http://)',
'headline_sample' => 'Po‘o‘ōlelo',
'math_tip'        => 'Ha‘ilula makemakika (LaTeX)',
'media_tip'       => 'Loulou waihona',
'sig_tip'         => 'Kou kākau inoa ame ka manawa',
'hr_tip'          => 'Laina ‘ilikai (e ho‘ohana pākiko)',

# Edit pages
'summary'                => "Hō'ulu'ulu mana'o:",
'minoredit'              => "He mea i ho'opololei iki 'ia",
'watchthis'              => 'E nānā pono i kēia mea',
'savearticle'            => 'E mālama i ka mea',
'preview'                => 'Nāmua',
'showpreview'            => "E hō'ike i ka nāmua",
'showdiff'               => "E hō'ike hou",
'anoneditwarning'        => "'''Aʻo ʻana:''' ʻO ʻoe ʻaʻole ʻeʻe. E hoʻopaʻa ʻia ana kou IP ma \"he aha i hoʻololi ʻia ai\" o kēia ʻaoʻao.",
'blockedtitle'           => 'Ua ke‘a ‘ia ka mea ho‘ohana',
'blockednoreason'        => '‘a‘ohe kumu',
'blockedoriginalsource'  => "Aia ke kumu o '''$1'''
hō‘ike ‘ia i lalo:",
'blockededitsource'      => "Aia ka mo‘olelo o '''kou mau ho‘opololei''' i '''$1''' hō‘ike ‘ia i lalo:",
'loginreqlink'           => 'E komo',
'accmailtitle'           => 'Ua ho‘ouna ‘ia ka hua‘ōlelo huna',
'newarticle'             => '(Hou)',
'anontalkpagetext'       => "---''‘O kēia ke kūkākūkā no he mea ho‘ohana ‘a‘ohe i hō‘ike‘ia ka inoa i hana ʻia he mea ho‘ohana ai‘ole ‘a‘ole ho‘ohana ia. Pēlā, e pono mākou ke ho‘ohana ka wahi noho IP e hōʻoia ‘oia. Inā he mea ho‘ohana ‘a‘ohe i hō‘ike‘ia ka inoa ‘oe, ke ho‘olale nei ‘ia ‘oe [[Special:UserLogin|e hana he mea ho‘ohana ai‘ole e komo]].''",
'noarticletext'          => 'ʻAʻohe a kēia ʻaoʻao kikokikona.
Hiki iā ʻoe ke [[Special:Search/{{PAGENAME}}|huli no kēia poʻo ʻaoʻao]] i nā ʻaoʻao ʻē aʻe, <span class="plainlinks">ke [{{fullurl:SpecialLog|page={{urlencode:{{FULLPAGENAME}}}}}} huli i nā moʻolelo pili], a i ʻole ke [{{fullurl:{{FULLPAGENAME}}|action=edit}} hoʻololi i kēia ʻaoʻao]</span>.',
'previewnote'            => "'''‘O keia ka nāmua;
‘a‘ole i mālama ‘ia ka ho‘ololi!'''",
'editing'                => 'Ke ho‘ololi nei iā $1',
'editingsection'         => 'Ke ho‘opololei nei iā $1 (mahele)',
'editingcomment'         => 'Ke ho‘ololi nei iā $1 (mana‘o)',
'yourtext'               => 'Ko‘u ‘ōlelo',
'yourdiff'               => 'Nā mea ‘oko‘a',
'copyrightwarning'       => "Hoʻokuʻu nā mea lūlū iā {{SITENAME}} i ka $2 (no nā mea kikoʻī, ʻike ʻoe i $1).
Inā ʻaʻole ʻoe makemake i nā poʻe aʻe e loli i kou kākau ʻana a ʻaʻole ʻoe makemake hoʻomalele hou i kou mau loli, inā mai waiho kou mau loli ma ʻaneʻi.<br />
Ke hoʻohiki nei ʻoe iā kākou: ua kākau ʻoe i kēia kikokikona na ʻo ʻoe ponoʻī a i ʻole ua kope i kēia kikokikona mai ke kūmole kūʻokoʻa.
'''MAI WAIHO NĀ HANA PONOKOPE ME ʻOLE KA ʻAE!'''",
'protectedpagewarning'   => "'''A‘o ‘ana:  Ua laka ‘ia kēia ‘ao‘ao, pēlā, hiki i nā \"kahu\" ke ho‘opololei wale nō.'''",
'template-protected'     => '(ho‘omalu ‘ia)',
'template-semiprotected' => '(hapa-ho‘omalu ‘ia)',
'edittools'              => '<!-- Eia ka ‘ōlelo e hō‘ike ‘ia malalo o nā palapala ho‘ololi ame nā palapala ho‘ohui. -->',

# History pages
'currentrev'          => 'Kāmua hou',
'revisionasof'        => 'Ka loli ʻana ma $1',
'previousrevision'    => '← Kāmua mua',
'nextrevision'        => 'Kāmua hou →',
'currentrevisionlink' => 'Kāmua hou',
'cur'                 => 'hou',
'last'                => 'hope',
'page_first'          => 'mua',
'page_last'           => 'hope',
'deletedrev'          => '[ua kāpae ‘ia]',
'histfirst'           => 'Kahiko loa',
'histlast'            => 'Hou loa',
'historysize'         => '({{PLURAL:$1|1 ‘ai|$1 ‘ai}})',
'historyempty'        => '(‘ole)',

# Revision feed
'history-feed-item-nocomment' => '$1 ma $2', # user at time

# Revision deletion
'rev-delundel' => 'hō‘ike/hūnā',

# Diffs
'lineno'   => 'Laina $1:',
'editundo' => 'wehe',

# Search results
'noexactmatch'       => "'''‘A‘ohe mo‘olelo me ka inoa \"\$1\".''' Hiki iā‘oe ke [[:\$1|hana i keia ‘ao‘ao]].",
'prevn'              => 'mua $1',
'nextn'              => 'hope $1',
'viewprevnext'       => 'Nānā i nā ($1) ($2) ($3)',
'searchhelp-url'     => 'Help:Papa kuhikuhi',
'search-result-size' => '$1 ({{PLURAL:$2|1 huaʻōlelo|$2 mau huaʻōlelo}})',
'searchall'          => 'apau',
'powersearch'        => 'Huli',

# Preferences page
'preferences'       => "Ka'u makemake",
'mypreferences'     => 'Ka‘u makemake',
'changepassword'    => 'E loli i ka palapala hua‘ōlelo',
'skin-preview'      => 'Nāmua',
'prefs-rc'          => 'Nā loli hou',
'searchresultshead' => 'Huli',
'savedprefs'        => 'Ua mālama ‘ia kāu makemake',
'default'           => 'paʻamau',

# User rights
'userrights' => 'Ho‘oponopono ‘ana o nā kuleana', # Not used as normal message but as header for the special page itself

# Groups
'group-sysop'      => 'Nā kahu',
'group-bureaucrat' => 'Nā kuhina',
'group-all'        => '(āpau)',

'group-sysop-member'      => 'Kahu',
'group-bureaucrat-member' => 'Kuhina',

'grouppage-sysop' => '{{ns:project}}:Nā kahu',

# Recent changes
'recentchanges'   => 'Nā loli hou',
'rcnote'          => "ʻO {{PLURAL:$1|ka loli '''1'''|nā loli hope '''$1'''}} ma hope mai {{PLURAL:$2|ka lā ʻekahi|nā lā '''$2'''}} ma hope, ma $5, $4.",
'rcshowhideminor' => '$1 i nā ho‘opololei iki',
'rcshowhidebots'  => '$1 i nā lopako',
'rcshowhideliu'   => '$1 i nā inoa mea ho‘ohana',
'rcshowhideanons' => '$1 i nā ‘a‘ohe i hō‘ike‘ia ka inoa',
'rcshowhidemine'  => '$1 i ka‘u mau hoʻololi',
'diff'            => '‘oko‘a',
'hist'            => 'loli',
'hide'            => 'hūnā',
'show'            => 'hō‘ike',
'minoreditletter' => 'iki',
'newpageletter'   => 'hou',
'boteditletter'   => 'lopako',

# Recent changes linked
'recentchangeslinked' => "Nā loli hou 'ālike",

# Upload
'upload'            => "Ho'ohui i ka waihona",
'uploadbtn'         => "Ho'ohui i ka waihona",
'filedesc'          => "Hō'ulu'ulu mana'o",
'fileuploadsummary' => "Hō'ulu'ulu mana'o:",

# Special:ListFiles
'listfiles_name' => 'Inoa',

# File description page
'filehist'            => 'Mo‘olelo o ka waihona',
'filehist-current'    => 'o kēia manawa',
'filehist-datetime'   => 'Manawa',
'filehist-user'       => 'Mea ho‘ohana',
'filehist-dimensions' => 'Nā nui',
'filehist-filesize'   => 'Nui o ka waihona',
'filehist-comment'    => 'Manaʻo',
'imagelinks'          => 'Nā loulou faila',
'linkstoimage'        => 'Loulou {{PLURAL:$1|kekahi ‘ao‘ao|kēia mau ‘ao‘ao $1}} i kēia waihona:',

# Random page
'randompage' => 'He akikala kaulele',

# Statistics
'statistics' => 'Papa helu',

'brokenredirects-edit'   => '(e ho‘opololei)',
'brokenredirects-delete' => '(e kāpae)',

'withoutinterwiki-submit' => 'Hō‘ike',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|‘ai|‘ai}}',
'nlinks'            => '$1 {{PLURAL:$1|loulou|loulou}}',
'nmembers'          => '$1 {{PLURAL:$1|lālā|lālā}}',
'wantedcategories'  => 'Nā māhele makemake',
'shortpages'        => 'Nā ʻaoʻao pōkole',
'longpages'         => 'Nā ʻaoʻao lōʻihi',
'newpages'          => 'Nā ‘ao‘ao hou',
'newpages-username' => "Inoa mea ho'ohana:",
'ancientpages'      => 'Nā ‘ao‘ao kahiko loa',
'move'              => 'E ho‘ololi i ka inoa',
'movethispage'      => "E ho'ololi kēia",

# Special:Log
'log'           => 'Nā mo‘olelo',
'all-logs-page' => 'Nā moʻolelo āpau',

# Special:AllPages
'allpages'       => 'Nā ‘ao‘ao loa apau',
'alphaindexline' => '$1 i $2',
'nextpage'       => 'Mea aʻe ($1)',
'prevpage'       => 'Mea ma mua aʻe ($1)',
'allarticles'    => 'Nā mo‘olelo apau loa',
'allpagesprev'   => 'Mua',
'allpagesnext'   => 'Hope',
'allpagessubmit' => 'E huli',

# Special:Categories
'categories' => 'Nā mahele',

# Special:DeletedContributions
'deletedcontributions' => 'Nā ha‘awina o ka inoa mea ho‘ohana i kāpae ‘ia ai',

# Special:ListUsers
'listusers-submit' => 'Hō‘ike',

# E-mail user
'emailuser'    => 'E leka uila i kēia mea ho‘ohana',
'emailmessage' => 'Memo:',

# Watchlist
'watchlist'         => "Ka'u papa nānā pono",
'mywatchlist'       => 'Ka‘u papa nānā pono',
'watchlistfor'      => "(no '''$1''')",
'watch'             => 'E kia‘i',
'watchthispage'     => 'E nānā pono i kēia mea',
'unwatch'           => 'E wehe kia‘i',
'watchlist-details' => '{{PLURAL:$1|$1|$1}} a kāu papa nānā pono ʻaoʻao, me ke koe ʻana o nā ʻaoʻao kūkākūkā.',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ke kia‘i nei...',
'unwatching' => 'Ke wehe-kia‘i nei...',

'changed' => 'ua loli ‘ia',

# Delete
'deletepage'             => 'Kāpae ʻaoʻao',
'actioncomplete'         => 'Ua pau',
'deletedtext'            => 'Ua kāpae ʻo "<nowiki>$1</nowiki>".
E ʻike i $2 no ka papa o nā kāpae ʻana hou.',
'deletedarticle'         => 'ua kāpae ‘ia "[[$1]]"',
'dellogpage'             => 'Mo‘olelo kāpae',
'dellogpagetext'         => 'He helu o nā mea i kāpae ʻia hou i lalo.',
'deletionlog'            => 'mo‘olelo kāpae',
'deletecomment'          => 'Ke kumu e kāpae',
'delete-edit-reasonlist' => 'Ho‘opololei i nā kumu no ke kāpae ‘ana',

# Rollback
'rollbacklink' => 'ho‘i',

# Protect
'prot_1movedto2'         => 'Ua hoʻoneʻe ʻo [[$1]] iā [[$2]]',
'protect-default'        => '(paʻamau)',
'protect-cantedit'       => 'ʻAʻole ʻoe hoʻololi i nā pae malu o kēia ʻaoʻao no ka mea ʻaʻohe ʻae āu o ka hoʻopololei ʻana o kēia ʻaoʻao.',
'protect-expiry-options' => '2 hola:2 hours,1 lā:1 day,3 lā:3 days,1 pule:1 week,2 pule:2 weeks,1 mahina:1 month,3 mahina:3 months,6 mahina:6 months,1 makahiki:1 year,palena ʻole:infinite', # display1:time1,display2:time2,...

# Restrictions (nouns)
'restriction-edit' => 'E ho‘opololei',
'restriction-move' => "E ho'ololi i ka inoa",

# Namespace form on various pages
'blanknamespace' => '(‘ano nui)',

# Contributions
'contributions' => 'Nā ha‘awina o kēia mea ho‘ohana',
'mycontris'     => "He aha ka'u i lūlū ai",
'contribsub2'   => 'No $1 ($2)',
'uctop'         => '(wēkiu)',
'month'         => 'Mai mahina (ame mamua):',
'year'          => 'Mai makahiki (ame mamua):',

'sp-contributions-search' => 'Huli no nā haʻawina',

# What links here
'whatlinkshere'       => 'He aha e loulou iho ai',
'whatlinkshere-page'  => '‘Ao‘ao:',
'nolinkshere'         => "‘A‘ole he ‘ao‘ao e loulou ai iā '''[[:$1]]'''.",
'isredirect'          => 'ʻaoʻao hoʻoili ʻana',
'whatlinkshere-prev'  => '{{PLURAL:$1|mua|mua $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|hope|hope $1}}',
'whatlinkshere-links' => '← nā loulou',

# Block/unblock
'blockip'       => 'E ke‘a i kēia mea ho‘ohana',
'ipbexpiry'     => 'Pau āhea:',
'ipbreason'     => 'Kumu:',
'ipbsubmit'     => 'E ke‘a i kēia mea ho‘ohana',
'ipbother'      => 'ʻĒ aʻe manawa:',
'ipboptions'    => '2 hours:2 hours,1 day:1 day,3 days:3 days,1 week:1 week,2 weeks:2 weeks,1 month:1 month,3 months:3 months,6 months:6 months,1 year:1 year,infinite:infinite', # display1:time1,display2:time2,...
'badipaddress'  => 'Mana ‘ole ka wahi noho IP',
'anononlyblock' => '‘A‘ohe i hō‘ike‘ia ka inoa wale nō',
'blocklink'     => 'e keʻa',
'contribslink'  => 'nā ha‘awina',
'blockme'       => 'E ke‘a ia‘u',

# Move page
'move-page-legend'        => "E ho'ololi",
'movearticle'             => "E ho'ololi",
'newtitle'                => 'I ka inoa hou:',
'move-watch'              => 'E nānā pono i kēia mea',
'movepagebtn'             => "E ho'ololi",
'pagemovedsub'            => 'Kūleʻa ka neʻe ʻana',
'1movedto2'               => 'Ua hoʻoneʻe ʻo [[$1]] iā [[$2]]',
'movereason'              => 'Kumu:',
'delete_and_move'         => 'E kāpae a e ho‘ololi i ka inoa',
'delete_and_move_confirm' => '‘Ae, e kāpae i ka ‘ao‘ao',

# Export
'export-addcat' => 'Ho‘ohui',

# Namespace 8 related
'allmessages'        => 'Nā kauoha o ke kahua',
'allmessagesname'    => 'Inoa',
'allmessagesdefault' => 'Kikokikona pa‘amau',
'allmessagescurrent' => 'Kikokikona i kēia manawa',

# Thumbnails
'thumbnail-more' => 'ho‘onui',

# Tooltip help for the actions
'tooltip-pt-userpage'      => 'Kāu inoa mea ho‘ohana',
'tooltip-pt-mytalk'        => 'Kāu ‘aoʻao ʻōlelo',
'tooltip-pt-preferences'   => 'ka‘u makemake',
'tooltip-pt-watchlist'     => 'Ka papa o nā ʻaoʻao o kou nānā ʻana no nā loli',
'tooltip-pt-mycontris'     => 'Kāu mau ha‘awina',
'tooltip-pt-login'         => 'Pai ‘ia ‘oe e ‘e‘e, akā, ‘a‘ole he koina.',
'tooltip-pt-logout'        => 'e ha‘alele',
'tooltip-ca-talk'          => 'Kūkākūkā e pili ana i kekahi ‘ao‘ao.',
'tooltip-ca-edit'          => 'Hiki iā‘oe ke ho‘opololei i kēia ‘ao‘ao. Imua o ka mālama, ho‘ohana i ka nāmua, ke ‘olu‘olu.',
'tooltip-ca-viewsource'    => 'Pale ʻia kēia ʻaoʻao.
Hiki iā ʻoe ke ʻikena i kāna molekumu.',
'tooltip-ca-protect'       => 'Ho‘omalu i keia ‘ao‘ao',
'tooltip-ca-delete'        => 'E kāpae i kēia mo‘olelo',
'tooltip-ca-move'          => 'E ne‘e i kēia mo‘olelo',
'tooltip-search'           => 'Huli iā {{SITENAME}}',
'tooltip-n-mainpage'       => 'Hele i ka papa kinohi',
'tooltip-n-portal'         => 'E pili ana i ka pelokeka, he aha e hana',
'tooltip-n-currentevents'  => 'ʻIke i nā nū hou',
'tooltip-n-recentchanges'  => 'Nā loli hou ma ka wiki.',
'tooltip-n-randompage'     => 'Ho‘ouka i he akikala kaulele',
'tooltip-n-help'           => 'Ka wahi e kōkua ai iā‘oe.',
'tooltip-t-whatlinkshere'  => 'Ka papa o nā ‘ao‘ao āpau e loulou mai',
'tooltip-t-upload'         => 'Ho‘ouka i nā waihona',
'tooltip-t-specialpages'   => 'Helu o nā papa nui apau',
'tooltip-ca-nstab-project' => 'Nānā i ka ‘ao‘ao papahana',
'tooltip-ca-nstab-help'    => 'Nānaina i ka ʻaoʻao kōkua',
'tooltip-minoredit'        => 'Wae i kēia hoʻopololei me he hoʻopololei iki',
'tooltip-save'             => 'Mālama i kāu ho‘opololei',

# Media information
'show-big-image' => 'Miomio piha',

# Special:NewFiles
'ilsubmit' => 'Huli',

# External editor support
'edit-externally-help' => '(E ʻike i nā [http://www.mediawiki.org/wiki/Manual:External_editors aʻo palapala no ka hoʻokuene ʻana])',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'apau',
'imagelistall'     => 'āpau',
'watchlistall2'    => 'apau',
'namespacesall'    => 'apau',
'monthsall'        => 'āpau',

# action=purge
'confirm_purge_button' => 'Hiki nō',

# Multipage image navigation
'imgmultipageprev' => '← mea ma mua aʻe',
'imgmultipagenext' => 'mea aʻe →',

# Table pager
'table_pager_next' => 'Mea aʻe',
'table_pager_prev' => 'Mea ma mua aʻe',

# Auto-summaries
'autosumm-replace' => "Ke pani nei i ka ‘ao‘ao me '$1'",
'autoredircomment' => 'Ke alaka‘i nei hou i [[$1]]',
'autosumm-new'     => 'Ka ‘ao‘ao hou: $1',

# Live preview
'livepreview-loading' => 'Ke ho‘ouka nei…',

# Watchlist editor
'watchlistedit-normal-title' => 'E ho‘opololei i ka‘u papa nānā pono',

# Special:Version
'version-specialpages' => 'Nā ‘ao‘ao kūikawā',

# Special:SpecialPages
'specialpages' => 'Nā ‘ao‘ao kūikawā',

);

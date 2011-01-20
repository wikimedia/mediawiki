<?php
/** Hawaiian (Hawai`i)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Kalani
 * @author Kolonahe
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

$specialPageAliases = array(
	'Userlogout'                => array( 'Haʻalele', 'Haalele' ),
	'CreateAccount'             => array( 'Kāinoa', 'Kainoa' ),
	'Preferences'               => array( 'Makemake' ),
	'Watchlist'                 => array( 'PapaNānāPono', 'PapaNanaPono' ),
	'Recentchanges'             => array( 'NāLoliHou', 'NaLoliHou' ),
	'Upload'                    => array( 'Hoʻouka', 'Hoouka' ),
	'Randompage'                => array( 'Kaulele' ),
	'Shortpages'                => array( 'ʻAoʻaoPōkole', 'AoaoPokole' ),
	'Longpages'                 => array( 'ʻAoʻaoLoa', 'AoaoLoa' ),
	'Newpages'                  => array( 'ʻAoʻaoHou', 'AoaoHou' ),
	'Ancientpages'              => array( 'ʻAoʻaoKahiko', 'AoaoKahiko' ),
	'Specialpages'              => array( 'PapaNui' ),
	'Contributions'             => array( 'Haʻawina', 'Haawina' ),
	'Emailuser'                 => array( 'LekaUila' ),
	'Movepage'                  => array( 'HoʻoneʻeʻAoʻao', 'HooneeAoao' ),
	'Categories'                => array( 'Māhele', 'Mahele' ),
	'Mypage'                    => array( 'KaʻuʻAoʻao', 'KauAoao' ),
	'Mytalk'                    => array( 'KaʻuKūkākūkā', 'KauKukakuka' ),
	'Mycontributions'           => array( 'KaʻuHaʻawina', 'KauHaawina' ),
	'Search'                    => array( 'Huli' ),
);

$magicWords = array(
	'currentmonth'          => array( '1', 'KĒIAMAHINA', 'KEIAMAHINA', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'KĒIAINOAMAHINA', 'KEIAINOAMAHINA', 'CURRENTMONTHNAME' ),
	'currentday'            => array( '1', 'KĒIALĀ', 'KEIALA', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'KĒIALĀ2', 'KEIALA2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'KĒIAINOALĀ', 'KEIAINOALA', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'KĒIAMAKAHIKI', 'KEIAMAKAHIKI', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'KĒIAMANAWA', 'KEIAMANAWA', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'KĒIAHOLA', 'KEIAHOLA', 'CURRENTHOUR' ),
	'numberofpages'         => array( '1', 'HELUʻAOʻAO', 'HELUAOAO', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'HELUMEA', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'HELUWAIHONA', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'HELUMEAHOʻOHANA', 'HELUMEAHOOHANA', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'HELULOLI', 'NUMBEROFEDITS' ),
	'pagename'              => array( '1', 'INOAʻAOʻAO', 'INOAAOAO', 'PAGENAME' ),
	'img_right'             => array( '1', 'ʻākau', 'ākau', 'akau', 'right' ),
	'img_left'              => array( '1', 'hema', 'left' ),
	'img_none'              => array( '1', 'ʻaʻohe', 'aohe', 'none' ),
	'img_link'              => array( '1', 'loulou=$1', 'link=$1' ),
	'currentweek'           => array( '1', 'KĒIAPULE', 'KEIAPULE', 'CURRENTWEEK' ),
	'language'              => array( '0', '#ʻŌLELO', '#ŌLELO', '#OLELO', '#LANGUAGE:' ),
	'numberofadmins'        => array( '1', 'HELUKAHU', 'NUMBEROFADMINS' ),
);

$messages = array(
# User preference toggles
'tog-underline'            => 'Kahalalo i nā loulou:',
'tog-justify'              => 'Ho‘okaulihi i nā paukū',
'tog-hideminor'            => 'E hūnā i nā ho‘opololei iki ma nā loli hou',
'tog-editondblclick'       => 'Ho‘opololei i nā ‘ao‘ao ma ke kōmi pālua (JavaScript)',
'tog-showtoc'              => 'Hō‘ike i ka papa kuhikuhi',
'tog-rememberpassword'     => 'Hoʻomanaʻo iaʻu ma kēia lolo uila (no ka palena nui o $1 {{PLURAL:$1|lā|mau lā}})',
'tog-watchcreations'       => 'Ho‘ohui i nā ‘ao‘ao i hana ai au i ka‘u papa nānā pono',
'tog-watchdefault'         => 'Ho‘ohui i nā ‘ao‘ao i ho‘opololei ai au i ka‘u papa nānā pono',
'tog-watchmoves'           => 'Ho‘ohui i nā ‘ao‘ao i ne‘e ai au i ka‘u papa nānā pono',
'tog-watchdeletion'        => 'Ho‘ohui i nā ‘ao‘ao i kāpae ai au i ka‘u papa nānā pono',
'tog-previewontop'         => 'Hō‘ike i ka nāmua mamua o ke kau ho‘opololei',
'tog-previewonfirst'       => 'Hō‘ike i ka nāmua ma ka ho‘ololi mua',
'tog-enotifwatchlistpages' => 'Ke loli kekahi ‘ao‘ao ma ka‘u papa nānā pono, leka uila ia‘u',
'tog-enotifusertalkpages'  => 'Ke loli ka‘u ʻaoʻao kūkākūkā, leka uila ia‘u',
'tog-enotifminoredits'     => 'No nā ho‘opololei ‘ana, leka uila ia‘u',
'tog-enotifrevealaddr'     => 'Hō‘ike i ko‘u leka uila ma nā leka uila hō‘ike',
'tog-shownumberswatching'  => 'Hō‘ike i ka heluna o nā mea ho‘ohana e nānā ai',
'tog-fancysig'             => 'Nā kākau inoa kūlohelohe (‘a‘ole me ka loulou hana nona iho)',
'tog-forceeditsummary'     => 'Ke kāhuakomo au i kekahi ho‘ulu‘ulu mana‘o ‘ole, ha‘i mai iaʻu',
'tog-watchlisthideown'     => 'Hūnā i ko‘u mau ho‘ololi ma ka papa nānā pono',
'tog-watchlisthidebots'    => 'Hūnā i nā ho‘opololei ‘ana o nā lopako mai ka papa nānā pono',
'tog-watchlisthideminor'   => 'E hūnā i nā ho‘ololi iki ma ka papa nānā pono',
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
'apr'           => 'ʻAp',
'may'           => 'Mei',
'jun'           => 'Iun',
'jul'           => 'Iul',
'aug'           => 'ʻAuk',
'sep'           => 'Kep',
'oct'           => 'ʻOk',
'nov'           => 'Now',
'dec'           => 'Kek',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|Mahele|Nā mahele}}',
'category_header'          => 'Nā ʻaoʻao i loko o ka mahele "$1"',
'subcategories'            => 'Nā māhele lalo',
'category-media-header'    => 'Nā pāpaho i loko o ka mahele "$1"',
'category-empty'           => "''‘A‘ohe mo‘olelo a me pāpaho o kēia mahele i kēia manawa.''",
'hidden-categories'        => '{{PLURAL:$1|Mahele hūnā|Nā mahele hūnā}}',
'hidden-category-category' => 'Nā mahele hūnā',
'listingcontinuesabbrev'   => '(ho‘omau ‘ia)',

'mainpagetext' => "'''Ua pono ka ho‘ouka ‘ana o MediaWiki.'''",

'about'         => 'E pili ana',
'newwindow'     => '(wehe i loko o kekahi pukaaniani ʻē aʻe)',
'cancel'        => 'Ho‘ōki',
'moredotdotdot' => 'Hou...',
'mypage'        => 'Ko‘u ‘ao‘ao',
'mytalk'        => 'Ka‘u kūkākūkā',
'anontalk'      => 'Ke kūkākūkā no kēia IP',
'navigation'    => 'Ka hoʻokele ʻana',
'and'           => '&#32;a me',

# Cologne Blue skin
'qbfind'         => 'Loa‘a',
'qbedit'         => 'E ho‘ololi',
'qbpageoptions'  => 'Kēia ‘ao‘ao',
'qbpageinfo'     => 'Pō‘aiapili',
'qbmyoptions'    => 'Ka‘u mau ‘ao‘ao',
'qbspecialpages' => 'Nā ‘ao‘ao kūikawā',

# Vector skin
'vector-action-delete'  => 'E holoi',
'vector-action-move'    => 'Neʻe',
'vector-action-protect' => 'Hoʻomalu',
'vector-view-edit'      => 'E hoʻololi',
'vector-view-view'      => 'Heluhelu',

'errorpagetitle'   => 'Hewa',
'returnto'         => 'Ho‘i iā $1.',
'tagline'          => 'Mai {{SITENAME}}',
'help'             => 'Kōkua',
'search'           => 'Huli',
'searchbutton'     => 'Huli',
'go'               => 'E huli',
'searcharticle'    => 'Hele',
'history'          => 'Mo‘olelo o ka ‘ao‘ao',
'history_short'    => 'Mōʻaukala',
'info_short'       => 'Hō‘ike',
'printableversion' => 'Mana paʻi pono',
'permalink'        => 'Ka loulou paʻa',
'print'            => 'Pa‘i',
'view'             => 'Nānā',
'edit'             => 'E ho‘ololi',
'create'           => 'Hana',
'editthispage'     => 'E ho‘opololei i kēia ‘ao‘ao',
'create-this-page' => 'Hana i keia ‘ao‘ao',
'delete'           => 'E kāpae',
'deletethispage'   => 'E kāpae i kēia mo‘olelo',
'undelete_short'   => 'Wehe-kāpae i {{PLURAL:$1|kekahi ho‘opololei|$1 ho‘opololei}}',
'protect'          => 'E ho‘omalu',
'protect_change'   => 'hoʻololi',
'protectthispage'  => 'E ho‘omalu i kēia ‘ao‘ao',
'unprotect'        => 'E wehe ho‘omalu',
'newpage'          => '‘Ao‘ao hou',
'talkpage'         => 'Kūkākūkā i keia ‘ao‘ao',
'talkpagelinktext' => 'Kūkākūkā',
'specialpage'      => '‘Ao‘ao kūikawā',
'personaltools'    => 'Nā mea hana ponoʻī',
'postcomment'      => 'Māhele hou',
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
'otherlanguages'   => 'Ma nā leo ʻē aʻe',
'redirectedfrom'   => '(Hoʻoili mai $1)',
'redirectpagesub'  => '‘Ao‘ao e alaka‘i ai',
'lastmodifiedat'   => 'Ua hoʻololi ʻia kēia ʻaoʻao ma ka lā $1, i ka manawa $2.',
'protectedpage'    => '‘Ao‘ao ho‘omalu',
'jumpto'           => 'Lele i:',
'jumptonavigation' => 'ka ho‘okele ‘ana',
'jumptosearch'     => 'huli',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'No {{SITENAME}}',
'aboutpage'            => 'Project:E pili ana',
'copyrightpage'        => '{{ns:project}}:Palapala ho‘okuleana',
'currentevents'        => 'Ka nū hou',
'currentevents-url'    => 'Project:Ka nū hou',
'disclaimers'          => 'Palapala hoʻokuʻu kuleana',
'disclaimerpage'       => 'Project:Palapala hoʻokuʻu kuleana',
'edithelp'             => 'Kōkua me ka ho‘ololi ‘ana',
'edithelppage'         => 'Help:Ho‘ololi',
'helppage'             => 'Help:Papa kuhikuhi',
'mainpage'             => 'Papa kinohi',
'mainpage-description' => 'Ka papa kinohi',
'policy-url'           => 'Project:Palapala',
'portal'               => 'Ka hui kaiaulu',
'portal-url'           => 'Project:Ka hui kaiaulu',
'privacy'              => 'Kulekele palekana ʻikepili pilikino',
'privacypage'          => 'Project:Palapala pilikino',

'badaccess' => 'Hewa me ka ‘ae',

'ok'                      => 'Hiki nō',
'retrievedfrom'           => 'Kiʻi ʻia mai "$1"',
'youhavenewmessages'      => 'He $1 ($2) kāu.',
'newmessageslink'         => 'mau memo hou',
'newmessagesdifflink'     => 'loli hope',
'youhavenewmessagesmulti' => 'He mau memo kou ma $1',
'editsection'             => 'e ho‘ololi',
'editold'                 => 'e ho‘ololi',
'viewsourceold'           => 'nānā i ke kumu kanawai',
'editlink'                => 'hoʻololi',
'viewsourcelink'          => 'nānā i ka molekumu',
'editsectionhint'         => 'E hoʻololi i ka paukū: $1',
'toc'                     => 'Papa kuhikuhi',
'showtoc'                 => 'hō‘ike',
'hidetoc'                 => 'hūnā',
'thisisdeleted'           => 'Nānā ai‘ole hō‘āla i $1?',
'viewdeleted'             => 'Nānā i $1?',
'restorelink'             => '{{PLURAL:$1|kekahi ho‘opololei kāpae|nā ho‘opololei kāpae $1}}',
'site-rss-feed'           => 'Hulu RSS o $1',
'site-atom-feed'          => 'Hulu Atom o $1',
'page-rss-feed'           => 'Hulu RSS o "$1"',
'page-atom-feed'          => 'Hulu Atom o "$1"',
'red-link-title'          => '$1 (ʻaʻole i kākau ʻia)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ʻaoʻao',
'nstab-user'      => 'Inoa mea ho‘ohana',
'nstab-media'     => 'Pāpaho',
'nstab-special'   => 'Papa nui',
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
'viewsource'          => 'E nānā i ka molekumu',
'viewsourcefor'       => 'no $1',
'cascadeprotected'    => 'Ho‘omalu ‘ia kēia ‘ao‘ao mai e ho‘opololei ana, no ka mea, hoʻokomo pū ‘ia ‘oia ma aia {{PLURAL:$1|‘ao‘ao|nā ‘ao‘ao}} i lalo, ho‘omalu ‘ia me ka "e wailele ana" koho:
$2',
'ns-specialprotected' => '‘A‘ole hiki ke ho‘opololei i nā ‘ao‘ao kūikawā',

# Login and logout pages
'welcomecreation'         => '== E komo mai, $1! ==
Ua hoʻokumu ʻia kāu moʻokāki.
Mai poina e hoʻololi i [[Special:Preferences|kāu makemake ma {{SITENAME}}]].',
'yourname'                => "Inoa mea ho'ohana",
'yourpassword'            => 'ʻŌlelo hūnā:',
'yourpasswordagain'       => "Hua'ōlelo huna hou",
'remembermypassword'      => 'Hoʻomanaʻo iaʻu ma kēia lolo uila (no ka palena nui o $1 {{PLURAL:$1|lā|mau lā}})',
'login'                   => 'ʻEʻe',
'nav-login-createaccount' => 'ʻEʻe / E kāinoa',
'loginprompt'             => 'Pono ʻoe e hoʻā i nā makana (cookies) no ka ʻeʻe ʻana i {{SITENAME}}.',
'userlogin'               => 'ʻEʻe / E kāinoa',
'userloginnocreate'       => 'ʻEʻe',
'logout'                  => 'E haʻalele',
'userlogout'              => 'E haʻalele',
'notloggedin'             => 'Mai ‘e‘e',
'nologin'                 => "ʻAʻohe āu moʻokāki? '''$1'''.",
'nologinlink'             => 'E kāinoa',
'createaccount'           => 'E hana',
'gotaccount'              => "He moʻokāki kāu? '''$1'''.",
'gotaccountlink'          => 'ʻEʻe',
'createaccountmail'       => 'no ka leka uila',
'createaccountreason'     => 'Kumu:',
'badretype'               => 'ʻAʻole like nā ʻōlelo hūnā āu i hoʻokomo ai',
'userexists'              => 'Lilo ka inoa mea ho‘ohana.
E koho i kekahi inoa, ke ‘olu‘olu.',
'loginerror'              => 'Hewa ʻeʻe',
'loginsuccesstitle'       => 'ʻEʻe kūleʻa',
'loginsuccess'            => "'''ʻEʻe ʻia ʻoe, ʻo \"\$1\", iā {{SITENAME}}.'''",
'nouserspecified'         => 'Pono ʻoe e kāhuakomo i ka ʻōlelo ʻeʻe.',
'wrongpassword'           => 'Hewa ka ʻōlelo hūnā.
E ʻoluʻolu, e kūlia hou.',
'wrongpasswordempty'      => 'Hakahaka ka ʻōlelo hūnā.
E ʻoluʻolu, e kūlia hou.',
'mailmypassword'          => 'Leka uila i ka huaʻōlelo hūnā hou',
'passwordremindertitle'   => "He 'ōlelo hūnā kūikawā no {{SITENAME}}",
'emailauthenticated'      => 'Ua hō‘oia ‘ia kāu leka uila ma ka lā $2 i ka hola $3.',
'emailconfirmlink'        => 'E hō‘oia i kāu leka uila',
'accountcreated'          => 'Ua lilo ‘ia ka mea ho‘ohana',
'accountcreatedtext'      => 'Ua lilo ‘ia ka mea ho‘ohana no $1.',
'loginlanguagelabel'      => "Kou 'ōlelo: $1",

# JavaScript password checks
'password-strength-bad'        => 'ʻIno',
'password-strength-mediocre'   => 'ʻaʻole maikaʻi loa',
'password-strength-acceptable' => 'ʻaʻole maikaʻi',
'password-strength-good'       => 'maikaʻi',

# Password reset dialog
'newpassword'       => 'ʻŌlelo hūnā hou:',
'resetpass_success' => 'Ua loli ‘ia kāu hua‘ōlelo huna! E ‘e‘e iā‘oe...',

# Edit page toolbar
'bold_sample'     => 'Ho‘okā‘ele',
'bold_tip'        => 'Ho‘okā‘ele',
'italic_sample'   => 'Ho‘ohiō',
'italic_tip'      => 'Ho‘ohiō',
'link_sample'     => 'Inoa loulou',
'link_tip'        => 'Loulou loko wahi',
'extlink_tip'     => 'Loulou kūwaho (e ho‘omana‘o i ka poʻo pāʻālua http://)',
'headline_sample' => 'Po‘o‘ōlelo',
'math_tip'        => 'Ha‘ilula makemakika (LaTeX)',
'media_tip'       => 'Loulou waihona',
'sig_tip'         => 'Kou kākau inoa a me ka manawa',
'hr_tip'          => 'Laina ‘ilikai (e ho‘ohana pākiko)',

# Edit pages
'summary'                          => 'Hōʻuluʻulu manaʻo:',
'subject'                          => 'Kumumanaʻo/poʻo laina:',
'minoredit'                        => "He mea i ho'opololei iki 'ia",
'watchthis'                        => 'E nānā pono i kēia mea',
'savearticle'                      => 'E mālama i ka mea',
'preview'                          => 'Nāmua',
'showpreview'                      => "E hō'ike i ka nāmua",
'showdiff'                         => "E hō'ike hou",
'anoneditwarning'                  => "'''Ke aʻo ʻana:''' ʻAʻole ʻoe ʻeʻe.
E hoʻopaʻa ʻia ana kou IP ma ko kēia ʻaoʻao mōʻaukala.",
'blockedtitle'                     => 'Ua ke‘a ‘ia ka mea ho‘ohana',
'blockednoreason'                  => '‘a‘ohe kumu',
'blockedoriginalsource'            => "Aia ke kumu o '''$1'''
hō‘ike ‘ia i lalo:",
'blockededitsource'                => "Aia ka mo‘olelo o '''kou mau ho‘opololei''' i '''$1''' hō‘ike ‘ia i lalo:",
'loginreqlink'                     => 'ʻeʻe',
'accmailtitle'                     => 'Ua ho‘ouna ‘ia ka hua‘ōlelo huna',
'newarticle'                       => '(Hou)',
'anontalkpagetext'                 => "----''‘O kēia ka ʻaoʻao kūkākūkā no kekahi mea ho‘ohana me ka moʻokāki ʻole. No laila, pono mākou e ho‘ohana i ka wahi noho IP no ka hōʻoia ʻana iā ia.
Hiki i kekahi mau mea hoʻohana ke hoʻokaʻana i kēia wahi noho IP.
Inā he mea ho‘ohana ʻoe a ua haʻi ʻia kekahi manaʻo iā ʻoe, [[Special:UserLogin/signup|e hoʻokumu ʻia kekahi moʻokāki]] a i ʻole [[Special:UserLogin|e ʻeʻe]].''",
'noarticletext'                    => 'ʻAʻohe kikokikona a kēia ʻaoʻao.
Hiki iā ʻoe ke [[Special:Search/{{PAGENAME}}|huli no kēia inoa ʻaoʻao]] i nā ʻaoʻao ʻē aʻe, <span class="plainlinks">[{{fullurl:SpecialLog|page={{FULLPAGENAMEE}}}} huli i nā moʻolelo pili], a i ʻole [{{fullurl:{{FULLPAGENAME}}|action=edit}} hoʻololi i kēia ʻaoʻao]</span>.',
'previewnote'                      => "'''‘O keia ka nāmua;
‘a‘ole i mālama ‘ia ka ho‘ololi!'''",
'editing'                          => 'Ke ho‘ololi nei iā $1',
'editingsection'                   => 'Ke ho‘opololei nei iā $1 (mahele)',
'editingcomment'                   => 'Ke ho‘ololi nei iā $1 (paukū hou)',
'yourtext'                         => 'Ko‘u ‘ōlelo',
'yourdiff'                         => 'Nā mea ‘oko‘a',
'copyrightwarning'                 => "Hoʻokuʻu ʻia nā mea lūlū iā {{SITENAME}} ma lalo o ka $2 (no nā mea kikoʻī, kele iā $1).
Inā ʻaʻole ʻoe makemake i ka hoʻololi ʻana kūnoa i kou kākau ʻana a ʻaʻole ʻoe makemake i ka hoʻomalele ʻana i kāu mau loli, inā mai kākau ma ʻaneʻi.<br />
Ke hoʻohiki nei ʻoe iā kākou: nou i kākau i kēia kikokikona a i ʻole nou i kope i kēia kikokikona mai ke kūmole kūʻokoʻa.
'''MAI KĀKAU I NĀ KIKOKIKONA PONOKOPE E NELE AI KA ʻAE!'''",
'protectedpagewarning'             => "'''A‘o ‘ana:  Ua laka ‘ia kēia ‘ao‘ao, pēlā, hiki i nā \"kahu\" ke ho‘opololei wale nō.'''",
'templatesusedpreview'             => 'Hoʻohana ʻia kēia {{PLURAL:$1|anakuhi|mau anakuhi}} i kēia nāmua:',
'template-protected'               => '(ho‘omalu ‘ia)',
'template-semiprotected'           => '(hapa-ho‘omalu ‘ia)',
'edittools'                        => '<!-- Eia ka ‘ōlelo e hō‘ike ‘ia malalo o nā palapala ho‘ololi ame nā palapala ho‘ohui. -->',
'permissionserrorstext-withaction' => 'ʻAʻohe ou ʻae no $2, no {{PLURAL:$1|ia mea|no ia mau mea}}:',

# "Undo" feature
'undo-success' => 'Hiki iā ʻoe ke hoʻihoʻi mai i kēia loli ʻana.
E ʻoluʻolu, e hōʻoia i ka hoʻokūkū ʻana i lalo, a laila, e mālama i nā loli i lalo no ka hoʻopau ʻana i ka hoʻihoʻi mai ʻana i ka loli.',
'undo-summary' => 'Hoʻihoʻi mai i ke kāmua $1 na [[Special:Contributions/$2|$2]] ([[User talk:$2|kūkākūkā]])',

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
'histfirst'           => 'Kahiko loa',
'histlast'            => 'Hou loa',
'historysize'         => '({{PLURAL:$1|1 ‘ai|$1 ‘ai}})',
'historyempty'        => '(‘ole)',

# Revision feed
'history-feed-item-nocomment' => '$1 ma $2',

# Revision deletion
'rev-delundel'          => 'hō‘ike/hūnā',
'revdelete-radio-same'  => '(mai hoʻololi)',
'revdelete-radio-set'   => 'ʻAe',
'revdelete-radio-unset' => 'ʻAʻole',
'revdel-restore'        => 'hoʻololi ka nānā ʻana',

# Merge log
'revertmerge' => 'Mai hoʻokuʻi pū',

# Diffs
'difference' => '(Ka ʻokoʻa ma waena o nā hoʻololi)',
'lineno'     => 'Laina $1:',
'editundo'   => 'hoʻihoʻi mai',

# Search results
'searchresults'             => 'Nā hualoaʻa',
'searchresults-title'       => 'Nā hualoaʻa no "$1"',
'searchresulttext'          => 'No kekahi ʻike hou aku e pili ana i ka huli ʻana iā {{SITENAME}}, kele i [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => 'Ua huli ʻoe no \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|nā ʻaoʻao a pau i hoʻomaka me "$1"]]{{int:pipe-separator}} [[Special:WhatLinksHere/$1|nā ʻaoʻao a pau e loulou ai i "$1"]])',
'searchsubtitleinvalid'     => "Ua huli ʻoe iā '''$1'''",
'notitlematches'            => 'ʻAʻohe inoa ʻaoʻao e like me ka huli ʻana',
'prevn'                     => '{{PLURAL:$1|$1}} ma mua',
'nextn'                     => '{{PLURAL:$1|$1}} ma hope',
'viewprevnext'              => 'Nānā i nā ($1 {{int:pipe-separator}} $2) ($3)',
'searchhelp-url'            => 'Help:Papa kuhikuhi',
'search-result-size'        => '$1 ({{PLURAL:$2|1 huaʻōlelo|$2 huaʻōlelo}})',
'search-section'            => '(māhele $1)',
'search-suggest'            => 'ʻO kēia paha kou manaʻo: $1',
'search-interwiki-caption'  => 'Nā pāhana ʻē aʻe',
'search-mwsuggest-enabled'  => 'me nā manaʻo kōkua',
'search-mwsuggest-disabled' => 'ʻaʻohe manaʻo kōkua',
'searchall'                 => 'apau',
'powersearch'               => 'Hulina kūlana kiʻekiʻe',
'powersearch-legend'        => 'Hulina kūlana kiʻekiʻe',
'powersearch-ns'            => 'Huli i loko o nā wahi inoa:',
'powersearch-field'         => 'Huli no',

# Preferences page
'preferences'               => 'Kaʻu makemake',
'mypreferences'             => 'Ka‘u makemake',
'changepassword'            => 'E loli i ka palapala hua‘ōlelo',
'prefs-skin'                => 'ʻIli',
'skin-preview'              => 'Nāmua',
'prefs-math'                => 'Makemakika',
'datedefault'               => 'ʻAʻohe makemake',
'prefs-datetime'            => 'Ka lā a me ka hola',
'prefs-personal'            => 'ʻAoʻao ʻike mea hoʻohana',
'prefs-rc'                  => 'Nā loli hou',
'prefs-watchlist'           => 'Helu nānā',
'prefs-watchlist-days'      => 'Nā lā e hōʻike ana i ka helu nānā:',
'prefs-watchlist-days-max'  => 'ʻEhiku lā ka palena nui',
'saveprefs'                 => 'Mālama',
'searchresultshead'         => 'Huli',
'savedprefs'                => 'Ua mālama ‘ia kāu makemake',
'timezoneregion-africa'     => 'ʻApelika',
'timezoneregion-america'    => 'Amelika',
'timezoneregion-antarctica' => 'ʻAneʻālika',
'timezoneregion-arctic'     => 'ʻĀlika',
'timezoneregion-asia'       => 'ʻĀkia',
'timezoneregion-atlantic'   => 'Moana ʻAkelanika',
'timezoneregion-australia'  => 'ʻAukekulelia',
'timezoneregion-europe'     => 'ʻEulopa',
'timezoneregion-indian'     => 'Moana ʻIniana',
'timezoneregion-pacific'    => 'Moana Pakipika',
'default'                   => 'paʻamau',
'youremail'                 => 'Leka uila:',
'username'                  => "Inoa mea ho'ohana:",
'yourrealname'              => 'Inoa maoli:',
'yourlanguage'              => 'Kou ʻōlelo:',
'yournick'                  => 'Inoa kapakapa:',
'yourgender'                => 'Keka:',
'gender-male'               => 'Kāne',
'gender-female'             => 'Wahine',
'email'                     => 'Leka uila',
'prefs-help-email-required' => 'Koina ka leka uila.',

# User rights
'userrights' => 'Ho‘oponopono ‘ana o nā kuleana',

# Groups
'group-sysop'      => 'Nā kahu',
'group-bureaucrat' => 'Nā kuhina',
'group-all'        => '(āpau)',

'group-sysop-member'      => 'Kahu',
'group-bureaucrat-member' => 'Kuhina',

'grouppage-sysop' => '{{ns:project}}:Nā kahu',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'ka hoʻololi ʻana i kēia ʻaoʻao',

# Recent changes
'nchanges'           => '$1 {{PLURAL:$1|loli|mau loli}}',
'recentchanges'      => 'Nā loli hou',
'rcnote'             => "ʻO {{PLURAL:$1|ka loli '''1'''|nā loli hope '''$1'''}} ma hope mai {{PLURAL:$2|ka lā hoʻokahi|nā lā '''$2'''}} ma hope, ma $5, $4.",
'rcshowhideminor'    => '$1 i nā ho‘opololei iki',
'rcshowhidebots'     => '$1 i nā lopako',
'rcshowhideliu'      => '$1 i nā mea hoʻohana i ʻeʻe ai',
'rcshowhideanons'    => '$1 i nā mea hoʻohana i nele ai ka inoa',
'rcshowhidemine'     => '$1 i ka‘u mau hoʻololi',
'diff'               => '‘oko‘a',
'hist'               => 'loli',
'hide'               => 'hūnā',
'show'               => 'hō‘ike',
'minoreditletter'    => 'iki',
'newpageletter'      => 'hou',
'boteditletter'      => 'lopako',
'rc-enhanced-expand' => 'Hō‘ike i nā ‘ikepili (me JavaScript)',
'rc-enhanced-hide'   => 'Hūnā i nā ‘ikepili',

# Recent changes linked
'recentchangeslinked'      => 'Nā loli hou ʻālike',
'recentchangeslinked-page' => 'Inoa ʻaoʻao:',

# Upload
'upload'            => 'Hoʻouka i ka waihona',
'uploadbtn'         => 'Hoʻouka i ka waihona',
'filedesc'          => 'Hōʻuluʻulu manaʻo',
'fileuploadsummary' => 'Hōʻuluʻulu manaʻo:',
'uploadedimage'     => 'hoʻouka ʻia iā "[[$1]]"',

# Special:ListFiles
'listfiles_name' => 'Inoa',

# File description page
'file-anchor-link'    => 'Waihona',
'filehist'            => 'Mo‘olelo o ka waihona',
'filehist-current'    => 'o kēia manawa',
'filehist-datetime'   => 'Manawa',
'filehist-thumb'      => 'Kiʻiliʻiliʻi',
'filehist-user'       => 'Mea ho‘ohana',
'filehist-dimensions' => 'Nā nui',
'filehist-filesize'   => 'Nui o ka waihona',
'filehist-comment'    => 'Manaʻo',
'imagelinks'          => 'Nā loulou faila',
'linkstoimage'        => 'Loulou {{PLURAL:$1|kekahi ‘ao‘ao|kēia mau ‘ao‘ao $1}} i kēia waihona:',

# File deletion
'filedelete-comment' => 'Kumu:',

# Random page
'randompage' => 'He akikala kaulele',

# Statistics
'statistics' => 'Papa helu',

'brokenredirects-edit'   => 'e ho‘ololi',
'brokenredirects-delete' => 'e kāpae',

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
'movethispage'      => 'E hoʻoneʻe i kēia ʻaoʻao',
'pager-newer-n'     => '{{PLURAL:$1|1 hou aku|$1 hou aku}}',
'pager-older-n'     => '{{PLURAL:$1|1 kekahi iho|$1 kekahi iho}}',

# Book sources
'booksources'    => 'Kumu puke',
'booksources-go' => 'E huli',

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
'deletedcontributions'       => 'Nā ha‘awina o ka inoa mea ho‘ohana i kāpae ‘ia ai',
'deletedcontributions-title' => 'Nā ha‘awina o ka inoa mea ho‘ohana i kāpae ‘ia ai',

# Special:LinkSearch
'linksearch'    => 'Loulou waho wahi',
'linksearch-ok' => 'Huli',

# Special:ListUsers
'listusers-submit' => 'Hō‘ike',

# Special:Log/newusers
'newuserlog-create-entry' => 'Mea hoʻohana hou',

# Special:ListGroupRights
'listgrouprights-members' => '(papa o nā lālā)',

# E-mail user
'emailuser'    => 'E leka uila i kēia mea ho‘ohana',
'emailmessage' => 'Memo:',

# Watchlist
'watchlist'         => 'Kaʻu papa nānā pono',
'mywatchlist'       => 'Ka‘u papa nānā pono',
'removedwatch'      => 'Wehe ʻia mai kāu papa nānā pono',
'removedwatchtext'  => 'Wehe ʻia ʻo "[[:$1]]" mai [[Special:Watchlist|kāu papa nānā pono]].',
'watch'             => 'E kia‘i',
'watchthispage'     => 'E nānā pono i kēia mea',
'unwatch'           => 'E wehe ke kia‘i',
'watchlist-details' => '{{PLURAL:$1|$1|$1}} a kāu papa nānā pono ʻaoʻao, me ke koe ʻana o nā ʻaoʻao kūkākūkā.',
'wlshowlast'        => 'Hōʻike $1 hola hope $2 lā hope $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ke kia‘i nei...',
'unwatching' => 'Ke wehe nei i ke kiaʻi...',

'changed' => 'ua loli ‘ia',

# Delete
'deletepage'             => 'Kāpae ʻaoʻao',
'actioncomplete'         => 'Ua pau',
'deletedtext'            => 'Ua kāpae ʻia ʻo "<nowiki>$1</nowiki>".
E ʻike iā $2 no ka papa o nā kāpae ʻana hou.',
'deletedarticle'         => 'ua kāpae ‘ia "[[$1]]"',
'dellogpage'             => 'Mo‘olelo kāpae',
'dellogpagetext'         => 'He helu o nā mea i kāpae ʻia hou i lalo.',
'deletionlog'            => 'mo‘olelo kāpae',
'deletecomment'          => 'Kumu:',
'deleteotherreason'      => 'Kumu ʻē aʻe/hoʻokomo',
'deletereasonotherlist'  => 'Kumu ʻē aʻe',
'delete-edit-reasonlist' => 'Ho‘opololei i nā kumu no ke kāpae ‘ana',

# Rollback
'rollbacklink' => 'ho‘i',

# Protect
'protectedarticle'       => 'ua pale ʻia "[[$1]]"',
'prot_1movedto2'         => 'Ua hoʻoneʻe ʻo [[$1]] iā [[$2]]',
'protectcomment'         => 'Kumu:',
'protect-default'        => 'ʻAe nā mea hoʻohana a pau',
'protect-level-sysop'    => 'Nā kahu wale nō',
'protect-cantedit'       => 'ʻAʻole hiki iā ʻoe ke hoʻololi i nā kūlana māmalu o kēia ʻaoʻao, no ka mea, ʻaʻohe āu ʻae no ka hoʻololi ʻana.',
'protect-expiry-options' => '1 hola:1 hour,1 lā:1 day,1 pule:1 week,2 pule:2 weeks,1 mahina:1 month,3 mahina:3 months,6 mahina:6 months,1 makahiki:1 year,pau ʻole:infinite',
'restriction-type'       => 'ʻAe ʻia:',

# Restrictions (nouns)
'restriction-edit' => 'E ho‘ololi',
'restriction-move' => "E ho'ololi i ka inoa",

# Undelete
'undeletebtn'            => 'Ho‘āla',
'undeletelink'           => 'nānā/ho‘āla',
'undelete-search-submit' => 'Huli',

# Namespace form on various pages
'namespace'      => 'Wahi inoa',
'blanknamespace' => '(‘ano nui)',

# Contributions
'contributions' => 'Nā ha‘awina o kēia mea ho‘ohana',
'mycontris'     => 'Koʻu mau haʻawina',
'contribsub2'   => 'No $1 ($2)',
'uctop'         => '(wēkiu)',
'month'         => 'Mai ka mahina (me mua):',
'year'          => 'Mai ka makahiki (me mua):',

'sp-contributions-deleted'    => 'Nā ha‘awina o ka inoa mea ho‘ohana i kāpae ‘ia ai',
'sp-contributions-talk'       => 'Kūkākūkā',
'sp-contributions-userrights' => 'Ho‘oponopono ‘ana o nā kuleana',
'sp-contributions-search'     => 'Huli no nā haʻawina',
'sp-contributions-submit'     => 'Huli',

# What links here
'whatlinkshere'           => 'Nā mea e loulou iho ai',
'whatlinkshere-page'      => '‘Ao‘ao:',
'nolinkshere'             => "‘A‘ole he ‘ao‘ao e loulou ai iā '''[[:$1]]'''.",
'isredirect'              => 'ʻaoʻao hoʻoili ʻana',
'whatlinkshere-prev'      => '{{PLURAL:$1|mua|mua $1}}',
'whatlinkshere-next'      => '{{PLURAL:$1|hope|hope $1}}',
'whatlinkshere-links'     => '← nā loulou',
'whatlinkshere-hidelinks' => '$1 i nā loulou',
'whatlinkshere-filters'   => 'Kānana',

# Block/unblock
'blockip'            => 'E ke‘a i kēia mea ho‘ohana',
'ipbexpiry'          => 'Pau āhea:',
'ipbreason'          => 'Kumu:',
'ipbsubmit'          => 'E ke‘a i kēia mea ho‘ohana',
'ipbother'           => 'ʻĒ aʻe manawa:',
'ipboptions'         => '2 hola:2 hours,1 lā:1 day,3 lā:3 days,1 pule:1 week,2 pule:2 weeks,1 mahina:1 month,3 mahina:3 months,6 mahina:6 months,1 makahiki:1 year,pau ʻole:infinite',
'badipaddress'       => 'Mana ‘ole ka wahi noho IP',
'ipblocklist-submit' => 'Huli',
'infiniteblock'      => 'pau ʻole',
'anononlyblock'      => '‘A‘ohe i hō‘ike‘ia ka inoa wale nō',
'blocklink'          => 'e keʻa',
'unblocklink'        => 'mai pale',
'change-blocklink'   => 'hoʻololi ka palena',
'contribslink'       => 'nā ha‘awina',
'blockme'            => 'E ke‘a ia‘u',

# Move page
'move-page-legend'        => 'Hoʻoneʻe i ka ʻaoʻao',
'movearticle'             => 'E hoʻoneʻe i ka ʻaoʻao:',
'newtitle'                => 'I ka inoa hou:',
'move-watch'              => 'E nānā pono i kēia mea',
'movepagebtn'             => 'Hoʻoneʻe i ka ʻaoʻao',
'pagemovedsub'            => 'Kūleʻa ka hoʻoneʻe ʻana',
'movepage-moved'          => '\'\'\'Ua hoʻoneʻe ʻia ʻo "$1" iā "$2"\'\'\'',
'movedto'                 => 'ua neʻe ʻia i/iā',
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
'tooltip-pt-userpage'            => 'Kāu inoa mea ho‘ohana',
'tooltip-pt-mytalk'              => 'Kāu ‘aoʻao ʻōlelo',
'tooltip-pt-preferences'         => 'ka‘u makemake',
'tooltip-pt-watchlist'           => 'Ka papa o nā ʻaoʻao o kou nānā ʻana no nā loli',
'tooltip-pt-mycontris'           => 'Kāu mau ha‘awina',
'tooltip-pt-login'               => 'Pai ‘ia ‘oe e ‘e‘e, akā, ‘a‘ole ia he koina',
'tooltip-pt-logout'              => 'E ha‘alele',
'tooltip-ca-talk'                => 'Kūkākūkā e pili ana i kēia ‘ao‘ao',
'tooltip-ca-edit'                => 'Hiki iā ‘oe ke ho‘ololi i kēia ‘ao‘ao. Ma mua o ka mālama ʻia ʻana, e ho‘ohana i ke pihi nāmua, ke ‘olu‘olu.',
'tooltip-ca-addsection'          => 'Hoʻomaka i kekahi māhele hou',
'tooltip-ca-viewsource'          => 'Pale ʻia kēia ʻaoʻao.
Hiki iā ʻoe ke ʻikena i kāna molekumu.',
'tooltip-ca-history'             => 'Ko kēia ʻaoʻao mau kāmua hope',
'tooltip-ca-protect'             => 'Ho‘omalu i keia ‘ao‘ao',
'tooltip-ca-delete'              => 'E kāpae i kēia mo‘olelo',
'tooltip-ca-move'                => 'E hoʻoneʻe i kēia ʻaoʻao',
'tooltip-ca-watch'               => 'E nānā pono i kēia mea',
'tooltip-search'                 => 'Huli iā {{SITENAME}}',
'tooltip-search-go'              => 'Kele i kekahi ʻaoʻao me kēia inoa inā hiki ke loaʻa',
'tooltip-search-fulltext'        => 'Huli i nā ʻaoʻao no kēia kikokikona',
'tooltip-p-logo'                 => 'Kele i ka papa kinohi',
'tooltip-n-mainpage'             => 'Kele i ka papa kinohi',
'tooltip-n-mainpage-description' => 'Kele i ka papa kinohi',
'tooltip-n-portal'               => 'E pili ana ka pāhana, nā hana hiki, nā wahi no ka loaʻa ʻana',
'tooltip-n-currentevents'        => 'ʻIke i nā nū hou',
'tooltip-n-recentchanges'        => 'Nā loli hou ma ka wiki',
'tooltip-n-randompage'           => 'Hōʻike kekahi ʻaoʻao kaulele',
'tooltip-n-help'                 => 'Ka wahi e kōkua ai iā ‘oe',
'tooltip-t-whatlinkshere'        => 'Nā ‘ao‘ao a pau i loulou mai ai',
'tooltip-t-emailuser'            => 'Leka uila i kēia mea hoʻohana',
'tooltip-t-upload'               => 'Ho‘ouka i nā waihona',
'tooltip-t-specialpages'         => 'Papa inoa o nā ʻaoʻao nui apau',
'tooltip-t-print'                => 'Mana paʻi pono o kēia ʻaoʻao',
'tooltip-t-permalink'            => 'Loulou paʻa no kēia kāmua o ka ʻaoʻao',
'tooltip-ca-nstab-special'       => 'He papa nui kēia; ʻaʻole hiki iā ʻoe ke hoʻololi',
'tooltip-ca-nstab-project'       => 'Nānā i ka ‘ao‘ao papahana',
'tooltip-ca-nstab-image'         => 'Nānā i ka ʻaoʻao faila',
'tooltip-ca-nstab-help'          => 'Nānaina i ka ʻaoʻao kōkua',
'tooltip-minoredit'              => 'Wae i kēia hoʻopololei me he hoʻopololei iki',
'tooltip-save'                   => 'Mālama i kāu ho‘opololei',
'tooltip-watch'                  => 'E nānā pono i kēia mea',

# Browsing diffs
'nextdiff' => 'Hoʻololi hou aʻe →',

# Media information
'file-info-size' => '$1 x $2 kiʻiʻuku, nui faila: $3, ʻano MIME: $4',
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
'autosumm-new'     => "Ua hoʻokumu ʻia kekahi ʻaoʻao me '$1'",

# Live preview
'livepreview-loading' => 'Ke ho‘ouka nei…',

# Watchlist editor
'watchlistedit-normal-title' => 'E ho‘opololei i ka‘u papa nānā pono',

# Special:Version
'version-specialpages' => 'Nā ‘ao‘ao kūikawā',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Huli',

# Special:SpecialPages
'specialpages' => 'Nā ‘ao‘ao kūikawā',

# Special:Tags
'tags-edit' => 'e hoʻololi',

);

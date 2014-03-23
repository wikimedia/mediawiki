<?php
/** Hawaiian (Hawai`i)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Bulaisen
 * @author Kalani
 * @author Kolonahe
 * @author Node ue
 * @author Singularity
 * @author Xqt
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
	'Ancientpages'              => array( 'ʻAoʻaoKahiko', 'AoaoKahiko' ),
	'Categories'                => array( 'Māhele', 'Mahele' ),
	'Contributions'             => array( 'Haʻawina', 'Haawina' ),
	'CreateAccount'             => array( 'Kāinoa', 'Kainoa' ),
	'Emailuser'                 => array( 'LekaUila' ),
	'Longpages'                 => array( 'ʻAoʻaoLoa', 'AoaoLoa' ),
	'Movepage'                  => array( 'HoʻoneʻeʻAoʻao', 'HooneeAoao' ),
	'Mycontributions'           => array( 'KaʻuHaʻawina', 'KauHaawina' ),
	'Mypage'                    => array( 'KaʻuʻAoʻao', 'KauAoao' ),
	'Mytalk'                    => array( 'KaʻuKūkākūkā', 'KauKukakuka' ),
	'Newpages'                  => array( 'ʻAoʻaoHou', 'AoaoHou' ),
	'Preferences'               => array( 'Makemake' ),
	'Randompage'                => array( 'Kaulele' ),
	'Recentchanges'             => array( 'NāLoliHou', 'NaLoliHou' ),
	'Search'                    => array( 'Huli' ),
	'Shortpages'                => array( 'ʻAoʻaoPōkole', 'AoaoPokole' ),
	'Specialpages'              => array( 'PapaNui' ),
	'Upload'                    => array( 'Hoʻouka', 'Hoouka' ),
	'Userlogout'                => array( 'Haʻalele', 'Haalele' ),
	'Watchlist'                 => array( 'PapaNānāPono', 'PapaNanaPono' ),
);

$magicWords = array(
	'currentmonth'              => array( '1', 'KĒIAMAHINA', 'KEIAMAHINA', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'KĒIAINOAMAHINA', 'KEIAINOAMAHINA', 'CURRENTMONTHNAME' ),
	'currentday'                => array( '1', 'KĒIALĀ', 'KEIALA', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'KĒIALĀ2', 'KEIALA2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'KĒIAINOALĀ', 'KEIAINOALA', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'KĒIAMAKAHIKI', 'KEIAMAKAHIKI', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'KĒIAMANAWA', 'KEIAMANAWA', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'KĒIAHOLA', 'KEIAHOLA', 'CURRENTHOUR' ),
	'numberofpages'             => array( '1', 'HELUʻAOʻAO', 'HELUAOAO', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'HELUMEA', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'HELUWAIHONA', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'HELUMEAHOʻOHANA', 'HELUMEAHOOHANA', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'HELULOLI', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'INOAʻAOʻAO', 'INOAAOAO', 'PAGENAME' ),
	'img_right'                 => array( '1', 'ʻākau', 'ākau', 'akau', 'right' ),
	'img_left'                  => array( '1', 'hema', 'left' ),
	'img_none'                  => array( '1', 'ʻaʻohe', 'aohe', 'none' ),
	'img_link'                  => array( '1', 'loulou=$1', 'link=$1' ),
	'currentweek'               => array( '1', 'KĒIAPULE', 'KEIAPULE', 'CURRENTWEEK' ),
	'language'                  => array( '0', '#ʻŌLELO', '#ŌLELO', '#OLELO', '#LANGUAGE:' ),
	'numberofadmins'            => array( '1', 'HELUKAHU', 'NUMBEROFADMINS' ),
);

$messages = array(
# User preference toggles
'tog-underline' => 'Kahalalo i nā loulou:',
'tog-hideminor' => 'E hoʻohūnā i nā ho‘ololi iki ma nā loli hou',
'tog-hidepatrolled' => 'E hoʻohūnā i nā loli kiaʻi kaʻahele ma nā loli hou',
'tog-newpageshidepatrolled' => 'E hoʻohūnā i nā ʻaoʻao kiaʻi kaʻahele mai ka papahelu ʻaoʻao hou',
'tog-extendwatchlist' => 'E hoʻākea i ka papakiaʻi no ka hōʻike ʻana i nā loli apau, ʻaʻole nā mea hanawale wale nō',
'tog-usenewrc' => 'Nā loli hui mao ka ʻaoʻao ma loko o ka loli hou a me ka papakiaʻi',
'tog-numberheadings' => 'E hikahelu i nā poʻo',
'tog-showtoolbar' => 'E hōʻike i ka pahu hāmeʻa hoʻololi',
'tog-editondblclick' => 'E ho‘ololi i nā ‘ao‘ao me ke kōmi pālua',
'tog-editsectiononrightclick' => 'E hoʻokuʻu i ka hoʻololi mahele mao ka paʻina ʻākau ma nā poʻoinoa māhele',
'tog-rememberpassword' => 'E hoʻomanaʻo iaʻu ma kēia lolo uila (no ka palena nui o $1 {{PLURAL:$1|lā|mau lā}})',
'tog-watchcreations' => 'E ho‘ohui i nā ‘ao‘ao aʻu i hana ai a me nā waihona aʻu i hoʻouka ai i ka‘u papakiaʻi',
'tog-watchdefault' => 'E ho‘ohui i nā ‘ao‘ao a me nā waihona aʻu e hoʻololi ai i ka‘u papakiaʻi',
'tog-watchmoves' => 'E ho‘ohui i nā ‘ao‘ao a me nā waihona aʻu e ne‘e ai i ka‘u papakiaʻi',
'tog-watchdeletion' => 'E ho‘ohui i nā ‘ao‘ao a me nā waihona aʻu e holoi ai i ka‘u papakiaʻi',
'tog-minordefault' => 'E kaha i nā loli apau i ka loli iki mao ka paʻamau',
'tog-previewontop' => 'E hō‘ike i ka nāmua mamua o ka pahu hoʻololi',
'tog-previewonfirst' => 'E hō‘ike i ka nāmua ma ka ho‘ololi mua',
'tog-enotifwatchlistpages' => 'E leka uila iaʻu i ka loli ʻana o kekahi waihona aiʻole kekahi ʻaoʻao ma kaʻu papakiaʻi',
'tog-enotifusertalkpages' => 'E leka uila iaʻu i ka loli ʻana o kaʻu ʻaoʻao walaʻau',
'tog-enotifminoredits' => 'E leka uila iaʻu no nā loli iki o nā ʻaoʻao a me nā waihona',
'tog-enotifrevealaddr' => 'E hō‘ike i kaʻu wahinoho lekauila ma nā lekauila notikala',
'tog-shownumberswatching' => 'E hō‘ike i ka helu o nā mea ho‘ohana e nānā nei',
'tog-oldsig' => 'Pūlima hananei:',
'tog-fancysig' => 'E hana i ka pūlima me he wikitext (me ʻole i ka loulou hanawale)',
'tog-uselivepreview' => 'E hana i ka nāmua ʻānō (hoʻokolohua)',
'tog-forceeditsummary' => 'E kono iaʻu i ka hoʻokomo ʻana i kekahi hōʻuluʻulu manaʻo hoʻololi hou',
'tog-watchlisthideown' => 'E hoʻohūnā i ko‘u mau ho‘ololi mai ka papakiaʻi',
'tog-watchlisthidebots' => 'E hoʻohūnā i nā loli o nā lopako mai ka papakiaʻi',
'tog-watchlisthideminor' => 'E hoʻohūnā i nā loli iki mai ka papakiaʻi',
'tog-watchlisthideliu' => 'E hoʻohūnā i nā loli e nā mea hoʻohana ʻeʻeia mai ka papakiaʻi',
'tog-watchlisthideanons' => 'E hoʻohūnā i nā loli e nā mea hoʻohana inoaʻole mai ka papakiaʻi',
'tog-watchlisthidepatrolled' => 'E hoʻohūnā i nā loli kiaʻi kaʻahele mai ka papakiaʻi',
'tog-ccmeonemails' => 'E hoʻouna mai i nā kope o nā lekauila aʻu i hāʻawi ai i kekahi mau mea hoʻohana ʻē aʻe.',
'tog-diffonly' => 'Mai hōʻike i nā mealoko ʻaoʻao ma lalo o ka ʻokoʻa',
'tog-showhiddencats' => 'E hōʻike i nā māhele hūnā',
'tog-norollbackdiff' => 'E waiho i ka ʻokoʻa ma hope o ka hana hoʻihoʻi',
'tog-useeditwarning' => 'E aʻo mai iaʻu i kaʻu haʻalele ʻana i ka ʻaoʻao hoʻololi inā loaʻa i nā loli mālama ʻia ʻole',
'tog-prefershttps' => 'E hana mau i ka hoʻokuʻi paʻa ma loko o ka ʻeʻe ʻana',

'underline-always' => 'I nā manawa apau',
'underline-never' => '‘A‘ole loa',
'underline-default' => 'Paʻamau ʻike aiʻole pōlamu pūnaewele',

# Font style option in Special:Preferences
'editfont-style' => 'E hoʻololi i kahi kaila hua:',
'editfont-default' => 'Pōlamu pūnaewele paʻamau',
'editfont-monospace' => 'Hua pukakahi',
'editfont-sansserif' => 'Hua Sanā-selifa',
'editfont-serif' => 'Hua Selifa',

# Dates
'sunday' => 'Lāpule',
'monday' => 'Pō‘akahi',
'tuesday' => 'Pō‘alua',
'wednesday' => 'Pō‘akolu',
'thursday' => 'Pō‘ahā',
'friday' => 'Pō‘alima',
'saturday' => 'Pō‘aono',
'sun' => 'LP',
'mon' => 'P1',
'tue' => 'P2',
'wed' => 'P3',
'thu' => 'P4',
'fri' => 'P5',
'sat' => 'P6',
'january' => 'Ianuali',
'february' => 'Pepeluali',
'march' => 'Malaki',
'april' => '‘Apelila',
'may_long' => 'Mei',
'june' => 'Iune',
'july' => 'Iulai',
'august' => '‘Aukake',
'september' => 'Kepakemapa',
'october' => '‘Okakopa',
'november' => 'Nowemapa',
'december' => 'Kēkēmapa',
'january-gen' => 'Ianuali',
'february-gen' => 'Pepeluali',
'march-gen' => 'Malaki',
'april-gen' => '‘Apelila',
'may-gen' => 'Mei',
'june-gen' => 'Iune',
'july-gen' => 'Iulai',
'august-gen' => '‘Aukake',
'september-gen' => 'Kepakemapa',
'october-gen' => '‘Okakopa',
'november-gen' => 'Nowemapa',
'december-gen' => 'Kēkēmapa',
'jan' => 'Ian',
'feb' => 'Pep',
'mar' => 'Mal',
'apr' => 'ʻApe',
'may' => 'Mei',
'jun' => 'Iun',
'jul' => 'Iul',
'aug' => 'ʻAuk',
'sep' => 'Kep',
'oct' => 'ʻOk',
'nov' => 'Now',
'dec' => 'Kek',
'january-date' => '$1 Ianuali',
'february-date' => '$1 Pepeluali',
'march-date' => '$1 Malaki',
'april-date' => '$1 ʻApelila',
'may-date' => '$1 Mei',
'june-date' => '$1 Iune',
'july-date' => '$1 Iulai',
'august-date' => '$1 ʻAukake',
'september-date' => '$1 Kepakemapa',
'october-date' => '$1 ʻOkakopa',
'november-date' => '$1 Nowemapa',
'december-date' => '$1 Kēkēmapa',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Mahele| mau Māhele}}',
'category_header' => 'Nā ʻaoʻao i loko o ka mahele "$1"',
'subcategories' => 'Nā Māhele kūloko',
'category-media-header' => 'Nā Pāpaho i loko o ka mahele "$1"',
'category-empty' => "''ʻAʻohe ʻaoʻao a me pāpaho a kēia mahele i kēia manawa.''",
'hidden-categories' => '{{PLURAL:$1|mahele hūnā|mau māhele hūnā}}',
'hidden-category-category' => 'Nā māhele hūnā',
'category-subcat-count' => '{{PLURAL:$2|Hoʻokahi mahele kūloko wale nō o kēia mahele.|Aia {{PLURAL:$1|i kēia mahele kūloko|$1 mau māhele kūloko}} i loko o kēia mahele, $2 ka huina.}}',
'category-article-count' => '{{PLURAL:$2|Hoʻokahi ʻaoʻao wale nō o kēia mahele.|Aia {{PLURAL:$1|i kēia ʻaoʻao|$1 mau ʻaoʻao}} i loko o kēia mahele, $2 ka huina.}}',
'category-file-count' => '{{PLURAL:$2|Hoʻokahi waihona wale nō o kēia mahele.|Aia {{PLURAL:$1|i kēia waihona|$1 mau waihona}} i loko o kēia mahele, $2 ka huina.}}',
'listingcontinuesabbrev' => '(homaʻia)',
'noindex-category' => 'Nā ʻAoʻao i hoʻopapakuhikuhi kikoʻī ʻia',

'about' => 'No ia',
'article' => 'ʻAoʻao mealoko',
'newwindow' => '(wehe ʻia i loko o kekahi pukaaniani hou)',
'cancel' => 'Ho‘ōki',
'moredotdotdot' => 'Nā mea ʻē aʻe...',
'morenotlisted' => 'ʻAʻole pau kēia papahelu.',
'mypage' => 'Ka‘u ‘ao‘ao',
'mytalk' => 'Ka‘u walaʻau',
'anontalk' => 'Walaʻau no kēia IP',
'navigation' => 'Kelena',
'and' => '&#32;a me',

# Cologne Blue skin
'qbfind' => 'Loa‘a iā',
'qbbrowse' => 'Kele',
'qbedit' => 'Hoʻololi',
'qbpageoptions' => 'Kēia ‘ao‘ao',
'qbmyoptions' => 'Ka‘u mau ‘ao‘ao',
'faq' => 'NNP',
'faqpage' => 'Project:NNP',

# Vector skin
'vector-action-addsection' => 'Hoʻohui kumuhana',
'vector-action-delete' => 'Holoi',
'vector-action-move' => 'E hoʻoneʻe',
'vector-action-protect' => 'E hoʻomalu',
'vector-action-undelete' => 'Holoiʻole',
'vector-action-unprotect' => 'E hoʻololi i ka hoʻomalu',
'vector-view-create' => 'Haku',
'vector-view-edit' => 'Hoʻololi',
'vector-view-history' => 'Nānā i ka mōʻaukala',
'vector-view-view' => 'Heluhelu',
'vector-view-viewsource' => 'Nānā i ke kumu',
'actions' => 'Nā Hana',
'namespaces' => 'Lewainoa',
'variants' => 'Nā Lolina',

'navigation-heading' => 'Papa kelena',
'errorpagetitle' => 'Hewa',
'returnto' => 'Ho‘i iā $1.',
'tagline' => 'Mai {{SITENAME}}',
'help' => 'Kōkua',
'search' => 'Huli',
'searchbutton' => 'Huli',
'go' => 'E huli',
'searcharticle' => 'E huli',
'history' => 'Mōʻaukala ʻaoʻao',
'history_short' => 'Mōʻaukala',
'updatedmarker' => 'ua hoʻopuka hou mahope mai koʻu kipa ʻana mai mua',
'printableversion' => 'Mana paʻi pono',
'permalink' => 'Loulou paʻa',
'print' => 'Pa‘i',
'view' => 'Nānā',
'edit' => 'Hoʻololi',
'create' => 'Haku',
'editthispage' => 'E hoʻololi i kēia ‘ao‘ao',
'create-this-page' => 'E haku i keia ‘ao‘ao',
'delete' => 'Holoi',
'deletethispage' => 'E holoi i kēia ʻaoʻao',
'undeletethispage' => 'E holoiʻole i kēia ʻaoʻao',
'undelete_short' => 'E holoiʻole i {{PLURAL:$1|hoʻokahi loli|$1 mau loli}}',
'viewdeleted_short' => 'E ʻike i {{PLURAL:$1|hoʻokahi loli holoi|$1 mau loli holoi}}',
'protect' => 'Hoʻomalu',
'protect_change' => 'hoʻololi',
'protectthispage' => 'E ho‘omalu i kēia ‘ao‘ao',
'unprotect' => 'E hoʻololi i ka ho‘omalu',
'unprotectthispage' => 'E hoʻololi i ka hoʻomalu o kēia ʻaoʻao',
'newpage' => '‘Ao‘ao hou',
'talkpage' => 'Kūkākūkā i keia ‘ao‘ao',
'talkpagelinktext' => 'Walaʻau',
'specialpage' => '‘Ao‘ao kūikawā',
'personaltools' => 'Hāmeʻa ponoʻī',
'postcomment' => 'Māhele hou',
'articlepage' => 'Nānā i ka ʻaoʻao mealoko',
'talk' => 'walaʻau',
'views' => 'Nānaina',
'toolbox' => 'Hāmeʻa',
'userpage' => 'Nānā i ka ‘ao‘ao mea ho‘ohana',
'projectpage' => 'Nānā i ka ‘ao‘ao papahana',
'imagepage' => 'Nānā i ka ‘ao‘ao waihona',
'mediawikipage' => 'Nānā i ka ‘ao‘ao pūlono',
'templatepage' => 'Nānā i ka ‘ao‘ao anakuhi',
'viewhelppage' => 'Nānā i ka ‘ao‘ao kōkua',
'categorypage' => 'Nānā i ka ‘ao‘ao mahele',
'viewtalkpage' => 'Nānā i ke kūkākūkā',
'otherlanguages' => 'Ma nā leo ʻē aʻe',
'redirectedfrom' => '(Kia hou mai $1)',
'redirectpagesub' => 'ʻAoʻao kia hou',
'lastmodifiedat' => 'Ua kāloli ʻia kēia ʻaoʻao i ka lā $1, ma ka hola $2.',
'viewcount' => 'Ua komo ʻia kēia ʻaoʻao i {{PLURAL:$1|hoʻokahi manawa|$1 mau manawa}}',
'protectedpage' => '‘Ao‘ao ho‘omalu',
'jumpto' => 'Lele iā:',
'jumptonavigation' => 'kelena',
'jumptosearch' => 'huli',
'view-pool-error' => 'E kala mai, ua hoʻoili nui ʻino nā pūnaewele i kēia manawa. Hoʻāʻo nā mea hoʻohana nui kā e ʻike i kēia ʻaoʻao. E ʻoluʻolu, e kali no kekahi mau minuke a hana hou. 

$1',
'pool-errorunknown' => 'Hewa ʻikeʻole',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'No {{SITENAME}}',
'aboutpage' => 'Project:No translatewiki.net',
'copyrightpage' => '{{ns:project}}:Kūleana kope',
'currentevents' => 'Nūhou',
'currentevents-url' => 'Project:Nūhou',
'disclaimers' => 'Nā Akahele',
'disclaimerpage' => 'Project:Akahele Laulaha',
'edithelp' => 'Kōkua ho‘ololi',
'mainpage' => 'Papa Kinohi',
'mainpage-description' => 'Papa Kinohi',
'policy-url' => 'Project:Kulekele',
'portal' => 'Puka Kaiāulu',
'portal-url' => 'Project:Puka Kaiāulu',
'privacy' => 'Kulekele pilikino',
'privacypage' => 'Project:Kulekele pilikino',

'badaccess' => 'Hewa ‘aena',

'versionrequired' => 'Noi ʻia ka mana $1 o MekiaWiki',
'versionrequiredtext' => 'Noi ʻia ka mana $1 o MekiaWiki no ka hoʻohana ʻana o kēia ʻaoʻao.
ʻIke i ka [[Special:Version|ʻaoʻao mana]].',

'ok' => 'Hiki nō',
'retrievedfrom' => 'Kiʻi ʻia mai "$1"',
'youhavenewmessages' => '$1 {{PLURAL:$3|kāu}} ($2).',
'youhavenewmessagesfromusers' => '$1 {{PLURAL:$4|kāu}} mai {{PLURAL:$3|kekahi mea hoʻohana ʻē aʻe|$3 mau mea hoʻohana}} ($2).',
'youhavenewmessagesmanyusers' => '$1 kāu mai nā mea hoʻohana he nui ($2).',
'newmessageslinkplural' => '{{PLURAl:$1|Hoʻokahi leka|999=He mau leka}}',
'newmessagesdifflinkplural' => '{{PLURAL:$1|loli|999=mau loli}}',
'youhavenewmessagesmulti' => '$1 kāu',
'editsection' => 'ho‘ololi',
'editold' => 'ho‘ololi',
'viewsourceold' => 'nānā i ke kumu',
'editlink' => 'hoʻololi',
'viewsourcelink' => 'nānā i ke kumu',
'editsectionhint' => 'E hoʻololi i ka paukū: $1',
'toc' => 'Nā Mealoko',
'showtoc' => 'hō‘ike',
'hidetoc' => 'hoʻohūnā',
'collapsible-collapse' => 'Hoʻoliʻi',
'collapsible-expand' => 'Hoʻākea',
'thisisdeleted' => 'Nānā ai‘ole hō‘āla iā $1?',
'viewdeleted' => 'Nānā iā $1?',
'restorelink' => '{{PLURAL:$1|kekahi loli holoi|$1 mau loli holoi}}',
'feedlinks' => 'Hānaīke:',
'site-rss-feed' => 'Hānaīke RSS o $1',
'site-atom-feed' => 'Hānaīke Atom o $1',
'page-rss-feed' => 'Hānaīke RSS o "$1"',
'page-atom-feed' => 'Hānaīke Atom o "$1"',
'red-link-title' => '$1 (haku ʻia ʻole)',
'sort-descending' => 'Wae iho',
'sort-ascending' => 'Wae piʻi',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'ʻAoʻao',
'nstab-user' => 'ʻAoʻao mea ho‘ohana',
'nstab-media' => 'ʻAoʻao Pāpaho',
'nstab-special' => 'ʻAoʻao kūikawā',
'nstab-project' => 'ʻAoʻao papahana',
'nstab-image' => 'Waihona',
'nstab-mediawiki' => 'Pūlono',
'nstab-template' => 'Anakuhi',
'nstab-help' => 'Kōkua',
'nstab-category' => 'Mahele',

# Main script and global functions
'nosuchaction' => 'ʻAʻohe hana',
'nosuchspecialpage' => 'ʻAʻohe ʻaoʻao kūikawā',

# General errors
'error' => 'Hewa',
'databaseerror' => 'Hewa hōkeo ʻikepili',
'databaseerror-query' => 'Nīnau: $1',
'databaseerror-function' => 'Hana: $1',
'databaseerror-error' => 'Hewa: $1',
'laggedslavemode' => '<strong>E akahele:</strong> ʻAʻole loaʻa paha i ka ʻaoʻao na hoʻouka hou hou.',
'readonly' => 'Laka ‘ia ka hōkeo ‘ikepili',
'missing-article' => 'Loaʻa ʻole i ka hōkeo ʻikepili ke kikokikona o ka ʻaoʻao i pono e loaʻa ʻia i kapa ʻia ʻo "$1" $2.

Hana ʻia kēia pilikia e ka hāhai ʻana o kekahi ʻokoʻa kahiko aiʻole i kekahi loulou mōʻaukala i kekahi ʻaoʻao i holoi ʻia.

Inā ʻaʻole ka hana, ua loaʻa paha iā ʻoe i kekahi mū i loko o ka lako pōlamu.
E ʻoluʻolu, e lono  kēia pilikia i kekahi [[Special:ListUsers/sysop|kahu]], mai poina i ka URL.',
'missingarticle-rev' => '(kāmua#: $1)',
'missingarticle-diff' => '(ʻOkoʻa: $1, $2)',
'internalerror' => 'Hewa koloko',
'internalerror_info' => 'Hewa koloko:$1',
'fileappenderrorread' => 'ʻAʻole hiki ke heluhelu iā "$1" ma loko o ka pākuʻi ʻana.',
'fileappenderror' => 'ʻAʻole hiki ke pākuʻi "$1" iā "$2".',
'filecopyerror' => 'ʻAʻole hiki ke kope ka waihona "$1" iā "$2".',
'filerenameerror' => 'ʻAʻole hiki ke hōʻinoa hou ka waihona "$1" iā "$2".',
'filedeleteerror' => '‘A‘ole hiki ke holoi i ka waihona "$1".',
'directorycreateerror' => 'ʻAʻole hiki ke haku ka papakuhi waihona "$1"',
'filenotfound' => '‘A‘ole hiki ke loa‘a ka waihona "$1".',
'fileexistserror' => 'ʻAʻole hiki ke kākau i ka waihona "$1": Aia no ia.',
'cannotdelete-title' => 'Hiki ʻole ke holoi iā "$1"',
'badtitle' => 'Inoa ʻohe',
'badtitletext' => 'ʻAʻohe paha, hakahaka paha aiʻole loulou hewa paha ka poʻoinoa ʻaoʻao.
Loaʻa paha nā hua kikokikona e hiki ʻole ke hana i nā poʻoinoa.',
'viewsource' => 'Nānā i ke kumu',
'viewsource-title' => 'Nānā i ke kumu no $1',
'cascadeprotected' => 'Ho‘omalu ‘ia kēia ‘ao‘ao mai e ho‘opololei ana, no ka mea, hoʻokomo pū ‘ia ‘oia ma aia {{PLURAL:$1|‘ao‘ao|nā ‘ao‘ao}} i lalo, ho‘omalu ‘ia me ka "e wailele ana" koho:
$2',
'ns-specialprotected' => '‘A‘ole hiki ke ho‘ololi i nā ‘ao‘ao kūikawā',
'exception-nologin' => 'ʻE‘e ʻole',

# Login and logout pages
'welcomeuser' => 'Welina mai e $1!',
'yourname' => "Inoa mea ho'ohana:",
'userlogin-yourname' => 'Inoa mea hoʻohana',
'userlogin-yourname-ph' => 'E kikokiko i kāu inoa mea hoʻohana',
'createacct-another-username-ph' => 'E kikokiko i ka inoa mea hoʻohana',
'yourpassword' => 'ʻŌlelo hūnā:',
'userlogin-yourpassword' => 'ʻŌlelo hūnā',
'userlogin-yourpassword-ph' => 'Kikokiko i kāu ʻōlelo hūnā',
'createacct-yourpassword-ph' => 'Kikokiko i kekahi ʻōlelo hūnā',
'yourpasswordagain' => 'E kikokiko hou i ka ʻōlelo hūnā:',
'createacct-yourpasswordagain' => 'E hōʻoia i ka ʻōlelo hūnā',
'createacct-yourpasswordagain-ph' => 'E kikokiko hou i ka ʻōlelo hūnā',
'remembermypassword' => 'Hoʻomanaʻo iaʻu ma kēia lolo uila (no ka palena nui o $1 {{PLURAL:$1|lā|mau lā}})',
'userlogin-remembermypassword' => 'Hoʻomanaʻo iaʻu',
'login' => 'ʻEʻe',
'nav-login-createaccount' => 'ʻEʻe / Kāinoa',
'loginprompt' => 'Pono ʻoe e hoʻā i nā makana no ka ʻeʻe ʻana iā {{SITENAME}}.',
'userlogin' => 'ʻEʻe / Kāinoa',
'userloginnocreate' => 'ʻEʻe',
'logout' => 'Haʻalele',
'userlogout' => 'Haʻalele',
'notloggedin' => 'ʻE‘e ʻole',
'userlogin-noaccount' => 'ʻAʻohe āu moʻokāki?',
'userlogin-joinproject' => 'E komo mai iā {{SITENAME}}',
'nologin' => "ʻAʻohe āu moʻokāki? '''$1'''.",
'nologinlink' => 'E Kāinoa',
'createaccount' => 'E Kāinoa',
'gotaccount' => "He moʻokāki kāu? '''$1'''.",
'gotaccountlink' => 'ʻEʻe',
'userlogin-resetlink' => 'Ua poina i kāu ʻike ʻeʻe?',
'userlogin-resetpassword-link' => 'Ua poina i kāu ʻōlelo hūnā?',
'userlogin-createanother' => 'E kāinoa i kekahi moʻokāki ʻē aʻe',
'createacct-join' => 'E kikokiko i kāu ʻike i lalo.',
'createacct-another-join' => 'E kikokiko i ka ʻike o ka moʻokāki hou i lalo.',
'createacct-emailrequired' => 'Wahinoho lekauila',
'createacct-emailoptional' => 'Wahinoho lekauila (kāpae)',
'createacct-email-ph' => 'E kikokiko i kāu wahinoho lekauila',
'createacct-another-email-ph' => 'E kikokiko i ka wahinoho lekauila',
'createaccountmail' => 'Hana i kekahi ʻōlelo hūnā ponokoho kūikawā a hoʻouna ia i ka wahinoho lekauila i kikokiko ʻia',
'createacct-realname' => 'Inoa ʻoiaʻiʻo (kāpae)',
'createaccountreason' => 'Kumu:',
'createacct-reason' => 'Kumu',
'createacct-reason-ph' => 'No ke aha mai ke kāinoa nei i kekahi moʻokāki ʻē aʻe',
'createacct-imgcaptcha-ph' => 'E kikokiko i ke kikokikona  i luna',
'createacct-submit' => 'Kāinoa',
'createacct-another-submit' => 'Kāinoa hou',
'badretype' => 'ʻAʻole like nā ʻōlelo hūnā āu i hoʻokomo ai',
'userexists' => 'Ua kāinoa ʻia ka inoa mea ho‘ohana.
E koho i kekahi inoa ʻē aʻe, ke ‘olu‘olu.',
'loginerror' => 'Hewa ʻeʻe',
'createacct-error' => 'Hewa kāinoa',
'createaccounterror' => 'ʻAʻole hiki ke kāinoa: $1',
'loginsuccesstitle' => 'ʻEʻe kūleʻa',
'loginsuccess' => '<strong>Ua ʻeʻe ʻo "$1" iā {{SITENAME}}.</strong>',
'nouserspecified' => 'Pono ʻoe e kāhuakomo i ka inoa mea hoʻohana.',
'wrongpassword' => 'Hewa ka ʻōlelo hūnā.
E ʻoluʻolu, e hana hou.',
'wrongpasswordempty' => 'Hakahaka ka ʻōlelo hūnā.
E ʻoluʻolu, e hana hou.',
'mailmypassword' => 'Kāinoa hou i ka ʻōlelo hūnā',
'passwordremindertitle' => "He 'ōlelo hūnā kūikawā no {{SITENAME}}",
'emailauthenticated' => 'Ua hō‘oia ‘ia kāu wahinoho lekauila ma ka lā $2 i ka hola $3.',
'emailnotauthenticated' => 'ʻAʻole hōʻoia ʻia kāu wahinoho lekauila.
Hoʻouna ʻole i kekahi lekauila no kēia mau helena.',
'emailconfirmlink' => 'E hō‘oia i kāu wahinoho lekauila',
'accountcreated' => 'Ua kāinoa',
'accountcreatedtext' => 'Ua kāinoa ka moʻokāki no [[{{ns:User}}:$1|$1]] ([[{{ns:User talk}}:$1|walaʻau]])',
'createaccount-title' => 'Kāinoa moʻokāki no {{SITENAME}}',
'loginlanguagelabel' => 'ʻŌlelo: $1',
'pt-login' => 'ʻEʻe',
'pt-login-button' => 'ʻEʻe',
'pt-createaccount' => 'Kāinoa',
'pt-userlogout' => 'Haʻalele',

# Change password dialog
'changepassword' => 'E hoʻololi i ka ʻōlelo hūnā',
'oldpassword' => 'ʻŌlelo hūnā kahiko:',
'newpassword' => 'ʻŌlelo hūnā hou:',
'retypenew' => 'E kikokiko hou i ka ʻōlelo hūnā hou:',
'resetpass_submit' => 'Kau i ka ʻōlelo hūnā a ʻeʻe',
'changepassword-success' => 'Ua hoʻololi ‘ia kāu hua‘ōlelo huna!',
'resetpass-submit-cancel' => 'Hoʻōki',
'resetpass-temp-password' => 'ʻŌlelo hūnā kūikawā:',

# Special:PasswordReset
'passwordreset' => 'Kāinoa hou i ka ʻōlelo hūnā',
'passwordreset-legend' => 'Kāinoa hou i ka ʻōlelo hūnā',
'passwordreset-username' => "Inoa mea ho'ohana:",
'passwordreset-email' => 'Wahinoho lekauila:',

# Special:ChangeEmail
'changeemail-oldemail' => 'Wahinoho lekauila hananei:',
'changeemail-newemail' => 'Wahinoho lekauila hou:',
'changeemail-none' => '(ʻaʻohe)',
'changeemail-password' => 'Kāu ʻōlelo hūnā {{SITENAME}}:',
'changeemail-submit' => 'Loli i kāu lekauila',
'changeemail-cancel' => 'Hoʻōki',

# Edit page toolbar
'bold_sample' => 'Ho‘okā‘ele',
'bold_tip' => 'Ho‘okā‘ele',
'italic_sample' => 'Ho‘ohiō',
'italic_tip' => 'Ho‘ohiō',
'link_sample' => 'Inoa loulou',
'link_tip' => 'Loulou kūloko',
'extlink_sample' => 'http://www.example.com inoa loulou',
'extlink_tip' => 'Loulou kūwaho (e ho‘omana‘o i ka poʻo pāʻālua http://)',
'headline_sample' => 'Po‘o‘ōlelo',
'headline_tip' => 'Poʻomanaʻo kau 2',
'nowiki_sample' => 'Hoʻokomo i nā kikokikona huluʻole ma ʻaneʻi',
'nowiki_tip' => 'Nānaʻole i ka hulu wiki',
'image_tip' => 'Waihona kauloko',
'media_tip' => 'Loulou waihona',
'sig_tip' => 'Kau pūlima me ka manawa',
'hr_tip' => 'Laina ‘ilikai (e hana pākiko)',

# Edit pages
'summary' => 'Hōʻuluʻulu manaʻo:',
'subject' => 'Kumumanaʻo/poʻo laina:',
'minoredit' => 'He hoʻololi iki kēia',
'watchthis' => 'E kiaʻi i kēia ʻaoʻao',
'savearticle' => 'E mālama i ka ʻaoʻao',
'preview' => 'Nāmua',
'showpreview' => "E hō'ike i ka nāmua",
'showlivepreview' => 'Nāmua ʻānō',
'showdiff' => "E hō'ike i nā loli",
'anoneditwarning' => '<strong>E akahele:</strong> ʻAʻole ʻoe ʻeʻe nei.
E hoʻopaʻa ʻia ana kāu IP ma ko kēia ʻaoʻao mōʻaukala hoʻololi.',
'blockedtitle' => 'Ua pale ‘ia ka mea ho‘ohana',
'blockednoreason' => '‘a‘ohe kumu',
'loginreqtitle' => 'Noi i ka ʻeʻe ʻana',
'loginreqlink' => 'ʻeʻe',
'accmailtitle' => 'Ua ho‘ouna ‘ia ka ‘ōlelo hūnā',
'newarticle' => '(Hou)',
'newarticletext' => 'Ua hāhai ʻoe i kekahi loulou i kekahi ʻaoʻao e haku ʻole.
No ka haku ʻana i ka ʻaoʻao, kikokiko i loko o ka pahu i lalo (ʻike i ka [$1 ʻaoʻao kōkua] no nā ʻike ʻē aʻe).
Inā hewa kou hele ʻana, kāomi i ka pihi <strong>hoʻi</strong>.',
'anontalkpagetext' => "----
<em>ʻO kēia ka ʻaoʻao kūkākūkā no kekahi mea ho‘ohana me ka inoa ʻole.</em>
No laila, pono mākou e ho‘ohana i ka IP no ka hōʻoia ʻana iā ia a hiki i kekahi mau mea hoʻohana ke hoʻokaʻana i kēia  IP.
Inā he mea ho‘ohana inoa ʻole ʻoe a loaʻa kekahi mau manaʻo nāuʻole, e ʻoluʻolu [[Special:UserLogin/signup|e kāinoa]] a i ʻole [[Special:UserLogin|e ʻeʻe]].''",
'noarticletext' => 'ʻAʻohe kikokikona a kēia ʻaoʻao.
Hiki iā ʻoe ke [[Special:Search/{{PAGENAME}}|huli no kēia inoa ʻaoʻao]] i nā ʻaoʻao ʻē aʻe, <span class="plainlinks">[{{fullurl:SpecialLog|page={{FULLPAGENAMEE}}}} huli i nā moʻolelo pili], a i ʻole [{{fullurl:{{FULLPAGENAME}}|action=edit}} hoʻololi i kēia ʻaoʻao]</span>.',
'noarticletext-nopermission' => 'ʻAʻohe kikokikona a kēia ʻaoʻao.
Hiki iā ʻoe ke [[Special:Search/{{PAGENAME}}|huli no kēia inoa ʻaoʻao]] i nā ʻaoʻao ʻē aʻe aiʻole <span class="plainlinks">[{{fullurl:SpecialLog|page={{FULLPAGENAMEE}}}} huli i nā moʻolelo pili]</span>, akā hiki ʻole iā ʻoe ke hoʻololi i kēia ʻaoʻao.',
'updated' => '(Hoʻopuka hou ʻia)',
'note' => '<strong>E noka:</strong>',
'previewnote' => '<strong>ʻO kēia ka nāmua wale nō.</strong>
‘A‘ole mālama ‘ia nā ho‘ololi!',
'continue-editing' => 'Kele i kahi hoʻololi',
'editing' => 'Ke ho‘ololi nei iā $1',
'creating' => 'Ke haku nei iā $1',
'editingsection' => 'Hoʻololi nei iā $1 (mahele)',
'editingcomment' => 'Ke ho‘ololi nei iā $1 (paukū hou)',
'editconflict' => 'He pilikia hoʻololi: $1',
'yourtext' => 'Kāu kikokikona',
'storedversion' => 'Loihape waiho ʻia',
'yourdiff' => 'Nā mea ‘oko‘a',
'copyrightwarning' => 'E ʻoluʻolu, hoʻokuʻu ʻia nā mea lūlū iā {{SITENAME}} ma lalo o ka laikini $2 (no nā mea kikoʻī, kele iā $1).
Inā ʻaʻole ʻoe makemake i ka hoʻololi ʻana kūnoa o kou kākau ʻana a ʻaʻole ʻoe makemake i ka hoʻomalele ʻana i kāu mau loli, a laila mai kākau ma ʻaneʻi.<br />
Ke hoʻohiki nei ʻoe iā kākou: nāu i kākau i kēia kikokikona aiʻole nau i kope i kēia kikokikona mai ke kūmole kūʻokoʻa.
<strong>Mai waiho i nā kikokikona ponokope me ka ʻae ʻole!</strong>',
'protectedpagewarning' => '<strong>E akahele:  Ua hoʻomalu ‘ia kēia ‘ao‘ao, pēlā, hiki i nā "kahu" ke ho‘ololi wale nō.</strong>
Aia nā loli hanalohi i lalo no ka ʻikena:',
'templatesused' => '{{PLURAL:$1|anakuhi|mau anakuhi}} e hana ʻia ma kēia ʻaoʻao:',
'templatesusedpreview' => 'Hoʻohana ʻia kēia {{PLURAL:$1|anakuhi|mau anakuhi}} i kēia nāmua:',
'template-protected' => '(ho‘omalu ‘ia)',
'template-semiprotected' => '(hapa-ho‘omalu ‘ia)',
'hiddencategories' => 'ʻO kēia ʻaoʻao he lālā o {{PLURAL:$1|1 mahele hūnā|$1 mau māhele hūnā}}:',
'edittools' => '<!-- Eia ka ‘ōlelo e hō‘ike ‘ia malalo o nā palapala ho‘ololi ame nā palapala ho‘ohui. -->',
'nocreate-loggedin' => 'Loaʻa ʻole iā ʻoe nā kūleana no ka haku ʻana o nā ʻaoʻao hou.',
'permissionserrorstext-withaction' => 'ʻAʻohe āu ʻae no $2, no {{PLURAL:$1|kumu| mau kumu}}:',
'recreate-moveddeleted-warn' => '<strong>E akahele: Ke haku nei ʻoe i kekahi ʻaoʻao i holoi ʻia.</strong>

Pono ʻoe e noʻonoʻo e pili ana ka pono o ka hoʻomau ʻana o ka hoʻololi ʻana o kēia ʻaoʻao.
Aia ka moʻolelo holoi a hoʻoneʻe no kēia ʻaoʻao ma ʻaneʻi:',
'moveddeleted-notice' => 'Ua holoi ʻia kēia ʻaoʻao.
Hoʻolako ʻia ka moʻolelo holoi a hoʻoneʻe no kēia ʻaoʻao i lalo no ke kūmole.',
'log-fulllog' => 'Nānā i ka moʻolelo piha',
'postedit-confirmation' => 'Ua mālama ʻia kāu hoʻololi',
'defaultmessagetext' => 'Kikokikona pūlono pa‘amau',

# Content models
'content-model-wikitext' => 'kikokikonawiki',
'content-model-javascript' => 'IawaSikulipa',

# Parser/template warnings
'post-expand-template-inclusion-warning' => '<strong>E akahele:</strong> Hoʻokela ʻia ka palena nui o ke anakuhi.
Hoʻohui ʻole i kekahi mau anakuhi.',
'post-expand-template-inclusion-category' => 'Nā ʻaoʻao me nā anakuhi e hoʻokela i ka palenanui',
'post-expand-template-argument-warning' => '<strong>E akahele:</strong> Aia ma kēia ʻaoʻao i kekahi a ʻoi pilikia anakuhi e loaʻa i kekahi nui hoʻonui nunui loa.
Ua waiho ʻia kēia mau pilikia.',
'post-expand-template-argument-category' => 'Nā ʻAoʻao e loaʻa nā pilikia anakuhi i waiho ʻia',

# "Undo" feature
'undo-success' => 'Hiki iā ʻoe ke hoʻihoʻi i kēia loli.
E ʻoluʻolu, e hōʻoia i ka hoʻokūkū ʻana i lalo, a laila, e mālama i nā loli i lalo no ka hoʻopau ʻana o ka hoʻihoʻi o ka loli.',
'undo-summary' => 'Hoʻihoʻi mai i ke kāmua $1 na [[Special:Contributions/$2|$2]] ([[User talk:$2|walaʻau]])',

# History pages
'viewpagelogs' => 'Nānā i nā moʻolelo no kēia ʻaoʻao',
'currentrev' => 'Kāmua hou',
'currentrev-asof' => 'Ke Kāmua houloa ma $1',
'revisionasof' => 'Kāmua ʻia ma $1',
'revision-info' => 'Kāmua ma $1 na $2',
'previousrevision' => '← Kāmua kahiko',
'nextrevision' => 'Kāmua hou →',
'currentrevisionlink' => 'Kāmua houloa',
'cur' => 'okawā',
'next' => 'hou aʻe',
'last' => 'aku nei',
'page_first' => 'mua loa',
'page_last' => 'hope loa',
'histlegend' => 'Koho ʻokoʻa: Kaha i nā pahu lekiō o nā kāmua no ka hoʻokūkū ʻana a kāomi ke kāhoʻi aiʻole ka pihi ma ka lalo.<br />
Pahu hōʻailona: <strong>({{int:cur}})</strong> = ka ʻokoʻa me ke kāmua houloa, <strong>({{int:last}})</strong> = ka ʻokoʻa me ke kāmua i hana mua, <strong>{{int:minoreditletter}}</strong> = he hoʻololi iki ia.',
'history-fieldset-title' => 'Mōʻaukaki Pūnaewele',
'history-show-deleted' => 'Holoi wale nō',
'histfirst' => 'kahiko loa',
'histlast' => 'hou loa',
'historysize' => '({{PLURAL:$1|1 ‘ai|$1 mau ‘ai}})',
'historyempty' => '(ʻaʻohe)',

# Revision feed
'history-feed-title' => 'Mōʻaukala kāmua',
'history-feed-description' => 'Mōʻaukala kāmua no kēia ʻaoʻao ma ka wiki',
'history-feed-item-nocomment' => '$1 ma $3 ma ka hola $4',

# Revision deletion
'rev-delundel' => 'hoʻololi ka nānā ʻana',
'rev-showdeleted' => 'hōʻike',
'revisiondelete' => 'Holoi/holoi ʻole i nā kāmua',
'revdelete-show-file-submit' => 'ʻAe',
'revdelete-hide-text' => 'Kikokikona kāmua',
'revdelete-hide-image' => 'Hoʻohūnā i nā waihona mealoko',
'revdelete-hide-comment' => 'Hoʻololi i ka hōʻuluʻulu manaʻo',
'revdelete-radio-same' => '(mai hoʻololi)',
'revdelete-radio-set' => 'ʻAe',
'revdelete-radio-unset' => 'ʻAʻole',
'revdelete-log' => 'Kumu:',
'revdel-restore' => 'hoʻololi i ka nānā ʻana',
'pagehist' => 'Mōʻaukala ʻaoʻao',
'deletedhist' => 'Mōʻaukala holoi',
'revdelete-otherreason' => 'Nā kumu ʻē aʻe',
'revdelete-reasonotherlist' => 'Nā kumu ʻē aʻe',
'revdelete-edit-reasonlist' => 'Hoʻololi i nā kumu holoi',
'revdelete-offender' => 'Mea kākau kāmua:',

# History merging
'mergehistory-from' => 'ʻAoʻao kūmole:',
'mergehistory-into' => 'ʻAoʻao helewahi:',
'mergehistory-reason' => 'Kumu:',

# Merge log
'revertmerge' => 'Hoʻokuʻipū ʻole',

# Diffs
'history-title' => 'Mōʻaukala kāmua o "$1"',
'lineno' => 'Laina $1:',
'compareselectedversions' => 'Hoʻohālikelike i nā kāmua i koho ʻia',
'editundo' => 'hoʻihoʻi',
'diff-empty' => '(ʻaʻohe like ʻole)',

# Search results
'searchresults' => 'Nā Hualoaʻa',
'searchresults-title' => 'Nā hualoaʻa no "$1"',
'prevn' => '{{PLURAL:$1|$1}} mamua',
'nextn' => '{{PLURAL:$1|$1}} hou aʻe',
'prevn-title' => '$1 {{PLURAL:$1|hualoaʻa|mau hualoaʻa}} aku nei',
'nextn-title' => '$1 {{PLURAL:$1|hualoaʻa|mau hualoaʻa}} hou aʻe',
'shown-title' => 'Hōʻike $1 {{PLURAL:$1|hualoaʻa|mau hualoaʻa}} i kekahi ʻaoʻao',
'viewprevnext' => 'Nānā i ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-exists' => '<strong>Aia kekahi ʻaoʻao i kapa ʻia o "[[:$1]]" ma kēia wiki.</strong> {{PLURAL:$2|0=|ʻIke hoʻi i na hualoaʻa huli ʻē aʻe i loaʻa ʻia.}}',
'searchmenu-new' => '<strong>Haku i ka ʻaoʻao "[[:$1]]" ma kēia wiki!</strong> {{PLURAL:$2|0=|"ʻIke hoʻi i ka ʻaoʻao i loaʻa ʻia ma kou huli ʻana.|ʻIke hoʻi nā hualoaʻa huli i loaʻa ʻia.}}',
'searchprofile-articles' => 'Nā ʻAoʻao mealoko',
'searchprofile-project' => 'Nā ʻaoʻao Kōkua a me Papahana',
'searchprofile-images' => 'Laupāpaho',
'searchprofile-everything' => 'Nā mea apau',
'searchprofile-advanced' => 'Kiʻelē',
'searchprofile-articles-tooltip' => 'Huli i loko o $1',
'searchprofile-project-tooltip' => 'Huli i loko o $1',
'searchprofile-images-tooltip' => 'Huli no nā waihona',
'searchprofile-everything-tooltip' => 'Huli i nā mea apau (nā walaʻau nō hoʻi)',
'searchprofile-advanced-tooltip' => 'Huli iā lewainoa pilikino',
'search-result-size' => '$1 ({{PLURAL:$2|1 huaʻōlelo|$2 mau huaʻōlelo}})',
'search-result-category-size' => '{{PLURAL:$1|1 mea hoʻohana|$1 mau mea hoʻohana}} ({{PLURAL:$2|1 mahele kūloko|$2 mau māhele kūloko}}, {{PLURAL:$3|1 wahihona|$3 mau waihona}})',
'search-redirect' => '(kia hou $1)',
'search-section' => '(pauku $1)',
'search-suggest' => 'ʻO kēia paha kou manaʻo: $1',
'search-interwiki-caption' => 'Nā pāhana ʻē aʻe',
'search-interwiki-more' => '(hou aʻe)',
'search-relatedarticle' => 'Nā Mea ʻālike',
'searchrelated' => 'na mea ʻālike',
'searchall' => 'nā mea apau',
'showingresultsheader' => '{{PLURAL:$5|<strong>$1</strong> hualoaʻa o <strong>$3</strong> mau hualoaʻa|<strong$1-$2</strong> mau hualoaʻa o <strong>$3</strong> mau hualoaʻa}} no <strong>$4</strong>',
'search-nonefound' => 'ʻAʻohe hualoaʻa no kēia huli.',
'powersearch-legend' => 'Hulina kūlana kiʻekiʻe',
'powersearch-ns' => 'Huli i loko o nā wahi inoa:',
'powersearch-togglelabel' => 'Hōʻoia:',
'powersearch-toggleall' => 'Nā mea apau',
'powersearch-togglenone' => 'ʻAʻohe',
'search-external' => 'Huli kūwaho',

# Preferences page
'preferences' => 'Kaʻu makemake',
'mypreferences' => 'Ka‘u makemake',
'prefs-skin' => 'ʻIli',
'skin-preview' => 'Nāmua',
'datedefault' => 'ʻAʻohe makemake',
'prefs-beta' => 'Helena Beta',
'prefs-datetime' => 'Ka lā a me ka hola',
'prefs-personal' => 'ʻAoʻao mea hoʻohana',
'prefs-rc' => 'Nā loli hou',
'prefs-watchlist' => 'Papakiaʻi',
'prefs-watchlist-days' => 'Nā lā e hōʻike i ka papakiaʻi:',
'prefs-watchlist-days-max' => 'He palenanui o $1 {{PLURAL:$1|lā|mau lā}}',
'prefs-email' => 'Koho lekauila',
'prefs-rendering' => 'Helena',
'saveprefs' => 'Mālama',
'restoreprefs' => 'Hōʻala i nā makemake paʻamau (apau)',
'prefs-editing' => 'Hoʻololi',
'rows' => 'Lālani:',
'columns' => 'Koloma:',
'searchresultshead' => 'Huli',
'savedprefs' => 'Ua mālama ‘ia kāu makemake',
'timezonelegend' => 'Kāʻei hola:',
'localtime' => 'Hola kamaʻāina:',
'servertime' => 'Hola pūnaewele:',
'guesstimezone' => 'Piha mai ka pōlamu pūnaewele',
'timezoneregion-africa' => 'ʻApelika',
'timezoneregion-america' => 'ʻAmelika',
'timezoneregion-antarctica' => 'ʻAneʻālika',
'timezoneregion-arctic' => 'ʻĀlika',
'timezoneregion-asia' => 'ʻĀkia',
'timezoneregion-atlantic' => 'Moana ʻAkelanika',
'timezoneregion-australia' => 'ʻAukekulelia',
'timezoneregion-europe' => 'ʻEulopa',
'timezoneregion-indian' => 'Moana ʻIniana',
'timezoneregion-pacific' => 'Moana Pakipika',
'prefs-searchoptions' => 'Huli',
'prefs-namespaces' => 'Lewainoa',
'default' => 'paʻamau',
'prefs-files' => 'Waihona',
'youremail' => 'Lekauila:',
'username' => '{{GENDER:$1|Inoa mea hoʻohana}}:',
'uid' => '{{GENDER:$1|Mea hoʻohana}} ID:',
'prefs-memberingroups' => '{{GENDER:$2|He lālā}} o {{PLURAL:$1|hui|mau hui}}:',
'prefs-registration' => 'Hola kāinoa:',
'yourrealname' => 'Inoa ʻoiaʻiʻo:',
'yourlanguage' => 'Kāu ʻōlelo:',
'yournick' => 'Pūlima hou:',
'yourgender' => 'Keka:',
'gender-unknown' => 'Kāpae',
'gender-male' => 'Kāne',
'gender-female' => 'Wahine',
'email' => 'Lekauila',
'prefs-help-email' => 'Koi ʻole i ka wahinoho lekauila, akā pono ia nō ke kāinoa ʻana o ka ʻōlelo hūnā inā poina ʻoe i kāu ʻōlelo hūnā.',
'prefs-help-email-others' => 'Hiki iā ʻoe ke koho i ka ʻae ʻana i nā mea ʻē aʻe e hoʻokaʻaʻike iā ʻoe mao ka lekauila mao kekahi loulou ma kāu ʻaoʻao mea hoʻohana aiʻole kāu ʻaoʻao walaʻau.
ʻAʻole hōʻike ʻia kāu wahinoho lekauila i nā mea ʻē aʻe e hoʻokaʻaʻike iā ʻoe.',
'prefs-help-email-required' => 'Koi i ka lekauila.',
'prefs-signature' => 'Pūlima',
'prefs-advancedediting' => 'Koho paʻamau',
'prefs-editor' => 'Luna Hoʻoponopono:',
'prefs-preview' => 'Nāmua',
'prefs-advancedrc' => 'Koho kiʻelē',
'prefs-advancedrendering' => 'Koho kiʻelē',
'prefs-advancedsearchoptions' => 'Koho kiʻelē',
'prefs-advancedwatchlist' => 'Koho kiʻelē',
'prefs-displayrc' => 'Koho nānā',
'prefs-displaysearchoptions' => 'Koho nānā',
'prefs-displaywatchlist' => 'Koho nānā',
'prefs-diffs' => 'ʻOkoʻa',

# User rights
'userrights' => 'Ho‘oponopono ‘ana o nā kuleana',
'userrights-groupsmember' => 'He lālā o:',
'userrights-reason' => 'Kumu:',

# Groups
'group' => 'Hui:',
'group-user' => 'Mea hoʻohana',
'group-bot' => 'Lopako',
'group-sysop' => 'Nā Kahu',
'group-bureaucrat' => 'Nā Kuhina',
'group-all' => '(Nā mea apau)',

'group-user-member' => '{{GENDER:$1|ka mea hoʻohana}}',
'group-bot-member' => '{{GENDER:$1|ka lopako}}',
'group-sysop-member' => '{{GENDER:$1|ke kahu}}',
'group-bureaucrat-member' => '{{GENDER:$1|ke kuhina}}',

'grouppage-user' => '{{ns:project}}:Mea hoʻohana',
'grouppage-bot' => '{{ns:project}}:Lopako',
'grouppage-sysop' => '{{ns:project}}:Nā Kahu',
'grouppage-bureaucrat' => '{{ns:project}}:Nā Kuhina',

# Rights
'right-read' => 'Heluhelu i nā ʻaoʻao',
'right-edit' => 'Hoʻololi i nā ʻaoʻao',
'right-createpage' => 'Haku i nā ʻaoʻao (he kūkākūkā ʻole)',
'right-createtalk' => 'Haku i ka ʻaoʻao kūkākūkā',
'right-createaccount' => 'Kāinoa i nā moʻokāki hou',
'right-minoredit' => 'Kaha i nā loli me he hoʻololi iki',
'right-move' => 'Hoʻoneʻe i nā ʻaoʻao',
'right-move-subpages' => 'Hoʻoneʻe i nā ʻaoʻao me nā ʻaoʻao lokoiho',
'right-movefile' => 'Hoʻoneʻe i nā waihona',
'right-upload' => 'Hoʻouka i nā waihona',
'right-upload_by_url' => 'Hoʻouka i nā waihona mai kekahi URL',
'right-delete' => 'Holoi i nā ʻaoʻao',
'right-bigdelete' => 'Holoi i nā ʻaoʻao me he mōʻaukala nui',
'right-browsearchive' => 'Huli i nā ʻaoʻao holoi',
'right-undelete' => 'Holoi ʻole i kekahi ʻaoʻao',
'right-block' => 'Pale i nā mea hoʻohana ʻē aʻe mai ka hoʻololi ʻana',
'right-blockemail' => 'Pale i nā mea hoʻohana ʻē aʻe mai ka lekauila ʻana',
'right-hideuser' => 'Pale i ka inoa mea hoʻohana, no laila ʻaʻole hōʻike i ka lehulehu',
'right-unblockself' => 'Paleʻole i kāuiho',

# Special:Log/newusers
'newuserlogpage' => 'Moʻolelo haku mea hoʻohana',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'heluhelu i kēia ʻaoʻao',
'action-edit' => 'ka hoʻololi ʻana i kēia ʻaoʻao',
'action-createpage' => 'haku ʻaoʻao',
'action-createtalk' => 'haku ʻaoʻao kūkākūkā',
'action-createaccount' => 'kāinoa i kēia moʻokāki mea hoʻohana',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|loli|mau loli}}',
'enhancedrc-since-last-visit' => '$1 {{PLURAL:$1|mai kāu kipana aku nei}}',
'enhancedrc-history' => 'mōʻaukala',
'recentchanges' => 'Loli Hou',
'recentchanges-legend' => 'Nā Koho loli hou',
'recentchanges-feed-description' => 'Hāhai i nā loli houloa i ka wiki ma kēia hānaīke.',
'recentchanges-label-newpage' => 'Ua haku kēia hoʻololi i kēia ʻaoʻao hou',
'recentchanges-label-minor' => 'He hoʻololi iki kēia',
'recentchanges-label-bot' => 'Ua hana ʻia kēia hoʻololi e kekahi pako',
'recentchanges-label-unpatrolled' => 'ʻAʻole kiaʻi kaʻa ʻia kēia hoʻololi',
'rcnotefrom' => 'Aia i lalo nā loli mai <strong>$2</strong> (hōʻike a <strong>$1</strong>)',
'rclistfrom' => 'Hōʻike i nā loli hou mai ka hola $2, $3',
'rcshowhideminor' => '$1 i nā ho‘ololi iki',
'rcshowhideminor-show' => 'Hōʻike',
'rcshowhideminor-hide' => 'Hoʻohūnā',
'rcshowhidebots' => '$1 i nā lopako',
'rcshowhidebots-show' => 'Hōʻike',
'rcshowhidebots-hide' => 'Hoʻohūnā',
'rcshowhideliu' => '$1 i nā mea hoʻohana i kāinoa ʻia',
'rcshowhideliu-show' => 'Hōʻike',
'rcshowhideliu-hide' => 'Hoʻohūnā',
'rcshowhideanons' => '$1 i nā mea hoʻohana inoa ʻole',
'rcshowhideanons-show' => 'Hōʻike',
'rcshowhideanons-hide' => 'Hoʻohūnā',
'rcshowhidepatr' => '$1 i nā hoʻololi kiaʻi kaʻahele',
'rcshowhidepatr-show' => 'Hōʻike',
'rcshowhidepatr-hide' => 'Hoʻohūnā',
'rcshowhidemine' => '$1 i ka‘u mau hoʻololi',
'rcshowhidemine-show' => 'Hōʻike',
'rcshowhidemine-hide' => 'Hoʻohūnā',
'rclinks' => 'E hōʻike i nā loli $1 hou, mai $2 (mau) lā aku nei<br />$3',
'diff' => 'ʻokoʻa',
'hist' => 'mōkala',
'hide' => 'Hoʻohūnā',
'show' => 'Hō‘ike',
'minoreditletter' => 'iki',
'newpageletter' => 'ʻAHou',
'boteditletter' => 'pako',
'rc-enhanced-expand' => 'Hō‘ike i nā kikoʻī',
'rc-enhanced-hide' => 'Hoʻohūnā i nā kikoʻī',

# Recent changes linked
'recentchangeslinked' => 'Nā loli hou ʻālike',
'recentchangeslinked-feed' => 'Nā loli hou ʻālike',
'recentchangeslinked-toolbox' => 'Nā loli hou ʻālike',
'recentchangeslinked-title' => 'Nā loli e ʻālike me "$1"',
'recentchangeslinked-summary' => 'He papahelu o nā loli i hana wale i nā ʻaoʻao loulou ʻia e kekahi ʻaoʻao kikoʻī (aiʻole i nā lālā o kekahi mahele kikoʻī) kēia.
<strong>Hoʻokāʻele</strong> nā ʻaoʻao ma [[Special:Watchlist|kāu papakiaʻi]].',
'recentchangeslinked-page' => 'Inoa ʻaoʻao:',
'recentchangeslinked-to' => 'Hōʻike i nā loli i nā ʻaoʻao e loulou ʻia ma kahi o ka ʻaoʻao i hāʻawi ʻia',

# Upload
'upload' => 'Hoʻouka waihona',
'uploadbtn' => 'Hoʻouka i ka waihona',
'uploadnologin' => 'ʻE‘e ʻole',
'uploaderror' => 'Hewa hoʻouka',
'uploadlogpage' => 'Moʻolelo hoʻouka',
'filename' => 'Inoa waihona',
'filedesc' => 'Hōʻuluʻulu manaʻo',
'fileuploadsummary' => 'Hōʻuluʻulu manaʻo:',
'filesource' => 'Kumu:',
'uploadedfiles' => 'Waihona hoʻouka ʻia',
'savefile' => 'Waihona mālama',
'uploadedimage' => 'ua hoʻouka iā "[[$1]]"',
'upload-source' => 'Waihona kūmole',
'sourcefilename' => 'Inoa waihona kūmole:',
'sourceurl' => 'URL kūmole:',

'license' => 'Laikini:',
'license-header' => 'Laikini',

# Special:ListFiles
'imgfile' => 'waihona',
'listfiles' => 'Papahelu waihona',
'listfiles_thumb' => 'Kiʻiliʻi',
'listfiles_date' => 'Lā',
'listfiles_name' => 'Inoa',
'listfiles_user' => 'Mea hoʻohana',
'listfiles_size' => 'Nui',
'listfiles_description' => 'Hōʻike ʻAno',
'listfiles_count' => 'Mana',
'listfiles-latestversion' => 'Mana okamanawa',
'listfiles-latestversion-yes' => 'ʻAe',
'listfiles-latestversion-no' => 'ʻAʻole',

# File description page
'file-anchor-link' => 'Waihona',
'filehist' => 'Mōʻaukala waihona',
'filehist-help' => 'Kāomi ma ka lā/hola no ka nānā ʻana i ka waihona ma kēlā manawa.',
'filehist-deleteall' => 'holoi apau',
'filehist-deleteone' => 'holoi',
'filehist-revert' => 'hoʻihoʻi',
'filehist-current' => 'okamanawa',
'filehist-datetime' => 'Lā/Hola',
'filehist-thumb' => 'Kiʻiliʻi',
'filehist-thumbtext' => 'Ke kiʻiliʻi no ka mana ma $1',
'filehist-nothumb' => 'ʻAʻohe kiʻiliʻi',
'filehist-user' => 'Mea ho‘ohana',
'filehist-dimensions' => 'Nā Nui',
'filehist-filesize' => 'Nui o ka waihona',
'filehist-comment' => 'Kaumanaʻo',
'filehist-missing' => 'Nele ka waihona',
'imagelinks' => 'Nā Hana waihona',
'linkstoimage' => 'Loulou {{PLURAL:$1|kekahi ‘ao‘ao|kēia mau ‘ao‘ao $1}} i kēia waihona:',
'nolinkstoimage' => 'ʻAʻohe ʻaoʻao e loulou i kēia waihona.',
'sharedupload-desc-here' => 'ʻO kēia waihona mai $1 a hiki paha ke hana ʻia mai nā papahana ʻē aʻe.
Aia i lalo ka hōʻike ʻano [mai ka ʻaoʻao hōʻike ʻano waihona $2].',
'shared-repo-from' => 'mai $1',

# File reversion
'filerevert-comment' => 'Kumu:',

# File deletion
'filedelete' => 'Holoi iā $1',
'filedelete-legend' => 'Holoi i ka waihona',
'filedelete-comment' => 'Kumu:',
'filedelete-submit' => 'Holoi',
'filedelete-otherreason' => 'Nā kumu ʻē aʻe:',
'filedelete-reason-otherlist' => 'Nā kumu ʻē aʻe',

# MIME search
'download' => 'hoʻoili',

# Random page
'randompage' => 'ʻAtikala Kaulele',

# Statistics
'statistics' => 'ʻIkepilihelu',

'brokenredirects-edit' => 'ho‘ololi',
'brokenredirects-delete' => 'holoi',

'withoutinterwiki-submit' => 'Hō‘ike',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|‘ai|mau ‘ai}}',
'nlinks' => '$1 {{PLURAL:$1|loulou|loulou}}',
'nmembers' => '$1 {{PLURAL:$1|lālā|mau lālā}}',
'wantedcategories' => 'Nā māhele makemake',
'prefixindex' => 'Nā ʻAoʻao apau me ka pākuʻina kau mua',
'shortpages' => 'Nā ʻaoʻao pōkole',
'longpages' => 'Nā ʻaoʻao lōʻihi',
'usercreated' => '{{GENDER:$3|Haku ʻia}} i ka lā $1 ma ka hola $2',
'newpages' => 'Nā ‘Ao‘ao hou',
'newpages-username' => "Inoa mea ho'ohana:",
'ancientpages' => 'Nā ‘ao‘ao kahiko loa',
'move' => 'E hoʻoneʻe',
'movethispage' => 'E hoʻoneʻe i kēia ʻaoʻao',
'pager-newer-n' => '{{PLURAL:$1|1 hou aku|$1 hou aku}}',
'pager-older-n' => '{{PLURAL:$1|1 aku nei|$1 aku nei}}',

# Book sources
'booksources' => 'Kumu puke',
'booksources-search-legend' => 'Huli i nā kūmole  puke',
'booksources-go' => 'E huli',

# Special:Log
'log' => 'Nā Mo‘olelo',
'all-logs-page' => 'Nā Moʻolelo lehulehu apau',

# Special:AllPages
'allpages' => 'Nā ‘Ao‘ao apau',
'alphaindexline' => '$1 i $2',
'nextpage' => 'Mea aʻe ($1)',
'prevpage' => 'Mea ma mua aʻe ($1)',
'allarticles' => 'Nā ʻAoʻao apau',
'allpagessubmit' => 'E huli',

# Special:Categories
'categories' => 'Nā Māhele',

# Special:DeletedContributions
'deletedcontributions' => 'Nā ha‘awina mea ho‘ohana i holoi ‘ia',
'deletedcontributions-title' => 'Nā ha‘awina mea ho‘ohana i holoi ‘ia',

# Special:LinkSearch
'linksearch' => 'Huli loulou kūwaho',
'linksearch-ok' => 'Huli',
'linksearch-line' => 'Loulou ʻia ʻo $1 mai $2',

# Special:ListUsers
'listusers-submit' => 'Hō‘ike',

# Special:ListGroupRights
'listgrouprights-members' => '(papainoa o nā lālā)',

# Email user
'emailuser' => 'E leka uila i kēia mea ho‘ohana',
'emailusername' => 'Inoa mea hoʻohana:',
'emailusernamesubmit' => 'Waiho',
'emailfrom' => 'Mai:',
'emailto' => 'Iā:',
'emailsubject' => 'Kumunui:',
'emailmessage' => 'Pūlono:',
'emailsend' => 'Hoʻouna',

# Watchlist
'watchlist' => 'Kaʻu papakiaʻi',
'mywatchlist' => 'Ka‘u papakiaʻi',
'watchlistfor2' => 'No $1 $2',
'removedwatchtext' => 'Wehe ʻia ʻo "[[:$1]]" mai [[Special:Watchlist|kāu papa nānā pono]].',
'watch' => 'E kia‘i',
'watchthispage' => 'E nānā pono i kēia mea',
'unwatch' => 'Kiaʻi ʻole',
'watchlist-details' => '{{PLURAL:$1|$1 ʻaoʻao|$1 mau ʻaoʻao}} a kāu papakiaʻi, me ʻole ke koe ʻana o nā ʻaoʻao walaʻau.',
'wlshowlast' => 'Hōʻike $1 hola aku nei $2 lā aku nei $3',
'watchlist-options' => 'Nā Koho papakiaʻi',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Kia‘i nei...',
'unwatching' => 'Ke wehe nei i ke kiaʻi...',

'changed' => 'ua loli ‘ia',

# Delete
'deletepage' => 'Holoi ʻaoʻao',
'actioncomplete' => 'Hana kūleʻa',
'actionfailed' => 'Hana pohō',
'deletedtext' => 'Ua holoi ʻia ʻo "$1".
E ʻike iā $2 no ka papa o nā holoi hou.',
'dellogpage' => 'Mo‘olelo holoi',
'dellogpagetext' => 'He papahelu o nā holoi hou i lalo.',
'deletionlog' => 'mo‘olelo holoi',
'deletecomment' => 'Kumu:',
'deleteotherreason' => 'Kumu ʻē aʻe/hoʻokomo',
'deletereasonotherlist' => 'Kumu ʻē aʻe',
'delete-edit-reasonlist' => 'Hoʻololi i nā kumu holoi',

# Rollback
'rollbacklink' => 'ho‘ihoʻi',

# Protect
'protectlogpage' => 'Moʻolelo palekana',
'protectedarticle' => 'ua hoʻomalu iā "[[$1]]"',
'prot_1movedto2' => 'Ua hoʻoneʻe ʻo [[$1]] iā [[$2]]',
'protectcomment' => 'Kumu:',
'protect-default' => 'ʻAe i nā mea hoʻohana a pau',
'protect-level-sysop' => 'Nā Kahu wale nō',
'protect-cantedit' => 'ʻAʻole hiki iā ʻoe ke hoʻololi i nā kūlana māmalu o kēia ʻaoʻao, no ka mea, ʻaʻohe āu ʻae no ka hoʻololi ʻana.',
'protect-expiry-options' => '1 hola:1 hour,1 lā:1 day,1 pule:1 week,2 pule:2 weeks,1 mahina:1 month,3 mahina:3 months,6 mahina:6 months,1 makahiki:1 year,pau ʻole:infinite',
'restriction-type' => 'ʻAe ʻia:',

# Restrictions (nouns)
'restriction-edit' => 'Hoʻololi',
'restriction-move' => 'Hoʻoneʻe',

# Undelete
'undeletebtn' => 'Ho‘āla',
'undeletelink' => 'nānā/ho‘āla',
'undeleteviewlink' => 'hōʻike',
'undelete-search-submit' => 'Huli',

# Namespace form on various pages
'namespace' => 'Lewainoa:',
'invert' => 'Kuapo i ke koho',
'blanknamespace' => '(‘ano nui)',

# Contributions
'contributions' => 'Nā haʻawina o ka {{GENDER:$1|mea hoʻohana}}',
'contributions-title' => 'Nā Hāʻawina mea hoʻohana no $1',
'mycontris' => 'Kaʻu mau haʻawina',
'contribsub2' => 'No {{GENDER:$3|$1}} ($2)',
'uctop' => '(okamanawa)',
'month' => 'Mai ka mahina (mamua aku nei nō hoʻi):',
'year' => 'Mai ka makahiki (mamua aku nei nō hoʻi):',

'sp-contributions-newbies' => 'Hōʻike i nā hāʻawina o nā moʻokāki hou wale nō',
'sp-contributions-blocklog' => 'moʻolelo hoʻopale',
'sp-contributions-deleted' => 'nā ha‘awina o ka inoa mea ho‘ohana i holoi ‘ia',
'sp-contributions-uploads' => 'nā hoʻouka',
'sp-contributions-logs' => 'nā moʻolelo',
'sp-contributions-talk' => 'walaʻau',
'sp-contributions-userrights' => 'ka hoʻoponopono ʻana o nā kūleana mea hoʻohana',
'sp-contributions-search' => 'Huli no nā haʻawina',
'sp-contributions-username' => 'Wahinoho IP aiʻole inoa mea hoʻohana:',
'sp-contributions-toponly' => 'Hōʻike wale nō i nā hoʻololi kāmua hou loa',
'sp-contributions-submit' => 'Huli',

# What links here
'whatlinkshere' => 'He aha ka mea e loulou iho ai',
'whatlinkshere-title' => 'Nā ʻAoʻao e loulou iā "$1"',
'whatlinkshere-page' => '‘Ao‘ao:',
'linkshere' => 'Loulou kēia mau ʻaoʻao iā <strong>[[:$1]]</strong>:',
'nolinkshere' => "ʻAʻohe ‘ao‘ao e loulou iā '''[[:$1]]'''.",
'isredirect' => 'ʻaoʻao kia hou',
'istemplate' => 'kumo',
'isimage' => 'loulou waihona',
'whatlinkshere-prev' => '{{PLURAL:$1|mua aku nei|$1 mua aku nei}}',
'whatlinkshere-next' => '{{PLURAL:$1|hou aʻe|$1 hou aʻe}}',
'whatlinkshere-links' => '← nā loulou',
'whatlinkshere-hideredirs' => '$1 i nā kiahou',
'whatlinkshere-hidetrans' => '$1 i nā kumo',
'whatlinkshere-hidelinks' => '$1 i nā loulou',
'whatlinkshere-hideimages' => '$1 i nā loulou waihona',
'whatlinkshere-filters' => 'Kānana',

# Block/unblock
'blockip' => 'Pale i kēia mea ho‘ohana',
'ipbexpiry' => 'Pau āhea:',
'ipbreason' => 'Kumu:',
'ipbsubmit' => 'Pale i kēia mea ho‘ohana',
'ipbother' => 'Manawa ʻē aʻe:',
'ipboptions' => '2 mau hola:2 hours,1 lā:1 day,3 mau lā:3 days,1 pule:1 week,2 mau pule:2 weeks,1 mahina:1 month,3 mau mahina:3 months,6 mau mahina:6 months,1 makahiki:1 year,wā pau ʻole:infinite',
'badipaddress' => 'Wahinoho IP hewa',
'ipblocklist' => 'Nā Mea hoʻohana pale ʻia',
'ipblocklist-submit' => 'Huli',
'infiniteblock' => 'pau ʻole',
'anononlyblock' => 'nā inoaʻole wale nō',
'blocklink' => 'hoʻopale',
'unblocklink' => 'hoʻopale ʻole',
'change-blocklink' => 'hoʻololi i ka palena',
'contribslink' => 'ha‘awina',
'blocklogpage' => 'Moʻolelo hoʻopale',
'blocklogentry' => 'ua hoʻopale ʻia ʻo [[$1]] no ka manawa o $2 $3',
'block-log-flags-nocreate' => 'ua hoʻopale ʻia ke kāinoa moʻokāki ʻana',

# Move page
'move-page-legend' => 'Hoʻoneʻe i ka ʻaoʻao',
'movearticle' => 'E hoʻoneʻe i ka ʻaoʻao:',
'newtitle' => 'I ka inoa hou:',
'move-watch' => 'Kiaʻi i ka ʻaoʻao kumu a me ka ʻaoʻao māka',
'movepagebtn' => 'Hoʻoneʻe i ka ʻaoʻao',
'pagemovedsub' => 'Kūleʻa ka hoʻoneʻe ʻana',
'movepage-moved' => '\'\'\'Ua hoʻoneʻe ʻia ʻo "$1" iā "$2"\'\'\'',
'movelogpage' => 'Hoʻoneʻe i ka moʻolelo',
'movereason' => 'Kumu:',
'revertmove' => 'hoʻihoʻi',
'delete_and_move' => 'Holoi a hoʻoneʻe',
'delete_and_move_confirm' => '‘Ae, e holoi i ka ‘ao‘ao',

# Export
'export' => 'Kāpuka ʻaoʻao',
'export-addcat' => 'Ho‘ohui',

# Namespace 8 related
'allmessages' => 'Pūlono ʻōnaehana',
'allmessagesname' => 'Inoa',
'allmessagesdefault' => 'Kikokikona pa‘amau',
'allmessagescurrent' => 'Kikokikona i kēia manawa',

# Thumbnails
'thumbnail-more' => 'Ho‘onui',
'thumbnail_error' => 'Loaʻa i ka hewa ka haku ʻana o ke kiʻiliʻi: $1',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Kāu ʻaoʻao mea hoʻohana',
'tooltip-pt-mytalk' => 'Kāu walaʻau',
'tooltip-pt-preferences' => 'Kāu makemake',
'tooltip-pt-watchlist' => 'He papahelu o nā ʻaoʻao āu e kiaʻi nei no nā loli',
'tooltip-pt-mycontris' => 'Kāu mau ha‘awina',
'tooltip-pt-login' => 'Pai ‘ia ‘oe e ‘e‘e, akā, ‘a‘ole ia he koina',
'tooltip-pt-logout' => 'Ha‘alele',
'tooltip-ca-talk' => 'Kūkākūkā e pili ana i ka ʻaoʻao mealoko',
'tooltip-ca-edit' => 'Hiki iā ‘oe ke ho‘ololi i kēia ‘ao‘ao. Ma mua o ka mālama ʻia ʻana, e ho‘ohana i ke pihi nāmua, ke ‘olu‘olu.',
'tooltip-ca-addsection' => 'Hoʻomaka i kekahi pauku hou',
'tooltip-ca-viewsource' => 'Hoʻomalu ʻia kēia ʻaoʻao.
Hiki iā ʻoe ke ʻike i kāna kūmole.',
'tooltip-ca-history' => 'Nā kāmua mamua o kēia ʻaoʻao',
'tooltip-ca-protect' => 'E ho‘omalu i keia ‘ao‘ao',
'tooltip-ca-delete' => 'E holoi i kēia ʻaoʻao',
'tooltip-ca-move' => 'E hoʻoneʻe i kēia ʻaoʻao',
'tooltip-ca-watch' => 'Hoʻohui i kāu papakiʻai',
'tooltip-ca-unwatch' => 'Hoʻowehe i kēia ʻaoʻao mai kāu papakiaʻi',
'tooltip-search' => 'Huli iā {{SITENAME}}',
'tooltip-search-go' => 'Kele i kekahi ʻaoʻao me kēia inoa inā hiki ke loaʻa',
'tooltip-search-fulltext' => 'Huli i nā ʻaoʻao no kēia kikokikona',
'tooltip-p-logo' => 'Kele i ka papa kinohi',
'tooltip-n-mainpage' => 'Kele i ka papa kinohi',
'tooltip-n-mainpage-description' => 'Kele i ka papa kinohi',
'tooltip-n-portal' => 'No ka papahana, nā hana hiki, nāhi no ka loaʻa ʻana',
'tooltip-n-currentevents' => 'Loaʻa nā ʻike kūmole e pili ana i nā nūhou',
'tooltip-n-recentchanges' => 'Nā loli hou ma ka wiki',
'tooltip-n-randompage' => 'Hoʻouka i kekahi ʻaoʻao kaulele',
'tooltip-n-help' => 'Kahi e aʻo mai',
'tooltip-t-whatlinkshere' => 'He papahelu o nā ʻaoʻao wiki apau e loulou i ʻaneʻi',
'tooltip-t-recentchangeslinked' => 'Nā loli hou i nā ʻaoʻao i loulou ʻia mai kēia ʻaoʻao',
'tooltip-feed-atom' => 'Hānaīke Atom no kēia ʻaoʻao',
'tooltip-t-contributions' => 'He papahelu o nā hāʻawina o ka mea hoʻohana',
'tooltip-t-emailuser' => 'Leka uila i kēia mea hoʻohana',
'tooltip-t-upload' => 'Ho‘ouka i nā waihona',
'tooltip-t-specialpages' => 'He papainoa o nā ʻaoʻao kūikawā apau',
'tooltip-t-print' => 'Mana paʻi pono o kēia ʻaoʻao',
'tooltip-t-permalink' => 'Loulou paʻa no kēia kāmua o ka ʻaoʻao',
'tooltip-ca-nstab-main' => 'Nānā i ka ʻaoʻao mealoko',
'tooltip-ca-nstab-user' => 'Nānā i ka ʻaoʻao mea hoʻohana',
'tooltip-ca-nstab-special' => 'He ʻaoʻao kūikawā kēia; ʻaʻole hiki iā ʻoe ke hoʻololi',
'tooltip-ca-nstab-project' => 'Nānā i ka ‘ao‘ao papahana',
'tooltip-ca-nstab-image' => 'Nānā i ka ʻaoʻao waihona',
'tooltip-ca-nstab-template' => 'Nānā i ke anakuhi',
'tooltip-ca-nstab-help' => 'Nānaina i ka ʻaoʻao kōkua',
'tooltip-ca-nstab-category' => 'Nānā i ka ‘ao‘ao mahele',
'tooltip-minoredit' => 'Kaha i kēia me he hoʻololi iki',
'tooltip-save' => 'Mālama i kāu mau loli',
'tooltip-preview' => 'E nāmua i kāu mau loli ma mua o ka mālama ʻana ke ʻoluʻolu!',
'tooltip-diff' => 'Hōʻike i nā loli āu i hana ai i kēia kikokikona',
'tooltip-compareselectedversions' => 'E ʻike i na ʻokoʻa ma waena o nā kāmua ʻelua i koho ʻia o kēia ʻaoʻao',
'tooltip-watch' => 'Hoʻohui i kāu papakiʻai',
'tooltip-rollback' => 'Hoʻihoʻi ʻo "Hoʻihoʻi" i nā hoʻololi i kēia ʻaoʻao o ka mea hāʻawi hopeloa i hoʻokahi kāomi',
'tooltip-undo' => 'Hoʻihoʻi ʻo "Hōʻole" i kēia hoʻololi a wehe ia i ka ʻaoʻao hoʻololi i ke ʻano nāmua. ʻAe ia i ka hoʻohui ʻana i kekahi kumu i loko o ka hōʻuluʻulu manaʻo.',
'tooltip-summary' => 'Kikokiko i kekahi hōʻuluʻulu manaʻo pōkole',

# Browsing diffs
'previousdiff' => '← Hoʻololi aku nei',
'nextdiff' => 'Hoʻololi hou aʻe →',

# Media information
'file-info-size' => '$1 x $2 kiʻiʻuku, nui waihona: $3, ʻano MIME: $4',
'file-nohires' => 'Loaʻa ʻole ka miomio aʻe.',
'svg-long-desc' => 'Waihona SVG, $1 x $2 mau pikela, nui waihona: $3',
'show-big-image' => 'Waihona kumu',

# Special:NewFiles
'ilsubmit' => 'Huli',

# Bad image list
'bad_image_list' => 'ʻO kēia ka hulu:

Noʻonoʻo pono wale no i nā ʻikamu papahelu (nā laina e hoʻomaka ʻia me *).
Pono ka loulou mua loa ma kekahi laina e loulou i kekahi waihona ʻino.
Noʻonoʻo ʻia nā loulou heleiho ma kēlā laina like i nā kūʻē lula, he laʻana kēia, nā ʻaoʻao e loaʻa i ka waihona i loko o ka laina.',

# Metadata
'metadata' => 'ʻIkepiliMeta',
'metadata-help' => 'Loaʻa i kēia waihona nā ʻike ʻē aʻe i hoʻohui ʻia paha mai kekahi pahupaʻakiʻi aiʻole kekahi mīkinikopekiʻi i hana ʻia no ka haku ʻana aiʻole ka hoʻokamepiuila ʻana o ia.
Inā ua kāloli ʻia ka waihona mai kona ʻano kumu, hōʻike piha ʻole i kekahi o nā kikoʻī o ka waihona i kāloli ʻia.',
'metadata-fields' => 'E hoʻokomo ʻia ana nā kula ʻikepiliMeta kiʻi i loko o kēia pūlono ma ka hōʻike ʻaoʻao kiʻi oiai ka hoʻoliʻi ʻana o ke pākaukau ʻikepiliMeta.
Hoʻohuna paʻamau i nā mea ʻē aʻe
* kahana lōkō
* kaʻano
* kalāholakumu
* holahuʻena
* heluf
* kūlanawikiiso
* loaaniani
* meahana
* kūleanakope
* hōʻikeʻanokiʻi
* lakikūgps
* lonikūgps
* kiʻekiʻegps',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'nā mea apau',
'namespacesall' => 'nā mea apau',
'monthsall' => 'nā mea apau',

# action=purge
'confirm_purge_button' => 'Hiki nō',

# Multipage image navigation
'imgmultipageprev' => '← ʻaoʻao aku nei',
'imgmultipagenext' => 'ʻaoʻao hou aʻe →',

# Table pager
'table_pager_next' => 'ʻAoʻao hou aʻe',
'table_pager_prev' => 'ʻAoʻao aku nei',

# Auto-summaries
'autosumm-replace' => "Ke pani nei i ka mealoko me '$1'",
'autoredircomment' => 'Kiahou i ka ʻaoʻao iā [[$1]]',
'autosumm-new' => "Ua hoʻokumu ʻia kekahi ʻaoʻao me '$1'",

# Live preview
'livepreview-loading' => 'Ke ho‘ouka nei…',

# Watchlist editor
'watchlistedit-normal-title' => 'Hoʻololi i ka papakiaʻi',

# Watchlist editing tools
'watchlisttools-view' => 'Nānā i nā loli ʻālike',
'watchlisttools-edit' => 'Nānā a hoʻololi i ka papakiaʻi',
'watchlisttools-raw' => 'Hoʻololi i ka papakiaʻi maka',

# Core parser functions
'duplicate-defaultsort' => '<strong>E akahele:</strong> Mauʻaʻe ke kī kaʻalike paʻamau "$2" i ke kī kaʻalike paʻamau "$1" mai ka wā mua.',

# Special:Version
'version-specialpages' => 'Nā ‘Ao‘ao kūikawā',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Huli',

# Special:SpecialPages
'specialpages' => 'Nā ‘Ao‘ao kūikawā',

# External image whitelist
'external_image_whitelist' => ' #Waiho i kēia laina<pre>
#Kau i nā hapa haʻi maʻamau (nā hapa e kau ʻia ma waena o nā //) i lalo
#Hoʻohālikelike ia mea me nā URL o nā kiʻi kūwaho (loulouhūnāloko)
#Hōʻike ʻia ia mea e hoʻohālikelike me he mau kiʻi, inā ʻaʻole pēlā e hōʻike wale me he loulou no ke kiʻi wale nō
#Mālama ʻia nā laina e hoʻomaka me ka # e like me nā kaumanaʻo
#Kākau wale, mai hopohopo e pili ana nā ʻaui

#Kau i nā hapa ligaka apau ma luna o kēia laina. Wahiho i kēia laina</pre>',

# Special:Tags
'tag-filter' => 'Kānana [[Special:Tags|lepili]]:',
'tags-edit' => 'hoʻololi',

# Special:ExpandTemplates
'expand_templates_ok' => 'Hiki nō',
'expand_templates_preview' => 'Nāmua',

);

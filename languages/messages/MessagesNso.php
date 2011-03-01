<?php
/** Northern Sotho (Sesotho sa Leboa)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Mohau
 * @author Urhixidur
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Bolediša',
	NS_USER             => 'Mošomi',
	NS_USER_TALK        => 'Boledišana_le_Mošomi',
	NS_PROJECT_TALK     => '$1_Poledišano',
	NS_FILE             => 'Seswantšho',
	NS_FILE_TALK        => 'Poledišano_ya_Seswantšho',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Poledišano_ya_MediaWiki',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Poledišano_ya_Template',
	NS_HELP             => 'Thušo',
	NS_HELP_TALK        => 'Poledišano_ya_Thušo',
	NS_CATEGORY         => 'Setensele',
	NS_CATEGORY_TALK    => 'Poledišano_ya_Setensele',
);

$magicWords = array(
	'currentmonth'          => array( '1', 'KGWEDI_BJALE', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'LEINA_KGWEDI_BJALE', 'CURRENTMONTHNAME' ),
	'currentday'            => array( '1', 'LEHONO_LETSATSI', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'LEHONO_LETSATSI2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'LEHONO_LETSATSILEINA', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'NGWAGA_BJALE', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'NAKO_BJALE', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'IRI_BJALE', 'CURRENTHOUR' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Tsenya mothalafase go dihlomaganyo:',
'tog-highlightbroken'         => 'Laetša dihlomaganyo tša go senyega <a href="" class="new">ga mokgwa wo</a> (goba: ka mokgwa wo<a href="" class="internal">?</a>).',
'tog-hideminor'               => 'Fihla diphetogo tše nnyenyane',
'tog-showtoolbar'             => "Bontšha ''toolbar'' yago fetola (JavaScript)",
'tog-editondblclick'          => 'Fetola matlakala ka go thathapa gabedi (JavaScript)',
'tog-editsection'             => 'Dumella go fetola sekgao ka [fetola] hlomaganyo',
'tog-editsectiononrightclick' => 'Dumella go fetola sekgao ka thathapa ka lagoja thaetlele ya sekgao (JavaScript)',
'tog-showtoc'                 => 'Bontšha Tatelano ya dikagare (go matlakala a goba le dihlogo tša go feta 3)',
'tog-rememberpassword'        => 'Gopola sedi yaka ya go tsena khomphutha ye (bogolo bja  $1 bja {{PLURAL:$1| ya letšatši le|ya matšatši}})',
'tog-watchcreations'          => 'Tsenya matlaka a mafsa ao ke a ngwalago go lenano laka la ditlhapetšo',
'tog-watchdefault'            => 'Tsenya matlaka ao ke a fetolago go lenano laka la ditlhapetšo',
'tog-watchmoves'              => 'Tsenya matlaka ao ke a hudušago go lenano laka la ditlhapetšo',
'tog-watchdeletion'           => 'Tsenya matlaka ao ke a phumulago go lenano laka la ditlhapetšo',
'tog-minordefault'            => 'Swaya diphetogo ka moka bjalo ka diphetogo tše nnyenyane',
'tog-previewontop'            => 'Bontšha Ponopele pele ga lepokisi la diphetogo',
'tog-previewonfirst'          => 'Bontšha Ponopeleka phetogo ya pele',
'tog-nocache'                 => "Thibela go tsenya matlakala go segakolodi (''cache'')",
'tog-enotifwatchlistpages'    => 'Nromele molaetša ge letlaka leo ke le tlhapetšego le eba le diphetogo',
'tog-enotifusertalkpages'     => 'Nromele molaetša ge letlakala la Dipoledišano laka le fetoga',
'tog-enotifminoredits'        => 'Nromele email ge goba le diphetogo tše nnyenyane go matlakala',
'tog-enotifrevealaddr'        => 'Bonagatša email atrese go temošo tša poso',
'tog-shownumberswatching'     => 'Laetša palo bašomiši bao ba tlhapetšego',
'tog-fancysig'                => 'Tsaeno ya gose fihliwe',
'tog-externaleditor'          => 'Šomiša sengwadi sa kantle (bašumiši bao banalego botsibi fela.  [http://www.mediawiki.org/wiki/Manual:External_editors More information.])',
'tog-forceeditsummary'        => 'Ntaetše ge kesa tsenye mongwalo go kakaretšo ya dithetogo',
'tog-watchlisthideown'        => 'Fihla diphetogo tšeo di direlego ke nna go lenano la ditlhapetšo.',
'tog-watchlisthideminor'      => 'Fihla diphetogo tše nyenyane tšeo di direlego ke nna go lenano la ditlhapetšo',
'tog-ccmeonemails'            => 'Nromele kopi ya melaetša yeo ke romelago bašumiši ba bangwe',

'underline-always' => 'Kamehla',
'underline-never'  => 'Le ga tee',

# Dates
'sunday'        => 'Sontaga',
'monday'        => 'Mošupologo',
'tuesday'       => 'Labobedi',
'wednesday'     => 'Laboraro',
'thursday'      => 'Labone',
'friday'        => 'Labohlano',
'saturday'      => 'Mokibelo',
'sun'           => 'Sontaga',
'mon'           => 'Mošupologo',
'tue'           => 'Labobedi',
'wed'           => 'Laboraro',
'thu'           => 'Labone',
'fri'           => 'Labohlano',
'sat'           => 'Mokibelo',
'january'       => 'Pherekgong',
'february'      => 'Dibokwane',
'march'         => 'Hlakola',
'april'         => 'Moranang',
'may_long'      => 'Mopitlo',
'june'          => 'Phupu',
'july'          => 'Mosegamanye',
'august'        => 'Phato',
'september'     => 'Lewedi',
'october'       => 'Diphalane',
'november'      => 'Dibatsela',
'december'      => 'Manthole',
'january-gen'   => 'Pherekgong',
'february-gen'  => 'Dibokwane',
'march-gen'     => 'Hlakola',
'april-gen'     => 'Moranang',
'may-gen'       => 'Mopitlo',
'june-gen'      => 'Phupu',
'july-gen'      => 'Mosegamanye',
'august-gen'    => 'Phato',
'september-gen' => 'Lewedi',
'october-gen'   => 'Diphalane',
'november-gen'  => 'Dibatsela',
'december-gen'  => 'Manthole',
'jan'           => 'Pherekgong',
'feb'           => 'Dibokwane',
'mar'           => 'Hlakola',
'apr'           => 'Moranang',
'may'           => 'May',
'jun'           => 'Phupu',
'jul'           => 'Mosegamanye',
'aug'           => 'Phato',
'sep'           => 'Lewedi',
'oct'           => 'Diphalane',
'nov'           => 'Dibatsela',
'dec'           => 'Manthole',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Sehlopha|Dihlopha}}',
'category_header'        => 'Matlakala go sehlopha "$1"',
'subcategories'          => 'Dihlophana',
'category-media-header'  => 'Matlakala goba difaele go sehlopha "$1"',
'category-empty'         => "''Sehlopha se, ga se na matlakala goba difaele.''",
'hidden-categories'      => '{{PLURAL:$1|Sehlopha sago huta|Dihlopha tšago huta}}',
'category-subcat-count'  => '{{PLURAL:$2|"Category" ye, e nale "subcategory" ye fela.|"Category" ye, e nale {{PLURAL:$1|subcategory|$1 subcategories}}, go tše $2 ka palo.}}',
'category-article-count' => '{{PLURAL:$2|Sehlopha se, se na le letlakala le fela.| {{PLURAL:$1|Letlakala le, le |$1 ya matlakala a}} go sehlopha "category" se, go $2 ya matlakala.}}',
'listingcontinuesabbrev' => 'tšweletša',

'about'         => 'Mabapi',
'article'       => 'Letlakala la mateng',
'newwindow'     => '(e bula lefastere le lempsha)',
'cancel'        => 'Khansela',
'moredotdotdot' => 'Tše dingwe...',
'mypage'        => 'Letlakala la ka',
'mytalk'        => 'Dipolelo tša ka',
'anontalk'      => 'Poledišano ya IP ye',
'navigation'    => 'Tšwelotšo',
'and'           => '&#32;le',

# Cologne Blue skin
'qbfind'         => 'Humana',
'qbedit'         => 'Fetola',
'qbpageoptions'  => 'Letlakala le',
'qbmyoptions'    => 'Matlakala a ka',
'qbspecialpages' => 'Matlakala a itšego',

'errorpagetitle'    => 'Phošo',
'returnto'          => 'Boela go $1.',
'tagline'           => 'Gotšwa go {{SITENAME}}',
'help'              => 'Thušo',
'search'            => 'Fetleka',
'searchbutton'      => 'Fetleka',
'go'                => 'Sepela',
'searcharticle'     => 'Eya',
'history'           => 'Histori ya letlakala',
'history_short'     => 'Histori',
'updatedmarker'     => 'fetotšwe esale ketelo yaka ya mafelelo',
'info_short'        => 'Sedi',
'printableversion'  => "''Version'' ya go gatišega",
'permalink'         => 'Hlomaganyo yao e tiišeditšwego',
'edit'              => 'Fetola',
'create'            => 'Tlhoma',
'editthispage'      => 'Fetola letlakala  le',
'create-this-page'  => 'Tlhoma letlakala le',
'delete'            => 'Phumula',
'deletethispage'    => 'Phumula letlakala le',
'protect'           => 'Lota',
'protect_change'    => 'Fetola go lotega',
'protectthispage'   => 'Lota letlakala le',
'unprotect'         => 'Tloša go lota',
'unprotectthispage' => 'Tloša go lota letlakaleng',
'newpage'           => 'Letlakala le lempsha',
'talkpage'          => 'Rêrišana ka letlakala le',
'talkpagelinktext'  => 'Bolela',
'specialpage'       => 'Matlaka a itšeng',
'personaltools'     => "Dithulusu tša gago (''personal'')",
'postcomment'       => 'Sekgao se sempsha',
'articlepage'       => 'Nyakoretša letlakala la mateng',
'talk'              => 'Poledišano',
'views'             => 'Dinyakorêtšo',
'toolbox'           => 'Lepokisi la dithulusu',
'userpage'          => 'Nyakorela letlakala la mošomiši',
'projectpage'       => 'Nyakoretša letlakala la tirotherwa',
'imagepage'         => "Nyakoretša letlakala la ''file''",
'mediawikipage'     => 'Nyakoretša letlakala la melaetša',
'templatepage'      => "Nyakoretša letlakala la ''template''",
'viewhelppage'      => 'Nyakoretša letlakala la thušo',
'categorypage'      => 'Nyakoretša letlakala la sehlopha',
'viewtalkpage'      => 'Nyakoretša dipoledišano',
'otherlanguages'    => 'Ka dipolelo tše dingwe',
'redirectedfrom'    => "(''Redirect'' go tšwa $1)",
'redirectpagesub'   => "''Redirect'' letlakala",
'lastmodifiedat'    => 'Letlakala le  fetotšwe la mafelelo ka $2, $1.',
'viewcount'         => 'Letlakala le le butšwe ga {{PLURAL:$1|tee|$1}}.',
'protectedpage'     => 'Letlakala la go lotiwa',
'jumpto'            => 'Taboga go:',
'jumptonavigation'  => 'Tšweletšo',
'jumptosearch'      => 'fetleka',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Mabapi le {{SITENAME}}',
'aboutpage'            => 'Project:Mabapi',
'copyright'            => 'Mateng a hwetšagala tlase ga $1.',
'copyrightpage'        => '{{ns:project}}:Tomello ya Mongwadi',
'currentevents'        => 'Ditirago tša bjale',
'currentevents-url'    => 'Project:Ditiragalo tša bjale',
'disclaimers'          => 'Hlapa-matsogo',
'disclaimerpage'       => 'Project:Hlapa-Matsogo',
'edithelp'             => 'Thušo ya go fetola',
'edithelppage'         => 'Help:Fetola',
'helppage'             => 'Help:Mateng',
'mainpage'             => 'Letlakala la Pele',
'mainpage-description' => 'Letlakala la Pele',
'policy-url'           => 'Project:Melao',
'portal'               => "''Portal'' ya badudi",
'portal-url'           => 'Project:Portal ya Badudi',
'privacy'              => 'Melao ya praebesi',
'privacypage'          => 'Project:Polisi ya praefesi',

'badaccess'        => 'Thušo ya tumello',
'badaccess-group0' => 'Ga wa dumelwa go dira seo o lekago go se dira.',
'badaccess-groups' => 'Seo o lekago go se dira se dumelwetše go bašomiši bao balego {{PLURAL:$2|dihlopeng tša|sehlopheng sa}}: $1.',

'versionrequired'     => 'Version $1 ya MediaWiki ea hlokega',
'versionrequiredtext' => 'Version $1 ya MediaWiki ea hlokega go šomiša letlakala le. Lebelela [[Special:Version|letlakala la version]].',

'retrievedfrom'           => 'Le tšwa go "$1"',
'youhavenewmessages'      => 'O na le $1 ($2).',
'newmessageslink'         => 'ya melaetša ye mefsa',
'newmessagesdifflink'     => 'phetogo ya mafelelo',
'youhavenewmessagesmulti' => 'O nale melaetša ye mefsa go $1',
'editsection'             => 'lokiša',
'editold'                 => 'fetola',
'editlink'                => 'Fetola',
'viewsourcelink'          => 'nyakorela mothopo',
'editsectionhint'         => 'Fetola sekgao: $1',
'toc'                     => 'Mateng',
'showtoc'                 => 'bontšha',
'hidetoc'                 => 'fihla',
'thisisdeleted'           => 'Nyakorela goba hlaphola $1?',
'viewdeleted'             => 'Nyakorela $1?',
'restorelink'             => '{{PLURAL:$1|e tee phetogo ye phumutšwego|phetogo tše $1 tše phumutšwego}}',
'site-rss-feed'           => '$1 RSS Feed',
'site-atom-feed'          => '$1 Atom Feed',
'page-rss-feed'           => '"$1" RSS Feed',
'page-atom-feed'          => '"$1" Atom feed',
'red-link-title'          => '$1 (ga e hwetšagale)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Letlakala',
'nstab-user'      => 'Letlakala la mošomiši',
'nstab-special'   => 'Letlakala le itšeng',
'nstab-project'   => 'Letlakala la tirotherwa',
'nstab-image'     => 'Faele',
'nstab-mediawiki' => 'Molaetša',
'nstab-template'  => 'Template',
'nstab-help'      => 'Letlakala la thuso',
'nstab-category'  => 'Sehlopha',

# Main script and global functions
'nosuchspecialpage' => 'Gago letlaka le itšego le bjalo',

# General errors
'error'              => 'Phošo',
'databaseerror'      => 'Phošo ya Database',
'readonly'           => "''Database'' e notletšwe",
'enterlockreason'    => 'Fana la lebaka la go notlela, o fana le tekanyetšo yage senotlolo se tlogo tlošwa',
'missing-article'    => '"Database" ga ya humana  dihlaka tša letlakala tšeo e bego e swanela go di humana, tša maina  "$1" $2.

Se, gantši se hlolwa ke ge o latela hlomanyo goba history ya kgale goya letlakaleng leo le phumitšego.


Gaeba lebaka e se leo, go ka be go na le tšhikidi go "software".
Bega se go  [[Special:ListUsers/sysop|administrator]], o fana ka URL.',
'missingarticle-rev' => '(Thumeletšo#: $1)',
'internalerror'      => 'Phošo ya ka gare',
'internalerror_info' => 'Phošo ya ka gare :$1',
'filecopyerror'      => 'Gara kgona go ngwalolla faele "$1" go "$2".',
'filerenameerror'    => 'Gara kgona go fetola leina la faele "$1" goba "$2".',
'filedeleteerror'    => 'Gara kgona go phumula faele "$1".',
'filenotfound'       => 'Gara kgona go humana faele "$1".',
'fileexistserror'    => 'Gara kgona go ngawala faele "$1":faele e gona',
'badtitle'           => 'Taetlile ya bošula',
'badtitletext'       => 'Letlakala le ga la dumelelwa, ga le na ditlhaka, goba hlomaganyo ya bogare-dipolelo goba bogare-wiki taetlele ga ya loka. Ekaba mohlomong taetlele enale hlaka goba dihlaka tšago sedumelelwe.',
'viewsource'         => 'Lebelela mothopo',
'viewsourcefor'      => 'ya $1',
'protectedpagetext'  => 'Letlakala le le notletšwe go thibela diphetogo.',
'viewsourcetext'     => 'O ka lebelela goba wa kôpiša mothapo wa letlakala le:',
'namespaceprotected' => "Ga ona tokelo ya go fetola matlakala  go  '''$1''' .",

# Login and logout pages
'welcomecreation'            => "Oa amogelwa, $1! ==

Tšhupaleloko (''account'') ya gago e tlhodilwe. O seke wa lebala go fetola [[Special:Preferences|{{SITENAME}} dikgetho/thato tša gago]].",
'yourname'                   => 'Leina la mošomiši:',
'yourpassword'               => 'Ditlhaka-tša-siphiri:',
'yourpasswordagain'          => 'Tlanya ditlhaka-tša-siphiri gape:',
'remembermypassword'         => 'Gopola sedi yaka ya go tsena khomphutha ye (bogolo bja  $1 ya {{PLURAL:$1| letšatši le|matšatši a}})',
'login'                      => 'Tsena',
'nav-login-createaccount'    => "Tsena / Tlhola tšhupaleloko (''account'')",
'loginprompt'                => "O swanela ke go dumella ''cookies'' go ''browser'' go tsena go {{SITENAME}}.",
'userlogin'                  => "Tsena / tlhola tšhupaleloko (''account'')",
'logout'                     => 'Etšwa/Tswalela',
'userlogout'                 => 'Etšwa/Tswalela',
'notloggedin'                => 'Ga wa tsena',
'nologin'                    => "A  o nale sedi ya go tsena? '''$1'''.",
'nologinlink'                => "Bula tšhupaleloko (''account'')",
'createaccount'              => 'Bula tšhupaleloko',
'gotaccount'                 => "O šetše o nale tšhupaleloko? '''$1'''.",
'gotaccountlink'             => 'Tsena',
'createaccountmail'          => 'ka e-mail',
'createaccountreason'        => 'Lebaka:',
'badretype'                  => 'Ditlhaka-tša-siphiri tše o di šomišitšego ga di swane.',
'noname'                     => 'Gawa fana ka leina la mošomiši la go loka.',
'loginsuccesstitle'          => 'O tsene ka katlego',
'loginsuccess'               => "'''Bjale o tsene go {{SITENAME}} bjalo ka \"\$1\".'''",
'nosuchuser'                 => 'Ga gona mošomiši wa leina la "$1".
Maina a huduetša ke ditlhaka.
Lebele mopeleto wa gago goba [[Special:UserLogin/signup|o tlhome mošomiši yo mophsa]].',
'nosuchusershort'            => 'Ga gona mošomiši wa leina la "<nowiki>$1</nowiki>". Hlokomela mopeleto wa gago.',
'nouserspecified'            => 'O swanela ke go fana ka leina la mošomiši.',
'wrongpassword'              => 'O loketše ditlhaka-tša-siphiri tšeo e sego tšona. Ka kgopelo, leka gape.',
'wrongpasswordempty'         => 'Ga wa lokela ditlhaka-tša-siphiri. Ka kgopelo, leka gape.',
'passwordtooshort'           => "Ditlhaka-tša-siphiri tša gago ga tša dumelega goba di kopana.
Go nyakega gore e be le {{PLURAL:$1|tlhaka ye tee|$1 ya ditlhaka}} gape  e seke ya swana le leina la gago (''username'').",
'mailmypassword'             => 'Romela ditlhaka-tša-siphiri tše mpšha ka e-mail',
'passwordremindertitle'      => "''Password'' ye mphsa ya nakonyana go {{SITENAME}}",
'passwordremindertext'       => 'Motho yo mongwe (goba wena, gotšwa IP atrese $1) o
kgopetše gore re moromele Ditlhaka-tša-siphiri tše mfsa tša {{SITENAME}} ($4).

Ditlhaka-tša-siphiri tša  mošomiši "$2" go tloga bjale ke  "$3".
Eya go {{SITENAME}} o e fetole.

Ga eba motho yo mongwe esego wena o dirile kgopelo ye, goba o gopola Ditlhaka-tša-siphiri gomme ga o sa
hloka gore e fetolwe, hlokomologa molaetša wo, o tšwele pele o šumiše Ditlhaka-tša-siphiri tša kgale.',
'noemail'                    => 'Ga gona e-mail atrese ya mošomiši "$1".',
'passwordsent'               => "
Dihlaka tša siphiri (''password'') tše mphsa di rometšwe go e-mail atrese ya \"\$1\".
Re kgopela gore o tsene ge fetša go e hwetša.",
'blocked-mailpassword'       => 'IP atrese ya gago e thibetšwe go dira diphetogo, ka fao ga wa dumellwa
go šomiša thulusu ya go hwetša Ditlhaka-tša-siphiri go thibela go hlapanya.',
'eauthentsent'               => 'Molaetša wa go tiišetša o  rometšwe go e-mail atrese.

Pele re romela melaetša ye mengwe go atrese ye, o kgopelwa go latela ditaelo tšeo dilego molaetšeng go tiišetša gore atrese ke ya gago.',
'throttled-mailpassword'     => 'Kgopotšo ya ditlhaka-tša-siphiri e rometšwe {{PLURAL:$1|iring|diiring tše $1}} tša gofeta.
Go thibela go hlapanya/kgobošo, kgopotšo e tee ka {{PLURAL:$1|iri|diiri tše $1}} e tla romellwa.',
'mailerror'                  => 'Gobile le phošo go romeleng molaetša  : $1',
'acct_creation_throttle_hit' => 'Ka maswabi, o tlhomile {{PLURAL:$1|tšhupaleloko|$1 tša ditšhupaleloko}}.
Ga wa dumelwa go tlhoma tše dingwe.',
'emailauthenticated'         => 'E-mail atrese ya gago e kgonthišitšwe ka $2, $3.',
'accountcreated'             => 'Tšhupaleloko (Account) e tlhodilwe',
'accountcreatedtext'         => 'Tšhupaleloko (account) ya modiri $1 e tlhodilwe.',
'loginlanguagelabel'         => 'Polelo: $1',

# Password reset dialog
'resetpass_text'    => '<!-- Tsenya ditlhaka mo -->',
'oldpassword'       => 'Ditlhaka-tša-siphiri tša kgale:',
'newpassword'       => 'Ditlhaka-tša-siphiri tše mpsha:',
'retypenew'         => 'Tlanya ditlhaka tše mphsa tša siphiri gape:',
'resetpass_success' => 'Ditlhaka tša siphiri di fetotšwe ka katlego! Bjale o kgona go tsena...',

# Edit page toolbar
'bold_sample'     => "Mongwalo wa '''Bold'''",
'bold_tip'        => 'Ditlhaka tše Bold',
'italic_sample'   => 'Ditlhaka tše Italic',
'italic_tip'      => 'Mongwala wa Italic',
'link_sample'     => 'Thaetlele ya hlomaganyo',
'link_tip'        => 'Hlomaganyo ya kagare',
'extlink_sample'  => 'http://www.example.com hlomaganyo thaetlele',
'extlink_tip'     => 'Hlomaganyo ya ka ntle (gopola go thoma ka http://)',
'headline_sample' => 'Tlhaka ya hlogotaba',
'headline_tip'    => 'Hlogotaba ya boemo ba 2',
'math_sample'     => "Lokela ''formula'' mo",
'math_tip'        => 'Formula ya dipalo (LaTeX)',
'nowiki_sample'   => "Tsenya ditlhaka tša go sebe le ''format'' mo",
'nowiki_tip'      => "Hlokomologa tselangwalo (''formatting'') ya  wiki",
'image_sample'    => 'Mohlala.jpg',
'image_tip'       => "Seswantšho/Faele yago dikanelwa (''embedded'')",
'media_sample'    => 'Mohlala.ogg',
'media_tip'       => 'Hlomaganyo ya Faele',
'sig_tip'         => 'Tshaeno ya gago le nako ya phetogo',
'hr_tip'          => 'Mothalo wago ya faase/papamela (šomiša ka hloko)',

# Edit pages
'summary'                          => 'Kakaretšo:',
'subject'                          => 'Tabataba/Hlogo ya taba:',
'minoredit'                        => 'Ye ke phetogo ye nnyenyane',
'watchthis'                        => 'Tlhapetša letlakala le',
'savearticle'                      => 'Boloka letlakala',
'preview'                          => 'Lebelela',
'showpreview'                      => 'Laetša sebopego sa letlaka',
'showdiff'                         => 'Laetša diphetogo',
'anoneditwarning'                  => "'''Temošo''' Gawa ''tsena'', IP ya gago e tla šumišwa go histori ya diphetogo tša letlakala",
'summary-preview'                  => 'Lebelela kakaretšo:',
'blockedtitle'                     => 'Mošomiši o thibilwe',
'blockedtext'                      => "'''Leina la gago la mošomiši goba IP atrese e thibilwe.'''

O thibilwe ke $1. Makaba a go thiba ke ''$2''.

* Go thoma gago thiba: $8
* Fetatšatši yago thiba: $6
* Mothibiwa: $7

O ka leka go boledišana le $1 goba [[{{MediaWiki:Grouppage-sysop}}|molaudi]] ka go thibiwa go.
O ka se kgone go šumiša thulusu ya 'romela mošomiši molaetša' ka ntle gage o loketše e-mail ya gago go
[[Special:Preferences|dikgatlhegelo]] gape ge o sa thibelwa go e šomiša.
IP atrese ya gago ke $3, ge ID ya go thiba ele #$5. Ka kgopelo šumiša ID le IP go dipoledišano ka moka tšeo dilego mabapi le go go thiba.",
'blockednoreason'                  => 'gago lebaka leo le filwego',
'blockedoriginalsource'            => "Mothopo wa '''$1''' oa botšhwa tlase:",
'whitelistedittitle'               => 'O swanela ke go tsena go fetola',
'whitelistedittext'                => 'O swanela ke go $1 go fetola matlakala.',
'confirmedittext'                  => 'E-mail e swanetše ke go tiišetšwa pele ge o ka fetola matlakala. Ka kgopelo, tiišetša e-mail atrese ya gago go [[Special:Preferences|dikgatlhego tša mošomiši]].',
'nosuchsectiontitle'               => 'Sekgao ga se humanege',
'nosuchsectiontext'                => 'O lekile go fetola sekgao seo se sego gona. 
Se, se ka hlolwa ke ge letlakala le phumulwa goba la hudušwa ge wena o sa le lebeletše.',
'loginreqtitle'                    => 'Go tsena goa hlokega',
'loginreqlink'                     => 'tsena',
'loginreqpagetext'                 => 'O swanela ke go  $1 go nyakorela matlakala a mangwe.',
'accmailtitle'                     => 'Ditlhaka tša siphiri di rometšwe.',
'accmailtext'                      => 'Ditlhaka-tša-siphiri tša "$1"  di rometšwe go $2.',
'newarticle'                       => '(mpsha)',
'newarticletext'                   => "O latetše hlomaganyo go letlakala leo le sego gona ka se sebaka.
Go tlhola letlakala, thoma go ngwalo lepokising le letelago
(lebelela [[{{MediaWiki:Helppage}}|letlakala la thušo]] go hwetša šedi).
Ga eba o le fa ka phošo, o ka boela morago ka go šumiša konopo ya '''back''' go ''browser'' ya gago.",
'noarticletext'                    => 'Ga gona ditlhaka letlakaleng le, 
oka [[Special:Search/{{PAGENAME}}|fetleka liena la letlakala]] matlakaleng a mangwe,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} fetleka "logs"],
 goba [{{fullurl:{{FULLPAGENAME}}|action=edit}} wa fetola letlakala le]</span>.',
'note'                             => "'''Ela hloko:'''",
'previewnote'                      => "'''Ye ke Taetšo ya sebopego sa letlakala fela; diphetogo ga di ya bolokwa!'''",
'editing'                          => 'O fetola $1',
'editingsection'                   => 'Phetolo ya $1 (sekgoba)',
'editingcomment'                   => 'O fetola $1 (sekgao se sempsha)',
'editconflict'                     => 'Tholano ya diphetogo: $1',
'yourtext'                         => 'Mongwalo wa gago',
'storedversion'                    => 'Version yeo e bolokilwego',
'yourdiff'                         => 'Diphapang',
'copyrightwarning'                 => "Diabe kamoka go {{SITENAME}} di akanywa go ngwadiwa tlase ga $2 (lebelela $1 go hwetša taba ka bophara). Ge o sa nyake gore mengwalo ya gago e fetolwe ntle le kgaugelo goba e phatlalatšwe ntle le tumello ya gago, o seke wa fana ka mengwalo mo.<br />
Gape o re holofetša  gore mengwalo ye e ngwadile ke wena, goba o e kopiša mothapong wa pepeneneng goba  ke mahala.
'''O SE TSENYE MEŠOMO YA BATHO BA BANGWE NTLE LE TUMELLO YA BONA!'''",
'templatesused'                    => '"{{PLURAL:$1|Template yeo e|Di-Template tšeo di}}\'\' šomišitšwego letlakaleng le:',
'templatesusedpreview'             => '{{PLURAL:$1|"Template" yeo e|"DiTemplate" tšeo di}} šomišitšwego go taetšo ya sebopego sa letlakala:',
'template-protected'               => '(e lotilwe)',
'template-semiprotected'           => '(lota-ka-seripa)',
'hiddencategories'                 => 'Letlakala le, ke setho sa {{PLURAL:$1|1 sehlopha sago uta|$1 dihlopha tšago uta}}:',
'nocreatetext'                     => '{{SITENAME}} e nale dithibelo tše itšego go tlholeng ga matlakala a maphsa.
O ka boela morago wa felola matlakala a lego gona, goba o [[Special:UserLogin|tsene]].',
'nocreate-loggedin'                => 'Ga ona tumello ya go tlhola matlakala a mampsha.',
'permissionserrors'                => 'Phošo ya ditumello',
'permissionserrorstext'            => 'Gawa dumelwa go pheta seo,ka {{PLURAL:$1|lebaka|mabaka}} a latelago:',
'permissionserrorstext-withaction' => 'Ga ona tumello ya go $2, {{PLURAL:$1|lebala le|mabaka a}} latelago:',
'recreate-moveddeleted-warn'       => "'''Temošo: O leka go tlhoma letlakala le gape, ka ge le ile la phumulwa.'''

Sekaseka gore letlakala le lephumutšwe ka mabaka afe pele o leka go le fetola.
Sedi ya phumulo le go huduga ga letlakala e re:",

# History pages
'viewpagelogs'           => "Nyakoretša di-''log'' tša letlakala le",
'currentrev'             => 'Poeletšo tša bjale',
'currentrev-asof'        => 'Thumeletšo tša seswa go tloga ka $1',
'revisionasof'           => 'Thumeletšo go tloga ka $1',
'revision-info'          => 'Poeletšo go tloga $1 ka $2',
'previousrevision'       => '←Poeletšo tša kgalenyana',
'nextrevision'           => 'Peletšo tše dimphsanyana→',
'currentrevisionlink'    => 'Poeletšo ya bjale',
'cur'                    => 'bjale',
'next'                   => 'latela',
'last'                   => 'bofelo',
'page_first'             => 'mathomo',
'page_last'              => 'mafelelo',
'histlegend'             => "Tlhaolo ya diphapano: swaya lepokisi la phetogo go bapetša ke moka o thwanye ''enter'' tlase-tlase<br />
Nane: (bjale) = phapang le diphetogo tša bjale,
(mafelelo) = phapang le diphetogo tša pele, M = diphetogo tše nyenyane.",
'history-fieldset-title' => 'Laotša histori',
'histfirst'              => 'Pelepele',
'histlast'               => 'Ntshwantshwa',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',

# Revision feed
'history-feed-title'          => 'Histori ya poeletšo',
'history-feed-description'    => 'History ya poeletšo ya letlakala le go wiki',
'history-feed-item-nocomment' => '$1 go $2',
'history-feed-empty'          => 'Letlakala lewe ga le gona.
Mohlomongwe le phumutšwe go wiki, goba le fetotšwe leina.
Leka [[Special:Search|go fetleka wikii]] go humana matlakala a mapsha.',

# Revision deletion
'rev-delundel'        => 'Bontšha/Fihla',
'revdelete-hide-text' => 'Fihla dihlaka tša poeletšo',
'revdelete-log'       => 'Lebaka:',
'revdel-restore'      => '
fetola tshenolo',
'pagehist'            => 'Histori ya letlakala',

# History merging
'mergehistory-from'   => 'Letlakala la mothopo:',
'mergehistory-into'   => 'Letlakala la boyo:',
'mergehistory-reason' => 'Lebaka:',

# Merge log
'revertmerge' => 'Tloša kopaganyo',

# Diffs
'history-title'           => 'Histori ya diphetogo tša "$1"',
'difference'              => '(Phapang magareng ga dipoeletšo)',
'lineno'                  => 'Mothalo $1:',
'compareselectedversions' => 'Bapetša diphapang tšeo di kgethilwego',
'editundo'                => 'dirolla',
'diff-multi'              => '({{PLURAL:$1|Phetogo ye kgolo|Diphetogo tše $1 tše kgolo}} gadi laetšwe.)',

# Search results
'searchresults'             => 'Sephetho sa go fetleka',
'searchresults-title'       => 'Diphetho tša go fetleka "$1"',
'searchresulttext'          => 'Go hwetša sedi ka go fetleka {{SITENAME}}, lebelela [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => 'O fetleka o nyaka \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|matlaka ka moka ago thoma ka "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|matlakala ka moka a go hlamaganya go  "$1"]])',
'searchsubtitleinvalid'     => "O fetlekile o nyaka  '''$1'''",
'notitlematches'            => 'Gago letlakala la thaetlele yago swana',
'notextmatches'             => 'Gago mangwalo letlakaleng a go swana',
'prevn'                     => 'gofeta {{PLURAL:$1|$1}}',
'nextn'                     => 'latela {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Lebelela ($1 {{int:pipe-separator}} $2) ($3)',
'searchhelp-url'            => 'Help:Mateng',
'search-result-size'        => '$1 ya ({{PLURAL:$2|lentswi|$2 mantswi}})',
'search-redirect'           => '(redirect $1)',
'search-section'            => '(sekga $1)',
'search-suggest'            => 'O be o nyaka gore: $1',
'search-interwiki-caption'  => 'Diprojeke tša moloko',
'search-interwiki-default'  => '$1 diphetho:',
'search-interwiki-more'     => '(gape)',
'search-mwsuggest-enabled'  => 'le dikakanyo',
'search-mwsuggest-disabled' => 'ga go dikakanyo',
'searchall'                 => 'tšohle',
'nonefound'                 => "'''Hloko''': Ke di \"namespace\" tše dingwe tšeo di  fetlekwago go \"default.\"

Leka go fetleka ka go ngwala ''all:'' go fetleka mateng ka moka, goba o šumiše \"namespace\" yeo o nyakago go e fetleka.",
'powersearch'               => 'Fetleka ka tlhoko',
'powersearch-legend'        => 'Fetleka ya tšwetše',
'powersearch-ns'            => 'Fetleka go di "namespace"',
'powersearch-redir'         => 'Lenano la di "redirect"',
'powersearch-field'         => 'Fetleka',

# Preferences page
'preferences'               => 'Dikgatlhegelo',
'mypreferences'             => 'Dikgatlhegelo tša ka',
'prefs-edits'               => 'Palo ya diphetogo:',
'prefsnologin'              => 'Ga wa tsena',
'changepassword'            => 'Fetola ditlhaka-tša-siphiri',
'skin-preview'              => 'Ponopele',
'prefs-datetime'            => 'Tšatšikgwedi le nako',
'prefs-rc'                  => 'Diphetogo tša bjale',
'prefs-watchlist'           => 'Lenano la tlhapetšo',
'saveprefs'                 => 'Boloka',
'prefs-editing'             => 'Fetola',
'searchresultshead'         => 'Fetleka',
'savedprefs'                => 'Dikgatlhegelo tša gago di bolokilwe.',
'allowemail'                => 'Dumella melaetša ya e-mail go tšwa go bašomiši ba bangwe',
'prefs-files'               => 'Difaele',
'username'                  => 'Mošomiši:',
'uid'                       => 'Nomoro ya mošomiši:',
'prefs-memberingroups'      => 'Leloko la {{PLURAL:$1|ya sehlopha|ya dihlopha}}:',
'yourrealname'              => 'Leina la mmakgonthe:',
'yourlanguage'              => 'Polelo:',
'yournick'                  => 'Tshaeno:',
'badsiglength'              => 'Leina la boreelo le letelele kudu.
Le swanela goba fase ga $1 {{PLURAL:$1|ya tlhaka|tša ditlhaka}}',
'prefs-help-realname'       => 'Leina la nnete gale gapeletšwe, efela ge o kgetha go fana ka lona, le tla šomišwa go bontšha diabe mešomong ya gago.',
'prefs-help-email-required' => 'E-mail atrese eya nyakega.',

# User rights
'editusergroup'            => 'Fetola sehlopha sa bašomiši',
'editinguser'              => "Fetola mošomiši '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup' => 'Fetola sehlopha sa bašomiši',
'saveusergroups'           => 'Boloko Dihlopha tša bašomiši',
'userrights-groupsmember'  => 'Leloko la:',
'userrights-reason'        => 'Lebaka:',
'userrights-no-interwiki'  => 'Gawa dumelwa go fetola di dumello tša mošumiši go di wiki tše dingwe.',

# Groups
'group'       => 'Sehlopha:',
'group-sysop' => 'Bahlapetši',
'group-all'   => '(ka moka)',

'grouppage-sysop' => '{{ns:project}}:Balaudi',

# Rights
'right-read' => 'Bala matlakala',
'right-edit' => 'Fetola matlakala',
'right-move' => 'Huduša matlakala',

# User rights log
'rightslog' => "''log'' ya ditumello tša mošomiši",

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'Fetola letlakala  le',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|phetogo|diphetogo}}',
'recentchanges'                  => 'Diphetogo tša bjale',
'recentchanges-legend'           => 'Dikgetho tša diphetogo tša bjale',
'recentchanges-feed-description' => "Lebalana diphetogo tše di mphsa-mphsa go wiki  ka ''feed'' ye.",
'rcnote'                         => "Go latela {{PLURAL:$1|phetogo ye '''1'''|diphetogo tša bofelo tše '''$1'''}} ka {{PLURAL:$2|letšatši|matšatši a '''$2'''}} a go feta, go tloga $4, $5.",
'rcnotefrom'                     => "Tlase ke diphetogo go tloga ka '''$2''' (go  fihla ka '''$1''').",
'rclistfrom'                     => 'Laêtša dipheto tše mfsa go thoma go $1',
'rcshowhideminor'                => '$1 ya diphetogo tše nnyenyane',
'rcshowhidebots'                 => '$1 bots',
'rcshowhideliu'                  => '$1 bašumiši bao batsenego',
'rcshowhideanons'                => '$1 bašumiši bago se tsebege',
'rcshowhidepatr'                 => "$1 diphetogo tše ''patrolled''",
'rcshowhidemine'                 => '$1 diphetogo tsa ka',
'rclinks'                        => 'Bontšha diphetogo tša bofelo tše $1 matšatšing a  $2  a bofelo <br />$3',
'diff'                           => 'phapang',
'hist'                           => 'histori',
'hide'                           => 'Fihla',
'show'                           => 'Bontšha',
'minoreditletter'                => 'm',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',
'newsectionsummary'              => '/* $1 */ sekgao se sempsha',
'rc-enhanced-expand'             => 'Laetša ka bophara (e nyaka "JavaScript")',
'rc-enhanced-hide'               => 'Fihla bophara bja sedi',

# Recent changes linked
'recentchangeslinked'          => 'Diphetogo tša go tswalana',
'recentchangeslinked-feed'     => 'Diphetogo tša go tswalana',
'recentchangeslinked-toolbox'  => 'Diphetogo tša go tswalana',
'recentchangeslinked-title'    => 'Diphetogo tša go tswalana le "$1"',
'recentchangeslinked-noresult' => 'Gago na diphetogo go matlakala a hlomaganya ka sebaka/sekga seo o se kgethilego.',
'recentchangeslinked-summary'  => "Letlakala le le laetša diphetogo tša bjale matlakaleng a go hlomaganya.
Matlakala ago ba [[Special:Watchlist|lenanong la gago la matlakala ditlhapetšo]]  a '''ngwadilwe ka bogolo'''.",
'recentchangeslinked-page'     => 'Leina la letlakala:',
'recentchangeslinked-to'       => 'Laetša diphetogo go matlakala ago hlomanya le letlakala leo',

# Upload
'upload'            => 'Lokela Faele',
'uploadbtn'         => 'Lokela faele',
'uploadlogpage'     => "''log'' yago lokela",
'filedesc'          => 'Kakaretšo',
'fileuploadsummary' => 'Kakaretšo:',
'filesource'        => 'Mothopo:',
'savefile'          => 'Boloka faele',
'uploadedimage'     => '"[[$1]]" e loketšwe',
'watchthisupload'   => "Tlhapetša ''faele'' ye",

'license-nopreview'  => '(Ponopele ga e gona)',
'upload_source_file' => '(faele go khomphuthara ya gago)',

# Special:ListFiles
'imgfile'        => 'faele',
'listfiles'      => 'Lenano la difaele',
'listfiles_date' => 'Letšatšikgwedi',
'listfiles_name' => 'Leina',
'listfiles_user' => 'Mošumiši',
'listfiles_size' => 'Bogolo',

# File description page
'file-anchor-link'          => 'Faele',
'filehist'                  => 'Histori ya faele',
'filehist-help'             => 'Pinyeletša go letšatšikgwedi/nako go bona faela ka tsela yeo ebego e le ka gona nakong yeo.',
'filehist-deleteall'        => 'phumula ka moka',
'filehist-deleteone'        => 'phumula ye',
'filehist-current'          => 'bjale',
'filehist-datetime'         => 'LetšatšiKgwedi/Nako',
'filehist-thumb'            => 'Nkgogorupo-Nala',
'filehist-thumbtext'        => 'Nkgogorupo-Nala ya "version" go tloga $1',
'filehist-user'             => 'Mošomiši',
'filehist-dimensions'       => 'Bogolo',
'filehist-filesize'         => 'Bogolo ba faele',
'filehist-comment'          => 'Comment',
'imagelinks'                => 'Dihlamaganyago tša matlakala',
'linkstoimage'              => '{{PLURAL:$1|Letlakala le ke |$1 ya matlakala a}} latelago a hlomaganya go faele ye:',
'nolinkstoimage'            => 'Gago matlakala a hlomaganyago faeleng ye.',
'sharedupload'              => 'Faele ye e tšwa $1 e bile a kaba  e šumišwa ke ditirotherwa tše dingwe.',
'uploadnewversion-linktext' => 'Lokela peoletšo ye mphsa ya faele',

# File reversion
'filerevert-comment'        => 'Ahlaahla:',
'filerevert-defaultcomment' => 'Boela go poeletšo ya go tloga go $2, $1',

# File deletion
'filedelete'                  => 'Phumula $1',
'filedelete-legend'           => 'Phumula faele',
'filedelete-intro'            => "O phumula '''[[Media:$1|$1]]'''.",
'filedelete-comment'          => 'Lebaka:',
'filedelete-submit'           => 'Phumula',
'filedelete-success'          => "'''$1''' e phumutšwe.",
'filedelete-nofile'           => "'''$1''' ga e gona.",
'filedelete-otherreason'      => 'Mabaka a mangwe:',
'filedelete-reason-otherlist' => 'Lebaka le lengwe',

# MIME search
'mimesearch' => 'fetleka MIME',

# List redirects
'listredirects' => "Lenano la di-''redirect''",

# Unused templates
'unusedtemplates'    => "''templates'' tša go se šomišwe",
'unusedtemplateswlh' => 'dihlomaganyo tše dingwe',

# Random page
'randompage' => 'Letlakala le lengwe le le lengwe',

# Random redirect
'randomredirect' => "''redirect'' engwe le engwe",

# Statistics
'statistics'              => 'Dipalopalo',
'statistics-header-users' => 'Dipalopalo tša mošomiši',

'disambiguations' => "Matlakala a ''Disambiguation''",

'doubleredirects' => "Di''redirect'' goya go ''redirect''",

'brokenredirects'        => "''redirect'' tša go robega",
'brokenredirects-edit'   => 'fetola',
'brokenredirects-delete' => 'phumula',

'withoutinterwiki'        => 'Matlakala a senago dihlomaganyo tša dipolelo',
'withoutinterwiki-submit' => 'Bontšha',

'fewestrevisions' => 'Matlakala a goba le diphetogo tše nnyenyane',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'nlinks'                  => '$1 {{PLURAL:$1|hlomaganyo|dihlomaganyo}}',
'nmembers'                => '$1 {{PLURAL:$1|leloko|maloko}}',
'nrevisions'              => '$1 {{PLURAL:$1|poeletšo|dipoeletšo}}',
'lonelypages'             => 'Matlakala a ditšhuana',
'uncategorizedpages'      => 'Matlakala ago sebe le magoro',
'uncategorizedcategories' => 'Dihlopha tšago sebe le magoro',
'uncategorizedimages'     => 'Difaele tšago hloka magoro',
'uncategorizedtemplates'  => "''Templates'' tšago sebe le magoro",
'unusedcategories'        => 'Dihlopha tša go se šomišwe',
'unusedimages'            => 'Difaele tša go se šomišwe',
'popularpages'            => 'Matlakala a go tuma',
'wantedcategories'        => 'Dihlopha tšago nyakega',
'wantedpages'             => 'Matlakala ago nyakega',
'mostlinked'              => 'Matlakala a go hlomaganya go feta a mangwe',
'mostlinkedcategories'    => 'Dihlopha tša go hlomaganya go feta tše dingwe',
'mostlinkedtemplates'     => "''templates'' tša go hlomaganya go feta tše dingwe",
'mostcategories'          => 'Matlakala a goba le dihlopha tše dintšhi',
'mostimages'              => 'Faele tša go hlomaganya go feta tše dingwe',
'mostrevisions'           => 'Matlakala a goba le diphetogo tše dintšhi',
'prefixindex'             => "Matlakala ka moka a goba le hlogo (''prefix'')",
'shortpages'              => 'Matlakala a makopana',
'longpages'               => 'Matlakala a matelele',
'deadendpages'            => "Matlakala a seye felo(''Dead-end'')",
'protectedpages'          => 'Matlakala a go lotiwa',
'listusers'               => 'Lanano la mošomiši',
'newpages'                => 'Matlakala a mampsha',
'ancientpages'            => 'Matlakala a kgalekgale',
'move'                    => 'Huduša',
'movethispage'            => 'Huduša letlakala le',
'pager-newer-n'           => '{{PLURAL:$1|1 ye mpšha|$1 tše mpšha}}',
'pager-older-n'           => '{{PLURAL:$1|1 ya kgale|$1 tša kgale}}',

# Book sources
'booksources'               => 'Dipuku tša mothopo',
'booksources-search-legend' => 'Fetleka mothopo wa dipuku',
'booksources-go'            => 'Sepela',

# Special:Log
'specialloguserlabel'  => 'Mošomiši:',
'speciallogtitlelabel' => 'Thaetlele:',
'log'                  => "Di-''log''",
'all-logs-page'        => "Di-''log'' kamoka",

# Special:AllPages
'allpages'       => 'Matlakala ka moka',
'alphaindexline' => '$1 goya go $2',
'nextpage'       => 'Letlakala lago latela ($1)',
'prevpage'       => 'Letlakala la go feta ($1)',
'allpagesfrom'   => 'Bontšha matlakala go thoma go :',
'allpagesto'     => 'Bontšha matlakala go felela go :',
'allarticles'    => 'Matlakala ka moka',
'allpagessubmit' => 'Eya',
'allpagesprefix' => "Laetša matlakala agoba le hlogo (''prefix''):",

# Special:Categories
'categories' => 'Dihlopha',

# Special:DeletedContributions
'deletedcontributions'       => 'Diabe tša mošomiši tšeo di phumutšwego',
'deletedcontributions-title' => 'Diabe tša mošomiši tšeo di phumutšwego',

# Special:LinkSearch
'linksearch'    => 'Dihlomaganyo tša ntle',
'linksearch-ok' => 'Fetleka',

# Special:ListUsers
'listusers-submit' => 'Bontšha',

# Special:Log/newusers
'newuserlogpage'          => '"Log" yago hlola mošumiši',
'newuserlog-create-entry' => 'Tšhupaleloko ya mošumiši ye mphsa',

# Special:ListGroupRights
'listgrouprights-members' => '(Lenano la ditho)',

# E-mail user
'emailuser'      => 'Romela mošomiši yo molaetša',
'emailpage'      => 'Romela email go mošomiši',
'noemailtitle'   => 'Gago email atrese',
'emailfrom'      => 'Go tšwa go',
'emailsubject'   => 'Sebolelwa',
'emailmessage'   => 'Molaetša',
'emailsend'      => 'Romela',
'emailccme'      => 'Nromela kopi ya melaetša.',
'emailccsubject' => 'Kopi ya molaetša wa gago goya go $1: $2',
'emailsent'      => 'E-mail e rometšwe',
'emailsenttext'  => 'Molaetša wa gago wa email gawa romelwa.',

# Watchlist
'watchlist'         => 'Lenano la ditlhapetšo tša ka',
'mywatchlist'       => 'Lenano la ditlhapetšo tša ka',
'addedwatch'        => 'Loketšwe go lenano la ditlhapetšo',
'addedwatchtext'    => "Letlakala \"[[:\$1]]\" le tsene go [[Special:Watchlist|watchlist]] ya gago.
Go tloga bjale, diphetogo letlakaleng le, le letlakaleng la dipoledišano la gona, di tla bontšhwa ka mongalo wa '''bold''' gare ga [[Special:RecentChanges|list of recent changes]] gore go be bonolo gore oa bone.

Ga eba o nyaka go hloša letlaka le go lenano la ditlhapetšo tša gago, šomiša \"Tloša tlhapetšo\" go sidebar.",
'removedwatch'      => 'Tlošitšwe go lenano la ditlhapetšo',
'removedwatchtext'  => 'Letlakala "[[:$1]]" letlošitšwe go [[Special:Watchlist|lenano la gago la ditlhapetšo]].',
'watch'             => 'Tlhapetša',
'watchthispage'     => 'Tlhapetša letlakala le',
'unwatch'           => 'Tloša tlhapešo',
'watchlist-details' => '{{PLURAL:$1|$1 ya letlakala|$1 ya matlakala}} a lenano la ditlhapetšo tša gago, re sa bale matlakala a dipoledišano (dipolelo).',
'wlshowlast'        => 'Laetša  $1 diiri $2 matšatši $3 tša gofeta',
'watchlist-options' => 'Dikgatlego tša lenano la ditlhapetšo',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Tlhapeditše...',
'unwatching' => 'Tlhapetšo eya tlošwa ...',

'enotif_reset'                 => 'Swaya matlakala kamoka awe oa etetšego',
'enotif_newpagetext'           => 'Le, ke letlakala le lempsha.',
'enotif_impersonal_salutation' => '{{SITENAME}} mošumiši',
'changed'                      => 'fetotšwe',
'created'                      => 'tlhodilwe',
'enotif_subject'               => '{{SITENAME}} letkalala $PAGETITLE le $CHANGEDORCREATED ke $PAGEEDITOR',
'enotif_lastvisited'           => 'Lebelela  $1 go bona diphetogo ka moka gotloga ge go tsena la mafelelo.',
'enotif_lastdiff'              => 'Bona $1 go nyakorela phetogo ye.',

# Delete
'deletepage'            => 'Phumula letlakala',
'excontent'             => "mateng ebe e le: '$1'",
'delete-legend'         => 'Phumula',
'historywarning'        => 'Temošo: Letlakala leo o lekago go lephumula le nale histori:',
'confirmdeletetext'     => 'O phumula letlakala le histori ka moka ya lona.
Ka kgopela sitlediša gore ke se o nyakago  go sedira, le gore o kwešiša ditla morago tša se, le gore seo o se dirago se latela melawana le ditaelo go ya ka [[{{MediaWiki:Policy-url}}|polisi]].',
'actioncomplete'        => 'Kgopelo e phetilwe ka katlego',
'deletedtext'           => '"<nowiki>$1</nowiki>" e phumutšwe.
Lebelela $2 go hweetša sedi ka diphulo tša bjale.',
'deletedarticle'        => 'E phumutšwe "[[$1]]"',
'dellogpage'            => "''Log'' yago phumula",
'deletecomment'         => 'Lebaka:',
'deleteotherreason'     => 'Mabaka a mangwe:',
'deletereasonotherlist' => 'Mabaka a mangwe',

# Rollback
'rollbacklink' => 'bošetša morago',
'editcomment'  => "Ahlaahlo ya phetogo ke : \"''\$1''\".",

# Protect
'protectlogpage'              => "''Log'' yago lota",
'protectedarticle'            => 'lotilwe "[[$1]]"',
'modifiedarticleprotection'   => 'fetotše  mokgwa wa go lota "[[$1]]"',
'prot_1movedto2'              => '[[$1]] e hudugile goya go [[$2]]',
'protect-legend'              => 'Tiišetša go lota',
'protectcomment'              => 'Lebaka:',
'protectexpiry'               => 'Fetatšatši:',
'protect_expiry_invalid'      => 'Fetatšatši, nako ye ga ya dumelwa.',
'protect_expiry_old'          => 'Fetatšatši ke ya nako yeo e fetilego.',
'protect-text'                => "O ka lebelela lego fetola seemo sa go lota sa letlakala '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Tšhupaleloko ya gago ga ena tumello ya go fetola seemo sago lota ga letlakala.
Seemo sa go lota ga letlakala '''$1''':",
'protect-cascadeon'           => 'Letlakala le lotegile ka ge le akeretšwa ke {{PLURAL:$1|letlakala, leo lenalego|matlakala, analego}} golotega ga kakaretšo. O ka fetola go lotega ga letlakala le.',
'protect-default'             => 'Dumella bašumiši ka moka',
'protect-fallback'            => 'Go nyakega tumello ya "$1"',
'protect-level-autoconfirmed' => "Thiba bašumiši  ba bafša le bao ba sakago ba engwadiša(''unregistered'')",
'protect-level-sysop'         => 'Sysops feela',
'protect-summary-cascade'     => 'cascading',
'protect-expiring'            => 'fetatšatši ke $1 (UTC)',
'protect-cascade'             => 'Lota matlakala, akaretša le letlakala le (go lota ka kakaretšo)',
'protect-cantedit'            => 'Ga o kgone go fetola tekano ya bolotego letlakaleng le, ka ge o sena tumello yago bofetola.',
'protect-expiry-options'      => '2 diiri:2 hours,1 letšatši:1 day,3 matšatši:3 days,1 beke:1 week,2 dibeke:2 weeks,1 kgwedi:1 month,3 digkwedi:3 months,6 dikgwedi:6 months,1 ngwaga:1 year,ga efele:infinite',
'restriction-type'            => 'Tumello:',
'restriction-level'           => 'Seemo sago Lota:',

# Restrictions (nouns)
'restriction-edit'   => 'Fetola',
'restriction-move'   => 'Huduša',
'restriction-create' => 'Tlhola',

# Undelete
'undelete'               => 'Nyakorela matlakala ago phumulwa',
'viewdeletedpage'        => 'Nyakorela matlakala ago phumulwa',
'undeletebtn'            => 'Hlaphola',
'undeletelink'           => 'Nyakorela/hlaphola',
'undeletecomment'        => 'Ahlaahla:',
'undeletedarticle'       => 'hlaphola "[[$1]]"',
'undelete-search-prefix' => 'Laetśa matlakala a go thoma ka:',
'undelete-search-submit' => 'Fetleka',

# Namespace form on various pages
'namespace'      => 'Namespace:',
'invert'         => 'Fetola kgetho',
'blanknamespace' => '(Hlogo)',

# Contributions
'contributions'       => 'Diabe tša mošomiši',
'contributions-title' => 'Diabe tša mošumiši go $1',
'mycontris'           => 'Diabe tša ka',
'contribsub2'         => 'Ya $1 ($2)',
'uctop'               => '(godimo)',
'month'               => 'Go tloga kgweding (le peleng):',
'year'                => 'Go tloga ngwageng (le peleng):',

'sp-contributions-newbies'     => 'Laetša diabe tša bašumiši ba bafsa fela',
'sp-contributions-newbies-sub' => 'Tša tšhupaleloko tše mphsa',
'sp-contributions-blocklog'    => "''Log'' yago thiba",
'sp-contributions-deleted'     => 'Diabe tša mošomiši tšeo di phumutšwego',
'sp-contributions-talk'        => 'Bolela',
'sp-contributions-search'      => 'Fetleka diabe',
'sp-contributions-username'    => 'IP Atrese goba leina la mošomiši:',
'sp-contributions-submit'      => 'Fetleka',

# What links here
'whatlinkshere'            => 'Ke eng yeo e hlomaganyago mo',
'whatlinkshere-title'      => 'Matlakala a go hlomaganya go "$1"',
'whatlinkshere-page'       => 'Letlakala:',
'linkshere'                => "Matlaka a latelago a hlomaganya le '''[[:$1]]''':",
'nolinkshere'              => "Ga go letlakala leo le hlomaganyago go '''[[:$1]]'''.",
'isredirect'               => "''redirect'' letlakala",
'istemplate'               => 'tsentšho',
'isimage'                  => 'hlomaganyo ya seswantšho',
'whatlinkshere-prev'       => '{{PLURAL:$1|fetile|fetile $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|latela|latela $1}}',
'whatlinkshere-links'      => '← dihlomaganyago',
'whatlinkshere-hideredirs' => '$1 di "redirect"',
'whatlinkshere-hidetrans'  => '$1 transclusions',
'whatlinkshere-hidelinks'  => '$1 dihlomaganyago',
'whatlinkshere-filters'    => 'Dihlotla',

# Block/unblock
'blockip'                  => 'Thibela mošomiši go tsena',
'ipaddress'                => 'IP Atrese:',
'ipbexpiry'                => 'Fetatšatši:',
'ipbreason'                => 'Lebaka:',
'ipbreasonotherlist'       => 'Lebaka le lengwe',
'ipbsubmit'                => 'Thiba mošomiši yo',
'ipbother'                 => 'Nako yengwe:',
'ipboptions'               => '2 diiri:2 hours,1 letšatši:1 day,3 matšatši:3 days,1 beke:1 week,2 dibeke:2 weeks,1 kgwedi:1 month,3 digkwedi:3 months,6 dikgwedi:6 months,1 ngwaga:1 year,ga efele:infinite',
'ipbotheroption'           => 'yengwe',
'ipblocklist'              => "Lenano la IP le bašumiši bao bathibilwego(''blocked'')",
'ipblocklist-submit'       => 'Fetleka',
'blocklistline'            => '$1, $2 o thibile $3 ($4)',
'emailblock'               => 'e-mail e thibilwe',
'blocklink'                => 'thibela',
'unblocklink'              => 'tloša thibelo',
'change-blocklink'         => 'fetola go thiba',
'contribslink'             => 'diabelo',
'blocklogpage'             => "''log'' yago Thiba",
'blocklogentry'            => 'Thibela [[$1]] ka fetšatši ya $2 $3',
'unblocklogentry'          => 'Gago thibelo $1',
'block-log-flags-nocreate' => 'Go hloma tšhupaleloko gago dumelege',
'block-log-flags-noemail'  => 'e-mail e thibilwe',
'proxyblocksuccess'        => 'Phetilwe.',

# Move page
'move-page-legend'        => 'Huduša letlakala',
'movepagetext'            => "Ge o šomiša fomo ye mo tlase, letlakala le kgale le history ya lona di tla huduga go ya letlakaleng le lemphsa.
Letlakala la kgale le ba ''redirect'' go ya letlakaleng le lemphsa.
Dihlomaganyo goya letlakaleng la kgale ga di fetolwe; gopola go sekaseka di [[Special:DoubleRedirects|''double'']] goba [[Special:BrokenRedirects|''broken redirects'']].

Ke boikarabela ba gago go kgonthišisa gore dihlomaganyo di tšhwela pele go šupa mowe di swanetšego goya.

Ela hloko gore letlakala le '''ka se''' hudušwe gaeba go ena letlakala la leina le lemphsa, ntle ga le sa selo goba ele ''redirect'' ebile le sa na histori.
Se sera gore o ka huduša letlakala morago ge o direle phošo gape o ka se ngwale godimo (''overwrite'') ga letlakala leo le lego gona.

'''TEMOŠO!'''
Se sekapa le dipheto tšeo di sa letelwago go matlakala atumilego;
Ka kgopelo kgontišiša gore o kwešiša ditla morago tša se, pele o tšwelapele.",
'movepagetalktext'        => "Letlakala la dipoledišano lago hlobana le letlakala le le tla hudušwa '''ntle le ge''':

*Ge letlakala la dipoledišano la leina le lemphsa lephela, goba
*O sa kgetha go le huduša tlase ga letlakala le.

Ge go le bjalo,o tla swanela ke go huduša goba go kopanya matlakala ka bowena.",
'movearticle'             => 'Huduša letlakala:',
'newtitle'                => 'Goya go taetlile:',
'move-watch'              => 'Tlhapetša letlakala le',
'movepagebtn'             => 'Huduša letlakala',
'pagemovedsub'            => 'Hudugile ka katlego',
'movepage-moved'          => '\'\'\'"$1" e hudušitšwe go "$2"\'\'\'',
'articleexists'           => 'Letlakala la goba le leina le legona, goba
leina leo o le kgethilego ga la dumelega.
Ka kgopelo, kgetha leina le lengwe.',
'talkexists'              => "'''Letlakala le hudugile ka katlego, efele letlakala la dipolešano ga la huduga kage gobe go ina letlakala leineng le lemphsa. Ka kgopelo, a gahlanye ka bowena.'''",
'movedto'                 => 'hudugetše go',
'movetalk'                => 'Huduša letlakala la dipoledišano la go hlobana le letlakala le',
'1movedto2'               => '[[$1]] e hudugile goya go [[$2]]',
'1movedto2_redir'         => 'hudušitše [[$1]] go [[$2]] godimo ga "redirect"',
'movelogpage'             => "''log'' yago huduša",
'movereason'              => 'Lebaka:',
'revertmove'              => 'bušetša',
'delete_and_move_confirm' => 'E, phumula letlakala le',

# Export
'export' => 'Matlakala a diyantle',

# Namespace 8 related
'allmessages'     => 'Melaetša ya tlhamego',
'allmessagesname' => 'Leina',

# Thumbnails
'thumbnail-more'  => 'Godiša/Atologa',
'thumbnail_error' => "Phoso go tlhama ''thumbnail'': $1",

# Special:Import
'import-comment'        => 'Ahlaahla:',
'import-revision-count' => '$1 {{PLURAL:$1|poeletšo|dipoeletšo}}',

# Import log
'importlogpage'                    => "''Log'' yago lokela",
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|poeletšo|dipoeletšo}}',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|poeletšo|dipoeletšo}} gotšwa go $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Letlakala (la mošomiši) lago',
'tooltip-pt-mytalk'               => 'Letlakala la gago la dipolelo',
'tooltip-pt-preferences'          => "Dikgetho (''preference'') tša ka",
'tooltip-pt-watchlist'            => 'Lenano la matlakala ago tlhapetšwa diphetogo ke wena',
'tooltip-pt-mycontris'            => 'Lenano la diabe tša gago',
'tooltip-pt-login'                => 'O a kgothatšwa gore o tsene, e efela ga se kgapeletšo.',
'tooltip-pt-logout'               => 'Etšwa fa/Tswalela/Log out',
'tooltip-ca-talk'                 => 'Poledišano mabapi le letlakala le',
'tooltip-ca-edit'                 => 'O ka fetola letlakala le. Kgopelo ke gore o šumiše konopo ya go Laetša sebopego sa letlakala pele o le boloka.',
'tooltip-ca-addsection'           => 'Lokela sekgao se seswa',
'tooltip-ca-viewsource'           => 'Letlakala le le lotilew. O ka lebelela mothopo fela.',
'tooltip-ca-history'              => 'Lebelela thumeletšo ya go feta ya letlakala le',
'tooltip-ca-protect'              => 'Lota letlakala le',
'tooltip-ca-delete'               => 'Phumula letlakala le',
'tooltip-ca-move'                 => 'Huduša letlakala le',
'tooltip-ca-watch'                => 'Lokela letlakala le go lenano la gago la tlhapetšo',
'tooltip-ca-unwatch'              => 'Tloša letlakala le go lenano la gago la matlakala a go tlhapetšwa',
'tooltip-search'                  => 'Fetleka  {{SITENAME}}',
'tooltip-search-go'               => 'Eya go letlakala la leina le, gaeba le le gona',
'tooltip-search-fulltext'         => 'Fetleka matlakala go hwetša mongwalo wo',
'tooltip-n-mainpage'              => 'Etela letlakala la pele',
'tooltip-n-mainpage-description'  => 'Etela letlakala-tona',
'tooltip-n-portal'                => 'Mabapi le tirotherwa, seo o ka se dirago, o ka humana dilo kae',
'tooltip-n-currentevents'         => 'Humana sedi yengwe go ditiragalo tša bjale',
'tooltip-n-recentchanges'         => 'Lenano la diphetogo tša bjale go wiki.',
'tooltip-n-randompage'            => 'Laiša letlakala le lengwe le le lengwe',
'tooltip-n-help'                  => 'O tla humana thušo mo.',
'tooltip-t-whatlinkshere'         => "Lenano la matlakala ao a hlomaganyago (''link'') mo",
'tooltip-t-recentchangeslinked'   => 'Diphetogo tša bjale go matlakala a go hlomaganya le letlakala le',
'tooltip-feed-rss'                => 'RSS feed tša letlakala le',
'tooltip-feed-atom'               => 'Atom "feed" tša letlakala le',
'tooltip-t-contributions'         => 'Lebelela lenano la diabe tša mošomiši yo',
'tooltip-t-emailuser'             => 'Romela molaetša go mošomiši yo',
'tooltip-t-upload'                => 'Lokela senepe goba difaele',
'tooltip-t-specialpages'          => 'Lenano la matlakala kamoka a itšeng',
'tooltip-t-print'                 => 'Seemo sa letlakala le seo se ka gatišwago',
'tooltip-t-permalink'             => 'Hlomaganyo go poeletšo ye ya letlakala',
'tooltip-ca-nstab-main'           => 'Nyakoretša boteng bja letlakala',
'tooltip-ca-nstab-user'           => 'Lebelela letlakala la mošomiši',
'tooltip-ca-nstab-special'        => 'Letlakala le le "special", gago kgonege go le fetola',
'tooltip-ca-nstab-project'        => 'Lebelela letlakala la tirotherwa',
'tooltip-ca-nstab-image'          => 'Lebelela  letlakala',
'tooltip-ca-nstab-template'       => "Lebelela ''template''",
'tooltip-ca-nstab-help'           => 'Lebelea matlakala a thušo',
'tooltip-ca-nstab-category'       => 'Lebelela letlakala la sehlopha',
'tooltip-minoredit'               => 'Swaya se bjalo ka phetogo ye nnyenyane',
'tooltip-save'                    => 'Boloka diphetogo tša gago',
'tooltip-preview'                 => 'E laetša gore diphetogo di tla lebega bjang, e šomiše pele ga ge o boloka letlakala!',
'tooltip-diff'                    => 'Laetša diphetogo tšeo o di dirilego go mongwalo.',
'tooltip-compareselectedversions' => 'Bontšha phapano magareng ga di dihlopha tše pedi tša diphetogo tšeo o di kgetilego letlakaleng le.',
'tooltip-watch'                   => 'Lokela letlakala le go lenano la gago la  matlakala ago tlhapetšwa',
'tooltip-rollback'                => 'Bošetša diphetogo letlakaleng morago go mošumiši wa mafelo',
'tooltip-undo'                    => '"Undo" e tloša dipheto tše, ya bula letlakala go nyakorela fela. E go dumela go ngawla lebaka go dikakaretšo.',

# Attribution
'siteuser'         => '{{SITENAME}} mošumiši $1',
'lastmodifiedatby' => 'Letlakala le  fetotšwe la mafelelo ka $2, $1 ke $3.',
'others'           => 'tše dingwe',
'siteusers'        => '{{SITENAME}} mošumiši/bašumiši $1',

# Info page
'numedits'    => 'Palo ya diphetogo (letlakala): $1',
'numwatchers' => 'Palo ya batlhapedi: $1',

# Math errors
'math_unknown_error' => 'Phošo ya gose tsebege',
'math_syntax_error'  => 'phošo ya popafoko',

# Image deletion
'filedeleteerror-short' => 'Phošo go phumuleng faele: $1',
'filedeleteerror-long'  => 'Diphošo di hlagile ge go phumulwa faele:

$1',
'filedelete-missing'    => 'Faele "$1" ga e phumulege ka ge e segona.',

# Browsing diffs
'previousdiff' => '← Dophapano tšago feta',
'nextdiff'     => 'Diphapano tše dimpšha →',

# Media information
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|letlakala|matlakala}}',
'file-info-size'       => '$1 × $2 pixel, bogolo ba faele: $3, Mohuta wa MIME: $4',
'file-nohires'         => "<small>Gago ''resolution'' ya go feta mo.</small>",
'svg-long-desc'        => 'SVG faele, nominally $1 × $2 pixels, bogolo ba faele: $3',
'show-big-image'       => "''resolution'' ya gofella",
'show-big-image-thumb' => '<small>Bogolo ba pono: $1 × $2 pixels</small>',

# Special:NewFiles
'newimages' => "''Gallery'' ya difaele tše mpsha",
'ilsubmit'  => 'Fetleka',
'bydate'    => 'ka letšatšikgwedi',

# Bad image list
'bad_image_list' => "''Format'' e ka mokgwa wo o latelago:

Ke fela tšeo dilego lenano (methalo ya go thoma ka *) yeo e dumeletšwego.
Hlomaganyo ya mathomo mothalong e swanetše go hlomaganya le seswantšho sa go senyega.
Dihlomaganyo tše dilatelago mothalong o tee di tšewa bjalo ka maarogi, ka mantšwe a mangwe, matlakala a we seswantšsho se ka bago gona mothalong.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => "Faele enale sedi yengwe, yeo ekabago e tšwa go khamera goba ''scanner'' tšeo di šumišitšwego go bopa faele ye. Sedi ye itsego ga ego goba gona go faele ye e fetolwago.",
'metadata-expand'   => 'Bontšha sedi ya gotlala(extended)',
'metadata-collapse' => "Fihla sedi ya gotlala(''extended'')",
'metadata-fields'   => "EXIF metadata ''fields'' tšao dilego go molaetša wo, di tla tsenywa go
letlakala la seswantšho ge tafola ya metadata e bulwa. Tše dingwe tša di ''fields'' di tla fihliwa.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-imagewidth'  => 'Bophara',
'exif-imagelength' => 'Botelele',
'exif-artist'      => 'Mongwadi',

'exif-componentsconfiguration-0' => 'Ga e gona',

'exif-subjectdistance-value' => '$1 metara',

'exif-meteringmode-1' => 'Palogare',

# External editor support
'edit-externally'      => 'Fetola faele ye o šumiša thulusi ya ka ntle',
'edit-externally-help' => '(Lebelela [http://www.mediawiki.org/wiki/Manual:External_editors Taelo ya go thoma] go humana sedi)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ka moka',
'imagelistall'     => 'ka moka',
'watchlistall2'    => 'ka moka',
'namespacesall'    => 'ka moka',
'monthsall'        => 'ka moka',

# Multipage image navigation
'imgmultipageprev' => '← letlakala la go feta',
'imgmultipagenext' => 'letlakala lago latela →',

# Table pager
'table_pager_next'  => 'Letlakala la go latela',
'table_pager_prev'  => 'Letlakala la gofeta',
'table_pager_first' => 'Letlakala la pele',
'table_pager_last'  => 'Letlakala la mafelelo',
'table_pager_empty' => 'Ga gona sepheto',

# Auto-summaries
'autosumm-new' => "Tlhodile letlakala ka '$1'",

# Watchlist editor
'watchlistedit-numitems'      => 'Lenano la gago la ditlhapetšo le na le  {{PLURAL:$1|thaetlele ye tee|di thaetlele tše $1}}, re sa bale matlakala a dipolelo.',
'watchlistedit-noitems'       => 'Lenano la gago la ditlhapetšo ga lena dithaetlele.',
'watchlistedit-normal-title'  => 'Felotal lenano la ditlhapetšo',
'watchlistedit-normal-legend' => 'Tloša dithaetlele go lenano la ditlhapetšo',
'watchlistedit-normal-submit' => 'Tloša thaetlele',

# Watchlist editing tools
'watchlisttools-view' => 'Nyakoretša diphetogo tša gona',
'watchlisttools-edit' => 'Lebelela lego fetola lenano la ditlhapetšo',
'watchlisttools-raw'  => "Fetola lenano le letala (''raw'') la ditlhapetšo",

# Special:Version
'version' => "''Version''",

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Fetleka',

# Special:SpecialPages
'specialpages' => 'Matlakala a itšeng',

);

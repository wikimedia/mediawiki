<?php
/** Malayalam (മലയാളം)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Abhishek Jacob
 * @author Anoopan
 * @author Chrisportelli
 * @author Deepugn
 * @author Jacob.jose
 * @author Jigesh
 * @author Junaidpv
 * @author Jyothis
 * @author Manjith Joseph <manjithkaini@gmail.com>
 * @author Praveen Prakash <me.praveen@gmail.com>
 * @author Praveenp
 * @author Sadik Khalid
 * @author Sadik Khalid <sadik.khalid@gmail.com>
 * @author ShajiA
 * @author Shiju Alex <shijualexonline@gmail.com>
 * @author Shijualex
 * @author Vssun
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'മീഡിയ',
	NS_SPECIAL          => 'പ്രത്യേകം',
	NS_TALK             => 'സംവാദം',
	NS_USER             => 'ഉപയോക്താവ്',
	NS_USER_TALK        => 'ഉപയോക്താവിന്റെ_സംവാദം',
	NS_PROJECT_TALK     => '$1_സംവാദം',
	NS_FILE             => 'പ്രമാണം',
	NS_FILE_TALK        => 'പ്രമാണത്തിന്റെ_സംവാദം',
	NS_MEDIAWIKI        => 'മീഡിയവിക്കി',
	NS_MEDIAWIKI_TALK   => 'മീഡിയവിക്കി_സംവാദം',
	NS_TEMPLATE         => 'ഫലകം',
	NS_TEMPLATE_TALK    => 'ഫലകത്തിന്റെ_സംവാദം',
	NS_HELP             => 'സഹായം',
	NS_HELP_TALK        => 'സഹായത്തിന്റെ_സംവാദം',
	NS_CATEGORY         => 'വര്‍ഗ്ഗം',
	NS_CATEGORY_TALK    => 'വര്‍ഗ്ഗത്തിന്റെ_സംവാദം',
);

$namespaceAliases = array(
	'അംഗം' => NS_USER,
	'ഉ' => NS_USER,
	'അംഗങ്ങളുടെ സംവാദം' => NS_USER_TALK,
	'ഉസം' => NS_USER_TALK,
	'ചി' => NS_FILE,
	'ചിസം' => NS_FILE_TALK,
	'ചിത്രം' => NS_FILE,
	'ചിത്രത്തിന്റെ_സംവാദം' => NS_FILE_TALK,
	'ഫ' => NS_TEMPLATE,
	'ഫസം' => NS_TEMPLATE_TALK,
	'വി' => NS_CATEGORY,
	'വിസം' => NS_CATEGORY_TALK,
	'വിഭാഗം' => NS_CATEGORY,
	'വിഭാഗത്തിന്റെ_സംവാദം' => NS_CATEGORY_TALK,
	'സ' => NS_HELP,
	'സസം' => NS_HELP_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'ഇരട്ടത്തിരിച്ചുവിടലുകള്‍' ),
	'BrokenRedirects'           => array( 'പൊട്ടിയതിരിച്ചുവിടലുകള്‍' ),
	'Disambiguations'           => array( 'നാനാര്‍ത്ഥങ്ങള്‍' ),
	'Userlogin'                 => array( 'പ്രവേശനം' ),
	'Userlogout'                => array( 'പുറത്തുകടക്കല്‍' ),
	'CreateAccount'             => array( 'അംഗത്വമെടുക്കല്‍' ),
	'Preferences'               => array( 'ക്രമീകരണങ്ങള്‍' ),
	'Watchlist'                 => array( 'ശ്രദ്ധിക്കുന്നവ' ),
	'Recentchanges'             => array( 'സമീപകാലമാറ്റങ്ങള്‍' ),
	'Upload'                    => array( 'അപ്‌ലോഡ്' ),
	'Listfiles'                 => array( 'പ്രമാണങ്ങളുടെ പട്ടിക', 'ചിത്രങ്ങളുടെ പട്ടിക' ),
	'Newimages'                 => array( 'പുതിയ പ്രമാണങ്ങള്‍', 'പുതിയ ചിത്രങ്ങള്‍' ),
	'Listusers'                 => array( 'ഉപയോക്താക്കളുടെ പട്ടിക' ),
	'Listgrouprights'           => array( 'സമൂഹത്തിന്റെ അവകാശങ്ങളുടെ പട്ടിക' ),
	'Statistics'                => array( 'സ്ഥിതിവിവരം' ),
	'Randompage'                => array( 'ക്രമരഹിതമായി', 'താളുകള്‍ ക്രമരഹിതമായി' ),
	'Lonelypages'               => array( 'അനാഥ താളുകള്‍' ),
	'Uncategorizedpages'        => array( 'വര്‍ഗ്ഗീകരിക്കാത്ത താളുകള്‍' ),
	'Uncategorizedcategories'   => array( 'വര്‍ഗ്ഗീകരിക്കാത്ത വര്‍ഗ്ഗങ്ങള്‍' ),
	'Uncategorizedimages'       => array( 'വര്‍ഗ്ഗീകരിക്കാത്ത പ്രമാണങ്ങള്‍' ),
	'Uncategorizedtemplates'    => array( 'വര്‍ഗ്ഗീകരിക്കാത്ത ഫലകങ്ങള്‍' ),
	'Unusedcategories'          => array( 'ഉപയോഗിക്കാത്ത വര്‍ഗ്ഗങ്ങള്‍' ),
	'Unusedimages'              => array( 'ഉപയോഗിക്കാത്ത പ്രമാണങ്ങള്‍' ),
	'Wantedpages'               => array( 'ആവശ്യമുള്ള താളുകള്‍', 'പൊട്ടിയ കണ്ണികള്‍' ),
	'Wantedcategories'          => array( 'ആവശ്യമുള്ള വര്‍ഗ്ഗങ്ങള്‍' ),
	'Wantedfiles'               => array( 'ആവശ്യമുള്ള പ്രമാണങ്ങള്‍' ),
	'Wantedtemplates'           => array( 'ആവശ്യമുള്ള ഫലകങ്ങള്‍' ),
	'Mostlinked'                => array( 'കൂടുതല്‍ കണ്ണികളുള്ള താളുകള്‍', 'കൂടുതല്‍ കണ്ണികളുള്ളവ' ),
	'Mostlinkedcategories'      => array( 'കൂടുതല്‍ കണ്ണികളുള്ള വര്‍ഗ്ഗങ്ങള്‍', 'കൂടുതല്‍ ഉപയോഗിച്ചിട്ടുള്ള വര്‍ഗ്ഗങ്ങള്‍' ),
	'Mostlinkedtemplates'       => array( 'കൂടുതല്‍ കണ്ണികളുള്ള ഫലകങ്ങള്‍', 'കൂടുതല്‍ ഉപയോഗിച്ചിട്ടുള്ള ഫലകങ്ങള്‍' ),
	'Mostimages'                => array( 'കൂടുതല്‍ കണ്ണികളുള്ള പ്രമാണങ്ങള്‍', 'കൂടുതല്‍ പ്രമാണങ്ങള്‍', 'കൂടുതല്‍ ചിത്രങ്ങള്‍' ),
	'Mostcategories'            => array( 'കൂടുതല്‍ വര്‍ഗ്ഗങ്ങള്‍' ),
	'Mostrevisions'             => array( 'കൂടുതല്‍ പുനരവലോകനങ്ങള്‍' ),
	'Fewestrevisions'           => array( 'കുറഞ്ഞ പുനരവലോകനങ്ങള്‍' ),
	'Shortpages'                => array( 'ചെറിയ താളുകള്‍' ),
	'Longpages'                 => array( 'വലിയ താളുകള്‍' ),
	'Newpages'                  => array( 'പുതിയ താളുകള്‍' ),
	'Ancientpages'              => array( 'പുരാതന താളുകള്‍' ),
	'Deadendpages'              => array( 'അന്ത്യസ്ഥാനത്തുള്ള താളുകള്‍' ),
	'Protectedpages'            => array( 'സംരക്ഷിത താളുകള്‍' ),
	'Protectedtitles'           => array( 'സംരക്ഷിത ശീര്‍ഷകങ്ങള്‍' ),
	'Allpages'                  => array( 'എല്ലാതാളുകളും' ),
	'Prefixindex'               => array( 'പൂര്‍വ്വപദസൂചിക' ),
	'Ipblocklist'               => array( 'തടയല്‍‌പട്ടിക', 'ഐപികളുടെ തടയല്‍‌പട്ടിക' ),
	'Specialpages'              => array( 'പ്രത്യേകതാളുകള്‍' ),
	'Contributions'             => array( 'സംഭാവനകള്‍' ),
	'Emailuser'                 => array( 'ഉപയോക്തൃഇമെയില്‍' ),
	'Confirmemail'              => array( 'ഇമെയില്‍ സ്ഥിരീകരിക്കുക' ),
	'Whatlinkshere'             => array( 'കണ്ണികളെന്തെല്ലാം' ),
	'Recentchangeslinked'       => array( 'ബന്ധപ്പെട്ട മാറ്റങ്ങള്‍' ),
	'Movepage'                  => array( 'താള്‍ മാറ്റുക' ),
	'Blockme'                   => array( 'എന്നെതടയുക' ),
	'Booksources'               => array( 'പുസ്തകസ്രോതസ്സുകള്‍' ),
	'Categories'                => array( 'വര്‍ഗ്ഗങ്ങള്‍' ),
	'Export'                    => array( 'കയറ്റുമതി' ),
	'Version'                   => array( 'പതിപ്പ്' ),
	'Allmessages'               => array( 'സര്‍വ്വസന്ദേശങ്ങള്‍' ),
	'Log'                       => array( 'രേഖ', 'രേഖകള്‍' ),
	'Blockip'                   => array( 'തടയുക', 'ഐപിയെ തടയുക', 'ഉപയോക്താവിനെ തടയുക' ),
	'Undelete'                  => array( 'മായ്ച്ചവ പുനഃസ്ഥാപനം' ),
	'Import'                    => array( 'ഇറക്കുമതി' ),
	'Userrights'                => array( 'ഉപയോക്തൃഅവകാശങ്ങള്‍' ),
	'FileDuplicateSearch'       => array( 'പ്രമാണത്തിന്റെ അപരനുള്ള തിരച്ചില്‍' ),
	'Unwatchedpages'            => array( 'അനാവശ്യതാളുകള്‍' ),
	'Listredirects'             => array( 'തിരിച്ചുവിടല്‍‌പട്ടിക' ),
	'Unusedtemplates'           => array( 'ഉപയോഗിക്കാത്തഫലകങ്ങള്‍' ),
	'Randomredirect'            => array( 'ക്രമരഹിതതിരിച്ചുവിടലുകള്‍' ),
	'Mypage'                    => array( 'എന്റെ താള്‍' ),
	'Mytalk'                    => array( 'എന്റെ സംവാദം' ),
	'Mycontributions'           => array( 'എന്റെ സംഭാവനകള്‍' ),
	'Listadmins'                => array( 'കാര്യനിര്‍വാഹകപട്ടിക' ),
	'Listbots'                  => array( 'യന്ത്രങ്ങളുടെ പട്ടിക' ),
	'Popularpages'              => array( 'ജനകീയതാളുകള്‍' ),
	'Search'                    => array( 'അന്വേഷണം' ),
	'Resetpass'                 => array( 'രഹസ്യവാക്ക് മാറ്റുക' ),
	'Withoutinterwiki'          => array( 'അന്തര്‍വിക്കിയില്ലാത്തവ' ),
	'MergeHistory'              => array( 'നാള്‍വഴിലയിപ്പിക്കുക' ),
	'Filepath'                  => array( 'പ്രമാണവിലാസം' ),
	'Invalidateemail'           => array( 'സാധുവല്ലാത്ത ഇമെയില്‍' ),
	'Blankpage'                 => array( 'ശൂന്യതാള്‍' ),
	'LinkSearch'                => array( 'കണ്ണികള്‍ തിരയുക' ),
	'DeletedContributions'      => array( 'മായ്ച്ച സേവനങ്ങള്‍' ),
	'Tags'                      => array( 'റ്റാഗുകള്‍' ),
	'Activeusers'               => array( 'സജീവ ഉപയോക്താക്കള്‍' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#തിരിച്ചുവിടുക', '#REDIRECT' ),
	'notoc'                 => array( '0', '__ഉള്ളടക്കംവേണ്ട__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__ചിത്രസഞ്ചയംവേണ്ട__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__ഉള്ളടക്കംഇടുക__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__ഉള്ളടക്കം__', '__TOC__' ),
	'noeditsection'         => array( '0', '__സംശോധിക്കേണ്ട__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'ഈമാസം', 'ഈമാസം2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'ഈമാസം1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'ഈമാസത്തിന്റെപേര്‌', 'CURRENTMONTHNAME' ),
	'currentmonthabbrev'    => array( '1', 'ഈമാസത്തിന്റെപേര്‌സംഗ്രഹം', 'ഈമാസത്തിന്റെപേര്‌ചുരുക്കം', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'ഈദിവസം', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'ഈദിവസം2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'ഈദിവസത്തിന്റെപേര്‌', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ഈവര്‍ഷം', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'ഈസമയം', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'ഈമണിക്കൂര്‍', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'പ്രാദേശികമാസം', 'പ്രാദേശികമാസം2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'പ്രാദേശികമാസം1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'പ്രാദേശികമാസത്തിന്റെപേര്‌', 'LOCALMONTHNAME' ),
	'localmonthabbrev'      => array( '1', 'പ്രാദേശികമാസത്തിന്റെപേര്‌സംഗ്രഹം', 'പ്രാദേശികമാസത്തിന്റെപേര്‌ചുരുക്കം', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'പ്രാദേശികദിവസം', 'LOCALDAY' ),
	'localday2'             => array( '1', 'പ്രാദേശികദിവസം2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'പ്രാദേശികദിവസത്തിന്റെപേര്‌', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'പ്രാദേശികവര്‍ഷം', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'പ്രാദേശികസമയം', 'LOCALTIME' ),
	'localhour'             => array( '1', 'പ്രാദേശികമണിക്കൂര്‍', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'താളുകളുടെയെണ്ണം', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'ലേഖങ്ങനളുടെയെണ്ണം', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'പ്രമാണങ്ങളുടെയെണ്ണം', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'ഉപയോക്താക്കളുടെയെണ്ണം', 'അംഗങ്ങളുയെണ്ണം', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'സജീവോപയാക്താക്കളുടെയെണ്ണം', 'NUMBEROFACTIVEUSERS' ),
	'pagename'              => array( '1', 'താളിന്റെപേര്‌', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'താളിന്റെപേര്‌സമഗ്രം', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'നാമമേഖല', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'നാമമേഖലസമഗ്രം', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'സംവാദമേഖല', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'സംവാദമേഖലസമഗ്രം', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'വിഷയമേഖല', 'ലേഖനമേഖല', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'വിഷയമേഖലസമഗ്രം', 'ലേഖനമേഖലസമഗ്രം', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'താളിന്റെമുഴുവന്‍പേര്‌', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'താളിന്റെമുഴുവന്‍പേര്സമഗ്രം', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'അനുബന്ധതാളിന്റെപേര്‌', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'അനുബന്ധതാളിന്റെപേര്സമഗ്രം', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'അടിസ്ഥാനതാളിന്റെപേര്‌', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'അടിസ്ഥാനതാളിന്റെപേര്‌സമഗ്രം', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'സംവാദതാളിന്റെപേര്‌', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'സംവാദതാളിന്റെപേര്‌സമഗ്രം', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'ലേഖനതാളിന്റെപേര്‌', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'ലേഖനതാളിന്റെപേര്‌സമഗ്രം', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                   => array( '0', 'സന്ദേശം:', 'MSG:' ),
	'subst'                 => array( '0', 'ബദല്‍:', 'SUBST:' ),
	'msgnw'                 => array( '0', 'മൂലരൂപം:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'ലഘുചിത്രം', 'ലഘു', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'ലഘുചിത്രം=$1', 'ലഘു=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'വലത്ത്‌', 'വലത്‌', 'right' ),
	'img_left'              => array( '1', 'ഇടത്ത്‌', 'ഇടത്‌', 'left' ),
	'img_none'              => array( '1', 'ശൂന്യം', 'none' ),
	'img_width'             => array( '1', '$1ബിന്ദു', '$1px' ),
	'img_center'            => array( '1', 'നടുവില്‍', 'നടുക്ക്‌', 'center', 'centre' ),
	'img_framed'            => array( '1', 'ചട്ടം', 'ചട്ടത്തില്‍', 'framed', 'enframed', 'frame' ),
	'img_page'              => array( '1', 'താള്‍=$1', 'താള്‍ $1', 'page=$1', 'page $1' ),
	'sitename'              => array( '1', 'സൈറ്റിന്റെപേര്', 'SITENAME' ),
	'ns'                    => array( '0', 'നാമേ:', 'NS:' ),
	'localurl'              => array( '0', 'ലോക്കല്‍യുആര്‍എല്‍:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'ലോക്കല്‍യുആര്‍എല്‍ഇ:', 'LOCALURLE:' ),
	'server'                => array( '0', 'സെര്‍വര്‍', 'SERVER' ),
	'servername'            => array( '0', 'സെര്‍വറിന്റെപേര്', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'സ്ക്രിപ്റ്റ്പാത്ത്', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'വ്യാകരണം:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'ലിംഗം:', 'GENDER:' ),
	'currentweek'           => array( '1', 'ആഴ്ച', 'ആഴ്‌ച', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'ദിവസത്തിന്റെപേര്‌അക്കത്തില്‍', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'പ്രാദേശികആഴ്ച', 'പ്രാദേശികആഴ്‌ച', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'ആഴ്ചയുടെപേര്‌അക്കത്തില്‍', 'ആഴ്‌ചയുടെപേര്‌അക്കത്തില്‍', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'തിരുത്തല്‍അടയാളം', 'REVISIONID' ),
	'revisionday'           => array( '1', 'തിരുത്തിയദിവസം', 'തിരുത്തിയദിനം', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'തിരുത്തിയദിവസം2', 'തിരുത്തിയദിനം2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'തിരുത്തിയമാസം', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'തിരുത്തിയവര്‍ഷം', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'തിരുത്തിയസമയമുദ്ര', 'REVISIONTIMESTAMP' ),
	'plural'                => array( '0', 'ബഹുവചനം:', 'PLURAL:' ),
	'raw'                   => array( '0', 'അസംസ്കൃതം:', 'RAW:' ),
	'displaytitle'          => array( '1', 'ശീര്‍ഷകംപ്രദര്‍ശിപ്പിക്കുക', 'തലക്കെട്ട്പ്രദര്‍ശിപ്പിക്കുക', 'DISPLAYTITLE' ),
	'rawsuffix'             => array( '1', 'വ', 'R' ),
	'newsectionlink'        => array( '1', '__പുതിയവിഭാഗംകണ്ണി__', '__പുതിയഖണ്ഡിക്കണ്ണി__', '__NEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'പതിപ്പ്', 'CURRENTVERSION' ),
	'currenttimestamp'      => array( '1', 'സമയമുദ്ര', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'പ്രാദേശികസമയമുദ്ര', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'ദിശാസൂചിക', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#ഭാഷ:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'ഉള്ളടക്കഭാഷ', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'നാമമേഖലയിലുള്ളതാളുകള്‍', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'കാര്യനിര്‍വ്വാഹകരുടെഎണ്ണം', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'ദശാംശഘടന', 'സംഖ്യാഘടന', 'FORMATNUM' ),
	'padleft'               => array( '0', 'ഇടത്ത്നിറക്കുക', 'PADLEFT' ),
	'padright'              => array( '0', 'വലത്ത്നിറക്കുക', 'PADRIGHT' ),
	'special'               => array( '0', 'പ്രത്യേകം', 'special' ),
	'hiddencat'             => array( '1', '‌‌__മറഞ്ഞിരിക്കുംവർഗ്ഗം__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'വർഗ്ഗത്തിലുള്ളതാളുകൾ', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'താൾവലിപ്പം', 'PAGESIZE' ),
	'index'                 => array( '1', '‌‌__സൂചിക__', '__INDEX__' ),
	'noindex'               => array( '1', '__സൂചികവേണ്ട__', '__NOINDEX__' ),
	'formatdate'            => array( '0', 'ദിനരേഖീകരണരീതി', 'ദിവസരേഖീകരണരീതി', 'formatdate', 'dateformat' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'കണ്ണികള്‍ക്ക് അടിവരയിടുക:',
'tog-highlightbroken'         => 'നിലവിലില്ലാത്ത കണ്ണികകള്‍ <a href="" class="new">ഇങ്ങിനെ</a> അടയാളപ്പെടുത്തുക (അഥവാ: ഇങ്ങിനെ <a href="" class="internal">?</a>).',
'tog-justify'                 => 'ഖണ്ഡികകളുടെ അരികുകള്‍ നേരെയാക്കുക',
'tog-hideminor'               => 'പുതിയ മാറ്റങ്ങളുടെ പട്ടികയില്‍ ചെറിയ തിരുത്തലുകള്‍ പ്രദര്‍ശിപ്പിക്കാതിരിക്കുക',
'tog-hidepatrolled'           => 'റോന്തുചുറ്റിയ തിരുത്തുകള്‍ പുതിയമാറ്റങ്ങളില്‍ പ്രദര്‍ശിപ്പിക്കാതിരിക്കുക',
'tog-newpageshidepatrolled'   => 'റോന്തുചുറ്റിയ താളുകള്‍ പുതിയതാളുകളുടെ പട്ടികയില്‍ പ്രദര്‍ശിപ്പിക്കാതിരിക്കുക',
'tog-extendwatchlist'         => 'എല്ലാ മാറ്റങ്ങളും ദൃശ്യമാകുന്ന വിധത്തില്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക വികസിപ്പിക്കുക, ഏറ്റവും പുതിയവ മാത്രമല്ല',
'tog-usenewrc'                => 'വിപുലീകരിച്ച പുതിയ മാറ്റങ്ങള്‍ ഉപയോഗിക്കുക(ജാവാസ്ക്രിപ്റ്റ് ആവശ്യമാണ്)',
'tog-numberheadings'          => 'ഉപവിഭാഗങ്ങള്‍ക്ക് നമ്പര്‍ കൊടുക്കുക',
'tog-showtoolbar'             => 'എഡിറ്റ് റ്റൂള്‍ബാര്‍  പ്രദര്‍ശിപ്പിക്കുക (ജാവാസ്ക്രിപ്റ്റ്)',
'tog-editondblclick'          => 'താളുകളില്‍ ഡബിള്‍ ക്ലിക്ക് ചെയ്യുമ്പോള്‍ തിരുത്താനനുവദിക്കുക (ജാവാസ്ക്രിപ്റ്റ്)',
'tog-editsection'             => 'ഉപവിഭാഗങ്ങളുടെ തിരുത്തല്‍ [തിരുത്തുക] എന്ന കണ്ണിയുപയോഗിച്ച് ചെയ്യുവാന്‍ അനുവദിക്കുക',
'tog-editsectiononrightclick' => 'ഉപവിഭാഗങ്ങളുടെ തലക്കെട്ടില്‍ റൈറ്റ് ക്ലിക്ക് ചെയ്യുന്നതു വഴി തിരുത്താനനുവദിക്കുക (ജാവാസ്ക്രിപ്റ്റ്)',
'tog-showtoc'                 => 'ഉള്ളടക്കപ്പട്ടിക പ്രദര്‍ശിപ്പിക്കുക (മൂന്നില്‍ കൂടുതല്‍ ഉപശീര്‍ഷകങ്ങളുള്ള താളുകള്‍ക്കു മാത്രം)',
'tog-rememberpassword'        => 'എന്റെ പ്രവേശനം ഈ കമ്പ്യൂട്ടറില്‍ ഓര്‍ത്തുവെക്കുക',
'tog-editwidth'               => 'സ്ക്രീന്‍ മുഴുവന്‍ നിറയുന്ന വിധത്തില്‍ തിരുത്തല്‍ പെട്ടി വിസ്താരമുള്ളതാക്കുക',
'tog-watchcreations'          => 'ഞാന്‍ നിര്‍മ്മിക്കുന്ന താളുകള്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയില്‍ ചേര്‍ക്കുക',
'tog-watchdefault'            => 'ഞാന്‍ തിരുത്തുന്ന താളുകള്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയില്‍ ചേര്‍ക്കുക',
'tog-watchmoves'              => 'ഞാന്‍ തലക്കെട്ടു മാറ്റുന്ന താളുകള്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയില്‍ ചേര്‍ക്കുക',
'tog-watchdeletion'           => 'ഞാന്‍ നീക്കം ചെയ്യുന്ന താളുകള്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയില്‍ ചേര്‍ക്കുക',
'tog-minordefault'            => 'എല്ലാ തിരുത്തലുകളും ചെറുതിരുത്തലുകളായി സ്വയം അടയാളപ്പെടുത്തുക',
'tog-previewontop'            => 'തിരുത്തല്‍ പെട്ടിക്കു മുകളില്‍ പ്രിവ്യൂ കാണിക്കുക',
'tog-previewonfirst'          => 'ആദ്യത്തെ തിരുത്തലിന്റെ പ്രിവ്യൂ കാണിക്കുക',
'tog-nocache'                 => 'താളുകള്‍ തദ്ദേശീയമായി സംഭരിച്ചുവയ്ക്കുന്നത് നിര്‍ത്തലാക്കുക',
'tog-enotifwatchlistpages'    => 'ഞാന്‍ ശ്രദ്ധിക്കുന്ന താളുകള്‍ക്കു മാറ്റം സംഭവിച്ചാല്‍ എനിക്കു ഇമെയില്‍ അയക്കുക',
'tog-enotifusertalkpages'     => 'എന്റെ സം‌വാദം താളിനു മാറ്റം സംഭവിച്ചാല്‍ ഇമെയില്‍ അയക്കുക',
'tog-enotifminoredits'        => 'ചെറുതിരുത്തലുകള്‍ക്കും എനിക്ക് ഇമെയില്‍ അയയ്ക്കുക',
'tog-enotifrevealaddr'        => 'വിജ്ഞാപന മെയിലുകളില്‍ എന്റെ ഇമെയില്‍ വിലാസം വെളിവാക്കാന്‍ അനുവദിക്കുക',
'tog-shownumberswatching'     => 'ശ്രദ്ധിക്കുന്ന ഉപയോക്താക്കളുടെ എണ്ണം കാണിക്കുക',
'tog-oldsig'                  => 'നിലവിലുള്ള ഒപ്പ് എങ്ങിനെയുണ്ടെന്നു കാണുക:',
'tog-fancysig'                => 'ഒപ്പ് ഒരു വിക്കിടെക്സ്റ്റായി പരിഗണിക്കുക (കണ്ണി സ്വയം ചേര്‍ക്കേണ്ടതില്ല)',
'tog-externaleditor'          => 'തിരുത്തലുകള്‍ക്കായി ബാഹ്യ ഉപകരണങ്ങള്‍ സ്വതവേ ഉപയോഗിക്കുക',
'tog-externaldiff'            => 'വ്യത്യാസം അറിയാനായി ബാഹ്യ ഉപകരണങ്ങള്‍ സ്വതവേ ഉപയോഗിക്കുക',
'tog-showjumplinks'           => '"പോവുക" ഗമ്യത കണ്ണികള്‍ പ്രാപ്തമാക്കുക',
'tog-uselivepreview'          => 'ലൈവ് പ്രിവ്യൂ ഉപയോഗപ്പെടുത്തുക (ജാവാസ്ക്രിപ്റ്റ്) (പരീക്ഷണാടിസ്ഥാനത്തിലുള്ളത്)',
'tog-forceeditsummary'        => 'തിരുത്തലുകളുടെ ചുരുക്കം നല്‍കിയില്ലെങ്കില്‍ എന്നെ ഓര്‍മ്മിപ്പിക്കുക',
'tog-watchlisthideown'        => 'ഞാന്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയില്‍നിന്ന് എന്റെ തിരുത്തലുകള്‍ മറയ്ക്കുക',
'tog-watchlisthidebots'       => 'ഞാന്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയില്‍നിന്ന് യന്ത്രങ്ങള്‍ വരുത്തിയ തിരുത്തലുകള്‍ മറയ്ക്കുക',
'tog-watchlisthideminor'      => 'ഞാന്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയില്‍നിന്ന് ചെറുതിരുത്തലുകള്‍ മറയ്ക്കുക',
'tog-watchlisthideliu'        => 'ഞാന്‍ ശ്രദ്ധിക്കുന്ന താളുകളിലെ മാറ്റങ്ങളില്‍ നിന്നും ലോഗിന്‍ ചെയ്തിട്ടുള്ളവരുടെ തിരുത്തലുകള്‍ മറയ്ക്കുക',
'tog-watchlisthideanons'      => 'ഞാന്‍ ശ്രദ്ധിക്കുന്ന താളുകളിലെ മാറ്റങ്ങളില്‍ നിന്നും അജ്ഞാത ഉപയോക്താക്കളുടെ തിരുത്തുകള്‍ മറയ്ക്കുക',
'tog-watchlisthidepatrolled'  => 'റോന്തുചുറ്റിയ തിരുത്തുകള്‍ ശ്രദ്ധിക്കുന്നവയില്‍ പ്രദര്‍ശിപ്പിക്കാതിരിക്കുക',
'tog-ccmeonemails'            => 'ഞാന്‍ മറ്റുള്ളവര്‍ക്കയക്കുന്ന ഈമെയിലുകളുടെ ഒരു പകര്‍പ്പ് എനിക്കും അയക്കുക',
'tog-diffonly'                => 'രണ്ട് പതിപ്പുകള്‍ തമ്മിലുള്ള വ്യത്യാസത്തിനു താഴെ താളിന്റെ ഉള്ളടക്കം കാണിക്കരുത്.',
'tog-showhiddencats'          => 'മറഞ്ഞിരിക്കുന്ന വിഭാഗങ്ങളെ കാണിക്കുക',
'tog-norollbackdiff'          => 'റോള്‍ബാക്കിനു ശേഷം വ്യത്യാസം കാണിക്കാതിരിക്കുക',

'underline-always'  => 'എല്ലായ്പ്പോഴും',
'underline-never'   => 'ഒരിക്കലും അരുത്',
'underline-default' => 'ബ്രൗസറിലേതു പോലെ',

# Font style option in Special:Preferences
'editfont-style'     => 'തിരുത്തല്‍ മേഖലയിലെ ഫോണ്ടിന്റെ ശൈലി',
'editfont-default'   => 'ബ്രൗസറിലേതു പോലെ',
'editfont-monospace' => 'മോണോസ്പേസ്ഡ് ഫോണ്ട്',
'editfont-sansserif' => 'സാന്‍സ്-സെറിഫ് ഫോണ്ട്',
'editfont-serif'     => 'സെറിഫ് ഫോണ്ട്',

# Dates
'sunday'        => 'ഞായര്‍',
'monday'        => 'തിങ്കള്‍',
'tuesday'       => 'ചൊവ്വ',
'wednesday'     => 'ബുധന്‍',
'thursday'      => 'വ്യാഴം',
'friday'        => 'വെള്ളി',
'saturday'      => 'ശനി',
'sun'           => 'ഞാ.',
'mon'           => 'തി.',
'tue'           => 'ചൊ.',
'wed'           => 'ബു.',
'thu'           => 'വ്യാ.',
'fri'           => 'വെ.',
'sat'           => 'ശ.',
'january'       => 'ജനുവരി',
'february'      => 'ഫെബ്രുവരി',
'march'         => 'മാര്‍ച്ച്',
'april'         => 'ഏപ്രില്‍',
'may_long'      => 'മേയ്',
'june'          => 'ജൂണ്‍',
'july'          => 'ജൂലൈ',
'august'        => 'ഓഗസ്റ്റ്',
'september'     => 'സെപ്റ്റംബര്‍',
'october'       => 'ഒക്ടോബര്‍',
'november'      => 'നവംബര്‍',
'december'      => 'ഡിസംബര്‍',
'january-gen'   => 'ജനുവരി',
'february-gen'  => 'ഫെബ്രുവരി',
'march-gen'     => 'മാര്‍ച്ച്',
'april-gen'     => 'ഏപ്രില്‍',
'may-gen'       => 'മേയ്',
'june-gen'      => 'ജൂണ്‍',
'july-gen'      => 'ജൂലൈ',
'august-gen'    => 'ഓഗസ്റ്റ്',
'september-gen' => 'സെപ്റ്റംബര്‍',
'october-gen'   => 'ഒക്ടോബര്‍',
'november-gen'  => 'നവംബര്‍',
'december-gen'  => 'ഡിസംബര്‍',
'jan'           => 'ജനു.',
'feb'           => 'ഫെബ്രു.',
'mar'           => 'മാര്‍.',
'apr'           => 'ഏപ്രി.',
'may'           => 'മേയ്‌',
'jun'           => 'ജൂണ്‍',
'jul'           => 'ജൂലൈ',
'aug'           => 'ഓഗ.',
'sep'           => 'സെപ്റ്റം.',
'oct'           => 'ഒക്ടോ.',
'nov'           => 'നവം.',
'dec'           => 'ഡിസം.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|വര്‍ഗ്ഗം|വര്‍ഗ്ഗങ്ങള്‍}}',
'category_header'                => '"$1" എന്ന വര്‍ഗ്ഗത്തിലെ താളുകള്‍',
'subcategories'                  => 'ഉപവര്‍ഗ്ഗങ്ങള്‍',
'category-media-header'          => '"$1" എന്ന വര്‍ഗ്ഗത്തിലെ പ്രമാണങ്ങള്‍',
'category-empty'                 => "''ഈ വര്‍ഗ്ഗത്തില്‍ താളുകളോ പ്രമാണങ്ങളോ ഇല്ല.''",
'hidden-categories'              => '{{PLURAL:$1|മറഞ്ഞിരിക്കുന്ന വര്‍ഗ്ഗം|മറഞ്ഞിരിക്കുന്ന വര്‍ഗ്ഗങ്ങള്‍}}',
'hidden-category-category'       => 'മറഞ്ഞിരിക്കുന്ന വിഭാഗങ്ങള്‍',
'category-subcat-count'          => '{{PLURAL:$2|ഈ വിഭാഗത്തില്‍ താഴെ പ്രദര്‍ശിപ്പിച്ചിരിക്കുന്ന ഒരു ഉപവിഭാഗം മാത്രമേ ഉള്ളൂ.|മൊത്തം $2 ഉപവിഭാഗങ്ങള്‍ ഉള്ളതില്‍  ഈ വിഭാഗത്തില്‍ താഴെ പ്രദര്‍ശിപ്പിച്ചിരിക്കുന്ന {{PLURAL:$1|ഉപവിഭാഗം|$1 ഉപവിഭാഗങ്ങള്‍}} ആണ്‌ ഉള്ളത്.}}',
'category-subcat-count-limited'  => 'ഈ വിഭാഗത്തിനു താഴെ കാണുന്ന {{PLURAL:$1|ഉപവിഭാഗമുണ്ട്|$1 ഉപവിഭാഗങ്ങളുണ്ട്}}.',
'category-article-count'         => '{{PLURAL:$2|ഈ വര്‍ഗ്ഗത്തില്‍ താഴെ പ്രദര്‍ശിപ്പിച്ചിരിക്കുന്ന ഒരു താളേ ഉള്ളൂ.|ഈ വര്‍ഗ്ഗത്തില്‍ $2 താളുകളുള്ളതില്‍ {{PLURAL:$1|ഒരു താള്‍|$1 താളുകള്‍}} താഴെ പ്രദര്‍ശിപ്പിച്ചിരിക്കുന്നു.}}',
'category-article-count-limited' => 'ഈ വിഭാഗത്തില്‍ താഴെ പ്രദര്‍ശിപ്പിച്ചിരിക്കുന്ന {{PLURAL:$1|ഒരു താള്‍ ഉണ്ട്|$1 താളുകള്‍ ഉണ്ട്}}.',
'category-file-count'            => '{{PLURAL:$2|ഈ വിഭാഗത്തില്‍ താഴെ കാണുന്ന ഒരു പ്രമാണം മാത്രമേ ഉള്ളൂ.|മൊത്തം $2 പ്രമാണങ്ങളുള്ളതില്‍ {{PLURAL:$1|ഒരു പ്രമാണം|$1 പ്രമാണങ്ങള്‍}} താഴെ കാണിച്ചിരിക്കുന്നു.}}',
'category-file-count-limited'    => 'ഈ കാറ്റഗറിയില്‍ താഴെ കാണുന്ന {{PLURAL:$1|ഒരു ഫയല്‍|$1 ഫയലുകള്‍}} ഉണ്ട്.',
'listingcontinuesabbrev'         => 'തുടരുന്നു',
'index-category'                 => 'വർഗ്ഗീകരിക്കപ്പെട്ട താളുകൾ',
'noindex-category'               => 'വർഗ്ഗീകരിക്കപ്പെടാത്ത താളുകൾ',

'mainpagetext'      => "<big>'''മീഡിയവിക്കി വിജയകരമായി സജ്ജീകരിച്ചിരിക്കുന്നു.'''</big>",
'mainpagedocfooter' => 'വിക്കി സോഫ്റ്റ്‌വെയര്‍ ഉപയോഗിക്കുന്നതിനെ കുറിച്ചുള്ള വിശദാംശങ്ങള്‍ക്ക്  [http://meta.wikimedia.org/wiki/Help:Contents സോഫ്റ്റ്‌വെയര്‍ സഹായി] കാണുക.

== പ്രാരംഭസഹായികള്‍ ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings ക്രമീകരണങ്ങളുടെ പട്ടിക]
* [http://www.mediawiki.org/wiki/Manual:FAQ മീഡിയവിക്കി സ്ഥിരംചോദ്യങ്ങള്‍]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce മീഡിയവിക്കി പ്രകാശന മെയിലിംങ്ങ് ലിസ്റ്റ്]',

'about'         => 'വിവരണം',
'article'       => 'ലേഖന താള്‍',
'newwindow'     => '(പുതിയ വിന്‍ഡോയില്‍ തുറന്നു വരും)',
'cancel'        => 'റദ്ദാക്കുക',
'moredotdotdot' => 'കൂടുതല്‍...',
'mypage'        => 'എന്റെ താള്‍',
'mytalk'        => 'എന്റെ സംവാദവേദി',
'anontalk'      => 'ഈ ഐപിയുടെ സം‌വാദം താള്‍',
'navigation'    => 'ഉള്ളടക്കം',
'and'           => '&#32;ഒപ്പം',

# Cologne Blue skin
'qbfind'         => 'കണ്ടെത്തുക',
'qbbrowse'       => 'പരതുക',
'qbedit'         => 'തിരുത്തുക',
'qbpageoptions'  => 'ഈ താള്‍',
'qbpageinfo'     => 'സന്ദര്‍ഭം',
'qbmyoptions'    => 'എന്റെ താളുകള്‍',
'qbspecialpages' => 'പ്രത്യേക താളുകള്‍',
'faq'            => 'പതിവുചോദ്യങ്ങള്‍',
'faqpage'        => 'Project:പതിവുചോദ്യങ്ങള്‍',

# Vector skin
'vector-action-addsection'   => 'വിഷയം ചേര്‍ക്കുക',
'vector-action-delete'       => 'മായ്ക്കുക',
'vector-action-move'         => 'മാറ്റുക',
'vector-action-protect'      => 'സം‌രക്ഷിക്കുക',
'vector-action-undelete'     => 'മായ്ക്കപ്പെട്ടത് പുനഃസ്ഥാപിക്കുക',
'vector-action-unprotect'    => 'സം‌രക്ഷണം നീക്കുക',
'vector-namespace-category'  => 'വര്‍ഗ്ഗം',
'vector-namespace-help'      => 'സഹായം താള്‍',
'vector-namespace-image'     => 'പ്രമാണം',
'vector-namespace-main'      => 'താള്‍',
'vector-namespace-media'     => 'മീഡിയ താള്‍',
'vector-namespace-mediawiki' => 'സന്ദേശം',
'vector-namespace-project'   => 'പദ്ധതി താള്‍',
'vector-namespace-special'   => 'പ്രത്യേക താള്‍',
'vector-namespace-talk'      => 'സം‌വാദം',
'vector-namespace-template'  => 'ഫലകം',
'vector-namespace-user'      => 'ഉപയോക്താവിന്റെ താള്‍',
'vector-view-create'         => 'സൃഷ്ടിക്കുക',
'vector-view-edit'           => 'തിരുത്തുക',
'vector-view-history'        => 'നാള്‍വഴി കാണുക',
'vector-view-view'           => 'വായിക്കുക',
'vector-view-viewsource'     => 'മൂലരൂപം കാണുക',
'actions'                    => 'നടപടികള്‍',
'namespaces'                 => 'നാമമേഖല',
'variants'                   => 'ചരങ്ങള്‍',

# Metadata in edit box
'metadata_help' => 'മെറ്റാഡാറ്റ:',

'errorpagetitle'    => 'പിശക്',
'returnto'          => '$1 എന്ന താളിലേക്ക് തിരിച്ചുപോവുക.',
'tagline'           => '{{SITENAME}} സംരംഭത്തില്‍ നിന്ന്',
'help'              => 'സഹായം',
'search'            => 'തിരയൂ',
'searchbutton'      => 'തിരയൂ',
'go'                => 'പോകൂ',
'searcharticle'     => 'പോകൂ',
'history'           => 'നാള്‍വഴി',
'history_short'     => 'നാള്‍വഴി',
'updatedmarker'     => 'എന്റെ കഴിഞ്ഞ സന്ദര്‍ശനത്തിനുശേഷം നവീകരിക്കപ്പെട്ടവ',
'info_short'        => 'വൃത്താന്തം',
'printableversion'  => 'അച്ചടിരൂപം',
'permalink'         => 'സ്ഥിരംകണ്ണി',
'print'             => 'പ്രിന്റ്',
'edit'              => 'മാറ്റിയെഴുതുക',
'create'            => 'ഈ താള്‍ സൃഷ്ടിക്കുക',
'editthispage'      => 'ഈ താള്‍ തിരുത്തുക',
'create-this-page'  => 'ഈ താള്‍ സൃഷ്ടിക്കുക',
'delete'            => 'താള്‍ മായ്ക്കുക',
'deletethispage'    => 'ഈ താള്‍ നീക്കം ചെയ്യുക',
'undelete_short'    => '{{PLURAL:$1|ഒരു തിരുത്തല്‍|$1 തിരുത്തലുകള്‍}} പുനഃസ്ഥാപിക്കുക',
'protect'           => 'സം‌രക്ഷിക്കുക',
'protect_change'    => 'സംരക്ഷണമാനത്തില്‍ വ്യതിയാനം വരുത്തുക',
'protectthispage'   => 'ഈ താള്‍ സം‌രക്ഷിക്കുക',
'unprotect'         => 'സം‌രക്ഷണരഹിതമാക്കുക',
'unprotectthispage' => 'ഈ താള്‍ സം‌രക്ഷണരഹിതമാക്കുക',
'newpage'           => 'പുതിയ താള്‍',
'talkpage'          => 'ഈ താളിനെക്കുറിച്ച്‌ ചര്‍ച്ച ചെയ്യുക',
'talkpagelinktext'  => 'സംവാദം',
'specialpage'       => 'പ്രത്യേക താള്‍',
'personaltools'     => 'സ്വകാര്യതാളുകള്‍',
'postcomment'       => 'അഭിപ്രായം ചേര്‍ക്കുക',
'articlepage'       => 'ലേഖനം കാണുക',
'talk'              => 'സംവാദം',
'views'             => 'താളിന്റെ അനുബന്ധങ്ങള്‍',
'toolbox'           => 'പണിസഞ്ചി',
'userpage'          => 'ഉപയോക്താവിന്റെ താള്‍ കാണുക',
'projectpage'       => 'സം‌രംഭംതാള്‍ കാണുക',
'imagepage'         => 'മീഡിയ താള്‍ കാണുക',
'mediawikipage'     => 'സന്ദേശങ്ങളുടെ താള്‍ കാണുക',
'templatepage'      => 'ഫലകം താള്‍ കാണുക',
'viewhelppage'      => 'സഹായം താള്‍ കാണുക',
'categorypage'      => 'വര്‍ഗ്ഗം താള്‍ കാണുക',
'viewtalkpage'      => 'സം‌വാദം കാണുക',
'otherlanguages'    => 'ഇതര ഭാഷകളില്‍',
'redirectedfrom'    => '($1-താളില്‍ നിന്നും തിരിച്ചു വിട്ടതു പ്രകാരം)',
'redirectpagesub'   => 'തിരിച്ചുവിടല്‍ താള്‍',
'lastmodifiedat'    => 'ഈ താള്‍ അവസാനം തിരുത്തപ്പെട്ടത് $2, $1.',
'viewcount'         => 'ഈ താള്‍ {{PLURAL:$1|ഒരു തവണ|$1 തവണ}} സന്ദര്‍ശിക്കപ്പെട്ടിട്ടുണ്ട്.',
'protectedpage'     => 'സംരക്ഷിത താള്‍',
'jumpto'            => 'പോവുക:',
'jumptonavigation'  => 'വഴികാട്ടി',
'jumptosearch'      => 'തിരയൂ',
'view-pool-error'   => 'ക്ഷമിക്കണം, ഈ നിമിഷം സെര്‍വറുകള്‍ അഭിതഭാരം കൈകാര്യം ചെയ്യുകയാണ്.
ധാരാളം ഉപയോക്താക്കള്‍ ഈ താള്‍ കാണുവാന്‍ ശ്രമിച്ചുകൊണ്ടിരിക്കുകയാണ്.
ഇനിയും താള്‍ ലഭ്യമാക്കുവാന്‍ താങ്കള്‍ ശ്രമിക്കുന്നതിന് മുന്‍പ് ദയവായി അല്പസമയം കത്തിരിക്കുക. 

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} സം‌രംഭത്തെക്കുറിച്ച്',
'aboutpage'            => 'Project:വിവരണം',
'copyright'            => 'ഉള്ളടക്കം $1 പ്രകാരം ലഭ്യം.',
'copyrightpage'        => '{{ns:project}}:പകര്‍പ്പവകാശം',
'currentevents'        => 'സമകാലികം',
'currentevents-url'    => 'Project:സമകാലികം',
'disclaimers'          => 'നിരാകരണങ്ങള്‍',
'disclaimerpage'       => 'Project:പൊതുനിരാകരണം',
'edithelp'             => 'തിരുത്തല്‍ സഹായി',
'edithelppage'         => 'Help:തിരുത്തല്‍ വഴികാട്ടി',
'helppage'             => 'Help:ഉള്ളടക്കം',
'mainpage'             => 'പ്രധാന താള്‍',
'mainpage-description' => 'പ്രധാന താള്‍',
'policy-url'           => 'Project:നയം',
'portal'               => 'സാമൂഹ്യകവാടം',
'portal-url'           => 'Project:സാമൂഹ്യകവാടം',
'privacy'              => 'സ്വകാര്യതാനയം',
'privacypage'          => 'Project:സ്വകാര്യതാനയം',

'badaccess'        => 'അനുമതിപ്രശ്നം',
'badaccess-group0' => 'താങ്കള്‍ ആവശ്യപ്പെട്ട കാര്യം ചെയ്യാനുള്ള അനുമതി താങ്കള്‍ക്കില്ല',
'badaccess-groups' => 'താങ്കള്‍ ആവശ്യപ്പെട്ട കാര്യം ചെയ്യാന്‍ {{PLURAL:$2|$1 ഗ്രൂപ്പിലെ|$1 എന്നീ ഗ്രൂപ്പുകളില്‍ ഏതെങ്കിലും ഒന്നിലെ}} അംഗങ്ങള്‍ക്കു മാത്രമേ സാധിക്കൂ',

'versionrequired'     => 'മീഡിയാവിക്കിയുടെ $1-ആം പതിപ്പ് ആവശ്യമാണ്',
'versionrequiredtext' => 'ഈ താള്‍ ഉപയോഗിക്കാന്‍ മീഡിയവിക്കി പതിപ്പ് $1 ആവശ്യമാണ്. കൂടുതല്‍ വിവരങ്ങള്‍ക്ക് [[Special:Version|മീഡിയാവിക്കി പതിപ്പ് താള്‍]] കാണുക.',

'ok'                      => 'ശരി',
'retrievedfrom'           => '"$1" എന്ന താളില്‍നിന്നു ശേഖരിച്ചത്',
'youhavenewmessages'      => 'താങ്കള്‍ക്ക് $1 ഉണ്ട് ($2).',
'newmessageslink'         => 'പുതിയ സന്ദേശങ്ങള്‍',
'newmessagesdifflink'     => 'അവസാന മാറ്റം',
'youhavenewmessagesmulti' => 'താങ്കള്‍ക്ക് $1-ല്‍ പുതിയ സന്ദേശങ്ങള്‍ ഉണ്ട്',
'editsection'             => 'തിരുത്തുക',
'editold'                 => 'തിരുത്തുക',
'viewsourceold'           => 'മൂലരൂപം കാണുക',
'editlink'                => 'തിരുത്തുക',
'viewsourcelink'          => 'മൂലരൂപം കാണുക',
'editsectionhint'         => 'ഉപവിഭാഗം തിരുത്തുക: $1',
'toc'                     => 'ഉള്ളടക്കം',
'showtoc'                 => 'പ്രദര്‍ശിപ്പിക്കുക',
'hidetoc'                 => 'മറയ്ക്കുക',
'thisisdeleted'           => '$1 കാണുകയോ പുനഃസ്ഥാപിക്കുകയോ ചെയ്യേണ്ടതുണ്ടോ?',
'viewdeleted'             => '$1 കാണണോ?',
'restorelink'             => '{{PLURAL:$1|നീക്കംചെയ്ത ഒരു തിരുത്തല്‍|നീക്കംചെയ്ത $1 തിരുത്തലുകള്‍}}',
'feedlinks'               => 'ഫീഡ്:',
'feed-invalid'            => 'അസാധുവായ സബ്‌സ്ക്രിപ്ഷന്‍ ഫീഡ് തരം.',
'feed-unavailable'        => 'സിന്‍ഡിക്കേഷന്‍ ഫീഡുകള്‍ ലഭ്യമല്ല',
'site-rss-feed'           => '$1 ന്റെ ആര്‍.എസ്.എസ് ഫീഡ്',
'site-atom-feed'          => '$1 ന്റെ ആറ്റം ഫീഡ്',
'page-rss-feed'           => '"$1" ന്റെ ആര്‍.എസ്.എസ്. ഫീഡ്',
'page-atom-feed'          => '"$1" ആറ്റം ഫീഡ്',
'red-link-title'          => '$1 (ഇതുവരെ എഴുതപ്പെട്ടിട്ടില്ല)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ലേഖനം',
'nstab-user'      => 'ഉപയോക്തൃതാള്‍',
'nstab-media'     => 'മീഡിയാ താള്‍',
'nstab-special'   => 'പ്രത്യേക താള്‍',
'nstab-project'   => 'പദ്ധതി താള്‍',
'nstab-image'     => 'പ്രമാണം',
'nstab-mediawiki' => 'സന്ദേശം',
'nstab-template'  => 'ഫലകം',
'nstab-help'      => 'സഹായ താള്‍',
'nstab-category'  => 'വര്‍ഗ്ഗം',

# Main script and global functions
'nosuchaction'      => 'ഈ പ്രവൃത്തി അസാധുവാണ്‌',
'nosuchactiontext'  => 'യുആര്‍‌എല്‍ വഴി നിര്‍‌വചിച്ച പ്രവര്‍ത്തനം വിക്കി തിരിച്ചറിഞ്ഞില്ല. താങ്കള്‍ യുആര്‍‌എല്‍ തെറ്റായി നല്‍കിയിരിക്കാം അല്ലെങ്കില്‍ ഒരു തെറ്റായ ലിങ്കുവഴി വന്നിരിക്കാം.  
ഒരുപക്ഷേ, ഇത് {{SITENAME}} ഉപയോഗിക്കുന്ന സോഫ്റ്റ്‌വെയറിലെ ബഗ്ഗും ആകാം.',
'nosuchspecialpage' => 'അത്തരമൊരു പ്രത്യേകതാള്‍ നിലവിലില്ല',
'nospecialpagetext' => '<strong>താങ്കള്‍ നിലവിലില്ലാത്ത ഒരു പ്രത്യേകതാള്‍ ആണ് ആവശ്യപ്പെട്ടത്.</strong>

നിലവിലുള്ള പ്രത്യേകതാളുകളുടെ പട്ടിക കാണാന്‍ [[Special:SpecialPages|{{int:specialpages}}]] ശ്രദ്ധിക്കുക.',

# General errors
'error'                => 'കുഴപ്പം',
'databaseerror'        => 'ഡാറ്റാബേസ് പിശക്',
'dberrortext'          => 'ഒരു വിവരശേഖര അന്വേഷണത്തിന്റെ ഉപയോഗക്രമത്തില്‍ പിഴവ് സംഭവിച്ചിരിക്കുന്നു.
ഇത് ചിലപ്പോള്‍ സോഫ്റ്റ്‌വെയര്‍ ബഗ്ഗിനെ സൂചിപ്പിക്കുന്നതാവാം.
അവസാനം ശ്രമിച്ച വിവരശേഖര അന്വേഷണം താഴെ കൊടുക്കുന്നു:
<blockquote><tt>$1</tt></blockquote>
"<tt>$2</tt>" എന്ന നിര്‍ദ്ദേശത്തിനകത്ത് നിന്നും.
വിവരശേഖരത്തില്‍ നിന്നും ലഭിച്ച പിഴവ് "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'വിവരശേഖര അന്വേഷണ ഘടനയില്‍ ഒരു പിഴവ് സംഭവിച്ചിരിക്കുന്നു.
അവസാനം ശ്രമിച്ച വിവരശേഖര അന്വേഷണം താഴെ കൊടുക്കുന്നു:
"$1"
"$2" എന്ന നിര്‍ദ്ദേശത്തിനകത്ത് നിന്നും .
വിവരശേഖരത്തില്‍ നിന്നും ലഭിച്ച പിഴവ് "$3: $4"',
'laggedslavemode'      => 'മുന്നറിയിപ്പ്: താളില്‍ അടുത്തകാലത്ത് വരുത്തിയ പുതുക്കലുകള്‍ ഉണ്ടാവണമെന്നില്ല.',
'readonly'             => 'ഡാറ്റാബേസ് ബന്ധിച്ചിരിക്കുന്നു',
'enterlockreason'      => 'ഡാറ്റാബേസ് ബന്ധിക്കുവാനുള്ള കാരണം സൂചിപ്പിക്കുക. അതോടൊപ്പം എപ്പോഴാണ്‌ ബന്ധനം അഴിക്കുവാന്‍ ഉദ്ദേശിക്കുന്നതെന്നും രേഖപ്പെടുത്തുക.',
'readonlytext'         => 'പുതിയ തിരുത്തലുകളും മറ്റ് മാറ്റങ്ങളും അനുവദനീയമല്ലാത്ത വിധത്തില്‍ ഡാറ്റാബേസ് ബന്ധിച്ചിരിക്കുകയാണ്‌. ക്രമപ്രകാരമുള്ള വൃത്തിയാക്കലിനു വേണ്ടി ബന്ധിച്ച ഡാറ്റാബേസ് താമസിയാതെ തന്നെ സാധാരണ നില കൈവരിക്കും.

ഡാറ്റാബേസ് ബന്ധിച്ച കാര്യനിര്‍‌വാഹകന്‍ അതിനു സൂചിപ്പിച്ച കാരണം: $1',
'missing-article'      => 'താളില്‍ ഉണ്ടായിരിക്കേണ്ട വിവരങ്ങള്‍ ("$1" $2) വിവരശേഖരത്തിനു കണ്ടെത്താനായില്ല.

ഇത് സാധാരണയായി തുടര്‍ന്നു വരുന്ന നീക്കം ചെയ്യപ്പെട്ട താളിലേക്കുള്ള തെറ്റായതിയ്യതിയില്‍ വരുന്ന മാറ്റം അല്ലെങ്കില്‍ നാള്‍വഴി കണ്ണി മൂലമാണ് സംഭവിക്കാറുള്ളത്.

കാരണം ഇതല്ലെങ്കില്‍, ഒരു സോഫ്റ്റ്‌വെയര്‍ ബഗ്ഗ് ആയിരിക്കാം.
ദയവായി താളിന്റെ യു.ആര്‍.എല്‍ സഹിതം ഒരു [[Special:ListUsers/sysop|കാര്യനിര്‍വാഹകനെ]] ഇത് അറിയിക്കുക.',
'missingarticle-rev'   => '(മാറ്റം#: $1)',
'missingarticle-diff'  => '(വ്യത്യാസം: $1, $2)',
'readonly_lag'         => 'വിവരശേഖരം സ്വയം ബന്ധിക്കപ്പെട്ടിരിക്കുന്നു അതേസമയം കീഴ്-വിവരശേഖര സെര്‍വറുകള്‍ മാസ്റ്റര്‍ വരെ പിടിച്ചിരിക്കുന്നു',
'internalerror'        => 'ആന്തരികമായ പ്രശ്നം',
'internalerror_info'   => 'ആന്തരികപ്രശ്നം: $1',
'fileappenderror'      => '"$1" എന്നത് "$2"-ലേക്ക് കൂട്ടിച്ചേര്‍ക്കുവാന്‍ സാധിച്ചില്ല.',
'filecopyerror'        => '"$1" എന്ന ഫയല്‍ "$2" വിലേക്കു പകര്‍ത്താന്‍ സാധിച്ചില്ല.',
'filerenameerror'      => 'ഫയല്‍ "$1", "$2" എന്ന തലക്കെട്ടിലേയ്ക്കു മാറ്റാന്‍ സാധിച്ചില്ല.',
'filedeleteerror'      => '"$1" നീക്കം ചെയ്യാന്‍ സാധിച്ചില്ല.',
'directorycreateerror' => '"$1" എന്ന directory സൃഷ്ടിക്കാന്‍ സാധിച്ചില്ല.',
'filenotfound'         => '"$1" എന്ന ഫയല്‍ കണ്ടെത്താനായില്ല.',
'fileexistserror'      => '"$1" എന്ന ഫയലിലേക്കു എഴുതാന്‍ പറ്റിയില്ല: ഫയല്‍ നിലവിലുണ്ട്',
'unexpected'           => 'പ്രതീക്ഷിക്കാത്ത മൂല്യം: "$1"="$2".',
'formerror'            => 'പിശക്: ഫോം സമര്‍പ്പിക്കുവാന്‍ പറ്റിയില്ല',
'badarticleerror'      => 'താങ്കള്‍ ചെയ്യാനുദ്ദേശിക്കുന്നത് ഈ താളില്‍ സാദ്ധ്യമല്ല',
'cannotdelete'         => 'സൂചിപ്പിച്ച താളോ പ്രമാണമോ നീക്കം ചെയ്യാന്‍ സാധിച്ചില്ല. (അതു മറ്റാരെങ്കിലും മുമ്പേ നീക്കം ചെയ്തിട്ടുണ്ടാവാം.)',
'badtitle'             => 'അസാധുവായ തലക്കെട്ട്',
'badtitletext'         => 'നിങ്ങള്‍ ആവശ്യപ്പെട്ട തലക്കെട്ടുള്ള ഒരു താള്‍ നിലവിലില്ല. ഇതു തെറ്റായി അന്തര്‍ഭാഷാ/അന്തര്‍വിക്കി കണ്ണി ചെയ്യപ്പെട്ടതു മൂലമോ, തലക്കെട്ടില്‍ ഉപയോഗിക്കരുതാത്ത അക്ഷരരൂപങ്ങള്‍ ഉപയോഗിച്ചതു മൂലമോ സംഭവിച്ചതായിരിക്കാം.',
'perfcached'           => 'താഴെ കൊടുത്തിരിക്കുന്ന വിവരം ശേഖരിക്കപ്പെട്ടതാണ് ആയതിനാല്‍ ചിലപ്പോള്‍ നവീനമായിരിക്കില്ല.',
'perfcachedts'         => 'താഴെയുള്ള വിവരങ്ങള്‍ ശേഖരിച്ച് വെച്ചവയില്‍ പെടുന്നു, അവസാനം പുതുക്കിയത് $1-നു ആണ്‌.',
'querypage-no-updates' => 'ഈ താളിന്റെ പുതുക്കല്‍ തല്‍ക്കാലം നിര്‍ത്തി വച്ചിരിക്കുന്നു. ഇവിടുള്ള വിവരങ്ങള്‍ ഏറ്റവും പുതിയതാവണമെന്നില്ല.',
'wrong_wfQuery_params' => 'wfQuery()എന്നതിലേക്ക് തെറ്റായ പരാമീറ്ററുകള്‍<br />
നിര്‍ദ്ദേശം: $1<br />
അന്വേഷണം: $2',
'viewsource'           => 'മൂലരൂപം കാണുക',
'viewsourcefor'        => '$1നു വേണ്ടി',
'actionthrottled'      => 'പ്രവൃത്തി നടത്തിയിരിക്കുന്നു',
'actionthrottledtext'  => 'സ്പാമിനെതിരെയുള്ള മുന്‍‌കരുതല്‍ എന്ന നിലയില്‍ ഒരേ പ്രവൃത്തി കുറഞ്ഞ സമയത്തിനുള്ളില്‍ നിരവധി തവണ ആവര്‍ത്തിക്കുന്നതു പരിമിതപ്പെടുത്തിയിരിക്കുന്നു. നിങ്ങള്‍ ആ പരിധി ലംഘിച്ചിരിക്കുന്നു. കുറച്ച് മിനിറ്റുകള്‍ക്കു ശേഷം വീണ്ടും ശ്രമിക്കുക.',
'protectedpagetext'    => 'ഈ താള്‍ തിരുത്തുവാന്‍ സാധിക്കാത്ത വിധം സംരക്ഷിക്കപ്പെട്ടിട്ടുള്ളതാണ്.',
'viewsourcetext'       => 'താങ്കള്‍ക്ക് ഈ താളിന്റെ മൂലരൂപം കാണാനും പകര്‍ത്താനും സാധിക്കും:',
'protectedinterface'   => 'ഈ താള്‍ സോഫ്റ്റ്‌വെയറിന് ആവശ്യമായ പ്രതല വാക്യം നല്‍കുന്നു, ആയതിനാല്‍ ദുര്‍വിനിയോഗം തടയുവാന്‍ വേണ്ടി ബന്ധിക്കപ്പെട്ടിരിക്കുന്നു.',
'editinginterface'     => "'''മുന്നറിയിപ്പ്:''' സോഫ്റ്റ്‌വെയറില്‍ ദൃശ്യരൂപം നിലനിര്‍ത്തുന്ന താളാണു താങ്കള്‍ തിരുത്തുവാന്‍ പോകുന്നത്. ഈ താളില്‍ താങ്കള്‍ വരുത്തുന്ന മാറ്റങ്ങള്‍ ഉപയോക്താവ് വിക്കി കാണുന്ന വിധത്തെ മാറ്റിമറിച്ചേക്കാം. മീഡിയവിക്കി സന്ദേശങ്ങളുടെ പരിഭാഷകള്‍ക്ക് മീഡിയവിക്കി സന്ദേശങ്ങളുടെ പ്രാദേശികവത്കരണ സംരംഭം ആയ [http://translatewiki.net/wiki/Main_Page?setlang=ml ബീറ്റാവിക്കി] ഉപയോഗിക്കുവാന്‍ താല്പര്യപ്പെടുന്നു.",
'sqlhidden'            => '(SQL query മറച്ചിരിക്കുന്നു)',
'cascadeprotected'     => 'നിര്‍ഝരിത (cascading) ഓപ്ഷന്‍ ഉപയോഗിച്ച് തിരുത്തല്‍ നടത്തുന്നതിനു സം‌രക്ഷണം ഏര്‍പ്പെടുത്തിയിട്ടുള്ള {{PLURAL:$1|താഴെ കൊടുത്തിട്ടുള്ള താളിന്റെ|താഴെ കൊടുത്തിട്ടുള്ള താളുകളുടെ}} ഭാഗമാണ്‌ ഈ താള്‍. അതിനാല്‍ ഈ താള്‍ തിരുത്തുവാന്‍ സാധിക്കില്ല:
$2',
'namespaceprotected'   => "'''$1''' നെയിംസ്പേസിലുള്ള താളുകള്‍ തിരുത്താന്‍ താങ്കള്‍ക്ക് അനുവാദമില്ല.",
'customcssjsprotected' => 'ഇത് മറ്റൊരു ഉപയോക്താവിന്റെ ക്രമീകരണങ്ങള്‍ ഉള്‍ക്കൊള്ളുന്ന താളാണ്‌, അതിനാല്‍ താങ്കള്‍ക്ക് ഈ താള്‍ തിരുത്താന്‍ അനുവാദമില്ല.',
'ns-specialprotected'  => 'പ്രത്യേകം എന്ന നെയിംസ്പേസില്‍ വരുന്ന താളുകള്‍ തിരുത്താനാവുന്നവയല്ല.',
'titleprotected'       => "[[User:$1|$1]] എന്ന ഉപയോക്താവ് ഈ താള്‍ ഉണ്ടാക്കുന്നതു നിരോധിച്ചിരിക്കുന്നു.
''$2'' എന്നതാണു അതിനു കാണിച്ചിട്ടുള്ള കാരണം.",

# Virus scanner
'virus-badscanner'     => "തെറ്റായ ക്രമീകരണങ്ങള്‍: അപരിചിതമായ വൈറസ് തിരച്ചില്‍ ഉപാധി :  ''$1''",
'virus-scanfailed'     => 'വൈറസ് സ്കാനിങ് പരാജയപ്പെട്ടു (code $1)',
'virus-unknownscanner' => 'തിരിച്ചറിയാനാകാത്ത ആന്റിവൈറസ്:',

# Login and logout pages
'logouttext'                 => "'''താങ്കള്‍ ഇപ്പോള്‍ {{SITENAME}} സംരംഭത്തില്‍നിന്നും ലോഗൗട്ട് ചെയ്തിരിക്കുന്നു'''

അജ്ഞാതമായിരുന്നു കൊണ്ട് {{SITENAME}} സം‌രംഭം താങ്കള്‍ക്കു തുടര്‍ന്നും ഉപയോഗിക്കാവുന്നതാണ്‌. അല്ലെങ്കില്‍  [[Special:UserLogin|ലോഗിന്‍ സൗകര്യം ഉപയോഗിച്ച്]] വീണ്ടും ലോഗിന്‍ ചെയ്യാവുന്നതും ആണ്‌.

താങ്കള്‍ വെബ് ബ്രൌസറിന്റെ ക്യാഷെ ശൂന്യമാക്കിയിട്ടില്ലെങ്കില്‍ ചില താളുകളില്‍ താങ്കള്‍ ലോഗിന്‍ ചെയ്തിരിക്കുന്നതായി കാണിക്കാന്‍ സാധ്യതയുണ്ട്.",
'welcomecreation'            => '== സ്വാഗതം, $1! ==
താങ്കളുടെ അംഗത്വം സൃഷ്ടിക്കപ്പെട്ടിരിക്കുന്നു.
താങ്കളുടെ [[Special:Preferences|{{SITENAME}} ക്രമീകരണങ്ങളില്‍]] ആവശ്യമായ മാറ്റം വരുത്തൂവാന്‍ മറക്കരുതേ.',
'yourname'                   => 'ഉപയോക്തൃനാമം:',
'yourpassword'               => 'രഹസ്യവാക്ക്:',
'yourpasswordagain'          => 'രഹസ്യവാക്ക് ഒരിക്കല്‍ക്കൂടി:',
'remembermypassword'         => 'എന്റെ പ്രവേശനം ഈ കമ്പ്യൂട്ടറില്‍ ഓര്‍ത്തുവെക്കുക.',
'yourdomainname'             => 'താങ്കളുടെ ഡൊമെയിന്‍:',
'externaldberror'            => 'ഒന്നുകില്‍ ആധികാരികതാ വിവരശേഖരത്തില്‍ പ്രശ്നം ഉണ്ടായിരുന്നു അല്ലെങ്കില്‍ നവീകരിക്കുവാന്‍ താങ്കളുടെ ബാഹ്യ അംഗത്വം താങ്കളെ അനുവധിക്കുന്നില്ല.',
'login'                      => 'പ്രവേശിക്കുക',
'nav-login-createaccount'    => 'പ്രവേശിക്കുക / അംഗത്വമെടുക്കുക',
'loginprompt'                => '{{SITENAME}} സംരംഭത്തില്‍ ലോഗിന്‍ ചെയ്യാന്‍ താങ്കള്‍ കുക്കികള്‍ (Cookies) സജ്ജമാക്കിയിരിക്കണം.',
'userlogin'                  => 'പ്രവേശിക്കുക',
'logout'                     => 'ലോഗൗട്ട്',
'userlogout'                 => 'ലോഗൗട്ട്',
'notloggedin'                => 'പ്രവേശനം ചെയ്തിട്ടില്ല',
'nologin'                    => "അംഗത്വമില്ലേ? '''$1'''.",
'nologinlink'                => 'ഒരംഗത്വമെടുക്കുക',
'createaccount'              => 'അംഗത്വമെടുക്കുക',
'gotaccount'                 => "താങ്കള്‍ക്ക് അംഗത്വമുണ്ടോ? '''$1'''.",
'gotaccountlink'             => 'പ്രവേശിക്കുക',
'createaccountmail'          => 'ഇമെയില്‍ വഴി',
'badretype'                  => 'നിങ്ങള്‍ ടൈപ്പു ചെയ്ത രഹസ്യവാക്കുകള്‍ തമ്മില്‍ യോജിക്കുന്നില്ല.',
'userexists'                 => 'ഈ പേരില്‍ മറ്റൊരു ഉപയോക്തൃനാമം  നിലവിലുണ്ട്. ദയവായി മറ്റൊരു ഉപയോക്തൃനാമം തിരഞ്ഞെടുക്കുക.',
'loginerror'                 => 'പ്രവേശനം സാധിച്ചില്ല',
'createaccounterror'         => 'അംഗത്വമെടുക്കാൻ കഴിഞ്ഞില്ല:$1',
'nocookiesnew'               => 'ഉപയോക്തൃഅക്കൗണ്ട് ഉണ്ടാക്കിയിരിക്കുന്നു. പക്ഷെ താങ്കള്‍ ലോഗിന്‍ ചെയ്തിട്ടില്ല. {{SITENAME}} സംരംഭത്തില്‍ ലോഗിന്‍ ചെയ്യുവാന്‍ കുക്കികള്‍ സജ്ജമാക്കിയിരിക്കണം. താങ്കളുട്രെ കമ്പ്യൂട്ടറില്‍ നിലവില്‍ കുക്കികള്‍ ഡിസേബിള്‍ ചെയ്തിരിക്കുന്നു. അതു എനേബിള്‍ ചെയ്തു താങ്കളുടെ ഉപയോക്തൃനാമവും രഹസ്യവാക്കും ഉപയോഗിച്ച് ലോഗിന്‍ ചെയ്യൂ.',
'nocookieslogin'             => '{{SITENAME}} സംരഭത്തില്‍ ലോഗിന്‍ ചെയ്യുവാന്‍ കുക്കികള്‍ സജ്ജമാക്കിയിരിക്കണം. പക്ഷെ താങ്കള്‍ കുക്കികള്‍ സജ്ജമാക്കിയിട്ടില്ല. കുക്കികള്‍ സജ്ജമാക്കിയതിനു ശേഷം വീണ്ടും ലോഗിന്‍ ചെയ്യാന്‍ ശ്രമിക്കൂ.',
'noname'                     => 'നിങ്ങള്‍ സാധുവായ ഉപയോക്തൃനാമം സൂചിപ്പിച്ചിട്ടില്ല.',
'loginsuccesstitle'          => 'വിജയകരമായി പ്രവേശിച്ചിരിക്കുന്നു',
'loginsuccess'               => "'''{{SITENAME}} സംരംഭത്തില്‍ \"\$1\" എന്ന പേരില്‍ താങ്കള്‍ ലോഗിന്‍ ചെയ്തിരിക്കുന്നു.'''",
'nosuchuser'                 => 'ഇതുവരെ "$1" എന്ന പേരില്‍ ആരും അംഗത്വമെടുത്തിട്ടില്ല.
ദയവായി അക്ഷരപ്പിശകുകള്‍ പരിശോധിക്കുക, അല്ലെങ്കില്‍ 
പുതിയ [[Special:UserLogin/signup|അംഗത്വമെടുക്കുക]].',
'nosuchusershort'            => '"<nowiki>$1</nowiki>" എന്ന പേരില്‍ ഒരു ഉപയോക്താവ് ഇല്ല. അക്ഷരങ്ങള്‍ ഒന്നു കൂടി പരിശോധിക്കുക.',
'nouserspecified'            => 'ഉപയോക്തൃനാമം നിര്‍ബന്ധമായും ചേര്‍ക്കണം.',
'wrongpassword'              => 'താങ്കള്‍ നല്‍കിയ രഹസ്യവാക്ക് തെറ്റാണ്, വീണ്ടും ശ്രമിക്കുക.',
'wrongpasswordempty'         => 'താങ്കള്‍ രഹസ്യവാക്ക് നല്‍കിയിരുന്നില്ല. വീണ്ടും ശ്രമിക്കുക.',
'passwordtooshort'           => 'രഹസ്യവാക്കില്‍ കുറഞ്ഞതു {{PLURAL:$1|1 അക്ഷരം|$1 അക്ഷരങ്ങള്‍}} ഉണ്ടായിരിക്കണം.',
'password-name-match'        => 'താങ്കളുടെ രഹസ്യവാക്ക് ഉപയോക്തൃനാമത്തില്‍ നിന്നും വ്യത്യസ്തമായിരിക്കണം.',
'mailmypassword'             => 'പുതിയ രഹസ്യവാക്ക് ഇമെയില്‍ ചെയ്യുക',
'passwordremindertitle'      => '{{SITENAME}} സംരംഭത്തില്‍ ഉപയോഗിക്കാനുള്ള താത്ക്കാലിക രഹസ്യവാക്ക്',
'passwordremindertext'       => 'ആരോ ഒരാള്‍ (ഒരു പക്ഷേ താങ്കളായിരിക്കാം, $1 എന്ന ഐപി വിലാസത്തില്‍നിന്ന്) {{SITENAME}} ($4) സംരംഭത്തിലേക്ക് പുതിയ രഹസ്യവാക്ക് ആവശ്യപ്പെട്ടിരിക്കുന്നു. "$2" എന്ന ഉപയോക്താവിന് ആവശ്യമായ ഒരു താല്‍കാലിക രഹസ്യവാക്കായി "$3" എന്ന് സജ്ജീകരിച്ചിരിക്കുന്നു. ഇത് താങ്കളുടെ ആവശ്യമാണെങ്കില്‍, താങ്കള്‍ പ്രവേശം ചെയ്ത് പുതിയ രഹസ്യവാക്ക് സജ്ജീകരിക്കേണ്ടതാണ്. താങ്കളുടെ താത്കാലിക രഹസ്യവാക്കിന്റെ കാലാവധി {{PLURAL:$5|ഒരു ദിവസമാകുന്നു|$5 ദിവങ്ങളാകുന്നു}}.

ഈ അഭ്യര്‍ത്ഥന മറ്റാരെങ്കിലും നടത്തിയതാണെങ്കില്‍, അതല്ല പഴയ രഹസ്യവാക്ക് ഓര്‍മ്മയുണ്ടായിരിക്കുകയും അത് മാറ്റുവാന്‍ താങ്കള്‍ക്ക് താത്പര്യവുമില്ലെങ്കില്‍, ഈ സന്ദേശം അവഗണിച്ച് താങ്കളുടെ പഴയ രഹസ്യവാക്ക് തുടര്‍ന്നും ഉപയോഗിക്കാവുന്നതാണ്‌.',
'noemail'                    => '"$1" എന്ന ഉപയോക്താവ് ഇമെയില്‍ വിലാസം ക്രമീകരിച്ചിട്ടില്ല.',
'noemailcreate'              => 'താങ്കൾ സാധുവായ ഇമെയിൽ വിലാസം നൽകേണ്ടതാണ്',
'passwordsent'               => '‘$1” എന്ന അംഗത്വത്തിനായി രജിസ്റ്റര്‍ ചെയ്യപ്പെട്ടിട്ടുള്ള ഇമെയില്‍ വിലാസത്തിലേക്ക് ഒരു പുതിയ രഹസ്യവാക്ക് അയച്ചിട്ടുണ്ട്. അത് ലഭിച്ചശേഷം ദയവായി ലോഗിന്‍ ചെയ്യുക.',
'blocked-mailpassword'       => 'നിങ്ങളുടെ ഐപി വിലാസത്തെ ഈ വിക്കി തിരുത്തുന്നതില്‍ നിന്നു തടഞ്ഞിട്ടുള്ളതാണ്‌. അതിനാല്‍ രഹസ്യവാക്കു വീണ്ടെടുക്കുന്ന സജ്ജീകരണം ഉപയോഗിക്കുന്നതിനു നിങ്ങള്‍ക്കു അവകാശമില്ല.',
'eauthentsent'               => 'നിങ്ങള്‍ വിക്കിയില്‍ ക്രമീകരിച്ചിട്ടുള്ള ഇമെയില്‍ വിലാസത്തിലേക്ക് സ്ഥിരീകരണത്തിനായി ഒരു മെയില്‍ അയച്ചിട്ടുണ്ട്. ഇവിടെ നിന്ന് ആ ഇമെയില്‍ വിലാസത്തിലേക്ക് മറ്റൊരു മെയില്‍ കൂടി അയക്കുന്നതിനു മുന്‍പായി, അക്കൗണ്ട് നിങ്ങളുടേതു തന്നെ എന്നു ഉറപ്പു വരുത്തുന്നതിനായി, ഇപ്പോള്‍ അയച്ചിട്ടുള്ള മെയിലിലെ നിര്‍ദ്ദേശങ്ങള്‍ നിങ്ങള്‍ പാലിക്കേണ്ടതാണ്.',
'throttled-mailpassword'     => 'കഴിഞ്ഞ {{PLURAL:$1|$1 മണിക്കൂറിനുള്ളില്‍ |$1 മണിക്കൂറുകള്‍ക്കുള്ളില്‍}} ഒരു രഹസ്യവാക്കു ഓര്‍മ്മപ്പെടുത്താനുള്ള മെയില്‍ അയച്ചിട്ടുണ്ട്. ദുര്‍വിനിയോഗം ഒഴിവാക്കാന്‍ {{PLURAL:$1|$1 മണിക്കൂറിനുള്ളില്‍ |$1 മണിക്കൂറുകള്‍ക്കുള്ളില്‍}} ഒരു രഹസ്യവാക്കു ഓര്‍മ്മപ്പെടുത്താനുള്ള മെയില്‍ മാത്രമേ അനുവദിക്കൂ.',
'mailerror'                  => 'മെയില്‍ അയയ്ക്കുന്നതില്‍ കുഴപ്പം: $1',
'acct_creation_throttle_hit' => 'കഴിഞ്ഞ ഒരു ദിവസത്തിനുള്ളില് താങ്കളുടെ ഐ.പി. വിലാസത്തില് നിന്നുമുള്ള സ്ന്ദര്ശകര് {{PLURAL:$1|1 അംഗത്വം|$1 അംഗത്വങ്ങള്}} എടുത്തിട്ടുണ്ട്, പ്രസ്താവിത സമയത്തിനുള്ളില് എടുക്കാവുന്ന ഏറ്റവും കൂടിയ പരിധിയാണിത്.
അതിന്റെ ഫലമായി, ഈ ഐ.പി.യില് നിന്നുള്ള സന്ദര്ശകര്ക്ക് ഇപ്പോള് കൂടുതല് അംഗത്വമെടുക്കാന് കഴിയുന്നതല്ല.',
'emailauthenticated'         => 'താങ്കളുടെ ഇമെയില്‍ വിലാസം $2, $3-ന് സാധുത തെളിയിച്ചതാണ്.',
'emailnotauthenticated'      => 'താങ്കളുടെ ഇമെയില്‍ വിലാസത്തിന്റെ സാധുത ഇതുവരെ സ്ഥിരീകരിക്കപ്പെട്ടിട്ടില്ല. സാധുത തെളിയിക്കുന്നതുവരെ താഴെപ്പറയുന്നവയ്ക്കൊന്നും താങ്കള്‍ക്ക് ഇമെയില്‍ അയക്കുവാന്‍ സാദ്ധ്യമല്ല.',
'noemailprefs'               => 'ഈ ക്രമീകരണങ്ങള്‍ പ്രവര്‍ത്തിക്കുവാന്‍ സാധുവായ ഒരു ഇമെയില്‍ വിലാസം ഉള്‍പ്പെടുത്തുക.',
'emailconfirmlink'           => 'താങ്കളുടെ ഇമെയില്‍ വിലാസം സ്ഥിരീകരിക്കുക',
'invalidemailaddress'        => 'ഇമെയില്‍ വിലാസം സാധുവായ രൂപത്തില്‍ അല്ലാത്തതിനാല്‍ സ്വീകാര്യമല്ല. 
ദയവായി സാധുവായ രൂപത്തിലുള്ള ഇമെയില്‍ വിലാസം ചേര്‍ക്കുകയോ ഇമെയില്‍ വിലാസത്തിനുള്ള ഇട ഒഴിവാക്കിയിടുകയോ ചെയ്യുക.',
'accountcreated'             => 'അക്കൗണ്ട് സൃഷ്ടിച്ചിരിക്കുന്നു',
'accountcreatedtext'         => '$1 എന്ന ഉപയോക്താവിനായുള്ള അക്കൗണ്ട് സൃഷ്ടിക്കപ്പെട്ടിരിക്കുന്നു.',
'createaccount-title'        => '{{SITENAME}} സംരംഭത്തില്‍ അക്കൗണ്ട് സൃഷ്ടിക്കല്‍',
'createaccount-text'         => '{{SITENAME}} സംരംഭത്തില്‍ ($4) താങ്കളുടെ ഇമെയില്‍ വിലാസത്തില്‍ ആരോ ഒരു അക്കൗണ്ട് "$2" എന്ന ഉപയോക്തൃനാമത്തില്‍ ഉണ്ടാക്കിയിരിക്കുന്നു (രഹസ്യവാക്ക്: "$3").  നിങ്ങള്‍ ഇപ്പോള്‍ ലോഗിന്‍ ചെയ്തു രഹസ്യവാക്കു മാറ്റേണ്ടതാകുന്നു.

അക്കൗണ്ട് അബദ്ധവശാല്‍ ഉണ്ടാക്കിയതാണെങ്കില്‍ താങ്കള്‍ക്കു ഈ സന്ദേശം നിരാകരിക്കാവുന്നതാണ്‌.',
'login-throttled'            => 'താങ്കൾ നിരവധി പ്രാവശ്യം ലോഗിന്‍ ചെയ്യാന്‍ ശ്രമിച്ചിരിക്കുന്നു.
പുതിയതായി ശ്രമിക്കുന്നതിനു മുമ്പ് ദയവായി കാത്തിരിക്കുക.',
'loginlanguagelabel'         => 'ഭാഷ: $1',

# Password reset dialog
'resetpass'                 => 'രഹസ്യവാക്ക് പുനഃക്രമീകരിക്കുക',
'resetpass_announce'        => 'താങ്കള്‍ക്ക് ഇമെയില്‍ ആയി കിട്ടിയ താല്‍ക്കാലിക കോഡ് ഉപയോഗിച്ചാണ്‌ ഇപ്പോള്‍ ലോഗിന്‍ ചെയ്തിരിക്കുന്നതു്‌. ലോഗിന്‍ പ്രക്രിയ പൂര്‍ത്തിയാകുവാന്‍ പുതിയൊരു രഹസ്യവാക്ക് ഇവിടെ കൊടുക്കുക:',
'resetpass_header'          => 'അംഗത്വത്തിന്റെ രഹസ്യവാക്ക് പുനഃക്രമീകരിക്കുക',
'oldpassword'               => 'പഴയ രഹസ്യവാക്ക്:',
'newpassword'               => 'പുതിയ രഹസ്യവാക്ക്:',
'retypenew'                 => 'പുതിയ രഹസ്യവാക്ക് ഉറപ്പിക്കുക:',
'resetpass_submit'          => 'രഹസ്യവാക്ക് സജ്ജീകരിച്ചശേഷം ലോഗിന്‍ ചെയ്യുക',
'resetpass_success'         => 'താങ്കളുടെ രഹസ്യവാക്ക് വിജയകരമായി മാറ്റിയിരിക്കുന്നു! ഇപ്പോള്‍ നിങ്ങളെ സംരംഭത്തിലേക്ക് ആനയിക്കുന്നു...',
'resetpass_forbidden'       => 'രഹസ്യവാക്കുകള്‍ മാറ്റുന്നത് അനുവദിക്കുന്നില്ല',
'resetpass-no-info'         => 'ഈ താള്‍ നേരിട്ടു കാണുന്നതിന് താങ്കള്‍ ലോഗിന്‍ ചെയ്തിരിക്കണം.',
'resetpass-submit-loggedin' => 'രഹസ്യവാക്ക് മാറ്റുക',
'resetpass-wrong-oldpass'   => 'സാധുതയില്ലാത്ത താത്കാലിക അല്ലെങ്കില്‍ നിലവിലുള്ള രഹസ്യവാക്ക്.
നിലവില്‍ താങ്കള്‍ വിജയകരമായി രഹസ്യവാക്ക് മാറ്റിയിട്ടുണ്ട് അല്ലെങ്കില്‍ ഒരു പുതിയ താത്കാലിക രഹസ്യവാക്കിന് ആവശ്യപ്പെട്ടിരിക്കുന്നു.',
'resetpass-temp-password'   => 'താത്കാലിക രഹസ്യവാക്ക്:',

# Edit page toolbar
'bold_sample'     => 'കടുപ്പിച്ച എഴുത്ത്',
'bold_tip'        => 'കടുപ്പിച്ചെഴുതുവാന്‍',
'italic_sample'   => 'ചരിച്ചുള്ള എഴുത്ത്',
'italic_tip'      => 'ചെരിച്ചെഴുതുവാന്‍',
'link_sample'     => 'വിക്കികണ്ണി',
'link_tip'        => 'വിക്കികണ്ണി ചേര്‍ക്കുവാന്‍',
'extlink_sample'  => 'http://www.example.com കണ്ണി തലക്കെട്ട്',
'extlink_tip'     => 'പുറത്തേക്കുള്ള കണ്ണി (http:// എന്ന ഉപസര്‍ഗ്ഗം ചേര്‍ക്കാന്‍ ഓര്‍മ്മിക്കുക)',
'headline_sample' => 'തലക്കെട്ടിനുള്ള വാചകം ഇവിടെ ചേര്‍ക്കുക',
'headline_tip'    => 'രണ്ടാംഘട്ട തലക്കെട്ട്',
'math_sample'     => 'ഇവിടെ സൂത്രവാക്യം ചേര്‍ക്കുക',
'math_tip'        => 'ഗണിതസൂത്രവാക്യം (LaTeX)',
'nowiki_sample'   => 'വിക്കിഫോര്‍മാറ്റിങ്ങ് ഉപയോഗിക്കേണ്ടാത്ത എഴുത്ത് ഇവിടെ ചേര്‍ക്കുക',
'nowiki_tip'      => 'വിക്കിരീതി അവഗണിക്കുക',
'image_tip'       => 'ചിത്രം ചേര്‍ക്കുവാ‍ന്‍',
'media_tip'       => 'മീഡിയയിലേക്ക് വിക്കി-കണ്ണി ചേര്‍ക്കുവാന്‍',
'sig_tip'         => 'നിങ്ങളുടെ ഒപ്പ് തിരുത്തല്‍ സമയമടക്കം',
'hr_tip'          => 'തിരശ്ചീനരേഖ (മിതമായി മാത്രം ഉപയോഗിക്കുക)',

# Edit pages
'summary'                          => 'ചുരുക്കം:',
'subject'                          => 'വിഷയം/തലക്കെട്ട്:',
'minoredit'                        => 'ഇതൊരു ചെറിയ തിരുത്തലാണ്',
'watchthis'                        => 'ഈ താളിലെ മാറ്റങ്ങള്‍ ശ്രദ്ധിക്കുക',
'savearticle'                      => 'സേവ് ചെയ്യുക',
'preview'                          => 'പ്രിവ്യൂ',
'showpreview'                      => 'പ്രിവ്യു കാണുക',
'showlivepreview'                  => 'ലൈവ് പ്രിവ്യൂ',
'showdiff'                         => 'മാറ്റങ്ങള്‍ കാണിക്കുക',
'anoneditwarning'                  => "'''മുന്നറിയിപ്പ്:''' താങ്കള്‍ ലോഗിന്‍ ചെയ്തിട്ടില്ല. താങ്കളുടെ ഐപി വിലാസം താളിന്റെ തിരുത്തല്‍ ചരിത്രത്തില്‍ ചേര്‍ക്കുന്നതാണ്.",
'missingsummary'                   => "'''ഓര്‍മ്മക്കുറിപ്പ്:''' താങ്കള്‍ തിരുത്തലിന്റെ ചുരുക്കരൂപം നല്‍കിയിട്ടില്ല. ''സേവ് ചെയ്യുക'' ബട്ടണ്‍ ഒരുവട്ടം കൂടി അമര്‍ത്തിയാല്‍ താങ്കള്‍ വരുത്തിയ മാറ്റം കാത്തുസൂക്ഷിക്കുന്നതാണ്.",
'missingcommenttext'               => 'താങ്കളുടെ അഭിപ്രായം ദയവായി താഴെ രേഖപ്പെടുത്തുക.',
'missingcommentheader'             => "'''ഓര്‍മ്മക്കുറിപ്പ്:''' ഈ കുറിപ്പിന് താങ്കള്‍ വിഷയം/തലക്കെട്ട് നല്‍കിയിട്ടില്ല. ''സേവ് ചെയ്യുക'' എന്ന ബട്ടണ്‍ ഒരുവട്ടം കൂടി അമര്‍ത്തിയാല്‍ വിഷയം/തലക്കെട്ട് ഇല്ലാതെ തന്നെ കാത്തുസൂക്ഷിക്കുന്നതാവും.",
'summary-preview'                  => 'ചുരുക്കരൂപത്തിന്റെ പ്രിവ്യൂ:',
'subject-preview'                  => 'വിഷയത്തിന്റെ/തലക്കെട്ടിന്റെ പ്രിവ്യൂ:',
'blockedtitle'                     => 'ഉപയോക്താവിനെ തടഞ്ഞിരിക്കുന്നു',
'blockedtext'                      => "<big>'''നിങ്ങളുടെ ഉപയോക്തൃനാമത്തേയോ നിങ്ങള്‍ ഇപ്പോള്‍ ലോഗിന്‍ ചെയ്തിട്ടുള്ള ഐപി വിലാസത്തേയോ ഈ വിക്കി തിരുത്തുന്നതില്‍ നിന്നു തടഞ്ഞിരിക്കുന്നു'''</big>

$1 ആണ് ഈ തടയല്‍ നടത്തിയത്. ''$2'' എന്നതാണു് അതിനു രേഖപ്പെടുത്തിയിട്ടുള്ള കാരണം.

* തടയലിന്റെ തുടക്കം: $8
* തടയലിന്റെ കാലാവധി: $6
* തടയപ്പെട്ട ഉപയോക്താവ്: $7

ഈ തടയലിനെ പറ്റി ചര്‍ച്ച ചെയ്യാന്‍ നിങ്ങള്‍ക്ക് $1 നേയോ മറ്റ് [[{{MediaWiki:Grouppage-sysop}}|കാര്യനിര്‍‌വാഹകരെയോ]] സമീപിക്കാവുന്നതാണ്. [[Special:Preferences|നിങ്ങളുടെ ക്രമീകരണങ്ങളില്‍]] നിങ്ങള്‍ സാധുവായ ഇമെയില്‍ വിലാസം കൊടുത്തിട്ടുണ്ടെങ്കില്‍, അതു അയക്കുന്നതില്‍ നിന്നു നിങ്ങള്‍ തടയപ്പെട്ടിട്ടില്ലെങ്കില്‍, 'ഇദ്ദേഹത്തിന് ഇ-മെയില്‍ അയക്കൂ' എന്ന സം‌വിധാനം ഉപയോഗിച്ച് നിങ്ങള്‍ക്ക് മറ്റുപയോക്താക്കളുമായി ബന്ധപ്പെടാം. നിങ്ങളുടെ നിലവിലുള്ള ഐപി വിലാസം $3 ഉം, നിങ്ങളുടെ ബ്ലോക്ക് ഐഡി #$5 ഉം ആണ്. ഇവ രണ്ടും നിങ്ങള്‍ കാര്യനിര്‍വാഹകനെ ബന്ധപ്പെടുമ്പോള്‍ ചേര്‍ക്കുക.",
'autoblockedtext'                  => 'താങ്കളുടെ ഐ.പി. വിലാസം സ്വയം തടയപ്പെട്ടിരിക്കുന്നു, മറ്റൊരു ഉപയോക്താവ് ഉപയോഗിച്ച കാരണത്താല്‍ $1 എന്ന കാര്യനിര്‍വാഹകനാണ് തടഞ്ഞുവെച്ചത്.
ഇതിനു കാരണമായി നല്‍കിയിട്ടുള്ളത്:

:\'\'$2\'\'

* തടയല്‍ തുടങ്ങിയത്: $8
* തടയല്‍ അവസാനിക്കുന്നത്: $6
* തടയാന്‍ ഉദ്ദേശിച്ചത്: $7

ഈ തടയലിനെ കുറിച്ച് ചര്‍ച്ച ചെയ്യാന്‍ താങ്കള്‍ക്കു $1 എന്ന കാര്യനിവാഹകനേയോ മറ്റു [[{{MediaWiki:Grouppage-sysop}}|കാര്യനിര്‍‌വാഹകരെയോ]] ബന്ധപ്പെടാവുന്നതാണ്.

ശ്രദ്ധിക്കുക [[Special:Preferences|നിങ്ങളുടെ ക്രമീകരണങ്ങളില്‍]] സാധുവായ ഇമെയില്‍ വിലാസം രേഖപ്പെടുത്താതിരിക്കുകയോ, അത് ഉപയോഗിക്കുന്നതില്‍ നിന്ന് താങ്കളെ തടയുകയോ ചെയ്തിട്ടുണ്ടെങ്കില്‍ "ഇദ്ദേഹത്തിന് ഇ-മെയില്‍ അയക്കൂ" എന്ന സം‌വിധാനം പ്രവര്‍ത്തന രഹിതമായിരിക്കും.

താങ്കളുടെ നിലവിലുള്ള ഐപി വിലാസം $3 ആണ്, നിങ്ങളുടെ തടയലിന്റെ ഐഡി #$5 ആകുന്നു.
ദയവായി മുകളില്‍ കൊടുത്തിരിക്കുന്ന വിവരങ്ങളെല്ലാം താങ്കള്‍ നടത്തുന്ന അന്വേഷണങ്ങളില്‍ ഉള്‍പ്പെടുത്തുവാന്‍ ശ്രദ്ധിക്കുക.',
'blockednoreason'                  => 'കാരണമൊന്നും സൂചിപ്പിച്ചിട്ടില്ല',
'blockedoriginalsource'            => "'''$1''' എന്നതിന്റെ മൂലരൂപം താഴെക്കാണിച്ചിരിക്കുന്നു:",
'blockededitsource'                => "'''$1''' എന്ന താളില്‍ '''താങ്കള്‍ നടത്തിയ തിരുത്തലുകളുടെ''' പൂര്‍ണ്ണരൂപം താഴെക്കാണിച്ചിരിക്കുന്നു:",
'whitelistedittitle'               => 'തിരുത്താന്‍ ലോഗിന്‍ ആവശ്യമാണ്‌',
'whitelistedittext'                => 'താളുകള്‍ തിരുത്താന്‍ താങ്കള്‍ $1 ചെയ്യേണ്ടതാണ്',
'confirmedittext'                  => 'താളുകള്‍ തിരുത്തുന്നതിനു മുന്‍പ് താങ്കള്‍ താങ്കളുടെ ഇമെയില്‍ വിലാസം സ്ഥിരീകരിക്കേണ്ടതാണ്‌. ഇമെയില്‍ വിലാസം ക്രമപ്പെടുത്തി സാധുത പരിശോധിക്കാന്‍ [[Special:Preferences|എന്റെ ക്രമീകരണങ്ങള്‍]] എന്ന സം‌വിധാനം ഉപയോഗിക്കുക.',
'nosuchsectiontitle'               => 'അത്തരം ഉപവിഭാഗം നിലവിലില്ല',
'nosuchsectiontext'                => 'നിലവിലില്ലാത്ത ഒരു ഉപവിഭാഗമാണു താങ്കള്‍ തിരുത്താന്‍ ശ്രമിക്കുന്നത്. $1 എന്ന ഉപവിഭാഗം നിലവില്ലാത്തതിനാല്‍ അതു സേവ് ചെയ്യുന്നതിനു സാധിക്കുകയില്ല.',
'loginreqtitle'                    => 'ലോഗിന്‍ ചെയ്യേണ്ടതുണ്ട്',
'loginreqlink'                     => 'ലോഗിന്‍ ചെയ്യുക',
'loginreqpagetext'                 => 'മറ്റു താളുകള്‍ കാണാന്‍ താങ്കള്‍ $1 ചെയ്യേണ്ടതാണ്.',
'accmailtitle'                     => 'രഹസ്യവാക്ക് അയച്ചിരിക്കുന്നു.',
'accmailtext'                      => "[[User talk:$1|$1]] എന്ന ഉപയോക്താവിനുള്ള ക്രമരഹിതമായി നിര്‍മ്മിച്ച രഹസ്യവാക്ക് $2 എന്ന വിലാസത്തിലേക്ക് അയച്ചിട്ടുണ്ട്.

പ്രവേശം ചെയ്തതിനു ശേഷം ഈ പുതിയ അംഗത്വത്തിനുള്ള രഹസ്യവാക്ക് ''[[Special:ChangePassword|രഹസ്യവാക്ക് മാറ്റുക]]'' എന്ന താളില്‍‌വച്ച് മാറ്റാവുന്നതാണ്.",
'newarticle'                       => '(പുതിയത്)',
'newarticletext'                   => 'ഇതുവരെ നിലവിലില്ലാത്ത ഒരു താള്‍ സൃഷ്ടിക്കാനുള്ള ശ്രമത്തിലാണ് താങ്കള്‍. അതിനായി താഴെ ആവശ്യമുള്ള വിവരങ്ങള്‍ എഴുതിച്ചേര്‍ത്ത് സേവ് ചെയ്യുക (കൂടുതല്‍ വിവരങ്ങള്‍ക്ക് [[{{MediaWiki:Helppage}}|സഹായം താള്‍]] കാണുക). താങ്കളിവിടെ അബദ്ധത്തില്‍ വന്നതാണെങ്കില്‍ ബ്രൗസറിന്റെ ബാക്ക് ബട്ടണ്‍ ഞെക്കിയാല്‍ തിരിച്ചുപോകാം.',
'anontalkpagetext'                 => "----''ഇതുവരെ അംഗത്വം എടുക്കാതിരിക്കുകയോ, നിലവിലുള്ള അംഗത്വം ഉപയോഗിക്കാതിരിക്കുകയോ ചെയ്യുന്ന ഒരു അജ്ഞാത ഉപയോക്താവിന്റെ സം‌വാത്താളാണിത്.
ആയതിനാല്‍  അദ്ദേഹത്തെ തിരിച്ചറിയുവാന്‍  അക്കരൂപത്തിലുള്ള ഐപി വിലാസം ഉപയോഗിക്കേണ്ടതുണ്ട്.
ഇത്തരം ഒരു ഐപി വിലാസം പല ഉപയോക്താക്കള്‍ പങ്കുവെക്കുന്നുണ്ടാവാം.
തങ്കള്‍ അനുയോജ്യമല്ലാത്ത ഒരു സന്ദേശം ലഭിച്ച ഒരു അജ്ഞാത ഉപയോക്താണെങ്കില്‍, ഭാവിയില്‍ ഇതര ഉപയോക്താക്കളുമായി ഉണ്ടായേക്കാവുന്ന ആശയക്കുഴപ്പം ഒഴിവാക്കാന്‍ ദയവായി [[Special:UserLogin/signup|ഒരു അംഗത്വമെടുക്കുക]] അല്ലെങ്കില്‍  [[Special:UserLogin|പ്രവേശം ചെയ്യുക]].",
'noarticletext'                    => 'ഈ താളില്‍ ഇതുവരെ ഉള്ളടക്കം ആയിട്ടില്ല.
താങ്കള്‍ക്ക് മറ്റുതാളുകളില്‍ [[Special:Search/{{PAGENAME}}|ഇതേക്കുറിച്ച് അന്വേഷിക്കാവുന്നതാണ്]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} ബന്ധപ്പെട്ട രേഖകള്‍ പരിശോധിക്കുക], അല്ലെങ്കില്‍ [{{fullurl:{{FULLPAGENAME}}|action=edit}} ഈ താള്‍ തിരുത്താവുന്നതാണ്]</span>.',
'noarticletext-nopermission'       => 'ഇപ്പോള്‍ ഈ താളില്‍ എഴുത്തുകളൊന്നും ഇല്ല.
താങ്കള്‍ക്ക് മറ്റു താളുകളില്‍ [[Special:Search/{{PAGENAME}}|ഈ താളിന്റെ തലക്കെട്ടിനായി തിരയാവുന്നതാണ്‌]],
അല്ലങ്കില്‍ <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} ബന്ധപ്പെട്ട രേഖകള്‍ പരിശോധിക്കാവുന്നതാണ്‌]</span>.',
'userpage-userdoesnotexist'        => '"$1" എന്ന ഉപയോക്താവ് അംഗത്വമെടുത്തിട്ടില്ല. ഈ താള്‍ സൃഷ്ടിക്കണമോ എന്നതു പരിശോധിക്കുക.',
'userpage-userdoesnotexist-view'   => '"$1" എന്ന അം‌ഗത്വം നിലവിലില്ല.',
'clearyourcache'                   => "'''പ്രത്യേക ശ്രദ്ധയ്ക്ക്:'''

സേവ് ചെയ്ത ക്രമീകരണങ്ങള്‍ കാണാന്‍ നിങ്ങളുടെ ബ്രൗസറിന്റെ കാഷെ ക്ലിയര്‍ ചെയ്യണം.

*'''മോസില്ല/ഫയര്‍ഫോക്സ്/സഫാരി''' എന്നീ ബ്രൗസറുകളില്‍ ''Reload'' ബട്ടണ്‍ അമര്‍ത്തുമ്പോള്‍ ''Shift'' കീ അമര്‍ത്തി പിടിക്കുകയോ ''Ctrl-Shift-R''  ഒരുമിച്ച് അമര്‍ത്തുകയോ (''Cmd-Shift-R'' on Apple Mac) ചെയ്യുക;
*'''ഇന്റര്‍നെറ്റ് എക്സ്പ്ലോറര്‍ (IE):''Refresh'' ബട്ടണ്‍ അമര്‍ത്തുമ്പോള്‍ ''Ctrl'' കീ അമര്‍ത്തിപിടിക്കുക. അല്ലെങ്കില്‍ ''Ctrl-F5'' അമര്‍ത്തുക;
*'''ഓപ്പറ (Opera)''':  ''Tools→Preferences'' ഉപയോഗിച്ച് കാഷെ പൂര്‍ണ്ണമായും ക്ലിയര്‍ ചെയ്യുക;
*'''Konqueror:''': ''Reload'' ബട്ടണ്‍ അമര്‍ത്തുകയോ ''F5'' കീ അമര്‍ത്തുകയോ ചെയ്യുക.",
'usercssyoucanpreview'             => "'''വഴികാട്ടി:''' താങ്കളുടെ പുതിയ CSS സേവ് ചെയ്യുന്നതിനു മുമ്പ് 'പ്രിവ്യൂ കാണുക' എന്ന ബട്ടന്‍ ഉപയോഗിച്ച് പരിശോധിക്കുക.",
'userjsyoucanpreview'              => "'''വഴികാട്ടി:''' താങ്കളുടെ പുതിയ JS സേവ് ചെയ്യുന്നതിനു മുമ്പ് 'പ്രിവ്യൂ കാണുക' എന്ന ബട്ടന്‍ ഉപയോഗിച്ച് പരിശോധിക്കുക.",
'usercsspreview'                   => "'''താങ്കള്‍ താങ്കളുടെ സ്വന്തം CSS പ്രിവ്യൂ ചെയ്യുക മാത്രമേ ചെയ്യുന്നുള്ളൂ എന്ന കാര്യം ഓര്‍മ്മിക്കുക.'''
'''ഇതു സേവ് ചെയ്തിട്ടില്ല!'''",
'userjspreview'                    => "'''താങ്കള്‍ താങ്കളുടെ സ്വന്തം ജാവസ്ക്രിപ്റ്റ് പ്രിവ്യൂ ചെയ്യുക മാത്രമേ ചെയ്യുന്നുള്ളൂ എന്ന കാര്യം ഓര്‍മ്മിക്കുക. ഇതു സേവ് ചെയ്തിട്ടില്ല!'''",
'userinvalidcssjstitle'            => "'''മുന്നറിപ്പ്:''' \"\$1\" എന്ന പേരില്‍ ഒരു സ്കിന്‍ ഇല്ല. '''.css''' ഉം '''.js''' ഉം താളുകള്‍ ഇംഗ്ലീഷ് ചെറിയക്ഷര തലക്കെട്ട് ആണ്‌ ഉപയോഗിക്കുന്നതെന്നു ദയവായി ഓര്‍ക്കുക. ഉദാ: {{ns:user}}:Foo/Monobook.css എന്നതിനു പകരം {{ns:user}}:Foo/monobook.css എന്നാണു ഉപയോഗിക്കേണ്ടത്.",
'updated'                          => '(പുതുക്കിയിരിക്കുന്നു)',
'note'                             => "'''പ്രത്യേക ശ്രദ്ധയ്ക്ക്:'''",
'previewnote'                      => "'''ഇതൊരു പ്രിവ്യൂ മാത്രമാണ്, താങ്കള്‍ നടത്തിയ മാറ്റങ്ങള്‍ സേവ് ചെയ്തിട്ടില്ല!'''",
'previewconflict'                  => 'ഈ പ്രിവ്യൂവില്‍ മുകളിലെ ടെക്സ്റ്റ് ഏരിയയിലുള്ള എഴുത്ത് മാത്രമാണ് കാട്ടുന്നത്, സേവ്‌ ചെയ്യാന്‍ താങ്കള്‍ തീരുമാനിച്ചാല്‍ അത് സേവ് ആകുന്നതാണ്.',
'session_fail_preview'             => "'''ക്ഷമിക്കണം! സെഷന്‍ ഡാറ്റ നഷ്ടപ്പെട്ടതിനാല്‍ താങ്കളുടെ തിരുത്തലിന്റെ തുടര്‍പ്രക്രിയ നടത്തുവാന്‍ സാധിച്ചില്ല. ഒരു പ്രാവശ്യം കൂടി ദയവായി ശ്രമിക്കൂ. എന്നിട്ടും ശരിയാവുന്നില്ലെങ്കില്‍ ലോഗൗട്ട് ചെയ്തതിനു ശേഷം പിന്നേയും ലോഗിന്‍ ചെയ്യൂ.'''",
'session_fail_preview_html'        => "'''ക്ഷമിക്കണം. സെഷന്‍ ഡാറ്റ നഷ്ടപ്പെട്ടതിനാല്‍ താങ്കളുടെ തിരുത്തലിന്റെ തുടര്‍പ്രക്രിയ നടത്തുവാന്‍ സാധിച്ചില്ല.'''

''{{SITENAME}} സം‌രംഭത്തില്‍ raw HTML എനേബിള്‍ ചെയ്തിട്ടുള്ളതിനാല്‍, ജാവാസ്ക്രിപ് ആക്രമണത്തിനെതിരെ ഒരു മുന്‍‌കരുതല്‍ എന്ന നിലയില്‍ പ്രിവ്യൂ മറച്ചിരിക്കുന്നു.''

'''നിങ്ങളുടേതു നല്ല വിശ്വാസത്തിലുള്ള തിരുത്തലാണെങ്കില്‍ ഒരു പ്രാവശ്യം കൂടി പരിശ്രമിക്കൂ. എന്നിട്ടും ശരിയാവുന്നില്ലെങ്കില്‍ ലോഗൗട്ട് ചെയ്തതിനു ശേഷം പിന്നേയും ലോഗിന്‍ ചെയ്യൂ.'''",
'editing'                          => 'തിരുത്തുന്ന താള്‍:- $1',
'editingsection'                   => 'തിരുത്തുന്ന താള്‍:- $1 (ഉപവിഭാഗം)',
'editingcomment'                   => 'തിരുത്തപ്പെടുന്നത്:- $1 (പുതിയ ഉപവിഭാഗം)',
'editconflict'                     => 'എഡിറ്റ് കോണ്‍ഫ്ലിറ്റ്: $1',
'explainconflict'                  => "താങ്കള്‍ തിരുത്താന്‍ തുടങ്ങിയതിനു ശേഷം ഈ താള്‍ മറ്റാരോ തിരുത്തി സേവ് ചെയ്തിരിക്കുന്നു.
മുകളിലുള്ള ടെക്സ്റ്റ് ഏരിയയില്‍ നിലവിലുള്ള ഉള്ളടക്കം കാണിക്കുന്നു.
താങ്കള്‍ ഉള്ളടക്കത്തില്‍ വരുത്തിയ മാറ്റങ്ങള്‍ താഴെയുള്ള ടെക്സ്റ്റ് ഏരിയയില്‍ കാണിക്കുന്നു.
താങ്കളുടെ മാറ്റങ്ങള്‍ മുകളിലെ ടെക്സ്റ്റ് ഏരിയയിലേക്ക് സം‌യോജിപ്പിക്കുക.
താങ്കള്‍ '''സേവ് ചെയ്യുക''' എന്ന ബട്ടണ്‍ അമര്‍ത്തുമ്പോള്‍ '''മുകളിലെ ടെക്സ്റ്റ് ഏരിയയിലുള്ള ടെക്സ്റ്റ് മാത്രമേ''' സേവ് ആവുകയുള്ളൂ.",
'yourtext'                         => 'താങ്കള്‍ എഴുതി ചേര്‍ത്തത്',
'storedversion'                    => 'സംഭരിക്കപ്പെട്ടിരിക്കുന്ന പതിപ്പ്',
'nonunicodebrowser'                => "'''WARNING: Your browser is not unicode compliant. A workaround is in place to allow you to safely edit pages: non-ASCII characters will appear in the edit box as hexadecimal codes.'''",
'editingold'                       => "'''മുന്നറിയിപ്പ്: താങ്കള്‍ ഈ താളിന്റെ ഒരു പഴയ പതിപ്പാണ്‌ തിരുത്തുന്നത്. ഇപ്പോള്‍ താങ്കള്‍ വരുത്തിയ മാറ്റങ്ങള്‍ സേവ് ചെയ്താല്‍ ഈ പതിപ്പിനു ശേഷം വന്ന മാറ്റങ്ങളെല്ലാം നഷ്ടമാകും.'''",
'yourdiff'                         => 'വ്യത്യാസങ്ങള്‍',
'copyrightwarning'                 => "{{SITENAME}} സംരംഭത്തില്‍ എഴുതപ്പെടുന്ന ലേഖനങ്ങളെല്ലാം $2 പ്രകാരം സ്വതന്ത്രമാണ് (വിശദാംശങ്ങള്‍ക്ക് $1 കാണുക). താങ്കള്‍ എഴുതുന്ന ലേഖനം തിരുത്തപ്പെടുന്നതിലോ ഒഴിവാക്കപ്പെടുന്നതിലോ എതിര്‍പ്പുണ്ടെങ്കില്‍ ദയവായി ലേഖനമെഴുതാതിരിക്കുക.

ഈ ലേഖനം താങ്കള്‍ത്തന്നെ എഴുതിയതാണെന്നും അതല്ലെങ്കില്‍ പകര്‍പ്പവകാശനിയമങ്ങളുടെ പരിധിയിലില്ലാത്ത ഉറവിടങ്ങളില്‍നിന്ന് പകര്‍ത്തിയതാണെന്നും ഉറപ്പാക്കുക.

'''പകര്‍പ്പവകാശ സംരക്ഷണമുള്ള സൃഷ്ടികള്‍ ഒരു കാരണവശാലും ഇവിടെ പ്രസിദ്ധീകരിക്കരുത്.'''",
'copyrightwarning2'                => "{{SITENAME}} സംരംഭത്തില്‍ താങ്കള്‍ എഴുതി ചേര്‍ക്കുന്നതെല്ലാം മറ്റുപയോക്താക്കള്‍ തിരുത്തുകയോ, മാറ്റം വരുത്തുകയോ, ഒഴിവാക്കുകയോ ചെയ്തേക്കാം. താങ്കള്‍ എഴുതി ചേര്‍ക്കുന്നതു മറ്റ് ഉപയോക്താക്കള്‍ തിരുത്തുന്നതിലോ ഒഴിവാക്കുന്നതിലോ താങ്കള്‍ക്ക് എതിര്‍പ്പുണ്ടെങ്കില്‍ ദയവായി ലേഖനമെഴുതാതിരിക്കുക.
ഇതു താങ്കള്‍ത്തന്നെ എഴുതിയതാണെന്നും, അതല്ലെങ്കില്‍ പകര്‍പ്പവകാശ നിയമങ്ങളുടെ പരിധിയിലില്ലാത്ത ഉറവിടങ്ങളില്‍നിന്നും പകര്‍ത്തിയതാണെന്നും ഉറപ്പാക്കുക (കുടുതല്‍ വിവരത്തിനു $1 കാണുക).
'''പകര്‍പ്പവകാശ സംരക്ഷണമുള്ള സൃഷ്ടികള്‍ ഒരു കാരണവശാലും ഇവിടെ പ്രസിദ്ധീകരിക്കരുത്!'''",
'longpagewarning'                  => "'''മുന്നറിയിപ്പ്:''' ഈ താളിന് $1 കിലോബൈറ്റ്സ് നീളമുണ്ട്;
ചില ബ്രൗസറുകള്‍ക്ക് 32 കിലോബൈറ്റ്സില്‍ കൂടിയ വലിയ താളുകള്‍ തിരുത്തുമ്പോള്‍ പ്രശ്നമുണ്ടാകാറുണ്ട്.
താളുകളുടെ ഉപവിഭാഗങ്ങള്‍ തിരഞ്ഞെടുത്ത് തിരുത്തുന്നത് പരിഗണിക്കുക.",
'longpageerror'                    => "'''പിശക്: താങ്കള്‍ സമര്‍പ്പിച്ച ടെക്സ്റ്റിനു $1 കിലോബൈറ്റ്സ് വലിപ്പമുണ്ട്. പരമാവധി അനുവദനീയമായ വലിപ്പം $2 കിലോബൈറ്റ്സ് ആണ്‌. അതിനാലിതു സേവ് ചെയ്യാന്‍ സാദ്ധ്യമല്ല.'''",
'readonlywarning'                  => "'''മുന്നറിയിപ്പ്: വിവരശേഖരം അതിന്റെ പരിപാലനത്തിനു വേണ്ടി ബന്ധിച്ചിരിക്കുന്നു, അതുകൊണ്ട് താങ്കളിപ്പോള്‍ വരുത്തിയ മാറ്റങ്ങള്‍ സേവ് ചെയ്യാന്‍ സാദ്ധ്യമല്ല.''' താങ്കള്‍ വരുത്തിയ മാറ്റങ്ങള്‍ ഒരു ടെക്സ്റ്റ് ഫയലിലേക്ക് പകര്‍ത്തി (കട്ട് & പേസ്റ്റ്) പിന്നീടുള്ള ഉപയോഗത്തിനായി സേവ് ചെയ്യുവാന്‍ താല്പര്യപ്പെടുന്നു. വിവരശേഖരം ബന്ധിച്ച അഡ്മിനിസ്ട്രേറ്റര്‍ നല്‍കിയ വിശദീകരണം: $1",
'protectedpagewarning'             => "'''മുന്നറിയിപ്പ്:  ഈ താള്‍ സിസോപ്പ് അധികാരമുള്ളവര്‍ക്ക് മാത്രം തിരുത്താന്‍ സാധിക്കാവുന്ന തരത്തില്‍ സം‌രക്ഷിക്കപ്പെട്ടിരിക്കുന്നു '''",
'semiprotectedpagewarning'         => "'''ശ്രദ്ധിക്കുക:''' ഈ താള്‍ സംരക്ഷിക്കപ്പെട്ടിട്ടുള്ളതാണ്; {{SITENAME}} സംരംഭത്തില്‍ അംഗത്വമെടുത്തിട്ടുള്ളവര്‍ക്കേ ഈ താള്‍ തിരുത്താന്‍ സാധിക്കൂ.",
'cascadeprotectedwarning'          => "'''മുന്നറിയിപ്പ്:''' ഈ താള്‍ കാര്യനിര്‍‌വാഹക അവകാശമുള്ളവര്‍ക്കു മാത്രം തിരുത്തുവാന്‍ സാധിക്കുന്ന വിധത്തില്‍ സം‌രക്ഷിക്കപ്പെട്ടിട്ടുള്ളതാണ്‌. {{PLURAL:$1|താള്‍|താളുകള്‍}} കാസ്കേഡ് സം‌രക്ഷണം ചെയ്തപ്പോള്‍ അതിന്റെ ഭാഗമായി സംരക്ഷിക്കപ്പെട്ടിട്ടുള്ളതാണ്‌ ഈ താള്‍.",
'titleprotectedwarning'            => "'''മുന്നറിയിപ്പ്: ഈ താള്‍ സൃഷ്ടിക്കണമെങ്കില്‍ [[Special:ListGroupRights|പ്രത്യേക അവകാശമുള്ള]] ഉപയോക്താക്കള്‍ വേണ്ടിയിരിക്കുന്നു.'''",
'templatesused'                    => 'ഈ താളില്‍ ഉപയോഗിച്ചിരിക്കുന്ന ഫലകങ്ങള്‍:',
'templatesusedpreview'             => 'ഈ താളില്‍ ഇപ്പോള്‍ ഉപയോഗിച്ചിരിക്കുന്ന ഫലകങ്ങള്‍:',
'templatesusedsection'             => 'ഈ ഉപവിഭാഗത്തില്‍ ഉപയോഗിച്ചിരിക്കുന്ന ഫലകങ്ങള്‍:',
'template-protected'               => '(സം‌രക്ഷിക്കപ്പെട്ടിരിക്കുന്നു)',
'template-semiprotected'           => '(അര്‍‌ദ്ധസം‌രക്ഷിതം)',
'hiddencategories'                 => 'ഈ താള്‍ {{PLURAL:$1|മറഞ്ഞിരിക്കുന്ന ഒരു വര്‍ഗ്ഗത്തില്‍|മറഞ്ഞിരിക്കുന്ന $1 വര്‍ഗ്ഗങ്ങളില്‍‍}} അംഗമാണു്‌:',
'nocreatetitle'                    => 'താളുകള്‍ സൃഷ്ടിക്കുന്നത് പരിമിതപ്പെടുത്തിയിരിക്കുന്നു',
'nocreatetext'                     => '{{SITENAME}} സംരംഭത്തില്‍ പുതിയ താളുകള്‍ സൃഷ്ടിക്കുവാനുള്ള അവകാശം നിയന്ത്രിതമാണ്‌.
താങ്കള്‍ ദയവായി തിരിച്ചുചെന്ന് നിലവിലുള്ള ഒരു താള്‍ തിരുത്തുകയോ, അഥവാ [[Special:UserLogin|ലോഗിന്‍ ചെയ്യുകയോ ഒരു അക്കൗണ്ട് സൃഷ്ടിക്കുകയോ]] ചെയ്യാന്‍ അഭ്യര്‍ത്ഥിക്കുന്നു.',
'nocreate-loggedin'                => 'പുതിയ താളുകള്‍ സൃഷ്ടിക്കുവാനുള്ള അനുവാദം താങ്കള്‍ക്കില്ല.',
'permissionserrors'                => 'അനുമതിപ്രശ്നം',
'permissionserrorstext'            => 'താഴെ കൊടുത്തിരിക്കുന്ന {{PLURAL:$1|കാരണം|കാരണങ്ങള്‍}} കൊണ്ട് താങ്കള്‍ക്ക് ഈ പ്രവൃത്തി ചെയ്യാനുള്ള അനുമതിയില്ല:',
'permissionserrorstext-withaction' => 'താങ്കള്‍ക്ക് $2 എന്ന പ്രവൃത്തി ചെയ്യാന്‍ അനുമതി ഇല്ല, {{PLURAL:$1|കാരണം|കാരണങ്ങള്‍}} താഴെ കൊടുത്തിരിക്കുന്നു:',
'recreate-moveddeleted-warn'       => "'''മുന്നറിയിപ്പ്: മുമ്പ് മായ്ച്ചുകളഞ്ഞ താളാണ്‌ താങ്കള്‍ വീണ്ടും ചേര്‍ക്കാന്‍ ശ്രമിക്കുന്നത്'''

താങ്കള്‍ ചെയ്യുന്നത് ശരിയായ നടപടിയാണോ എന്നു പരിശോധിക്കുക. ഉറപ്പിനായി ഈ താളിന്റെ മായ്ക്കല്‍ രേഖയും മാറ്റല്‍ രേഖയും കൂടെ ചേര്‍ത്തിരിക്കുന്നു.",
'moveddeleted-notice'              => 'ഈ താള്‍ മായ്ക്കപ്പെട്ടിരിക്കുന്നു. 
ഈ താളിന്റെ മായ്ക്കല്‍ രേഖ പരിശോധനയ്ക്കായി താഴെ കൊടുത്തിരിക്കുന്നു',
'log-fulllog'                      => 'എല്ലാ രേഖകളും കാണുക',
'edit-hook-aborted'                => 'കൊളുത്ത് ഛേദിച്ച തിരുത്ത്.
ഇത് ഒരു വിശദീകരണവും നല്‍കിയിട്ടില്ല.',
'edit-gone-missing'                => 'ഈ താൾ പുതുക്കുവാൻ സാധിക്കുകയില്ല.
ഇത് മായ്ക്കപ്പെട്ടതായി കാണുന്നു.',
'edit-conflict'                    => 'തിരുത്തല്‍ കോണ്‍ഫ്ലിക്റ്റ്',
'edit-no-change'                   => 'ഇപ്പോഴുള്ള സ്ഥിതിയില്‍ നിന്നു യാതൊരു മാറ്റവും ഇല്ലാത്തതിനാല്‍ താങ്കളുടെ തിരുത്തലുകള്‍ തിരസ്കരിക്കപ്പെട്ടിരിക്കുന്നു.',
'edit-already-exists'              => 'പുതിയ താള്‍ സൃഷ്ടിക്കാന്‍ കഴിഞ്ഞില്ല.
താള്‍ ഇപ്പോള്‍ തന്നെ നിലവിലുണ്ട്.',

# Parser/template warnings
'expensive-parserfunction-warning'  => 'Warning: This page contains too many expensive parser function calls.

It should have less than $2 {{PLURAL:$2|call|calls}}, there {{PLURAL:$1|is now $1 call|are now $1 calls}}.',
'expensive-parserfunction-category' => 'വളരെയധികം വിലയേറിയ വ്യാകരണ നിര്‍ദ്ദേശങ്ങള്‍ അടങ്ങിയ താളുകള്‍',
'parser-template-loop-warning'      => 'ഫലകക്കുരുക്ക് കണ്ടെത്തിയിരിക്കുന്നു: [[$1]]',

# "Undo" feature
'undo-success' => 'ഈ തിരുത്തല്‍ താങ്കള്‍ക്ക് തിരസ്ക്കരിക്കാവുന്നതാണ്‌. താഴെ കൊടുത്തിരിക്കുന്ന പതിപ്പുകള്‍ തമ്മിലുള്ള താരതമ്യം ഒന്നുകൂടി പരിശോധിച്ച് ഈ പ്രവൃത്തി ചെയ്യണോ എന്ന് ഒന്നുകൂടി ഉറപ്പാക്കുക. ഉറപ്പാണെങ്കില്‍ തിരുത്തല്‍ തിരസ്ക്കരിക്കുവാന്‍ താള്‍ സേവ് ചെയ്യുക.',
'undo-failure' => 'ഇടയ്ക്കുള്ള തിരുത്തലുകള്‍ തമ്മിലുള്ള കോണ്‍ഫ്ലിറ്റ് കാരണം ഈ തിരുത്തല്‍ തിരസ്ക്കരിക്കുവാന്‍ പറ്റില്ല.',
'undo-norev'   => 'ഈ എഡിറ്റ് നിലവിലില്ലാത്തതിനാലോ മായ്ക്കപെട്ടതിനാലോ പൂർവസ്ഥിതിയിലാക്കുവാൻ സാധിക്കുകയില്ല.',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]) ചെയ്ത $1 എന്ന തിരുത്തല്‍ നീക്കം ചെയ്യുന്നു',

# Account creation failure
'cantcreateaccounttitle' => 'അക്കൗണ്ട് സൃഷ്ടിക്കാന്‍ സാധിച്ചില്ല',
'cantcreateaccount-text' => "ഈ ഐപി ('''$1''') വിലാസത്തില്‍ നിന്നു അക്കൗണ്ട് സൃഷ്ടിക്കുന്നത് [[User:$3|$3]] നിരോധിച്ചിരിക്കുന്നു.

$3 അതിനു കാണിച്ചിരിക്കുന്ന കാരണം ''$2'' ആണ്‌.",

# History pages
'viewpagelogs'           => 'ഈ താളുമായി ബന്ധപ്പെട്ട രേഖകള്‍ കാണുക',
'nohistory'              => 'ഈ താളിനു തിരുത്തല്‍ ചരിത്രം ആയിട്ടില്ല.',
'currentrev'             => 'ഇപ്പോഴുള്ള രൂപം',
'currentrev-asof'        => '$1 -ല്‍ നിലവില്‍ ഉള്ള രൂപം',
'revisionasof'           => '$1-നു നിലവിലുണ്ടായിരുന്ന രൂപം',
'revision-info'          => '$1-നു ഉണ്ടായിരുന്ന രൂപം സൃഷ്ടിച്ചത്:- $2',
'previousrevision'       => '←പഴയ രൂപം',
'nextrevision'           => 'പുതിയ രൂപം→',
'currentrevisionlink'    => 'ഇപ്പോഴുള്ള രൂപം',
'cur'                    => 'ഇപ്പോള്‍',
'next'                   => 'അടുത്തത്',
'last'                   => 'മുമ്പ്',
'page_first'             => 'ആദി',
'page_last'              => 'അന്ത്യം',
'histlegend'             => "വ്യത്യാസങ്ങള്‍ ഒത്തുനോക്കാന്‍: ഒത്തുനോക്കേണ്ട പതിപ്പുകള്‍ക്കൊപ്പമുള്ള റേഡിയോ ബട്ടണ്‍ തിരഞ്ഞെടുത്ത് ''\"തിരഞ്ഞെടുത്ത പതിപ്പുകള്‍ തമ്മിലുള്ള വ്യത്യാസം കാണുക\"'' എന്ന ബട്ടണ്‍ ഞെക്കുകയോ ENTER കീ അമര്‍ത്തുകയോ ചെയ്യുക.<br />

സൂചന: (ഇപ്പോള്‍) = നിലവിലുള്ള പതിപ്പുമായുള്ള വ്യത്യാസം, (മുമ്പ്) = തൊട്ടുമുന്‍പത്തെ പതിപ്പുമായുള്ള വ്യത്യാസം, (ചെ.) = ചെറിയ തിരുത്തല്‍.",
'history-fieldset-title' => 'നാള്‍‌വഴി പരിശോധന',
'histfirst'              => 'പഴയവ',
'histlast'               => 'പുതിയവ',
'historysize'            => '({{PLURAL:$1|1 ബൈറ്റ്|$1 ബൈറ്റുകള്‍}})',
'historyempty'           => '(ശൂന്യം)',

# Revision feed
'history-feed-title'          => 'നാള്‍വഴി',
'history-feed-description'    => 'വിക്കിയില്‍ ഈ താളിന്റെ നാള്‍വഴി',
'history-feed-item-nocomment' => '$1 ല്‍ $2',
'history-feed-empty'          => 'താങ്കള്‍ തിരഞ്ഞ താള്‍ നിലവിലില്ല.
പ്രസ്തുത താള്‍ വിക്കിയില്‍ നിന്നു ഒഴിവാക്കിയിരിക്കാനോ പുനര്‍നാമകരണം ചെയ്തിരിക്കാനോ സാദ്ധ്യത ഉണ്ട്.
ബന്ധപ്പെട്ട പുതിയ താളുകള്‍ കണ്ടെത്താന്‍ [[Special:Search|വിക്കിയിലെ തിരച്ചില്‍]] എന്ന താള്‍ ഉപയോഗിക്കുക.',

# Revision deletion
'rev-deleted-comment'        => '(പ്രസ്താവന ഒഴിവാക്കിയിരിക്കുന്നു)',
'rev-deleted-user'           => '(ഉപയോക്തൃനാമം ഒഴിവാക്കിയിരിക്കുന്നു)',
'rev-deleted-event'          => '(പ്രവര്‍ത്തനരേഖയില്‍ നടത്തിയ പ്രവര്‍ത്തനം ഒഴിവാക്കിയിരിക്കുന്നു)',
'rev-deleted-text-view'      => "ഈ താളിന്റെ പതിപ്പുകള്‍ '''മായ്ച്ചിരിക്കുന്നു'''.

കാര്യനിര്‍‌വാഹകന്‍ എന്ന നിലയില്‍ താങ്കാള്‍ക്ക് അവ കാണാവുന്നതാണ്;  കൂടുതല്‍ വിവരങ്ങള്‍ [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} മായ്ക്കല്‍ രേഖയില്‍] കാണാം.",
'rev-delundel'               => 'പ്രദര്‍ശിപ്പിക്കുക/മറയ്ക്കുക',
'revisiondelete'             => 'പതിപ്പുകള്‍ ഒഴിവാക്കുകയോ/പുനഃസ്ഥാപിക്കുകയോ ചെയ്യുക',
'revdelete-nooldid-title'    => 'അസാധുവായ ഉദ്ദിഷ്ടപതിപ്പ്',
'revdelete-nooldid-text'     => 'ഈ പ്രവൃത്തി ചെയ്യുവാനാവശ്യമായ ഉദ്ദിഷ്ട പതിപ്പ്/പതിപ്പുകള്‍ താങ്കള്‍ തിരഞ്ഞെടുത്തിട്ടില്ല അല്ലെങ്കില്‍ ഉദ്ദിഷ്ട പതിപ്പ് നിലവിലില്ല അതുമല്ലെങ്കില്‍ താങ്കള്‍ നിലവിലുള്ള പതിപ്പ് മറയ്ക്കുവാന്‍ ശ്രമിക്കുന്നു.',
'revdelete-nologtype-title'  => 'പ്രവര്‍ത്തനരേഖയുടെ തരം നല്‍കിയിട്ടില്ല',
'revdelete-nologtype-text'   => 'ഈ പ്രവൃത്തി ചെയ്യുവാന്‍ പ്രവര്‍ത്തനരേഖയുടെ തരം താങ്കള്‍ വ്യക്തമാക്കിയിട്ടില്ല.',
'revdelete-nologid-title'    => 'തെറ്റായ തിരുത്തല്‍ പട്ടിക',
'revdelete-no-file'          => 'നിര്‍ദ്ദേശിച്ച പ്രമാണം നിലവിലില്ല.',
'revdelete-show-file-submit' => 'അതെ',
'revdelete-selected'         => "'''[[:$1]] എന്ന താളിന്റെ {{PLURAL:$2|തിരഞ്ഞെടുത്ത പതിപ്പ്|തിരഞ്ഞെടുത്ത പതിപ്പുകള്‍}}:'''",
'revdelete-text'             => "'''മായ്ക്കപ്പെട്ട നാള്‍‌രൂപങ്ങളും സംഭവങ്ങളും താളിന്റെ നാള്‍‌വഴിയിലും രേഖകളിലും ഉണ്ടായിരിക്കും, പക്ഷേ ആ ഉള്ളടക്കം പൊതുജനത്തിനു ലഭ്യമല്ല.'''
താങ്കള്‍ മായ്ച്ച പതിപ്പുകളും പ്രവര്‍ത്തനരേഖകളും താളിന്റെ നാള്‍‌വഴിയിലും ലോഗുകളിലും തുടര്‍ന്നും ലഭ്യമാകും. പക്ഷെ ആ പതിപ്പുകളുടെ ഉള്ളടക്കം പൊതുജനത്തിനു പ്രാപ്യമല്ല.

{{SITENAME}} സം‌രംഭത്തിലെ മറ്റു കാര്യനിര്‍‌വാഹകര്‍ക്ക് ഈ മറഞ്ഞിരിക്കുന്ന ഉള്ളടക്കം പരിശോധിക്കുവാനും താങ്കള്‍ മായ്ച്ചതു തിരസ്ക്കരിക്കുവാനും സാധിക്കും.  മറ്റു കൂടുതല്‍ സം‌രക്ഷണ പരിമിതികള്‍ സജ്ജീകരിച്ചിട്ടില്ലെങ്കില്‍ ഇതേ ഇന്റര്‍ഫേസ് ഉപയോഗിച്ചു തന്നെ അത്തരത്തില്‍ പ്രവര്‍ത്തിക്കുന്നതിനു അവര്‍ക്ക് സാധിക്കും.

ഇതുകൊണ്ടുള്ള ഗുണദോഷങ്ങള്‍ താങ്കള്‍ മനസ്സിലാക്കുന്നുവെന്നും, [[{{MediaWiki:Policy-url}}|നയത്തിനനുസൃതമായി]], താങ്കളിത് ചെയ്യാനാഗ്രഹിക്കുന്നുവെന്നും ദയവായി സ്ഥിരീകരിക്കുക.",
'revdelete-legend'           => 'ദര്‍ശനത്തിനു നിയന്ത്രണങ്ങള്‍ ഏര്‍പ്പെടുത്തുക',
'revdelete-hide-text'        => 'മാറ്റം വന്ന ടെക്സ്റ്റ് മറയ്ക്കുക',
'revdelete-hide-comment'     => 'തിരുത്തലിന്റെ അഭിപ്രായം മറയ്ക്കുക',
'revdelete-hide-user'        => 'തിരുത്തുന്ന ആളുടെ ഉപയോക്തൃനാമം/ഐപി വിലാസം മറയ്ക്കുക',
'revdelete-hide-restricted'  => 'വിവരങ്ങളുടെ നിയന്ത്രണം മറ്റുള്ളവരെ പോലെ കാര്യനിര്‍വാഹകര്‍ക്കും ബാധകമാക്കുക',
'revdelete-suppress'         => 'സിസോപ്പുകളില്‍ നിന്നും മറ്റുള്ളവരില്‍ നിന്നും ഈ ഡാറ്റാ മറച്ചു വെക്കുക',
'revdelete-hide-image'       => 'ഫയലിന്റെ ഉള്ളടക്കം മറയ്ക്കുക',
'revdelete-unsuppress'       => 'പുനഃസ്ഥാപിച്ച പതിപ്പുകളിലുള്ള നിയന്ത്രണങ്ങള്‍ ഒഴിവാക്കുക',
'revdelete-log'              => 'മായ്ക്കാനുള്ള കാരണം:',
'revdelete-submit'           => 'തിരഞ്ഞെടുത്ത പതിപ്പിനു ബാധകമാക്കുക',
'revdelete-logentry'         => '[[$1]]-ന്റെ പതിപ്പുകള്‍ പ്രദര്‍ശിപ്പിക്കുന്ന വിധം തിരുത്തിയിരിക്കുന്നു',
'revdelete-success'          => "'''പതിപ്പുകള്‍ പ്രദര്‍ശിപ്പിക്കുന്ന വിധം വിജയകരമായി സജ്ജീകരിച്ചിരിക്കുന്നു.'''",
'revdel-restore'             => 'കാണുന്ന രൂപത്തില്‍ മാറ്റം വരുത്തുക',
'pagehist'                   => 'താളിന്റെ നാള്‍‌വഴി',
'deletedhist'                => 'ഒഴിവാക്കപ്പെട്ട നാള്‍‌വഴി',
'revdelete-content'          => 'ഉള്ളടക്കം',
'revdelete-summary'          => 'തിരുത്തലിന്റെ ചുരുക്കം',
'revdelete-uname'            => 'ഉപയോക്തൃനാമം',
'revdelete-hid'              => '$1 അപ്രത്യക്ഷമാക്കി',
'revdelete-unhid'            => '$1 പ്രത്യക്ഷമാക്കി',
'revdelete-reason-dropdown'  => '*മായ്ക്കാനുള്ള സാധാരണ കാരണങ്ങൾ
**പകർപ്പവകാശ ലംഘനം
**അനുയോജ്യമല്ലാത്ത വ്യക്തി വിവരങ്ങൾ
**അടിസ്ഥാനപരമായി ദോഷകരമാകുന്ന വിവരങ്ങൾ',
'revdelete-otherreason'      => 'മറ്റ്/കൂടുതൽ കാരണം:',
'revdelete-reasonotherlist'  => 'മറ്റ് കാരണം',
'revdelete-edit-reasonlist'  => 'മായ്ക്കലിന്റെ കാരണം തിരുത്തുക',

# Suppression log
'suppressionlog' => 'ഒതുക്കല്‍ രേഖ',

# History merging
'mergehistory'                     => 'താളുകളുടെ നാള്‍‌വഴികള്‍ സം‌യോജിപ്പിക്കുക',
'mergehistory-header'              => 'ഒരു താളിന്റെ പതിപ്പുകളുടെ നാള്‍‌വഴി മറ്റൊരു പുതിയ താളിലേക്കു സം‌യോജിപ്പിക്കുവാന്‍ ഈ താള്‍ നിങ്ങളെ സഹായിക്കും.
ഈ മാറ്റം താളിന്റെ പതിപ്പുകളുടെ തുടര്‍ച്ച പരിപാലിക്കുന്നതിനു സഹായിക്കും എന്നതു ഉറപ്പു വരുത്തുക.',
'mergehistory-box'                 => 'രണ്ടു താളുകളുടെ പതിപ്പുകള്‍ സം‌യോജിപ്പിക്കുക:',
'mergehistory-from'                => 'സ്രോതസ്സ് താള്‍:',
'mergehistory-into'                => 'ലക്ഷ്യതാള്‍:',
'mergehistory-list'                => 'സം‌യോജിപ്പിക്കാവുന്ന തിരുത്തല്‍ നാള്‍‌വഴി',
'mergehistory-merge'               => '[[:$1]]ന്റെ താഴെ കാണിച്ചിരിക്കുന്ന പതിപ്പുകള്‍ [[:$2]] ലേക്കു സം‌യോജിപ്പിക്കാവുന്നതാണ്‌‍. റേഡിയോ ബട്ടണ്‍ കോളം ഉപയോഗിച്ച് സം‌യോജിപ്പിക്കാനുള്ള പതിപ്പുകളുടെ സമീപത്തുള്ള സമയം തിരഞ്ഞെടുക്കുക. താങ്കള്‍ തിരഞ്ഞെടുക്കുന്ന സമയത്തോ അതിനു മുന്‍പോ സൃഷ്ടിക്കപ്പെട്ട പതിപ്പുകള്‍ തിരഞ്ഞെടുക്കുക. നാവിഗേഷണല്‍ കണ്ണികള്‍ ഉപയോഗിക്കുന്നതു ഈ കോളത്തെ പുനഃക്രമീകരിക്കും.',
'mergehistory-go'                  => 'സം‌യോജിപ്പിക്കാവുന്ന തിരുത്തലുകള്‍ കാട്ടുക',
'mergehistory-submit'              => 'പതിപ്പുകള്‍ സം‌യോജിപ്പിക്കുക',
'mergehistory-empty'               => 'സം‌യോജിപ്പിക്കാവുന്ന പതിപ്പുകളൊന്നും ഇല്ല.',
'mergehistory-success'             => '[[:$1]]-ന്റെ {{PLURAL:$3|പതിപ്പ്|പതിപ്പുകള്‍}} [[:$2]]-ലേക്കു വിജയകരമായി സം‌യോജിപ്പിച്ചിരിക്കുന്നു.',
'mergehistory-fail'                => 'താളുകളുടെ നാള്‍‌വഴി സം‌യോജനം നടത്താന്‍ സാദ്ധ്യമല്ല. താളുകളും സമയവിവരങ്ങളും ഒന്നു കൂടി പരിശോധിക്കുക.',
'mergehistory-no-source'           => 'സ്രോതസ്സ് താളായ $1 നിലവിലില്ല.',
'mergehistory-no-destination'      => 'ലക്ഷ്യ താളായ $1 നിലവിലില്ല.',
'mergehistory-invalid-source'      => 'സ്രോതസ്സ് താളിന് നിര്‍ബന്ധമായും സാധുവായ ഒരു തലക്കെട്ടുണ്ടായിരിക്കണം.',
'mergehistory-invalid-destination' => 'ലക്ഷ്യമായി നല്‍കുന്ന താളിന് നിര്‍ബന്ധമായും സാധുവായ തലക്കെട്ടുണ്ടായിരിക്കണം.',
'mergehistory-autocomment'         => '[[:$1]]നെ [[:$2]]ലേക്കു സം‌യോജിപ്പിച്ചിരിക്കുന്നു',
'mergehistory-comment'             => '[[:$1]]നെ [[:$2]]ലേക്കു സം‌യോജിപ്പിച്ചിരിക്കുന്നു: $3',
'mergehistory-same-destination'    => 'സ്രോതസ് - ലക്ഷ്യ താളുകള്‍ക്ക് ഒരേ പേര്‌ ഉണ്ടാകാന്‍ പാടില്ല',
'mergehistory-reason'              => 'കാരണം:',

# Merge log
'mergelog'           => 'താളുകള്‍ സം‌യോജിപ്പിച്ചതിന്റെ രേഖകള്‍',
'pagemerge-logentry' => '[[$1]] എന്ന താള്‍ [[$2]] എന്ന താളിലേയ്ക്ക് സംയോജിപ്പിച്ച് കൂട്ടിച്ചേര്‍ത്തു ($3 വരെയുള്ള പതിപ്പുകള്‍)',
'revertmerge'        => 'സം‌യോജനത്തെ തിരസ്ക്കരിക്കുക',
'mergelogpagetext'   => 'രണ്ടു താളുകളുടെ നാള്‍‌വഴികള്‍ തമ്മില്‍ സം‌യോജിപ്പിച്ചതിന്റെ പ്രവര്‍ത്തനരേഖകളുടെ ഏറ്റവും പുതിയ പട്ടിക താഴെ കാണാം.',

# Diffs
'history-title'           => '"$1" എന്ന താളിന്റെ നാള്‍‌വഴി',
'difference'              => '(തിരഞ്ഞെടുത്ത പതിപ്പുകള്‍ തമ്മിലുള്ള വ്യത്യാസം)',
'lineno'                  => 'വരി $1:',
'compareselectedversions' => 'തിരഞ്ഞെടുത്ത പതിപ്പുകള്‍ തമ്മിലുള്ള വ്യത്യാസം കാണുക',
'visualcomparison'        => 'ദൃഷ്ടിഗോചര തുലനം',
'wikicodecomparison'      => 'വിക്കിവാചക തുലനം',
'editundo'                => 'മാറ്റം തിരസ്ക്കരിക്കുക',
'diff-multi'              => '(ഇടക്കുള്ള {{PLURAL:$1|ഒരു പതിപ്പിലെ മാറ്റം|$1 പതിപ്പുകളിലെ മാറ്റങ്ങള്‍}} ഇവിടെ കാണിക്കുന്നില്ല.)',
'diff-movedto'            => '$1 ലേക്ക് നീക്കപെട്ടിരിക്കുന്നു',
'diff-styleadded'         => '$1 എന്ന ശൈലി ചേര്‍ത്തിരിക്കുന്നു',
'diff-added'              => '$1 ചേര്‍ത്തു',
'diff-changedto'          => '$1 ലേക്ക് മാറ്റിയിരിക്കുന്നു',
'diff-movedoutof'         => '$1-ല്‍ നിന്നും നീക്കിയിരിക്കുന്നു',
'diff-styleremoved'       => '$1 എന്ന ശൈലി നീക്കിയിരിക്കുന്നു',
'diff-removed'            => '$1 നീക്കം ചെയ്തിരിക്കുന്നു',
'diff-changedfrom'        => '$1 ല്‍ നിന്നും മാറ്റിയിരിക്കുന്നു',
'diff-src'                => 'ഉറവിടം',
'diff-withdestination'    => '$1 എന്ന ലക്ഷ്യത്തോടെ',
'diff-with'               => '&#32;ന്റെ കൂടെ $1 $2',
'diff-with-final'         => '&#32;ഉം $1 $2',
'diff-width'              => 'വീതി',
'diff-height'             => 'നീളം',
'diff-p'                  => "ഒരു '''ഖണ്ഡിക'''",
'diff-blockquote'         => "ഒരു '''ഉദ്ധരണി'''",
'diff-h1'                 => "ഒരു '''തലക്കെട്ട് (നില 1)'''",
'diff-h2'                 => "ഒരു '''തലക്കെട്ട് (നില 2)'''",
'diff-h3'                 => "ഒരു '''തലക്കെട്ട് (നില 3)'''",
'diff-h4'                 => "ഒരു '''തലക്കെട്ട് (നില 4)'''",
'diff-h5'                 => "ഒരു '''തലക്കെട്ട് (നില 5)'''",
'diff-table'              => "ഒരു '''പട്ടിക'''",
'diff-tr'                 => "ഒരു '''വരി'''",
'diff-td'                 => "ഒരു '''നിര'''",
'diff-th'                 => "ഒരു '''തലക്കെട്ട്'''",
'diff-dd'                 => "ഒരു '''നിര്‍വചനം'''",
'diff-img'                => "ഒരു '''ചിത്രം'''",
'diff-a'                  => "ഒരു '''കണ്ണി'''",
'diff-i'                  => "'''ചെരിച്ച്'''",
'diff-b'                  => "'''കടുപ്പത്തില്‍'''",
'diff-strong'             => "'''ദൃഢം'''",
'diff-font'               => "'''ഫോണ്ട്'''",
'diff-big'                => "'''വലുത്'''",
'diff-del'                => "'''മായ്ച്ചത്'''",
'diff-tt'                 => "'''ഉറച്ച വീതി'''",
'diff-strike'             => "'''വെട്ടുക'''",

# Search results
'searchresults'                    => 'തിരച്ചിലിന്റെ ഫലം',
'searchresults-title'              => '"$1" എന്നു തിരഞ്ഞതിനു ലഭ്യമായ ഫലങ്ങള്‍',
'searchresulttext'                 => '{{SITENAME}} സംരംഭത്തില്‍ വിവരങ്ങള്‍ എങ്ങിനെ അന്വേഷിച്ചു കണ്ടെത്താമെന്നറിയാന്‍, [[{{MediaWiki:Helppage}}|{{int:help}}]] എന്ന താള്‍ കാണുക.',
'searchsubtitle'                   => 'താങ്കള്‍ അന്വേഷിച്ച വാക്ക് \'\'\'[[:$1]]\'\'\' ആണ്‌. ([[Special:Prefixindex/$1|"$1" എന്ന വാക്കില്‍ തുടങ്ങുന്ന എല്ലാ താളുകളും]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1"എന്ന വാക്കിലേക്ക് കണ്ണി ചേര്‍ത്തിരിക്കുന്ന എല്ലാ താളുകളും]])',
'searchsubtitleinvalid'            => "താങ്കള്‍ തിരഞ്ഞത് '''$1'''",
'noexactmatch'                     => "'''\"[[\$1]]\" എന്ന താള്‍ ഈ വിക്കിയില്‍ നിലവിലില്ല.''' താങ്കള്‍ക്ക് [[:\$1|പ്രസ്തുത ശീര്‍ഷകത്തോടു കൂടിയ ഒരു താള്‍]] തുടങ്ങാവുന്നതാണ്.",
'noexactmatch-nocreate'            => "'''\"\$1\" എന്ന താള്‍ നിലവിലില്ല.'''",
'toomanymatches'                   => 'യോജിച്ച ഫലങ്ങള്‍ വളരെയധികം കിട്ടിയിരിക്കുന്നു; ദയവായി വേറൊരു അന്വേഷണ വാക്ക് ഉപയോഗിച്ച് തിരയുക.',
'titlematches'                     => 'താളിന്റെ തലക്കെട്ടുമായി യോജിക്കുന്ന ഫലങ്ങള്‍',
'notitlematches'                   => 'ഒരു താളിന്റെയും തലക്കെട്ടുമായി യോജിക്കുന്നില്ല',
'textmatches'                      => 'താങ്കള്‍ തിരഞ്ഞ വാക്കുകള്‍ ഉള്ള താളുകള്‍',
'notextmatches'                    => 'താളുകളുടെ ഉള്ളടക്കത്തില്‍ നിങ്ങള്‍ തിരഞ്ഞ വാക്കുമായി യോജിക്കുന്ന ഫലങ്ങള്‍ ഒന്നും തന്നെയില്ല',
'prevn'                            => 'മുമ്പത്തെ {{PLURAL:$1|$1}}',
'nextn'                            => 'അടുത്ത {{PLURAL:$1|$1}}',
'prevn-title'                      => 'മുന്‍പത്തെ $1 {{PLURAL:$1|ഫലം|ഫലങ്ങള്‍}}',
'nextn-title'                      => 'അടുത്ത $1 {{PLURAL:$1|ഫലം|ഫലങ്ങള്‍}}',
'shown-title'                      => '$1 {{PLURAL:$1|ഫലം|ഫലങ്ങള്‍}} വീതം താളില്‍ കാണിക്കുക',
'viewprevnext'                     => '($1 {{int:pipe-separator}} $2 {{int:pipe-separator}} $3 മാറ്റങ്ങള്‍ കാണുക)',
'searchmenu-legend'                => 'തിരച്ചില്‍ ഉപാധികള്‍',
'searchmenu-exists'                => "'''\"[[:\$1]]\" എന്ന പേരില്‍ ഒരു താള്‍ ഈ വിക്കിയില്‍ നിലവിലുണ്ട്'''",
'searchmenu-new'                   => "'''ഈ വിക്കിയില്‍ \"[[:\$1]]\" താള്‍ നിര്‍മ്മിക്കുക!'''",
'searchhelp-url'                   => 'Help:ഉള്ളടക്കം',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|ഈ പൂര്‍‌വ്വപദങ്ങളുള്ള താളുകള്‍ ബ്രൗസ് ചെയ്യുക]]',
'searchprofile-articles'           => 'ലേഖനങ്ങളില്‍',
'searchprofile-project'            => 'സഹായം, പദ്ധതി താളുകളില്‍',
'searchprofile-images'             => 'പ്രമാണങ്ങളില്‍',
'searchprofile-everything'         => 'എല്ലാം',
'searchprofile-advanced'           => 'വിപുലമായ',
'searchprofile-articles-tooltip'   => '$1-ല്‍ തിരയുക',
'searchprofile-project-tooltip'    => '$1-ല്‍ തിരയുക',
'searchprofile-images-tooltip'     => 'പ്രമാണങ്ങള്‍ക്ക് വേണ്ടി തിരയുക',
'searchprofile-everything-tooltip' => 'എല്ലാ ഉള്ളടക്കവും തിരയുക (സംവാദത്താളുകള്‍ ഉള്‍പ്പെടെ)',
'searchprofile-advanced-tooltip'   => 'തിരഞ്ഞെടുത്ത നാമമേഖലകളില്‍ തിരച്ചില്‍ നടത്തുവാന്‍',
'search-result-size'               => '$1 ({{PLURAL:$2|ഒരു വാക്ക്|$2 വാക്കുകള്‍}})',
'search-result-score'              => 'സാംഗത്യം: $1%',
'search-redirect'                  => '(തിരിച്ചുവിടല്‍ താള്‍ $1)',
'search-section'                   => '(വിഭാഗം $1)',
'search-suggest'                   => '$1 എന്നാണോ താങ്കള്‍ ഉദ്ദേശിച്ചത്',
'search-interwiki-caption'         => 'സഹോദര സംരംഭങ്ങള്‍',
'search-interwiki-default'         => '$1 ഫലങ്ങള്‍:',
'search-interwiki-more'            => '(കൂടുതല്‍)',
'search-mwsuggest-enabled'         => 'നിര്‍ദ്ദേശങ്ങള്‍ വേണം',
'search-mwsuggest-disabled'        => 'നിര്‍ദ്ദേശങ്ങള്‍ വേണ്ട',
'search-relatedarticle'            => 'ബന്ധപ്പെട്ടവ',
'mwsuggest-disable'                => 'അജാക്സ് നിര്‍ദ്ദേശങ്ങള്‍ വേണ്ട',
'searcheverything-enable'          => 'എല്ലാ നാമമേഖലകളും തിരയുക',
'searchrelated'                    => 'ബന്ധപ്പെട്ടവ',
'searchall'                        => 'എല്ലാം',
'showingresults'                   => "താഴെ #'''$2''' മുതലുള്ള {{PLURAL:$1|'''1''' ഫലം|'''$1''' ഫലങ്ങള്‍}} കാട്ടുന്നു",
'showingresultsnum'                => "താഴെ #'''$2''' കൊണ്ടു തുടങ്ങുന്ന {{PLURAL:$3|'''1''' ഫലം|'''$3''' ഫലങ്ങള്‍}} കാണിച്ചിരിക്കുന്നു.",
'showingresultsheader'             => "'''$4''' എന്ന പദത്തിനു ലഭിച്ച {{PLURAL:$5|ആകെ'''$3''' എണ്ണത്തില്‍ '''$1''' ഫലം |ആകെ '''$3''' എണ്ണത്തില്‍ '''$1 - $2''' ഫലങ്ങള്‍}}",
'nonefound'                        => "'''ശ്രദ്ധിക്കുക''': ചില നാമമേഖലകള്‍ മാത്രമേ സ്വതവേ തിരയാറുള്ളൂ. എല്ലാ വിവരങ്ങളിലും തിരയാന്‍ '''തിരയേണ്ട നാമമേഖലകള്‍''' ''എല്ലാം'' എന്നതോ ആവശ്യമായ നാമമേഖലമാത്രം തിരയുവാന്‍ (സംവാദം, ഫലകം, തുടങ്ങിയവ) അതു മാത്രമായോ ടിക്ക് ചെയ്യേണ്ടതാണ്.",
'search-nonefound'                 => 'താങ്കളുടെ തിരച്ചിലിനനുയോജ്യമായ ഫലങ്ങള്‍ ഒന്നും ലഭ്യമല്ല.',
'powersearch'                      => 'തിരയൂ',
'powersearch-legend'               => 'വികസിതമായ തിരച്ചില്‍',
'powersearch-ns'                   => 'തിരയേണ്ട നാമമേഖലകള്‍',
'powersearch-redir'                => 'തിരിച്ചുവിടലുകള്‍ കാണിക്കുക',
'powersearch-field'                => 'ഇതിനു വേണ്ടി തിരയുക',
'powersearch-togglelabel'          => 'അടയാളപ്പെടുത്തുക:',
'powersearch-toggleall'            => 'എല്ലാം',
'powersearch-togglenone'           => 'ഒന്നുംവേണ്ട',
'search-external'                  => 'ബാഹ്യ അന്വേഷണം',
'searchdisabled'                   => '{{SITENAME}} സം‌രംഭത്തില്‍ തിരച്ചില്‍ ദുര്‍ബലപ്പെടുത്തിയിരിക്കുന്നു. നിങ്ങള്‍ക്ക് ഗൂഗിള്‍ ഉപയോഗിച്ച് തലക്കാലം തിരച്ചില്‍ നടത്താവുന്നതാണ്‌. പക്ഷെ ഗൂഗിളില്‍ {{SITENAME}} സം‌രംഭത്തിന്റെ ഇന്റക്സ് കാലഹരണപ്പെട്ടതായിരിക്കാന്‍ സാദ്ധ്യതയുണ്ട്.',

# Quickbar
'qbsettings-none'       => 'ഒന്നുമില്ല',
'qbsettings-fixedleft'  => 'സ്ഥിരമായ ഇടത്',
'qbsettings-fixedright' => 'സ്ഥിരമായ വലത്',

# Preferences page
'preferences'                   => 'ക്രമീകരണങ്ങള്‍',
'mypreferences'                 => 'എന്റെ ക്രമീകരണങ്ങള്‍',
'prefs-edits'                   => 'ആകെ തിരുത്തലുകള്‍:',
'prefsnologin'                  => 'ലോഗിന്‍ ചെയ്തിട്ടില്ല',
'prefsnologintext'              => 'ഉപയോക്തൃക്രമീകരണങ്ങള്‍ മാറ്റാന്‍ താങ്കള്‍ <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} ലോഗിന്‍]</span> ചെയ്തിരിക്കണം.',
'changepassword'                => 'രഹസ്യവാക്ക് മാറ്റുക',
'prefs-skin'                    => 'രൂപം',
'skin-preview'                  => 'പ്രിവ്യൂ',
'prefs-math'                    => 'സമവാക്യം',
'datedefault'                   => 'ക്രമീകരണങ്ങള്‍ വേണ്ട',
'prefs-datetime'                => 'ദിവസവും സമയവും',
'prefs-personal'                => 'അഹം',
'prefs-rc'                      => 'പുതിയ മാറ്റങ്ങള്‍',
'prefs-watchlist'               => 'ശ്രദ്ധിക്കുന്നവ',
'prefs-watchlist-days'          => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയില്‍ പ്രദര്‍ശിപ്പിക്കേണ്ട പരമാവധി ദിവസങ്ങള്‍:',
'prefs-watchlist-days-max'      => '(അങ്ങേയറ്റം 7 ദിവസം)',
'prefs-watchlist-edits'         => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയുടെ വികസിത രൂപത്തില്‍ പ്രദര്‍ശിപ്പിക്കേണ്ട പരമാവധി മാറ്റങ്ങള്‍:',
'prefs-watchlist-edits-max'     => '(അങ്ങേയറ്റം 1000 തിരുത്തലുകള്‍)',
'prefs-watchlist-token'         => 'ശ്രദ്ധിക്കുന്നവയുടെ പട്ടികയ്ക്കുള്ള അടയാളപദം:',
'prefs-misc'                    => 'പലവക',
'prefs-resetpass'               => 'രഹസ്യവാക്ക് മാറ്റുക',
'prefs-email'                   => 'ഇമെയില്‍ ക്രമീകരണങ്ങള്‍',
'prefs-rendering'               => 'ദൃശ്യരൂപം',
'saveprefs'                     => 'സേവ് ചെയ്യുക',
'resetprefs'                    => 'സേവ് ചെയ്തിട്ടില്ലാത്ത മാറ്റങ്ങള്‍ പുനഃക്രമീകരിക്കുക',
'restoreprefs'                  => 'സ്വതവേയുള്ള ക്രമീകരണങ്ങള്‍ പുനഃസ്ഥാപിക്കുക',
'prefs-editing'                 => 'തിരുത്തല്‍',
'prefs-edit-boxsize'            => 'തിരുത്തല്‍ ജാലകത്തിന്റെ വലിപ്പം',
'rows'                          => 'വരി:',
'columns'                       => 'നിര:',
'searchresultshead'             => 'തിരയൂ',
'resultsperpage'                => 'ഒരു താളിലുള്ള ശരാശരി സന്ദര്‍ശനം:',
'contextlines'                  => 'ഓരോ സന്ദര്‍ശനത്തിലും ചേര്‍ക്കപ്പെട്ട വരികള്‍:',
'contextchars'                  => 'ഓരോ വരിയുടേയും പ്രസക്തി:',
'recentchangesdays'             => 'പുതിയ മാറ്റങ്ങളില്‍ കാണിക്കേണ്ട ദിവസങ്ങളുടെ എണ്ണം:',
'recentchangesdays-max'         => 'അങ്ങേയറ്റം {{PLURAL:$1|ഒരു ദിവസം|$1 ദിവസങ്ങള്‍}}',
'recentchangescount'            => 'സ്വതവേ പ്രദര്‍ശിപ്പിക്കേണ്ട തിരുത്തലുകളുടെ എണ്ണം:',
'prefs-help-recentchangescount' => 'ഇത് പുതിയമാറ്റങ്ങള്‍, താളിന്റെ നാള്‍‌വഴികള്‍, രേഖകള്‍ എന്നിവയെ ഉള്‍ക്കൊള്ളുന്നു.',
'prefs-help-watchlist-token'    => 'ഈ പെട്ടിയിൽ ഒരു രഹസ്യവാക്ക് ഉപയോഗിച്ചാൽ താങ്കൾ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയ്ക്കുള്ള ആർ.എസ്.എസ്. ഫീഡ് ഉണ്ടാക്കുന്നതാണ്.
ഈ രഹസ്യവാക്ക് അറിയാവുന്ന ആർക്കും താങ്കൾ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക വായിക്കാവുന്നതാണ്. അതുകൊണ്ട് സുരക്ഷിതമായ ഒന്നു തിരഞ്ഞെടുക്കുക.
ഇവിടെ താങ്കൾക്കുപയോഗിക്കാവുന്ന ക്രമരഹിതമായി സൃഷ്ടിച്ച ഒരെണ്ണം കൊടുത്തിരിക്കുന്നു: $1',
'savedprefs'                    => 'താങ്കളുടെ ക്രമീകരണങ്ങള്‍ കാത്തുസൂക്ഷിച്ചിരിക്കുന്നു.',
'timezonelegend'                => 'സമയ മേഖല:',
'localtime'                     => 'പ്രാദേശിക സമയം:',
'timezoneuseserverdefault'      => 'സെര്‍‌വറില്‍ സ്വതവേയുള്ളത് ഉപയോഗിക്കുക',
'timezoneuseoffset'             => 'മറ്റുള്ളത് (എന്താണെന്നു നല്‍കുക)',
'timezoneoffset'                => 'വ്യത്യാസം¹:',
'servertime'                    => 'സെര്‍വര്‍ സമയം:',
'guesstimezone'                 => 'സമയവ്യത്യാസം ബ്രൗസറില്‍ നിന്നും ശേഖരിക്കൂ',
'timezoneregion-africa'         => 'ആഫ്രിക്ക',
'timezoneregion-america'        => 'അമേരിക്ക',
'timezoneregion-antarctica'     => 'അന്റാര്‍ട്ടിക്ക',
'timezoneregion-arctic'         => 'ആര്‍ട്ടിക്',
'timezoneregion-asia'           => 'ഏഷ്യ',
'timezoneregion-atlantic'       => 'അറ്റ്ലാന്റിക് സമുദ്രം',
'timezoneregion-australia'      => 'ഓസ്ട്രേലിയ',
'timezoneregion-europe'         => 'യൂറോപ്പ്',
'timezoneregion-indian'         => 'ഇന്ത്യന്‍ മഹാസമുദ്രം',
'timezoneregion-pacific'        => 'ശാന്തസമുദ്രം',
'allowemail'                    => 'എനിക്ക് എഴുത്തയക്കാന്‍ മറ്റുള്ളവരെ അനുവദിക്കുക',
'prefs-searchoptions'           => 'തിരച്ചില്‍ ക്രമീകരണങ്ങള്‍',
'prefs-namespaces'              => 'നാമമേഖലകള്‍',
'defaultns'                     => 'അല്ലെങ്കില്‍ ഈ നാമമേഖലകളില്‍ തിരയുക:',
'default'                       => 'സ്വതവെ',
'prefs-files'                   => 'ഫയലുകള്‍',
'prefs-custom-css'              => 'സ്വന്തം സി.എസ്.എസ്.',
'prefs-custom-js'               => 'സ്വന്തം ജെ.എസ്.',
'prefs-reset-intro'             => 'സൈറ്റിൽ സ്വതവേയുണ്ടാവേണ്ട ക്രമീകരണങ്ങൾ പുനഃക്രമീകരിക്കാൻ താങ്കൾക്ക് ഈ താൾ ഉപയോഗിക്കാവുന്നതാണ്.
ഇത് തിരിച്ചു ചെയ്യാൻ സാദ്ധ്യമല്ല.',
'prefs-emailconfirm-label'      => 'ഇമെയില്‍ സ്ഥിരീകരണം:',
'prefs-textboxsize'             => 'തിരുത്താനുള്ള ജാലകത്തിന്റെ വലിപ്പം',
'youremail'                     => 'ഇമെയില്‍:',
'username'                      => 'ഉപയോക്തൃനാമം:',
'uid'                           => 'ഉപയോക്തൃഐ.ഡി:',
'prefs-memberingroups'          => 'അംഗത്വമുള്ള {{PLURAL:$1|സംഘം|സംഘങ്ങള്‍}}:',
'prefs-registration'            => 'അംഗത്വം എടുത്തത്:',
'yourrealname'                  => 'യഥാര്‍ത്ഥ പേര്‌:',
'yourlanguage'                  => 'ഭാഷ:',
'yourvariant'                   => 'വ്യത്യാസമാനം',
'yournick'                      => 'ഒപ്പ്:',
'prefs-help-signature'          => 'സംവാദം താളിലെ കുറിപ്പുകളിൽ "<nowiki>~~~~</nowiki>" ഉപയോഗിച്ച് ഒപ്പിടേണ്ടതാണ്, അത് സ്വയം താങ്കളുടെ സമയമുദ്രയോടുകൂടിയ ഒപ്പായി മാറിക്കൊള്ളും.',
'badsig'                        => 'അനുവദനീയമല്ലാത്ത രൂപത്തിലുള്ള ഒപ്പ്. HTML ടാഗുകള്‍ പരിശോധിക്കുക.',
'badsiglength'                  => 'താങ്കളുടെ ഒപ്പിനു നീളം കൂടുതലാണ്‌. 
അതിലെ {{PLURAL:$1|അക്ഷരത്തിന്റെ|അക്ഷരങ്ങങ്ങളുടെ}} എണ്ണം $1 ല്‍ താഴെയായിരിക്കണം.',
'yourgender'                    => 'ആണ്‍/പെണ്‍:',
'gender-unknown'                => 'വ്യക്തമാക്കിയിട്ടില്ല',
'gender-male'                   => 'പുരുഷന്‍',
'gender-female'                 => 'സ്ത്രീ',
'prefs-help-gender'             => 'നിര്‍ബന്ധമില്ല: സോഫ്റ്റ്‌വെയര്‍ ഉപയോഗിച്ച് സ്ത്രീകളേയും പുരുഷന്മാരേയും ശരിയായി സംബോധന ചെയ്യാന്‍ ഉപയോഗിക്കുന്നു.
ഈ വിവരം പരസ്യമായി ലഭ്യമായിരിക്കുന്നതാണ്‌.',
'email'                         => 'ഇമെയില്‍',
'prefs-help-realname'           => 'നിങ്ങളുടെ യഥാര്‍ത്ഥ പേര്‌ നല്‍കണമെന്നു നിര്‍ബന്ധമില്ല. പക്ഷെ അങ്ങനെ ചെയ്താല്‍ വിക്കിയിലെ നിങ്ങളുടെ സംഭാവനകള്‍ ആ പേരില്‍ അംഗീകരിക്കപ്പെടും.',
'prefs-help-email'              => 'ഇമെയില്‍ വിലാസം നല്‍കണമെന്ന് നിര്‍ബന്ധമില്ല, പക്ഷേ താങ്കള്‍ രഹസ്യവാക്ക് മറന്നാല്‍ പുതിയത് അയച്ചു തരാന്‍ ഇതുകൊണ്ട് സാധിക്കുന്നതാണ്‌.
താങ്കള്‍ക്കായുള്ള താളില്‍ നിന്നോ, താങ്കള്‍ക്കുള്ള സന്ദേശങ്ങളുടെ താളില്‍ നിന്നോ മറ്റുപയോക്താക്കള്‍ക്ക് താങ്കളുടെ വ്യക്തിത്വം മനസ്സിലാക്കാതെ തന്നെ താങ്കള്‍ക്ക് സന്ദേശങ്ങളയയ്ക്കാനും ഈ സം‌വിധാനം അവസരം നല്‍കുന്നു.',
'prefs-help-email-required'     => 'ഇമെയില്‍ വിലാസം ആവശ്യമാണ്‌.',
'prefs-info'                    => 'അടിസ്ഥാന വിവരങ്ങള്‍',
'prefs-i18n'                    => 'ആഗോളീകരണം',
'prefs-signature'               => 'ഒപ്പ്',
'prefs-dateformat'              => 'ദിന ലേഖന രീതി',
'prefs-timeoffset'              => 'സമയ വ്യത്യാസം',
'prefs-advancedediting'         => 'വിപുലമായ ഉപാധികള്‍',
'prefs-advancedrc'              => 'വിപുലമായ ഉപാധികള്‍',
'prefs-advancedrendering'       => 'വിപുലമായ ഉപാധികള്‍',
'prefs-advancedsearchoptions'   => 'വിപുലമായ ഉപാധികള്‍',
'prefs-advancedwatchlist'       => 'വിപുലമായ ഉപാധികള്‍',
'prefs-display'                 => 'പ്രദര്‍ശന ഐച്ഛീകങ്ങള്‍',
'prefs-diffs'                   => 'വ്യത്യാസങ്ങള്‍',

# User rights
'userrights'                  => 'ഉപയോക്തൃഅവകാശ പരിപാലനം',
'userrights-lookup-user'      => 'ഉപയോക്തൃഗ്രൂപ്പുകളെ പരിപാലിക്കുക',
'userrights-user-editname'    => 'ഒരു ഉപയോക്തൃനാമം ടൈപ്പു ചെയ്യുക:',
'editusergroup'               => 'ഉപയോക്തൃഗ്രൂപ്പുകള്‍ തിരുത്തുക',
'editinguser'                 => "'''[[User:$1|$1]]''' ന്റെ ഉപയോക്തൃ അവകാശങ്ങള്‍ തിരുത്തുന്നു ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'ഉപയോക്തൃസമൂഹത്തിലെ അംഗത്വം തിരുത്തുക',
'saveusergroups'              => 'ഉപയോക്തൃഗ്രൂപ്പുകള്‍ സേവ് ചെയ്യുക',
'userrights-groupsmember'     => 'അംഗത്വമുള്ളത്:',
'userrights-groups-help'      => 'ഈ ഉപയോക്താവ് ഉൾപ്പെട്ടിട്ടുള്ള സംഘങ്ങൾ താങ്കൾക്ക് മാറ്റാവുന്നതാണ്:
*ഉപയോക്താവ് ആ സംഘത്തിലുണ്ടെന്ന് ശരിയിട്ട ചതുരം അർത്ഥമാക്കുന്നു.
*ഉപയോക്താവ് ആ സംഘത്തിലില്ലെന്ന് ശരിയിടാത്ത ചതുരം അർത്ഥമാക്കുന്നു.
*ഒരു * ഒരിക്കൽ സംഘം കൂട്ടിച്ചേർത്താൻ പിന്നീട് അത് നീക്കാൻ താങ്കൾക്ക് കഴിയില്ലന്നോ, അല്ലങ്കിൽ തിരിച്ചോ അർത്ഥമാക്കുന്നു.',
'userrights-reason'           => 'മാറ്റത്തിനുള്ള കാരണം:',
'userrights-no-interwiki'     => 'മറ്റ് വിക്കികളിലെ ഉപയോക്തൃ അവകാശങ്ങള്‍ തിരുത്തുവാന്‍ നിങ്ങള്‍ക്കു അനുമതിയില്ല.',
'userrights-nodatabase'       => '$1 എന്ന ഡാറ്റാബേസ് നിലവിലില്ല അല്ലെങ്കില്‍ പ്രാദേശികമല്ല.',
'userrights-nologin'          => 'ഉപയോക്താക്കള്‍ക്ക് അവകാശങ്ങള്‍ കൊടുക്കണമെങ്കില്‍ നിങ്ങള്‍ കാര്യനിര്‍‌വാഹക അക്കൗണ്ട് ഉപയോഗിച്ച് [[Special:UserLogin|ലോഗിന്‍]] ചെയ്തിരിക്കണം.',
'userrights-notallowed'       => 'ഉപയോക്താക്കള്‍ക്ക് അവകാശങ്ങള്‍ കൊടുക്കാനുള്ള അനുമതി നിങ്ങളുടെ അക്കൗണ്ടിനില്ല.',
'userrights-changeable-col'   => 'നിങ്ങള്‍ക്ക് മാറ്റാവുന്ന ഗ്രൂപ്പുകള്‍',
'userrights-unchangeable-col' => 'നിങ്ങള്‍ക്ക് മാറ്റാനാവാത്ത ഗ്രൂപ്പുകള്‍',

# Groups
'group'               => 'ഗ്രൂപ്പ്:',
'group-user'          => 'ഉപയോക്താക്കള്‍',
'group-autoconfirmed' => 'യാന്ത്രികമായി സ്ഥിരീകരിക്കപ്പെട്ട ഉപയോക്താക്കള്‍',
'group-bot'           => 'യന്ത്രങ്ങള്‍',
'group-sysop'         => 'സിസോപ്പുകള്‍',
'group-bureaucrat'    => 'ബ്യൂറോക്രാറ്റുകള്‍',
'group-suppress'      => 'മേല്‍നോട്ടങ്ങള്‍',
'group-all'           => '(എല്ലാം)',

'group-user-member'          => 'ഉപയോക്താവ്',
'group-autoconfirmed-member' => 'യാന്ത്രികമായി സ്ഥിരീകരിക്കപ്പെട്ട ഉപയോക്താവ്',
'group-bot-member'           => 'യന്ത്രം',
'group-sysop-member'         => 'സിസോപ്പ്',
'group-bureaucrat-member'    => 'ബ്യൂറോക്രാറ്റ്',
'group-suppress-member'      => 'മേല്‍നോട്ടം',

'grouppage-user'          => '{{ns:project}}:ഉപയോക്താക്കള്‍',
'grouppage-autoconfirmed' => '{{ns:project}}:യാന്ത്രികമായി സ്ഥിരീകരിക്കപ്പെട്ട ഉപയോക്താക്കള്‍',
'grouppage-bot'           => '{{ns:project}}:യന്ത്രങ്ങള്‍',
'grouppage-sysop'         => '{{ns:project}}:കാര്യനിര്‍‌വാഹകര്‍',
'grouppage-bureaucrat'    => '{{ns:project}}:ബ്യൂറോക്രാറ്റ്',
'grouppage-suppress'      => '{{ns:project}}:മേല്‍നോട്ടം',

# Rights
'right-read'                 => '
താളുകള്‍ വായിക്കുക',
'right-edit'                 => 'താളുകള്‍ തിരുത്തുക',
'right-createpage'           => 'താളുകൾ സൃഷ്ടിക്കുക (സംവാദം താളുകൾ അല്ലാത്തവ)',
'right-createtalk'           => 'സംവാദ താളുകള്‍ സൃഷ്ടിക്കുക',
'right-createaccount'        => 'പുതിയ ഉപയോക്തൃ അംഗത്വങ്ങള്‍ സൃഷ്ടിക്കുക',
'right-minoredit'            => 'ചെറിയ തിരുത്തലായി രേഖപ്പെടുത്തുക',
'right-move'                 => 'താളുകള്‍ നീക്കുക',
'right-move-subpages'        => 'താളുകള്‍ അവയുടെ ഉപതാളുകളോടുകൂടീ നീക്കുക',
'right-movefile'             => 'പ്രമാണങ്ങള്‍ നീക്കുക',
'right-upload'               => 'പ്രമാണങ്ങള്‍ അപ്‌ലോഡ് ചെയ്യുക',
'right-autoconfirmed'        => 'അര്‍ദ്ധസംരക്ഷിത താളുകള്‍ തിരുത്തുക',
'right-bot'                  => 'യാന്ത്രിക പ്രവൃത്തിയായി കണക്കാക്കപ്പെടുന്നു',
'right-delete'               => 'താളുകള്‍ മായ്ക്കുക',
'right-bigdelete'            => 'വലിയ നാള്‍വഴിയുള്ള താളുകള്‍ മായ്ക്കുക',
'right-browsearchive'        => 'നീക്കം ചെയ്യപ്പെട്ട താളുകളില്‍ തിരയുക',
'right-undelete'             => 'താള്‍ പുനഃസ്ഥാപിക്കുക',
'right-block'                => 'മറ്റുള്ള ഉപയോക്താക്കളെ മാറ്റിയെഴുതുന്നതില്‍നിന്നും വിലക്കുക',
'right-blockemail'           => 'ഉപയോക്താവിനെ ഇ-മെയില്‍ അയക്കുന്നതില്‍ നിന്നും വിലക്കുക',
'right-hideuser'             => 'ഒരു ഉപയോക്തനാമത്തെ തടയുക, പരസ്യമായി കാണപ്പെടുന്നതില്‍ നിന്നും മറയ്ക്കുന്നു',
'right-editusercssjs'        => 'മറ്റ് ഉപയോക്താക്കളുടെ CSS, JS പ്രമാണങ്ങൾ തിരുത്തുക',
'right-editusercss'          => 'മറ്റ് ഉപയോക്താക്കളുടെ CSS പ്രമാണങ്ങൾ തിരുത്തുക',
'right-edituserjs'           => 'മറ്റ് ഉപയോക്താക്കളുടെ JS പ്രമാണങ്ങൾ തിരുത്തുക',
'right-import'               => 'മറ്റുള്ള വിക്കികളില്‍ നിന്നും താളുകള്‍ ഇറക്കുമതി ചെയ്യുക',
'right-unwatchedpages'       => 'ശ്രദ്ധിക്കാത്ത താളുകളുടെ പട്ടിക കാണുക',
'right-userrights'           => 'എല്ലാ ഉപയോക്തൃ അവകാശങ്ങളും തിരുത്തുക',
'right-userrights-interwiki' => 'മറ്റുള്ള വിക്കികളില്‍ ഉപയോക്താക്കളുടെ അവകാശങ്ങള്‍ തിരുത്തുക',
'right-reset-passwords'      => 'മറ്റ് ഉപയോക്താക്കളുടെ രഹസ്യവാക്കുകൾ റീസെറ്റ് ചെയ്യുക',

# User rights log
'rightslog'      => 'ഉപയോക്താവിന്റെ അവകാശ ലോഗ്',
'rightslogtext'  => 'ഉപയോക്തൃ അവകാശങ്ങള്‍ക്കുണ്ടായ മാറ്റങ്ങള്‍ കാണിക്കുന്ന ഒരു ലോഗാണിത്.',
'rightslogentry' => '$1ന്റെ ഗ്രൂപ്പ് അംഗത്വം $2ല്‍ നിന്നു $3ലേക്കു മാറ്റിയിരിക്കുന്നു',
'rightsnone'     => '(ഒന്നുമില്ല)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'ഈ താൾ വായിക്കുക',
'action-edit'                 => 'ഈ താള്‍ തിരുത്തുക',
'action-createpage'           => 'താളുകള്‍ നിര്‍മിക്കുക',
'action-createtalk'           => 'സംവാദ താളുകള്‍ നിര്‍മിക്കുക',
'action-createaccount'        => 'ഈ ഉപയോക്തൃനാമം സൃഷ്ടിക്കുക',
'action-minoredit'            => 'ഈ തിരുത്തല്‍ ഒരു ചെറിയ തിരുത്തലായി അടയാളപ്പെടുത്തുക',
'action-move'                 => 'ഈ താള്‍ മാറ്റുക',
'action-move-subpages'        => 'ഈ താളും ഇതിന്റെ ഉപതാളുകളും നീക്കുക',
'action-move-rootuserpages'   => 'ഉപയോക്താവിന്റെ അടിസ്ഥാന താള്‍ മാറ്റുക',
'action-movefile'             => 'ഈ പ്രമാണം നീക്കുക',
'action-upload'               => 'ഈ പ്രമാണം അപ്‌ലോഡ് ചെയ്യുക',
'action-reupload'             => 'നിലവിലുള്ള പ്രമാണത്തിന്റെ ഉപരിയായി സ്ഥാപിക്കുക',
'action-reupload-shared'      => 'പങ്കു വെയ്ക്കപ്പെട്ടുപയോഗിക്കുന്ന ശേഖരണസ്ഥാനത്തുനിന്നുമുള്ള ഈ പ്രമാണത്തിന്റെ ഉപരിയായി സ്ഥാപിക്കുക',
'action-upload_by_url'        => 'ഈ പ്രമാണം ഒരു യൂ.ആര്‍.എല്ലില്‍ നിന്നും അപ്‌ലോഡ് ചെയ്യുക',
'action-delete'               => 'ഈ താള്‍ മായ്ക്കുക',
'action-deletedhistory'       => 'ഈ താളിന്റെ മായ്ക്കപ്പെട്ട ചരിത്രം കാണുക',
'action-browsearchive'        => 'മായ്ക്കപ്പെട്ട താളുകള്‍ അന്വേഷിക്കുക',
'action-undelete'             => 'ഈ താള്‍ പുനഃസ്ഥാപിക്കുക',
'action-suppressrevision'     => 'മറച്ചിരിക്കുന്ന ഈ നാള്‍‌രൂപം പുനഃപരിശോധിക്കുക അല്ലങ്കില്‍ പുനഃസ്ഥാപിക്കുക',
'action-suppressionlog'       => 'ഈ സ്വകാര്യ രേഖ കാണുക',
'action-block'                => 'ഈ ഉപയോക്താവിനെ തിരുത്തുന്നതില്‍ നിന്നും തടയുക',
'action-protect'              => 'ഈ താളിന്റെ സം‌രക്ഷണ മാനത്തില്‍ വ്യത്യാസം വരുത്തുക',
'action-import'               => 'ഈ താള്‍ മറ്റൊരു വിക്കിയീല്‍ നിന്നും ഇറക്കുമതി ചെയ്യുക',
'action-importupload'         => 'അപ്‌ലോഡ് ചെയ്ത പ്രമാണത്തില്‍ ഇന്നും ഈ താള്‍ ഇറക്കുമതി ചെയ്യുക',
'action-patrol'               => 'മറ്റുള്ളവരുടെ തിരുത്തല്‍ റോന്തുചുറ്റിയതായി അടയാളപ്പെടുത്തുക',
'action-autopatrol'           => 'താങ്കളുടെ തിരുത്തലില്‍ റോന്തുചുറ്റിയതായി അടയാളപ്പെടുത്തി',
'action-unwatchedpages'       => 'ശ്രദ്ധിക്കാത്ത താളുകളുടെ പട്ടിക കാട്ടുക',
'action-mergehistory'         => 'ഈ താളിന്റെ നാള്‍‌വഴി ലയിപ്പിക്കുക',
'action-userrights'           => 'എല്ലാ ഉപയോക്തൃ അവകാശങ്ങളും തിരുത്തുക',
'action-userrights-interwiki' => 'മറ്റു വിക്കികളില്‍ നിന്നുള്ള ഉപയോക്താക്കളുടെ ഉപയോക്തൃ അവകാശങ്ങള്‍ തിരുത്തുക',
'action-siteadmin'            => 'ഡേറ്റാബേസ് തുറക്കുക അല്ലങ്കില്‍ പൂട്ടുക',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|മാറ്റം|മാറ്റങ്ങള്‍}}',
'recentchanges'                     => 'പുതിയ മാറ്റങ്ങള്‍',
'recentchanges-legend'              => 'പുതിയമാറ്റങ്ങളുടെ ക്രമീകരണം',
'recentchangestext'                 => '{{SITENAME}} സംരംഭത്തിലെ ഏറ്റവും പുതിയ മാറ്റങ്ങള്‍ ഇവിടെ കാണാം.',
'recentchanges-feed-description'    => 'ഈ ഫീഡ് ഉപയോഗിച്ച് വിക്കിയിലെ പുതിയ മാറ്റങ്ങള്‍ നിരീക്ഷിക്കുക.',
'recentchanges-label-legend'        => 'സൂചന: $1',
'recentchanges-legend-newpage'      => '$1 - പുതിയ താള്‍',
'recentchanges-label-newpage'       => 'ഈ തിരുത്ത് ഒരു പുതിയ താള്‍ സൃഷ്ടിച്ചിരിക്കുന്നു',
'recentchanges-legend-minor'        => '$1 - ചെറിയ തിരുത്ത്',
'recentchanges-label-minor'         => 'ഇതൊരു ചെറിയ തിരുത്തലാണ്',
'recentchanges-legend-bot'          => '$1 - യാന്ത്രിക തിരുത്ത്',
'recentchanges-label-bot'           => 'ഒരു യന്ത്രം നടത്തിയ തിരുത്താണിത്',
'recentchanges-legend-unpatrolled'  => '$1 - റോന്തുചുറ്റപ്പെടാത്ത തിരുത്ത്',
'recentchanges-label-unpatrolled'   => 'ഇതുവരെ റോന്തു ചുറ്റപ്പെടാത്ത ഒരു തിരുത്താണിത്',
'rcnote'                            => "കഴിഞ്ഞ {{PLURAL:$2|ദിവസം|'''$2''' ദിവസങ്ങള്‍ക്കുള്ളില്‍}} സംഭവിച്ച, {{PLURAL:$1|'''1''' തിരുത്തല്‍|'''$1''' തിരുത്തലുകള്‍}} താഴെക്കാണാം. ശേഖരിച്ച സമയം: $4, $5.",
'rcnotefrom'                        => '<b>$2</b> മുതലുള്ള മാറ്റങ്ങള്‍ (<b>$1</b> എണ്ണം വരെ കാണാം).',
'rclistfrom'                        => '$1 മുതലുള്ള മാറ്റങ്ങള്‍ കാട്ടുക',
'rcshowhideminor'                   => 'ചെറുതിരുത്തലുകളെ $1',
'rcshowhidebots'                    => 'ബോട്ടുകളെ $1',
'rcshowhideliu'                     => 'ലോഗിന്‍ ചെയ്തിട്ടുള്ളവരെ $1',
'rcshowhideanons'                   => 'അജ്ഞാത ഉപയോക്താക്കളെ $1',
'rcshowhidepatr'                    => '$1 പരിശോധിച്ചു സ്ഥിരീകരിച്ച തിരുത്തലുകള്‍',
'rcshowhidemine'                    => 'എന്റെ തിരുത്തലുകള്‍ $1',
'rclinks'                           => 'കഴിഞ്ഞ $2 ദിവസങ്ങള്‍ക്കുള്ളിലുണ്ടായ $1 മാറ്റങ്ങള്‍ കാട്ടുക<br />$3',
'diff'                              => 'മാറ്റം',
'hist'                              => 'നാള്‍‌വഴി',
'hide'                              => 'മറയ്ക്കുക',
'show'                              => 'പ്രദര്‍ശിപ്പിക്കുക',
'minoreditletter'                   => '(ചെ.)',
'newpageletter'                     => '(പു.)',
'boteditletter'                     => '(യ.)',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|ഉപയോക്താവ്|ഉപയോക്താക്കള്‍}} ഈ താള്‍ ശ്രദ്ധിക്കുന്നുണ്ട്.]',
'rc_categories'                     => 'വിഭാഗങ്ങള്‍ക്കുള്ള പരിമിതി ("|" ഉപയോഗിച്ച് പിരിക്കുക)',
'rc_categories_any'                 => 'ഏതും',
'newsectionsummary'                 => '/* $1 */ പുതിയ ഉപവിഭാഗം',
'rc-enhanced-expand'                => 'അധികവിവരങ്ങള്‍ കാട്ടുക (ജാവാസ്ക്രിപ്റ്റ് സജ്ജമായിരിക്കണം)',
'rc-enhanced-hide'                  => 'അധികവിവരങ്ങള്‍ മറയ്ക്കുക',

# Recent changes linked
'recentchangeslinked'          => 'അനുബന്ധ മാറ്റങ്ങള്‍',
'recentchangeslinked-feed'     => 'അനുബന്ധ മാറ്റങ്ങള്‍',
'recentchangeslinked-toolbox'  => 'അനുബന്ധ മാറ്റങ്ങള്‍',
'recentchangeslinked-title'    => '$1 എന്ന താളുമായി ബന്ധപ്പെട്ട മാറ്റങ്ങള്‍',
'recentchangeslinked-noresult' => 'ഈ താളിലേയ്ക്ക് കണ്ണികളുള്ള മറ്റ് താളുകള്‍ക്ക് ഇവിടെ സൂചിപ്പിക്കപ്പെട്ട സമയത്ത് മാറ്റങ്ങളൊന്നും സം‌ഭവിച്ചിട്ടില്ല.',
'recentchangeslinked-summary'  => "ഒരു പ്രത്യേക താളില്‍ നിന്നു കണ്ണി ചേര്‍ക്കപ്പെട്ടിട്ടുള്ള താളുകളില്‍ അവസാനമായി വരുത്തിയ മാറ്റങ്ങളുടെ പട്ടിക താഴെ പ്രദര്‍ശിപ്പിച്ചിരിക്കുന്നു. ഈ പട്ടികയില്‍ പെടുന്ന [[Special:Watchlist|നിങ്ങള്‍ ശ്രദ്ധിക്കുന്ന താളുകള്‍]] '''കടുപ്പിച്ച്''' കാണിച്ചിരിക്കുന്നു.",
'recentchangeslinked-page'     => 'താളിന്റെ പേര്:',
'recentchangeslinked-to'       => 'തന്നിരിക്കുന്ന താളിലെ മാറ്റങ്ങള്‍ക്കു പകരം ബന്ധപ്പെട്ട താളുകളിലെ മാറ്റങ്ങള്‍ കാണിക്കുക',

# Upload
'upload'                      => 'അപ്‌ലോഡ്‌',
'uploadbtn'                   => 'പ്രമാണം അപ്‌ലോഡ് ചെയ്യുക',
'reupload'                    => 'ഒന്നുകൂടി അപ്‌ലോഡ് ചെയ്യുക',
'reuploaddesc'                => 'വീണ്ടും അപ്‌ലോഡ് ചെയ്ത് നോക്കാനായി തിരിച്ചു പോവുക.',
'uploadnologin'               => 'ലോഗിന്‍ ചെയ്തിട്ടില്ല',
'uploadnologintext'           => 'പ്രമാണങ്ങള്‍ അപ്‌ലോഡ് ചെയ്യാന്‍ താങ്കള്‍ [[Special:UserLogin|ലോഗിന്‍]] ചെയ്തിരിക്കണം',
'upload_directory_missing'    => 'അപ്‌‌ലോഡ് ഡയറക്ടറി ($1) ലഭ്യമല്ല, അത് സൃഷ്ടിക്കാൻ വെബ്‌‌സെർവറിനു സാധിക്കില്ല.',
'upload_directory_read_only'  => 'വെബ്ബ് സെര്‍‌വറിനു അപ്‌ലോഡ് ഡയറക്ടറിയിലേക്ക് ($1) എഴുതാന്‍ കഴിഞ്ഞില്ല.',
'uploaderror'                 => 'അപ്‌ലോഡ് പിശക്',
'uploadtext'                  => "താഴെ കാണുന്ന ഫോം പ്രമാണങ്ങൾ അപ്‌ലോഡ് ചെയ്യുവാന്‍ വേണ്ടി ഉപയോഗിക്കുക.
നിലവില്‍ അപ്‌ലോഡ് ചെയ്തിരിക്കുന്ന പ്രമാണങ്ങള്‍ കാണുവാന്‍ [[Special:FileList|അപ്‌ലോഡ് ചെയ്ത പ്രമാണങ്ങളുടെ പട്ടിക]] സന്ദര്‍ശിക്കുക. (പുതുക്കിയ) അപ്‌‌ലോഡുകൾ [[Special:Log/upload|അപ്‌ലോഡ് രേഖ]], മായ്ക്കപ്പെട്ടവ [[Special:Log/delete|മായ്ക്കൽ രേഖയിലും]] കാണാവുന്നതാണ്‌.

പ്രമാണം താളില്‍ പ്രദര്‍ശിപ്പിക്കുവാന്‍ താഴെ കാണുന്ന ഒരു വഴി സ്വീകരിക്കുക

*'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki>''' പൂർണ്ണരൂപത്തിലുള്ള പ്രമാണം ഉപയോഗിക്കാന്‍

* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></tt>''' 200 പിക്സൽ ഉള്ള പെട്ടിയിൽ പകരമുള്ള എഴുത്തടക്കം ഉപയോഗിക്കാന്‍ 
*'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>''' പ്രമാണം കാട്ടാതെ പ്രമാണത്തെ നേരിട്ടു കണ്ണി ചേര്‍ക്കാന്‍",
'upload-permitted'            => 'അനുവദനീയമായ ഫയല്‍ തരങ്ങള്‍: $1.',
'upload-preferred'            => 'പ്രോത്സാഹിപ്പിക്കുന്ന ഫയല്‍ തരങ്ങള്‍: $1.',
'upload-prohibited'           => 'നിരോധിക്കപ്പെട്ട തരം ഫയലുകള്‍: $1.',
'uploadlog'                   => 'അപ്‌ലോഡ് പ്രവര്‍ത്തനരേഖ',
'uploadlogpage'               => 'അപ്‌ലോഡ് പട്ടിക',
'uploadlogpagetext'           => 'സമീപകാലത്ത് അപ്‌ലോഡ് ചെയ്ത പ്രമാണങ്ങളുടെ പട്ടിക താഴെ കാണാം.',
'filename'                    => 'പ്രമാണത്തിന്റെ പേര്',
'filedesc'                    => 'ചുരുക്കം',
'fileuploadsummary'           => 'ചുരുക്കം:',
'filereuploadsummary'         => 'പ്രമാണത്തിലെ മാറ്റങ്ങൾ:',
'filestatus'                  => 'പകര്‍പ്പവകാശത്തിന്റെ സ്ഥിതി:',
'filesource'                  => 'സ്രോതസ്സ്:',
'uploadedfiles'               => 'അപ്‌ലോഡ് ചെയ്ത ഫയലുകള്‍',
'ignorewarning'               => 'മുന്നറിയിപ്പ് അവഗണിച്ച് പ്രമാണം കാത്തു സൂക്ഷിക്കുക',
'ignorewarnings'              => 'അറിയിപ്പുകള്‍ അവഗണിക്കുക',
'minlength1'                  => 'പ്രമാണത്തിന്റെ പേരില്‍ ഒരക്ഷരമെങ്കിലും ഉണ്ടാവണം.',
'illegalfilename'             => 'പ്രമാണത്തിന്റെ "$1" എന്ന പേരില്‍, താളിന്റെ തലക്കെട്ടില്‍ അനുവദനീയമല്ലാത്ത ചിഹ്നങ്ങള്‍ ഉണ്ട്. ദയവായി പ്രമാണം പുനര്‍നാമകരണം നടത്തി വീണ്ടും അപ്‌ലോഡ് ചെയ്യുവാന്‍ ശ്രമിക്കുക.',
'badfilename'                 => 'പ്രമാണത്തിന്റെ പേര് "$1" എന്നാക്കി മാറ്റിയിരിക്കുന്നു.',
'filetype-badmime'            => '"$1" എന്ന MIME type-ല്‍ ഉള്ള പ്രമാണങ്ങള്‍ അപ്‌ലോഡ് ചെയ്യുന്നത് അനുവദനീയമല്ല.',
'filetype-unwanted-type'      => '\'\'\'".$1"\'\'\' ഉപയോഗയോഗ്യമല്ലാത്ത ഒരു ഫയല്‍ തരം ആണ്‌. $2 അഭിലഷണീയമായ {{PLURAL:$3|ഫയല്‍ തരം|ഫയല്‍ തരങ്ങള്‍}} ഇവയാണ് : $2.',
'filetype-banned-type'        => '\'\'\'".$1"\'\'\' അനുവദനീയമല്ലാത്ത ഒരു ഫയല്‍ തരം ആണ്‌. $2 ആണ്‌ അഭിലക്ഷണീയമായ  {{PLURAL:$3|ഫയല്‍ തരം|ഫയല്‍ തരങ്ങള്‍}} $2 ആണ്.',
'filetype-missing'            => 'പ്രമാണത്തിനു ഫയല്‍ എക്സ്റ്റന്‍ഷന്‍ (ഉദാ: ".jpg") ഇല്ല.',
'large-file'                  => 'പ്രമാണങ്ങളുടെ വലിപ്പം $1-ല്‍ കൂടരുതെന്നാണ്‌ നിഷ്ക്കര്‍ഷിക്കപ്പെട്ടിരിക്കുന്നത്. ഈ പ്രമാണത്തിന്റെ വലിപ്പം $2 ആണ്‌.',
'largefileserver'             => 'സെര്‍‌വറില്‍ ചിട്ടപ്പെടുത്തിയതുപ്രകാരം ഈ പ്രമാണത്തിന്റെ വലിപ്പം അനുവദനീയമായതിലും കൂടുതലാണ്‌.',
'emptyfile'                   => 'താങ്കള്‍ അപ്‌ലോഡ് ചെയ്ത പ്രമാണം ശൂന്യമാണെന്നു കാണുന്നു. പ്രമാണത്തിന്റെ പേരിലുള്ള അക്ഷരത്തെറ്റായിരിക്കാം ഇതിനു കാരണം. ഈ പ്രമാണം അപ്‌ലോഡ് ചെയ്യണമോ എന്നതു ഒരിക്കല്‍ കൂടി പരിശോധിക്കുക.',
'fileexists'                  => "ഇതേ പേരില്‍ വേറെ ഒരു പ്രമാണം നിലവിലുണ്ട്.
ദയവായി '''<tt>[[:$1]]</tt>''' പരിശോധിച്ച് പ്രസ്തുത പ്രമാണം മാറ്റണമോ എന്നു തീരുമാനിക്കുക.
[[$1|thumb]]",
'filepageexists'              => "ഈ പ്രമാണത്തിനുള്ള വിവരണതാൾ '''<tt>[[:$1]]</tt>''' എന്നു സൃഷ്ടിക്കപ്പെട്ടിട്ടുണ്ട്, പക്ഷേ ഇതേ പേരിൽ പ്രമാണം ഒന്നും നിലവിലില്ല.
വിവരണതാളിൽ താങ്കൾ ഇവിടെ ചേർക്കുന്ന ലഘുകുറിപ്പ് പ്രത്യക്ഷപ്പെടുന്നതല്ല.
അവിടെ ലഘുകുറിപ്പ് വരാൻ ആ താൾ താങ്കൾ സ്വയം തിരുത്തേണ്ടതാണ്.
[[$1|ലഘുചിത്രം]]",
'fileexists-extension'        => "ഇതേ പേരില്‍ മറ്റൊരു പ്രമാണം നിലവിലുണ്ട്: [[$2|ലഘുചിത്രം]]
* ഇപ്പോള്‍ അപ്‌ലോഡ് ചെയ്ത പ്രമാണത്തിന്റെ പേര്‌: '''<tt>[[:$1]]</tt>'''
* നിലവിലുള്ള പ്രമാണത്തിന്റെ പേര്‌: '''<tt>[[:$2]]</tt>'''
വേറെ ഒരു പേരു തിരഞ്ഞെടുക്കുക.",
'fileexists-thumbnail-yes'    => "ഈ ചിത്രം വലിപ്പം കുറച്ച ഒന്നാണെന്നു ''(നഖചിത്രം)'' കാണുന്നു.
[[$1|ലഘുചിത്രം]]
ദയവായി '''<tt>[[:$1]]</tt>''' എന്ന ചിത്രം പരിശോധിക്കുക. 
[[:$1]] എന്ന ചിത്രവും ഈ ചിത്രവും ഒന്നാണെങ്കില്‍ ലഘുചിത്രത്തിനു വേണ്ടി മാത്രമായി ചിത്രം അപ്‌ലോഡ് ചെയ്യേണ്ടതില്ല.",
'file-thumbnail-no'           => "പ്രമാണത്തിന്റെ പേര്‌  '''<tt>$1</tt>''' എന്നാണ്‌ തുടങ്ങുന്നത്.
ഇതു വലിപ്പം കുറച്ച ഒരു ചിത്രം ''(ലഘുചിത്രം)'' ആണെന്നു കാണുന്നു.
പൂര്‍ണ്ണ റെസലൂഷന്‍ ഉള്ള ചിത്രം ഉണ്ടെങ്കില്‍ അതു അപ്‌ലോഡ് ചെയ്യുവാന്‍ താല്പര്യപ്പെടുന്നു, അല്ലെങ്കില്‍ പ്രമാണത്തിന്റെ പേരു മാറ്റുവാന്‍ അഭ്യര്‍ത്ഥിക്കുന്നു.",
'fileexists-forbidden'        => 'ഈ പേരില്‍ ഒരു പ്രമാണം നിലവിലുണ്ട്, അതു മാറ്റി സൃഷ്ടിക്കുക സാദ്ധ്യമല്ല.
താങ്കള്‍ക്ക് ഈ ചിത്രം അപ്‌ലോഡ് ചെയ്തേ മതിയാവുയെങ്കില്‍, ദയവു ചെയ്തു വേറൊരു പേരില്‍ ഈ പ്രമാണം അപ്‌ലോഡ് ചെയ്യുക. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'ഈ പേരില്‍ ഒരു പ്രമാണം പങ്ക് വെയ്ക്കപ്പെട്ടുപയോഗിക്കുന്ന ശേഖരത്തിലുണ്ട്. താങ്കള്‍ക്ക് ഈ പ്രമാണം അപ്‌ലോഡ് ചെയ്തേ മതിയാവുയെങ്കില്‍, ദയവായി തിരിച്ചു പോയി പുതിയ ഒരു പേരില്‍ ഈ പ്രമാണം അപ്‌ലോഡ് ചെയ്യുക.[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'ഈ പ്രമാണം ഇനി പറയുന്ന {{PLURAL:$1|പ്രമാണത്തിന്റെ|പ്രമാണങ്ങളുടെ}} അപരനാണ്‌:',
'file-deleted-duplicate'      => 'ഈ പ്രമാണത്തിനു സദൃശമായ ഫയൽ ([[$1]]) മുമ്പ് മായ്ക്കപ്പെട്ടിട്ടുണ്ട്.
ആ പ്രമാണത്തിന്റെ മായ്ക്കൽ ചരിത്രം എടുത്തു പരിശോധിച്ച ശേഷം വീണ്ടും അപ്‌‌ലോഡ് ചെയ്യുക.',
'successfulupload'            => 'അപ്‌ലോഡ് വിജയിച്ചിരിക്കുന്നു',
'uploadwarning'               => 'അപ്‌ലോഡ് മുന്നറിയിപ്പ്',
'savefile'                    => 'ഫയല്‍ കാത്ത് സൂക്ഷിക്കുക',
'uploadedimage'               => '"[[$1]]" അപ്‌ലോഡ് ചെയ്തു.',
'overwroteimage'              => '"[[$1]]" എന്നതിന്റെ പുതിയ പതിപ്പ് അപ്‌ലോഡ് ചെയ്തിരിക്കുന്നു',
'uploaddisabled'              => 'അപ്‌ലോഡുകള്‍ അനുവദനീയമല്ല',
'uploaddisabledtext'          => 'ഫയല്‍ അപ്‌ലോഡ് ചെയ്യുന്നതു സാദ്ധ്യമല്ലാതാക്കിയിരിക്കുന്നു.',
'uploadscripted'              => 'ഈ പ്രമാണത്തില്‍ വെബ്ബ് ബ്രൗസര്‍ തെറ്റായി വ്യാഖ്യാനിച്ചേക്കാവുന്ന HTML അല്ലെങ്കില്‍ സ്ക്രിപ് കോഡുകള്‍ ഉണ്ട്.',
'uploadcorrupt'               => 'ഉപയോഗയോഗ്യമല്ലാത്തതോ തെറ്റായ എക്സ്റ്റന്‍ഷന്‍ ഉപയോഗിക്കുന്നതോ ആയ ഒരു പ്രമാണമാണിത്‌. ദയവായി ഒന്നു കൂടി പരിശോധിച്ചതിനു ശേഷം മാത്രം പ്രമാണം അപ്‌ലോഡ് ചെയ്യുക.',
'uploadvirus'                 => 'പ്രമാണത്തില്‍ വൈറസുണ്ട്! വിശദാംശങ്ങള്‍: $1',
'sourcefilename'              => 'അപ്‌ലോഡ് ചെയ്യേണ്ട പ്രമാണത്തിന്റെ സ്രോതസ്സ് നാമം:',
'destfilename'                => '{{SITENAME}} സംരംഭത്തില്‍ ഉപയോഗിക്കേണ്ട പേര്:',
'upload-maxfilesize'          => 'ഫയലിന്റെ വലിപ്പത്തിന്റെ കൂടിയ പരിധി: $1',
'watchthisupload'             => 'ഈ പ്രമാണം ശ്രദ്ധിക്കുക',
'filewasdeleted'              => 'ഈ പേരിലുള്ള ഒരു പ്രമാണം ഇതിനു മുന്‍പ് അപ്‌ലോഡ് ചെയ്യുകയും പിന്നീട് മായ്ക്കുകയും ചെയ്തിട്ടുള്ളതാണ്‌. ഈ പ്രമാണം തുടര്‍ന്നും അപ്‌ലോഡ് ചെയ്യുന്നതിനു മുന്‍പ് $1 പരിശോധിക്കേണ്ടതാണ്‌.',
'upload-wasdeleted'           => "'''മുന്നറിയിപ്പ്: മുന്‍പ് അപ്‌ലോഡ് ചെയ്യുകയും പിന്നീട് മായ്ക്കുകയും ചെയ്തിട്ടുള്ള ഒരു പ്രമാണമാണ്‌ താങ്കള്‍ അപ്‌ലോഡ് ചെയ്യാന്‍ ശ്രമിക്കുന്നത്.'''

ഈ പ്രമാണം അപ്‌ലോഡ് ചെയ്യുന്നതു തുടരണമോ എന്നതു പരിശോധിക്കുന്നത് നന്നായിരിക്കും.
നിങ്ങളുടെ പരിശോധനയ്ക്കായി പ്രമാണത്തിന്റെ മായ്ക്കല്‍ ലോഗ് ഇവിടെ കൊടുത്തിരിക്കുന്നു:",
'filename-bad-prefix'         => "താങ്കള്‍ അപ്‌ലോഡ് ചെയ്യുവാന്‍ ശ്രമിക്കുന്ന പ്രമാണത്തിന്റെ പേര്‌ '''\"\$1\"''' എന്നാണ്‌ തുടങ്ങുന്നത്. ഇതു ഡിജിറ്റല്‍ ക്യാമറയില്‍ പടങ്ങള്‍ക്കു യാന്ത്രികമായി ചേര്‍ക്കുന്ന പേരാണ്‌. ദയവു ചെയ്തു താങ്കള്‍ അപ്‌ലോഡ് ചെയ്യുന്ന പ്രമാണത്തെ വിശദീകരിക്കുന്ന അനുയോജ്യമായ ഒരു പേരു തിരഞ്ഞെടുക്കുക.",

'upload-proto-error'      => 'തെറ്റായ പ്രോട്ടോക്കോള്‍',
'upload-proto-error-text' => 'റിമോട്ട് അപ്‌ലോഡിനു <code>http://</code> അല്ലെങ്കില്‍ <code>ftp://</code> യില്‍ തുടങ്ങുന്ന URL വേണം.',
'upload-file-error'       => 'ആന്തരികപ്രശ്നം',
'upload-file-error-text'  => 'സെര്‍വറില്‍ ഒരു താല്‍ക്കാലിക പ്രമാണം ഉണ്ടാക്കുവാന്‍ ശ്രമിക്കുമ്പോള്‍ ആന്തരികപ്രശ്നം സംഭവിച്ചു. ദയവായി [[Special:ListUsers/sysop|കാര്യനിർവാഹകരിലൊരാളെ]] സമീപിക്കുക.',
'upload-misc-error'       => 'കാരണം അജ്ഞാതമായ അപ്‌ലോഡ് പിശക്',
'upload-misc-error-text'  => 'അപ്‌ലോഡിങ്ങ് സമയത്ത് അജ്ഞാതമായ പിശക് സംഭവിച്ചു.
ദയവായി URL സാധുവാണോ എന്നും അതു പ്രാപ്യമാണോ എന്നും പരിശോധിച്ചതിനു ശേഷം വീണ്ടും പരിശ്രമിക്കുക.
തുടര്‍ന്നും പ്രശ്നം അവശേഷിക്കുകയാണെങ്കില്‍ [[Special:ListUsers/sysop|കാര്യനി‌ർവാഹകരിലൊരാളെ]] സമീപിക്കുക.',
'upload-unknown-size'     => 'വലിപ്പം അറിയില്ല',

# img_auth script messages
'img-auth-accessdenied' => 'പ്രവേശനമില്ല',
'img-auth-badtitle'     => '"$1" എന്നതില്‍ നിന്ന് സാധുവായ തലക്കെട്ട് സൃഷ്ടിക്കാന്‍ കഴിയില്ല.',
'img-auth-nologinnWL'   => 'താങ്കൾ ലോഗിൻ ചെയ്തിട്ടില്ല ഒപ്പം "$1" ശുദ്ധിപട്ടികയിൽ ഇല്ല.',
'img-auth-nofile'       => '"$1" എന്ന പ്രമാണം നിലവിലില്ല.',
'img-auth-isdir'        => 'താങ്കൾ "$1" എന്ന ഡയറക്ടറി എടുക്കാനാണു ശ്രമിക്കുന്നത്.
പ്രമാണങ്ങൾ എടുക്കാൻ മാത്രമേ അനുവദിക്കുള്ളു.',
'img-auth-noread'       => '"$1" എടുത്തുനോക്കാൻ ഉപയോക്താവിനു കഴിയില്ല.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL-ല്‍ എത്തിപ്പെടാന്‍ സാധിച്ചില്ല',
'upload-curl-error6-text'  => 'താങ്കള്‍ സമര്‍പ്പിച്ച URL പ്രാപ്യമല്ല‌. ദയവായി URL സാധുവാണോ എന്നും സൈറ്റ് സജീവമാണോ എന്നും പരിശോധിക്കുക.',
'upload-curl-error28'      => 'അപ്‌ലോഡ് ടൈംഔട്ട്',
'upload-curl-error28-text' => 'ഈ സൈറ്റില്‍ നിന്നു പ്രതികരണം ലഭിക്കുവാന്‍ ധാരാളം സമയമെടുക്കുന്നു. സൈറ്റ് സജീവമാണോ എന്നു പരിശോധിക്കുകയും കുറച്ച് സമയം കാത്തു നിന്നതിനു ശേഷം വീടും ശ്രമിക്കുകയും ചെയ്യുക. തിരക്കു കുറഞ്ഞ സമയത്ത് പരിശ്രമിക്കുന്നതാവും നല്ലത്.',

'license'            => 'പകര്‍പ്പവകാശ വിവരങ്ങള്‍:',
'license-header'     => 'അനുമതി',
'nolicense'          => 'ഒന്നും തിരഞ്ഞെടുത്തിട്ടില്ല',
'license-nopreview'  => '(പ്രിവ്യൂ ലഭ്യമല്ല)',
'upload_source_url'  => '(സാധുവായ, ആര്‍ക്കും ഉപയോഗിക്കാവുന്ന URL)',
'upload_source_file' => '(താങ്കളുടെ കമ്പ്യൂട്ടറിലുള്ള ഒരു പ്രമാണം)',

# Special:ListFiles
'listfiles-summary'     => 'ചുരുക്കം',
'listfiles_search_for'  => 'മീഡിയ പ്രമാണം തിരയുക:',
'imgfile'               => 'പ്രമാണം',
'listfiles'             => 'പ്രമാണങ്ങളുടെ പട്ടിക',
'listfiles_date'        => 'തിയതി',
'listfiles_name'        => 'പേര്',
'listfiles_user'        => 'ഉപയോക്താവ്',
'listfiles_size'        => 'വലുപ്പം',
'listfiles_description' => 'വിവരണം',
'listfiles_count'       => 'പതിപ്പുകള്‍',

# File description page
'file-anchor-link'          => 'പ്രമാണം',
'filehist'                  => 'പ്രമാണ നാള്‍‌വഴി',
'filehist-help'             => 'ഏതെങ്കിലും തീയതി/സമയ കണ്ണിയില്‍ ഞെക്കിയാല്‍ പ്രസ്തുതസമയത്ത് ഈ പ്രമാണം എങ്ങിനെയായിരുന്നു എന്നു കാണാം.',
'filehist-deleteall'        => 'എല്ലാം മായ്ക്കുക',
'filehist-deleteone'        => 'ഇതു മായ്ക്കുക',
'filehist-revert'           => 'പൂര്‍വ്വസ്ഥിതിയിലാക്കുക',
'filehist-current'          => 'നിലവിലുള്ളത്',
'filehist-datetime'         => 'തീയതി/സമയം',
'filehist-thumb'            => 'ലഘുചിത്രം',
'filehist-thumbtext'        => 'മാറ്റം $1-ന്റെ ലഘുചിത്രം',
'filehist-nothumb'          => 'ലഘുചിത്രമില്ല',
'filehist-user'             => 'ഉപയോക്താവ്',
'filehist-dimensions'       => 'അളവുകള്‍',
'filehist-filesize'         => 'പ്രമാണത്തിന്റെ വലിപ്പം',
'filehist-comment'          => 'അഭിപ്രായം',
'filehist-missing'          => 'പ്രമാണം ലഭ്യമല്ല',
'imagelinks'                => 'പ്രമാണങ്ങളിലേക്കുള്ള കണ്ണികള്‍',
'linkstoimage'              => 'താഴെ കാണുന്ന {{PLURAL:$1|താളില്‍|$1 താളുകളില്‍}}  ഈ ചിത്രം ഉപയോഗിക്കുന്നു:',
'linkstoimage-more'         => 'ഈ പ്രമാണത്തിലേയ്ക്ക് {{PLURAL:$1|ഒരു താളിലധികം കണ്ണി|$1 താളിലധികം കണ്ണികൾ}} ഉണ്ട്.
താഴെക്കൊടുത്തിരിക്കുന്ന പട്ടിക ഈ പ്രമാണത്തിലേയ്ക്കു മാത്രമുള്ള {{PLURAL:$1|ആദ്യ താളിന്റെ കണ്ണി|ആദ്യ $1 താളുകളുടെ കണ്ണികൾ}} കാട്ടുന്നു.
[[Special:WhatLinksHere/$2|മുഴുവൻ പട്ടികയും]] ലഭ്യമാണ്.',
'nolinkstoimage'            => 'ഈ ചിത്രം/പ്രമാണം വിക്കിയിലെ താളുകളിലൊന്നിലും ഉപയോഗിക്കുന്നില്ല.',
'morelinkstoimage'          => 'ഈ പ്രമാണത്തിലേയ്ക്കുള്ള [[Special:WhatLinksHere/$1|കൂടുതൽ കണ്ണികൾ]] കാണുക.',
'redirectstofile'           => 'താഴെ ഈ പ്രമാണത്തിലേയ്ക്കുള്ള {{PLURAL:$1|പ്രമാണ തിരിച്ചുവിടലുകൾ|$1 പ്രമാണങ്ങളുടെ തിരിച്ചുവിടൽ}} കൊടുത്തിരിക്കുന്നു:',
'duplicatesoffile'          => 'ഈ പ്രമാണത്തിന്റെ {{PLURAL:$1|ഒരു അപര പ്രമാണത്തെ|$1 അപര പ്രമാണങ്ങളെ}} താഴെ കൊടുത്തിരിക്കുന്നു ([[Special:FileDuplicateSearch/$2|കൂടുതൽ വിവരങ്ങൾ]]):',
'sharedupload'              => 'ഇത് $1 സം‌രംഭത്തില്‍ നിന്നുള്ള പ്രമാണമാണ്‌, മറ്റു സം‌രംഭങ്ങളും ഇതുപയോഗിക്കുന്നുണ്ടാകാം.',
'sharedupload-desc-there'   => 'ഇത് $1-ൽ നിന്നുമുള്ളതാണ്, മറ്റു പദ്ധതികൾ ഇതുപയോഗിക്കുന്നുണ്ടാകാം.
കൂടുതൽ വിവരങ്ങൾക്ക് ദയവായി [$2 പ്രമാണത്തിന്റെ വിവരണ താൾ] കാണുക.',
'sharedupload-desc-here'    => 'ഇത് $1-ൽ നിന്നുമുള്ളതാണ്, മറ്റു പദ്ധതികൾ ഇതുപയോഗിക്കുന്നുണ്ടാകാം.
[$2 പ്രമാണത്തിന്റെ വിവരണ താളിലുള്ള] വിവരങ്ങൾ താഴെ കൊടുത്തിരിക്കുന്നു.',
'filepage-nofile'           => 'ഈ പേരില്‍ ഒരു പ്രമാണവും നിലവിലില്ല.',
'filepage-nofile-link'      => 'ഈ പേരില്‍ ഒരു പ്രമാണവും നിലവിലില്ല, താങ്കള്‍ക്ക് [$1 അത് അപ്‌ലോഡ് ചെയ്യാവുന്നതാണ്‌]',
'uploadnewversion-linktext' => 'ഈ ചിത്രത്തിലും മെച്ചപ്പെട്ടത് അപ്‌ലോഡ് ചെയ്യുക',
'shared-repo-from'          => '$1ല്‍ നിന്ന്',
'shared-repo'               => 'ഒരു പങ്കുവെക്കപ്പെട്ട സംഭരണി',

# File reversion
'filerevert'                => '$1 തിരസ്ക്കരിക്കുക',
'filerevert-legend'         => 'പ്രമാണം തിരസ്ക്കരിക്കുക',
'filerevert-intro'          => "താങ്കള്‍ '''[[Media:$1|$1]]''' യെ, [$3, $2 ഉണ്ടായിരുന്ന $4 പതിപ്പിലേക്കു സേവ് ചെയ്യുകയാണ്‌].",
'filerevert-comment'        => 'കുറിപ്പ്:',
'filerevert-defaultcomment' => '$2 ല്‍ ഉണ്ടായിരുന്ന $1 പതിപ്പിലേക്കു സേവ് ചെയ്തിരിക്കുന്നു',
'filerevert-submit'         => 'പൂര്‍വ്വസ്ഥിതിയിലാക്കുക',
'filerevert-success'        => "'''[[Media:$1|$1]]''' യെ,  [$3, $2 ഉണ്ടായിരുന്ന $4] പതിപ്പിലേക്കു സേവ് ചെയ്തിരിക്കുന്നു.",
'filerevert-badversion'     => 'താങ്കള്‍ തന്ന സമയവുമായി യോജിക്കുന്ന മുന്‍ പതിപ്പുകള്‍ ഒന്നും തന്നെ ഈ പ്രമാണത്തിനില്ല.',

# File deletion
'filedelete'                  => '$1 മായ്ക്കുക',
'filedelete-legend'           => 'പ്രമാണം മായ്ക്കുക',
'filedelete-intro'            => "താങ്കള്‍ '''[[Media:$1|$1]]''' എന്ന പ്രമാണം അതിന്റെ എല്ലാ ചരിത്രവുമടക്കം നീക്കം ചെയ്യാന്‍ പോവുകയാണ്‌.",
'filedelete-intro-old'        => "നിങ്ങള്‍ '''[[Media:$1|$1]]''' യയുടെ [$3, $2 ഉണ്ടായിരുന്ന $4] പതിപ്പാണു മായ്ക്കുവാന്‍ പോകുന്നത്.",
'filedelete-comment'          => 'നീക്കം ചെയ്യാനുള്ള കാരണം:',
'filedelete-submit'           => 'മായ്ക്കുക',
'filedelete-success'          => "'''$1''' മായ്ച്ചു കഴിഞ്ഞു.",
'filedelete-success-old'      => "'''[[Media:$1|$1]]''' എന്ന മീഡിയയുടെ $3, $2-വില്‍ ഉണ്ടായിരുന്ന പതിപ്പ് മായ്ച്ചിരിക്കുന്നു.",
'filedelete-nofile'           => "'''$1'''  നിലവിലില്ല.",
'filedelete-otherreason'      => 'മറ്റു/കൂടുതല്‍ കാരണങ്ങള്‍:',
'filedelete-reason-otherlist' => 'മറ്റു കാരണങ്ങള്‍',
'filedelete-reason-dropdown'  => '*നീക്കം ചെയ്യാനുള്ള സാധാരണ കാരണങ്ങള്‍
** പകര്‍പ്പവകാശ ലംഘനം
** നിലവിലുള്ള ഫയലിന്റെ പകര്‍പ്പ്
** പകര്‍പ്പവകാശ വിവരങ്ങള്‍ ചേര്‍ത്തിട്ടില്ല',
'filedelete-edit-reasonlist'  => 'മായ്ക്കലിന്റെ കാരണം തിരുത്തുക',
'filedelete-maintenance'      => 'നന്നാക്കൽ പ്രവർത്തനങ്ങൾ പുരോഗമിക്കുന്നതിനാൽ പ്രമാണങ്ങളുടെ മായ്ക്കലും പുനഃസ്ഥാപിക്കലും താത്ക്കാലികമായി നിർത്തിവച്ചിരിക്കുന്നു.',

# MIME search
'mimesearch'         => 'MIME തിരയല്‍',
'mimesearch-summary' => 'ഈ താൾ പ്രമാണങ്ങളെ അവയുടെ മൈം-തരം അനുസരിച്ച് അരിച്ചെടുക്കാൻ പ്രാപ്തമാക്കുന്നു:
നൽകേണ്ടവിധം: പ്രമാണത്തിന്റെ തരം/ഉപതരം, ഉദാ:<tt>image/jpeg</tt>.',
'mimetype'           => 'MIME തരം:',
'download'           => 'ഡൗണ്‍ലോഡ്',

# Unwatched pages
'unwatchedpages' => 'ആരും ശ്രദ്ധിക്കാത്ത താളുകള്‍',

# List redirects
'listredirects' => 'തിരിച്ചുവിടല്‍ താളുകളുടെ പട്ടിക കാണിക്കുക',

# Unused templates
'unusedtemplates'     => 'ഉപയോഗിക്കപ്പെടാത്ത ഫലകങ്ങള്‍',
'unusedtemplatestext' => '{{ns:template}} എന്ന നാമമേഖലയില്‍ ഉള്ളതും ഒരു താളിലും ചേര്‍ത്തിട്ടുമില്ലാത്ത എല്ലാ ഫലകതാളുകളുടേയും പട്ടിക ഈ താളില്‍ കാണാം. ഫലകങ്ങള്‍ മായ്ക്കുന്നതിനു മുന്‍പ് അതു മറ്റൊരു താളിലും ഉപയോഗിക്കുന്നില്ല എന്നുറപ്പാക്കുക.',
'unusedtemplateswlh'  => 'മറ്റു കണ്ണികള്‍',

# Random page
'randompage'         => 'ഏതെങ്കിലും താള്‍',
'randompage-nopages' => 'ഇനി കൊടുത്തിരിക്കുന്ന {{PLURAL:$2|നാമമേഖലയില്‍|നാമമേഖലകളില്‍}} താളുകള്‍ ഒന്നുമില്ല: $1.',

# Random redirect
'randomredirect'         => 'ക്രമരഹിതമായ തിരിച്ചുവിടല്‍',
'randomredirect-nopages' => '"$1" എന്ന നാമമേഖലയില്‍ തിരിച്ചുവിടല്‍ താളുകളൊന്നുമില്ല.',

# Statistics
'statistics'                   => 'സ്ഥിതിവിവരക്കണക്കുകള്‍',
'statistics-header-pages'      => 'താള്‍ സ്ഥിതിവിവരക്കണക്കുകള്‍',
'statistics-header-edits'      => 'തിരുത്തല്‍ സ്ഥിതിവിവരക്കണക്കുകള്‍',
'statistics-header-views'      => 'സന്ദര്‍ശനങ്ങളുടെ സ്ഥിതിവിവരക്കണക്കുകള്‍',
'statistics-header-users'      => 'ഉപയോക്താക്കളുടെ സ്ഥിതിവിവരക്കണക്കുകള്‍',
'statistics-header-hooks'      => 'മറ്റു സ്ഥിതിവിവരക്കണക്കുകള്‍',
'statistics-articles'          => 'ലേഖനങ്ങള്‍',
'statistics-pages'             => 'താളുകള്‍',
'statistics-pages-desc'        => 'സം‌വാദം താളുകള്‍, തിരിച്ചുവിടലുകള്‍ തുടങ്ങിയവയടക്കം വിക്കിയിലെ എല്ലാ താളുകളും.',
'statistics-files'             => 'അപ്‌ലോഡ് ചെയ്തിട്ടുള്ള പ്രമാണങ്ങള്‍',
'statistics-edits'             => '{{SITENAME}} സം‌രംഭത്തിന്റെ തുടക്കം മുതലേയുള്ള തിരുത്തലുകള്‍',
'statistics-edits-average'     => 'ഒരു താളില്‍ ശരാശരി തിരുത്തലുകള്‍',
'statistics-views-total'       => 'ആകെ സന്ദര്‍ശനങ്ങള്‍',
'statistics-views-peredit'     => 'ഓരോ തിരുത്തലിലും ഉള്ള എടുത്തുനോട്ടങ്ങള്‍',
'statistics-jobqueue'          => '[http://www.mediawiki.org/wiki/Manual:Job_queue ജോബ് ക്യൂ] നീളം',
'statistics-users'             => 'രജിസ്റ്റര്‍ ചെയ്തിട്ടുള്ള [[Special:ListUsers|ഉപയോക്താക്കള്‍]]',
'statistics-users-active'      => 'സജീവ ഉപയോക്താക്കള്‍',
'statistics-users-active-desc' => 'കഴിഞ്ഞ {{PLURAL:$1|ദിവസം|$1 ദിവസങ്ങള്‍ക്കുള്ളില്‍}} പ്രവര്‍ത്തിച്ചിട്ടുള്ള ഉപയോക്താക്കള്‍',
'statistics-mostpopular'       => 'ഏറ്റവുമധികം സന്ദര്‍ശിക്കപ്പെട്ട താളുകള്‍',

'disambiguations'      => 'നാനാര്‍ത്ഥ താളുകള്‍',
'disambiguationspage'  => 'Template:നാനാര്‍ത്ഥം',
'disambiguations-text' => 'താഴെ കൊടുത്തിരിക്കുന്ന താളുകള്‍ നാനാര്‍ത്ഥതാളിലേക്കു കണ്ണി‍ ചേര്‍ക്കപ്പെട്ടിരിക്കുന്നു. അതിനു പകരം അവ ലേഖനതാളുകളിലേക്കു കണ്ണി ചേക്കേണ്ടതാണ്‌. <br /> ഒരു താളിനെ നാനാര്‍ത്ഥതാള്‍ ആയി പരിഗണിക്കണമെങ്കില്‍ അതു  [[MediaWiki:Disambiguationspage]] എന്ന താളില്‍ നിന്നു കണ്ണി ചേര്‍ക്കപ്പെട്ട ഒരു ഫലകം ഉപയോഗിക്കണം.',

'doubleredirects'            => 'ഇരട്ട തിരിച്ചുവിടലുകള്‍',
'doubleredirectstext'        => 'ഈ താളില്‍ ഒരു തിരിച്ചുവിടലില്‍ നിന്നും മറ്റു തിരിച്ചുവിടല്‍ താളുകളിലേയ്ക്ക് പോകുന്ന താളുകള്‍ കൊടുത്തിരിക്കുന്നു. ഓരോ വരിയിലും ഒന്നാമത്തേയും രണ്ടാമത്തേയും തിരിച്ചുവിടല്‍ താളിലേക്കുള്ള കണ്ണികളും, രണ്ടാമത്തെ തിരിച്ചുവിടല്‍ താളില്‍ നിന്നു ശരിയായ ലക്ഷ്യതാളിലേക്കുള്ള കണ്ണികളും ഉള്‍ക്കൊള്ളുന്നു.
<s>വെട്ടിക്കൊടുത്തിരിക്കുന്നവ</s> ശരിയാക്കേണ്ടതുണ്ട്.',
'double-redirect-fixed-move' => '[[$1]] മാറ്റിയിരിക്കുന്നു.
ഇത് ഇപ്പോള്‍ [[$2]] എന്നതിലേയ്ക്ക് തിരിച്ചുവിടപ്പെട്ടിരിക്കുന്നു.',
'double-redirect-fixer'      => 'തിരിച്ചുവിടൽ ശരിയാക്കിയത്',

'brokenredirects'        => 'മുറിഞ്ഞ തിരിച്ചുവിടലുകള്‍',
'brokenredirectstext'    => 'താഴെക്കാണുന്ന തിരിച്ചുവിടലുകള്‍ നിലവിലില്ലാത്ത താളുകളിലേയ്ക്കാണ്‌:',
'brokenredirects-edit'   => 'തിരുത്തുക',
'brokenredirects-delete' => 'മായ്ക്കുക',

'withoutinterwiki'         => 'അന്തര്‍ഭാഷാകണ്ണികള്‍ ഇല്ലാത്ത താളുകള്‍',
'withoutinterwiki-summary' => 'താഴെ പ്രദര്‍ശിപ്പിച്ചിരിക്കുന്ന താളുകളില്‍ മറ്റു ഭാഷാ വിക്കികളിലേക്ക്  കണ്ണി ചേര്‍ത്തിട്ടില്ല.',
'withoutinterwiki-legend'  => 'പൂര്‍വപ്രത്യയം',
'withoutinterwiki-submit'  => 'പ്രദര്‍ശിപ്പിക്കുക',

'fewestrevisions' => 'ഏറ്റവും ചുരുക്കം പ്രാവശ്യം തിരുത്തപ്പെട്ട താളുകള്‍',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|ബൈറ്റ്|ബൈറ്റുകള്‍}}',
'ncategories'             => '$1 {{PLURAL:$1|വര്‍ഗ്ഗം|വര്‍ഗ്ഗങ്ങള്‍}}',
'nlinks'                  => '$1 {{PLURAL:$1|കണ്ണി|കണ്ണികള്‍}}',
'nmembers'                => '$1 {{PLURAL:$1|അംഗം|അംഗങ്ങള്‍}}',
'nrevisions'              => '$1 {{PLURAL:$1|പതിപ്പ്|പതിപ്പുകള്‍}}',
'nviews'                  => '$1 {{PLURAL:$1|സന്ദര്‍ശനം|സന്ദര്‍ശനങ്ങള്‍}}',
'specialpage-empty'       => 'ഈ താള്‍ ശൂന്യമാണ്.',
'lonelypages'             => 'അനാഥ താളുകള്‍',
'lonelypagestext'         => 'താഴെക്കാണുന്ന താളുകളിലേക്ക് {{SITENAME}} സം‌രംഭത്തിലെ മറ്റു താളുകളില്‍നിന്നും കണ്ണികളോ ഉൾപ്പെടുത്തലോ നിലവിലില്ല.',
'uncategorizedpages'      => 'വര്‍ഗ്ഗീകരിച്ചിട്ടില്ലാത്ത താളുകള്‍',
'uncategorizedcategories' => 'വര്‍ഗ്ഗീകരിക്കപ്പെടാത്ത വര്‍ഗ്ഗങ്ങള്‍',
'uncategorizedimages'     => 'വര്‍ഗ്ഗീകരിക്കപ്പെടാത്ത പ്രമാണങ്ങള്‍',
'uncategorizedtemplates'  => 'വര്‍ഗ്ഗീകരിക്കാത്ത ഫലകങ്ങള്‍',
'unusedcategories'        => 'ഉപയോഗത്തിലില്ലാത്ത വര്‍ഗ്ഗങ്ങള്‍',
'unusedimages'            => 'ഉപയോഗിക്കപ്പെടാത്ത പ്രമാണങ്ങള്‍',
'popularpages'            => 'ജനപ്രീതിയുള്ള താളുകള്‍',
'wantedcategories'        => 'അവശ്യ വര്‍ഗ്ഗങ്ങള്‍',
'wantedpages'             => 'അവശ്യ താളുകള്‍',
'wantedpages-badtitle'    => 'ഫലങ്ങളുടെ ഗണത്തിൽ അസാധുവായ തലക്കെട്ട്: $1',
'wantedfiles'             => 'ആവശ്യമുള്ള ഫയലുകള്‍',
'wantedtemplates'         => 'അവശ്യ ഫലകങ്ങള്‍',
'mostlinked'              => 'ഏറ്റവുമധികം കണ്ണികളാല്‍ ബന്ധിപ്പിക്കപ്പെട്ട താളുകള്‍',
'mostlinkedcategories'    => 'ഏറ്റവും കൂടുതല്‍ താളുകള്‍ ബന്ധിപ്പിക്കപ്പെട്ട വര്‍ഗ്ഗങ്ങള്‍',
'mostlinkedtemplates'     => 'ഫലകങ്ങളിലേക്ക് ഏറ്റവുമധികം കണ്ണിയുള്ള താളുകള്‍',
'mostcategories'          => 'ഏറ്റവും കൂടുതല്‍ വര്‍ഗ്ഗങ്ങള്‍ ഉള്‍പെടുത്തിയിരിക്കുന്ന താളുകള്‍',
'mostimages'              => 'ചിത്രങ്ങളിലേക്ക് ഏറ്റവുമധികം കണ്ണിയുള്ള താളുകള്‍',
'mostrevisions'           => 'ഏറ്റവുമധികം തിരുത്തപ്പെട്ട താളുകള്‍',
'prefixindex'             => 'പൂര്‍വപദത്തോടു കൂടിയ എല്ലാ താളുകളും',
'shortpages'              => 'വിവരം ഏറ്റവും കുറവുള്ള താളുകള്‍',
'longpages'               => 'വലിയ താളുകളുടെ പട്ടിക',
'deadendpages'            => 'അന്തര്‍ വിക്കി കണ്ണിയാല്‍ ബന്ധിപ്പിക്കപ്പെടാത്ത താളുകള്‍',
'deadendpagestext'        => 'താഴെക്കാണുന്ന താളുകളില്‍നിന്ന് {{SITENAME}} സം‌രംഭത്തിലെ മറ്റൊരു താളിലേയ്ക്കും കണ്ണി ചേര്‍ത്തിട്ടില്ല.',
'protectedpages'          => 'സംരക്ഷിക്കപ്പെട്ടിരിക്കുന്ന താളുകള്‍',
'protectedpages-indef'    => 'അനന്തകാലത്തേയ്ക്ക് സംരക്ഷിക്കപ്പെട്ടവ മാത്രം',
'protectedpages-cascade'  => 'നിർഝരിത സംരക്ഷണങ്ങൾ മാത്രം',
'protectedpagestext'      => 'താഴെ പ്രദര്‍ശിപ്പിച്ചിരിക്കുന്ന താളുകള്‍ തലക്കെട്ട് മാറ്റുന്നതില്‍ നിന്നും തിരുത്തല്‍ വരുത്തുന്നതില്‍ നിന്നും സം‌രക്ഷിച്ചിരിക്കുന്നു',
'protectedpagesempty'     => 'ഈ പരാമീറ്ററുകള്‍ ഉപയോഗിച്ചു താളുകള്‍ ഒന്നും തന്നെ സം‌രക്ഷിക്കപ്പെട്ടിട്ടില്ല.',
'protectedtitles'         => 'സംരക്ഷിക്കപ്പെട്ട താളുകള്‍',
'protectedtitlestext'     => 'താഴെക്കാണുന്ന തലക്കെട്ടുകള്‍ സൃഷ്ടിക്കുന്നത് നിരോധിച്ചിരിക്കുന്നു',
'protectedtitlesempty'    => 'ഈ പരാമീറ്ററുകള്‍ ഉപയോഗിച്ചു ടൈറ്റിലുകള്‍ ഒന്നും തന്നെ സം‌രക്ഷിക്കപ്പെട്ടിട്ടില്ല.',
'listusers'               => 'ഉപയോക്താക്കളുടെ പട്ടിക',
'listusers-editsonly'     => 'തിരുത്തലുകള്‍ വരുത്തിയ ഉപയോക്താക്കളെമാത്രം കാണിക്കുക',
'listusers-creationsort'  => 'നിര്‍മ്മിക്കപ്പെട്ട തീയതി അനുസരിച്ച് ക്രമീകരിക്കുക',
'usereditcount'           => '{{PLURAL:$1|ഒരു തിരുത്തല്‍|$1 തിരുത്തലുകള്‍}}',
'usercreated'             => '$1 $2-നു സൃഷ്ടിച്ചത്',
'newpages'                => 'പുതിയ താളുകള്‍',
'newpages-username'       => 'ഉപയോക്തൃനാമം:',
'ancientpages'            => 'ഏറ്റവും പഴയ താളുകള്‍',
'move'                    => 'തലക്കെട്ടു്‌ മാറ്റുക',
'movethispage'            => 'ഈ താള്‍ മാറ്റുക',
'unusedcategoriestext'    => 'താഴെ പറയുന്ന വര്‍ഗ്ഗത്താള്‍ നിലവിലുണ്ട്, എങ്കിലും മറ്റു താളുകളോ വര്‍ഗ്ഗങ്ങളോ അവ ഉപയോഗിക്കുന്നില്ല.',
'notargettitle'           => 'ലക്ഷ്യം നിര്‍‌വചിച്ചിട്ടില്ല',
'notargettext'            => 'ഈ പ്രക്രിയ പൂര്‍ത്തിയാക്കുവാന്‍ ആവശ്യമായ ലക്ഷ്യതാളിനേയോ ഉപയോക്താവിനേയോ താങ്കള്‍ സൂചിപ്പിച്ചിട്ടില്ല.',
'nopagetitle'             => 'ഇങ്ങനെ ഒരു താള്‍ നിലവിലില്ല',
'nopagetext'              => 'താങ്കൾ വ്യക്തമാക്കിയ ലക്ഷ്യതാൾ നിലവിലില്ല.',
'pager-newer-n'           => '{{PLURAL:$1|പുതിയ 1|പുതിയ $1}}',
'pager-older-n'           => '{{PLURAL:$1|പഴയ 1|പഴയ $1}}',
'suppress'                => 'മേല്‍നോട്ടം',

# Book sources
'booksources'               => 'പുസ്തക സ്രോതസ്സുകള്‍',
'booksources-search-legend' => 'പുസ്തകസ്രോതസ്സുകള്‍ക്കായി തിരയുക',
'booksources-isbn'          => 'ഐ.എസ്.ബി.എന്‍.:',
'booksources-go'            => 'പോകൂ',
'booksources-text'          => 'പുതിയതും ഉപയോഗിച്ചതുമായ പുസ്തകങ്ങള്‍ വില്‍ക്കുന്ന സൈറ്റുകളിലേക്കുള്ള ലിങ്കുകളുടെ പട്ടിക ആണ്‌ താഴെ. നിങ്ങള്‍ തിരയുന്ന പുസ്തകത്തെ പറ്റിയുള്ള കൂടുതല്‍ വിവരങ്ങള്‍ ഈ പട്ടികയില്‍ നിന്നു ലഭിച്ചേക്കാം:',
'booksources-invalid-isbn'  => 'തന്നിരിക്കുന്ന ഐ.എസ്.ബി.എന്‍. സാധുവാണെന്നു തോന്നുന്നില്ല; യഥാര്‍ത്ഥ സ്രോതസ്സില്‍ നിന്നും പകര്‍ത്തിയപ്പോള്‍ തെറ്റുപറ്റിയോ എന്നു പരിശോധിക്കുക',

# Special:Log
'specialloguserlabel'  => 'ഉപയോക്താവ്:',
'speciallogtitlelabel' => 'ശീര്‍ഷകം:',
'log'                  => 'പ്രവര്‍ത്തന രേഖകള്‍',
'all-logs-page'        => 'എല്ലാ പൊതുരേഖകളും',
'alllogstext'          => '{{SITENAME}} സംരംഭത്തില്‍ ലഭ്യമായ പ്രവര്‍ത്തന രേഖകള്‍ സംയുക്തമായി ഈ താളില്‍ കാണാം. താങ്കള്‍ക്ക് രേഖകളുടെ സ്വഭാവം, ഉപയോക്തൃനാമം(കേസ് സെന്‍സിറ്റീവ്), ബന്ധപ്പെട്ട താള്‍ (കേസ് സെന്‍സിറ്റീവ്) മുതലായവ തിരഞ്ഞെടുത്ത് അന്വേഷണം കൂടുതല്‍ ക്ഌപ്തപ്പെടുത്താവുന്നതാണ്.',
'logempty'             => 'താളുമായി ബന്ധപ്പെട്ട രേഖകള്‍ ഒന്നും തന്നെയില്ല.',
'log-title-wildcard'   => 'ഈ വാക്കില്‍ തുടങ്ങുന്ന തിരച്ചില്‍ ഫലങ്ങള്‍',

# Special:AllPages
'allpages'          => 'എല്ലാ താളുകളും',
'alphaindexline'    => '$1 മുതല്‍ $2 വരെ',
'nextpage'          => 'അടുത്ത താള്‍ ($1)',
'prevpage'          => 'മുന്‍പത്തെ താള്‍ ($1)',
'allpagesfrom'      => 'താളുകളുടെ തുടക്കം:',
'allpagesto'        => 'ഇതില്‍ അവസാനിക്കുന്ന താളുകള്‍ കാട്ടുക:',
'allarticles'       => 'എല്ലാ താളുകളും',
'allinnamespace'    => 'എല്ലാ താളുകളും ($1 നാമമേഖല)',
'allnotinnamespace' => 'എല്ലാ താളുകളും ($1 നെയിംസ്പേസിലല്ലാത്തത്)',
'allpagesprev'      => 'മുമ്പത്തെ',
'allpagesnext'      => 'അടുത്തത്',
'allpagessubmit'    => 'പോകൂ',
'allpagesprefix'    => 'പൂര്‍‌വ്വപ്രത്യയമുള്ള താളുകള്‍ പ്രദര്‍ശിപ്പിക്കുക:',
'allpagesbadtitle'  => 'താളിനു നല്‍കിയ തലക്കെട്ട് അസാധുവാണ്‌ അല്ലങ്കില്‍ അന്തര്‍‌ഭാഷയ്ക്കുള്ളതോ അന്തര്‍‌വിക്കിയ്ക്കുള്ളതോ ആയ പൂര്‍‌വ്വപദം ഉപയോഗിച്ചിരിക്കുന്നു.
തലക്കെട്ടില്‍ ഉപയോഗിക്കാന്‍ പാടില്ലാത്ത ഒന്നോ അതിലധികമോ ലിപികള്‍ ഇതിലുണ്ടാകാം.',
'allpages-bad-ns'   => '{{SITENAME}} സംരംഭത്തില്‍ "$1" എന്ന നാമമേഖല നിലവിലില്ല.',

# Special:Categories
'categories'                    => 'വര്‍ഗ്ഗങ്ങള്‍',
'categoriespagetext'            => 'താഴെ കൊടുത്തിരിക്കുന്ന {{PLURAL:$1|വര്‍ഗ്ഗത്തില്‍|വര്‍ഗ്ഗങ്ങളില്‍}} താളുകളും പ്രമാണങ്ങളുമുണ്ട്. [[Special:UnusedCategories|ഉപയോഗിക്കപ്പെടാത്ത വര്‍ഗ്ഗങ്ങള്‍]] ഇവിടെ കാണിക്കുന്നില്ല. [[Special:WantedCategories|അവശ്യവര്‍ഗ്ഗങ്ങള്‍]] കൂടി കാണുക.',
'categoriesfrom'                => 'ഇങ്ങിനെ തുടങ്ങുന്ന വര്‍ഗ്ഗങ്ങള്‍ കാട്ടുക:',
'special-categories-sort-count' => 'എണ്ണത്തിനനുസരിച്ച് ക്രമപ്പെടുത്തുക',
'special-categories-sort-abc'   => 'അക്ഷരമാലാക്രമത്തില്‍ ക്രമീകരിക്കുക',

# Special:DeletedContributions
'deletedcontributions'             => 'മായ്ക്കപ്പെട്ട ഉപയോക്തൃസംഭാവനകള്‍',
'deletedcontributions-title'       => 'മായ്ക്കപ്പെട്ട ഉപയോക്തൃസംഭാവനകള്‍',
'sp-deletedcontributions-contribs' => 'സം‌ഭാവനകള്‍',

# Special:LinkSearch
'linksearch'       => 'വെബ്ബ് കണ്ണികള്‍',
'linksearch-pat'   => 'തിരച്ചിലിന്റെ മാതൃക:',
'linksearch-ns'    => 'നെയിംസ്പേസ്:',
'linksearch-ok'    => 'തിരയൂ',
'linksearch-text'  => '"*.wikipedia.org" പോലുള്ള വൈല്‍ഡ് കാര്‍ഡുകള്‍ ഉപയോഗിക്കാവുന്നതാണ്‌.<br />
പിന്താങ്ങുന്ന പ്രോട്ടോക്കോളുകള്‍: <tt>$1</tt>',
'linksearch-line'  => '$1,  $2ല്‍ നിന്നു കണ്ണി ചേര്‍ക്കപ്പെട്ടിരിക്കുന്നു.',
'linksearch-error' => 'ഹോസ്റ്റ്നെയിമിന്റെ തുടക്കത്തില്‍ മാത്രമേ വൈല്‍ഡ് കാര്‍ഡുകള്‍ വരാവൂ.',

# Special:ListUsers
'listusersfrom'      => 'ഇതില്‍ തുടങ്ങുന്ന ഉപയോക്താക്കളെ പ്രദര്‍ശിപ്പിക്കുക:',
'listusers-submit'   => 'പ്രദര്‍ശിപ്പിക്കുക',
'listusers-noresult' => 'ഈ ഗ്രൂപ്പില്‍ ഉള്‍പ്പെടുന്ന ഉപയോക്താക്കള്‍ ആരും ഇല്ല.',
'listusers-blocked'  => '(തടയപ്പെട്ടത്)',

# Special:ActiveUsers
'activeusers'          => 'സജീവ ഉപയോക്താക്കളുടെ പട്ടിക',
'activeusers-count'    => 'അവസാനത്തെ {{PLURAL:$3|ദിവസം|$3 ദിവസങ്ങളിൽ}} നടത്തിയ {{PLURAL:$1|തിരുത്തൽ|$1 തിരുത്തലുകൾ}}',
'activeusers-from'     => 'ഇങ്ങിനെ തുടങ്ങുന്ന ഉപയോക്താക്കളെ കാട്ടുക:',
'activeusers-noresult' => 'ഉപയോക്താക്കളില്ല',

# Special:Log/newusers
'newuserlogpage'              => 'പുതിയ ഉപയോക്താക്കളുടെ പട്ടിക',
'newuserlogpagetext'          => 'പുതിയതായി അംഗത്വമെടുത്ത ഉപയോക്താക്കളുടെ പട്ടിക താഴെ കാണാം.',
'newuserlog-byemail'          => 'രഹസ്യവാക്ക് ഇ-മെയില്‍ വഴി അയച്ചിരിക്കുന്നു',
'newuserlog-create-entry'     => 'പുതിയ ഉപയോക്താവ്',
'newuserlog-create2-entry'    => 'പുതിയ അംഗത്വം $1 സൃഷ്ടിച്ചിരിക്കുന്നു',
'newuserlog-autocreate-entry' => 'അക്കൗണ്ട് യാന്ത്രികമായി ഉണ്ടാക്കിയിരിക്കുന്നു',

# Special:ListGroupRights
'listgrouprights'          => 'ഉപയോക്തൃവിഭാഗത്തിന്റെ അവകാശങ്ങള്‍',
'listgrouprights-key'      => '* <span class="listgrouprights-granted">അവകാശം നല്‍കിയിരിക്കുന്നു</span>
* <span class="listgrouprights-revoked">അവകാശം നീക്കിയിരിക്കുന്നു</span>',
'listgrouprights-group'    => 'വിഭാഗം',
'listgrouprights-rights'   => 'അവകാശങ്ങള്‍',
'listgrouprights-helppage' => 'Help:സംഘാവകാശങ്ങൾ',
'listgrouprights-members'  => '(അംഗങ്ങളുടെ പട്ടിക)',

# E-mail user
'mailnologin'      => 'അയയ്ക്കാനുള്ള വിലാസം ലഭ്യമല്ല',
'mailnologintext'  => 'മറ്റ് ഉപയോക്താക്കള്‍ക്കു ഇമെയിലയക്കുവാന്‍ താങ്കള്‍ [[Special:UserLogin|ലോഗിന്‍]] ചെയ്തിരിക്കുകയും, സാധുവായ ഒരു ഇമെയില്‍ വിലാസം താങ്കളുടെ [[Special:Preferences|ക്രമീകരണങ്ങള്‍]] താളില്‍ സജ്ജീകരിച്ചിരിക്കുകയും വേണം.',
'emailuser'        => 'ഈ ഉപയോക്താവിനു ഇമെയില്‍ അയക്കൂ',
'emailpage'        => 'ഉപയോക്താവിന് ഇമെയില്‍ അയക്കുക',
'emailpagetext'    => 'താഴെ കാണുന്ന ഫോം മറ്റൊരു ഉപയോക്താവിന്‌ ഇമെയില്‍ അയക്കാന്‍ ഉപയോഗിക്കാവുന്നതാണ്.
[[Special:Preferences|ഉപയോക്താവിന്റെ ക്രമീകരണങ്ങളില്‍]] കൊടുത്തിട്ടുള്ള ഇമെയില്‍ വിലാസം "ദാതാവ്" ആയി വരുന്നതാണ്‌, അതുകൊണ്ട് സ്വീകര്‍ത്താവിന്‌ താങ്കള്‍ക്ക് നേരിട്ട് മറുപടി അയക്കാന്‍ കഴിയും.',
'defemailsubject'  => '{{SITENAME}} സം‌രംഭത്തില്‍ നിന്നുള്ള ഇമെയില്‍',
'noemailtitle'     => 'ഇമെയില്‍ വിലാസം ഇല്ല',
'noemailtext'      => 'ഈ ഉപയോക്താവ് സാധുവായ ഇമെയില്‍ വിലാസം നല്‍കിയിട്ടില്ല.',
'nowikiemailtitle' => 'ഇ-മെയില്‍ അനുവദിക്കപ്പെട്ടിട്ടില്ല',
'nowikiemailtext'  => 'ഈ ഉപയോക്താവ് മറ്റുള്ളവരില്‍ നിന്നും ഇ-മെയില്‍ സ്വീകരിക്കുന്നത് ഒഴിവാക്കിയിരിക്കുന്നു.',
'email-legend'     => 'മറ്റൊരു {{SITENAME}} ഉപയോക്താവിനു ഇമെയില്‍ അയയ്ക്കുക',
'emailfrom'        => 'ദാതാവ്:',
'emailto'          => 'സ്വീകര്‍ത്താവ്:',
'emailsubject'     => 'വിഷയം:',
'emailmessage'     => 'സന്ദേശം:',
'emailsend'        => 'അയക്കൂ',
'emailccme'        => 'ഇമെയിലിന്റെ പകര്‍പ്പ് എനിക്കും അയക്കുക.',
'emailccsubject'   => '$2: $1 എന്ന ഉപയോക്താവിനയച്ച സന്ദേശത്തിന്റെ പകര്‍പ്പ്',
'emailsent'        => 'ഇമെയില്‍ അയച്ചിരിക്കുന്നു',
'emailsenttext'    => 'താങ്കളുടെ ഇമെയില്‍ അയച്ചു കഴിഞ്ഞിരിക്കുന്നു.',
'emailuserfooter'  => 'ഈ ഇ-മെയില്‍ {{SITENAME}} "ഉപയോക്താവിന്‌ ഇമെയില്‍ അയയ്ക്കുക" എന്ന സൌകര്യം ഉപയോഗിച്ച് $1 $2 ന് അയച്ചതാണ്.',

# Watchlist
'watchlist'            => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക',
'mywatchlist'          => 'ഞാന്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക',
'watchlistfor'         => "('''$1'''നു വേണ്ടി)",
'nowatchlist'          => 'താങ്കള്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയില്‍ ഇനങ്ങളൊന്നുമില്ല.',
'watchlistanontext'    => 'നിങ്ങളുടെ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക കാണുവാനോ തിരുത്തുവാനോ $1 ചെയ്യുക.',
'watchnologin'         => 'ലോഗിന്‍ ചെയ്തിട്ടില്ല',
'watchnologintext'     => 'ശ്രദ്ധിക്കുന്ന താളിന്റെ പട്ടിക തിരുത്തുവാന്‍ നിങ്ങള്‍ [[Special:UserLogin|ലോഗിന്‍]] ചെയ്തിരിക്കണം.',
'addedwatch'           => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിലേക്കു ചേര്‍ത്തിരിക്കുന്നു',
'addedwatchtext'       => "\"[[:\$1]]\" എന്ന ഈ താള്‍ താങ്കള്‍ [[Special:Watchlist|ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിലേക്കു]] ചേര്‍ത്തിരിക്കുന്നു. ഇനിമുതല്‍ ഈ താളിലും ബന്ധപ്പെട്ട സം‌വാദംതാളിലും ഉണ്ടാകുന്ന മാറ്റങ്ങള്‍ ഈ പട്ടികയില്‍ ദൃശ്യമാവും. കൂടാതെ [[Special:RecentChanges|പുതിയ മാറ്റങ്ങള്‍]] താളില്‍ ഈ താളുകളിലെ മാറ്റങ്ങള്‍ താങ്കള്‍ക്ക് എളുപ്പത്തില്‍ തിരിച്ചറിയാന്‍ '''കടുപ്പത്തില്‍''' കാണിക്കുകയും ചെയ്യും.

ഇനി എപ്പോഴെങ്കിലും പ്രസ്തുത താള്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയില്‍ നിന്നു നീക്കം ചെയ്യണമെങ്കില്‍, താളിന്റെ മുകളിലെ വരിയില്‍ കാണുന്ന \"മാറ്റങ്ങള്‍ അവഗണിക്കുക\" എന്ന ടാബില്‍ ഞെക്കിയാല്‍ മതിയാകും.",
'removedwatch'         => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയില്‍ നിന്നും നീക്കിയിരിക്കുന്നു',
'removedwatchtext'     => '"[[:$1]]" എന്ന താള്‍ താങ്കള്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയില്‍ നിന്നും നീക്കം ചെയ്തിരിക്കുന്നു.',
'watch'                => 'മാറ്റങ്ങള്‍ ശ്രദ്ധിക്കുക',
'watchthispage'        => 'ഈ താള്‍ ശ്രദ്ധിക്കുക',
'unwatch'              => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയില്‍ നിന്നു മാറ്റുക',
'unwatchthispage'      => 'ശ്രദ്ധിക്കുന്നത് അവസാനിപ്പിക്കുക',
'notanarticle'         => 'ലേഖന താള്‍ അല്ല',
'watchnochange'        => 'താങ്കള്‍ ശ്രദ്ധിക്കുന്ന താളുകള്‍ ഒന്നും തന്നെ ഇക്കാലയളവില്‍ തിരുത്തപ്പെട്ടിട്ടില്ല.',
'watchlist-details'    => 'സം‌വാദം താളുകള്‍ അല്ലാത്ത {{PLURAL:$1|$1 താള്‍|$1 താളുകള്‍}} ശ്രദ്ധിക്കുന്ന താളിന്റെ പട്ടികയിലുണ്ട്.',
'wlheader-enotif'      => '* ഇമെയില്‍‌ വിജ്ഞാപനം സാധ്യമാക്കിയിരിക്കുന്നു.',
'wlheader-showupdated' => "* താങ്കളുടെ അവസാന സന്ദര്‍ശനത്തിനു ശേഷം തിരുത്തപ്പെട്ട താളുകള്‍  '''കടുപ്പിച്ച്''' കാണിച്ചിരിക്കുന്നു",
'watchmethod-recent'   => 'ശ്രദ്ധിക്കുന്ന താളുകള്‍ക്കുവേണ്ടി പുതിയ മാറ്റങ്ങള്‍ പരിശോധിക്കുന്നു',
'watchmethod-list'     => 'ശ്രദ്ധിക്കുന്ന താളുകളിലെ പുതിയ മാറ്റങ്ങള്‍ പരിശോധിക്കുന്നു',
'watchlistcontains'    => 'താങ്കള്‍ {{PLURAL:$1|താള്‍|താളുകള്‍}} ശ്രദ്ധിക്കുന്നുണ്ട്.',
'iteminvalidname'      => "ഇനം '$1' ല്‍ പിഴവ്, അസാധുവായ പേര്‌‍...",
'wlnote'               => "കഴിഞ്ഞ {{PLURAL:$2|മണിക്കൂറില്‍|'''$2''' മണിക്കൂറില്‍}} നടന്ന {{PLURAL:$1|ഒരു പുതിയ മാറ്റം|'''$1''' പുതിയ മാറ്റങ്ങള്‍}} താഴെ പ്രദര്‍ശിപ്പിച്ചിരിക്കുന്നു.",
'wlshowlast'           => 'ഒടുവിലത്തെ $1 മണിക്കൂറുകള്‍ $2 ദിനങ്ങള്‍, $3 കാട്ടുക',
'watchlist-options'    => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ സജ്ജീകരണങ്ങള്‍',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ശ്രദ്ധിക്കുന്നു...',
'unwatching' => 'അവഗണിക്കുന്നു...',

'enotif_mailer'                => '{{SITENAME}} വിജ്ഞാപന മെയിലര്‍',
'enotif_reset'                 => 'എല്ലാ താളുകളും സന്ദര്‍ശിച്ചതായി രേഖപ്പെടുത്തുക',
'enotif_newpagetext'           => 'ഇതൊരു പുതിയ താളാണ്‌',
'enotif_impersonal_salutation' => '{{SITENAME}} ഉപയോക്താവ്',
'changed'                      => 'മാറ്റിയിരിക്കുന്നു',
'created'                      => 'സൃഷ്ടിച്ചു',
'enotif_subject'               => '{{SITENAME}} സംരംഭത്തിലെ $PAGETITLE എന്ന താള്‍ $PAGEEDITOR $CHANGEDORCREATED',
'enotif_lastvisited'           => 'താങ്കളുടെ അവസാന സന്ദര്‍ശനത്തിനു ശേഷമുണ്ടായ മാറ്റങ്ങള്‍ കാണുവാന്‍  $1 സന്ദര്‍ശിക്കുക.',
'enotif_lastdiff'              => 'ഈ മാറ്റം ദര്‍ശിക്കാന്‍ $1 കാണുക.',
'enotif_anon_editor'           => 'അജ്ഞാത ഉപയോക്താവ് $1',
'enotif_body'                  => 'പ്രിയ $WATCHINGUSERNAME,


{{SITENAME}} സം‌രംഭത്തിലെ $PAGETITLE താള്‍ $PAGEEDITDATE-ല്‍ $PAGEEDITOR എന്ന ഉപയോക്താവ് $CHANGEDORCREATED, ഇപ്പോഴുള്ള പതിപ്പിനായി $PAGETITLE_URL കാണുക.

$NEWPAGE

തിരുത്തിയയാള്‍ നല്‍കിയ സം‌ഗ്രഹം: $PAGESUMMARY $PAGEMINOREDIT

തിരുത്തിയയാളെ ബന്ധപ്പെടുക:
മെയില്‍: $PAGEEDITOR_EMAIL
വിക്കി: $PAGEEDITOR_WIKI

താങ്കള്‍ ഈ താള്‍ സന്ദര്‍ശിക്കുന്നില്ലങ്കില്‍ മറ്റ് അറിയിപ്പുകള്‍ ഒന്നുമുണ്ടാകുന്നതല്ല.
ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക സന്ദര്‍ശിച്ചും ഉള്‍പ്പെട്ട താളുകളിലെ അറിയിപ്പ് മുദ്രകള്‍ താങ്കള്‍ക്ക് പുനഃക്രമീകരിക്കാവുന്നതാണ്‌.
             താങ്കളുടെ {{SITENAME}} സുഹൃദ് അറിയിപ്പ് സജ്ജീകരണം
         
--
ശ്രദ്ധിക്കുന്ന പട്ടികയിലെ ക്രമീകരണങ്ങളില്‍ മാറ്റം വരുത്താന്‍, സന്ദര്‍ശിക്കുക
{{fullurl:{{#special:Watchlist}}/edit}}

അഭിപ്രായം അറിയിക്കാനും മറ്റു സഹായങ്ങള്‍ക്കും:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'താള്‍ മായ്ക്കുക',
'confirm'                => 'സ്ഥിരീകരിക്കുക',
'excontent'              => "ഉള്ളടക്കം: '$1'",
'excontentauthor'        => "ഉള്ളടക്കം: '$1' ('[[Special:Contributions/$2|$2]]' മാത്രമേ ഈ താളില്‍ തിരുത്തല്‍ നടത്തിയിട്ടുള്ളൂ‍‌)",
'exbeforeblank'          => "ശൂന്യമാക്കപ്പെടുന്നതിനു മുമ്പുള്ള ഉള്ളടക്കം: '$1'",
'exblank'                => 'താള്‍ ശൂന്യമായിരുന്നു',
'delete-confirm'         => '"$1" മായ്ക്കുക',
'delete-legend'          => 'മായ്ക്കുക',
'historywarning'         => 'മുന്നറിയിപ്പ്: നിങ്ങള്‍ മായ്ക്കുവാന്‍ പോകുന്ന താള്‍ തിരുത്തല്‍ ചരിത്രം ഉള്ള ഒരു താളാണ്.',
'confirmdeletetext'      => 'നിങ്ങള്‍ ഒരു താള്‍ അതിന്റെ തിരുത്തല്‍ ചരിത്രമടക്കം മായ്ക്കുവാന്‍ പോവുകയാണ്. നിങ്ങള്‍ ചെയ്യുന്നതിന്റെ പരിണതഫലം നിങ്ങള്‍ക്കറിയാമെന്നും, നിങ്ങളുടെ ഈ മായ്ക്കല്‍ [[{{MediaWiki:Policy-url}}|വിക്കിയുടെ നയം]] അനുസരിച്ചാണു ചെയ്യുന്നതെന്നും ഉറപ്പാക്കുക.',
'actioncomplete'         => 'പ്രവൃത്തി പൂര്‍ത്തിയായിരിക്കുന്നു',
'actionfailed'           => 'പ്രവര്‍ത്തനം പരാജയപ്പെട്ടിരിക്കുന്നു',
'deletedtext'            => '"<nowiki>$1</nowiki>" മായ്ച്ചിരിക്കുന്നു. പുതിയതായി നടന്ന മായ്ക്കലുകളുടെ വിവരങ്ങള്‍ $2 ഉപയോഗിച്ച് കാണാം.',
'deletedarticle'         => '"[[$1]]" മായ്ച്ചിരിക്കുന്നു',
'dellogpage'             => 'മായ്ക്കല്‍ പട്ടിക',
'dellogpagetext'         => 'സമീപകാലത്ത് മായ്ച്ചുകളഞ്ഞ താളുകളുടെ പട്ടിക താഴെ കാണാം.',
'deletionlog'            => 'മായ്ക്കല്‍ പട്ടിക',
'reverted'               => 'പൂര്‍‌വ്വസ്ഥിതിയിലേക്കാക്കിയിരിക്കുന്നു.',
'deletecomment'          => 'നീക്കം ചെയ്യാനുള്ള കാരണം',
'deleteotherreason'      => 'മറ്റ്/കൂടുതല്‍ കാരണങ്ങള്‍:',
'deletereasonotherlist'  => 'മറ്റു കാരണങ്ങള്‍',
'deletereason-dropdown'  => '*മായ്ക്കാനുള്ള സാധാരണ കാരണങ്ങള്‍
** സ്രഷ്ടാവ് ആവശ്യപ്പെട്ടതു പ്രകാരം
** പകര്‍പ്പവകാശ ലംഘനം
** നശീകരണ പ്രവര്‍ത്തനം',
'delete-edit-reasonlist' => 'മായ്ക്കലിന്റെ കാരണം തിരുത്തുക',
'delete-toobig'          => 'ഈ താളിനു വളരെ വിപുലമായ തിരുത്തല്‍ ചരിത്രമുണ്ട്. $1 മേല്‍ {{PLURAL:$1|പതിപ്പുണ്ട്|പതിപ്പുകളുണ്ട്}}. ഇത്തരം താളുകള്‍ മായ്ക്കുന്നതു {{SITENAME}} സം‌രംഭത്തിന്റെ നിലനില്പ്പിനെ തന്നെ ബാധിക്കുമെന്നതിനാല്‍ ഈ താള്‍ മായ്ക്കുന്നതിനുള്ള അവകാശം പരിമിതപ്പെടുത്തിയിരിക്കുന്നു.',
'delete-warning-toobig'  => 'ഈ താളിനു വളരെ വിപുലമായ തിരുത്തല്‍ ചരിത്രമുണ്ട്. അതായത്, ഇതിനു് $1 മേല്‍ {{PLURAL:$1|പതിപ്പുണ്ട്|പതിപ്പുകളുണ്ട്}}. ഇത്തരം താളുകള്‍ മായ്ക്കുന്നതു {{SITENAME}} സം‌രംഭത്തിന്റെ ഡാറ്റാബേസ് ഓപ്പറേഷനെ ബാധിച്ചേക്കാം. അതിനാല്‍ വളരെ ശ്രദ്ധാപൂര്‍വ്വം തുടര്‍നടപടികളിലേക്കു നീങ്ങുക.',

# Rollback
'rollback'         => 'തിരുത്തലുകള്‍ റോള്‍ബാക്ക് ചെയ്യുക',
'rollback_short'   => 'റോള്‍ബാക്ക്',
'rollbacklink'     => 'റോള്‍ബാക്ക്',
'rollbackfailed'   => 'റോള്‍ബാക്ക് പരാജയപ്പെട്ടു',
'cantrollback'     => 'റോള്‍ ബാക്ക് ചെയ്യുവാന്‍ സാദ്ധ്യമല്ല. ഒരു ഉപയോക്താവ് മാത്രമാണു ഈ താളില്‍ സം‌ഭാവന ചെയ്തിരിക്കുന്നത്.',
'editcomment'      => "തിരുത്തലിന്റെ ചുരുക്കം: \"''\$1''\" എന്നായിരുന്നു.",
'revertpage'       => '[[Special:Contributions/$2|$2]] ([[User talk:$2|സന്ദേശങ്ങള്‍]]) നടത്തിയ തിരുത്തലുകള്‍ നീക്കം ചെയ്തിരിക്കുന്നു; നിലവിലുള്ള അവസ്ഥ [[User:$1|$1]] സൃഷ്ടിച്ചതാ‍ണ്',
'rollback-success' => '$1 ന്റെ തിരുത്തല്‍ തിരസ്ക്കരിച്ചിരിക്കുന്നു. $2 ചെയ്ത തൊട്ടു മുന്‍പത്തെ പതിപ്പിലേക്ക് സേവ് ചെയ്യുന്നു.',
'sessionfailure'   => 'താങ്കളുടെ ലോഗിൻ സെഷനിൽ പ്രശ്നങ്ങളുള്ളതായി കാണുന്നു;
സെഷൻ തട്ടിയിടുക്കൽ ഒഴിവാക്കാനുള്ള മുൻകരുതലായി ഈ പ്രവൃത്തി റദ്ദാക്കിയിരിക്കുന്നു.
ദയവായി പിന്നോട്ട് പോയി താങ്കൾ വന്ന താളിൽ ചെന്ന്, വീണ്ടും ശ്രമിക്കുക.',

# Protect
'protectlogpage'              => 'സംരക്ഷണ പ്രവര്‍ത്തനരേഖ',
'protectlogtext'              => 'താഴെ താളുകൾ സംരക്ഷിച്ചതിന്റേയും സംരക്ഷണം നീക്കിയതിന്റേയും പട്ടിക നൽകിയിരിക്കുന്നു.
ഇപ്പോൾ നിലവിലുള്ള എല്ലാ താൾ സംരക്ഷണവും കാണാൻ [[Special:ProtectedPages|സംരക്ഷിക്കപ്പെട്ട താളുകളുടെ പട്ടിക]] കാണുക.',
'protectedarticle'            => '"[[$1]]" സം‌രക്ഷിച്ചിരിക്കുന്നു',
'modifiedarticleprotection'   => '"[[$1]]" എന്ന താളിനുള്ള സം‌രക്ഷണമാനം മാറ്റിയിരിക്കുന്നു',
'unprotectedarticle'          => '"[[$1]]" സ്വതന്ത്രമാക്കി',
'protect-title'               => '"$1" നു സം‌രക്ഷണമാനം സജ്ജീകരിക്കുന്നു',
'prot_1movedto2'              => '[[$1]] എന്ന താളിന്റെ പേര്‍ [[$2]] എന്നാക്കിയിരിക്കുന്നു',
'protect-legend'              => 'സം‌രക്ഷണം സ്ഥിരീകരിക്കുക',
'protectcomment'              => 'കാരണം:',
'protectexpiry'               => 'സംരക്ഷണ കാലാവധി:',
'protect_expiry_invalid'      => 'കാലാവധി തീരുന്ന സമയം അസാധുവാണ്.',
'protect_expiry_old'          => 'കാലവധി തീരുന്ന സമയം ഭൂതകാലത്തിലാണ്.',
'protect-unchain'             => 'തലക്കെട്ടുമാറ്റാനുള്ള അനുമതികള്‍ പുനഃസ്ഥാപിക്കുക',
'protect-text'                => "താങ്കള്‍ക്ക് ഇവിടെ '''<nowiki>$1</nowiki>''' എന്ന താളിന്റെ നിലവിലുള്ള സംരക്ഷണമാനം ദര്‍ശിക്കുകയും അതില്‍ മാറ്റംവരുത്തുകയും ചെയ്യാം.",
'protect-locked-blocked'      => "തടയപ്പെട്ടിരിക്കുന്ന സമയത്ത് താങ്കള്‍ക്ക് സം‌രക്ഷണ പരിധി മാറ്റുവാന്‍ സാധിക്കില്ല. '''$1''' എന്ന താളിന്റെ നിലവിലുള്ള ക്രമീകരണം ഇതാണ്‌:",
'protect-locked-dblock'       => "ഡാറ്റാബേസ് ബന്ധിച്ചിരിക്കുന്നതു കാരണം താങ്കള്‍ക്കു സം‌രക്ഷണമാനം മാറ്റുവാന്‍ സാധിക്കില്ല.

'''$1''' എന്ന താളിന്റെ നിലവിലുള്ള ക്രമീകരണം ഇതാണ്‌:",
'protect-locked-access'       => "താളുകളുടെ സംരക്ഷണമാനത്തില്‍ വ്യതിയാനംവരുത്തുവാനുള്ള അനുമതി താങ്കളുടെ അക്കൗണ്ടിനില്ല.
'''$1''' എന്ന താളിന്റെ നിലവിലുള്ള ക്രമീകരണങ്ങള്‍ ഇതാ:",
'protect-cascadeon'           => 'ഈ താള്‍ നിര്‍ഝരിതസംരക്ഷിതമായ (cascading protection) {{PLURAL:$1|ഒരു താളില്‍|പല താളുകളില്‍}} ഉള്‍പ്പെടുത്തപ്പെടുത്തപ്പെട്ടിരിക്കുന്നതിനാല്‍ ഇത് സംരക്ഷിത താളാണ്. എന്നാല്‍ താങ്കള്‍ക്ക് ഈ താളിന്റെ സംരക്ഷണമാനം മാറ്റുവാന്‍ കഴിയും, അങ്ങനെ ചെയ്താല്‍ നിര്‍ഝരിതസംരക്ഷണത്തിനു മാറ്റം വരികയില്ല.',
'protect-default'             => 'എല്ലാ ഉപയോക്താക്കളെയും അനുവദിക്കുക',
'protect-fallback'            => '"$1" അനുവാദം ആവശ്യമാണ്‌',
'protect-level-autoconfirmed' => 'അംഗത്വമെടുക്കാത്ത ഉപയോക്താക്കളെ തടയുക',
'protect-level-sysop'         => 'സിസോപ്പുകള്‍ മാത്രം',
'protect-summary-cascade'     => 'നിര്‍ഝരിതം',
'protect-expiring'            => '$1 (UTC) നു കാലാവധി തീരുന്നു',
'protect-expiry-indefinite'   => 'അനിശ്ചിതം',
'protect-cascade'             => 'ഈ താളില്‍ ഉള്‍പ്പെട്ടിരിക്കുന്ന താളുകളെല്ലാം സംരക്ഷിക്കുക (നിര്‍ഝരിത സംരക്ഷണം)',
'protect-cantedit'            => 'ഈ താള്‍ തിരുത്തുവാനുള്ള അധികാരമില്ലാത്തതിനാല്‍ ഈ താളിന്റെ സംരക്ഷണമാനം മാറ്റുവാന്‍ താങ്കള്‍ക്ക് സാധിക്കുകയില്ല.',
'protect-othertime'           => 'മറ്റ് കാലാവധി:',
'protect-othertime-op'        => 'മറ്റു കാലയളവ്',
'protect-existing-expiry'     => 'നിലവിലെ കാലാവധി: $3, $2',
'protect-otherreason'         => 'മറ്റുള്ള/പുറമേയുള്ള കാരണം:',
'protect-otherreason-op'      => 'മറ്റു/കൂടുതല്‍ കാരണങ്ങള്‍',
'protect-dropdown'            => '*സംരക്ഷിക്കാനുള്ള കാരണങ്ങള്‍
** അമിതമായ നശീകരണപ്രവര്‍ത്തനങ്ങള്‍
** അമിതമായ സ്പാമിങ്ങ്
** Counter-productive edit warring
** High traffic page',
'protect-edit-reasonlist'     => 'സംരക്ഷണ കാരണങ്ങള്‍ തിരുത്തുക',
'protect-expiry-options'      => '1 മണിക്കൂര്‍:1 hour,1 ദിവസം:1 day,1 ആഴ്ച:1 week,2 ആഴ്ച:2 weeks,1 മാസം:1 month,3 മാസം:3 months,6 മാസം:6 months,1 വര്‍ഷം:1 year,അനന്തകാലം:infinite',
'restriction-type'            => 'അനുമതി:',
'restriction-level'           => 'പരിമിതിയുടേ മാനം:',
'minimum-size'                => 'കുറഞ്ഞ വലുപ്പം',
'maximum-size'                => 'പരമാവധി വലുപ്പം',
'pagesize'                    => '(ബൈറ്റ്സ്)',

# Restrictions (nouns)
'restriction-edit'   => 'തിരുത്തുക',
'restriction-move'   => 'തലക്കെട്ടു്‌ മാറ്റുക',
'restriction-create' => 'താള്‍ സൃഷ്ടിക്കുക',
'restriction-upload' => 'അപ്‌ലോഡ്',

# Restriction levels
'restriction-level-sysop'         => 'പൂര്‍ണ്ണമായി സം‌രക്ഷിച്ചിരിക്കുന്നു',
'restriction-level-autoconfirmed' => 'ഭാഗികമായി സം‌രക്ഷിച്ചിരിക്കുന്നു',
'restriction-level-all'           => 'ഏതു തലവും',

# Undelete
'undelete'                   => 'നീക്കംചെയ്ത താളുകള്‍ കാണുക',
'undeletepage'               => 'നീക്കം ചെയ്ത താളുകള്‍ കാണുകയും പുനഃസ്ഥാപിക്കുകയും ചെയ്യുക',
'undeletepagetitle'          => "'''[[:$1|$1]] - എന്ന താളിന്റെ നീക്കം ചെയ്ത പതിപ്പുകളാണ് താഴെക്കൊടുത്തിരിക്കുന്നത്'''.",
'viewdeletedpage'            => 'നീക്കം ചെയ്ത താളുകള്‍ കാണുക',
'undeletepagetext'           => 'താഴെ കാണിച്ചിരിക്കുന്ന താളുകള്‍ മായ്ക്കപ്പെട്ടതാണെങ്കിലും പത്തായത്തിലുള്ളതിനാല്‍ പുനഃസ്ഥാപിക്കാവുന്നതാണ്‌. പത്തായം സമയാസമയങ്ങളില്‍ വൃത്തിയാക്കുന്നതാണ്‌.',
'undeleterevisions'          => '$1 {{PLURAL:$1|പതിപ്പ്|പതിപ്പുകള്‍}} പത്തായത്തിലാക്കി',
'undeletehistorynoadmin'     => 'ഈ താള്‍ മായ്ക്കപ്പെട്ടിരിക്കുന്നു. ഈ താള്‍ മായ്കാനുള്ള കാരണവും താള്‍ മായ്ക്കുന്നതിനു മുന്‍പ് തിരുത്തിയവരെ കുറിച്ചുള്ള വിവരങ്ങളും, താഴെ കൊടുത്തിരിക്കുന്നു. മായ്ക്കപ്പെട്ട ഈ പതിപ്പുകളുടെ ഉള്ളടക്കം അഡ്മിനിസ്റ്റ്രേറ്ററുമാര്‍ക്ക് മാത്രമേ പ്രാപ്യമാകൂ.',
'undelete-revision'          => '$1 താളിന്റെ ($4, $5 -ലെ) $3 സൃഷ്ടിച്ച പതിപ്പ് മായ്ച്ചിരിക്കുന്നു:',
'undeleterevision-missing'   => 'അസാധുവായ അല്ലെങ്കില്‍ നഷ്ടപ്പെട്ട പതിപ്പ്. നിങ്ങളുടെ കണ്ണി ഒന്നുകില്‍ തെറ്റായായിരിക്കാം അല്ലെങ്കില്‍ ഒഴിവാക്കപ്പെട്ട ഒരു പതിപ്പായിരിക്കും താങ്കള്‍ തിരയുന്നത്.',
'undelete-nodiff'            => 'പഴയ പതിപ്പുകള്‍ ഒന്നും കണ്ടില്ല.',
'undeletebtn'                => 'പുനഃസ്ഥാപിക്കുക',
'undeletelink'               => 'കാണുക/പുനഃസ്ഥാപിക്കുക',
'undeleteviewlink'           => 'കാണുക',
'undeletereset'              => 'പുനഃക്രമീകരിക്കുക',
'undeleteinvert'             => 'വിപരീതം തിരഞ്ഞെടുക്കുക',
'undeletecomment'            => 'കുറിപ്പ്:',
'undeletedarticle'           => '"[[$1]]" പുനഃസ്ഥാപിച്ചു',
'undeletedrevisions'         => '{{PLURAL:$1|1 പതിപ്പ്|$1 പതിപ്പുകള്‍}} പുനഃസ്ഥാപിച്ചിരിക്കുന്നു',
'undeletedrevisions-files'   => '{{PLURAL:$1|1 പതിപ്പും|$1 പതിപ്പുകളും}} {{PLURAL:$2|1 പ്രമാണവും|$2 പ്രമാണങ്ങളും}} പുനഃസ്ഥാപിച്ചിരിക്കുന്നു',
'undeletedfiles'             => '{{PLURAL:$1|1 പ്രമാണം|$1 പ്രമാണങ്ങള്‍}} പുനഃസ്ഥാപിച്ചു',
'cannotundelete'             => 'മായ്ക്കല്‍ തിരസ്ക്കരിക്കാനുള്ള ശ്രമം പരാജയപ്പെട്ടു. മറ്റാരെങ്കിലും ഇതിനു മുന്‍പ് മായ്ക്കല്‍ തിരസ്ക്കരിച്ചിരിക്കാം.',
'undeletedpage'              => "<big>'''$1 പുനഃസ്ഥാപിച്ചിരിക്കുന്നു'''</big>

പുതിയതായി നടന്ന ഒഴിവാക്കലുകളുടേയും പുനഃസ്ഥാപനങ്ങളുടേയും വിവരങ്ങള്‍ കാണാന്‍ [[Special:Log/delete|മായ്ക്കല്‍ ലോഗ്]] കാണുക.",
'undelete-header'            => 'അടുത്തകാലത്ത് നീക്കംചെയ്ത താളുകളുടെ പട്ടികയ്ക്ക് [[Special:Log/delete|നീക്കം ചെയ്യല്‍ പ്രവര്‍ത്തനരേഖ]] കാണുക.',
'undelete-search-box'        => 'നീക്കംചെയ്ത താളുകളില്‍ തിരയുക',
'undelete-search-prefix'     => 'ഈ വാക്കില്‍ തുടങ്ങുന്ന താളുകള്‍ കാണിക്കുക:',
'undelete-search-submit'     => 'തിരയൂ',
'undelete-filename-mismatch' => '$1 എന്ന സമയത്തുണ്ടാക്കിയ പതിപ്പിന്റെ മായ്ക്കുല്‍ തിരസ്ക്കരിക്കുവാന്‍ സാധിച്ചില്ല: പ്രമാണത്തിന്റെ പേരു യോജിക്കുന്നില്ല',
'undelete-bad-store-key'     => '$1 സമയത്തുണ്ടാക്കിയ പതിപ്പിന്റെ മായ്ക്കുല്‍ തിരസ്ക്കരിക്കുവാന്‍ സാധിച്ചില്ല: മായ്ക്കുന്നതിനു മുന്‍പേ പ്രമാണം അപ്രത്യക്ഷമായിരിക്കുന്നു.',
'undelete-cleanup-error'     => 'ഉപയോഗത്തിലില്ലാത്ത "$1" എന്ന പ്രമാണം മായ്ക്കുന്നതില്‍ പിഴവ് സംഭവിച്ചു.',
'undelete-error-short'       => 'ഈ പ്രമാണത്തിന്റെ മായ്ക്കല്‍ തിരസ്ക്കരിക്കുന്നതില്‍ പിഴവ്: $1',
'undelete-error-long'        => 'ഈ പ്രമാണം പുനഃസ്ഥാപിക്കുവാന്‍ ശ്രമിക്കുമ്പോള്‍ പിഴവുകള്‍ സംഭവിച്ചു:

$1',
'undelete-show-file-submit'  => 'ശരി',

# Namespace form on various pages
'namespace'      => 'നെയിംസ്പേസ്:',
'invert'         => 'വിപരീതം തിരഞ്ഞെടുക്കുക',
'blanknamespace' => '(മുഖ്യം)',

# Contributions
'contributions'       => 'ഉപയോക്താവിന്റെ സംഭാവനകള്‍',
'contributions-title' => '$1 എന്ന ഉപയോക്താവിന്റെ സംഭാവനകള്‍',
'mycontris'           => 'എന്റെ സംഭാവനകള്‍',
'contribsub2'         => '$1 എന്ന ഉപയോക്താവിന്റെ $2.',
'nocontribs'          => 'ഈ ക്രൈറ്റീരിയകളുമായി യോജിക്കുന്ന മാറ്റങ്ങള്‍ ഒന്നും കണ്ടില്ല.',
'uctop'               => '(അവസാനത്തെ തിരുത്തല്‍)',
'month'               => 'മാസം:',
'year'                => 'വര്‍ഷം:',

'sp-contributions-newbies'        => 'പുതിയ അംഗങ്ങള്‍ നടത്തിയ തിരുത്തലുകള്‍ മാത്രം',
'sp-contributions-newbies-sub'    => 'പുതിയ അക്കൗണ്ടുകള്‍ക്ക്',
'sp-contributions-newbies-title'  => 'പുതിയ അംഗത്വമെടുത്ത ഉപയോക്താക്കളുടെ സേവനങ്ങൾ',
'sp-contributions-blocklog'       => 'തടയല്‍ പട്ടിക',
'sp-contributions-deleted'        => 'മായ്ക്കപ്പെട്ട ഉപയോക്തൃസംഭാവനകള്‍',
'sp-contributions-logs'           => 'പ്രവര്‍ത്തനരേഖകള്‍',
'sp-contributions-talk'           => 'സംവാദം',
'sp-contributions-userrights'     => 'ഉപയോക്തൃ അവകാശങ്ങളുടെ പരിപാലനം',
'sp-contributions-blocked-notice' => 'ഈ ഉപയോക്താവ് ഇപ്പോൾ തടയപ്പെട്ടിരിക്കുകയാണ്. അവലംബമായി തടയൽ രേഖയുടെ പുതിയ ഭാഗം താഴെ കൊടുത്തിരിക്കുന്നു:',
'sp-contributions-search'         => 'ചെയ്ത സേവനങ്ങള്‍',
'sp-contributions-username'       => 'ഐപി വിലാസം അഥവാ ഉപയോക്തൃനാമം:',
'sp-contributions-submit'         => 'തിരയൂ',

# What links here
'whatlinkshere'            => 'അനുബന്ധകണ്ണികള്‍',
'whatlinkshere-title'      => '"$1" എന്ന താളിലേക്കുള്ള കണ്ണികള്‍',
'whatlinkshere-page'       => 'താള്‍:',
'linkshere'                => "താഴെക്കൊടുത്തിരിക്കുന്ന താളുകളില്‍ നിന്നും '''[[:$1]]''' എന്ന താളിലേക്ക് കണ്ണികളുണ്ട്:",
'nolinkshere'              => "'''[[:$1]]''' എന്ന താളിലേക്ക് കണ്ണികളൊന്നും നിലവിലില്ല.",
'nolinkshere-ns'           => "തിരഞ്ഞെടുത്ത നെയിംസ്പേസില്‍ '''[[:$1]]''' എന്ന താളിലേക്ക് മറ്റൊരു താളുകളില്‍നിന്നും കണ്ണികളില്ല.",
'isredirect'               => 'തിരിച്ചുവിടല്‍ താള്‍',
'istemplate'               => 'ഉള്‍പ്പെടുത്തല്‍',
'isimage'                  => 'ചിത്രത്തിന്റെ കണ്ണി',
'whatlinkshere-prev'       => '{{PLURAL:$1|മുന്‍പത്തെ ‍|മുന്‍പത്തെ $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|അടുത്ത|അടുത്ത $1}}',
'whatlinkshere-links'      => '← കണ്ണികള്‍',
'whatlinkshere-hideredirs' => '$1 തിരിച്ചുവിടലുകള്‍',
'whatlinkshere-hidetrans'  => '$1 ഉള്‍പ്പെടുത്തലുകള്‍',
'whatlinkshere-hidelinks'  => '$1 കണ്ണികള്‍',
'whatlinkshere-hideimages' => 'ചിത്രങ്ങളിൽ നിന്ന് $1 കണ്ണികൾ',
'whatlinkshere-filters'    => 'അരിപ്പകള്‍',

# Block/unblock
'blockip'                      => 'ഉപയോക്താവിനെ വിലക്കുക',
'blockip-legend'               => 'ഉപയോക്താവിനെ തടയുക',
'blockiptext'                  => 'ഏതെങ്കിലും ഐപി വിലാസത്തേയോ ഉപയോക്താവിനേയോ തടയുവാന്‍ താഴെയുള്ള ഫോം ഉപയോഗിക്കുക.
[[{{MediaWiki:Policy-url}}|വിക്കിയുടെ നയം]] അനുസരിച്ച് നശീകരണപ്രവര്‍ത്തനം തടയാന്‍ മാത്രമേ ഇതു ചെയ്യാവൂ.
തടയാനുള്ള വ്യക്തമായ കാരണം (ഏതു താളിലാണു നശീകരണപ്രവര്‍ത്തനം നടന്നത് എന്നതടക്കം) താഴെ രേഖപ്പെടുത്തിയിരിക്കണം.',
'ipaddress'                    => 'ഐപി വിലാസം:',
'ipadressorusername'           => 'ഐപി വിലാസം അല്ലെങ്കില്‍ ഉപയോക്തൃനാമം:',
'ipbexpiry'                    => 'കാലാവധി:',
'ipbreason'                    => 'കാരണം:',
'ipbreasonotherlist'           => 'മറ്റു കാരണം',
'ipbreason-dropdown'           => '*തടയലിനു യോഗ്യമായ കാരണങ്ങള്‍
** തെറ്റായ വിവരങ്ങള്‍ ചേര്‍ക്കുക
** താളില്‍ നിന്നു വിവരങ്ങള്‍ മായ്ക്കുക
** പുറം വെബ്ബ്സൈറ്റിലേക്കുള്ള സ്പാം കണ്ണികള്‍ ചേര്‍ക്കല്‍
** അനാവശ്യം/അസംബന്ധം താളിലേക്കു ചേര്‍ക്കല്‍
** മാന്യമല്ലാത്ത പെരുമാറ്റം
** ദുരുദ്ദേശത്തോടെ ഉപയോഗിക്കുന്ന മള്‍ട്ടിപ്പിള്‍ അക്കൗണ്ടുകള്‍
** വിക്കിക്കു ചേരാത്ത ഉപയോക്തൃനാമം',
'ipbanononly'                  => 'അജ്ഞാത ഉപയോക്താക്കളെ മാത്രം തടയുക',
'ipbcreateaccount'             => 'അക്കൗണ്ട് സൃഷ്ടിക്കുന്നത് തടയുക',
'ipbemailban'                  => 'ഇമെയില്‍ അയക്കുന്നതില്‍ നിന്നു ഉപയോക്താവിനെ തടയുക',
'ipbenableautoblock'           => 'ഈ ഉപയോക്താവ് അവസാനം ഉപയോഗിച്ച ഐപിയും തുടര്‍ന്ന് ഉപയോഗിക്കാന്‍ സാദ്ധ്യതയുള്ള ഐപികളും യാന്ത്രികമായി തടയുക',
'ipbsubmit'                    => 'ഈ ഉപയോക്താവിനെ തടയുക',
'ipbother'                     => 'മറ്റ് കാലാവധി:',
'ipboptions'                   => '2 മണിക്കൂര്‍ നേരത്തേയ്ക്ക്:2 hours,1 ദിവസത്തേയ്ക്ക്:1 day,3 ദിവസത്തേയ്ക്ക്:3 days,1 ആഴ്ചത്തേയ്ക്ക്:1 week,2 ആഴ്ചത്തേയ്ക്ക്:2 weeks,1 മാസത്തേയ്ക്ക്:1 month,3 മാസത്തേയ്ക്ക്:3 months,6 മാസത്തേയ്ക്ക്:6 months,1 വര്‍ഷത്തേയ്ക്ക്:1 year,അനന്തകാലത്തേയ്ക്ക്:infinite',
'ipbotheroption'               => 'മറ്റുള്ളവ',
'ipbotherreason'               => 'മറ്റ്/കൂടുതല്‍ കാരണം:',
'ipbhidename'                  => 'തിരുത്തലുകള്‍, പട്ടികകള്‍ എന്നിവയില്‍ നിന്നും ഉപയോക്തൃനാമം മറയ്ക്കുക',
'ipbwatchuser'                 => 'ഈ ഉപയോക്താവിന്റെ താളും സംവാദം താളും ശ്രദ്ധിക്കുക',
'badipaddress'                 => 'അസാധുവായ ഐപി വിലാസം.',
'blockipsuccesssub'            => 'തടയല്‍ വിജയിച്ചിരിക്കുന്നു',
'blockipsuccesstext'           => '[[Special:Contributions/$1|$1]]നെ തടഞ്ഞിരിക്കുന്നു.<br />
തടയല്‍ പുനഃപരിശോധിക്കാന്‍ [[Special:IPBlockList|IP block list]] കാണുക.',
'ipb-edit-dropdown'            => 'തടഞ്ഞതിന്റെ കാരണം തിരുത്തുക',
'ipb-unblock-addr'             => '$1 അംഗത്വത്തെ അണ്‍‌ബ്ലോക്ക് ചെയ്യുക',
'ipb-unblock'                  => 'ഒരു ഐപി വിലാസത്തേയോ ഉപയോക്താവിനേയോ അണ്‍‌ബ്ലോക്ക് ചെയ്യുക',
'ipb-blocklist-addr'           => '$1 ന് നിലവിലുള്ള വിലക്കുകള്‍',
'ipb-blocklist'                => 'നിലവിലുള്ള ബ്ലോക്കുകള്‍',
'ipb-blocklist-contribs'       => '$1-ന്റെ സംഭാവനകള്‍',
'unblockip'                    => 'ഉപയോക്താവിനെ അണ്‍ബ്ലോക്ക് ചെയ്യുക',
'unblockiptext'                => 'മുന്‍പ് ബ്ലോക്ക് ചെയ്യപ്പെട്ട ഐപിയുടേയും ഉപയോക്തയാവിന്റേയും തിരുത്തല്‍ അവകാശം പുനഃസ്ഥാപിക്കാന്‍ താഴെയുള്ള ഫോം ഉപയോഗിക്കുക.',
'ipusubmit'                    => 'ഈ വിലക്ക് ഒഴിവാക്കുക',
'unblocked'                    => '[[User:$1|$1]] എന്ന ഉപയോക്താവിനെ അണ്‍‌ബ്ലോക്ക് ചെയ്തിരിക്കുന്നു',
'unblocked-id'                 => '$1 എന്ന തടയല്‍ നീക്കം ചെയ്തിരിക്കുന്നു',
'ipblocklist'                  => 'തടയപ്പെട്ട ഐ.പി. വിലാസങ്ങളും ഉപയോക്താക്കളും',
'ipblocklist-legend'           => 'തടഞ്ഞ ഒരു ഉപയോക്താവിനെ തിരയുക',
'ipblocklist-username'         => 'ഉപയോക്തൃനാമം അല്ലെങ്കില്‍ ഐപി വിലാസം:',
'ipblocklist-sh-userblocks'    => '$1 അംഗത്വ വിലക്കുകള്‍',
'ipblocklist-sh-tempblocks'    => '$1 താല്‍ക്കാലിക വിലക്കുകള്‍',
'ipblocklist-sh-addressblocks' => '$1 ഏക ഐ.പി. വിലക്കുകള്‍',
'ipblocklist-submit'           => 'തിരയൂ',
'infiniteblock'                => 'അനിശ്ചിത',
'expiringblock'                => '$1 $2 നു കാലാവധി തീരുന്നു',
'anononlyblock'                => 'അജ്ഞാത ഉപയോക്താക്കളെ മാത്രം',
'noautoblockblock'             => 'യാന്ത്രികതടയല്‍ ഒഴിവാക്കിയിരിക്കുന്നു',
'createaccountblock'           => 'അക്കൗണ്ട് സൃഷ്ടിക്കുന്നതില്‍നിന്ന് തടഞ്ഞിരിക്കുന്നു',
'emailblock'                   => 'ഇമെയില്‍ ഉപയോഗിക്കുന്നതു തടഞ്ഞിരിക്കുന്നു',
'blocklist-nousertalk'         => 'സ്വന്തം സം‌വാദ താളില്‍ തിരുത്താന്‍ സാധിക്കില്ല',
'ipblocklist-empty'            => 'തടയല്‍‌പ്പട്ടിക ശൂന്യമാണ്‌.',
'ipblocklist-no-results'       => 'ഈ ഐപി വിലാസമോ ഉപയോക്തൃനാമമോ ബ്ലോക്ക് ചെയ്തിട്ടില്ല.',
'blocklink'                    => 'തടയുക',
'unblocklink'                  => 'സ്വതന്ത്രമാക്കുക',
'change-blocklink'             => 'തടയലില്‍ മാറ്റം വരുത്തുക',
'contribslink'                 => 'സംഭാവനകള്‍',
'autoblocker'                  => 'താങ്കളുടെ ഐപി വിലാസം "[[User:$1|$1]]" എന്ന ഉപയോക്താവ് ഈ അടുത്ത് ഉപയോഗിക്കുകയും പ്രസ്തുത ഉപയോക്താവിനെ വിക്കിയില്‍ നിന്നു തടയുകയും ചെയ്തിട്ടുള്ളതാണ്‌. അതിനാല്‍ താങ്കളും യാന്ത്രികമായി തടയപ്പെട്ടിരിക്കുന്നു. $1ന്റെ തടയലിനു സൂചിപ്പിക്കപ്പെട്ട കാരണം "$2" ആണ്‌.',
'blocklogpage'                 => 'തടയല്‍ പട്ടിക',
'blocklogentry'                => '[[$1]]-നെ $2 കാലത്തേക്കു വിലക്കിയിരിക്കുന്നു $3',
'blocklogtext'                 => '{{SITENAME}} സംരംഭത്തില്‍ പ്രവര്‍ത്തിക്കുന്നതില്‍ നിന്ന് ഉപയോക്താക്കളെ തടഞ്ഞതിന്റേയും, പുനഃപ്രവര്‍ത്തനാനുമതി നല്‍കിയതിന്റേയും രേഖകള്‍ താഴെ കാണാം. {{SITENAME}} സംരംഭം സ്വയം  തടയുന്ന ഐപി വിലാസങ്ങള്‍ ഈ പട്ടികയില്‍ ഇല്ല. [[Special:IPBlockList|തടയപ്പെട്ടിട്ടുള്ള ഐപി വിലാസങ്ങളുടെ പട്ടിക]] എന്നതാളില്‍ നിലവിലുള്ള നിരോധനങ്ങളേയും തടയലുകളേയും കാണാവുന്നതാണ്.',
'unblocklogentry'              => '$1 എന്ന ഉപയോക്താവിനെ പുനഃസ്ഥാപിച്ചിരിക്കുന്നു',
'block-log-flags-anononly'     => 'അജ്ഞാത ഉപയോക്താക്കളെ മാത്രം',
'block-log-flags-nocreate'     => 'അക്കൗണ്ട് സൃഷ്ടിക്കുന്നതും തടഞ്ഞിരിക്കുന്നു',
'block-log-flags-noautoblock'  => 'യാന്ത്രികബ്ലോക്ക് ദുര്‍ബലപ്പെടുത്തിയിരിക്കുന്നു',
'block-log-flags-noemail'      => 'ഇമെയില്‍ അയയ്ക്കുന്നത് തടഞ്ഞിരിക്കുന്നു',
'block-log-flags-nousertalk'   => 'സ്വന്തം സംവാദം താളിൽ തിരുത്താനനുവാദമില്ല',
'block-log-flags-hiddenname'   => 'ഉപയോക്തൃനാമം മറയ്ക്കപ്പെട്ടിരിക്കുന്നു',
'range_block_disabled'         => 'സിസോപ്പിനു റേഞ്ച് ബ്ലോക്കു ചെയ്യാനുള്ള സൗകര്യം ദുര്‍ബലപ്പെടുത്തുക.',
'ipb_expiry_invalid'           => 'കാലാവധി സമയം അസാധുവാണ്‌.',
'ipb_already_blocked'          => '"$1" ഇതിനകം തന്നെ തടയപ്പെട്ടിരിക്കുന്നു.',
'ipb-needreblock'              => '== നിലവിൽ തടയപ്പെട്ടതാണ് ==
$1 നിലവിൽ തടയപ്പെട്ടതാണ്.<br />
താങ്കൾ സജ്ജീകരണത്തിൽ മാറ്റം വരുത്തുവാൻ ഉദ്ദേശിക്കുന്നുണ്ടോ?',
'ipb_cant_unblock'             => 'പിഴവ്: $1 എന്ന തടയല്‍ ഐഡി കാണുന്നില്ല. ഇതിനകം അതിനെ അണ്‍‌ബ്ലോക്ക് ചെയ്തിരിക്കാം.',
'ipb_blocked_as_range'         => 'പിഴവ്:  $1 എന്ന ഐപിയെ നേരിട്ടല്ല ബ്ലോക്കിയിട്ടുള്ളത്. അതിനാല്‍ അണ്‍ബ്ലോക്ക് ചെയ്യുവാന്‍ സാദ്ധ്യമല്ല. അതിനെ $2ന്റെ ഭാഗമായുള്ള റേഞ്ചില്‍ ആണ്‌ ബ്ലോക്കിയിട്ടുള്ളത്. അതിനാല്‍ $2നെ അണ്‍ബ്ലോക്ക് ചെയ്താല്‍ $1ഉം അണ്‍‌ബ്ലോക്ക് ആവും.',
'ip_range_invalid'             => 'അസാധുവായ ഐപി റേഞ്ച്.',
'blockme'                      => 'എന്നെ തടയുക',
'proxyblocker'                 => 'പ്രോക്സി തടയല്‍',
'proxyblocker-disabled'        => 'ഈ പ്രക്രിയ അനുവദനീയമല്ല.',
'proxyblockreason'             => 'ഓപ്പണ്‍ പ്രോക്സി ആയതിനാല്‍ നിങ്ങളുടെ ഐപി വിലാസത്തെ ബ്ലോക്കിയിരിക്കുന്നു. ഇതു എന്തെങ്കിലും പിഴവ് മൂലം സംഭവിച്ചതാണെങ്കില്‍ നിങ്ങളുടെ ഇന്റര്‍നെറ്റ് സേവന ദാതാവിനെ സമീപിച്ചു ഈ സെക്യൂരിറ്റി പ്രശ്നത്തെ കുറിച്ച് ബോധിപ്പിക്കുക.',
'proxyblocksuccess'            => 'ചെയ്തു കഴിഞ്ഞു.',
'sorbsreason'                  => '{{SITENAME}} സം‌രംഭം ഉപയോഗിക്കുന്ന DNSBL ല്‍ താങ്കലുടെ ഐപി വിലാസം ഒരു ഓപ്പണ്‍ പ്രോക്സിയായാണു രേഖപ്പെടുത്തിട്ടുള്ളത്.',
'sorbs_create_account_reason'  => '{{SITENAME}} സം‌രംഭം ഉപയോഗിക്കുന്ന DNSBL ല്‍ താങ്കലുടെ ഐപി വിലാസം ഒരു ഓപ്പണ്‍ പ്രോക്സിയായാണു രേഖപ്പെടുത്തിട്ടുള്ളത്. താങ്കള്‍ക്ക് അക്കൗണ്ട് സൃഷ്ടിക്കുവാന്‍ സാദ്ധ്യമല്ല.',

# Developer tools
'lockdb'              => 'ഡാറ്റാബേസ് ബന്ധിക്കുക',
'unlockdb'            => 'ഡാറ്റാബേസ് സ്വതന്ത്രമാക്കുക',
'lockconfirm'         => 'അതെ എനിക്കു തീര്‍ച്ചയായും ഡാറ്റബേസിനെ ബന്ധിക്കണം.',
'unlockconfirm'       => 'അതെ എനിക്കു തീര്‍ച്ചയായും ഡാറ്റാബേസിനെ സ്വതന്ത്രമാക്കണം.',
'lockbtn'             => 'ഡാറ്റാബേസ് ബന്ധിക്കുക',
'unlockbtn'           => 'ഡാറ്റാബേസ് സ്വതന്ത്രമാക്കുക',
'locknoconfirm'       => 'നിങ്ങള്‍ സ്ഥിരീകരണ പെട്ടി തിരഞ്ഞെടുത്തില്ല.',
'lockdbsuccesssub'    => 'ഡാറ്റാബേസ് ബന്ധിക്കുവാന്‍ സാധിച്ചില്ല',
'unlockdbsuccesssub'  => 'ഡാറ്റാബേസ് സ്വതന്ത്രമാക്കിയിരിക്കുന്നു',
'lockdbsuccesstext'   => 'ഡാറ്റാബേസ് ബന്ധിച്ചിരിക്കുന്നു.<br />
ശുദ്ധീകരണപ്രവര്‍ത്തനം കഴിഞ്ഞതിനു ശേഷം [[Special:UnlockDB|ഈ കണ്ണിയുപയോഗിച്ച്]] ഡാറ്റാബേസ് സ്വതന്ത്രമാക്കുക.',
'unlockdbsuccesstext' => 'ഡാറ്റാബേസ് സ്വതന്ത്രമാക്കിയിരിക്കുന്നു.',
'databasenotlocked'   => 'ഡാറ്റാബേസ് ബന്ധിച്ചിട്ടില്ല.',

# Move page
'move-page'                    => '$1 മാറ്റുക',
'move-page-legend'             => 'താള്‍ മാറ്റുക',
'movepagetext'                 => "താഴെയുള്ള ഫോം ഒരു താളിനെ പുനര്‍നാമകരണം ചെയ്യാനുള്ളതാണ്.
താളിന്റെ പഴയരൂപങ്ങളും ഈ മാറ്റത്തിന് വിധേയമാക്കപ്പെടും.
പഴയ തലക്കെട്ട്, പുതിയ തലക്കെട്ടുള്ള താളിലേക്കുള്ള ഒരു തിരിച്ചുവിടല്‍ താളായി മാറും.
പഴയതാളിലേക്കുള്ള ലിങ്കുകള്‍ ഈ മാറ്റത്തില്‍ മാറുകയില്ല.
[[Special:DoubleRedirects|ഇരട്ട തിരിച്ചുവിടലുകളോ]], [[Special:BrokenRedirects|ഫലപ്രദമല്ലാത്ത തിരിച്ചുവിടലുകളോ]] ഉണ്ടാകുന്നുണ്ടോയെന്ന് ദയവായി പരിശോധിക്കുക.
ലിങ്കുകള്‍ ശരിയായി പ്രവര്‍ത്തിക്കുന്നുണ്ടോ എന്ന് പരിശോധിച്ച് ഉറപ്പു വരുത്തേണ്ടത് താങ്കളുടെ ചുമതലയാണ്. 

താങ്കള്‍ പുതിയതായി ഉദ്ദേശിക്കുന്ന തലക്കെട്ടില്‍ ഒരു താള്‍ നേരത്തേ നിലവിലുണ്ടെങ്കില്‍ '''പുനര്‍നാമകരണം സാധിക്കില്ല'''.
അല്ലെങ്കില്‍ അതൊരു തിരിച്ചുവിടല്‍ താളോ, ശൂന്യമായ താളോ അതിനു മറ്റു പഴയരൂപങ്ങള്‍ ഇല്ലാതിരിക്കുകയോ ചെയ്യണം.
അതായത് താങ്കള്‍ ഒരു താള്‍ തെറ്റായി പുനര്‍നാമകരണം ചെയ്താല്‍ മാത്രമേ അതിനേ തിരിച്ചാക്കാന്‍ സാധിക്കുകയുള്ളു.
നിലവിലുള്ള ഒരു താളിന്റെ മുകളില്‍ അതേ തലക്കെട്ടില്‍ മറ്റൊരു താളുണ്ടാക്കാന്‍ സാധിക്കില്ല.

'''മുന്നറിയിപ്പ്!:'''
ഈ പ്രവൃത്തി ഒരു നല്ലതാളില്‍ അപ്രതീക്ഷിതവും, ഉഗ്രവുമായി തീര്‍ന്നേക്കാം.
മുന്നോട്ടു പോകുന്നതിനു മുമ്പ് താങ്കള്‍ ചെയ്യുന്നതെന്താണെന്ന് വ്യക്തമായി മനസ്സിലാക്കുക.",
'movepagetalktext'             => "'''ബന്ധപ്പെട്ട സം‌വാദംതാളും സ്വയം മാറ്റപ്പെടാതിരിക്കാനുള്ള കാരണങ്ങള്‍'''
*അതേ പേരില്‍ തന്നെ ശൂന്യമല്ലാത്ത ഒരു സം‌വാദതാള്‍ നിലവിലുണ്ടെങ്കില്‍, അഥവാ
*താങ്കള്‍ താഴെയുള്ള ചെക്‍ബോക്സ് ഉപയോഗിച്ചിട്ടില്ലങ്കില്‍

അത്തരം സന്ദര്‍ഭങ്ങളില്‍ സം‌വാദം താളുകള്‍ താങ്കള്‍ സ്വയം കൂട്ടിച്ചേര്‍ക്കേണ്ടതാണ്.",
'movearticle'                  => 'മാറ്റേണ്ട താള്‍',
'movenologin'                  => 'ലോഗിന്‍ ചെയ്തിട്ടില്ല',
'movenologintext'              => 'തലക്കെട്ടു മാറ്റാനുള്ള അനുമതി കൈവരിക്കാന്‍ താങ്കള്‍ ഒരു രജിസ്റ്റേര്‍ഡ് ഉപയോക്താവായിരിക്കുകയും [[Special:UserLogin|ലോഗിന്‍ ചെയ്തിരിക്കുകയും]] ചെയ്യേണ്ടത് അത്യന്താപേക്ഷിതമാണ്‌.',
'movenotallowed'               => 'താളുകളുടെ തലക്കെട്ടു മാറ്റുവാനുള്ള അനുവാദം താങ്കള്‍ക്കില്ല.',
'movenotallowedfile'           => 'പ്രമാണങ്ങള്‍ മാറ്റാനുള്ള അനുമതി താങ്കള്‍ക്കില്ല.',
'cant-move-user-page'          => 'ഉപയോക്താവിനുള്ള താളുകളുടെ തലക്കെട്ട് മാറ്റാനുള്ള അനുമതി താങ്കള്‍ക്കില്ല (ഉപതാളുകള്‍ ഉള്‍പ്പെടുന്നില്ല).',
'cant-move-to-user-page'       => 'ഉപയോക്താവിനുള്ള താളിന്റെ തലക്കെട്ടു മാറ്റാനുള്ള അനുമതി താങ്കള്‍ക്കില്ല (ഉപയോക്താവിനുള്ള ഉപതാളുകള്‍ ഒഴിച്ച്).',
'newtitle'                     => 'പുതിയ തലക്കെട്ട്',
'move-watch'                   => 'ഈ താളിലെ മാറ്റങ്ങള്‍ ശ്രദ്ധിക്കുക',
'movepagebtn'                  => 'മാറ്റുക',
'pagemovedsub'                 => 'തലക്കെട്ടു മാറ്റം വിജയിച്ചിരിക്കുന്നു',
'movepage-moved'               => '<big>\'\'\'"$1" എന്ന ലേഖനം "$2" എന്ന തലക്കെട്ടിലേക്ക് മാറ്റിയിരിക്കുന്നു\'\'\'</big>',
'movepage-moved-redirect'      => 'ഒരു തിരിച്ചുവിടല്‍ സൃഷ്ടിച്ചിരിക്കുന്നു.',
'articleexists'                => 'ഈ പേരില്‍ മറ്റൊരു താള്‍ ഉള്ളതായി കാണുന്നു, അല്ലെങ്കില്‍ നിങ്ങള്‍ തിരഞ്ഞെടുത്ത തലക്കെട്ട് സ്വീകാര്യമല്ല. ദയവായി മറ്റൊരു തലക്കെട്ട് തിരഞ്ഞെടുക്കുക.',
'cantmove-titleprotected'      => 'താള്‍ സൃഷ്ടിക്കുന്നതിനു നിരോധനം ഏര്‍പ്പെടുത്തിയിട്ടുള്ള ഒരു തലക്കെട്ടു താങ്കള്‍ തിരഞ്ഞെടുത്ത കാരണം നിങ്ങള്‍ക്ക് താള്‍ ആ സ്ഥാനത്തേക്കു മാറ്റുവാന്‍ സാധിക്കില്ല.',
'talkexists'                   => "'''താളിന്റെ തലക്കെട്ട് വിജയകരമായി മാറ്റിയിരിക്കുന്നു. പക്ഷെ താളിന്റെ സംവാദത്തിനു അതേ പേരില്‍ മറ്റൊരു സംവാദംതാള്‍ നിലവിലുള്ളതിനാല്‍ മാറ്റം സാധിച്ചില്ല. അതിനാല്‍ സംവാദംതാള്‍ താങ്കള്‍ തന്നെ സംയോജിപ്പിക്കുക.'''",
'movedto'                      => 'ഇവിടേക്ക് മാറ്റിയിരിക്കുന്നു',
'movetalk'                     => 'ബന്ധപ്പെട്ട സം‌വാദംതാളും കൂടെ നീക്കുക',
'move-subpages'                => 'ഉപതാളുകള്‍  മാറ്റുക ( $1 വരെ)',
'move-talk-subpages'           => 'സംവാദം താളിന്റെ ഉപതാളുകൾ മാറ്റുക ($1 എണ്ണം)',
'movepage-page-exists'         => '$1 എന്ന താൾ നിലനിൽക്കുന്നുണ്ട്, അതിനു മുകളിൽ സൃഷ്ടിക്കാൻ സ്വതവേ കഴിയില്ല.',
'movepage-page-moved'          => '$1 എന്ന താൾ $2 എന്നു മാറ്റിയിരിക്കുന്നു.',
'movepage-page-unmoved'        => '$1 എന്ന താൾ $2 എന്നു മാറ്റാന്‍ സാദ്ധ്യമല്ല.',
'movepage-max-pages'           => 'പരമാവധി സാധ്യമായ {{PLURAL:$1|ഒരു താൾ|$1 താളുകൾ}} മാറ്റിയിരിക്കുന്നു, അതിൽ കൂടുതൽ മാറ്റാൻ സ്വതവേ സാധ്യമല്ല.',
'1movedto2'                    => 'തലക്കെട്ടു മാറ്റം:  [[$1]]  >>> [[$2]]',
'1movedto2_redir'              => 'നിലവിലുണ്ടായിരുന്ന തിരിച്ചുവിടല്‍ താളിലേക്ക് തലക്കെട്ടു മാറ്റം: [[$1]] >>>  [[$2]]',
'movelogpage'                  => 'മാറ്റ പട്ടിക',
'movelogpagetext'              => 'തലക്കെട്ട് മാറ്റിയ താളുകളുടെ പട്ടിക താഴെ കാണാം.',
'movesubpage'                  => '{{PLURAL:$1|ഉപതാൾ|ഉപതാളുകൾ}}',
'movesubpagetext'              => 'ഈ താളിനുള്ള {{PLURAL:$1|ഒരു ഉപതാൾ|$1 ഉപതാളുകൾ}} താഴെ കൊടുത്തിരിക്കുന്നു.',
'movenosubpage'                => 'ഈ താളിന്‌ ഉപതാളുകള്‍ ഇല്ല',
'movereason'                   => 'കാരണം:',
'revertmove'                   => 'പൂര്‍വ്വസ്ഥിതിയിലാക്കുക',
'delete_and_move'              => 'മായ്ക്കുകയും മാറ്റുകയും ചെയ്യുക',
'delete_and_move_text'         => '==താള്‍ മായ്ക്കേണ്ടിയിരിക്കുന്നു==

താങ്കള്‍ സൃഷ്ടിക്കാന്‍ ശ്രമിച്ച "[[:$1]]" എന്ന താള്‍ നിലവിലുണ്ട്. ആ താള്‍ മായ്ച്ച് പുതിയ തലക്കെട്ട് നല്‍കേണ്ടതുണ്ടോ?',
'delete_and_move_confirm'      => 'ശരി, താള്‍ നീക്കം ചെയ്യുക',
'delete_and_move_reason'       => 'താള്‍ മാറ്റാനായി മായ്ച്ചു',
'selfmove'                     => 'സ്രോതസ്സിന്റെ തലക്കെട്ടും ലക്ഷ്യത്തിന്റെ തലക്കെട്ടും ഒന്നാണ്‌. അതിനാല്‍ തലക്കെട്ടുമാറ്റം സാദ്ധ്യമല്ല.',
'immobile-source-namespace'    => '"$1" നാമമേഖലയിലെ താളുകൾ മാറ്റാന്‍ കഴിയില്ല',
'immobile-target-namespace'    => '"$1" നാമമേഖലിയിലേയ്ക്ക് താളുകൾ മാറ്റാന്‍ കഴിയില്ല',
'immobile-target-namespace-iw' => 'അന്തർവിക്കി കണ്ണി താൾ മാറ്റാനുള്ള സാധുവായ ലക്ഷ്യമല്ല.',
'immobile-source-page'         => 'ഈ താള്‍ നീക്കാന്‍ സാധ്യമല്ല',
'immobile-target-page'         => 'ലക്ഷ്യമാക്കിയ തലക്കെട്ടിലേക്ക് മാറ്റാൻ സാധിക്കില്ല.',
'imagenocrossnamespace'        => 'പ്രമാണം അതിനായി അല്ലാത്ത നാമമേഖലയിലേയ്ക്ക് മാറ്റാന്‍ കഴിയില്ല',
'imagetypemismatch'            => 'പുതിയ പ്രമാണത്തിന്റെ എക്സ്റ്റെൻഷൻ അതിന്റെ തരവുമായി ഒത്തുപോകുന്നില്ല.',
'imageinvalidfilename'         => 'പ്രമാണത്തിനു ലക്ഷ്യമിട്ട പേര് അസാധുവാണ്',
'fix-double-redirects'         => 'പഴയ തലക്കെട്ടിലേക്കുള്ള തിരിച്ചുവിടല്‍ താളുകളും ഇതോടൊപ്പം പുതുക്കുക',
'move-leave-redirect'          => 'പിന്നില്‍ ഒരു തിരിച്ചുവിടല്‍ നിലനിര്‍ത്തുക',
'protectedpagemovewarning'     => "'''മുന്നറിയിപ്പ്:''' ഈ താൾ പൂട്ടിയിരിക്കുന്നു, അതായത് കാര്യനിർവാഹക പദവിയുള്ളവർക്കു മാത്രമേ അത് മാറ്റാന്‍ കഴിയൂ.",
'semiprotectedpagemovewarning' => "'''കുറിപ്പ്:''' ഈ താൾ പൂട്ടിയിരിക്കുന്നു, അതായത് അംഗത്വമെടുത്ത ഉപയോക്താക്കൾക്കു മാത്രമേ അതു മാറ്റാന്‍ കഴിയൂ.",

# Export
'export'            => 'താളുകള്‍ എക്സ്പോര്‍ട്ട് ചെയ്യുക',
'exportcuronly'     => 'നിലവിലുള്ള പതിപ്പ് മാത്രം ചേര്‍ക്കുക, പൂര്‍ണ്ണ തിരുത്തല്‍ ചരിത്രം വേണ്ട',
'export-submit'     => 'എക്സ്പോര്‍ട്ട്',
'export-addcattext' => 'വിഭാഗത്തില്‍നിന്നും താളുകള്‍ ചേര്‍ക്കുക:',
'export-addcat'     => 'ചേര്‍ക്കുക',
'export-addnstext'  => 'നാമമേഖലയില്‍ നിന്നും താളുകള്‍ ചേര്‍ക്കുക:',
'export-addns'      => 'ചേര്‍ക്കുക',
'export-download'   => 'ഒരു പ്രമാണമാക്കി സൂക്ഷിക്കുക',
'export-templates'  => 'ഫലകങ്ങളും ഉള്‍പ്പെടുത്തുക',

# Namespace 8 related
'allmessages'                   => 'സന്ദേശസഞ്ചയം',
'allmessagesname'               => 'പേര്‌',
'allmessagesdefault'            => 'സ്വതവേയുള്ള ഉള്ളടക്കം',
'allmessagescurrent'            => 'നിലവിലുള്ള ഉള്ളടക്കം',
'allmessagestext'               => 'ഇത് മീഡിയവിക്കി നെയിംസ്പേസില്‍ ലഭ്യമായ വ്യവസ്ഥാസന്ദേശങ്ങളുടെ ഒരു പട്ടിക ആണ്‌.
പ്രാമാണികമായ വിധത്തില്‍ മീഡിയവിക്കിയുടെ പ്രാദേശീകരണം താങ്കള്‍ ഉദ്ദേശിക്കുന്നുവെങ്കില്‍ ദയവായി [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation], [http://translatewiki.net translatewiki.net] എന്നീ താളുകള്‍ സന്ദര്‍ശിക്കുക.',
'allmessagesnotsupportedDB'     => "'''\$wgUseDatabaseMessages''' ബന്ധിച്ചിരിക്കുന്നതു കാരണം ഈ താള്‍ ഉപയോഗിക്കുവാന്‍ സാദ്ധ്യമല്ല.",
'allmessages-filter-legend'     => 'അരിപ്പ',
'allmessages-filter'            => 'പുനഃക്രമീകരിച്ച ക്രമത്തിൽ തിരഞ്ഞുവെയ്ക്കുക:',
'allmessages-filter-unmodified' => 'തിരുത്തപ്പെടാത്തത്',
'allmessages-filter-all'        => 'എല്ലാം',
'allmessages-filter-modified'   => 'തിരുത്തപ്പെട്ടത്',
'allmessages-prefix'            => 'പൂർവ്വപദത്തിനനുസരിച്ച് തിരഞ്ഞുവെയ്ക്കുക:',
'allmessages-language'          => 'ഭാഷ:',
'allmessages-filter-submit'     => 'പോകൂ',

# Thumbnails
'thumbnail-more'           => 'വലുതാക്കി കാണിക്കുക',
'filemissing'              => 'പ്രമാണം നഷ്ടമായിരിക്കുന്നു',
'thumbnail_error'          => 'ലഘുചിത്രം സൃഷ്ടിക്കുന്നതില്‍ പിഴവ്: $1',
'djvu_no_xml'              => 'DjVu പ്രമാണത്തിനു വേണ്ടി XML ശേഖരിക്കുവാന്‍ പറ്റിയില്ല',
'thumbnail_invalid_params' => 'ലഘുചിത്രത്തിനാവശ്യമായ ചരങ്ങൾ അസാധുവാണ്',
'thumbnail_dest_directory' => 'ലക്ഷ്യ ഡയറക്ടറി സൃഷ്ടിക്കുവാന്‍ സാധിച്ചില്ല',
'thumbnail_image-type'     => 'ചിത്രത്തിന്റെ തരം പിന്തുണക്കപ്പെട്ടതല്ല',
'thumbnail_image-missing'  => 'പ്രമാണം ലഭ്യമല്ലെന്നു കാണുന്നു: $1',

# Special:Import
'import'                     => 'താളുകള്‍ ഇറക്കുമതി ചെയ്യുക',
'importinterwiki'            => 'അന്തര്‍‌വിക്കി ഇറക്കുമതി',
'import-interwiki-text'      => 'വിക്കിയും ഇറക്കുമതി ചെയ്യാനുള്ള താളും തിരഞ്ഞെടുക്കുക.
പുതുക്കല്‍ തീയതികളും തിരുത്തിയ ആളുകളുടെ പേരും സൂക്ഷിക്കപ്പെടും.
അന്തര്‍‌വിക്കി ഇറക്കുമതിയുടെ എല്ലാ വിവരങ്ങളും [[Special:Log/import|ഇറക്കുമതി പ്രവര്‍ത്തനരേഖ]] എന്ന താളില്‍ ശേഖരിക്കപ്പെടും.',
'import-interwiki-source'    => 'മൂല വിക്കി/താൾ:',
'import-interwiki-history'   => 'ഈ താളിന്റെ എല്ലാ പൂര്‍വ്വചരിത്രവും പകര്‍ത്തുക',
'import-interwiki-templates' => 'എല്ലാ ഫലകങ്ങളും ഉള്‍പ്പെടുത്തുക',
'import-interwiki-submit'    => 'ഇറക്കുമതി',
'import-interwiki-namespace' => 'ഉദ്ദിഷ്ട നാമമേഖല:',
'import-upload-filename'     => 'പ്രമാണത്തിന്റെ പേര്‌',
'import-comment'             => 'കുറിപ്പ്:',
'importstart'                => 'താളുകള്‍ ഇറക്കുമതി ചെയ്യുന്നു...',
'import-revision-count'      => '$1 {{PLURAL:$1|പതിപ്പ്|പതിപ്പുകള്‍}}',
'importnopages'              => 'ഇറക്കുമതി ചെയ്യാന്‍ പറ്റിയ താളുകള്‍ ഇല്ല.',
'importfailed'               => 'ഇറക്കുമതി പരാജയപ്പെട്ടു: <nowiki>$1</nowiki>',
'importcantopen'             => 'ഇറക്കുമതി പ്രമാണം തുറക്കാന്‍ പറ്റിയില്ല',
'importbadinterwiki'         => 'മോശമായ അന്തര്‍‌വിക്കി കണ്ണി',
'importnotext'               => 'ശൂന്യം അല്ലെങ്കില്‍ ഉള്ളടക്കം ഒന്നുമില്ല',
'importsuccess'              => 'ഇറക്കുമതി ചെയ്തുകഴിഞ്ഞു!',
'importhistoryconflict'      => 'പതിപ്പുകളുടെ ചരിത്രത്തില്‍ പൊരുത്തക്കേട് (ഈ താള്‍ ഇതിനു മുന്‍പ് ഇറക്കുമതി ചെയ്തിട്ടുണ്ടാവാം)',
'import-noarticle'           => 'ഇറക്കുമതി ചെയ്യാന്‍ താള്‍ ഇല്ല!',
'import-nonewrevisions'      => 'എല്ല പതിപ്പുകളും നേരത്തെ ഇറക്കുമതി ചെയ്തിട്ടുള്ളതാണ്‌.',
'import-token-mismatch'      => 'സെഷന്‍ ഡാറ്റ നഷ്ടപ്പെട്ടതിനാല്‍ ദയവായി വീണ്ടും ശ്രമിക്കൂക',
'import-invalid-interwiki'   => 'താങ്കള്‍ നിര്‍ദ്ദേശിച്ച വിക്കിയില്‍നിന്നും ഇറക്കുമതിചെയ്യാന്‍ സാധിച്ചില്ല',

# Import log
'importlogpage'                    => 'ഇറക്കുമതി പ്രവര്‍ത്തനരേഖ',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|പതിപ്പ്|പതിപ്പുകള്‍}}',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|പതിപ്പ്|പതിപ്പുകള്‍}} $2 നിന്ന്',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'താങ്കളുടെ ഉപയോക്തൃതാള്‍',
'tooltip-pt-anonuserpage'         => 'താങ്കളുടെ ഐപി വിലാസത്തിന്റെ ഉപയോക്തൃതാള്‍',
'tooltip-pt-mytalk'               => 'താങ്കളുടെ സംവാദത്താള്‍',
'tooltip-pt-anontalk'             => 'ഈ ഐപി വിലാസത്തില്‍നിന്നുള്ള തിരുത്തലുകളെക്കുറിച്ചുള്ള സം‌വാദം',
'tooltip-pt-preferences'          => 'താങ്കളുടെ ക്രമീകരണങ്ങൾ',
'tooltip-pt-watchlist'            => 'താങ്കള്‍ ശ്രദ്ധിക്കുന്ന താളുകളിലെ മാറ്റങ്ങള്‍',
'tooltip-pt-mycontris'            => 'താങ്കളുടെ സേവനങ്ങളുടെ പട്ടിക',
'tooltip-pt-login'                => 'ലോഗിന്‍ ചെയ്യണമെന്നു നിര്‍ബന്ധം ഇല്ലെങ്കിലും ലോഗിന്‍ ചെയ്യുവാന്‍ താല്പര്യപ്പെടുന്നു.',
'tooltip-pt-anonlogin'            => 'ലോഗിന്‍ ചെയ്തു തിരുത്തല്‍ നടത്തുവാന്‍ താല്പര്യപ്പെടുന്നു.',
'tooltip-pt-logout'               => 'ലോഗൗട്ട് ചെയ്യാനുള്ള കണ്ണി',
'tooltip-ca-talk'                 => 'ഉള്ളടക്കം താളിനെക്കുറിച്ചുള്ള ചര്‍ച്ച',
'tooltip-ca-edit'                 => 'നിങ്ങള്‍ക്ക് ഈ താള്‍ തിരുത്താവുന്നതാണ്. തിരുത്തിയ താള്‍ സേവ് ചെയ്യൂന്നതിനു മുന്‍പ് പ്രിവ്യൂ കാണുക.',
'tooltip-ca-addsection'           => 'പുതിയ വിഭാഗം തുടങ്ങുക',
'tooltip-ca-viewsource'           => 'ഈ താള്‍ സം‌രക്ഷിക്കപ്പെട്ടിരിക്കുന്നു. താങ്കള്‍ക്ക് ഈ താളിന്റെ മൂലരൂപം കാണാവുന്നതാണ്‌.',
'tooltip-ca-history'              => 'ഈ താളിന്റെ പഴയ പതിപ്പുകള്‍.',
'tooltip-ca-protect'              => 'ഈ താള്‍ സം‌രക്ഷിക്കുക',
'tooltip-ca-unprotect'            => 'ഈ താളിന്റെ സംരക്ഷണം ഒഴിവാക്കുക',
'tooltip-ca-delete'               => 'ഈ താള്‍ നീക്കം ചെയ്യുക',
'tooltip-ca-undelete'             => 'ഈ താള്‍ നീക്കം ചെയ്തതിനുമുമ്പ് വരുത്തിയ തിരുത്തലുകള്‍ പുനഃസ്ഥാപിക്കുക',
'tooltip-ca-move'                 => 'ഈ താളിന്റെ തലക്കെട്ടു്‌ മാറ്റുക',
'tooltip-ca-watch'                => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിലേയ്ക്ക് ഈ താള്‍ ചേര്‍ക്കുക',
'tooltip-ca-unwatch'              => 'ഈ താള്‍ ഞാന്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയില്‍നിന്നു നീക്കുക',
'tooltip-search'                  => '{{SITENAME}} സംരംഭത്തില്‍ തിരയുക',
'tooltip-search-go'               => 'ഈ പേരില്‍ ഒരു താളുണ്ടെങ്കില്‍ അതിലേക്കു പോവുക.',
'tooltip-search-fulltext'         => 'ഈ പേര് ഏതൊക്കെ താളിന്റെ ഉള്ളടക്കത്തിലുണ്ടെന്ന് എന്നു തിരയുന്നു',
'tooltip-p-logo'                  => 'പ്രധാന താള്‍',
'tooltip-n-mainpage'              => 'പ്രധാനതാള്‍ സന്ദര്‍ശിക്കുക',
'tooltip-n-mainpage-description'  => 'പ്രധാന താൾ സന്ദർശിക്കുക',
'tooltip-n-portal'                => 'പദ്ധതി താളിനെക്കുറിച്ച്, താങ്കള്‍ക്കെന്തൊക്കെ ചെയ്യാം, കാര്യങ്ങള്‍ എവിടെനിന്ന് കണ്ടെത്താം',
'tooltip-n-currentevents'         => 'സമകാലീനസംഭവങ്ങളുടെ പശ്ചാത്തലം അന്വേഷിക്കുക',
'tooltip-n-recentchanges'         => 'വിക്കിയിലെ സമീപകാലമാറ്റങ്ങള്‍',
'tooltip-n-randompage'            => 'ഏതെങ്കിലും ഒരു താള്‍ തുറക്കൂ',
'tooltip-n-help'                  => 'സഹായം ലഭ്യമായ ഇടം',
'tooltip-t-whatlinkshere'         => 'ഈ താളിലേക്കു കണ്ണിയാല്‍ ബന്ധിപ്പിക്കപ്പെട്ടിരിക്കുന്ന എല്ലാ വിക്കി താളുകളുടേയും പട്ടിക.',
'tooltip-t-recentchangeslinked'   => 'താളുകളിലെ പുതിയ മാറ്റങ്ങള്‍',
'tooltip-feed-rss'                => 'ഈ താളിന്റെ RSS ഫീഡ്',
'tooltip-feed-atom'               => 'ഈ താളിന്റെ Atom ഫീഡ്',
'tooltip-t-contributions'         => 'ഉപയോക്താവിന്റെ സംഭാവനകളുടെ പട്ടിക കാണുക',
'tooltip-t-emailuser'             => 'ഈ ഉപയോക്താവിനു ഇമെയില്‍ അയക്കുക',
'tooltip-t-upload'                => 'പ്രമാണങ്ങള്‍ അപ്‌ലോഡ് ചെയ്യുവാന്‍',
'tooltip-t-specialpages'          => 'പ്രത്യേകതാളുകളുടെ പട്ടിക',
'tooltip-t-print'                 => 'ഈ താളിന്റെ അച്ചടി രൂപം',
'tooltip-t-permalink'             => 'താളിന്റെ ഈ പതിപ്പിന്റെ സ്ഥിരം കണ്ണി',
'tooltip-ca-nstab-main'           => 'ഉള്ളടക്കം താള്‍ കാണുക',
'tooltip-ca-nstab-user'           => 'ഉപയോക്താവിന്റെ താള്‍ കാണുക',
'tooltip-ca-nstab-media'          => 'മീഡിയ താള്‍ കാണുക',
'tooltip-ca-nstab-special'        => "ഇതൊരു '''പ്രത്യേക''' താളാണ്‌. ഇത് തിരുത്തുക സാധ്യമല്ല.",
'tooltip-ca-nstab-project'        => 'പദ്ധതി താള്‍ കാണുക',
'tooltip-ca-nstab-image'          => 'പ്രമാണ താള്‍ കാണുക',
'tooltip-ca-nstab-mediawiki'      => 'വ്യവസ്ഥാസന്ദേശം കാണുക',
'tooltip-ca-nstab-template'       => 'ഫലകം കാണുക',
'tooltip-ca-nstab-help'           => 'സഹായം താള്‍ കാണുക',
'tooltip-ca-nstab-category'       => 'വര്‍ഗ്ഗം താള്‍ കാണുക',
'tooltip-minoredit'               => 'ഇത് ഒരു ചെറുതിരുത്തലായി അടയാളപ്പെടുത്തുക',
'tooltip-save'                    => 'മാറ്റങ്ങള്‍ സംരക്ഷിക്കുന്നു',
'tooltip-preview'                 => 'താങ്കള്‍ വരുത്തിയ മാറ്റത്തിന്റെ പരിണതഫലം കാണുന്നതിനു താള്‍ സംരക്ഷിക്കുന്നതിനു മുന്‍പ് ഈ ബട്ടണ്‍ ഉപയോഗിക്കുക!',
'tooltip-diff'                    => 'താങ്കള്‍ ഉള്ളടക്കത്തില്‍ വരുത്തിയ മാറ്റങ്ങള്‍ ഏതൊക്കെയെന്നു പ്രദര്‍ശിപ്പിക്കുക',
'tooltip-compareselectedversions' => 'ഈ താളിന്റെ നിങ്ങള്‍ തിരഞ്ഞെടുത്ത രണ്ട് പതിപ്പുകള്‍ തമ്മിലുള്ള വ്യത്യാസം കാണുക.',
'tooltip-watch'                   => 'ഈ താള്‍ നിങ്ങള്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിലേക്കു മാറ്റുക.',
'tooltip-recreate'                => 'താള്‍ മായ്ചതാണെങ്കിലും പുനഃസൃഷ്ടിക്കുക',
'tooltip-upload'                  => 'അപ്‌ലോഡ് തുടങ്ങുക',
'tooltip-rollback'                => 'അവസാനത്തെ ആള്‍ നടത്തിയ തിരുത്തലുകളെ ഒരൊറ്റ ക്ലിക്ക് കൊണ്ട് മുന്‍ അവസ്ഥയിലേക്ക് തിരിച്ചുവെയ്ക്കാന്‍ "റോള്‍ബാക്ക്" സഹായിക്കുന്നു.',
'tooltip-undo'                    => 'മാറ്റം തിരസ്കരിക്കുക എന്നത് ഈ മാറ്റം മുന്‍ അവസ്ഥയിലേക്ക് മാറ്റുകയും അത് എഡിറ്റ് ഫോമില്‍ പ്രിവ്യൂ ആയി കാട്ടുകയും ചെയ്യും. അതുകൊണ്ട് തിരുത്തലിന്റെ കാരണം ചുരുക്കമായി നല്‍കാന്‍ സാധിക്കുന്നതാണ്.',

# Attribution
'anonymous'        => '{{SITENAME}} സംരംഭത്തിലെ അജ്ഞാത {{PLURAL:$1|ഉപയോക്താവ്|ഉപയോക്താക്കള്‍}}',
'siteuser'         => '{{SITENAME}} ഉപയോക്താവ് $1',
'anonuser'         => '{{SITENAME}} പദ്ധതിയിലെ അജ്ഞാത ഉപയോക്താവ് $1',
'lastmodifiedatby' => '$2, $1 നു $3 ആണ്‌ ഈ താള്‍ അവസാനം പുതുക്കിയത്.',
'othercontribs'    => '$1 ന്റെ സൃഷ്ടിയെ അധികരിച്ച്.',
'others'           => 'മറ്റുള്ളവര്‍',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|ഉപയോക്താവ്‌|ഉപയോക്താക്കള്‍}} $1',
'anonusers'        => '{{SITENAME}} പദ്ധതിയിലെ അജ്ഞാത {{PLURAL:$2|ഉപയോക്താവ്|ഉപയോക്താക്കൾ}} $1',
'creditspage'      => 'താളിനുള്ള കടപ്പാട്',
'nocredits'        => 'ഈ താളിന്റെ കടപ്പാട് വിവരങ്ങള്‍ ലഭ്യമല്ല.',

# Spam protection
'spamprotectiontitle' => 'സ്പാം സം‌രക്ഷണ ഫില്‍ട്ടര്‍',
'spambot_username'    => 'മീഡിയാവിക്കിയിലെ സ്പാം ശുദ്ധീകരണം',

# Info page
'infosubtitle'   => 'താളിനുള്ള വിവരങ്ങള്‍',
'numedits'       => 'തിരുത്തലുകളുടെ എണ്ണം (ലേഖനം): $1',
'numtalkedits'   => 'തിരുത്തലുകളുടെ എണ്ണം (സം‌വാദം താള്‍): $1',
'numwatchers'    => 'ശ്രദ്ധിക്കുന്നവരുടെ എണ്ണം: $1',
'numauthors'     => 'വ്യത്യസ്തരായ രചയിതാക്കളുടെ എണ്ണം (താളിന്റെ): $1',
'numtalkauthors' => 'വ്യത്യസ്തരായ രചയിതാക്കളുടെ എണ്ണം (സം‌വാദം താളിന്റെ): $1',

# Skin names
'skinname-standard'    => 'സാര്‍വത്രികം',
'skinname-nostalgia'   => 'ഗൃഹാതുരത്വം',
'skinname-cologneblue' => 'ക്ലോണ്‍ നീല',
'skinname-monobook'    => 'മോണോബുക്ക്',
'skinname-chick'       => 'സുന്ദരി',
'skinname-simple'      => 'ലളിതം',
'skinname-modern'      => 'നവീനം',

# Math options
'mw_math_png'    => 'എപ്പോഴും PNG ആയി പ്രദര്‍ശിപ്പിക്കുക',
'mw_math_simple' => 'വളരെ ലളിതമാണെങ്കില്‍ HTML അല്ലെങ്കില്‍ PNG',
'mw_math_html'   => 'പറ്റുമെങ്കില്‍ HTML അല്ലെങ്കില്‍ PNG',
'mw_math_source' => 'TeX ആയി തന്നെ പ്രദര്‍ശിപ്പിക്കുക (ടെക്സ്റ്റ് ബ്രൗസറുകള്‍ക്ക്)',
'mw_math_modern' => 'ആധുനിക ബ്രൗസറുകള്‍ക്കായി നിര്‍ദേശിക്കപ്പെട്ടത്',
'mw_math_mathml' => 'പറ്റുമെങ്കില്‍ MathML (പരീക്ഷണാടിസ്ഥാനം)',

# Math errors
'math_failure'          => 'parse ചെയ്യുവാന്‍ പരാജയപ്പെട്ടു',
'math_unknown_error'    => 'കാരണമറിയാത്ത പിഴവ്',
'math_unknown_function' => 'അജ്ഞാതമായ ഫങ്ങ്ഷന്‍',
'math_syntax_error'     => 'തെറ്റായ പദവിന്യാസം',
'math_bad_tmpdir'       => 'math temp ഡയറക്ടറി ഉണ്ടാക്കാനോ അതിലേക്കു എഴുതാനോ സാധിച്ചില്ല',
'math_bad_output'       => 'math output ഡയറക്ടറി ഉണ്ടാക്കാനോ അതിലേക്കു എഴുതാനോ സാധിച്ചില്ല',

# Patrolling
'markaspatrolleddiff'                 => 'റോന്തുചുറ്റിയതായി അടയാളപ്പെടുത്തുക',
'markaspatrolledtext'                 => 'ഈ താളില്‍ റോന്തുചുറ്റിയതായി രേഖപ്പെടുത്തുക',
'markedaspatrolled'                   => 'റോന്തുചുറ്റിയതായി രേഖപ്പെടുത്തിയിരിക്കുന്നു',
'markedaspatrolledtext'               => "\"'''{{PAGENAME}}'''\" എന്ന താളില്‍ റോന്തുചുറ്റിയതായി രേഖപ്പെടുത്തിയിരിക്കുന്നു",
'rcpatroldisabled'                    => 'പുതിയ മാറ്റങ്ങളുടെ റോന്തുചുറ്റല്‍ ദുര്‍ബലപ്പെടുത്തിയിരിക്കുന്നു',
'rcpatroldisabledtext'                => 'പുതിയ മാറ്റങ്ങളുടെ റോന്തുചുറ്റല്‍ സം‌വിധാനം ദുര്‍ബലപ്പെടുത്തിയിരിക്കുകയാണ്‌.',
'markedaspatrollederror'              => 'റോന്തുചുറ്റിയതായി അടയാളപ്പെടുത്തുക സാധ്യമല്ല',
'markedaspatrollederror-noautopatrol' => 'സ്വന്തം മാറ്റങ്ങള്‍ റോന്തുചുറ്റിയതായി അടയാളപ്പെടുത്തുക അനുവദനീയമല്ല.',

# Patrol log
'patrol-log-page'      => 'റോന്തുചുറ്റല്‍ പ്രവര്‍ത്തനരേഖ',
'patrol-log-auto'      => '(യാന്ത്രികം)',
'log-show-hide-patrol' => '$1 റോന്തുചുറ്റല്‍ രേഖ',

# Image deletion
'deletedrevision'                 => '$1 എന്ന പഴയ പതിപ്പ് മായ്ച്ചിരിക്കുന്നു',
'filedeleteerror-short'           => 'പ്രമാണം നീക്കം ചെയ്യുമ്പോള്‍ പ്രശ്നം: $1',
'filedeleteerror-long'            => 'പ്രമാണം നീക്കം ചെയ്യുമ്പോള്‍ ചില പ്രശ്നങ്ങള്‍ സംഭവിച്ചു:

$1',
'filedelete-missing'              => '"$1" എന്ന പ്രമാണം നിലവില്ലാത്തതിനാല്‍ അതു നീക്കം ചെയ്യുക സാധ്യമല്ല.',
'filedelete-old-unregistered'     => 'താങ്കള്‍ ആവശ്യപ്പെട്ട "$1" എന്ന പതിപ്പ് ഡാറ്റാബേസില്‍ ഇല്ല.',
'filedelete-current-unregistered' => 'താങ്കള്‍ ആവശ്യപ്പെട്ട "$1" എന്ന പ്രമാണം ഡാറ്റബേസില്‍ ഇല്ല.',

# Browsing diffs
'previousdiff' => '← മുന്‍‌പത്തെ വ്യത്യാസം',
'nextdiff'     => 'അടുത്ത വ്യത്യാസം →',

# Visual comparison
'visual-comparison' => 'ദൃഷ്ടിഗോചരമായ താരതമ്യം',

# Media information
'mediawarning'         => "'''മുന്നറിയിപ്പ്''': ഈ പ്രമാണത്തില്‍ വിനാശകാരിയായ കോഡ് ഉണ്ടായേക്കാം. ഇതു തുറക്കുന്നതു നിങ്ങളുടെ കമ്പ്യൂട്ടറിനു അപകടമായി തീര്‍ന്നേക്കാം.<hr />",
'imagemaxsize'         => "ചിത്രത്തിന്റെ വലിപ്പം:<br />''(പ്രമാണത്തിന്റെ വിവരണ താളുകളില്‍)''",
'thumbsize'            => 'ലഘുചിത്രത്തിന്റെ വലിപ്പം:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|താള്‍|താളുകള്‍}}',
'file-info'            => '(പ്രമാണത്തിന്റെ വലിപ്പം: $1, MIME തരം: $2)',
'file-info-size'       => '($1 × $2 പിക്സല്‍, ഫയലിന്റെ വലുപ്പം: $3, MIME തരം: $4)',
'file-nohires'         => '<small>കൂടുതല്‍ വ്യക്തതയുള്ള ചിത്രം ലഭ്യമല്ല.</small>',
'svg-long-desc'        => '(SVG ഫയല്‍, നാമമാത്രമായ $1 × $2 പിക്സലുകള്‍, ഫയലിന്റെ വലുപ്പം: $3)',
'show-big-image'       => 'പൂര്‍ണ്ണ റെസലൂഷന്‍',
'show-big-image-thumb' => '<small>ഈ പ്രിവ്യൂവിന്റെ വലിപ്പം: $1 × $2 പിക്സലുകള്‍</small>',

# Special:NewFiles
'newimages'             => 'പുതിയ പ്രമാണങ്ങളുടെ ഗാലറി',
'imagelisttext'         => "$2 പ്രകാരം സോര്‍ട്ട് ചെയ്ത '''$1''' {{PLURAL:$1|പ്രമാണത്തിന്റെ|പ്രമാണങ്ങളുടെ}} പട്ടിക താഴെ കാണാം.",
'newimages-summary'     => 'ചുരുക്കം',
'newimages-legend'      => 'അരിപ്പ',
'newimages-label'       => 'പ്രമാണത്തിന്റെ പേര്‌ (അഥവാ പേരിന്റെ ഭാഗം)',
'showhidebots'          => '($1 ബോട്ടുകള്‍)',
'noimages'              => 'ഒന്നും കാണാനില്ല.',
'ilsubmit'              => 'തിരയൂ',
'bydate'                => 'ദിനക്രമത്തില്‍',
'sp-newimages-showfrom' => '$2, $1 നു ശേഷം അപ്‌ലോഡ് ചെയ്ത പ്രമാണങ്ങള്‍ പ്രദര്‍ശിപ്പിക്കുക',

# Bad image list
'bad_image_list' => 'The format is as follows:

Only list items (lines starting with *) are considered. The first link on a line must be a link to a bad file.
Any subsequent links on the same line are considered to be exceptions, i.e. pages where the file may occur inline.',

# Metadata
'metadata'          => 'മെറ്റാഡാറ്റ',
'metadata-help'     => 'ഡിജിറ്റല്‍ ക്യാമറയില്‍ നിന്നോ, മറ്റേതെങ്കിലും സ്രോതസ്സില്‍ നിന്നോ, സ്കാനര്‍ ഉപയോഗിച്ച് ഡിജിറ്റലൈസ് ചെയ്തപ്പോഴോ ചേര്‍ക്കപ്പെട്ട അധിക വിവരങ്ങള്‍ ഈ പ്രമാണത്തില്‍ ഉണ്ട്. ഈ പ്രമാണം അതിന്റെ ആദ്യസ്ഥിതിയില്‍ നിന്നും മാറ്റിയിട്ടുണ്ടെങ്കില്‍, ഇപ്പോള്‍ എല്ലാ വിശദാംശങ്ങളും പ്രദര്‍ശിപ്പിക്കണമെന്നില്ല.',
'metadata-expand'   => 'അധികവിവരങ്ങള്‍ കാണിക്കുക',
'metadata-collapse' => 'അധികവിവരങ്ങള്‍ മറയ്ക്കുക',
'metadata-fields'   => 'ഈ സന്ദേശത്തില്‍ തന്നിട്ടുള്ള EXIF മെറ്റാഡാറ്റ ഫീല്‍ഡുകള്‍ ചിത്രത്തിന്റെ താളില്‍ മെറ്റാഡാറ്റ ടേബിള്‍ മറഞ്ഞിരിക്കുമ്പോഴും ദൃശ്യമാകും. മറ്റുള്ള ഫീല്‍ഡുകള്‍ സ്വതവേ മറഞ്ഞിരിക്കും.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'വീതി',
'exif-imagelength'                 => 'ഉയരം',
'exif-compression'                 => 'കംപ്രഷന്‍ രീതി',
'exif-orientation'                 => 'വിന്യാസം',
'exif-samplesperpixel'             => 'ഘടകങ്ങളുടെ എണ്ണം',
'exif-xresolution'                 => 'തിരശ്ചീന റെസലൂഷന്‍',
'exif-yresolution'                 => 'ലംബ റെസലൂഷന്‍',
'exif-rowsperstrip'                => 'ഓരോ സ്‌ട്രിപ്പിലുമുള്ള വരികളുടെ എണ്ണം',
'exif-jpeginterchangeformatlength' => 'JPEG ഡാറ്റയുടെ ബൈറ്റ്സുകള്‍',
'exif-datetime'                    => 'പ്രമാണത്തിന് മാറ്റം വരുത്തിയ തീയതിയും സമയവും',
'exif-imagedescription'            => 'ചിത്രത്തിന്റെ തലക്കെട്ട്',
'exif-make'                        => 'ഛായാഗ്രാഹി നിര്‍മ്മാതാവ്',
'exif-model'                       => 'ഛായാഗ്രാഹി മോഡല്‍',
'exif-software'                    => 'ഉപയോഗിച്ച സോഫ്റ്റ്‌വെയര്‍',
'exif-artist'                      => 'ഛായാഗ്രാഹകന്‍',
'exif-copyright'                   => 'പകര്‍പ്പവകാശി',
'exif-exifversion'                 => 'Exif ന്റെ വേര്‍ഷന്‍',
'exif-colorspace'                  => 'കളര്‍ സ്പേസ്',
'exif-componentsconfiguration'     => 'ഓരോ ഘടകത്തിന്റേയും അര്‍ത്ഥം',
'exif-pixelydimension'             => 'ചിത്രത്തിന്റെ സാധുവായ വീതി',
'exif-pixelxdimension'             => 'ചിത്രത്തിന്റെ സാധുവായ ഉയരം',
'exif-makernote'                   => 'നിര്‍മ്മാതാക്കളുടെ കുറിപ്പുകള്‍',
'exif-usercomment'                 => 'ഉപയോക്താവിന്റെ കുറിപ്പുകള്‍',
'exif-relatedsoundfile'            => 'ഇതുമായി ബന്ധമുള്ള ഓഡിയോ പ്രമാണം',
'exif-datetimeoriginal'            => 'ഡാറ്റ സൃഷ്ടിക്കപ്പെട്ട തീയതിയും സമയവും',
'exif-datetimedigitized'           => 'ഡിജിറ്റൈസ് ചെയ്ത തീയതിയും സമയവും',
'exif-exposuretime'                => 'Exposure time',
'exif-exposuretime-format'         => '$1 സെക്കന്റ് ($2)',
'exif-fnumber'                     => 'F സംഖ്യ',
'exif-exposureprogram'             => 'എക്സ്പോഷര്‍ പ്രോഗ്രാം',
'exif-shutterspeedvalue'           => 'ഷട്ടര്‍ സ്പീഡ്',
'exif-aperturevalue'               => 'അപ്പെര്‍ച്ചര്‍',
'exif-brightnessvalue'             => 'ബ്രൈറ്റ്നെസ്സ്',
'exif-lightsource'                 => 'പ്രകാശ സ്രോതസ്സ്',
'exif-flash'                       => 'ഫ്ലാഷ്',
'exif-focallength'                 => 'ലെന്‍സിന്റെ ഫോക്കല്‍ ലെങ്ത്',
'exif-subjectarea'                 => 'വസ്തുവിന്റെ വിസ്തൃതി',
'exif-flashenergy'                 => 'ഫ്ലാഷ് എനര്‍ജി',
'exif-exposureindex'               => 'Exposure index',
'exif-filesource'                  => 'പ്രമാണത്തിന്റെ സ്രോതസ്സ്',
'exif-exposuremode'                => 'എക്സ്പോഷര്‍ മോഡ്',
'exif-whitebalance'                => 'വൈറ്റ് ബാലന്‍സ്',
'exif-digitalzoomratio'            => 'ഡിജിറ്റല്‍ സൂം അനുപാതം',
'exif-focallengthin35mmfilm'       => '35 മില്ലീമീറ്റര്‍ ഫിലിമിലെ ഫോക്കസ് ദൂരം',
'exif-contrast'                    => 'കോണ്‍‌ട്രാസ്റ്റ്',
'exif-saturation'                  => 'സാച്ചുറേഷന്‍',
'exif-sharpness'                   => 'ഷാര്‍പ്പനെസ്',
'exif-imageuniqueid'               => 'ചിത്രത്തിന്റെ തനതായ ഐഡി',
'exif-gpslatituderef'              => 'ഉത്തര അല്ലെങ്കില്‍ ദക്ഷിണ അക്ഷാംശം',
'exif-gpslatitude'                 => 'അക്ഷാംശം',
'exif-gpslongituderef'             => 'പൂര്‍വ്വരേഖാംശം അല്ലെങ്കില്‍ പശ്ചിമരേഖാംശം',
'exif-gpslongitude'                => 'രേഖാംശം',
'exif-gpsaltitude'                 => 'ഉന്നതി',
'exif-gpstimestamp'                => 'GPS സമയം (ആറ്റോമിക് ക്ലോക്ക്)',
'exif-gpssatellites'               => 'അളക്കാന്‍ ഉപയോഗിച്ച കൃത്രിമോപഗ്രഹങ്ങള്‍',
'exif-gpsspeedref'                 => 'വേഗതയുടെ ഏകകം',
'exif-gpsspeed'                    => 'GPS പരിഗ്രാഹിയുടെ ഗതിവേഗം (Speed of GPS receiver)',
'exif-gpstrack'                    => 'ചലനത്തിന്റെ ദിശ',
'exif-gpsimgdirection'             => 'ചിത്രത്തിന്റെ ദിശ',
'exif-gpsareainformation'          => 'GPS പ്രദേശത്തിന്റെ പേര്‌',
'exif-gpsdatestamp'                => 'GPS തീയ്യതി',

'exif-unknowndate' => 'തീയ്യതി അജ്ഞാതം',

'exif-orientation-1' => 'സാധാരണം',
'exif-orientation-2' => 'തിരശ്ചീനമാക്കി',
'exif-orientation-3' => '180° തിരിച്ചു',
'exif-orientation-4' => 'കുത്തനെ തിരിച്ചു',
'exif-orientation-5' => '90° CCW തിരിക്കുകയും കുത്തനെയാക്കുകയും ചെയ്തു',
'exif-orientation-6' => '90° CW തിരിച്ചു',
'exif-orientation-7' => '90° CW തിരിക്കുകയും കുത്തനെയാക്കുകയും ചെയ്തു',
'exif-orientation-8' => '90° CCW തിരിച്ചു',

'exif-componentsconfiguration-0' => 'നിലവിലില്ല',

'exif-exposureprogram-0' => 'നിര്‍‌വചിക്കപ്പെട്ടിട്ടില്ല',
'exif-exposureprogram-1' => 'കായികമായി',
'exif-exposureprogram-2' => 'സാധാരണ പ്രോഗ്രാം',
'exif-exposureprogram-3' => 'അപ്പെര്‍ച്ചര്‍ മുന്‍‌ഗണന',
'exif-exposureprogram-4' => 'ഷട്ടര്‍ മുന്‍‌ഗണന',
'exif-exposureprogram-5' => 'ക്രിയേറ്റീവ് പ്രോഗ്രാം (biased toward depth of field)',
'exif-exposureprogram-6' => 'ആക്ഷന്‍ പ്രോഗ്രാം (biased toward fast shutter speed)',
'exif-exposureprogram-7' => 'പോര്‍ട്ടറൈറ്റ് മോഡ് (for closeup photos with the background out of focus)',
'exif-exposureprogram-8' => 'ലാന്‍ഡ് സ്കേപ്പ് മോഡ് (for landscape photos with the background in focus)',

'exif-subjectdistance-value' => '$1 മീറ്റര്‍',

'exif-meteringmode-0'   => 'അജ്ഞാതം',
'exif-meteringmode-1'   => 'ശരാശരി',
'exif-meteringmode-6'   => 'ഭാഗികം',
'exif-meteringmode-255' => 'മറ്റുള്ളവ',

'exif-lightsource-0'   => 'അജ്ഞാതം',
'exif-lightsource-1'   => 'പകല്‍‌പ്രകാശം',
'exif-lightsource-2'   => 'ഫ്ലൂറോസെന്റ്',
'exif-lightsource-3'   => 'ടങ്ങ്സ്റ്റണ്‍ (ധവള പ്രകാശം)',
'exif-lightsource-4'   => 'ഫ്ലാഷ്',
'exif-lightsource-9'   => 'തെളിഞ്ഞ കാലാവസ്ഥ',
'exif-lightsource-10'  => 'മൂടിക്കെട്ടിയ കാലാവസ്ഥ',
'exif-lightsource-11'  => 'തണല്‍',
'exif-lightsource-12'  => 'പകല്‍‌വെളിച്ച ഫ്ലൂറോസെന്റ് (D 5700 – 7100K)',
'exif-lightsource-13'  => 'പകല്‍ വെള്ള ഫ്ലൂറോസെന്റ് (N 4600 – 5400K)',
'exif-lightsource-14'  => 'ശീത വെള്ള ഫ്ലൂറോസെന്റ് (W 3900 – 4500K)',
'exif-lightsource-15'  => 'വെള്ള ഫ്ലൂറോസെന്റ് (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'മാതൃകാ വെളിച്ചം A',
'exif-lightsource-18'  => 'മാതൃകാ വെളിച്ചം B',
'exif-lightsource-19'  => 'മാതൃകാ വെളിച്ചം C',
'exif-lightsource-255' => 'മറ്റു പ്രകാശ സ്രോതസ്സ്',

'exif-focalplaneresolutionunit-2' => 'ഇഞ്ച്',

'exif-sensingmethod-1' => 'നിര്‍‌വചിക്കപ്പെട്ടിട്ടില്ല',

'exif-scenetype-1' => 'നേരിട്ടു ഛായഗ്രഹണം നടത്തിയ പടം',

'exif-customrendered-0' => 'സാധാരണ പ്രക്രിയ',
'exif-customrendered-1' => 'സാമ്പ്രദായിക പ്രക്രിയ',

'exif-exposuremode-0' => 'യാന്തിക എക്സ്പോഷര്‍',
'exif-exposuremode-1' => 'മാനുവല്‍ എക്സ്പോഷര്‍',

'exif-whitebalance-0' => 'യാന്ത്രിക വൈറ്റ് ബാലന്‍സ്',
'exif-whitebalance-1' => 'മാനുവല്‍ വൈറ്റ് ബാലന്‍സ്',

'exif-scenecapturetype-0' => 'സാധാരണം',
'exif-scenecapturetype-1' => 'ലാന്‍ഡ്‌സ്കേപ്പ്',
'exif-scenecapturetype-2' => 'പോര്‍ട്ട്‌റൈറ്റ്',
'exif-scenecapturetype-3' => 'രാത്രി ദൃശ്യം',

'exif-gaincontrol-0' => 'ഒന്നുമില്ല',

'exif-contrast-0' => 'സാധാരണം',
'exif-contrast-1' => 'സോഫ്റ്റ്',
'exif-contrast-2' => 'ഹാര്‍ഡ്',

'exif-saturation-0' => 'സാധാരണം',
'exif-saturation-1' => 'ലോ സാച്ചുറേഷന്‍',
'exif-saturation-2' => 'ഹൈ സാച്ചുറേഷന്‍',

'exif-sharpness-0' => 'സാധാരണം',
'exif-sharpness-1' => 'സോഫ്റ്റ്',
'exif-sharpness-2' => 'ഹാര്‍ഡ്',

'exif-subjectdistancerange-0' => 'അജ്ഞാതം',
'exif-subjectdistancerange-1' => 'മാക്രോ',
'exif-subjectdistancerange-2' => 'സമീപ ദൃശ്യം',
'exif-subjectdistancerange-3' => 'വിദൂരവീക്ഷണം',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'ഉത്തര അക്ഷാംശം',
'exif-gpslatitude-s' => 'ദക്ഷിണ അക്ഷാംശം',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'കിഴക്കേ രേഖാംശം',
'exif-gpslongitude-w' => 'പടിഞ്ഞാറെ രേഖാംശം',

'exif-gpsstatus-a' => 'കണക്കെടുപ്പ് പുരോഗമിക്കുന്നു',

'exif-gpsmeasuremode-2' => 'ദ്വിമാന അളവ്',
'exif-gpsmeasuremode-3' => 'ത്രിമാന അളവ്',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometres per hour',
'exif-gpsspeed-m' => 'Miles per hour',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'ശരിക്കുള്ള ദിശ',
'exif-gpsdirection-m' => 'കാന്തിക ദിശ',

# External editor support
'edit-externally'      => 'ഈ പ്രമാണം ഒരു ബാഹ്യ ആപ്ലിക്കേഷന്‍ ഉപയോഗിച്ച് തിരുത്തുക',
'edit-externally-help' => '(കൂടുതല്‍ വിവരത്തിനു http://www.mediawiki.org/wiki/Manual:External_editors കാണുക)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'എല്ലാം',
'imagelistall'     => 'എല്ലാം',
'watchlistall2'    => 'എല്ലാം',
'namespacesall'    => 'എല്ലാം',
'monthsall'        => 'എല്ലാം',
'limitall'         => 'എല്ലാം',

# E-mail address confirmation
'confirmemail'             => 'ഇമെയില്‍ വിലാസം സ്ഥിരീകരിക്കല്‍',
'confirmemail_noemail'     => '[[Special:Preferences|താങ്കളുടെ ക്രമീകരണങ്ങളുടെ കൂടെ]] സാധുവായൊരു ഇമെയില്‍ വിലാസം സജ്ജീകരിച്ചിട്ടില്ല.',
'confirmemail_text'        => '{{SITENAME}} സം‌രംഭത്തില്‍ ഇമെയില്‍ സൗകര്യം ഉപയോഗിക്കണമെങ്കില്‍ നിങ്ങള്‍ നിങ്ങളുടെ ഇമെയില്‍ വിലാസത്തിന്റെ സാധുത തെളിയിച്ചിരിക്കണം. നിങ്ങളുടെ ഇമെയില്‍ വിലാസത്തിലേക്ക് സ്ഥിരീകരണ മെയില്‍ അയക്കുവാന്‍ താഴെയുള്ള ബട്ടണ്‍ അമര്‍ത്തുക. നിങ്ങള്‍ക്ക് അയക്കുന്ന ഇമെയിലില്‍ ഒരു സ്ഥിരീകരണ കോഡ് ഉണ്ട്. ആ കോഡില്‍ അമര്‍ത്തിയാല്‍ നിങ്ങളുടെ വിലാസത്തിന്റെ സാധുത തെളിയിക്കപ്പെടും.',
'confirmemail_pending'     => 'താങ്കളുടെ അക്കൗണ്ട് ഈ അടുത്ത് ഉണ്ടാക്കിയതാണെങ്കില്‍,  ഒരു സ്ഥിരീകരണ കോഡ് താങ്കള്‍ക്ക് ഇമെയില്‍ ചെയ്തിട്ടുണ്ട്.  പുതിയ സ്ഥിരീകരണ കോഡ് ആവശ്യപ്പെടാന്‍ ശ്രമിക്കുന്നതിനു മുന്‍പ് ആദ്യത്തെ സ്ഥിരികരണ കോഡിനായി കുറച്ച് സമയം കാത്തിരിക്കൂ.',
'confirmemail_send'        => 'സ്ഥിരീകരണ കോഡ് (confirmation code) മെയില്‍ ചെയ്യുക',
'confirmemail_sent'        => 'സ്ഥിരീകരണ ഇമെയില്‍ അയച്ചിരിക്കുന്നു.',
'confirmemail_oncreate'    => 'ഒരു സ്ഥിരീകരണ കോഡ് താങ്കളുടെ ഇമെയില്‍ വിലാസത്തിലേക്ക് അയച്ചിട്ടുണ്ട്.
ലോഗിന്‍ ചെയ്യുന്നതിനു ഈ കോഡ് ആവശ്യമില്ല. പക്ഷെ വിക്കിയില്‍ ഇമെയിലുമായി ബന്ധപ്പെട്ട സേവനങ്ങള്‍ ഉപയോഗിക്കുന്നതിനു മുന്‍പ് പ്രസ്തുത സ്ഥിരീകരണ കോഡ് ഉപയോഗിച്ചിരിക്കണം.',
'confirmemail_sendfailed'  => '{{SITENAME}} സം‌രംഭത്തിന്‌ സ്ഥിരീകരണ ഇമെയില്‍ അയക്കുവാന്‍ സാധിച്ചില്ല. വിലാസത്തില്‍ സാധുവല്ലാത്ത അക്ഷരങ്ങള്‍ ഉണ്ടോ എന്നു ദയവായി  പരിശോധിക്കുക.

ഇമെയില്‍ അയക്കാന്‍ ശ്രമിച്ചപ്പോള്‍ ലഭിച്ച മറുപടി: $1',
'confirmemail_invalid'     => 'അസാധുവായ സ്ഥിരീകരണ കോഡ്. കോഡിന്റെ കാലാവധി തീര്‍ന്നിരിക്കണം.',
'confirmemail_needlogin'   => 'ഇമെയില്‍ വിലാസം സ്ഥിരീകരിക്കാന്‍ നിങ്ങള്‍ $1 ചെയ്തിരിക്കണം.',
'confirmemail_success'     => 'താങ്കളുടെ ഇമെയില്‍ വിലാസം സ്ഥിരീകരിക്കപ്പെട്ടിരിക്കുന്നു. ഇനി താങ്കള്‍ക്ക് ലോഗിന്‍ ചെയ്തശേഷം വിക്കി ആസ്വദിക്കാം.',
'confirmemail_loggedin'    => 'താങ്കളുടെ ഇമെയില്‍ വിലാസം സ്ഥിരീകരിക്കപ്പെട്ടിരിക്കുന്നു.',
'confirmemail_error'       => 'താങ്കളുടെ സ്ഥിരീകരണം സൂക്ഷിച്ചുവയ്ക്കാനുള്ള ശ്രമത്തിനിടയ്ക്ക് എന്തോ കുഴപ്പം സംഭവിച്ചു.',
'confirmemail_subject'     => '{{SITENAME}} ഇമെയില്‍ വിലാസ സ്ഥിരീകരണം',
'confirmemail_body'        => '$1 എന്ന ഐപി വിലാസത്തില്‍ നിന്നു (ഒരു പക്ഷെ താങ്കളായിരിക്കാം), "$2" എന്ന പേരോടു കൂടിയും ഈ ഇമെയില്‍ വിലാസത്തോടു കൂടിയും {{SITENAME}} സം‌രംഭത്തില്‍ ഒരു അക്കൗണ്ട് സൃഷ്ടിച്ചിരിക്കുന്നു.

ഈ അക്കൗണ്ട് താങ്കളുടേതാണ്‌ എന്നു സ്ഥിരീകരിക്കുവാനും {{SITENAME}} സം‌രംഭത്തില്‍ ഇമെയിലുമായി ബന്ധപ്പെട്ട സേവനങ്ങള്‍ ഉപയോഗിക്കുവാനും താഴെ കാണുന്ന കണ്ണി ബ്രൗസറില്‍ തുറക്കുക.

$3

അക്കൗണ്ട് ഉണ്ടാക്കിയത് താങ്കളല്ലെങ്കില്‍ ഇമെയില്‍ വിലാസ സ്ഥിരീകരണം റദ്ദാക്കുവാന്‍ താഴെയുള്ള കണ്ണി ബ്രൗസറില്‍ തുറക്കുക.  

$5


ഈ സ്ഥിരീകരണ കോഡിന്റെ കാലാവധി  $4 നു തീരും.',
'confirmemail_invalidated' => 'ഇമെയില്‍ വിലാസത്തിന്റെ സ്ഥിരീകരണം റദ്ദാക്കിയിരിക്കുന്നു',
'invalidateemail'          => 'ഇമെയില്‍ വിലാസ സ്ഥിരീകരണം റദ്ദാക്കുക',

# Scary transclusion
'scarytranscludedisabled' => '[അന്തർവിക്കി ഉൾപ്പെടുത്തൽ സജ്ജമല്ല]',
'scarytranscludefailed'   => '[$1നു ഫലകം കണ്ടുപിടിക്കാന്‍ പറ്റിയില്ല]',
'scarytranscludetoolong'  => '[URLനു നീളം വളരെ കൂടുതലാണ്]',

# Trackbacks
'trackbackremove' => '([$1 മായ്ക്കുക])',

# Delete conflict
'deletedwhileediting' => "'''മുന്നറിയിപ്പ്''': താങ്കള്‍ തിരുത്തുവാന്‍ തുടങ്ങിയ ശേഷം താള്‍ മായ്ക്കപ്പെട്ടിരിക്കുന്നു!",
'confirmrecreate'     => "താങ്കള്‍ ഈ താള്‍ തിരുത്താന്‍ തുടങ്ങിയതിനുശേഷം [[User:$1|$1]] ([[User talk:$1|talk]]) എന്ന ഉപയോക്താവ് ഇങ്ങനെ ഒരു കാരണം നല്‍കി ഈ താള്‍ നീക്കം ചെയ്തു:
: ''$2''
ദയവായി താള്‍ പുനഃസൃഷ്ടിക്കേണ്ടതുണ്ടോ എന്ന് സ്ഥിരീകരിക്കുക.",
'recreate'            => 'പുനഃസൃഷ്ടിക്കുക',

# action=purge
'confirm_purge_button' => 'ശരി',
'confirm-purge-top'    => 'ഈ താളിന്റെ കാഷെ ക്ലീയര്‍ ചെയ്യട്ടെ?',

# Multipage image navigation
'imgmultipageprev' => '← മുന്‍പത്തെ താള്‍',
'imgmultipagenext' => 'അടുത്ത താള്‍ →',
'imgmultigo'       => 'പോകൂ!',
'imgmultigoto'     => '$1 താളിലേക്ക് പോകുക',

# Table pager
'ascending_abbrev'         => 'ആരോഹണം',
'descending_abbrev'        => 'ഇറക്കം',
'table_pager_next'         => 'അടുത്ത താള്‍',
'table_pager_prev'         => 'മുന്‍പത്തെ താള്‍',
'table_pager_first'        => 'ആദ്യത്തെ താള്‍',
'table_pager_last'         => 'അവസാനത്തെ താള്‍',
'table_pager_limit'        => 'ഓരോ താളിലും $1 ഇനങ്ങള്‍ വീതം പ്രദര്‍ശിപ്പിക്കുക',
'table_pager_limit_submit' => 'പോകൂ',
'table_pager_empty'        => 'ഫലങ്ങള്‍ ഒന്നുമില്ല',

# Auto-summaries
'autosumm-blank'   => 'താള്‍ ശൂന്യമാക്കി',
'autosumm-replace' => 'താളിലെ വിവരങ്ങള്‍ $1 എന്നാക്കിയിരിക്കുന്നു',
'autoredircomment' => '[[$1]]-താളിലേക്ക് തിരിച്ചുവിടുന്നു',
'autosumm-new'     => "'$1' ഉപയോഗിച്ച് താള്‍ സൃഷ്ടിച്ചിരിക്കുന്നു",

# Live preview
'livepreview-loading' => 'ശേഖരിച്ചുകൊണ്ടിരിക്കുന്നു…',
'livepreview-ready'   => 'ശേഖരിച്ചുകൊണ്ടിരിക്കുന്നു… തയ്യാര്‍!',
'livepreview-failed'  => 'തല്‍സമയ പ്രിവ്യൂ പരാജയപ്പെട്ടു. സാധാരണ പ്രിവ്യൂ പരീക്ഷിക്കുക.',
'livepreview-error'   => 'ബന്ധപ്പെടുവാന്‍ പരാജയപ്പെട്ടു.  $1 "$2". ദയവായി സാധാരണ പ്രിവ്യൂ പരീക്ഷിക്കുക.',

# Friendlier slave lag warnings
'lag-warn-normal' => '$1 {{PLURAL:$1|സെക്കന്റിനുള്ളില്‍|സെക്കന്റുകള്‍ക്കുള്ളില്‍}} നടന്ന തിരുത്തലുകള്‍ ഈ പട്ടികയില്‍ ഉണ്ടാകാനിടയില്ല.',

# Watchlist editor
'watchlistedit-numitems'       => 'താങ്കള്‍ സം‌വാദം താളുകള്‍ ഒഴിച്ച് {{PLURAL:$1|1 താള്‍|$1 താളുകള്‍}} ശ്രദ്ധിക്കുന്നുണ്ട്.',
'watchlistedit-noitems'        => 'താങ്കള്‍ നിലവില്‍ ഒരു താളും ശ്രദ്ധിക്കുന്നില്ല.',
'watchlistedit-normal-title'   => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക തിരുത്തുക',
'watchlistedit-normal-legend'  => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയില്‍ നിന്നും ഒഴിവാക്കുക',
'watchlistedit-normal-explain' => "താങ്കള്‍ ശ്രദ്ധിക്കുന്ന താളുകള്‍ താഴെ കൊടുത്തിരിക്കുന്നു. നീക്കം ചെയ്യേണ്ടവ തിരഞ്ഞെടുത്ത ശേഷം '''തിരഞ്ഞെടുത്തവ നീക്കുക''' എന്ന ബട്ടണില്‍ ഞെക്കിയാല്‍ നീക്കം ചെയ്യപ്പെടുന്നതാണ്‌. താങ്കള്‍ക്ക് [[Special:Watchlist/raw|പട്ടികയുടെ മൂല രൂപം]] തിരുത്തുകയും ചെയ്യാവുന്നതാണ്‌.",
'watchlistedit-normal-submit'  => 'തിരഞ്ഞെടുത്തവ നീക്കുക',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 താള്‍|$1 താളുകള്‍}} താങ്കള്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയില്‍ നിന്നും ഒഴിവാക്കിയിരിക്കുന്നു:',
'watchlistedit-raw-title'      => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയുടെ മൂലരൂപം തിരുത്തുക',
'watchlistedit-raw-legend'     => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയുടെ മൂലരൂപം തിരുത്തുക',
'watchlistedit-raw-explain'    => 'താങ്കളുടെ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിലുള്ള താളുകള്‍ താഴെ കാണിച്ചിരിക്കുന്നു. ഒരു വരിയില്‍ ഒരു താള്‍ മാത്രം വരത്തക്ക രീതിയില്‍ ഈ പട്ടിക തിരുത്തി താളുകള്‍ കൂട്ടിച്ചേര്‍ക്കുകയോ ഒഴിവാക്കുകയോ ചെയ്യാം. തിരുത്തല്‍ പൂര്‍ത്തിയായാല്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക പുതുക്കുക എന്ന ബട്ടന്‍ ഞെക്കുക.

[[Special:Watchlist/edit|ശ്രദ്ധിക്കുന്ന താളിന്റെ പട്ടിക തിരുത്തുക]] എന്ന താള്‍ ഉപയോഗിച്ചും താങ്കള്‍ക്ക് പട്ടിക പുതുക്കാവുന്നതാണ്‌.',
'watchlistedit-raw-titles'     => 'തലക്കെട്ടുകള്‍:',
'watchlistedit-raw-submit'     => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക പുതുക്കുക',
'watchlistedit-raw-done'       => 'താങ്കളുടെ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക പുതുക്കിയിരിക്കുന്നു.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 താള്‍|$1 താളുകള്‍}} പട്ടികയിലേക്കു ചേര്‍ത്തിരിക്കുന്നു:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 താള്‍|$1 താളുകള്‍}} പട്ടികയില്‍ നിന്നു മാറ്റിയിരിക്കുന്നു:',

# Watchlist editing tools
'watchlisttools-view' => 'ബന്ധപ്പെട്ട മാറ്റങ്ങള്‍ കാട്ടുക',
'watchlisttools-edit' => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക കാട്ടുക, തിരുത്തുക',
'watchlisttools-raw'  => 'താങ്കള്‍ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയുടെ മൂലരൂപം തിരുത്തുക',

# Special:Version
'version'                  => 'പതിപ്പ്',
'version-extensions'       => 'ഇന്‍സ്റ്റോൾ ചെയ്തിട്ടുള്ള അനുബന്ധങ്ങൾ',
'version-specialpages'     => 'പ്രത്യേക താളുകള്‍',
'version-variables'        => 'ചരങ്ങള്‍',
'version-other'            => 'മറ്റുള്ളവ',
'version-version'          => '(പതിപ്പ് $1)',
'version-license'          => 'ലൈസന്‍സ്',
'version-software'         => 'ഇന്‍സ്റ്റാള്‍ ചെയ്ത സോഫ്റ്റ്‌വെയര്‍',
'version-software-product' => 'സോഫ്റ്റ്‌വെയര്‍ ഉല്പ്പന്നം',
'version-software-version' => 'വിവരണം',

# Special:FilePath
'filepath'         => 'പ്രമാണത്തിലേക്കുള്ള വിലാസം',
'filepath-page'    => 'പ്രമാണം:',
'filepath-submit'  => 'പാത',
'filepath-summary' => 'ഈ പ്രത്യേക താള്‍ ഒരു പ്രമാണത്തിന്റെ പൂര്‍ണ്ണ വിലാസം പ്രദര്‍ശിപ്പിക്കുന്നു.
ചിത്രങ്ങള്‍ പൂര്‍ണ്ണ റെസലൂഷനോടു കൂടി പ്രദര്‍ശിപ്പിച്ചിരിക്കുന്നു. മറ്റുള്ള ഫയല്‍ തരങ്ങള്‍ അതതു പ്രോഗ്രാമില്‍ നേരിട്ടു തുറക്കാവുന്നതാണ്‌.

"{{ns:file}}:" എന്ന മുന്‍‌കുറി ഇല്ലാതെ പ്രമാണത്തിന്റെ പേരു ടൈപ്പു ചെയൂക.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'ഒരേ പ്രമാണത്തിന്റെ പലപകര്‍പ്പുകളുണ്ടോയെന്നു തിരയുക',
'fileduplicatesearch-summary'  => 'ഒരേ പ്രമാണം തന്നെ വിവിധപേരിലുണ്ടോയെന്നു ഹാഷ് വാല്യൂവധിഷ്ഠിതമായി തിരയുക.

പ്രമാണത്തിന്റെ പേര്‌ "{{ns:file}}:" എന്ന മുന്‍കുറിയില്ലാതെ നല്‍കുക.',
'fileduplicatesearch-legend'   => 'അപരനെ തിരയുക',
'fileduplicatesearch-filename' => 'പ്രമാണത്തിന്റെ പേര്:',
'fileduplicatesearch-submit'   => 'തിരയൂ',
'fileduplicatesearch-result-1' => '"$1" എന്ന പ്രമാണത്തിന് സദൃശ അപരനില്ല.',
'fileduplicatesearch-result-n' => '"$1" എന്ന പ്രമാണത്തിന് {{PLURAL:$2|ഒരു സദൃശ അപരന്‍|$2 സദൃശ അപരർ}} ഉണ്ട്.',

# Special:SpecialPages
'specialpages'                   => 'പ്രത്യേക താളുകള്‍',
'specialpages-note'              => '----
* സര്‍‌വോപയോഗ പ്രത്യേക താളുകള്‍.
* <strong class="mw-specialpagerestricted">ഉപയോഗം പരിമിതപ്പെടുത്തിയിരിക്കുന്ന പ്രത്യേക താളുകള്‍.</strong>',
'specialpages-group-maintenance' => 'പരിചരണം ആവശ്യമായവ',
'specialpages-group-other'       => 'മറ്റു പ്രത്യേക താളുകള്‍',
'specialpages-group-login'       => 'പ്രവേശനം / അംഗത്വം എടുക്കുക',
'specialpages-group-changes'     => 'പുതിയ മാറ്റങ്ങളും രേഖകളും',
'specialpages-group-media'       => 'മീഡിയ രേഖകളും അപ്‌ലോഡുകളും',
'specialpages-group-users'       => 'ഉപയോക്താക്കളും അവകാശങ്ങളും',
'specialpages-group-highuse'     => 'കൂടുതല്‍ ഉപയോഗിക്കപ്പെട്ട താളുകള്‍',
'specialpages-group-pages'       => 'താളുകളുടെ പട്ടിക',
'specialpages-group-pagetools'   => 'താളുകള്‍ക്കുള്ള ഉപകരണങ്ങള്‍',
'specialpages-group-wiki'        => 'വിക്കി വിവരങ്ങളും ഉപകരണങ്ങളും',
'specialpages-group-redirects'   => 'തിരിച്ചുവിടല്‍ സംബന്ധിച്ച പ്രത്യേക താളുകള്‍',
'specialpages-group-spam'        => 'സ്പാം ഉപകരണങ്ങൾ',

# Special:BlankPage
'blankpage'              => 'ശൂന്യതാള്‍',
'intentionallyblankpage' => 'ഈ താള്‍ മനഃപൂര്‍‌വ്വം ശൂന്യമായി ഇട്ടിരിക്കുന്നതാണ്‌.',

# Special:Tags
'tags'                    => 'സാധുവായ മാറ്റങ്ങളുടെ അനുബന്ധങ്ങള്‍',
'tag-filter'              => '[[Special:Tags|അനുബന്ധങ്ങളുടെ]] അരിപ്പ:',
'tag-filter-submit'       => 'അരിപ്പ',
'tags-title'              => 'അനുബന്ധങ്ങള്‍',
'tags-intro'              => 'സോഫ്റ്റ്‌വെയര്‍ അടയാളപ്പെടുത്തിയ തിരുത്തുകളുടെ അനുബന്ധങ്ങളും, അവയുടെ അര്‍ത്ഥവും ഈ താളില്‍ പ്രദര്‍ശിപ്പിക്കുന്നു.',
'tags-tag'                => 'റ്റാഗിന്റെ പേര്‌',
'tags-display-header'     => 'മാറ്റങ്ങളുടെ പട്ടികകളിലെ രൂപം',
'tags-description-header' => 'അര്‍ത്ഥത്തിന്റെ പൂര്‍ണ്ണ വിവരണം',
'tags-hitcount-header'    => 'അനുബന്ധമുള്ള മാറ്റങ്ങള്‍',
'tags-edit'               => 'തിരുത്തുക',
'tags-hitcount'           => '$1 {{PLURAL:$1|മാറ്റം|മാറ്റങ്ങള്‍}}',

# Database error messages
'dberr-header'    => 'ഈ വിക്കിയില്‍ പ്രശ്നമുണ്ട്',
'dberr-problems'  => 'ക്ഷമിക്കണം! ഈ സൈറ്റിന് സങ്കേതിക തകരാറുകള്‍ അനുഭവപ്പെടുന്നുണ്ട്.',
'dberr-again'     => 'കുറച്ച് മിനിട്ടുകള്‍ കാത്തിരുന്ന് വീണ്ടും തുറക്കുവാന്‍ ശ്രമിക്കുക.',
'dberr-info'      => '($1 എന്ന വിവരശേഖര സെര്‍വറുമായി ബന്ധപ്പെടാന്‍ പറ്റിയില്ല)',
'dberr-usegoogle' => 'അതേസമയം താങ്കള്‍ക്ക് ഗൂഗിള്‍ വഴി തിരയുവാന്‍ ശ്രമിക്കാവുന്നതാണ്.',

# HTML forms
'htmlform-invalid-input'       => 'താങ്കള്‍ നല്‍കിയ ചില വിവരങ്ങളില്‍ അപാകതകളുണ്ട്',
'htmlform-select-badoption'    => 'താങ്കള്‍ നല്‍കിയ വില ഒരു സ്വീകാര്യമായ ഉപാധിയല്ല.',
'htmlform-int-invalid'         => 'താങ്കള്‍ നല്‍കിയ വില ഒരു പൂര്‍ണ്ണസംഖ്യയല്ല.',
'htmlform-float-invalid'       => 'താങ്കള്‍ നല്‍കിയ വില ഒരു അക്കമല്ല.',
'htmlform-int-toolow'          => 'താങ്കള്‍ നല്‍കിയത് ഏറ്റവും കുറഞ്ഞ വിലയായ $1-നു താഴെയാണ്',
'htmlform-int-toohigh'         => 'താങ്കള്‍ നല്‍കിയത് ഏറ്റവും കൂടിയ വിലയായ $1-നു മുകളിലാണ്',
'htmlform-submit'              => 'സമര്‍പ്പിക്കുക',
'htmlform-reset'               => 'മാറ്റങ്ങള്‍ വേണ്ട',
'htmlform-selectorother-other' => 'മറ്റുള്ളവ',

# Add categories per AJAX
'ajax-add-category'            => 'വർഗ്ഗം കൂട്ടിച്ചേർക്കുക',
'ajax-add-category-submit'     => 'കൂട്ടിച്ചേർക്കുക',
'ajax-confirm-title'           => 'പ്രവൃത്തി സ്ഥിരീകരിക്കുക',
'ajax-confirm-prompt'          => 'താങ്കള്‍ക്ക് തിരുത്തലിന്റെ ചുരുക്കം താഴെ നല്‍കാവുന്നതാണ്‌.
"സേവ് ചെയ്യുക" എന്നത് ഞെക്കി താങ്കളുടെ തിരുത്തല്‍ സേവ് ചെയ്യാവുന്നതാണ്‌.',
'ajax-confirm-save'            => 'സേവ് ചെയ്യുക',
'ajax-add-category-summary'    => 'വര്‍ഗ്ഗം "$1" കൂട്ടിച്ചേര്‍ക്കുക',
'ajax-remove-category-summary' => 'വര്‍ഗ്ഗം "$1" നീക്കംചെയ്യുക',
'ajax-confirm-actionsummary'   => 'ചെയ്യേണ്ട പ്രവൃത്തി:',
'ajax-error-title'             => 'പിശക്',
'ajax-error-dismiss'           => 'ശരി',
'ajax-remove-category-error'   => 'ഈ വർഗ്ഗം മാറ്റാൻ കഴിയില്ല.
ഫലകങ്ങളുപയോഗിച്ചാണ് വർഗ്ഗം ചേർത്തിരിക്കുന്നതെങ്കിൽ സാധാരണ ഇങ്ങിനെ സംഭവിക്കാറുണ്ട്.',

);

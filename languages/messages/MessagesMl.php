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
 * @author Naveen Sankar
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
	'വർഗ്ഗം' => NS_CATEGORY,
	'വർഗ്ഗത്തിന്റെ_സംവാദം' => NS_CATEGORY_TALK,
	'സ' => NS_HELP,
	'സസം' => NS_HELP_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'ഇരട്ടത്തിരിച്ചുവിടലുകൾ' ),
	'BrokenRedirects'           => array( 'പൊട്ടിയതിരിച്ചുവിടലുകൾ' ),
	'Disambiguations'           => array( 'വിവക്ഷിതങ്ങൾ' ),
	'Userlogin'                 => array( 'പ്രവേശനം' ),
	'Userlogout'                => array( 'പുറത്തുകടക്കൽ' ),
	'CreateAccount'             => array( 'അംഗത്വമെടുക്കൽ' ),
	'Preferences'               => array( 'ക്രമീകരണങ്ങൾ' ),
	'Watchlist'                 => array( 'ശ്രദ്ധിക്കുന്നവ' ),
	'Recentchanges'             => array( 'സമീപകാലമാറ്റങ്ങൾ' ),
	'Upload'                    => array( 'അപ്‌ലോഡ്' ),
	'Listfiles'                 => array( 'പ്രമാണങ്ങളുടെ പട്ടിക', 'ചിത്രങ്ങളുടെ പട്ടിക' ),
	'Newimages'                 => array( 'പുതിയ പ്രമാണങ്ങൾ', 'പുതിയ ചിത്രങ്ങൾ' ),
	'Listusers'                 => array( 'ഉപയോക്താക്കളുടെ പട്ടിക' ),
	'Listgrouprights'           => array( 'സമൂഹത്തിന്റെ അവകാശങ്ങളുടെ പട്ടിക' ),
	'Statistics'                => array( 'സ്ഥിതിവിവരം' ),
	'Randompage'                => array( 'ക്രമരഹിതമായി', 'താളുകൾ ക്രമരഹിതമായി' ),
	'Lonelypages'               => array( 'അനാഥ താളുകൾ' ),
	'Uncategorizedpages'        => array( 'വർഗ്ഗീകരിക്കാത്ത താളുകൾ' ),
	'Uncategorizedcategories'   => array( 'വർഗ്ഗീകരിക്കാത്ത വർഗ്ഗങ്ങൾ' ),
	'Uncategorizedimages'       => array( 'വർഗ്ഗീകരിക്കാത്ത പ്രമാണങ്ങൾ' ),
	'Uncategorizedtemplates'    => array( 'വർഗ്ഗീകരിക്കാത്ത ഫലകങ്ങൾ' ),
	'Unusedcategories'          => array( 'ഉപയോഗിക്കാത്ത വർഗ്ഗങ്ങൾ' ),
	'Unusedimages'              => array( 'ഉപയോഗിക്കാത്ത പ്രമാണങ്ങൾ' ),
	'Wantedpages'               => array( 'ആവശ്യമുള്ള താളുകൾ', 'പൊട്ടിയ കണ്ണികൾ' ),
	'Wantedcategories'          => array( 'ആവശ്യമുള്ള വർഗ്ഗങ്ങൾ' ),
	'Wantedfiles'               => array( 'ആവശ്യമുള്ള പ്രമാണങ്ങൾ' ),
	'Wantedtemplates'           => array( 'ആവശ്യമുള്ള ഫലകങ്ങൾ' ),
	'Mostlinked'                => array( 'കൂടുതൽ കണ്ണികളുള്ള താളുകൾ', 'കൂടുതൽ കണ്ണികളുള്ളവ' ),
	'Mostlinkedcategories'      => array( 'കൂടുതൽ കണ്ണികളുള്ള വർഗ്ഗങ്ങൾ', 'കൂടുതൽ ഉപയോഗിച്ചിട്ടുള്ള വർഗ്ഗങ്ങൾ' ),
	'Mostlinkedtemplates'       => array( 'കൂടുതൽ കണ്ണികളുള്ള ഫലകങ്ങൾ', 'കൂടുതൽ ഉപയോഗിച്ചിട്ടുള്ള ഫലകങ്ങൾ' ),
	'Mostimages'                => array( 'കൂടുതൽ കണ്ണികളുള്ള പ്രമാണങ്ങൾ', 'കൂടുതൽ പ്രമാണങ്ങൾ', 'കൂടുതൽ ചിത്രങ്ങൾ' ),
	'Mostcategories'            => array( 'കൂടുതൽ വർഗ്ഗങ്ങൾ' ),
	'Mostrevisions'             => array( 'കൂടുതൽ പുനരവലോകനങ്ങൾ' ),
	'Fewestrevisions'           => array( 'കുറഞ്ഞ പുനരവലോകനങ്ങൾ' ),
	'Shortpages'                => array( 'ചെറിയ താളുകൾ' ),
	'Longpages'                 => array( 'വലിയ താളുകൾ' ),
	'Newpages'                  => array( 'പുതിയ താളുകൾ' ),
	'Ancientpages'              => array( 'പുരാതന താളുകൾ' ),
	'Deadendpages'              => array( 'അന്ത്യസ്ഥാനത്തുള്ള താളുകൾ' ),
	'Protectedpages'            => array( 'സംരക്ഷിത താളുകൾ' ),
	'Protectedtitles'           => array( 'സംരക്ഷിത ശീർഷകങ്ങൾ' ),
	'Allpages'                  => array( 'എല്ലാതാളുകളും' ),
	'Prefixindex'               => array( 'പൂർവ്വപദസൂചിക' ),
	'Ipblocklist'               => array( 'തടയൽ‌പട്ടിക', 'ഐപികളുടെ തടയൽ‌പട്ടിക' ),
	'Unblock'                   => array( 'തടയൽനീക്കുക' ),
	'Specialpages'              => array( 'പ്രത്യേകതാളുകൾ' ),
	'Contributions'             => array( 'സംഭാവനകൾ' ),
	'Emailuser'                 => array( 'ഉപയോക്തൃഇമെയിൽ' ),
	'Confirmemail'              => array( 'ഇമെയിൽ സ്ഥിരീകരിക്കുക' ),
	'Whatlinkshere'             => array( 'കണ്ണികളെന്തെല്ലാം' ),
	'Recentchangeslinked'       => array( 'ബന്ധപ്പെട്ട മാറ്റങ്ങൾ' ),
	'Movepage'                  => array( 'താൾ മാറ്റുക' ),
	'Blockme'                   => array( 'എന്നെതടയുക' ),
	'Booksources'               => array( 'പുസ്തകസ്രോതസ്സുകൾ' ),
	'Categories'                => array( 'വർഗ്ഗങ്ങൾ' ),
	'Export'                    => array( 'കയറ്റുമതി' ),
	'Version'                   => array( 'പതിപ്പ്' ),
	'Allmessages'               => array( 'സർവ്വസന്ദേശങ്ങൾ' ),
	'Log'                       => array( 'രേഖ', 'രേഖകൾ' ),
	'Blockip'                   => array( 'തടയുക', 'ഐപിയെ തടയുക', 'ഉപയോക്താവിനെ തടയുക' ),
	'Undelete'                  => array( 'മായ്ച്ചവ പുനഃസ്ഥാപനം' ),
	'Import'                    => array( 'ഇറക്കുമതി' ),
	'Lockdb'                    => array( 'ഡി.ബി.ബന്ധിക്കുക' ),
	'Unlockdb'                  => array( 'ഡി.ബി.ബന്ധനംനീക്കുക' ),
	'Userrights'                => array( 'ഉപയോക്തൃഅവകാശങ്ങൾ', 'കാര്യനിർവാഹകസൃഷ്ടി', 'യന്ത്രസൃഷ്ടി' ),
	'MIMEsearch'                => array( 'മൈംതിരയൽ' ),
	'FileDuplicateSearch'       => array( 'പ്രമാണത്തിന്റെ അപരനുള്ള തിരച്ചിൽ' ),
	'Unwatchedpages'            => array( 'ആരുംശ്രദ്ധിക്കാത്തതാളുകൾ' ),
	'Listredirects'             => array( 'തിരിച്ചുവിടൽ‌പട്ടിക' ),
	'Revisiondelete'            => array( 'നാൾപ്പതിപ്പ് മായ്ക്കൽ' ),
	'Unusedtemplates'           => array( 'ഉപയോഗിക്കാത്തഫലകങ്ങൾ' ),
	'Randomredirect'            => array( 'ക്രമരഹിതതിരിച്ചുവിടലുകൾ' ),
	'Mypage'                    => array( 'എന്റെ താൾ' ),
	'Mytalk'                    => array( 'എന്റെ സംവാദം' ),
	'Mycontributions'           => array( 'എന്റെ സംഭാവനകൾ' ),
	'Listadmins'                => array( 'കാര്യനിർവാഹകപട്ടിക' ),
	'Listbots'                  => array( 'യന്ത്രങ്ങളുടെ പട്ടിക' ),
	'Popularpages'              => array( 'ജനകീയതാളുകൾ' ),
	'Search'                    => array( 'അന്വേഷണം' ),
	'Resetpass'                 => array( 'രഹസ്യവാക്ക് മാറ്റുക' ),
	'Withoutinterwiki'          => array( 'അന്തർവിക്കിയില്ലാത്തവ' ),
	'MergeHistory'              => array( 'നാൾവഴിലയിപ്പിക്കുക' ),
	'Filepath'                  => array( 'പ്രമാണവിലാസം' ),
	'Invalidateemail'           => array( 'സാധുവല്ലാത്ത ഇമെയിൽ' ),
	'Blankpage'                 => array( 'ശൂന്യതാൾ' ),
	'LinkSearch'                => array( 'കണ്ണികൾ തിരയുക' ),
	'DeletedContributions'      => array( 'മായ്ച്ച സേവനങ്ങൾ' ),
	'Tags'                      => array( 'റ്റാഗുകൾ' ),
	'Activeusers'               => array( 'സജീവ ഉപയോക്താക്കൾ' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#തിരിച്ചുവിടുക', '#തിരിച്ചുവിടൽ', '#REDIRECT' ),
	'notoc'                 => array( '0', '__ഉള്ളടക്കംവേണ്ട__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__ചിത്രസഞ്ചയംവേണ്ട__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__ഉള്ളടക്കംഇടുക__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__ഉള്ളടക്കം__', '__TOC__' ),
	'noeditsection'         => array( '0', '__സംശോധിക്കേണ്ട__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__തലക്കെട്ടുവേണ്ട__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'ഈമാസം', 'ഈമാസം2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'ഈമാസം1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'ഈമാസത്തിന്റെപേര്‌', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'ഈമാസത്തിന്റെപേരുസൃഷ്ടിക്കുക', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'ഈമാസത്തിന്റെപേര്‌സംഗ്രഹം', 'ഈമാസത്തിന്റെപേര്‌ചുരുക്കം', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'ഈദിവസം', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'ഈദിവസം2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'ഈദിവസത്തിന്റെപേര്‌', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ഈവർഷം', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'ഈസമയം', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'ഈമണിക്കൂർ', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'പ്രാദേശികമാസം', 'പ്രാദേശികമാസം2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'പ്രാദേശികമാസം1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'പ്രാദേശികമാസത്തിന്റെപേര്‌', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'പ്രാദേശികമാസത്തിന്റെപേരുസൃഷ്ടിക്കുക', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'പ്രാദേശികമാസത്തിന്റെപേര്‌സംഗ്രഹം', 'പ്രാദേശികമാസത്തിന്റെപേര്‌ചുരുക്കം', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'പ്രാദേശികദിവസം', 'LOCALDAY' ),
	'localday2'             => array( '1', 'പ്രാദേശികദിവസം2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'പ്രാദേശികദിവസത്തിന്റെപേര്‌', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'പ്രാദേശികവർഷം', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'പ്രാദേശികസമയം', 'LOCALTIME' ),
	'localhour'             => array( '1', 'പ്രാദേശികമണിക്കൂർ', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'താളുകളുടെയെണ്ണം', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'ലേഖങ്ങനളുടെയെണ്ണം', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'പ്രമാണങ്ങളുടെയെണ്ണം', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'ഉപയോക്താക്കളുടെയെണ്ണം', 'അംഗങ്ങളുയെണ്ണം', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'സജീവോപയാക്താക്കളുടെയെണ്ണം', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'തിരുത്തലുകളുടെണ്ണം', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'എടുത്തുനോക്കലുകളുടെണ്ണം', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'താളിന്റെപേര്‌', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'താളിന്റെപേര്‌സമഗ്രം', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'നാമമേഖല', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'നാമമേഖലസമഗ്രം', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'സംവാദമേഖല', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'സംവാദമേഖലസമഗ്രം', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'വിഷയമേഖല', 'ലേഖനമേഖല', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'വിഷയമേഖലസമഗ്രം', 'ലേഖനമേഖലസമഗ്രം', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'താളിന്റെമുഴുവൻപേര്‌', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'താളിന്റെമുഴുവൻപേര്സമഗ്രം', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'അനുബന്ധതാളിന്റെപേര്‌', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'അനുബന്ധതാളിന്റെപേര്സമഗ്രം', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'അടിസ്ഥാനതാളിന്റെപേര്‌', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'അടിസ്ഥാനതാളിന്റെപേര്‌സമഗ്രം', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'സംവാദതാളിന്റെപേര്‌', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'സംവാദതാളിന്റെപേര്‌സമഗ്രം', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'ലേഖനതാളിന്റെപേര്‌', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'ലേഖനതാളിന്റെപേര്‌സമഗ്രം', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                   => array( '0', 'സന്ദേശം:', 'MSG:' ),
	'subst'                 => array( '0', 'ബദൽ:', 'SUBST:' ),
	'msgnw'                 => array( '0', 'മൂലരൂപം:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'ലഘുചിത്രം', 'ലഘു', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'ലഘുചിത്രം=$1', 'ലഘു=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'വലത്ത്‌', 'വലത്‌', 'right' ),
	'img_left'              => array( '1', 'ഇടത്ത്‌', 'ഇടത്‌', 'left' ),
	'img_none'              => array( '1', 'ശൂന്യം', 'none' ),
	'img_width'             => array( '1', '$1ബിന്ദു', '$1px' ),
	'img_center'            => array( '1', 'നടുവിൽ', 'നടുക്ക്‌', 'center', 'centre' ),
	'img_framed'            => array( '1', 'ചട്ടം', 'ചട്ടത്തിൽ', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'ചട്ടരഹിതം', 'frameless' ),
	'img_page'              => array( '1', 'താൾ=$1', 'താൾ $1', 'page=$1', 'page $1' ),
	'img_border'            => array( '1', 'അതിർവര', 'border' ),
	'img_top'               => array( '1', 'മേലെ', 'top' ),
	'img_text_top'          => array( '1', 'എഴുത്ത്-മേലെ', 'text-top' ),
	'img_middle'            => array( '1', 'മദ്ധ്യം', 'middle' ),
	'img_bottom'            => array( '1', 'താഴെ', 'bottom' ),
	'img_text_bottom'       => array( '1', 'എഴുത്ത്-താഴെ', 'text-bottom' ),
	'img_link'              => array( '1', 'കണ്ണി=$1', 'link=$1' ),
	'img_alt'               => array( '1', 'പകരം=$1', 'alt=$1' ),
	'sitename'              => array( '1', 'സൈറ്റിന്റെപേര്', 'SITENAME' ),
	'ns'                    => array( '0', 'നാമേ:', 'NS:' ),
	'localurl'              => array( '0', 'ലോക്കൽയുആർഎൽ:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'ലോക്കൽയുആർഎൽഇ:', 'LOCALURLE:' ),
	'server'                => array( '0', 'സെർവർ', 'SERVER' ),
	'servername'            => array( '0', 'സെർവറിന്റെപേര്', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'സ്ക്രിപ്റ്റ്പാത്ത്', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'വ്യാകരണം:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'ലിംഗം:', 'GENDER:' ),
	'currentweek'           => array( '1', 'ആഴ്ച', 'ആഴ്‌ച', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'ദിവസത്തിന്റെപേര്‌അക്കത്തിൽ', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'പ്രാദേശികആഴ്ച', 'പ്രാദേശികആഴ്‌ച', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'ആഴ്ചയുടെപേര്‌അക്കത്തിൽ', 'ആഴ്‌ചയുടെപേര്‌അക്കത്തിൽ', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'തിരുത്തൽഅടയാളം', 'REVISIONID' ),
	'revisionday'           => array( '1', 'തിരുത്തിയദിവസം', 'തിരുത്തിയദിനം', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'തിരുത്തിയദിവസം2', 'തിരുത്തിയദിനം2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'തിരുത്തിയമാസം', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'തിരുത്തിയവർഷം', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'തിരുത്തിയസമയമുദ്ര', 'REVISIONTIMESTAMP' ),
	'plural'                => array( '0', 'ബഹുവചനം:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'പൂർണ്ണവിലാസം:', 'FULLURL:' ),
	'raw'                   => array( '0', 'അസംസ്കൃതം:', 'RAW:' ),
	'displaytitle'          => array( '1', 'ശീർഷകംപ്രദർശിപ്പിക്കുക', 'തലക്കെട്ട്പ്രദർശിപ്പിക്കുക', 'DISPLAYTITLE' ),
	'rawsuffix'             => array( '1', 'വ', 'R' ),
	'newsectionlink'        => array( '1', '__പുതിയവിഭാഗംകണ്ണി__', '__പുതിയഖണ്ഡിക്കണ്ണി__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '__പുതിയവിഭാഗംകണ്ണിവേണ്ട__', '__പുതിയഖണ്ഡിക്കണ്ണിവേണ്ട__', '__NONEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'ഈപതിപ്പ്', 'CURRENTVERSION' ),
	'currenttimestamp'      => array( '1', 'സമയമുദ്ര', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'പ്രാദേശികസമയമുദ്ര', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'ദിശാസൂചിക', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#ഭാഷ:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'ഉള്ളടക്കഭാഷ', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'നാമമേഖലയിലുള്ളതാളുകൾ', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'കാര്യനിർവ്വാഹകരുടെഎണ്ണം', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'ദശാംശഘടന', 'സംഖ്യാഘടന', 'FORMATNUM' ),
	'padleft'               => array( '0', 'ഇടത്ത്നിറക്കുക', 'PADLEFT' ),
	'padright'              => array( '0', 'വലത്ത്നിറക്കുക', 'PADRIGHT' ),
	'special'               => array( '0', 'പ്രത്യേകം', 'special' ),
	'defaultsort'           => array( '1', 'സ്വതവേയുള്ളക്രമപ്പെടുത്തൽ:', 'സ്വതവേയുള്ളക്രമപ്പെടുത്തൽചാവി:', 'സ്വതവേയുള്ളവർഗ്ഗക്രമപ്പെടുത്തൽ:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'പ്രമാണപഥം:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'റ്റാഗ്', 'tag' ),
	'hiddencat'             => array( '1', '‌‌__മറഞ്ഞിരിക്കുംവർഗ്ഗം__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'വർഗ്ഗത്തിലുള്ളതാളുകൾ', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'താൾവലിപ്പം', 'PAGESIZE' ),
	'index'                 => array( '1', '‌‌__സൂചിക__', '__INDEX__' ),
	'noindex'               => array( '1', '__സൂചികവേണ്ട__', '__NOINDEX__' ),
	'protectionlevel'       => array( '1', 'സംരക്ഷണതലം', 'PROTECTIONLEVEL' ),
	'formatdate'            => array( '0', 'ദിനരേഖീകരണരീതി', 'ദിവസരേഖീകരണരീതി', 'formatdate', 'dateformat' ),
	'url_path'              => array( '0', 'പഥം', 'PATH' ),
	'url_wiki'              => array( '0', 'വിക്കി', 'WIKI' ),
	'url_query'             => array( '0', 'ക്വറി', 'QUERY' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'കണ്ണികൾക്ക് അടിവരയിടുക:',
'tog-highlightbroken'         => 'നിലവിലില്ലാത്ത കണ്ണികൾ <a href="" class="new">ഇങ്ങനെ</a> അടയാളപ്പെടുത്തുക (അഥവാ: ഇങ്ങനെ <a href="" class="internal">?</a>).',
'tog-justify'                 => 'ഖണ്ഡികകളുടെ അരികുകൾ നേരെയാക്കുക',
'tog-hideminor'               => 'പുതിയ മാറ്റങ്ങളുടെ പട്ടികയിൽ ചെറിയ തിരുത്തലുകൾ പ്രദർശിപ്പിക്കാതിരിക്കുക',
'tog-hidepatrolled'           => 'റോന്തുചുറ്റിയ തിരുത്തുകൾ പുതിയമാറ്റങ്ങളിൽ പ്രദർശിപ്പിക്കാതിരിക്കുക',
'tog-newpageshidepatrolled'   => 'റോന്തുചുറ്റിയ താളുകൾ പുതിയതാളുകളുടെ പട്ടികയിൽ പ്രദർശിപ്പിക്കാതിരിക്കുക',
'tog-extendwatchlist'         => 'എല്ലാ മാറ്റങ്ങളും ദൃശ്യമാകുന്ന വിധത്തിൽ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക വികസിപ്പിക്കുക, ഏറ്റവും പുതിയവ മാത്രമല്ല',
'tog-usenewrc'                => 'വിപുലീകരിച്ച പുതിയ മാറ്റങ്ങൾ ഉപയോഗിക്കുക (ജാവാസ്ക്രിപ്റ്റ് ആവശ്യമാണ്)',
'tog-numberheadings'          => 'ഉപവിഭാഗങ്ങൾക്ക് ക്രമസംഖ്യ കൊടുക്കുക',
'tog-showtoolbar'             => 'തിരുത്തൽ റ്റൂൾബാർ  പ്രദർശിപ്പിക്കുക (ജാവാസ്ക്രിപ്റ്റ്)',
'tog-editondblclick'          => 'താളുകളിൽ ഇരട്ട ക്ലിക്ക് ചെയ്യുമ്പോൾ തിരുത്താനനുവദിക്കുക (ജാവാസ്ക്രിപ്റ്റ്)',
'tog-editsection'             => 'ഉപവിഭാഗങ്ങളുടെ തിരുത്തൽ [തിരുത്തുക] എന്ന കണ്ണിയുപയോഗിച്ച് ചെയ്യുവാൻ അനുവദിക്കുക',
'tog-editsectiononrightclick' => 'ഉപവിഭാഗങ്ങളുടെ തലക്കെട്ടിൽ റൈറ്റ് ക്ലിക്ക് ചെയ്യുന്നതു വഴി തിരുത്താനനുവദിക്കുക (ജാവാസ്ക്രിപ്റ്റ്)',
'tog-showtoc'                 => 'ഉള്ളടക്കപ്പട്ടിക പ്രദർശിപ്പിക്കുക (മൂന്നിൽ കൂടുതൽ ഉപശീർഷകങ്ങളുള്ള താളുകൾക്കു മാത്രം)',
'tog-rememberpassword'        => 'എന്റെ പ്രവേശം ഈ കമ്പ്യൂട്ടറിൽ ഓർത്തുവെക്കുക',
'tog-watchcreations'          => 'ഞാൻ സൃഷ്ടിക്കുന്ന താളുകൾ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിൽ ചേർക്കുക',
'tog-watchdefault'            => 'ഞാൻ തിരുത്തുന്ന താളുകൾ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിൽ ചേർക്കുക',
'tog-watchmoves'              => 'ഞാൻ തലക്കെട്ടു മാറ്റുന്ന താളുകൾ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിൽ ചേർക്കുക',
'tog-watchdeletion'           => 'ഞാൻ നീക്കം ചെയ്യുന്ന താളുകൾ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിൽ ചേർക്കുക',
'tog-minordefault'            => 'എല്ലാ തിരുത്തലുകളും ചെറുതിരുത്തലുകളായി സ്വയം അടയാളപ്പെടുത്തുക',
'tog-previewontop'            => 'തിരുത്തൽ പെട്ടിക്കു മുകളിൽ പ്രിവ്യൂ കാണിക്കുക',
'tog-previewonfirst'          => 'ആദ്യത്തെ തിരുത്തലിന്റെ പ്രിവ്യൂ കാണിക്കുക',
'tog-nocache'                 => 'താളുകൾ തദ്ദേശീയമായി സംഭരിച്ചുവയ്ക്കുന്നത് നിർത്തലാക്കുക',
'tog-enotifwatchlistpages'    => 'ഞാൻ ശ്രദ്ധിക്കുന്ന താളുകൾക്കു മാറ്റം സംഭവിച്ചാൽ എനിക്കു ഇമെയിൽ അയക്കുക',
'tog-enotifusertalkpages'     => 'എന്റെ സം‌വാദം താളിനു മാറ്റം സംഭവിച്ചാൽ ഇമെയിൽ അയക്കുക',
'tog-enotifminoredits'        => 'ചെറുതിരുത്തലുകൾക്കും എനിക്ക് ഇമെയിൽ അയയ്ക്കുക',
'tog-enotifrevealaddr'        => 'വിജ്ഞാപന മെയിലുകളിൽ എന്റെ ഇമെയിൽ വിലാസം വെളിവാക്കാൻ അനുവദിക്കുക',
'tog-shownumberswatching'     => 'ശ്രദ്ധിക്കുന്ന ഉപയോക്താക്കളുടെ എണ്ണം കാണിക്കുക',
'tog-oldsig'                  => 'നിലവിലുള്ള ഒപ്പ് എങ്ങനെയുണ്ടെന്നു കാണുക:',
'tog-fancysig'                => 'ഒപ്പ് ഒരു വിക്കിടെക്സ്റ്റായി പരിഗണിക്കുക (കണ്ണി സ്വയം ചേർക്കേണ്ടതില്ല)',
'tog-externaleditor'          => 'തിരുത്തലുകൾക്കായി ബാഹ്യ ഉപകരണങ്ങൾ സ്വതവേ ഉപയോഗിക്കുക',
'tog-externaldiff'            => 'വ്യത്യാസം അറിയാനായി ബാഹ്യ ഉപകരണങ്ങൾ സ്വതവേ ഉപയോഗിക്കുക',
'tog-showjumplinks'           => '"പോവുക" ഗമ്യത കണ്ണികൾ പ്രാപ്തമാക്കുക',
'tog-uselivepreview'          => 'തത്സമയ പ്രിവ്യൂ ഉപയോഗപ്പെടുത്തുക (ജാവാസ്ക്രിപ്റ്റ്) (പരീക്ഷണാടിസ്ഥാനത്തിലുള്ളത്)',
'tog-forceeditsummary'        => 'തിരുത്തലുകളുടെ ചുരുക്കം നൽകിയില്ലെങ്കിൽ എന്നെ ഓർമ്മിപ്പിക്കുക',
'tog-watchlisthideown'        => 'ഞാൻ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിൽനിന്ന് എന്റെ തിരുത്തലുകൾ മറയ്ക്കുക',
'tog-watchlisthidebots'       => 'ഞാൻ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിൽനിന്ന് യന്ത്രങ്ങൾ വരുത്തിയ തിരുത്തലുകൾ മറയ്ക്കുക',
'tog-watchlisthideminor'      => 'ഞാൻ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിൽനിന്ന് ചെറുതിരുത്തലുകൾ മറയ്ക്കുക',
'tog-watchlisthideliu'        => 'ഞാൻ ശ്രദ്ധിക്കുന്ന താളുകളിലെ മാറ്റങ്ങളിൽ നിന്നും ലോഗിൻ ചെയ്തിട്ടുള്ളവരുടെ തിരുത്തലുകൾ മറയ്ക്കുക',
'tog-watchlisthideanons'      => 'ഞാൻ ശ്രദ്ധിക്കുന്ന താളുകളിലെ മാറ്റങ്ങളിൽ നിന്നും അജ്ഞാത ഉപയോക്താക്കളുടെ തിരുത്തുകൾ മറയ്ക്കുക',
'tog-watchlisthidepatrolled'  => 'റോന്തുചുറ്റിയ തിരുത്തുകൾ ശ്രദ്ധിക്കുന്നവയിൽ പ്രദർശിപ്പിക്കാതിരിക്കുക',
'tog-ccmeonemails'            => 'ഞാൻ മറ്റുള്ളവർക്കയക്കുന്ന ഇമെയിലുകളുടെ ഒരു പകർപ്പ് എനിക്കും അയക്കുക',
'tog-diffonly'                => 'രണ്ട് പതിപ്പുകൾ തമ്മിലുള്ള വ്യത്യാസത്തിനു താഴെ താളിന്റെ ഉള്ളടക്കം കാണിക്കരുത്.',
'tog-showhiddencats'          => 'മറഞ്ഞിരിക്കുന്ന വർഗ്ഗങ്ങളെ കാണിക്കുക',
'tog-norollbackdiff'          => 'റോൾബാക്കിനു ശേഷം വ്യത്യാസം കാണിക്കാതിരിക്കുക',

'underline-always'  => 'എല്ലായ്പ്പോഴും',
'underline-never'   => 'ഒരിക്കലും അരുത്',
'underline-default' => 'ബ്രൗസറിലേതു പോലെ',

# Font style option in Special:Preferences
'editfont-style'     => 'തിരുത്തൽ മേഖലയിലെ ഫോണ്ടിന്റെ ശൈലി',
'editfont-default'   => 'ബ്രൗസറിലേതു പോലെ',
'editfont-monospace' => 'മോണോസ്പേസ്ഡ് ഫോണ്ട്',
'editfont-sansserif' => 'സാൻസ്-സെറിഫ് ഫോണ്ട്',
'editfont-serif'     => 'സെറിഫ് ഫോണ്ട്',

# Dates
'sunday'        => 'ഞായർ',
'monday'        => 'തിങ്കൾ',
'tuesday'       => 'ചൊവ്വ',
'wednesday'     => 'ബുധൻ',
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
'march'         => 'മാർച്ച്',
'april'         => 'ഏപ്രിൽ',
'may_long'      => 'മേയ്',
'june'          => 'ജൂൺ',
'july'          => 'ജൂലൈ',
'august'        => 'ഓഗസ്റ്റ്',
'september'     => 'സെപ്റ്റംബർ',
'october'       => 'ഒക്ടോബർ',
'november'      => 'നവംബർ',
'december'      => 'ഡിസംബർ',
'january-gen'   => 'ജനുവരി',
'february-gen'  => 'ഫെബ്രുവരി',
'march-gen'     => 'മാർച്ച്',
'april-gen'     => 'ഏപ്രിൽ',
'may-gen'       => 'മേയ്',
'june-gen'      => 'ജൂൺ',
'july-gen'      => 'ജൂലൈ',
'august-gen'    => 'ഓഗസ്റ്റ്',
'september-gen' => 'സെപ്റ്റംബർ',
'october-gen'   => 'ഒക്ടോബർ',
'november-gen'  => 'നവംബർ',
'december-gen'  => 'ഡിസംബർ',
'jan'           => 'ജനു.',
'feb'           => 'ഫെബ്രു.',
'mar'           => 'മാർ.',
'apr'           => 'ഏപ്രി.',
'may'           => 'മേയ്‌',
'jun'           => 'ജൂൺ',
'jul'           => 'ജൂലൈ',
'aug'           => 'ഓഗ.',
'sep'           => 'സെപ്റ്റം.',
'oct'           => 'ഒക്ടോ.',
'nov'           => 'നവം.',
'dec'           => 'ഡിസം.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|വർഗ്ഗം|വർഗ്ഗങ്ങൾ}}',
'category_header'                => '"$1" എന്ന വർഗ്ഗത്തിലെ താളുകൾ',
'subcategories'                  => 'ഉപവർഗ്ഗങ്ങൾ',
'category-media-header'          => '"$1" എന്ന വർഗ്ഗത്തിലെ പ്രമാണങ്ങൾ',
'category-empty'                 => "''ഈ വർഗ്ഗത്തിൽ താളുകളോ പ്രമാണങ്ങളോ ഇല്ല.''",
'hidden-categories'              => '{{PLURAL:$1|മറഞ്ഞിരിക്കുന്ന വർഗ്ഗം|മറഞ്ഞിരിക്കുന്ന വർഗ്ഗങ്ങൾ}}',
'hidden-category-category'       => 'മറഞ്ഞിരിക്കുന്ന വർഗ്ഗങ്ങൾ',
'category-subcat-count'          => '{{PLURAL:$2|ഈ വർഗ്ഗത്തിനു്‌ താഴെ പ്രദർശിപ്പിച്ചിരിക്കുന്ന ഒരു ഉപവർഗ്ഗം മാത്രമേ ഉള്ളൂ.|ഈ വർഗ്ഗത്തിനു്‌ മൊത്തം $2 ഉപവർഗ്ഗങ്ങളുള്ളതിൽ {{PLURAL:$1|ഒരു ഉപവർഗ്ഗം|$1 ഉപവർഗ്ഗങ്ങൾ}} താഴെ പ്രദർശിപ്പിച്ചിരിക്കുന്നു.}}',
'category-subcat-count-limited'  => 'ഈ വർഗ്ഗത്തിനു താഴെ കാണുന്ന {{PLURAL:$1|ഉപവർഗ്ഗമുണ്ട്|$1 ഉപവർഗ്ഗങ്ങളുണ്ട്}}.',
'category-article-count'         => '{{PLURAL:$2|ഈ വർഗ്ഗത്തിൽ താഴെ പ്രദർശിപ്പിച്ചിരിക്കുന്ന ഒരു താളേ ഉള്ളൂ.|ഈ വർഗ്ഗത്തിൽ $2 താളുകളുള്ളതിൽ {{PLURAL:$1|ഒരു താൾ|$1 താളുകൾ}} താഴെ പ്രദർശിപ്പിച്ചിരിക്കുന്നു.}}',
'category-article-count-limited' => 'ഈ വർഗ്ഗത്തിൽ താഴെ പ്രദർശിപ്പിച്ചിരിക്കുന്ന {{PLURAL:$1|ഒരു താൾ ഉണ്ട്|$1 താളുകൾ ഉണ്ട്}}.',
'category-file-count'            => '{{PLURAL:$2|ഈ വർഗ്ഗത്തിൽ താഴെ കാണുന്ന ഒരു പ്രമാണം മാത്രമേ ഉള്ളൂ.|മൊത്തം $2 പ്രമാണങ്ങളുള്ളതിൽ {{PLURAL:$1|ഒരു പ്രമാണം|$1 പ്രമാണങ്ങൾ}} താഴെ കാണിച്ചിരിക്കുന്നു.}}',
'category-file-count-limited'    => 'ഈ വർഗ്ഗത്തിൽ താഴെ കാണുന്ന {{PLURAL:$1|ഒരു പ്രമാണം|$1 പ്രമാണങ്ങൾ}} ഉണ്ട്.',
'listingcontinuesabbrev'         => 'തുടർച്ച.',
'index-category'                 => 'വർഗ്ഗീകരിക്കപ്പെട്ട താളുകൾ',
'noindex-category'               => 'വർഗ്ഗീകരിക്കപ്പെടാത്ത താളുകൾ',

'mainpagetext'      => "'''മീഡിയവിക്കി വിജയകരമായി സജ്ജീകരിച്ചിരിക്കുന്നു.'''",
'mainpagedocfooter' => 'വിക്കി സോഫ്റ്റ്‌വെയർ ഉപയോഗിക്കുന്നതിനെ കുറിച്ചുള്ള വിശദാംശങ്ങൾക്ക്  [http://meta.wikimedia.org/wiki/Help:Contents സോഫ്റ്റ്‌വെയർ സഹായി] കാണുക.

== പ്രാരംഭസഹായികൾ ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings ക്രമീകരണങ്ങളുടെ പട്ടിക]
* [http://www.mediawiki.org/wiki/Manual:FAQ മീഡിയവിക്കി സ്ഥിരംചോദ്യങ്ങൾ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce മീഡിയവിക്കി പ്രകാശന മെയിലിങ്ങ് ലിസ്റ്റ്]',

'about'         => 'വിവരണം',
'article'       => 'ലേഖന താൾ',
'newwindow'     => '(പുതിയ ജാലകത്തിൽ തുറന്നു വരും)',
'cancel'        => 'റദ്ദാക്കുക',
'moredotdotdot' => 'കൂടുതൽ...',
'mypage'        => 'എന്റെ താൾ',
'mytalk'        => 'എന്റെ സംവാദതാൾ',
'anontalk'      => 'ഈ ഐ.പി.യുടെ സം‌വാദം താൾ',
'navigation'    => 'ഉള്ളടക്കം',
'and'           => '&#32;ഒപ്പം',

# Cologne Blue skin
'qbfind'         => 'കണ്ടെത്തുക',
'qbbrowse'       => 'പരതുക',
'qbedit'         => 'തിരുത്തുക',
'qbpageoptions'  => 'ഈ താൾ',
'qbpageinfo'     => 'സന്ദർഭം',
'qbmyoptions'    => 'എന്റെ താളുകൾ',
'qbspecialpages' => 'പ്രത്യേക താളുകൾ',
'faq'            => 'പതിവുചോദ്യങ്ങൾ',
'faqpage'        => 'Project:പതിവുചോദ്യങ്ങൾ',

# Vector skin
'vector-action-addsection'   => 'വിഷയം ചേർക്കുക',
'vector-action-delete'       => 'മായ്ക്കുക',
'vector-action-move'         => 'മാറ്റുക',
'vector-action-protect'      => 'സം‌രക്ഷിക്കുക',
'vector-action-undelete'     => 'മായ്ക്കപ്പെട്ടത് പുനഃസ്ഥാപിക്കുക',
'vector-action-unprotect'    => 'സം‌രക്ഷണം നീക്കുക',
'vector-namespace-category'  => 'വർഗ്ഗം',
'vector-namespace-help'      => 'സഹായം താൾ',
'vector-namespace-image'     => 'പ്രമാണം',
'vector-namespace-main'      => 'താൾ',
'vector-namespace-media'     => 'മീഡിയ താൾ',
'vector-namespace-mediawiki' => 'സന്ദേശം',
'vector-namespace-project'   => 'പദ്ധതി താൾ',
'vector-namespace-special'   => 'പ്രത്യേക താൾ',
'vector-namespace-talk'      => 'സം‌വാദം',
'vector-namespace-template'  => 'ഫലകം',
'vector-namespace-user'      => 'ഉപയോക്താവിന്റെ താൾ',
'vector-view-create'         => 'സൃഷ്ടിക്കുക',
'vector-view-edit'           => 'തിരുത്തുക',
'vector-view-history'        => 'നാൾവഴി കാണുക',
'vector-view-view'           => 'വായിക്കുക',
'vector-view-viewsource'     => 'മൂലരൂപം കാണുക',
'actions'                    => 'നടപടികൾ',
'namespaces'                 => 'നാമമേഖല',
'variants'                   => 'ചരങ്ങൾ',

'errorpagetitle'    => 'പിഴവ്',
'returnto'          => '$1 എന്ന താളിലേക്ക് തിരിച്ചുപോവുക.',
'tagline'           => '{{SITENAME}} സംരംഭത്തിൽ നിന്ന്',
'help'              => 'സഹായം',
'search'            => 'തിരയൂ',
'searchbutton'      => 'തിരയൂ',
'go'                => 'പോകൂ',
'searcharticle'     => 'പോകൂ',
'history'           => 'നാൾവഴി',
'history_short'     => 'നാൾവഴി',
'updatedmarker'     => 'എന്റെ കഴിഞ്ഞ സന്ദർശനത്തിനുശേഷം നവീകരിക്കപ്പെട്ടവ',
'info_short'        => 'വൃത്താന്തം',
'printableversion'  => 'അച്ചടിരൂപം',
'permalink'         => 'സ്ഥിരംകണ്ണി',
'print'             => 'അച്ചടിയ്ക്കുക',
'edit'              => 'മാറ്റിയെഴുതുക',
'create'            => 'ഈ താൾ സൃഷ്ടിക്കുക',
'editthispage'      => 'ഈ താൾ തിരുത്തുക',
'create-this-page'  => 'ഈ താൾ സൃഷ്ടിക്കുക',
'delete'            => 'താൾ മായ്ക്കുക',
'deletethispage'    => 'ഈ താൾ നീക്കം ചെയ്യുക',
'undelete_short'    => '{{PLURAL:$1|ഒരു തിരുത്തൽ|$1 തിരുത്തലുകൾ}} പുനഃസ്ഥാപിക്കുക',
'protect'           => 'സം‌രക്ഷിക്കുക',
'protect_change'    => 'സംരക്ഷണമാനത്തിൽ വ്യതിയാനം വരുത്തുക',
'protectthispage'   => 'ഈ താൾ സം‌രക്ഷിക്കുക',
'unprotect'         => 'സം‌രക്ഷണരഹിതമാക്കുക',
'unprotectthispage' => 'ഈ താൾ സം‌രക്ഷണരഹിതമാക്കുക',
'newpage'           => 'പുതിയ താൾ',
'talkpage'          => 'ഈ താളിനെക്കുറിച്ച്‌ ചർച്ച ചെയ്യുക',
'talkpagelinktext'  => 'സംവാദം',
'specialpage'       => 'പ്രത്യേക താൾ',
'personaltools'     => 'സ്വകാര്യതാളുകൾ',
'postcomment'       => 'അഭിപ്രായം ചേർക്കുക',
'articlepage'       => 'ലേഖനം കാണുക',
'talk'              => 'സംവാദം',
'views'             => 'ദർശനീയത',
'toolbox'           => 'പണിസഞ്ചി',
'userpage'          => 'ഉപയോക്താവിന്റെ താൾ കാണുക',
'projectpage'       => 'പദ്ധതി താൾ കാണുക',
'imagepage'         => 'മീഡിയ താൾ കാണുക',
'mediawikipage'     => 'സന്ദേശങ്ങളുടെ താൾ കാണുക',
'templatepage'      => 'ഫലകം താൾ കാണുക',
'viewhelppage'      => 'സഹായം താൾ കാണുക',
'categorypage'      => 'വർഗ്ഗം താൾ കാണുക',
'viewtalkpage'      => 'സം‌വാദം കാണുക',
'otherlanguages'    => 'ഇതര ഭാഷകളിൽ',
'redirectedfrom'    => '($1 എന്ന താളിൽ നിന്നും തിരിച്ചുവിട്ടതു പ്രകാരം)',
'redirectpagesub'   => 'തിരിച്ചുവിടൽ താൾ',
'lastmodifiedat'    => 'ഈ താൾ അവസാനം തിരുത്തപ്പെട്ടത്: $2, $1.',
'viewcount'         => 'ഈ താൾ {{PLURAL:$1|ഒരു തവണ|$1 തവണ}} സന്ദർശിക്കപ്പെട്ടിട്ടുണ്ട്.',
'protectedpage'     => 'സംരക്ഷിത താൾ',
'jumpto'            => 'പോവുക:',
'jumptonavigation'  => 'വഴികാട്ടി',
'jumptosearch'      => 'തിരയൂ',
'view-pool-error'   => 'ക്ഷമിക്കണം, ഈ നിമിഷം സെർവറുകൾ അമിതഭാരം കൈകാര്യം ചെയ്യുകയാണ്.
ധാരാളം ഉപയോക്താക്കൾ ഈ താൾ കാണുവാൻ ശ്രമിച്ചുകൊണ്ടിരിക്കുകയാണ്.
ഇനിയും താൾ ലഭ്യമാക്കുവാൻ താങ്കൾ ശ്രമിക്കുന്നതിന് മുൻപ് ദയവായി അല്പസമയം കാത്തിരിക്കുക. 

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} സം‌രംഭത്തെക്കുറിച്ച്',
'aboutpage'            => 'Project:വിവരണം',
'copyright'            => 'ഉള്ളടക്കം $1 പ്രകാരം ലഭ്യം.',
'copyrightpage'        => '{{ns:project}}:പകർപ്പവകാശം',
'currentevents'        => 'സമകാലികം',
'currentevents-url'    => 'Project:സമകാലികം',
'disclaimers'          => 'നിരാകരണങ്ങൾ',
'disclaimerpage'       => 'Project:പൊതുനിരാകരണം',
'edithelp'             => 'തിരുത്തൽ സഹായി',
'edithelppage'         => 'Help:തിരുത്തൽ വഴികാട്ടി',
'helppage'             => 'Help:ഉള്ളടക്കം',
'mainpage'             => 'പ്രധാന താൾ',
'mainpage-description' => 'പ്രധാന താൾ',
'policy-url'           => 'Project:നയം',
'portal'               => 'സാമൂഹികകവാടം',
'portal-url'           => 'Project:സാമൂഹികകവാടം',
'privacy'              => 'സ്വകാര്യതാനയം',
'privacypage'          => 'Project:സ്വകാര്യതാനയം',

'badaccess'        => 'അനുമതിപ്രശ്നം',
'badaccess-group0' => 'താങ്കൾ ആവശ്യപ്പെട്ട കാര്യം ചെയ്യാനുള്ള അനുമതി താങ്കൾക്കില്ല',
'badaccess-groups' => 'താങ്കൾ ആവശ്യപ്പെട്ട കാര്യം ചെയ്യാൻ {{PLURAL:$2|$1 സംഘത്തിലെ|$1 എന്നീ സംഘങ്ങളിൽ ഏതെങ്കിലും ഒന്നിലെ}} അംഗങ്ങൾക്കു മാത്രമേ സാധിക്കൂ',

'versionrequired'     => 'മീഡിയാവിക്കിയുടെ പതിപ്പ് $1 ആവശ്യമാണ്',
'versionrequiredtext' => 'ഈ താൾ ഉപയോഗിക്കാൻ മീഡിയവിക്കി പതിപ്പ് $1 ആവശ്യമാണ്. കൂടുതൽ വിവരങ്ങൾക്ക് [[Special:Version|മീഡിയാവിക്കി പതിപ്പ് താൾ]] കാണുക.',

'ok'                      => 'ശരി',
'retrievedfrom'           => '"$1" എന്ന താളിൽനിന്നു ശേഖരിച്ചത്',
'youhavenewmessages'      => 'താങ്കൾക്ക് $1 ഉണ്ട് ($2).',
'newmessageslink'         => 'പുതിയ സന്ദേശങ്ങൾ',
'newmessagesdifflink'     => 'അവസാന മാറ്റം',
'youhavenewmessagesmulti' => 'താങ്കൾക്ക് $1-ൽ പുതിയ സന്ദേശങ്ങൾ ഉണ്ട്',
'editsection'             => 'തിരുത്തുക',
'editold'                 => 'തിരുത്തുക',
'viewsourceold'           => 'മൂലരൂപം കാണുക',
'editlink'                => 'തിരുത്തുക',
'viewsourcelink'          => 'മൂലരൂപം കാണുക',
'editsectionhint'         => 'ഉപവിഭാഗം തിരുത്തുക: $1',
'toc'                     => 'ഉള്ളടക്കം',
'showtoc'                 => 'പ്രദർശിപ്പിക്കുക',
'hidetoc'                 => 'മറയ്ക്കുക',
'thisisdeleted'           => '$1 കാണുകയോ പുനഃസ്ഥാപിക്കുകയോ ചെയ്യേണ്ടതുണ്ടോ?',
'viewdeleted'             => '$1 കാണണോ?',
'restorelink'             => '{{PLURAL:$1|നീക്കംചെയ്ത ഒരു തിരുത്തൽ|നീക്കംചെയ്ത $1 തിരുത്തലുകൾ}}',
'feedlinks'               => 'ഫീഡ്:',
'feed-invalid'            => 'അസാധുവായ സബ്‌സ്ക്രിപ്ഷൻ ഫീഡ് തരം.',
'feed-unavailable'        => 'സിൻഡിക്കേഷൻ ഫീഡുകൾ ലഭ്യമല്ല',
'site-rss-feed'           => '$1 ന്റെ ആർ.എസ്.എസ് ഫീഡ്',
'site-atom-feed'          => '$1 ന്റെ ആറ്റം ഫീഡ്',
'page-rss-feed'           => '"$1" ന്റെ ആർ.എസ്.എസ്. ഫീഡ്',
'page-atom-feed'          => '"$1" ആറ്റം ഫീഡ്',
'red-link-title'          => '$1 (ഇതുവരെ എഴുതപ്പെട്ടിട്ടില്ല)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ലേഖനം',
'nstab-user'      => 'ഉപയോക്തൃതാൾ',
'nstab-media'     => 'മീഡിയാ താൾ',
'nstab-special'   => 'പ്രത്യേക താൾ',
'nstab-project'   => 'പദ്ധതി താൾ',
'nstab-image'     => 'പ്രമാണം',
'nstab-mediawiki' => 'സന്ദേശം',
'nstab-template'  => 'ഫലകം',
'nstab-help'      => 'സഹായ താൾ',
'nstab-category'  => 'വർഗ്ഗം',

# Main script and global functions
'nosuchaction'      => 'ഈ പ്രവൃത്തി അസാധുവാണ്‌',
'nosuchactiontext'  => 'യു.ആർ‌.എൽ. വഴി നിർ‌വചിച്ച പ്രവർത്തനം വിക്കി തിരിച്ചറിഞ്ഞില്ല. താങ്കൾ യു.ആർ‌.എൽ. തെറ്റായി നൽകിയിരിക്കാം അല്ലെങ്കിൽ ഒരു തെറ്റായ ലിങ്കുവഴി വന്നിരിക്കാം.  
ഒരുപക്ഷേ, ഇത് {{SITENAME}} ഉപയോഗിക്കുന്ന സോഫ്റ്റ്‌വെയറിലെ ബഗ്ഗും ആകാം.',
'nosuchspecialpage' => 'അത്തരമൊരു പ്രത്യേകതാൾ നിലവിലില്ല',
'nospecialpagetext' => '<strong>താങ്കൾ നിലവിലില്ലാത്ത ഒരു പ്രത്യേകതാൾ ആണ് ആവശ്യപ്പെട്ടത്.</strong>

നിലവിലുള്ള പ്രത്യേകതാളുകളുടെ പട്ടിക കാണാൻ [[Special:SpecialPages|{{int:specialpages}}]] ശ്രദ്ധിക്കുക.',

# General errors
'error'                => 'പിഴവ്',
'databaseerror'        => 'ഡാറ്റാബേസ് പിഴവ്',
'dberrortext'          => 'ഒരു വിവരശേഖര അന്വേഷണത്തിന്റെ ഉപയോഗക്രമത്തിൽ പിഴവ് സംഭവിച്ചിരിക്കുന്നു.
ഇത് ചിലപ്പോൾ സോഫ്റ്റ്‌വെയർ ബഗ്ഗിനെ സൂചിപ്പിക്കുന്നതാവാം.
അവസാനം ശ്രമിച്ച വിവരശേഖര അന്വേഷണം താഴെ കൊടുക്കുന്നു:
<blockquote><tt>$1</tt></blockquote>
"<tt>$2</tt>" എന്ന നിർദ്ദേശത്തിനകത്ത് നിന്നും.
വിവരശേഖരത്തിൽ നിന്നും ലഭിച്ച പിഴവ് "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'വിവരശേഖര അന്വേഷണ ഘടനയിൽ ഒരു പിഴവ് സംഭവിച്ചിരിക്കുന്നു.
അവസാനം ശ്രമിച്ച വിവരശേഖര അന്വേഷണം താഴെ കൊടുക്കുന്നു:
"$1"
"$2" എന്ന നിർദ്ദേശത്തിനകത്ത് നിന്നും .
വിവരശേഖരത്തിൽ നിന്നും ലഭിച്ച പിഴവ് "$3: $4"',
'laggedslavemode'      => 'മുന്നറിയിപ്പ്: താളിൽ അടുത്തകാലത്ത് വരുത്തിയ പുതുക്കലുകൾ ഉണ്ടാവണമെന്നില്ല.',
'readonly'             => 'ഡാറ്റാബേസ് ബന്ധിച്ചിരിക്കുന്നു',
'enterlockreason'      => 'ഡാറ്റാബേസ് ബന്ധിക്കുവാനുള്ള കാരണം സൂചിപ്പിക്കുക. അതോടൊപ്പം എപ്പോഴാണ്‌ ബന്ധനം അഴിക്കുവാൻ ഉദ്ദേശിക്കുന്നതെന്നും രേഖപ്പെടുത്തുക.',
'readonlytext'         => 'പുതിയ തിരുത്തലുകളും മറ്റ് മാറ്റങ്ങളും അനുവദനീയമല്ലാത്ത വിധത്തിൽ ഡാറ്റാബേസ് ബന്ധിച്ചിരിക്കുകയാണ്‌. ക്രമപ്രകാരമുള്ള വൃത്തിയാക്കലിനു വേണ്ടി ബന്ധിച്ച ഡാറ്റാബേസ് താമസിയാതെ തന്നെ സാധാരണ നില കൈവരിക്കും.

ഡാറ്റാബേസ് ബന്ധിച്ച കാര്യനിർ‌വാഹകൻ അതിനു സൂചിപ്പിച്ച കാരണം: $1',
'missing-article'      => 'താളിൽ ഉണ്ടായിരിക്കേണ്ട വിവരങ്ങൾ ("$1" $2) വിവരശേഖരത്തിനു കണ്ടെത്താനായില്ല.

ഇത് സാധാരണയായി തുടർന്നു വരുന്ന നീക്കം ചെയ്യപ്പെട്ട താളിലേക്കുള്ള തെറ്റായതിയ്യതിയിൽ വരുന്ന മാറ്റം അല്ലെങ്കിൽ നാൾവഴി കണ്ണി മൂലമാണ് സംഭവിക്കാറുള്ളത്.

കാരണം ഇതല്ലെങ്കിൽ, ഒരു സോഫ്റ്റ്‌വെയർ ബഗ്ഗ് ആയിരിക്കാം.
ദയവായി താളിന്റെ യു.ആർ.എൽ സഹിതം ഒരു [[Special:ListUsers/sysop|കാര്യനിർവാഹകനെ]] ഇത് അറിയിക്കുക.',
'missingarticle-rev'   => '(മാറ്റം#: $1)',
'missingarticle-diff'  => '(വ്യത്യാസം: $1, $2)',
'readonly_lag'         => 'വിവരശേഖരം സ്വയം ബന്ധിക്കപ്പെട്ടിരിക്കുന്നു അതേസമയം കീഴ്-വിവരശേഖര സെർവറുകൾ മാസ്റ്റർ വരെ പിടിച്ചിരിക്കുന്നു',
'internalerror'        => 'ആന്തരികമായ പ്രശ്നം',
'internalerror_info'   => 'ആന്തരികപ്രശ്നം: $1',
'fileappenderrorread'  => 'കൂട്ടിച്ചേർക്കുന്ന സമയം "$1" വായിച്ചെടുക്കാൻ കഴിഞ്ഞില്ല.',
'fileappenderror'      => '"$1" എന്നത് "$2"-ലേക്ക് കൂട്ടിച്ചേർക്കുവാൻ സാധിച്ചില്ല.',
'filecopyerror'        => '"$1" എന്ന പ്രമാണം "$2" എന്നതിലേയ്ക്ക് പകർത്താൻ സാധിച്ചില്ല.',
'filerenameerror'      => 'പ്രമാണം "$1", "$2" എന്ന തലക്കെട്ടിലേയ്ക്കു മാറ്റാൻ സാധിച്ചില്ല.',
'filedeleteerror'      => '"$1" നീക്കം ചെയ്യാൻ സാധിച്ചില്ല.',
'directorycreateerror' => '"$1" എന്ന directory സൃഷ്ടിക്കാൻ സാധിച്ചില്ല.',
'filenotfound'         => '"$1" എന്ന പ്രമാണം കണ്ടെത്താനായില്ല.',
'fileexistserror'      => '"$1" എന്ന പ്രമാണത്തിലേയ്ക്ക് എഴുതാൻ പറ്റിയില്ല: പ്രമാണം നിലവിലുണ്ട്',
'unexpected'           => 'പ്രതീക്ഷിക്കാത്ത മൂല്യം: "$1"="$2".',
'formerror'            => 'പിഴവ്: ഫോം സമർപ്പിക്കുവാൻ പറ്റിയില്ല',
'badarticleerror'      => 'താങ്കൾ ചെയ്യാനുദ്ദേശിക്കുന്നത് ഈ താളിൽ സാദ്ധ്യമല്ല',
'cannotdelete'         => '"$1" എന്ന താൾ അഥവാ പ്രമാണം നീക്കം ചെയ്യാൻ കഴിഞ്ഞില്ല.
അതു മിക്കവാറും മറ്റാരെങ്കിലും നീക്കം ചെയ്തിട്ടുണ്ടാവാം.',
'badtitle'             => 'അസാധുവായ തലക്കെട്ട്',
'badtitletext'         => 'താങ്കൾ ആവശ്യപ്പെട്ട തലക്കെട്ടുള്ള ഒരു താൾ നിലവിലില്ല. ഇതു തെറ്റായി അന്തർഭാഷാ/അന്തർവിക്കി കണ്ണി ചെയ്യപ്പെട്ടതു മൂലമോ, തലക്കെട്ടിൽ ഉപയോഗിക്കരുതാത്ത അക്ഷരരൂപങ്ങൾ ഉപയോഗിച്ചതു മൂലമോ സംഭവിച്ചതായിരിക്കാം.',
'perfcached'           => 'താഴെ കൊടുത്തിരിക്കുന്ന വിവരം ശേഖരിക്കപ്പെട്ടതാണ് ആയതിനാൽ ചിലപ്പോൾ നവീനമായിരിക്കില്ല.',
'perfcachedts'         => 'താഴെയുള്ള വിവരങ്ങൾ ശേഖരിച്ച് വെച്ചവയിൽ പെടുന്നു, അവസാനം പുതുക്കിയത് $1-നു ആണ്‌.',
'querypage-no-updates' => 'ഈ താളിന്റെ പുതുക്കൽ തൽക്കാലം നിർത്തി വച്ചിരിക്കുന്നു. ഇവിടുള്ള വിവരങ്ങൾ ഏറ്റവും പുതിയതാവണമെന്നില്ല.',
'wrong_wfQuery_params' => 'wfQuery()എന്നതിലേക്ക് തെറ്റായ പരാമീറ്ററുകൾ<br />
നിർദ്ദേശം: $1<br />
അന്വേഷണം: $2',
'viewsource'           => 'മൂലരൂപം കാണുക',
'viewsourcefor'        => 'താൾ $1',
'actionthrottled'      => 'പ്രവൃത്തി നടത്തിയിരിക്കുന്നു',
'actionthrottledtext'  => 'പാഴ് എഴുത്തിനെതിരെയുള്ള മുൻ‌കരുതൽ എന്ന നിലയിൽ ഒരേ പ്രവൃത്തി കുറഞ്ഞ സമയത്തിനുള്ളിൽ നിരവധി തവണ ആവർത്തിക്കുന്നതു പരിമിതപ്പെടുത്തിയിരിക്കുന്നു. താങ്കൾ ആ പരിധി ലംഘിച്ചിരിക്കുന്നു. കുറച്ച് മിനിറ്റുകൾക്കു ശേഷം വീണ്ടും ശ്രമിക്കുക.',
'protectedpagetext'    => 'ഈ താൾ തിരുത്തുവാൻ സാധിക്കാത്ത വിധം സംരക്ഷിക്കപ്പെട്ടിട്ടുള്ളതാണ്.',
'viewsourcetext'       => 'താങ്കൾക്ക് ഈ താളിന്റെ മൂലരൂപം കാണാനും പകർത്താനും സാധിക്കും:',
'protectedinterface'   => 'ഈ താൾ സോഫ്റ്റ്‌വെയറിന്റെ സമ്പർക്കമുഖ എഴുത്തുകൾ നൽകുന്നു, അതുകൊണ്ട് ദുരുപയോഗം തടയാൻ ബന്ധിക്കപ്പെട്ടിരിക്കുന്നു.',
'editinginterface'     => "'''മുന്നറിയിപ്പ്:''' സോഫ്റ്റ്‌വെയറിൽ സമ്പർക്കമുഖം നിലനിർത്തുന്ന താളാണു താങ്കൾ തിരുത്തുവാൻ പോകുന്നത്. ഈ താളിൽ താങ്കൾ വരുത്തുന്ന മാറ്റങ്ങൾ ഉപയോക്താവ് വിക്കി കാണുന്ന വിധത്തെ മാറ്റിമറിച്ചേക്കാം. മീഡിയവിക്കി സന്ദേശങ്ങളുടെ പരിഭാഷകൾക്ക് മീഡിയവിക്കി സന്ദേശങ്ങളുടെ പ്രാദേശികവത്കരണ സംരംഭം ആയ [http://translatewiki.net/wiki/Main_Page?setlang=ml ബീറ്റാവിക്കി] ഉപയോഗിക്കുവാൻ താല്പര്യപ്പെടുന്നു.",
'sqlhidden'            => '(SQL query മറച്ചിരിക്കുന്നു)',
'cascadeprotected'     => 'നിർഝരിത (cascading) സൗകര്യം ഉപയോഗിച്ച് തിരുത്തൽ നടത്തുന്നതിനു സം‌രക്ഷണം ഏർപ്പെടുത്തിയിട്ടുള്ള {{PLURAL:$1|താഴെ കൊടുത്തിട്ടുള്ള താളിന്റെ|താഴെ കൊടുത്തിട്ടുള്ള താളുകളുടെ}} ഭാഗമാണ്‌ ഈ താൾ. അതിനാൽ ഈ താൾ തിരുത്തുവാൻ സാധിക്കില്ല:
$2',
'namespaceprotected'   => "'''$1''' നാമമേഖലയിലുള്ള താളുകൾ തിരുത്താൻ താങ്കൾക്ക് അനുവാദമില്ല.",
'customcssjsprotected' => 'ഇത് മറ്റൊരു ഉപയോക്താവിന്റെ ക്രമീകരണങ്ങൾ ഉൾക്കൊള്ളുന്ന താളാണ്‌, അതിനാൽ താങ്കൾക്ക് ഈ താൾ തിരുത്താൻ അനുവാദമില്ല.',
'ns-specialprotected'  => 'പ്രത്യേകം എന്ന നാമമേഖലയിൽ വരുന്ന താളുകൾ തിരുത്താനാവുന്നവയല്ല.',
'titleprotected'       => "[[User:$1|$1]] എന്ന ഉപയോക്താവ് ഈ താൾ ഉണ്ടാക്കുന്നതു നിരോധിച്ചിരിക്കുന്നു.
''$2'' എന്നതാണു അതിനു കാണിച്ചിട്ടുള്ള കാരണം.",

# Virus scanner
'virus-badscanner'     => "തെറ്റായ ക്രമീകരണങ്ങൾ: അപരിചിതമായ വൈറസ് തിരച്ചിൽ ഉപാധി :  ''$1''",
'virus-scanfailed'     => 'വൈറസ് സ്കാനിങ് പരാജയപ്പെട്ടു (code $1)',
'virus-unknownscanner' => 'തിരിച്ചറിയാനാകാത്ത ആന്റിവൈറസ്:',

# Login and logout pages
'logouttext'                 => "'''താങ്കൾ ഇപ്പോൾ {{SITENAME}} സംരംഭത്തിൽനിന്നും ലോഗൗട്ട് ചെയ്തിരിക്കുന്നു'''

അജ്ഞാതമായിരുന്നു കൊണ്ട് {{SITENAME}} സം‌രംഭം താങ്കൾക്കു തുടർന്നും ഉപയോഗിക്കാവുന്നതാണ്‌.
അല്ലെങ്കിൽ  [[Special:UserLogin|ലോഗിൻ സൗകര്യം ഉപയോഗിച്ച്]] വീണ്ടും ലോഗിൻ ചെയ്യാവുന്നതും ആണ്‌.
താങ്കൾ വെബ് ബ്രൌസറിന്റെ ക്യാഷെ ശൂന്യമാക്കിയിട്ടില്ലെങ്കിൽ ചില താളുകളിൽ താങ്കൾ ലോഗിൻ ചെയ്തിരിക്കുന്നതായി കാണിക്കാൻ സാധ്യതയുണ്ട്.",
'welcomecreation'            => '== സ്വാഗതം, $1! ==
താങ്കളുടെ അംഗത്വം സൃഷ്ടിക്കപ്പെട്ടിരിക്കുന്നു.
താങ്കളുടെ [[Special:Preferences|{{SITENAME}} ക്രമീകരണങ്ങളിൽ]] ആവശ്യമായ മാറ്റം വരുത്തുവാൻ മറക്കരുതേ.',
'yourname'                   => 'ഉപയോക്തൃനാമം:',
'yourpassword'               => 'രഹസ്യവാക്ക്:',
'yourpasswordagain'          => 'രഹസ്യവാക്ക് ഒരിക്കൽക്കൂടി:',
'remembermypassword'         => 'എന്റെ പ്രവേശനം ഈ കമ്പ്യൂട്ടറിൽ ഓർത്തുവെക്കുക.',
'yourdomainname'             => 'താങ്കളുടെ ഡൊമെയിൻ:',
'externaldberror'            => 'ഒന്നുകിൽ ഡേറ്റാബേസ് സാധൂകരണത്തിൽ പ്രശ്നം ഉണ്ടായിരുന്നു അല്ലെങ്കിൽ നവീകരിക്കുവാൻ താങ്കളുടെ ബാഹ്യ അംഗത്വം താങ്കളെ അനുവദിക്കുന്നില്ല.',
'login'                      => 'പ്രവേശിക്കുക',
'nav-login-createaccount'    => 'പ്രവേശിക്കുക / അംഗത്വമെടുക്കുക',
'loginprompt'                => '{{SITENAME}} സംരംഭത്തിൽ ലോഗിൻ ചെയ്യാൻ താങ്കൾ കുക്കികൾ (Cookies) സജ്ജമാക്കിയിരിക്കണം.',
'userlogin'                  => 'പ്രവേശിക്കുക / അംഗത്വമെടുക്കുക',
'userloginnocreate'          => 'പ്രവേശിക്കുക',
'logout'                     => 'ലോഗൗട്ട്',
'userlogout'                 => 'ലോഗൗട്ട്',
'notloggedin'                => 'പ്രവേശിച്ചിട്ടില്ല',
'nologin'                    => "അംഗത്വമില്ലേ? '''$1'''.",
'nologinlink'                => 'ഒരംഗത്വമെടുക്കുക',
'createaccount'              => 'അംഗത്വമെടുക്കുക',
'gotaccount'                 => "താങ്കൾക്ക് അംഗത്വമുണ്ടോ? '''$1'''.",
'gotaccountlink'             => 'പ്രവേശിക്കുക',
'createaccountmail'          => 'ഇമെയിൽ വഴി',
'badretype'                  => 'താങ്കൾ ടൈപ്പു ചെയ്ത രഹസ്യവാക്കുകൾ തമ്മിൽ യോജിക്കുന്നില്ല.',
'userexists'                 => 'ഈ പേരിൽ മറ്റൊരു ഉപയോക്തൃനാമം  നിലവിലുണ്ട്. ദയവായി മറ്റൊരു ഉപയോക്തൃനാമം തിരഞ്ഞെടുക്കുക.',
'loginerror'                 => 'പ്രവേശനം സാധിച്ചില്ല',
'createaccounterror'         => 'അംഗത്വമെടുക്കാൻ കഴിഞ്ഞില്ല:$1',
'nocookiesnew'               => 'ഉപയോക്തൃഅംഗത്വം ഉണ്ടാക്കിയിരിക്കുന്നു. പക്ഷെ താങ്കൾ ലോഗിൻ ചെയ്തിട്ടില്ല. {{SITENAME}} സംരംഭത്തിൽ ലോഗിൻ ചെയ്യുവാൻ കുക്കികൾ സജ്ജമാക്കിയിരിക്കണം. താങ്കളുടെ കമ്പ്യൂട്ടറിൽ നിലവിൽ കുക്കികൾ ഡിസേബിൾ ചെയ്തിരിക്കുന്നു. അതു എനേബിൾ ചെയ്തു താങ്കളുടെ ഉപയോക്തൃനാമവും രഹസ്യവാക്കും ഉപയോഗിച്ച് ലോഗിൻ ചെയ്യൂ.',
'nocookieslogin'             => '{{SITENAME}} സംരഭത്തിൽ ലോഗിൻ ചെയ്യുവാൻ കുക്കികൾ സജ്ജമാക്കിയിരിക്കണം. പക്ഷെ താങ്കൾ കുക്കികൾ സജ്ജമാക്കിയിട്ടില്ല. കുക്കികൾ സജ്ജമാക്കിയതിനു ശേഷം വീണ്ടും ലോഗിൻ ചെയ്യാൻ ശ്രമിക്കൂ.',
'noname'                     => 'താങ്കൾ സാധുവായ ഉപയോക്തൃനാമം സൂചിപ്പിച്ചിട്ടില്ല.',
'loginsuccesstitle'          => 'വിജയകരമായി പ്രവേശിച്ചിരിക്കുന്നു',
'loginsuccess'               => "'''{{SITENAME}} സംരംഭത്തിൽ \"\$1\" എന്ന പേരിൽ താങ്കൾ ലോഗിൻ ചെയ്തിരിക്കുന്നു.'''",
'nosuchuser'                 => 'ഇതുവരെ "$1" എന്ന പേരിൽ ആരും അംഗത്വമെടുത്തിട്ടില്ല.
ദയവായി അക്ഷരപ്പിശകുകൾ പരിശോധിക്കുക, അല്ലെങ്കിൽ 
പുതിയ [[Special:UserLogin/signup|അംഗത്വമെടുക്കുക]].',
'nosuchusershort'            => '"<nowiki>$1</nowiki>" എന്ന പേരിൽ ഒരു ഉപയോക്താവ് ഇല്ല. അക്ഷരങ്ങൾ ഒന്നു കൂടി പരിശോധിക്കുക.',
'nouserspecified'            => 'ഉപയോക്തൃനാമം നിർബന്ധമായും ചേർക്കണം.',
'login-userblocked'          => 'ഈ ഉപയോക്താവ് തടയപ്പെട്ടിരിക്കുന്നു. പ്രവേശനം അനുവദിക്കുന്നില്ല.',
'wrongpassword'              => 'താങ്കൾ നൽകിയ രഹസ്യവാക്ക് തെറ്റാണ്, വീണ്ടും ശ്രമിക്കുക.',
'wrongpasswordempty'         => 'താങ്കൾ രഹസ്യവാക്ക് നൽകിയിരുന്നില്ല. വീണ്ടും ശ്രമിക്കുക.',
'passwordtooshort'           => 'രഹസ്യവാക്കിൽ കുറഞ്ഞതു {{PLURAL:$1|ഒരു അക്ഷരം|$1 അക്ഷരങ്ങൾ}} ഉണ്ടായിരിക്കണം.',
'password-name-match'        => 'താങ്കളുടെ രഹസ്യവാക്ക് ഉപയോക്തൃനാമത്തിൽ നിന്നും വ്യത്യസ്തമായിരിക്കണം.',
'mailmypassword'             => 'പുതിയ രഹസ്യവാക്ക് ഇമെയിൽ ചെയ്യുക',
'passwordremindertitle'      => '{{SITENAME}} സംരംഭത്തിൽ ഉപയോഗിക്കാനുള്ള താത്ക്കാലിക രഹസ്യവാക്ക്',
'passwordremindertext'       => 'ആരോ ഒരാൾ (ഒരു പക്ഷേ താങ്കളായിരിക്കാം, $1 എന്ന ഐ.പി. വിലാസത്തിൽനിന്ന്) {{SITENAME}} ($4) സംരംഭത്തിലേക്ക് പുതിയ രഹസ്യവാക്ക് ആവശ്യപ്പെട്ടിരിക്കുന്നു. "$2" എന്ന ഉപയോക്താവിന് ആവശ്യമായ ഒരു താൽകാലിക രഹസ്യവാക്കായി "$3" എന്ന് സജ്ജീകരിച്ചിരിക്കുന്നു. ഇത് താങ്കളുടെ ആവശ്യമാണെങ്കിൽ, താങ്കൾ പ്രവേശം ചെയ്ത് പുതിയ രഹസ്യവാക്ക് സജ്ജീകരിക്കേണ്ടതാണ്. താങ്കളുടെ താത്കാലിക രഹസ്യവാക്കിന്റെ കാലാവധി {{PLURAL:$5|ഒരു ദിവസമാകുന്നു|$5 ദിവങ്ങളാകുന്നു}}.

ഈ അഭ്യർത്ഥന മറ്റാരെങ്കിലും നടത്തിയതാണെങ്കിൽ, അതല്ല പഴയ രഹസ്യവാക്ക് ഓർമ്മയുണ്ടായിരിക്കുകയും അത് മാറ്റുവാൻ താങ്കൾക്ക് താത്പര്യവുമില്ലെങ്കിൽ, ഈ സന്ദേശം അവഗണിച്ച് താങ്കളുടെ പഴയ രഹസ്യവാക്ക് തുടർന്നും ഉപയോഗിക്കാവുന്നതാണ്‌.',
'noemail'                    => '"$1" എന്ന ഉപയോക്താവ് ഇമെയിൽ വിലാസം ക്രമീകരിച്ചിട്ടില്ല.',
'noemailcreate'              => 'താങ്കൾ സാധുവായ ഇമെയിൽ വിലാസം നൽകേണ്ടതാണ്',
'passwordsent'               => '‘$1” എന്ന അംഗത്വത്തിനായി രജിസ്റ്റർ ചെയ്യപ്പെട്ടിട്ടുള്ള ഇമെയിൽ വിലാസത്തിലേക്ക് ഒരു പുതിയ രഹസ്യവാക്ക് അയച്ചിട്ടുണ്ട്. അത് ലഭിച്ചശേഷം ദയവായി ലോഗിൻ ചെയ്യുക.',
'blocked-mailpassword'       => 'താങ്കളുടെ ഐ.പി. വിലാസത്തെ ഈ വിക്കി തിരുത്തുന്നതിൽ നിന്നു തടഞ്ഞിട്ടുള്ളതാണ്‌. അതിനാൽ രഹസ്യവാക്ക് വീണ്ടെടുക്കുവാനുള്ള സജ്ജീകരണം ഉപയോഗിക്കുന്നതിനു താങ്കൾക്ക് അവകാശമില്ല.',
'eauthentsent'               => 'താങ്കൾ വിക്കിയിൽ ക്രമീകരിച്ചിട്ടുള്ള ഇമെയിൽ വിലാസത്തിലേക്ക് സ്ഥിരീകരണത്തിനായി ഒരു മെയിൽ അയച്ചിട്ടുണ്ട്. ഇവിടെ നിന്ന് ആ ഇമെയിൽ വിലാസത്തിലേക്ക് മറ്റൊരു മെയിൽ കൂടി അയക്കുന്നതിനു മുൻപായി, അംഗത്വം താങ്കളുടേതു തന്നെ എന്നു ഉറപ്പു വരുത്തുന്നതിനായി, ഇപ്പോൾ അയച്ചിട്ടുള്ള മെയിലിലെ നിർദ്ദേശങ്ങൾ താങ്കൾ പാലിക്കേണ്ടതാണ്.',
'throttled-mailpassword'     => 'കഴിഞ്ഞ {{PLURAL:$1|മണിക്കൂറിനുള്ളിൽ |$1 മണിക്കൂറുകൾക്കുള്ളിൽ}} രഹസ്യവാക്ക് ഓർമ്മപ്പെടുത്താനുള്ള ഒരു മെയിൽ അയച്ചിട്ടുണ്ട്. ദുർവിനിയോഗം ഒഴിവാക്കാൻ {{PLURAL:$1|ഒരു മണിക്കൂറിനുള്ളിൽ |$1 മണിക്കൂറുകൾക്കുള്ളിൽ}} രഹസ്യവാക്ക് ഓർമ്മപ്പെടുത്താനുള്ള ഒരു മെയിൽ മാത്രമേ അനുവദിക്കൂ.',
'mailerror'                  => 'മെയിൽ അയയ്ക്കുന്നതിൽ പിഴവ്: $1',
'acct_creation_throttle_hit' => 'കഴിഞ്ഞ ഒരു ദിവസത്തിനുള്ളിൽ താങ്കളുടെ ഐ.പി. വിലാസത്തിൽ നിന്നുമുള്ള സന്ദർശകർ {{PLURAL:$1|1 അംഗത്വം|$1 അംഗത്വങ്ങൾ}} എടുത്തിട്ടുണ്ട്, പ്രസ്താവിത സമയത്തിനുള്ളിൽ എടുക്കാവുന്ന ഏറ്റവും കൂടിയ പരിധിയാണിത്.
അതിന്റെ ഫലമായി, ഈ ഐ.പി.യിൽ നിന്നുള്ള സന്ദർശകർക്ക് ഇപ്പോൾ കൂടുതൽ അംഗത്വമെടുക്കാൻ കഴിയുന്നതല്ല.',
'emailauthenticated'         => 'താങ്കളുടെ ഇമെയിൽ വിലാസം $2, $3-ന് സാധുത തെളിയിച്ചതാണ്.',
'emailnotauthenticated'      => 'താങ്കളുടെ ഇമെയിൽ വിലാസത്തിന്റെ സാധുത ഇതുവരെ സ്ഥിരീകരിക്കപ്പെട്ടിട്ടില്ല. സാധുത തെളിയിക്കുന്നതുവരെ താഴെപ്പറയുന്നവയ്ക്കൊന്നും താങ്കൾക്ക് ഇമെയിൽ അയക്കുവാൻ സാദ്ധ്യമല്ല.',
'noemailprefs'               => 'ഈ ക്രമീകരണങ്ങൾ പ്രവർത്തിക്കുവാൻ സാധുവായ ഒരു ഇമെയിൽ വിലാസം ഉൾപ്പെടുത്തുക.',
'emailconfirmlink'           => 'താങ്കളുടെ ഇമെയിൽ വിലാസം സ്ഥിരീകരിക്കുക',
'invalidemailaddress'        => 'ഇമെയിൽ വിലാസം സാധുവായ രൂപത്തിൽ അല്ലാത്തതിനാൽ സ്വീകാര്യമല്ല. 
ദയവായി സാധുവായ രൂപത്തിലുള്ള ഇമെയിൽ വിലാസം ചേർക്കുകയോ ഇമെയിൽ വിലാസത്തിനുള്ള ഇട ഒഴിവാക്കിയിടുകയോ ചെയ്യുക.',
'accountcreated'             => 'അംഗത്വം സൃഷ്ടിച്ചിരിക്കുന്നു',
'accountcreatedtext'         => '$1 എന്ന ഉപയോക്താവിനായി അംഗത്വം സൃഷ്ടിക്കപ്പെട്ടിരിക്കുന്നു.',
'createaccount-title'        => '{{SITENAME}} സംരംഭത്തിൽ അംഗത്വം സൃഷ്ടിക്കൽ',
'createaccount-text'         => '{{SITENAME}} സംരംഭത്തിൽ ($4) താങ്കളുടെ ഇമെയിൽ വിലാസത്തിൽ ആരോ ഒരു അംഗത്വം "$2" എന്ന ഉപയോക്തൃനാമത്തിൽ ഉണ്ടാക്കിയിരിക്കുന്നു (രഹസ്യവാക്ക്: "$3").  താങ്കൾ ഇപ്പോൾ ലോഗിൻ ചെയ്തു രഹസ്യവാക്ക് മാറ്റേണ്ടതാകുന്നു.

അംഗത്വം അബദ്ധവശാൽ ഉണ്ടാക്കിയതാണെങ്കിൽ താങ്കൾക്ക് ഈ സന്ദേശം നിരാകരിക്കാവുന്നതാണ്‌.',
'usernamehasherror'          => 'ഉപയോക്തൃനാമത്തിൽ ഹാഷ് ലിപികൾ ഉൾപ്പെടുത്തരുത്',
'login-throttled'            => 'താങ്കൾ നിരവധി പ്രാവശ്യം ലോഗിൻ ചെയ്യാൻ ശ്രമിച്ചിരിക്കുന്നു.
പുതിയതായി ശ്രമിക്കുന്നതിനു മുമ്പ് ദയവായി കാത്തിരിക്കുക.',
'loginlanguagelabel'         => 'ഭാഷ: $1',
'suspicious-userlogout'      => 'ലോഗൗട്ട് ചെയ്യാനുള്ള താങ്കളുടെ അഭ്യർത്ഥന നിരസിച്ചിരിക്കുന്നു, കാരണം അത് തകർന്ന ബ്രൗസറിൽ നിന്നോ കാഷിങ് പ്രോക്സിയിൽ നിന്നോ ഉണ്ടായതുപോലെ അനുഭവപ്പെടുന്നു.',

# Password reset dialog
'resetpass'                 => 'രഹസ്യവാക്ക് പുനഃക്രമീകരിക്കുക',
'resetpass_announce'        => 'താങ്കൾക്ക് ഇമെയിൽ ആയി കിട്ടിയ താൽക്കാലിക കോഡ് ഉപയോഗിച്ചാണ്‌ ഇപ്പോൾ ലോഗിൻ ചെയ്തിരിക്കുന്നതു്‌. ലോഗിൻ പ്രക്രിയ പൂർത്തിയാകുവാൻ പുതിയൊരു രഹസ്യവാക്ക് ഇവിടെ കൊടുക്കുക:',
'resetpass_header'          => 'അംഗത്വത്തിന്റെ രഹസ്യവാക്ക് പുനഃക്രമീകരിക്കുക',
'oldpassword'               => 'പഴയ രഹസ്യവാക്ക്:',
'newpassword'               => 'പുതിയ രഹസ്യവാക്ക്:',
'retypenew'                 => 'പുതിയ രഹസ്യവാക്ക് ഉറപ്പിക്കുക:',
'resetpass_submit'          => 'രഹസ്യവാക്ക് സജ്ജീകരിച്ചശേഷം ലോഗിൻ ചെയ്യുക',
'resetpass_success'         => 'താങ്കളുടെ രഹസ്യവാക്ക് വിജയകരമായി മാറ്റിയിരിക്കുന്നു! ഇപ്പോൾ താങ്കളെ സംരംഭത്തിലേക്ക് ആനയിക്കുന്നു...',
'resetpass_forbidden'       => 'രഹസ്യവാക്കുകൾ മാറ്റുന്നത് അനുവദിക്കുന്നില്ല',
'resetpass-no-info'         => 'ഈ താൾ നേരിട്ടു കാണുന്നതിന് താങ്കൾ ലോഗിൻ ചെയ്തിരിക്കണം.',
'resetpass-submit-loggedin' => 'രഹസ്യവാക്ക് മാറ്റുക',
'resetpass-submit-cancel'   => 'റദ്ദാക്കുക',
'resetpass-wrong-oldpass'   => 'സാധുതയില്ലാത്ത താത്കാലിക അല്ലെങ്കിൽ നിലവിലുള്ള രഹസ്യവാക്ക്.
നിലവിൽ താങ്കൾ വിജയകരമായി രഹസ്യവാക്ക് മാറ്റിയിട്ടുണ്ട് അല്ലെങ്കിൽ ഒരു പുതിയ താത്കാലിക രഹസ്യവാക്കിന് ആവശ്യപ്പെട്ടിരിക്കുന്നു.',
'resetpass-temp-password'   => 'താത്കാലിക രഹസ്യവാക്ക്:',

# Edit page toolbar
'bold_sample'     => 'കടുപ്പിച്ച എഴുത്ത്',
'bold_tip'        => 'കടുപ്പിച്ചെഴുതുവാൻ',
'italic_sample'   => 'ചരിച്ചുള്ള എഴുത്ത്',
'italic_tip'      => 'ചെരിച്ചെഴുതുവാൻ',
'link_sample'     => 'വിക്കികണ്ണി',
'link_tip'        => 'വിക്കികണ്ണി ചേർക്കുവാൻ',
'extlink_sample'  => 'http://www.example.com കണ്ണി തലക്കെട്ട്',
'extlink_tip'     => 'പുറത്തേക്കുള്ള കണ്ണി (http:// എന്ന ഉപസർഗ്ഗം ചേർക്കാൻ ഓർമ്മിക്കുക)',
'headline_sample' => 'തലക്കെട്ടിനുള്ള വാചകം ഇവിടെ ചേർക്കുക',
'headline_tip'    => 'രണ്ടാംഘട്ട തലക്കെട്ട്',
'math_sample'     => 'ഇവിടെ സൂത്രവാക്യം ചേർക്കുക',
'math_tip'        => 'ഗണിതസൂത്രവാക്യം (LaTeX)',
'nowiki_sample'   => 'വിക്കിഫോർമാറ്റിങ്ങ് ഉപയോഗിക്കേണ്ടാത്ത എഴുത്ത് ഇവിടെ ചേർക്കുക',
'nowiki_tip'      => 'വിക്കിരീതി അവഗണിക്കുക',
'image_tip'       => 'ചിത്രം ചേർക്കുവാൻ',
'media_tip'       => 'മീഡിയയിലേക്ക് വിക്കി-കണ്ണി ചേർക്കുവാൻ',
'sig_tip'         => 'തിരുത്തൽ സമയമടക്കമുള്ള താങ്കളുടെ ഒപ്പ്',
'hr_tip'          => 'തിരശ്ചീനരേഖ (മിതമായി മാത്രം ഉപയോഗിക്കുക)',

# Edit pages
'summary'                          => 'ചുരുക്കം:',
'subject'                          => 'വിഷയം/തലക്കെട്ട്:',
'minoredit'                        => 'ഇതൊരു ചെറിയ തിരുത്തലാണ്',
'watchthis'                        => 'ഈ താളിലെ മാറ്റങ്ങൾ ശ്രദ്ധിക്കുക',
'savearticle'                      => 'സേവ് ചെയ്യുക',
'preview'                          => 'എങ്ങനെയുണ്ടെന്നു കാണുക',
'showpreview'                      => 'എങ്ങനെയുണ്ടെന്നു കാണുക',
'showlivepreview'                  => 'തത്സമയ പ്രിവ്യൂ',
'showdiff'                         => 'മാറ്റങ്ങൾ കാണിക്കുക',
'anoneditwarning'                  => "'''മുന്നറിയിപ്പ്:''' താങ്കൾ ലോഗിൻ ചെയ്തിട്ടില്ല. താങ്കളുടെ ഐ.പി. വിലാസം താളിന്റെ തിരുത്തൽ ചരിത്രത്തിൽ ചേർക്കുന്നതാണ്.",
'anonpreviewwarning'               => "''താങ്കൾ ലോഗിൻ ചെയ്തിട്ടില്ല. സേവ് ചെയ്യുമ്പോൾ താളിന്റെ തിരുത്തൽ ചരിത്രത്തിൽ താങ്കളുടെ ഐ.പി. വിലാസം ചേർത്തു സൂക്ഷിക്കപ്പെടും.''",
'missingsummary'                   => "'''ഓർമ്മക്കുറിപ്പ്:''' താങ്കൾ തിരുത്തലിന്റെ ചുരുക്കരൂപം നൽകിയിട്ടില്ല. ''സേവ് ചെയ്യുക'' ബട്ടൺ ഒരുവട്ടം കൂടി അമർത്തിയാൽ താങ്കൾ വരുത്തിയ മാറ്റം കാത്തുസൂക്ഷിക്കുന്നതാണ്.",
'missingcommenttext'               => 'താങ്കളുടെ അഭിപ്രായം ദയവായി താഴെ രേഖപ്പെടുത്തുക.',
'missingcommentheader'             => "'''ഓർമ്മക്കുറിപ്പ്:''' ഈ കുറിപ്പിന് താങ്കൾ വിഷയം/തലക്കെട്ട് നൽകിയിട്ടില്ല. ''സേവ് ചെയ്യുക'' എന്ന ബട്ടൺ ഒരുവട്ടം കൂടി അമർത്തിയാൽ വിഷയം/തലക്കെട്ട് ഇല്ലാതെ തന്നെ കാത്തുസൂക്ഷിക്കുന്നതാവും.",
'summary-preview'                  => 'ചുരുക്കരൂപം എങ്ങനെയുണ്ടെന്നു കാണുക:',
'subject-preview'                  => 'വിഷയം/തലക്കെട്ട് എങ്ങനെയുണ്ടെന്ന് കാണുക:',
'blockedtitle'                     => 'ഉപയോക്താവിനെ തടഞ്ഞിരിക്കുന്നു',
'blockedtext'                      => "'''താങ്കളുടെ ഉപയോക്തൃനാമത്തേയോ താങ്കൾ ഇപ്പോൾ ലോഗിൻ ചെയ്തിട്ടുള്ള ഐ.പി. വിലാസത്തേയോ ഈ വിക്കി തിരുത്തുന്നതിൽ നിന്നു തടഞ്ഞിരിക്കുന്നു'''

$1 ആണ് ഈ തടയൽ നടത്തിയത്. ''$2'' എന്നതാണു് അതിനു രേഖപ്പെടുത്തിയിട്ടുള്ള കാരണം.

* തടയലിന്റെ തുടക്കം: $8
* തടയലിന്റെ കാലാവധി: $6
* തടയപ്പെട്ട ഉപയോക്താവ്: $7

ഈ തടയലിനെ പറ്റി ചർച്ച ചെയ്യാൻ താങ്കൾക്ക് $1 നേയോ മറ്റ് [[{{MediaWiki:Grouppage-sysop}}|കാര്യനിർ‌വാഹകരെയോ]] സമീപിക്കാവുന്നതാണ്. [[Special:Preferences|താങ്കളുടെ ക്രമീകരണങ്ങളിൽ]] താങ്കൾ സാധുവായ ഇമെയിൽ വിലാസം കൊടുത്തിട്ടുണ്ടെങ്കിൽ, അതു അയക്കുന്നതിൽ നിന്നു താങ്കൾ തടയപ്പെട്ടിട്ടില്ലെങ്കിൽ, 'ഇദ്ദേഹത്തിന് ഇമെയിൽ അയക്കൂ' എന്ന സം‌വിധാനം ഉപയോഗിച്ച് താങ്കൾക്ക് മറ്റുപയോക്താക്കളുമായി ബന്ധപ്പെടാം. താങ്കളുടെ നിലവിലുള്ള ഐ.പി. വിലാസം $3 ഉം, താങ്കളുടെ തടയൽ ഐ.ഡി. #$5 ഉം ആണ്. ഇവ രണ്ടും താങ്കൾ കാര്യനിർവാഹകനെ ബന്ധപ്പെടുമ്പോൾ ചേർക്കുക.",
'autoblockedtext'                  => 'താങ്കളുടെ ഐ.പി. വിലാസം സ്വയം തടയപ്പെട്ടിരിക്കുന്നു, മറ്റൊരു ഉപയോക്താവ് ഉപയോഗിച്ച കാരണത്താൽ $1 എന്ന കാര്യനിർവാഹകനാണ് തടഞ്ഞുവെച്ചത്.
ഇതിനു കാരണമായി നൽകിയിട്ടുള്ളത്:

:\'\'$2\'\'

* തടയൽ തുടങ്ങിയത്: $8
* തടയൽ അവസാനിക്കുന്നത്: $6
* തടയാൻ ഉദ്ദേശിച്ചത്: $7

ഈ തടയലിനെ കുറിച്ച് ചർച്ച ചെയ്യാൻ താങ്കൾക്കു $1 എന്ന കാര്യനിവാഹകനേയോ മറ്റു [[{{MediaWiki:Grouppage-sysop}}|കാര്യനിർ‌വാഹകരെയോ]] ബന്ധപ്പെടാവുന്നതാണ്.

ശ്രദ്ധിക്കുക [[Special:Preferences|താങ്കളുടെ ക്രമീകരണങ്ങളിൽ]] സാധുവായ ഇമെയിൽ വിലാസം രേഖപ്പെടുത്താതിരിക്കുകയോ, അത് ഉപയോഗിക്കുന്നതിൽ നിന്ന് താങ്കളെ തടയുകയോ ചെയ്തിട്ടുണ്ടെങ്കിൽ "ഇദ്ദേഹത്തിന് ഇമെയിൽ അയക്കൂ" എന്ന സം‌വിധാനം പ്രവർത്തന രഹിതമായിരിക്കും.

താങ്കളുടെ നിലവിലുള്ള ഐ.പി. വിലാസം $3 ആണ്, താങ്കളുടെ തടയലിന്റെ ഐ.ഡി. #$5 ആകുന്നു.
ദയവായി മുകളിൽ കൊടുത്തിരിക്കുന്ന വിവരങ്ങളെല്ലാം താങ്കൾ നടത്തുന്ന അന്വേഷണങ്ങളിൽ ഉൾപ്പെടുത്തുവാൻ ശ്രദ്ധിക്കുക.',
'blockednoreason'                  => 'കാരണമൊന്നും സൂചിപ്പിച്ചിട്ടില്ല',
'blockedoriginalsource'            => "'''$1''' എന്നതിന്റെ മൂലരൂപം താഴെക്കാണിച്ചിരിക്കുന്നു:",
'blockededitsource'                => "'''$1''' എന്ന താളിൽ '''താങ്കൾ നടത്തിയ തിരുത്തലുകളുടെ''' പൂർണ്ണരൂപം താഴെക്കാണിച്ചിരിക്കുന്നു:",
'whitelistedittitle'               => 'തിരുത്താൻ ലോഗിൻ ചെയ്യണം',
'whitelistedittext'                => 'താളുകൾ തിരുത്താൻ താങ്കൾ $1 ചെയ്യേണ്ടതാണ്',
'confirmedittext'                  => 'താളുകൾ തിരുത്തുന്നതിനു മുൻപ് താങ്കൾ താങ്കളുടെ ഇമെയിൽ വിലാസം സ്ഥിരീകരിക്കേണ്ടതാണ്‌. ഇമെയിൽ വിലാസം ക്രമപ്പെടുത്തി സാധുത പരിശോധിക്കാൻ [[Special:Preferences|എന്റെ ക്രമീകരണങ്ങൾ]] എന്ന സം‌വിധാനം ഉപയോഗിക്കുക.',
'nosuchsectiontitle'               => 'ഉപവിഭാഗം കണ്ടെത്താനായില്ല',
'nosuchsectiontext'                => 'നിലവിലില്ലാത്ത ഒരു ഉപവിഭാഗമാണു താങ്കൾ തിരുത്താൻ ശ്രമിക്കുന്നത്.
താങ്കൾ താൾ വായിച്ച നേരം അത് മാറ്റപ്പെടുകയോ നീക്കംചെയ്യപ്പെടുകയോ ചെയ്തിട്ടുണ്ടാവാം.',
'loginreqtitle'                    => 'ലോഗിൻ ചെയ്യേണ്ടതുണ്ട്',
'loginreqlink'                     => 'ലോഗിൻ ചെയ്യുക',
'loginreqpagetext'                 => 'മറ്റു താളുകൾ കാണാൻ താങ്കൾ $1 ചെയ്യേണ്ടതാണ്.',
'accmailtitle'                     => 'രഹസ്യവാക്ക് അയച്ചിരിക്കുന്നു.',
'accmailtext'                      => "[[User talk:$1|$1]] എന്ന ഉപയോക്താവിനുള്ള ക്രമരഹിതമായി നിർമ്മിച്ച രഹസ്യവാക്ക് $2 എന്ന വിലാസത്തിലേക്ക് അയച്ചിട്ടുണ്ട്.

പ്രവേശിച്ചതിനു ശേഷം ഈ പുതിയ അംഗത്വത്തിനുള്ള രഹസ്യവാക്ക് ''[[Special:ChangePassword|രഹസ്യവാക്ക് മാറ്റുക]]'' എന്ന താളിൽ‌വച്ച് മാറ്റാവുന്നതാണ്.",
'newarticle'                       => '(പുതിയത്)',
'newarticletext'                   => 'ഇതുവരെ നിലവിലില്ലാത്ത ഒരു താൾ സൃഷ്ടിക്കാനുള്ള ശ്രമത്തിലാണ് താങ്കൾ. അതിനായി താഴെ ആവശ്യമുള്ള വിവരങ്ങൾ എഴുതിച്ചേർത്ത് സേവ് ചെയ്യുക (കൂടുതൽ വിവരങ്ങൾക്ക് [[{{MediaWiki:Helppage}}|സഹായം താൾ]] കാണുക). താങ്കളിവിടെ അബദ്ധത്തിൽ വന്നതാണെങ്കിൽ ബ്രൗസറിന്റെ ബാക്ക് ബട്ടൺ ഞെക്കിയാൽ തിരിച്ചുപോകാം.',
'anontalkpagetext'                 => "----
{| class=\"messagebox standard-talk\" style=\"border: 1px solid #B3B300; background-color:#FFFFBF;\"
|align=\"left\" |
''ഇതുവരെ അംഗത്വം എടുക്കാതിരിക്കുകയോ, നിലവിലുള്ള അംഗത്വം ഉപയോഗിക്കാതിരിക്കുകയോ ചെയ്യുന്ന '''ഒരു അജ്ഞാത ഉപയോക്താവിന്റെ സം‌വാദം താളാണിത്'''.
അതിനാൽ അദ്ദേഹത്തെ തിരിച്ചറിയുവാൻ അക്കരൂപത്തിലുള്ള ഐ.പി. വിലാസം ഉപയോഗിക്കേണ്ടതുണ്ട്. ഇത്തരം ഒരു ഐ.പി. വിലാസം പല ഉപയോക്താക്കൾ പങ്കുവെക്കുന്നുണ്ടാവാം.
താങ്കൾ ഈ സന്ദേശം ലഭിച്ച ഒരു അജ്ഞാത ഉപയോക്താവാണെങ്കിൽ, ഭാവിയിൽ ഇതര ഉപയോക്താക്കളുമായി ഉണ്ടായേക്കാവുന്ന ആശയക്കുഴപ്പം ഒഴിവാക്കാൻ ദയവായി [[Special:UserLogin/signup|ഒരു അംഗത്വമെടുക്കുക]] അല്ലെങ്കിൽ  [[Special:UserLogin|പ്രവേശിക്കുക]].
|}",
'noarticletext'                    => 'ഈ താളിൽ ഇതുവരെ ഉള്ളടക്കം ആയിട്ടില്ല.
താങ്കൾക്ക് മറ്റുതാളുകളിൽ [[Special:Search/{{PAGENAME}}|ഇതേക്കുറിച്ച് അന്വേഷിക്കുകയോ]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ബന്ധപ്പെട്ട രേഖകൾ പരിശോധിക്കുകയോ], [{{fullurl:{{FULLPAGENAME}}|action=edit}} ഈ താൾ തിരുത്തുകയോ ചെയ്യാവുന്നതാണ്]</span>.',
'noarticletext-nopermission'       => 'ഇപ്പോൾ ഈ താളിൽ എഴുത്തുകളൊന്നും ഇല്ല.
താങ്കൾക്ക് മറ്റു താളുകളിൽ [[Special:Search/{{PAGENAME}}|ഈ താളിന്റെ തലക്കെട്ടിനായി തിരയാവുന്നതാണ്‌]],
അല്ലെങ്കിൽ <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ബന്ധപ്പെട്ട രേഖകൾ പരിശോധിക്കാവുന്നതാണ്‌]</span>.',
'userpage-userdoesnotexist'        => '"$1" എന്ന ഉപയോക്താവ് അംഗത്വമെടുത്തിട്ടില്ല. ഈ താൾ സൃഷ്ടിക്കണമോ എന്നതു പരിശോധിക്കുക.',
'userpage-userdoesnotexist-view'   => '"$1" എന്ന അം‌ഗത്വം നിലവിലില്ല.',
'blocked-notice-logextract'        => 'ഈ ഉപയോക്താവ് ഇപ്പോൾ തടയപ്പെട്ടിരിക്കുകയാണ്.
തടയൽ രേഖയിലെ പുതിയ ഉൾപ്പെടുത്തൽ അവലംബമായി താഴെ നൽകിയിരിക്കുന്നു:',
'clearyourcache'                   => "'''പ്രത്യേക ശ്രദ്ധയ്ക്ക്:'''

സേവ് ചെയ്ത ക്രമീകരണങ്ങൾ കാണാൻ താങ്കളുടെ ബ്രൗസറിന്റെ കാഷെ ക്ലിയർ ചെയ്യണം.

*'''മോസില്ല/ഫയർഫോക്സ്/സഫാരി''' എന്നീ ബ്രൗസറുകളിൽ ''Reload'' ബട്ടൺ അമർത്തുമ്പോൾ ''Shift'' കീ അമർത്തി പിടിക്കുകയോ ''Ctrl-Shift-R''  ഒരുമിച്ച് അമർത്തുകയോ (''Cmd-Shift-R'' on Apple Mac) ചെയ്യുക;
*'''ഇന്റർനെറ്റ് എക്സ്പ്ലോറർ (IE):''Refresh'' ബട്ടൺ അമർത്തുമ്പോൾ ''Ctrl'' കീ അമർത്തിപിടിക്കുക. അല്ലെങ്കിൽ ''Ctrl-F5'' അമർത്തുക;
*'''ഓപ്പറ (Opera)''':  ''Tools→Preferences'' ഉപയോഗിച്ച് കാഷെ പൂർണ്ണമായും ക്ലിയർ ചെയ്യുക;
*'''Konqueror:''': ''Reload'' ബട്ടൺ അമർത്തുകയോ ''F5'' കീ അമർത്തുകയോ ചെയ്യുക.",
'usercssyoucanpreview'             => "'''വഴികാട്ടി:''' താങ്കളുടെ പുതിയ CSS സേവ് ചെയ്യുന്നതിനു മുമ്പ് \"{{int:showpreview}}\" എന്ന ബട്ടൻ ഉപയോഗിച്ച് പരിശോധിക്കുക.",
'userjsyoucanpreview'              => "'''വഴികാട്ടി:''' താങ്കളുടെ പുതിയ ജാവാസ്ക്രിപ്റ്റ് സേവ് ചെയ്യുന്നതിനു മുമ്പ് \"{{int:showpreview}}\" എന്ന ബട്ടൻ ഉപയോഗിച്ച് പരിശോധിക്കുക.",
'usercsspreview'                   => "'''താങ്കൾ താങ്കളുടെ സ്വന്തം CSS പ്രിവ്യൂ ചെയ്യുക മാത്രമേ ചെയ്യുന്നുള്ളൂ എന്ന കാര്യം ഓർമ്മിക്കുക.'''
'''ഇതു സേവ് ചെയ്തിട്ടില്ല!'''",
'userjspreview'                    => "'''താങ്കൾ താങ്കളുടെ സ്വന്തം ജാവസ്ക്രിപ്റ്റ് പ്രിവ്യൂ ചെയ്യുക മാത്രമേ ചെയ്യുന്നുള്ളൂ എന്ന കാര്യം ഓർമ്മിക്കുക. ഇതു സേവ് ചെയ്തിട്ടില്ല!'''",
'userinvalidcssjstitle'            => "'''മുന്നറിപ്പ്:''' \"\$1\" എന്ന പേരിൽ ഒരു സ്കിൻ ഇല്ല. '''.css''' ഉം '''.js''' ഉം താളുകൾ ഇംഗ്ലീഷ് ചെറിയക്ഷര തലക്കെട്ട് ആണ്‌ ഉപയോഗിക്കുന്നതെന്നു ദയവായി ഓർക്കുക. ഉദാ: {{ns:user}}:Foo/Monobook.css എന്നതിനു പകരം {{ns:user}}:Foo/monobook.css എന്നാണു ഉപയോഗിക്കേണ്ടത്.",
'updated'                          => '(പുതുക്കിയിരിക്കുന്നു)',
'note'                             => "'''പ്രത്യേക ശ്രദ്ധയ്ക്ക്:'''",
'previewnote'                      => "'''ഇതൊരു പ്രിവ്യൂ മാത്രമാണ്, താങ്കൾ നടത്തിയ മാറ്റങ്ങൾ സേവ് ചെയ്തിട്ടില്ല!'''",
'previewconflict'                  => 'ഈ പ്രിവ്യൂവിൽ മുകളിലെ ടെക്സ്റ്റ് ഏരിയയിലുള്ള എഴുത്ത് മാത്രമാണ് കാട്ടുന്നത്, സേവ്‌ ചെയ്യാൻ താങ്കൾ തീരുമാനിച്ചാൽ അത് സേവ് ആകുന്നതാണ്.',
'session_fail_preview'             => "'''ക്ഷമിക്കണം! സെഷൻ ഡാറ്റ നഷ്ടപ്പെട്ടതിനാൽ താങ്കളുടെ തിരുത്തലിന്റെ തുടർപ്രക്രിയ നടത്തുവാൻ സാധിച്ചില്ല. ഒരു പ്രാവശ്യം കൂടി ദയവായി ശ്രമിക്കൂ. എന്നിട്ടും ശരിയാവുന്നില്ലെങ്കിൽ [[Special:UserLogout|ലോഗൗട്ട് ചെയ്തതിനു ശേഷം]] പിന്നേയും ലോഗിൻ ചെയ്യൂ.'''",
'session_fail_preview_html'        => "'''ക്ഷമിക്കണം! സെഷൻ ഡാറ്റ നഷ്ടപ്പെട്ടതിനാൽ താങ്കളുടെ തിരുത്തലിന്റെ തുടർപ്രക്രിയ നടത്തുവാൻ സാധിച്ചില്ല.'''

''{{SITENAME}} സം‌രംഭത്തിൽ raw HTML സജ്ജമാക്കിയിരിക്കുന്നതിനാൽ, ജാവാസ്ക്രിപ്റ്റ് ആക്രമണത്തിനെതിരെയുള്ള മുൻ‌കരുതൽ എന്ന നിലയിൽ പ്രിവ്യൂ മറച്ചിരിക്കുന്നു.''

'''താങ്കളുടേതു ഉത്തരവാദിത്വത്തോടെയുള്ള തിരുത്തലെങ്കിൽ, ദയവായി വീണ്ടും ശ്രമിക്കൂ'''. എന്നിട്ടും ശരിയാവുന്നില്ലെങ്കിൽ [[Special:UserLogout|ലോഗൗട്ട് ചെയ്തതിനു]] ശേഷം വീണ്ടും ലോഗിൻ ചെയ്യൂ.",
'token_suffix_mismatch'            => "'''താങ്കളുടെ ക്ലൈന്റ് തിരുത്തൽ കുറിയിലെ ചിഹ്നനങ്ങൾ നശിപ്പിച്ചതിനാൽ താങ്കളുടെ തിരുത്തൽ സ്വീകരിക്കുന്നില്ല.'''
താളിലെ എഴുത്തിന്റെ നാശം ഒഴിവാക്കാനാണു താങ്കളുടെ തിരുത്തൽ സ്വീകരിക്കാത്തത്.
ഗുണനിലവാരമില്ലാത്ത വെബ് അധിഷ്ഠിത അജ്ഞാത പ്രോക്സി സേവനങ്ങൾ ഉപയോഗിച്ചാൽ ചിലപ്പോൾ ഇത്തരത്തിലുണ്ടാകാറുണ്ട്.",
'editing'                          => 'തിരുത്തുന്ന താൾ: $1',
'editingsection'                   => 'തിരുത്തുന്ന താൾ:- $1 (ഉപവിഭാഗം)',
'editingcomment'                   => 'തിരുത്തപ്പെടുന്നത്:- $1 (പുതിയ ഉപവിഭാഗം)',
'editconflict'                     => 'തിരുത്തൽ സമരസപ്പെടായ്ക: $1',
'explainconflict'                  => "താങ്കൾ തിരുത്താൻ തുടങ്ങിയതിനു ശേഷം ഈ താൾ മറ്റാരോ തിരുത്തി സേവ് ചെയ്തിരിക്കുന്നു.
മുകളിലുള്ള ടെക്സ്റ്റ് ഏരിയയിൽ നിലവിലുള്ള ഉള്ളടക്കം കാണിക്കുന്നു.
താങ്കൾ ഉള്ളടക്കത്തിൽ വരുത്തിയ മാറ്റങ്ങൾ താഴെയുള്ള ടെക്സ്റ്റ് ഏരിയയിൽ കാണിക്കുന്നു.
താങ്കളുടെ മാറ്റങ്ങൾ മുകളിലെ ടെക്സ്റ്റ് ഏരിയയിലേക്ക് സം‌യോജിപ്പിക്കുക.
താങ്കൾ '''സേവ് ചെയ്യുക''' എന്ന ബട്ടൺ അമർത്തുമ്പോൾ '''മുകളിലെ ടെക്സ്റ്റ് ഏരിയയിലുള്ള എഴുത്തുകൾ മാത്രമേ''' സേവ് ആവുകയുള്ളൂ.",
'yourtext'                         => 'താങ്കൾ എഴുതി ചേർത്തത്',
'storedversion'                    => 'സംഭരിക്കപ്പെട്ടിരിക്കുന്ന പതിപ്പ്',
'nonunicodebrowser'                => "'''മുന്നറിയിപ്പ്: താങ്കളുടെ ബ്രൗസർ യൂണീകോഡിനു സജ്ജമല്ല. താളുകൾ സുരക്ഷിതമായി തിരുത്താനുള്ള സൗകര്യം ഒരുക്കിയിട്ടുണ്ട്: ASCII അല്ലാത്ത അക്ഷരങ്ങൾ ഹെക്സാഡെസിമൽ കോഡായി തിരുത്തുവാനുള്ള പെട്ടിയിൽ പ്രത്യക്ഷപ്പെടുന്നതാണ്.'''",
'editingold'                       => "'''മുന്നറിയിപ്പ്: താങ്കൾ ഈ താളിന്റെ ഒരു പഴയ പതിപ്പാണ്‌ തിരുത്തുന്നത്. ഇപ്പോൾ താങ്കൾ വരുത്തിയ മാറ്റങ്ങൾ സേവ് ചെയ്താൽ ഈ പതിപ്പിനു ശേഷം വന്ന മാറ്റങ്ങളെല്ലാം നഷ്ടമാകും.'''",
'yourdiff'                         => 'വ്യത്യാസങ്ങൾ',
'copyrightwarning'                 => "{{SITENAME}} സംരംഭത്തിൽ എഴുതപ്പെടുന്ന ലേഖനങ്ങളെല്ലാം $2 പ്രകാരം സ്വതന്ത്രമാണ് (വിശദാംശങ്ങൾക്ക് $1 കാണുക). താങ്കൾ എഴുതുന്ന ലേഖനം തിരുത്തപ്പെടുന്നതിലോ ഒഴിവാക്കപ്പെടുന്നതിലോ എതിർപ്പുണ്ടെങ്കിൽ ദയവായി ലേഖനമെഴുതാതിരിക്കുക.

ഈ ലേഖനം താങ്കൾത്തന്നെ എഴുതിയതാണെന്നും അതല്ലെങ്കിൽ പകർപ്പവകാശനിയമങ്ങളുടെ പരിധിയിലില്ലാത്ത ഉറവിടങ്ങളിൽനിന്ന് പകർത്തിയതാണെന്നും ഉറപ്പാക്കുക.

'''പകർപ്പവകാശ സംരക്ഷണമുള്ള സൃഷ്ടികൾ ഒരു കാരണവശാലും ഇവിടെ പ്രസിദ്ധീകരിക്കരുത്.'''",
'copyrightwarning2'                => "{{SITENAME}} സംരംഭത്തിൽ താങ്കൾ എഴുതി ചേർക്കുന്നതെല്ലാം മറ്റുപയോക്താക്കൾ തിരുത്തുകയോ, മാറ്റം വരുത്തുകയോ, ഒഴിവാക്കുകയോ ചെയ്തേക്കാം. താങ്കൾ എഴുതി ചേർക്കുന്നതു മറ്റ് ഉപയോക്താക്കൾ തിരുത്തുന്നതിലോ ഒഴിവാക്കുന്നതിലോ താങ്കൾക്ക് എതിർപ്പുണ്ടെങ്കിൽ ദയവായി ലേഖനമെഴുതാതിരിക്കുക.
ഇതു താങ്കൾത്തന്നെ എഴുതിയതാണെന്നും, അതല്ലെങ്കിൽ പകർപ്പവകാശ നിയമങ്ങളുടെ പരിധിയിലില്ലാത്ത ഉറവിടങ്ങളിൽനിന്നും പകർത്തിയതാണെന്നും ഉറപ്പാക്കുക (കുടുതൽ വിവരത്തിനു $1 കാണുക).
'''പകർപ്പവകാശ സംരക്ഷണമുള്ള സൃഷ്ടികൾ ഒരു കാരണവശാലും ഇവിടെ പ്രസിദ്ധീകരിക്കരുത്!'''",
'longpagewarning'                  => "'''മുന്നറിയിപ്പ്:''' ഈ താളിന് $1 കിലോബൈറ്റ്സ് നീളമുണ്ട്;
ചില ബ്രൗസറുകളിൽ 32 കിലോബൈറ്റ്സിൽ കൂടിയ വലിയ താളുകൾ തിരുത്തുമ്പോൾ പ്രശ്നമുണ്ടാകാറുണ്ട്.
താളുകളുടെ ഉപവിഭാഗങ്ങൾ തിരഞ്ഞെടുത്ത് തിരുത്തുന്നത് പരിഗണിക്കുക.",
'longpageerror'                    => "'''പിഴവ്: താങ്കൾ സമർപ്പിച്ച എഴുത്തുകൾക്ക് $1 കിലോബൈറ്റ്സ് വലിപ്പമുണ്ട്. പരമാവധി അനുവദനീയമായ വലിപ്പം $2 കിലോബൈറ്റ്സ് ആണ്‌. അതിനാലിതു സേവ് ചെയ്യാൻ സാദ്ധ്യമല്ല.'''",
'readonlywarning'                  => "'''മുന്നറിയിപ്പ്: വിവരശേഖരം അതിന്റെ പരിപാലനത്തിനു വേണ്ടി ബന്ധിച്ചിരിക്കുന്നു, അതുകൊണ്ട് താങ്കളിപ്പോൾ വരുത്തിയ മാറ്റങ്ങൾ സേവ് ചെയ്യാൻ സാദ്ധ്യമല്ല.''' താങ്കൾ വരുത്തിയ മാറ്റങ്ങൾ ഒരു ടെക്സ്റ്റ് പ്രമാണത്തിലേക്ക് പകർത്തി (കട്ട് & പേസ്റ്റ്) പിന്നീടുള്ള ഉപയോഗത്തിനായി സേവ് ചെയ്യുവാൻ താല്പര്യപ്പെടുന്നു. വിവരശേഖരം ബന്ധിച്ച അഡ്മിനിസ്ട്രേറ്റർ നൽകിയ വിശദീകരണം: $1",
'protectedpagewarning'             => "'''മുന്നറിയിപ്പ്:  ഈ താൾ കാര്യനിർവാഹക പദവിയുള്ളവർക്കു മാത്രം തിരുത്താൻ സാധിക്കാവുന്ന തരത്തിൽ സം‌രക്ഷിക്കപ്പെട്ടിരിക്കുന്നു.''' അവലംബമായി രേഖകളിൽ ലഭ്യമായ ഏറ്റവും പുതിയ വിവരം താഴെ നൽകിയിരിക്കുന്നു:",
'semiprotectedpagewarning'         => "'''ശ്രദ്ധിക്കുക:'''അംഗത്വമെടുത്തിട്ടുള്ളവർക്കുമാത്രം തിരുത്താൻ സാധിക്കുന്ന വിധത്തിൽ ഈ താൾ സംരക്ഷിക്കപ്പെട്ടിരിക്കുന്നു. അവലംബമായി രേഖകളിലെ ഏറ്റവും പുതിയ വിവരം താഴെ കൊടുത്തിരിക്കുന്നു:",
'cascadeprotectedwarning'          => "'''മുന്നറിയിപ്പ്:''' ഈ താൾ കാര്യനിർ‌വാഹക അവകാശമുള്ളവർക്കു മാത്രം തിരുത്തുവാൻ സാധിക്കുന്ന വിധത്തിൽ സം‌രക്ഷിക്കപ്പെട്ടിട്ടുള്ളതാണ്‌. {{PLURAL:$1|താൾ|താളുകൾ}} കാസ്കേഡ് സം‌രക്ഷണം ചെയ്തപ്പോൾ അതിന്റെ ഭാഗമായി സംരക്ഷിക്കപ്പെട്ടിട്ടുള്ളതാണ്‌ ഈ താൾ.",
'titleprotectedwarning'            => "'''മുന്നറിയിപ്പ്: [[Special:ListGroupRights|പ്രത്യേക അവകാശമുള്ള]] ഉപയോക്താക്കൾക്ക് മാത്രം സൃഷ്ടിക്കാൻ സാധിക്കുന്ന വിധത്തിൽ ഈ താൾ സംരക്ഷിക്കപ്പെട്ടിരിക്കുന്നു.''' അവലംബമായി രേഖകളിൽ ലഭ്യമായ ഏറ്റവും പുതിയ വിവരം താഴെ നൽകിയിരിക്കുന്നു:",
'templatesused'                    => 'ഈ താളിൽ ഉപയോഗിച്ചിരിക്കുന്ന {{PLURAL:$1|ഫലകം|ഫലകങ്ങൾ}}:',
'templatesusedpreview'             => 'ഈ പ്രിവ്യൂവിൽ ഉപയോഗിച്ചിരിക്കുന്ന {{PLURAL:$1|ഫലകം|ഫലകങ്ങൾ}}:',
'templatesusedsection'             => 'ഈ ഉപവിഭാഗത്തിൽ ഉപയോഗിച്ചിരിക്കുന്ന {{PLURAL:$1|ഫലകം|ഫലകങ്ങൾ}}:',
'template-protected'               => '(സം‌രക്ഷിക്കപ്പെട്ടിരിക്കുന്നു)',
'template-semiprotected'           => '(അർ‌ദ്ധസം‌രക്ഷിതം)',
'hiddencategories'                 => 'ഈ താൾ {{PLURAL:$1|മറഞ്ഞിരിക്കുന്ന ഒരു വർഗ്ഗത്തിൽ|മറഞ്ഞിരിക്കുന്ന $1 വർഗ്ഗങ്ങളിൽ}} അംഗമാണു്‌:',
'edittools'                        => '<!-- തിരുത്തുവാനുള്ളതിനും അപ്‌‌ലോഡ് ചെയ്യുന്നതിനുമുള്ള ഫോമുകൾക്കടിയിൽ ഇവിടെ നൽകുന്ന എഴുത്തുകൾ വരുന്നതാണ്. -->',
'nocreatetitle'                    => 'താളുകൾ സൃഷ്ടിക്കുന്നത് പരിമിതപ്പെടുത്തിയിരിക്കുന്നു',
'nocreatetext'                     => '{{SITENAME}} സംരംഭത്തിൽ പുതിയ താളുകൾ സൃഷ്ടിക്കുവാനുള്ള അവകാശം നിയന്ത്രിതമാണ്‌.
താങ്കൾ ദയവായി തിരിച്ചുചെന്ന് നിലവിലുള്ള ഒരു താൾ തിരുത്തുകയോ, അഥവാ [[Special:UserLogin|ലോഗിൻ ചെയ്യുകയോ ഒരു അംഗത്വം സൃഷ്ടിക്കുകയോ]] ചെയ്യാൻ അഭ്യർത്ഥിക്കുന്നു.',
'nocreate-loggedin'                => 'പുതിയ താളുകൾ സൃഷ്ടിക്കുവാനുള്ള അനുവാദം താങ്കൾക്കില്ല.',
'sectioneditnotsupported-title'    => 'വിഭാഗങ്ങളായുള്ള തിരുത്തൽ പിന്തുണയ്ക്കുന്നില്ല',
'sectioneditnotsupported-text'     => 'ഈ തിരുത്താനുള്ള താളിൽ വിഭാഗങ്ങളായുള്ള തിരുത്തൽ പിന്തുണയ്ക്കുന്നില്ല.',
'permissionserrors'                => 'അനുമതിപ്രശ്നം',
'permissionserrorstext'            => 'താഴെ കൊടുത്തിരിക്കുന്ന {{PLURAL:$1|കാരണം|കാരണങ്ങൾ}} കൊണ്ട് താങ്കൾക്ക് ഈ പ്രവൃത്തി ചെയ്യാനുള്ള അനുമതിയില്ല:',
'permissionserrorstext-withaction' => 'താങ്കൾക്ക് $2 എന്ന പ്രവൃത്തി ചെയ്യാൻ അനുമതി ഇല്ല, {{PLURAL:$1|കാരണം|കാരണങ്ങൾ}} താഴെ കൊടുത്തിരിക്കുന്നു:',
'recreate-moveddeleted-warn'       => "'''മുന്നറിയിപ്പ്: മുമ്പ് മായ്ച്ചുകളഞ്ഞ താളാണ്‌ താങ്കൾ വീണ്ടും ചേർക്കാൻ ശ്രമിക്കുന്നത്'''

താങ്കൾ ചെയ്യുന്നത് ശരിയായ നടപടിയാണോ എന്നു പരിശോധിക്കുക. ഉറപ്പിനായി ഈ താളിന്റെ മായ്ക്കൽ രേഖയും മാറ്റൽ രേഖയും കൂടെ ചേർത്തിരിക്കുന്നു.",
'moveddeleted-notice'              => 'ഈ താൾ മായ്ക്കപ്പെട്ടിരിക്കുന്നു. 
ഈ താളിന്റെ മായ്ക്കൽ രേഖ പരിശോധനയ്ക്കായി താഴെ കൊടുത്തിരിക്കുന്നു',
'log-fulllog'                      => 'എല്ലാ രേഖകളും കാണുക',
'edit-hook-aborted'                => 'കൊളുത്ത് ഛേദിച്ച തിരുത്ത്.
ഇത് ഒരു വിശദീകരണവും നൽകിയിട്ടില്ല.',
'edit-gone-missing'                => 'ഈ താൾ പുതുക്കുവാൻ സാധിക്കുകയില്ല.
ഇത് മായ്ക്കപ്പെട്ടതായി കാണുന്നു.',
'edit-conflict'                    => 'തിരുത്തൽ സമരസപ്പെടായ്ക.',
'edit-no-change'                   => 'ഇപ്പോഴുള്ള സ്ഥിതിയിൽ നിന്നു യാതൊരു മാറ്റവും ഇല്ലാത്തതിനാൽ താങ്കളുടെ തിരുത്തലുകൾ തിരസ്കരിക്കപ്പെട്ടിരിക്കുന്നു.',
'edit-already-exists'              => 'പുതിയ താൾ സൃഷ്ടിക്കാൻ കഴിഞ്ഞില്ല.
താൾ ഇപ്പോൾ തന്നെ നിലവിലുണ്ട്.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Warning: This page contains too many expensive parser function calls.

It should have less than $2 {{PLURAL:$2|call|calls}}, there {{PLURAL:$1|is now $1 call|are now $1 calls}}.',
'expensive-parserfunction-category'       => 'വളരെയധികം വിലയേറിയ വ്യാകരണ നിർദ്ദേശങ്ങൾ അടങ്ങിയ താളുകൾ',
'post-expand-template-inclusion-warning'  => "'''അറിയിപ്പ്:''' ഫലകം ഉൾപ്പെടുത്താവുന്ന വലിപ്പത്തിലും വളരെ കൂടുതൽ ആയിരിക്കുന്നു.
ചില ഫലകങ്ങൾ ഉൾപ്പെടുത്തുകയില്ല.",
'post-expand-template-inclusion-category' => 'ഫലകം ഉൾപ്പെടുത്താവുന്ന വലിപ്പത്തിലും കൂടുതലുള്ള താളുകൾ',
'post-expand-template-argument-warning'   => "'''അറിയിപ്പ്:''' ഈ താളിൽ വളരെയധികം വികസിപ്പിക്കപ്പെട്ടേക്കാവുന്ന ഒരു ഫലകമെങ്കിലും ഉണ്ട്.
അതിനായുള്ള ഘടകങ്ങൾ ഒഴിവാക്കിയിരിക്കുന്നു.",
'post-expand-template-argument-category'  => 'താൾ ഫലകത്തിന്റെ ഘടകങ്ങളിൽ ഒഴിവാക്കിയവ ഉൾക്കൊള്ളുന്നു',
'parser-template-loop-warning'            => 'ഫലകക്കുരുക്ക് കണ്ടെത്തിയിരിക്കുന്നു: [[$1]]',
'parser-template-recursion-depth-warning' => 'ഫലകത്തിന്റെ പുനരാവർത്തന ആഴത്തിന്റെ പരിധി കഴിഞ്ഞിരിക്കുന്നു ($1)',
'language-converter-depth-warning'        => 'ഭാഷ മാറ്റൽ ഉപകരണത്തിന്റെ ആഴത്തിന്റെ പരിധി കവിഞ്ഞിരിക്കുന്നു ($1)',

# "Undo" feature
'undo-success' => 'ഈ തിരുത്തൽ താങ്കൾക്ക് തിരസ്ക്കരിക്കാവുന്നതാണ്‌. താഴെ കൊടുത്തിരിക്കുന്ന പതിപ്പുകൾ തമ്മിലുള്ള താരതമ്യം ഒന്നുകൂടി പരിശോധിച്ച് ഈ പ്രവൃത്തി ചെയ്യണോ എന്ന് ഒന്നുകൂടി ഉറപ്പാക്കുക. ഉറപ്പാണെങ്കിൽ തിരുത്തൽ തിരസ്ക്കരിക്കുവാൻ താൾ സേവ് ചെയ്യുക.',
'undo-failure' => 'ഇടയ്ക്കുള്ള തിരുത്തലുകൾ തമ്മിലുള്ള കോൺഫ്ലിറ്റ് കാരണം ഈ തിരുത്തൽ തിരസ്ക്കരിക്കുവാൻ പറ്റില്ല.',
'undo-norev'   => 'ഈ തിരുത്തൽ നിലവിലില്ലാത്തതിനാലോ മായ്ക്കപ്പെട്ടതിനാലോ പൂർവസ്ഥിതിയിലാക്കുവാൻ സാധിക്കുകയില്ല.',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]) ചെയ്ത $1 എന്ന തിരുത്തൽ നീക്കം ചെയ്യുന്നു',

# Account creation failure
'cantcreateaccounttitle' => 'അംഗത്വം സൃഷ്ടിക്കാൻ സാധിച്ചില്ല',
'cantcreateaccount-text' => "ഈ ഐ.പി. ('''$1''') വിലാസത്തിൽ നിന്നു അംഗത്വം സൃഷ്ടിക്കുന്നത് [[User:$3|$3]] നിരോധിച്ചിരിക്കുന്നു.

$3 അതിനു കാണിച്ചിരിക്കുന്ന കാരണം ''$2'' ആണ്‌.",

# History pages
'viewpagelogs'           => 'ഈ താളുമായി ബന്ധപ്പെട്ട രേഖകൾ കാണുക',
'nohistory'              => 'ഈ താളിന് നാൾവഴി ഇല്ല.',
'currentrev'             => 'ഇപ്പോഴുള്ള രൂപം',
'currentrev-asof'        => '$1 -ൽ നിലവിലുള്ള രൂപം',
'revisionasof'           => '$1-നു നിലവിലുണ്ടായിരുന്ന രൂപം',
'revision-info'          => '$1-നു ഉണ്ടായിരുന്ന രൂപം സൃഷ്ടിച്ചത്:- $2',
'previousrevision'       => '←പഴയ രൂപം',
'nextrevision'           => 'പുതിയ രൂപം→',
'currentrevisionlink'    => 'ഇപ്പോഴുള്ള രൂപം',
'cur'                    => 'ഇപ്പോൾ',
'next'                   => 'അടുത്തത്',
'last'                   => 'മുമ്പ്',
'page_first'             => 'ആദി',
'page_last'              => 'അന്ത്യം',
'histlegend'             => "വ്യത്യാസങ്ങൾ ഒത്തുനോക്കാൻ: ഒത്തുനോക്കേണ്ട പതിപ്പുകൾക്കൊപ്പമുള്ള റേഡിയോ ബട്ടൺ തിരഞ്ഞെടുത്ത് ''\"തിരഞ്ഞെടുത്ത പതിപ്പുകൾ തമ്മിലുള്ള വ്യത്യാസം കാണുക\"'' എന്ന ബട്ടൺ ഞെക്കുകയോ ENTER കീ അമർത്തുകയോ ചെയ്യുക.<br />

സൂചന: (ഇപ്പോൾ) = നിലവിലുള്ള പതിപ്പുമായുള്ള വ്യത്യാസം, (മുമ്പ്) = തൊട്ടുമുൻപത്തെ പതിപ്പുമായുള്ള വ്യത്യാസം, (ചെ.) = ചെറിയ തിരുത്തൽ.",
'history-fieldset-title' => 'നാൾ‌വഴി പരിശോധന',
'history-show-deleted'   => 'മായ്ക്കപ്പെട്ടവ മാത്രം',
'histfirst'              => 'പഴയവ',
'histlast'               => 'പുതിയവ',
'historysize'            => '({{PLURAL:$1|1 ബൈറ്റ്|$1 ബൈറ്റുകൾ}})',
'historyempty'           => '(ശൂന്യം)',

# Revision feed
'history-feed-title'          => 'നാൾവഴി',
'history-feed-description'    => 'വിക്കിയിൽ ഈ താളിന്റെ നാൾവഴി',
'history-feed-item-nocomment' => '$1 ൽ $2',
'history-feed-empty'          => 'താങ്കൾ തിരഞ്ഞ താൾ നിലവിലില്ല.
പ്രസ്തുത താൾ വിക്കിയിൽ നിന്നു ഒഴിവാക്കിയിരിക്കാനോ പുനർനാമകരണം ചെയ്തിരിക്കാനോ സാദ്ധ്യത ഉണ്ട്.
ബന്ധപ്പെട്ട പുതിയ താളുകൾ കണ്ടെത്താൻ [[Special:Search|വിക്കിയിലെ തിരച്ചിൽ]] എന്ന താൾ ഉപയോഗിക്കുക.',

# Revision deletion
'rev-deleted-comment'         => '(പ്രസ്താവന ഒഴിവാക്കിയിരിക്കുന്നു)',
'rev-deleted-user'            => '(ഉപയോക്തൃനാമം ഒഴിവാക്കിയിരിക്കുന്നു)',
'rev-deleted-event'           => '(പ്രവർത്തനരേഖയിൽ നടത്തിയ പ്രവർത്തനം ഒഴിവാക്കിയിരിക്കുന്നു)',
'rev-deleted-user-contribs'   => '[ഉപയോക്തൃനാമം അഥവാ ഐ.പി. വിലാസം ഒഴിവാക്കപ്പെട്ടിരിക്കുന്നു - തിരുത്തൽ സേവനങ്ങളിൽ നിന്നും മറച്ചിരിക്കുന്നു]',
'rev-deleted-text-permission' => "താളിന്റെ ഈ നാൾപ്പതിപ്പ് '''മായ്ച്ചിരിക്കുന്നു'''.
കൂടുതൽ വിവരങ്ങൾ [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} മായ്ക്കൽ രേഖയിൽ] കാണാവുന്നതാണ്.",
'rev-deleted-text-unhide'     => "താളിന്റെ ഈ നാൾപ്പതിപ്പ് '''മായ്ച്ചിരിക്കുന്നു'''.
കൂടുതൽ വിവരങ്ങൾ [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} മായ്ക്കൽ രേഖയിൽ] ഉണ്ടായിരിക്കും.
കാര്യനിർവ്വാഹകനെന്ന നിലയിൽ താങ്കൾക്ക് ഇപ്പോഴും വേണമെങ്കിൽ [$1 ഈ നാൾപ്പതിപ്പ് കാണാവുന്നതാണ്].",
'rev-suppressed-text-unhide'  => "താളിന്റെ ഈ സംശോധനം '''ഒതുക്കപ്പെട്ടിരിക്കുന്നു'''.
കൂടുതൽ വിവരങ്ങൾ [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} ഒതുക്കൽ രേഖയിൽ] ഉണ്ടാകും.
കാര്യനിർവാകനായതിനാൽ  താങ്കൾക്ക് ആവശ്യമെങ്കിൽ [$1 ഈ സംശോധനം കാണാൻ] കഴിയുന്നതാണ്.",
'rev-deleted-text-view'       => "ഈ താളിന്റെ പതിപ്പുകൾ '''മായ്ച്ചിരിക്കുന്നു'''.

കാര്യനിർ‌വാഹകൻ എന്ന നിലയിൽ താങ്കാൾക്ക് അവ കാണാവുന്നതാണ്;  കൂടുതൽ വിവരങ്ങൾ [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} മായ്ക്കൽ രേഖയിൽ] കാണാം.",
'rev-suppressed-text-view'    => "താളിന്റെ ഈ സംശോധനം '''ഒതുക്കിയിരിക്കുന്നു'''.
കാര്യനിർവാഹകനെന്നിരിക്കെ താങ്കൾക്കത് കാണാവുന്നതാണ്; കൂടുതൽ വിവരങ്ങൾ [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} ഒതുക്കൽ രേഖയിൽ] ഉണ്ട്.",
'rev-deleted-no-diff'         => "നാൾപ്പതിപ്പുകളിലൊന്ന് '''മായ്ച്ചിരിക്കുന്നതിനാൽ''' ഈ വ്യത്യാസം താങ്കൾക്ക് കാണാൻ കഴിയില്ല.
കൂടുതൽ വിവരങ്ങൾ [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} മായ്ക്കൽ രേഖയിൽ] ഉണ്ടായിരിക്കും.",
'rev-suppressed-no-diff'      => "ഒരു നാൾപ്പതിപ്പ് '''മായ്ക്കപ്പെട്ടിരിക്കുന്നു''' എന്ന കാരണത്താൽ ഈ വ്യത്യാസം കാണാൻ താങ്കൾക്ക് കഴിയില്ല.",
'rev-deleted-unhide-diff'     => "ഈ വ്യത്യാസങ്ങളിലെ ഒരു നാൾപ്പതിപ്പ് '''മായ്ക്കപ്പെട്ടിരിക്കുന്നു'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} മായ്ക്കൽ രേഖയിൽ] വിശദവിവരങ്ങൾ ഉണ്ടായിരിക്കും.
കാര്യനിർവാഹകനായതിനാൽ താങ്കൾക്ക് [$1 ഈ വ്യത്യാസം] വേണമെങ്കിൽ കാണാവുന്നതാണ്.",
'rev-suppressed-unhide-diff'  => "ഈ വ്യത്യാസത്തിലെ ഒരു നാൾപ്പതിപ്പ് '''ഒതുക്കിയിരിക്കുന്നു'''.
[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} ഒതുക്കൽ രേഖയിൽ] കൂടുതൽ വിവരങ്ങൾ ഉണ്ടാകാം.
ഒരു കാര്യനിർവാഹകനായതിനാൽ താങ്കൾ ആഗ്രഹിക്കുന്നുവെങ്കിൽ [$1 ഈ വ്യത്യാസം കാണാവുന്നതാണ്].",
'rev-deleted-diff-view'       => "ഈ വ്യത്യാസത്തിലെ ഒരു മാറ്റം '''മായ്ക്കപ്പെട്ടിരിക്കുന്നു'''.
കാര്യനിർവാഹകനായതിനാൽ താങ്കൾക്ക് ഈ മാറ്റം കാണാവുന്നതാണ്; കൂടുതൽ വിവരങ്ങൾ [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} മായ്ക്കൽ രേഖയിൽ] ഉണ്ടാവും.",
'rev-suppressed-diff-view'    => "ഈ വ്യത്യാസത്തിലെ ഒരു നാൾപ്പതിപ്പ് '''ഒതുക്കിയിരിക്കുന്നു'''.
ഒരു കാര്യനിർവാഹകനായതിനാല താങ്കൾ ഈ മാറ്റം കാണാൻ കഴിയുന്നതാണ്; കൂടുതൽ വിവരങ്ങൾ [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} ഒതുക്കൽ രേഖയിൽ] ഉണ്ടായിരിക്കും.",
'rev-delundel'                => 'പ്രദർശിപ്പിക്കുക/മറയ്ക്കുക',
'rev-showdeleted'             => 'പ്രദർശിപ്പിക്കുക',
'revisiondelete'              => 'പതിപ്പുകൾ ഒഴിവാക്കുകയോ/പുനഃസ്ഥാപിക്കുകയോ ചെയ്യുക',
'revdelete-nooldid-title'     => 'അസാധുവായ ലക്ഷ്യ നാൾപ്പതിപ്പ്',
'revdelete-nooldid-text'      => 'ഈ പ്രവൃത്തി ചെയ്യുവാനാവശ്യമായ ഉദ്ദിഷ്ട പതിപ്പ്/പതിപ്പുകൾ താങ്കൾ തിരഞ്ഞെടുത്തിട്ടില്ല അല്ലെങ്കിൽ ഉദ്ദിഷ്ട പതിപ്പ് നിലവിലില്ല അതുമല്ലെങ്കിൽ താങ്കൾ നിലവിലുള്ള പതിപ്പ് മറയ്ക്കുവാൻ ശ്രമിക്കുന്നു.',
'revdelete-nologtype-title'   => 'പ്രവർത്തനരേഖയുടെ തരം നൽകിയിട്ടില്ല',
'revdelete-nologtype-text'    => 'ഈ പ്രവൃത്തി ചെയ്യുവാൻ പ്രവർത്തനരേഖയുടെ തരം താങ്കൾ വ്യക്തമാക്കിയിട്ടില്ല.',
'revdelete-nologid-title'     => 'തെറ്റായ തിരുത്തൽ പട്ടിക',
'revdelete-nologid-text'      => 'ഈ പ്രവൃത്തി ചെയ്യുവാനായി രേഖയിൽ ലക്ഷ്യം വെയ്ക്കേണ്ട സംഭവം താങ്കൾ വ്യക്തമാക്കിയിട്ടില്ല അല്ലെങ്കിൽ വ്യക്തമാക്കിയത് നിലനിൽക്കുന്നില്ല.',
'revdelete-no-file'           => 'നിർദ്ദേശിച്ച പ്രമാണം നിലവിലില്ല.',
'revdelete-show-file-confirm' => '"<nowiki>$1</nowiki>" പ്രമാണത്തിന്റെ $2 തീയതി $3 -യ്ക്കു നിലനിന്നിരുന്ന മായ്ക്കപ്പെട്ട പതിപ്പു  കാണണം എന്നു താങ്കൾക്ക് ഉറപ്പാണോ?',
'revdelete-show-file-submit'  => 'അതെ',
'revdelete-selected'          => "'''[[:$1]] എന്ന താളിന്റെ {{PLURAL:$2|തിരഞ്ഞെടുത്ത പതിപ്പ്|തിരഞ്ഞെടുത്ത പതിപ്പുകൾ}}:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|തിരഞ്ഞെടുത്ത രേഖയിലുള്ളത്|തിരഞ്ഞെടുത്ത രേഖയിലുള്ളവ}}:'''",
'revdelete-text'              => "'''മായ്ക്കപ്പെട്ട നാൾ‌രൂപങ്ങളും സംഭവങ്ങളും താളിന്റെ നാൾ‌വഴിയിലും രേഖകളിലും ഉണ്ടായിരിക്കും, പക്ഷേ ആ ഉള്ളടക്കം പൊതുജനത്തിനു ലഭ്യമല്ല.'''
താങ്കൾ മായ്ച്ച പതിപ്പുകളും പ്രവർത്തനരേഖകളും താളിന്റെ നാൾ‌വഴിയിലും ലോഗുകളിലും തുടർന്നും ലഭ്യമാകും. പക്ഷെ ആ പതിപ്പുകളുടെ ഉള്ളടക്കം പൊതുജനത്തിനു പ്രാപ്യമല്ല.
{{SITENAME}} സം‌രംഭത്തിലെ മറ്റു കാര്യനിർ‌വാഹകർക്ക് ഈ മറഞ്ഞിരിക്കുന്ന ഉള്ളടക്കം പരിശോധിക്കുവാനും താങ്കൾ മായ്ച്ചതു തിരസ്ക്കരിക്കുവാനും സാധിക്കും.  മറ്റു കൂടുതൽ സം‌രക്ഷണ പരിമിതികൾ സജ്ജീകരിച്ചിട്ടില്ലെങ്കിൽ ഇതേ സമ്പർക്കമുഖം ഉപയോഗിച്ചു തന്നെ അത്തരത്തിൽ പ്രവർത്തിക്കുന്നതിനു അവർക്ക് സാധിക്കും.",
'revdelete-confirm'           => 'ഇതിന്റെ അനന്തരഫലങ്ങളെക്കുറിച്ചറിയാമെന്നും,  [[{{MediaWiki:Policy-url}}|നയങ്ങൾ]] പാലിച്ചാണ് താങ്കളിത് ചെയ്യുന്നതെന്നും താങ്കൾ ദയവായി സ്ഥിരീകരിക്കുക.',
'revdelete-suppress-text'     => "താഴെ പറയുന്ന സാഹചര്യങ്ങളിൽ '''മാത്രമേ''' ഒതുക്കൽ ഉപയോഗിക്കാവൂ:
* അപകീർത്തികരമായ വിവരങ്ങൾ അടങ്ങിയവ
* അനുയോജ്യമല്ലാത്ത വ്യക്തി വിവരങ്ങൾ
*: ''വീട്ടുവിലാസങ്ങൾ, ടെലിഫോൺ നമ്പറുകൾ, സാമൂഹിക സുരക്ഷാ നമ്പരുകൾ, തുടങ്ങിയവ.''",
'revdelete-legend'            => 'ദർശനത്തിനു നിയന്ത്രണങ്ങൾ ഏർപ്പെടുത്തുക',
'revdelete-hide-text'         => 'മാറ്റം വന്ന എഴുത്ത് മറയ്ക്കുക',
'revdelete-hide-image'        => 'പ്രമാണത്തിന്റെ ഉള്ളടക്കം മറയ്ക്കുക',
'revdelete-hide-name'         => 'പ്രവൃത്തിയും ലക്ഷ്യവും മറയ്ക്കുക',
'revdelete-hide-comment'      => 'തിരുത്തലിന്റെ അഭിപ്രായം മറയ്ക്കുക',
'revdelete-hide-user'         => 'തിരുത്തുന്ന ആളുടെ ഉപയോക്തൃനാമം/ഐ.പി. വിലാസം മറയ്ക്കുക',
'revdelete-hide-restricted'   => 'വിവരങ്ങളുടെ നിയന്ത്രണം മറ്റുള്ളവരെ പോലെ കാര്യനിർവാഹകർക്കും ബാധകമാക്കുക',
'revdelete-radio-same'        => '(മാറ്റം വരുത്തരുത്)',
'revdelete-radio-set'         => 'അതെ',
'revdelete-radio-unset'       => 'അല്ല',
'revdelete-suppress'          => 'സിസോപ്പുകളിൽ നിന്നും മറ്റുള്ളവരിൽ നിന്നും ഈ ഡാറ്റാ മറച്ചു വെക്കുക',
'revdelete-unsuppress'        => 'പുനഃസ്ഥാപിച്ച പതിപ്പുകളിലുള്ള നിയന്ത്രണങ്ങൾ ഒഴിവാക്കുക',
'revdelete-log'               => 'മായ്ക്കാനുള്ള കാരണം:',
'revdelete-submit'            => 'തിരഞ്ഞെടുത്ത {{PLURAL:$1|നാൾപ്പതിപ്പിനു|നാൾപ്പതിപ്പുകൾക്ക്}} ബാധകമാക്കുക',
'revdelete-logentry'          => '[[$1]]-ന്റെ പതിപ്പുകൾ പ്രദർശിപ്പിക്കുന്ന വിധം തിരുത്തിയിരിക്കുന്നു',
'logdelete-logentry'          => '[[$1]] സാഹചര്യത്തിന്റെ പ്രദർശനപരത മാറ്റിയിരിക്കുന്നു',
'revdelete-success'           => "'''നാൾപ്പതിപ്പുകളുടെ ദർശനീയത വിജയകരമായി പുതുക്കിയിരിക്കുന്നു.'''",
'revdelete-failure'           => "'''നാൾപ്പതിപ്പിന്റെ ദർശനീയത പുതുക്കാൻ കഴിഞ്ഞില്ല:'''
$1",
'logdelete-success'           => "'''രേഖയുടെ ദൃശ്യത വിജയകരമായി നിശ്ചിതപ്പെടുത്തി.'''",
'logdelete-failure'           => "'''രേഖയുടെ ദൃശ്യത നിശ്ചിതപ്പെടുത്താൻ കഴിഞ്ഞില്ല:'''
$1",
'revdel-restore'              => 'കാണുന്ന രൂപത്തിൽ മാറ്റം വരുത്തുക',
'revdel-restore-deleted'      => 'മായ്ക്കപ്പെട്ട നാൾപ്പതിപ്പുകൾ',
'revdel-restore-visible'      => 'ദൃശ്യമായ നാൾപ്പതിപ്പുകൾ',
'pagehist'                    => 'താളിന്റെ നാൾ‌വഴി',
'deletedhist'                 => 'ഒഴിവാക്കപ്പെട്ട നാൾ‌വഴി',
'revdelete-content'           => 'ഉള്ളടക്കം',
'revdelete-summary'           => 'തിരുത്തലിന്റെ ചുരുക്കം',
'revdelete-uname'             => 'ഉപയോക്തൃനാമം',
'revdelete-restricted'        => 'കാര്യനിർവാഹകർക്ക് പ്രവർത്തന അതിരുകൾ ഏർപ്പെടുത്തിയിരിക്കുന്നു',
'revdelete-unrestricted'      => 'കാര്യനിർവാഹകർക്ക് ഏർപ്പെടുത്തിയ പ്രവർത്തന അതിരുകൾ നീക്കം ചെയ്തിരിക്കുന്നു',
'revdelete-hid'               => '$1 അപ്രത്യക്ഷമാക്കി',
'revdelete-unhid'             => '$1 പ്രത്യക്ഷമാക്കി',
'revdelete-log-message'       => '{{PLURAL:$2|ഒരു നാൾപ്പതിപ്പിന്റെ|$2 നാൾപ്പതിപ്പുകളുടെ}} $1',
'logdelete-log-message'       => '{{PLURAL:$2|സാഹചര്യങ്ങളിൽ|$2 സാഹചര്യങ്ങളിൽ}} $1',
'revdelete-hide-current'      => '$2, $1 തീയതിയിലെ ഇനം മറയ്ക്കുമ്പോൾ പിഴവ് സംഭവിച്ചു: ഇത് ഇപ്പോഴുള്ള നാൾപ്പതിപ്പാണ്.
ഇത് മറയ്ക്കാൻ കഴിയില്ല.',
'revdelete-show-no-access'    => '$2, $1 തീയതി കുറിച്ച ഇനം പ്രദർശിപ്പിക്കുന്നതിൽ പിഴവ്: ഇത് "പരിമിതപ്പെടുത്തിയതെന്ന്" അടയാളപ്പെടുത്തിയിരിക്കുന്നു.
താങ്കൾക്ക് അതിനുള്ള അനുമതിയില്ല.',
'revdelete-modify-no-access'  => '$2, $1 എന്നു സമയമുദ്രയുള്ള ഇനം പുതുക്കുന്നതിൽ പിഴവ്: ഈ ഇനം "ഉപയോഗം പരിമിതപ്പെടുത്തിയത്" എന്നടയാളപ്പെടുത്തിയതാണ്.
താങ്കൾക്കതിനുള്ള അനുമതി ഇല്ല.',
'revdelete-modify-missing'    => 'ഇനം ഐ.ഡി. $1 ഉള്ളതിൽ മാറ്റം വരുത്തുമ്പോൾ പിഴവ് സംഭവിച്ചു: ഇത് ഡേറ്റാബേസിൽ ലഭ്യമല്ല!',
'revdelete-no-change'         => "'''മുന്നറിയിപ്പ്:''' $2 $1 തീയതിയിലുള്ള ഇനം മുമ്പുതന്നെ ദൃശ്യതാ ക്രമീകരണങ്ങൾ ആവശ്യപ്പെട്ടിട്ടുണ്ട്.",
'revdelete-concurrent-change' => '$1 $2 ദിനസമയമുദ്രയുള്ള ഇനം പുതുക്കുമ്പോൾ പിഴവു സംഭവിച്ചിരിക്കുന്നു: താങ്കൾ പുതുക്കാൻ ശ്രമിക്കുമ്പോൾ മറ്റാരോ അതിന്റെ സ്ഥിതി മാറ്റിയതായി കാണുന്നു.
ദയവായി രേഖകൾ പരിശോധിക്കുക.',
'revdelete-only-restricted'   => '$2, $1 തീയതിയിലെ ഇനം മറയ്ക്കുന്നതിൽ പിഴവ്: ഒതുക്കലിനുള്ള മറ്റ് ഐച്ഛികങ്ങളിലൊന്ന് തിരഞ്ഞെടുക്കാതെ ഇനങ്ങൾ കാര്യനിർവാഹകരുടെ ദൃഷ്ടിയിൽ നിന്നും ഒതുക്കാൻ താങ്കൾക്ക് കഴിയില്ല.',
'revdelete-reason-dropdown'   => '*മായ്ക്കാനുള്ള സാധാരണ കാരണങ്ങൾ
**പകർപ്പവകാശ ലംഘനം
**അനുയോജ്യമല്ലാത്ത വ്യക്തി വിവരങ്ങൾ
**അടിസ്ഥാനപരമായി ദോഷകരമാകുന്ന വിവരങ്ങൾ',
'revdelete-otherreason'       => 'മറ്റ്/കൂടുതൽ കാരണം:',
'revdelete-reasonotherlist'   => 'മറ്റ് കാരണം',
'revdelete-edit-reasonlist'   => 'മായ്ക്കലിന്റെ കാരണം തിരുത്തുക',
'revdelete-offender'          => 'നാൾപ്പതിപ്പിന്റെ രചയിതാവ്:',

# Suppression log
'suppressionlog'     => 'ഒതുക്കൽ രേഖ',
'suppressionlogtext' => 'കാര്യനിർവാഹകരിൽ നിന്നും മറയ്ക്കപ്പെട്ടിട്ടുള്ളതും മായ്ക്കുകയും തടയുകയും ചെയ്തതുമായ ഉള്ളടക്കങ്ങളുടെ പട്ടിക നൽകിയിരിക്കുന്നു.
ഇപ്പോൾ കൈകാര്യം ചെയ്യാൻ പറ്റുന്ന നിരോധനങ്ങളും തടയലുകളും കാണാൻ [[Special:IPBlockList|തടയപ്പെട്ട ഐ.പി. വിലാസങ്ങൾ]] കാണുക.',

# Revision move
'moverevlogentry'              => '$1 താളിൽ നിന്ന് $2 താളിലേയ്ക്ക് {{PLURAL:$3|ഒരു നാൾപ്പതിപ്പ്|$3 നാൾപ്പതിപ്പുകൾ}} മാറ്റിയിരിക്കുന്നു',
'revisionmove'                 => '"$1" താളിൽ നിന്നുള്ള നാൾപ്പതിപ്പുകൾ മാറ്റുക',
'revmove-explain'              => 'താഴെ കൊടുത്തിരിക്കുന്ന നാൾപ്പതിപ്പുകൾ $1 എന്ന താളിൽ നിന്നും ലക്ഷ്യമിട്ട താളിലേയ്ക്ക് മാറ്റുന്നതാണ്. ലക്ഷ്യമിട്ട താൾ നിലവിലില്ലെങ്കിൽ അത് സൃഷ്ടിക്കുന്നതാണ്. അല്ലെങ്കിൽ, അല്ലെങ്കിൽ ഈ നാൾപ്പതിപ്പുകൾ താളിന്റെ നാൾവഴിയിൽ ലയിപ്പിക്കുന്നതാണ്.',
'revmove-legend'               => 'ലക്ഷ്യം വെയ്ക്കുന്ന താളും സംഗ്രഹവും നൽകുക',
'revmove-submit'               => 'തിരഞ്ഞെടുത്ത താളിലേയ്ക്ക് നാൾപ്പതിപ്പുകൾ മാറ്റുക',
'revisionmoveselectedversions' => 'തിരഞ്ഞെടുത്ത നാൾപ്പതിപ്പുകൾ മാറ്റുക',
'revmove-reasonfield'          => 'കാരണം:',
'revmove-titlefield'           => 'ലക്ഷ്യ താൾ:',
'revmove-badparam-title'       => 'മോശം ചരങ്ങൾ',
'revmove-norevisions-title'    => 'അസാധുവായ ലക്ഷ്യ നാൾപ്പതിപ്പ്',
'revmove-nullmove-title'       => 'അസാധുവായ തലക്കെട്ട്',
'revmove-nullmove'             => '<span class="error">സ്രോതസ്സ് താളും ലക്ഷ്യതാളും ഒന്നാണ്. ദയവായി "ബാക്ക്" ബട്ടൺ ഞെക്കിയ ശേഷം, "$1" എന്നതിൽ നിന്നും വ്യത്യസ്തമായ ഒരു താൾ നൽകുക.</span>',

# History merging
'mergehistory'                     => 'താളുകളുടെ നാൾ‌വഴികൾ സം‌യോജിപ്പിക്കുക',
'mergehistory-header'              => 'ഒരു താളിന്റെ പതിപ്പുകളുടെ നാൾ‌വഴി മറ്റൊരു പുതിയ താളിലേക്കു സം‌യോജിപ്പിക്കുവാൻ ഈ താൾ താങ്കളെ സഹായിക്കും.
ഈ മാറ്റം താളിന്റെ പതിപ്പുകളുടെ തുടർച്ച പരിപാലിക്കുന്നതിനു സഹായിക്കും എന്നതു ഉറപ്പു വരുത്തുക.',
'mergehistory-box'                 => 'രണ്ടു താളുകളുടെ പതിപ്പുകൾ സം‌യോജിപ്പിക്കുക:',
'mergehistory-from'                => 'സ്രോതസ്സ് താൾ:',
'mergehistory-into'                => 'ലക്ഷ്യതാൾ:',
'mergehistory-list'                => 'സം‌യോജിപ്പിക്കാവുന്ന തിരുത്തൽ നാൾ‌വഴി',
'mergehistory-merge'               => '[[:$1]]ന്റെ താഴെ കാണിച്ചിരിക്കുന്ന പതിപ്പുകൾ [[:$2]] ലേക്കു സം‌യോജിപ്പിക്കാവുന്നതാണ്‌‍. റേഡിയോ ബട്ടൺ കോളം ഉപയോഗിച്ച് സം‌യോജിപ്പിക്കാനുള്ള പതിപ്പുകളുടെ സമീപത്തുള്ള സമയം തിരഞ്ഞെടുക്കുക. താങ്കൾ തിരഞ്ഞെടുക്കുന്ന സമയത്തോ അതിനു മുൻപോ സൃഷ്ടിക്കപ്പെട്ട പതിപ്പുകൾ തിരഞ്ഞെടുക്കുക. നാവിഗേഷണൽ കണ്ണികൾ ഉപയോഗിക്കുന്നതു ഈ കോളത്തെ പുനഃക്രമീകരിക്കും.',
'mergehistory-go'                  => 'സം‌യോജിപ്പിക്കാവുന്ന തിരുത്തലുകൾ കാട്ടുക',
'mergehistory-submit'              => 'പതിപ്പുകൾ സം‌യോജിപ്പിക്കുക',
'mergehistory-empty'               => 'സം‌യോജിപ്പിക്കാവുന്ന പതിപ്പുകളൊന്നും ഇല്ല.',
'mergehistory-success'             => '[[:$1]]-ന്റെ {{PLURAL:$3|പതിപ്പ്|പതിപ്പുകൾ}} [[:$2]]-ലേക്കു വിജയകരമായി സം‌യോജിപ്പിച്ചിരിക്കുന്നു.',
'mergehistory-fail'                => 'താളുകളുടെ നാൾ‌വഴി സം‌യോജനം നടത്താൻ സാദ്ധ്യമല്ല. താളുകളും സമയവിവരങ്ങളും ഒന്നു കൂടി പരിശോധിക്കുക.',
'mergehistory-no-source'           => 'സ്രോതസ്സ് താളായ $1 നിലവിലില്ല.',
'mergehistory-no-destination'      => 'ലക്ഷ്യ താളായ $1 നിലവിലില്ല.',
'mergehistory-invalid-source'      => 'സ്രോതസ്സ് താളിന് നിർബന്ധമായും സാധുവായ ഒരു തലക്കെട്ടുണ്ടായിരിക്കണം.',
'mergehistory-invalid-destination' => 'ലക്ഷ്യമായി നൽകുന്ന താളിന് നിർബന്ധമായും സാധുവായ തലക്കെട്ടുണ്ടായിരിക്കണം.',
'mergehistory-autocomment'         => '[[:$1]]നെ [[:$2]]ലേക്കു സം‌യോജിപ്പിച്ചിരിക്കുന്നു',
'mergehistory-comment'             => '[[:$1]]നെ [[:$2]]ലേക്കു സം‌യോജിപ്പിച്ചിരിക്കുന്നു: $3',
'mergehistory-same-destination'    => 'സ്രോതസ്സ് - ലക്ഷ്യ താളുകൾക്ക് ഒരേ പേര്‌ ഉണ്ടാകാൻ പാടില്ല',
'mergehistory-reason'              => 'കാരണം:',

# Merge log
'mergelog'           => 'താളുകൾ സം‌യോജിപ്പിച്ചതിന്റെ രേഖകൾ',
'pagemerge-logentry' => '[[$1]] എന്ന താൾ [[$2]] എന്ന താളിലേയ്ക്ക് സംയോജിപ്പിച്ച് കൂട്ടിച്ചേർത്തു ($3 വരെയുള്ള പതിപ്പുകൾ)',
'revertmerge'        => 'വിയോജിപ്പിക്കുക',
'mergelogpagetext'   => 'രണ്ടു താളുകളുടെ നാൾ‌വഴികൾ തമ്മിൽ സം‌യോജിപ്പിച്ചതിന്റെ പ്രവർത്തനരേഖകളുടെ ഏറ്റവും പുതിയ പട്ടിക താഴെ കാണാം.',

# Diffs
'history-title'            => '"$1" എന്ന താളിന്റെ നാൾ‌വഴി',
'difference'               => '(തിരഞ്ഞെടുത്ത പതിപ്പുകൾ തമ്മിലുള്ള വ്യത്യാസം)',
'lineno'                   => 'വരി $1:',
'compareselectedversions'  => 'തിരഞ്ഞെടുത്ത പതിപ്പുകൾ തമ്മിലുള്ള വ്യത്യാസം കാണുക',
'showhideselectedversions' => 'തിരഞ്ഞെടുത്ത മാറ്റങ്ങൾ പ്രദർശിപ്പിക്കുക/മറയ്ക്കുക',
'editundo'                 => 'മാറ്റം തിരസ്ക്കരിക്കുക',
'diff-multi'               => '(ഇടക്കുള്ള {{PLURAL:$1|ഒരു പതിപ്പിലെ മാറ്റം|$1 പതിപ്പുകളിലെ മാറ്റങ്ങൾ}} ഇവിടെ കാണിക്കുന്നില്ല.)',

# Search results
'searchresults'                    => 'തിരച്ചിലിന്റെ ഫലം',
'searchresults-title'              => '"$1" എന്നു തിരഞ്ഞതിനു ലഭ്യമായ ഫലങ്ങൾ',
'searchresulttext'                 => '{{SITENAME}} സംരംഭത്തിൽ വിവരങ്ങൾ എങ്ങനെ അന്വേഷിച്ചു കണ്ടെത്താമെന്നറിയാൻ, [[{{MediaWiki:Helppage}}|{{int:help}}]] എന്ന താൾ കാണുക.',
'searchsubtitle'                   => 'താങ്കൾ അന്വേഷിച്ച വാക്ക് \'\'\'[[:$1]]\'\'\' ആണ്‌. ([[Special:Prefixindex/$1|"$1" എന്ന വാക്കിൽ തുടങ്ങുന്ന എല്ലാ താളുകളും]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1"എന്ന വാക്കിലേക്ക് കണ്ണി ചേർത്തിരിക്കുന്ന എല്ലാ താളുകളും]])',
'searchsubtitleinvalid'            => "താങ്കൾ തിരഞ്ഞത് '''$1'''",
'toomanymatches'                   => 'യോജിച്ച ഫലങ്ങൾ വളരെയധികം കിട്ടിയിരിക്കുന്നു; ദയവായി വേറൊരു അന്വേഷണ വാക്ക് ഉപയോഗിച്ച് തിരയുക.',
'titlematches'                     => 'താളിന്റെ തലക്കെട്ടുമായി യോജിക്കുന്ന ഫലങ്ങൾ',
'notitlematches'                   => 'ഒരു താളിന്റെയും തലക്കെട്ടുമായി യോജിക്കുന്നില്ല',
'textmatches'                      => 'താങ്കൾ തിരഞ്ഞ വാക്കുകൾ ഉള്ള താളുകൾ',
'notextmatches'                    => 'താളുകളുടെ ഉള്ളടക്കത്തിൽ താങ്കൾ തിരഞ്ഞ വാക്കുമായി യോജിക്കുന്ന ഫലങ്ങൾ ഒന്നും തന്നെയില്ല',
'prevn'                            => 'മുമ്പത്തെ {{PLURAL:$1|$1}}',
'nextn'                            => 'അടുത്ത {{PLURAL:$1|$1}}',
'prevn-title'                      => 'മുൻപത്തെ {{PLURAL:$1|ഒരു ഫലം|$1 ഫലങ്ങൾ}}',
'nextn-title'                      => 'അടുത്ത {{PLURAL:$1|ഒരു ഫലം|$1 ഫലങ്ങൾ}}',
'shown-title'                      => '{{PLURAL:$1|ഒരു ഫലം|$1 ഫലങ്ങൾ}} വീതം താളിൽ കാണിക്കുക',
'viewprevnext'                     => '($1 {{int:pipe-separator}} $2 {{int:pipe-separator}} $3 മാറ്റങ്ങൾ കാണുക)',
'searchmenu-legend'                => 'തിരച്ചിൽ ഉപാധികൾ',
'searchmenu-exists'                => "'''\"[[:\$1]]\" എന്ന പേരിൽ ഒരു താൾ ഈ വിക്കിയിൽ നിലവിലുണ്ട്'''",
'searchmenu-new'                   => "'''ഈ വിക്കിയിൽ \"[[:\$1]]\" താൾ നിർമ്മിക്കുക!'''",
'searchhelp-url'                   => 'Help:ഉള്ളടക്കം',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|ഈ പൂർ‌വ്വപദങ്ങളുള്ള താളുകൾ ബ്രൗസ് ചെയ്യുക]]',
'searchprofile-articles'           => 'ലേഖനങ്ങളിൽ',
'searchprofile-project'            => 'സഹായം, പദ്ധതി താളുകളിൽ',
'searchprofile-images'             => 'പ്രമാണങ്ങളിൽ',
'searchprofile-everything'         => 'എല്ലാം',
'searchprofile-advanced'           => 'വിപുലമായ വിധം',
'searchprofile-articles-tooltip'   => '$1 മേഖലയിൽ തിരയുക',
'searchprofile-project-tooltip'    => '$1 മേഖലകളിൽ തിരയുക',
'searchprofile-images-tooltip'     => 'പ്രമാണങ്ങൾക്ക് വേണ്ടി തിരയുക',
'searchprofile-everything-tooltip' => 'എല്ലാ ഉള്ളടക്കവും തിരയുക (സംവാദത്താളുകൾ ഉൾപ്പെടെ)',
'searchprofile-advanced-tooltip'   => 'തിരഞ്ഞെടുത്ത നാമമേഖലകളിൽ തിരച്ചിൽ നടത്തുവാൻ',
'search-result-size'               => '$1 ({{PLURAL:$2|ഒരു വാക്ക്|$2 വാക്കുകൾ}})',
'search-result-category-size'      => '{{PLURAL:$1|ഒരു അംഗം|$1 അംഗങ്ങൾ}} ({{PLURAL:$2|ഒരു ഉപവർഗ്ഗം|$2 ഉപവർഗ്ഗങ്ങൾ}}, {{PLURAL:$3|ഒരു പ്രമാണം|$3 പ്രമാണങ്ങൾ}})',
'search-result-score'              => 'സാംഗത്യം: $1%',
'search-redirect'                  => '(തിരിച്ചുവിടൽ താൾ $1)',
'search-section'                   => '(വിഭാഗം $1)',
'search-suggest'                   => '$1 എന്നാണോ താങ്കൾ ഉദ്ദേശിച്ചത്',
'search-interwiki-caption'         => 'സഹോദര സംരംഭങ്ങൾ',
'search-interwiki-default'         => '$1 ഫലങ്ങൾ:',
'search-interwiki-more'            => '(കൂടുതൽ)',
'search-mwsuggest-enabled'         => 'നിർദ്ദേശങ്ങൾ വേണം',
'search-mwsuggest-disabled'        => 'നിർദ്ദേശങ്ങൾ വേണ്ട',
'search-relatedarticle'            => 'ബന്ധപ്പെട്ടവ',
'mwsuggest-disable'                => 'അജാക്സ് നിർദ്ദേശങ്ങൾ വേണ്ട',
'searcheverything-enable'          => 'എല്ലാ നാമമേഖലകളും തിരയുക',
'searchrelated'                    => 'ബന്ധപ്പെട്ടവ',
'searchall'                        => 'എല്ലാം',
'showingresults'                   => "ഇതിനു താഴെ #'''$2'''-ൽ തുടങ്ങുന്ന {{PLURAL:$1|'''ഒരു''' ഫലം|'''$1''' ഫലങ്ങൾ}} പ്രദർശിപ്പിക്കുന്നു.",
'showingresultsnum'                => "താഴെ #'''$2''' കൊണ്ടു തുടങ്ങുന്ന {{PLURAL:$3|'''ഒരു''' ഫലം|'''$3''' ഫലങ്ങൾ}} കാണിച്ചിരിക്കുന്നു.",
'showingresultsheader'             => "'''$4''' എന്ന പദത്തിനു ലഭിച്ച {{PLURAL:$5|ആകെ '''$3''' എണ്ണത്തിൽ '''$1''' ഫലം |ആകെ '''$3''' എണ്ണത്തിൽ '''$1 - $2''' ഫലങ്ങൾ}}",
'nonefound'                        => "'''ശ്രദ്ധിക്കുക''': ചില നാമമേഖലകൾ മാത്രമേ സ്വതവേ തിരയാറുള്ളൂ. എല്ലാ വിവരങ്ങളിലും തിരയാൻ '''തിരയേണ്ട നാമമേഖലകൾ''' ''എല്ലാം'' എന്നതോ ആവശ്യമായ നാമമേഖലമാത്രം തിരയുവാൻ (സംവാദം, ഫലകം, തുടങ്ങിയവ) അതു മാത്രമായോ ടിക്ക് ചെയ്യേണ്ടതാണ്.",
'search-nonefound'                 => 'താങ്കളുടെ തിരച്ചിലിനനുയോജ്യമായ ഫലങ്ങൾ ഒന്നും ലഭ്യമല്ല.',
'powersearch'                      => 'തിരയൂ',
'powersearch-legend'               => 'വികസിതമായ തിരച്ചിൽ',
'powersearch-ns'                   => 'തിരയേണ്ട നാമമേഖലകൾ',
'powersearch-redir'                => 'തിരിച്ചുവിടലുകൾ കാണിക്കുക',
'powersearch-field'                => 'ഇതിനു വേണ്ടി തിരയുക',
'powersearch-togglelabel'          => 'അടയാളപ്പെടുത്തുക:',
'powersearch-toggleall'            => 'എല്ലാം',
'powersearch-togglenone'           => 'ഒന്നുംവേണ്ട',
'search-external'                  => 'ബാഹ്യ അന്വേഷണം',
'searchdisabled'                   => '{{SITENAME}} സം‌രംഭത്തിൽ തിരച്ചിൽ ദുർബലപ്പെടുത്തിയിരിക്കുന്നു. താങ്കൾക്ക് ഗൂഗിൾ ഉപയോഗിച്ച് തത്കാലം തിരച്ചിൽ നടത്താവുന്നതാണ്‌. പക്ഷെ ഗൂഗിളിൽ {{SITENAME}} സം‌രംഭത്തിന്റെ സൂചിക കാലഹരണപ്പെട്ടതായിരിക്കാൻ സാദ്ധ്യതയുണ്ട്.',

# Quickbar
'qbsettings'               => 'ദ്രുത സൗകര്യം',
'qbsettings-none'          => 'ഒന്നുമില്ല',
'qbsettings-fixedleft'     => 'സ്ഥിരമായ ഇടത്',
'qbsettings-fixedright'    => 'സ്ഥിരമായ വലത്',
'qbsettings-floatingleft'  => 'ഇടത്തേയ്ക്ക് ഒഴുകി നിൽക്കുക',
'qbsettings-floatingright' => 'വലത്തേയ്ക്ക് ഒഴുകി നിൽക്കുക',

# Preferences page
'preferences'                   => 'ക്രമീകരണങ്ങൾ',
'mypreferences'                 => 'എന്റെ ക്രമീകരണങ്ങൾ',
'prefs-edits'                   => 'ആകെ തിരുത്തലുകൾ:',
'prefsnologin'                  => 'ലോഗിൻ ചെയ്തിട്ടില്ല',
'prefsnologintext'              => 'ഉപയോക്തൃക്രമീകരണങ്ങൾ മാറ്റാൻ താങ്കൾ <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} ലോഗിൻ]</span> ചെയ്തിരിക്കണം.',
'changepassword'                => 'രഹസ്യവാക്ക് മാറ്റുക',
'prefs-skin'                    => 'രൂപം',
'skin-preview'                  => 'പ്രിവ്യൂ',
'prefs-math'                    => 'സമവാക്യം',
'datedefault'                   => 'ക്രമീകരണങ്ങൾ വേണ്ട',
'prefs-datetime'                => 'ദിവസവും സമയവും',
'prefs-personal'                => 'അഹം',
'prefs-rc'                      => 'പുതിയ മാറ്റങ്ങൾ',
'prefs-watchlist'               => 'ശ്രദ്ധിക്കുന്നവ',
'prefs-watchlist-days'          => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിൽ പ്രദർശിപ്പിക്കേണ്ട പരമാവധി ദിവസങ്ങൾ:',
'prefs-watchlist-days-max'      => '(പരമാവധി 7 ദിവസം)',
'prefs-watchlist-edits'         => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയുടെ വികസിത രൂപത്തിൽ പ്രദർശിപ്പിക്കേണ്ട പരമാവധി മാറ്റങ്ങൾ:',
'prefs-watchlist-edits-max'     => '(പരമാവധി 1000 തിരുത്തലുകൾ)',
'prefs-watchlist-token'         => 'ശ്രദ്ധിക്കുന്നവയുടെ പട്ടികയ്ക്കുള്ള അടയാളപദം:',
'prefs-misc'                    => 'പലവക',
'prefs-resetpass'               => 'രഹസ്യവാക്ക് മാറ്റുക',
'prefs-email'                   => 'ഇമെയിൽ ക്രമീകരണങ്ങൾ',
'prefs-rendering'               => 'ദൃശ്യരൂപം',
'saveprefs'                     => 'സേവ് ചെയ്യുക',
'resetprefs'                    => 'സേവ് ചെയ്തിട്ടില്ലാത്ത മാറ്റങ്ങൾ പുനഃക്രമീകരിക്കുക',
'restoreprefs'                  => 'സ്വതവേയുള്ള ക്രമീകരണങ്ങൾ പുനഃസ്ഥാപിക്കുക',
'prefs-editing'                 => 'തിരുത്തൽ',
'prefs-edit-boxsize'            => 'തിരുത്തൽ ജാലകത്തിന്റെ വലിപ്പം',
'rows'                          => 'വരി:',
'columns'                       => 'നിര:',
'searchresultshead'             => 'തിരയൂ',
'resultsperpage'                => 'ഒരു താളിലുള്ള ശരാശരി സന്ദർശനം:',
'contextlines'                  => 'ഓരോ സന്ദർശനത്തിലും ചേർക്കപ്പെട്ട വരികൾ:',
'contextchars'                  => 'ഓരോ വരിയുടേയും പ്രസക്തി:',
'stub-threshold'                => '<a href="#" class="stub">അപൂർണ്ണമായ കണ്ണിയെന്നു</a> സ്ഥാപിക്കാനുള്ള ത്വരകം (ബൈറ്റുകൾ):',
'recentchangesdays'             => 'പുതിയ മാറ്റങ്ങളിൽ കാണിക്കേണ്ട ദിവസങ്ങളുടെ എണ്ണം:',
'recentchangesdays-max'         => 'പരമാവധി {{PLURAL:$1|ഒരു ദിവസം|$1 ദിവസങ്ങൾ}}',
'recentchangescount'            => 'സ്വതവേ പ്രദർശിപ്പിക്കേണ്ട തിരുത്തലുകളുടെ എണ്ണം:',
'prefs-help-recentchangescount' => 'ഇത് പുതിയമാറ്റങ്ങൾ, താളിന്റെ നാൾ‌വഴികൾ, രേഖകൾ എന്നിവയെ ഉൾക്കൊള്ളുന്നു.',
'prefs-help-watchlist-token'    => 'ഈ പെട്ടിയിൽ ഒരു രഹസ്യവാക്ക് ഉപയോഗിച്ചാൽ താങ്കൾ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയ്ക്കുള്ള ആർ.എസ്.എസ്. ഫീഡ് ഉണ്ടാക്കുന്നതാണ്.
ഈ രഹസ്യവാക്ക് അറിയാവുന്ന ആർക്കും താങ്കൾ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക വായിക്കാവുന്നതാണ്. അതുകൊണ്ട് സുരക്ഷിതമായ ഒന്നു തിരഞ്ഞെടുക്കുക.
ഇവിടെ താങ്കൾക്കുപയോഗിക്കാവുന്ന ക്രമരഹിതമായി സൃഷ്ടിച്ച ഒരെണ്ണം കൊടുത്തിരിക്കുന്നു: $1',
'savedprefs'                    => 'താങ്കളുടെ ക്രമീകരണങ്ങൾ കാത്തുസൂക്ഷിച്ചിരിക്കുന്നു.',
'timezonelegend'                => 'സമയ മേഖല:',
'localtime'                     => 'പ്രാദേശിക സമയം:',
'timezoneuseserverdefault'      => 'സെർ‌വറിൽ സ്വതവേയുള്ളത് ഉപയോഗിക്കുക',
'timezoneuseoffset'             => 'മറ്റുള്ളത് (എന്താണെന്നു നൽകുക)',
'timezoneoffset'                => 'വ്യത്യാസം¹:',
'servertime'                    => 'സെർവർ സമയം:',
'guesstimezone'                 => 'സമയവ്യത്യാസം ബ്രൗസറിൽ നിന്നും ശേഖരിക്കൂ',
'timezoneregion-africa'         => 'ആഫ്രിക്ക',
'timezoneregion-america'        => 'അമേരിക്ക',
'timezoneregion-antarctica'     => 'അന്റാർട്ടിക്ക',
'timezoneregion-arctic'         => 'ആർട്ടിക്',
'timezoneregion-asia'           => 'ഏഷ്യ',
'timezoneregion-atlantic'       => 'അറ്റ്ലാന്റിക് സമുദ്രം',
'timezoneregion-australia'      => 'ഓസ്ട്രേലിയ',
'timezoneregion-europe'         => 'യൂറോപ്പ്',
'timezoneregion-indian'         => 'ഇന്ത്യൻ മഹാസമുദ്രം',
'timezoneregion-pacific'        => 'ശാന്തസമുദ്രം',
'allowemail'                    => 'എനിക്ക് എഴുത്തയക്കാൻ മറ്റുള്ളവരെ അനുവദിക്കുക',
'prefs-searchoptions'           => 'തിരച്ചിൽ ക്രമീകരണങ്ങൾ',
'prefs-namespaces'              => 'നാമമേഖലകൾ',
'defaultns'                     => 'അല്ലെങ്കിൽ ഈ നാമമേഖലകളിൽ തിരയുക:',
'default'                       => 'സ്വതവെ',
'prefs-files'                   => 'പ്രമാണങ്ങൾ',
'prefs-custom-css'              => 'സ്വന്തം സി.എസ്.എസ്.',
'prefs-custom-js'               => 'സ്വന്തം ജെ.എസ്.',
'prefs-common-css-js'           => 'എല്ലാ ദൃശ്യരൂപങ്ങൾക്കുമായി പങ്ക് വെയ്ക്കപ്പെട്ട സി.എസ്.എസ്./ജെ.എസ്.:',
'prefs-reset-intro'             => 'സൈറ്റിൽ സ്വതവേയുണ്ടാവേണ്ട ക്രമീകരണങ്ങൾ പുനഃക്രമീകരിക്കാൻ താങ്കൾക്ക് ഈ താൾ ഉപയോഗിക്കാവുന്നതാണ്.
ഇത് തിരിച്ചു ചെയ്യാൻ സാദ്ധ്യമല്ല.',
'prefs-emailconfirm-label'      => 'ഇമെയിൽ സ്ഥിരീകരണം:',
'prefs-textboxsize'             => 'തിരുത്താനുള്ള ജാലകത്തിന്റെ വലിപ്പം',
'youremail'                     => 'ഇമെയിൽ:',
'username'                      => 'ഉപയോക്തൃനാമം:',
'uid'                           => 'ഉപയോക്തൃഐ.ഡി:',
'prefs-memberingroups'          => 'അംഗത്വമുള്ള {{PLURAL:$1|സംഘം|സംഘങ്ങൾ}}:',
'prefs-registration'            => 'അംഗത്വം എടുത്തത്:',
'yourrealname'                  => 'യഥാർത്ഥ പേര്‌:',
'yourlanguage'                  => 'ഭാഷ:',
'yourvariant'                   => 'വ്യത്യാസമാനം',
'yournick'                      => 'ഒപ്പ്:',
'prefs-help-signature'          => 'സംവാദം താളിലെ കുറിപ്പുകളിൽ "<nowiki>~~~~</nowiki>" ഉപയോഗിച്ച് ഒപ്പിടേണ്ടതാണ്, അത് താങ്കളുടെ സമയമുദ്രയോടുകൂടിയ ഒപ്പായി  സ്വയം  മാറിക്കൊള്ളും.',
'badsig'                        => 'അനുവദനീയമല്ലാത്ത രൂപത്തിലുള്ള ഒപ്പ്. HTML ടാഗുകൾ പരിശോധിക്കുക.',
'badsiglength'                  => 'താങ്കളുടെ ഒപ്പിനു നീളം കൂടുതലാണ്‌. 
അതിലെ {{PLURAL:$1|അക്ഷരത്തിന്റെ|അക്ഷരങ്ങങ്ങളുടെ}} എണ്ണം $1 ൽ താഴെയായിരിക്കണം.',
'yourgender'                    => 'ആൺ/പെൺ:',
'gender-unknown'                => 'വ്യക്തമാക്കിയിട്ടില്ല',
'gender-male'                   => 'പുരുഷൻ',
'gender-female'                 => 'സ്ത്രീ',
'prefs-help-gender'             => 'നിർബന്ധമില്ല: സോഫ്റ്റ്‌വെയർ ഉപയോഗിച്ച് സ്ത്രീകളേയും പുരുഷന്മാരേയും ശരിയായി സംബോധന ചെയ്യാൻ ഉപയോഗിക്കുന്നു.
ഈ വിവരം പരസ്യമായി ലഭ്യമായിരിക്കുന്നതാണ്‌.',
'email'                         => 'ഇമെയിൽ',
'prefs-help-realname'           => 'താങ്കളുടെ യഥാർത്ഥ പേര്‌ നൽകണമെന്നു നിർബന്ധമില്ല. എങ്കിലും അങ്ങനെ ചെയ്താൽ താങ്കളുടെ സംഭാവനകൾ ആ പേരിൽ അംഗീകരിക്കപ്പെടും.',
'prefs-help-email'              => 'ഇമെയിൽ വിലാസം നൽകണമെന്ന് നിർബന്ധമില്ല, പക്ഷേ താങ്കൾ രഹസ്യവാക്ക് മറന്നാൽ പുതിയത് അയച്ചു തരാൻ ഇതുകൊണ്ട് സാധിക്കുന്നതാണ്‌.
താങ്കൾക്കായുള്ള താളിൽ നിന്നോ, താങ്കൾക്കുള്ള സന്ദേശങ്ങളുടെ താളിൽ നിന്നോ മറ്റുപയോക്താക്കൾക്ക് താങ്കളുടെ വ്യക്തിത്വം മനസ്സിലാക്കാതെ തന്നെ താങ്കൾക്ക് സന്ദേശങ്ങളയയ്ക്കാനും ഈ സം‌വിധാനം അവസരം നൽകുന്നു.',
'prefs-help-email-required'     => 'ഇമെയിൽ വിലാസം ആവശ്യമാണ്‌.',
'prefs-info'                    => 'അടിസ്ഥാന വിവരങ്ങൾ',
'prefs-i18n'                    => 'ആഗോളീകരണം',
'prefs-signature'               => 'ഒപ്പ്',
'prefs-dateformat'              => 'ദിന ലേഖന രീതി',
'prefs-timeoffset'              => 'സമയ വ്യത്യാസം',
'prefs-advancedediting'         => 'വിപുലമായ ഉപാധികൾ',
'prefs-advancedrc'              => 'വിപുലമായ ഉപാധികൾ',
'prefs-advancedrendering'       => 'വിപുലമായ ഉപാധികൾ',
'prefs-advancedsearchoptions'   => 'വിപുലമായ ഉപാധികൾ',
'prefs-advancedwatchlist'       => 'വിപുലമായ ഉപാധികൾ',
'prefs-display'                 => 'പ്രദർശന ഐച്ഛികങ്ങൾ',
'prefs-diffs'                   => 'വ്യത്യാസങ്ങൾ',

# User rights
'userrights'                   => 'ഉപയോക്തൃ അവകാശ പരിപാലനം',
'userrights-lookup-user'       => 'ഉപയോക്തൃസംഘങ്ങളെ പരിപാലിക്കുക',
'userrights-user-editname'     => 'ഒരു ഉപയോക്തൃനാമം ടൈപ്പു ചെയ്യുക:',
'editusergroup'                => 'ഉപയോക്തൃസംഘങ്ങൾ തിരുത്തുക',
'editinguser'                  => "'''[[User:$1|$1]]''' എന്ന ഉപയോക്താവിന്റെ ഉപയോക്തൃ അവകാശങ്ങൾ തിരുത്തുന്നു ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'ഉപയോക്തൃസമൂഹത്തിലെ അംഗത്വം തിരുത്തുക',
'saveusergroups'               => 'ഉപയോക്തൃസംഘങ്ങൾ സേവ് ചെയ്യുക',
'userrights-groupsmember'      => 'അംഗത്വമുള്ളത്:',
'userrights-groupsmember-auto' => 'അന്തർലീനമായ അംഗത്വം:',
'userrights-groups-help'       => 'ഈ ഉപയോക്താവ് ഉൾപ്പെട്ടിട്ടുള്ള സംഘങ്ങൾ താങ്കൾക്ക് മാറ്റാവുന്നതാണ്:
*ഉപയോക്താവ് ആ സംഘത്തിലുണ്ടെന്ന് ശരിയിട്ട ചതുരം അർത്ഥമാക്കുന്നു.
*ഉപയോക്താവ് ആ സംഘത്തിലില്ലെന്ന് ശരിയിടാത്ത ചതുരം അർത്ഥമാക്കുന്നു.
*ഒരു * ഒരിക്കൽ സംഘം കൂട്ടിച്ചേർത്താൻ പിന്നീട് അത് നീക്കാൻ താങ്കൾക്ക് കഴിയില്ലന്നോ, അല്ലെങ്കിൽ തിരിച്ചോ അർത്ഥമാക്കുന്നു.',
'userrights-reason'            => 'കാരണം:',
'userrights-no-interwiki'      => 'മറ്റ് വിക്കികളിലെ ഉപയോക്തൃ അവകാശങ്ങൾ തിരുത്തുവാൻ താങ്കൾക്ക് അനുമതിയില്ല.',
'userrights-nodatabase'        => '$1 എന്ന ഡാറ്റാബേസ് നിലവിലില്ല അല്ലെങ്കിൽ പ്രാദേശികമല്ല.',
'userrights-nologin'           => 'ഉപയോക്താക്കൾക്ക് അവകാശങ്ങൾ കൊടുക്കണമെങ്കിൽ താങ്കൾ കാര്യനിർ‌വാഹക അംഗത്വം ഉപയോഗിച്ച് [[Special:UserLogin|പ്രവേശിച്ചിരിക്കണം]].',
'userrights-notallowed'        => 'ഉപയോക്താക്കൾക്ക് അവകാശങ്ങൾ കൊടുക്കാനുള്ള അനുമതി താങ്കളുടെ അംഗത്വത്തിനില്ല.',
'userrights-changeable-col'    => 'താങ്കൾക്ക് മാറ്റാവുന്ന സംഘങ്ങൾ',
'userrights-unchangeable-col'  => 'താങ്കൾക്ക് മാറ്റാനാവാത്ത സംഘങ്ങൾ',

# Groups
'group'               => 'സംഘം:',
'group-user'          => 'ഉപയോക്താക്കൾ',
'group-autoconfirmed' => 'യാന്ത്രികമായി സ്ഥിരീകരിക്കപ്പെട്ട ഉപയോക്താക്കൾ',
'group-bot'           => 'യന്ത്രങ്ങൾ',
'group-sysop'         => 'സിസോപ്പുകൾ',
'group-bureaucrat'    => 'ബ്യൂറോക്രാറ്റുകൾ',
'group-suppress'      => 'മേൽനോട്ടങ്ങൾ',
'group-all'           => '(എല്ലാം)',

'group-user-member'          => 'ഉപയോക്താവ്',
'group-autoconfirmed-member' => 'യാന്ത്രികമായി സ്ഥിരീകരിക്കപ്പെട്ട ഉപയോക്താവ്',
'group-bot-member'           => 'യന്ത്രം',
'group-sysop-member'         => 'സിസോപ്പ്',
'group-bureaucrat-member'    => 'ബ്യൂറോക്രാറ്റ്',
'group-suppress-member'      => 'മേൽനോട്ടം',

'grouppage-user'          => '{{ns:project}}:ഉപയോക്താക്കൾ',
'grouppage-autoconfirmed' => '{{ns:project}}:യാന്ത്രികമായി സ്ഥിരീകരിക്കപ്പെട്ട ഉപയോക്താക്കൾ',
'grouppage-bot'           => '{{ns:project}}:യന്ത്രങ്ങൾ',
'grouppage-sysop'         => '{{ns:project}}:കാര്യനിർ‌വാഹകർ',
'grouppage-bureaucrat'    => '{{ns:project}}:ബ്യൂറോക്രാറ്റ്',
'grouppage-suppress'      => '{{ns:project}}:മേൽനോട്ടം',

# Rights
'right-read'                  => '
താളുകൾ വായിക്കുക',
'right-edit'                  => 'താളുകൾ തിരുത്തുക',
'right-createpage'            => 'താളുകൾ സൃഷ്ടിക്കുക (സംവാദം താളുകൾ അല്ലാത്തവ)',
'right-createtalk'            => 'സംവാദ താളുകൾ സൃഷ്ടിക്കുക',
'right-createaccount'         => 'പുതിയ ഉപയോക്തൃ അംഗത്വങ്ങൾ സൃഷ്ടിക്കുക',
'right-minoredit'             => 'ചെറിയ തിരുത്തലായി രേഖപ്പെടുത്തുക',
'right-move'                  => 'താളുകൾ നീക്കുക',
'right-move-subpages'         => 'താളുകൾ അവയുടെ ഉപതാളുകളോടുകൂടീ നീക്കുക',
'right-move-rootuserpages'    => 'അടിസ്ഥാന ഉപയോക്തൃതാൾ മാറ്റുക',
'right-movefile'              => 'പ്രമാണങ്ങൾ നീക്കുക',
'right-suppressredirect'      => 'താളുകൾ മാറ്റുമ്പോൾ സ്രോതസ്സ് താളിൽ തിരിച്ചുവിടലുകൾ സൃഷ്ടിക്കാതിരിക്കുക',
'right-upload'                => 'പ്രമാണങ്ങൾ അപ്‌ലോഡ് ചെയ്യുക',
'right-reupload'              => 'നിലവിലുള്ള പ്രമാണങ്ങളുടെ മുകളിലേയ്ക്ക് അപ്‌‌ലോഡ് ചെയ്യുക',
'right-reupload-own'          => 'സ്വയം അപ്‌‌ലോഡ് ചെയ്ത പ്രമാണങ്ങൾക്കു മുകളിലേയ്ക്ക് പ്രമാണങ്ങൾ അപ്‌‌ലോഡ് ചെയ്യുക',
'right-reupload-shared'       => 'പങ്ക് വെയ്ക്കപ്പെട്ട മീഡിയ ശേഖരിണിയെ പ്രാദേശികമായി അതിലംഘിക്കുക',
'right-upload_by_url'         => 'യു.ആർ.എല്ലിൽ നിന്നും പ്രമാണങ്ങൾ അപ്‌‌ലോഡ് ചെയ്യുക',
'right-purge'                 => 'സ്ഥിരീകരണം ഒന്നും ഇല്ലാതെ സൈറ്റിന്റെ കാഷെ ഒരു താളിനായി പർജ് ചെയ്യുക',
'right-autoconfirmed'         => 'അർദ്ധസംരക്ഷിത താളുകൾ തിരുത്തുക',
'right-bot'                   => 'യാന്ത്രിക പ്രവൃത്തിയായി കണക്കാക്കപ്പെടുന്നു',
'right-nominornewtalk'        => 'സംവാദം താളുകളിലെ ചെറുതിരുത്തലുകൾ പുതിയ സന്ദേശങ്ങളുണ്ടെന്ന അറിയിപ്പിനു കാരണമാകരുത്',
'right-apihighlimits'         => 'എ.പി.ഐ. ക്വറികളിൽ ഉയർന്ന പരിധി ഉപയോഗിക്കുക',
'right-writeapi'              => 'തിരുത്തുക എ.പി.ഐ.യുടെ ഉപയോഗം',
'right-delete'                => 'താളുകൾ മായ്ക്കുക',
'right-bigdelete'             => 'വലിയ നാൾവഴിയുള്ള താളുകൾ മായ്ക്കുക',
'right-deleterevision'        => 'താളിന്റെ പ്രത്യേക പതിപ്പുകൾ മായ്ക്കുക പുനഃസ്ഥാപിക്കുക',
'right-deletedhistory'        => 'മായ്ക്കപ്പെട്ട വിവരങ്ങൾ ബന്ധപ്പെട്ട എഴുത്തുകൾ ഇല്ലാതെ കാണുക',
'right-deletedtext'           => 'മായ്ക്കപ്പെട്ട എഴുത്തും താളിന്റെ മായ്ക്കപ്പെട്ട പതിപ്പുകൾ തമ്മിലുള്ള വ്യത്യാസവും കാണുക',
'right-browsearchive'         => 'നീക്കം ചെയ്യപ്പെട്ട താളുകളിൽ തിരയുക',
'right-undelete'              => 'താൾ പുനഃസ്ഥാപിക്കുക',
'right-suppressrevision'      => 'കാര്യനിർവാഹകരിൽ നിന്നും മറയ്ക്കപ്പെട്ട നാൾപ്പതിപ്പുകൾ സംശോധനം ചെയ്യുക, പുനഃസ്ഥാപിക്കുക',
'right-suppressionlog'        => 'പരസ്യമല്ലാത്ത രേഖകൾ കാണുക',
'right-block'                 => 'മറ്റുള്ള ഉപയോക്താക്കളെ മാറ്റിയെഴുതുന്നതിൽനിന്നും തടയുക',
'right-blockemail'            => 'ഇമെയിൽ അയക്കുന്നതിൽ നിന്നും ഉപയോക്താവിനെ തടയുക',
'right-hideuser'              => 'ഒരു ഉപയോക്തൃനാമത്തെ തടയുക, പരസ്യമായി കാണപ്പെടുന്നതിൽ നിന്നും മറയ്ക്കുന്നു',
'right-ipblock-exempt'        => 'ഐ.പി. തടയലുകൾ, സ്വതവേയുള്ള തടയലുകൾ, റേഞ്ച് തടയലുകൾ ഒക്കെ ബാധകമല്ലാതിരിക്കുക',
'right-proxyunbannable'       => 'പ്രോക്സികളെ സ്വതവേ തടയുന്നത് ബാധകമല്ലാതിരിക്കുക',
'right-unblockself'           => 'തടയപ്പെട്ടവർ സ്വയം തടയൽ നീക്കുക',
'right-protect'               => 'സംരക്ഷണ മാനത്തിൽ മാറ്റം വരുത്തുക, സംരക്ഷിത താളുകൾ തിരുത്തുക',
'right-editprotected'         => 'സംരക്ഷിത താളുകൾ തിരുത്തുക (നിർഝരിത സംരക്ഷണം അല്ലാത്തത്)',
'right-editinterface'         => 'ഉപയോക്തൃ സമ്പർക്കമുഖത്തിൽ മാറ്റം വരുത്തുക',
'right-editusercssjs'         => 'മറ്റ് ഉപയോക്താക്കളുടെ CSS, JS പ്രമാണങ്ങൾ തിരുത്തുക',
'right-editusercss'           => 'മറ്റ് ഉപയോക്താക്കളുടെ CSS പ്രമാണങ്ങൾ തിരുത്തുക',
'right-edituserjs'            => 'മറ്റ് ഉപയോക്താക്കളുടെ JS പ്രമാണങ്ങൾ തിരുത്തുക',
'right-rollback'              => 'ഒരു പ്രത്യേക താളിൽ അവസാനം തിരുത്തൽ നടത്തിയ ഉപയോക്താവിന്റെ തിരുത്തൽ പെട്ടെന്ന് ഒഴിവാക്കുക',
'right-markbotedits'          => 'മുൻപ്രാപനം നടത്തിയ തിരുത്തലുകൾ യാന്ത്രിക തിരുത്തലുകളായി അടയാളപ്പെടുത്തുക',
'right-noratelimit'           => 'നിലവാരമിടലിന്റെ പരിധികൾ ബാധകമല്ല',
'right-import'                => 'മറ്റുള്ള വിക്കികളിൽ നിന്നും താളുകൾ ഇറക്കുമതി ചെയ്യുക',
'right-importupload'          => 'അപ്‌‌ലോഡ് ചെയ്ത പ്രമാണത്തിൽ നിന്നും താളുകൾ ഇറക്കുമതി ചെയ്യുക',
'right-patrol'                => 'മറ്റുള്ളവരുടെ തിരുത്തലുകൾ റോന്തുചുറ്റിയതായി അടയാളപ്പെടുത്തുക',
'right-autopatrol'            => 'സ്വന്തം തിരുത്തലുകൾ റോന്തു ചുറ്റിയതായി അടയാളപ്പെടുത്തുക',
'right-patrolmarks'           => 'പുതിയ മാറ്റങ്ങളിലെ റോന്തുചുറ്റൽ രേഖകൾ പരിശോധിക്കുക',
'right-unwatchedpages'        => 'ശ്രദ്ധിക്കാത്ത താളുകളുടെ പട്ടിക കാണുക',
'right-trackback'             => 'ഒരു പിന്തുടരൽ സമർപ്പിക്കുക',
'right-mergehistory'          => 'താളുകളുടെ നാൾവഴികൾ ലയിപ്പിക്കുക',
'right-userrights'            => 'എല്ലാ ഉപയോക്തൃ അവകാശങ്ങളും തിരുത്തുക',
'right-userrights-interwiki'  => 'മറ്റുള്ള വിക്കികളിൽ ഉപയോക്താക്കളുടെ അവകാശങ്ങൾ തിരുത്തുക',
'right-siteadmin'             => 'ഡേറ്റാബേസ് തുറക്കുക, പൂട്ടുക',
'right-reset-passwords'       => 'മറ്റ് ഉപയോക്താക്കളുടെ രഹസ്യവാക്കുകൾ റീസെറ്റ് ചെയ്യുക',
'right-override-export-depth' => 'കണ്ണിവത്കരിക്കപ്പെട്ട താളുകളുടെ ആഴം 5 വരെയുള്ള താളുകൾ കയറ്റുമതി ചെയ്യുക',
'right-sendemail'             => 'മറ്റുപയോക്താക്കൾക്ക് ഇമെയിൽ അയയ്ക്കുക',
'right-revisionmove'          => 'നാൾപ്പതിപ്പുകൾ മാറ്റുക',

# User rights log
'rightslog'      => 'ഉപയോക്തൃ അവകാശ രേഖ',
'rightslogtext'  => 'ഉപയോക്തൃ അവകാശങ്ങൾക്കുണ്ടായ മാറ്റങ്ങൾ കാണിക്കുന്ന ഒരു ലോഗാണിത്.',
'rightslogentry' => '$1 എന്ന ഉപയോക്താവിന്റെ സംഘ അംഗത്വം $2 എന്നതിൽ നിന്നു $3 എന്നതിലേക്കു മാറ്റിയിരിക്കുന്നു',
'rightsnone'     => '(ഒന്നുമില്ല)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'ഈ താൾ വായിക്കുക',
'action-edit'                 => 'ഈ താൾ തിരുത്തുക',
'action-createpage'           => 'താളുകൾ നിർമ്മിക്കുക',
'action-createtalk'           => 'സംവാദ താളുകൾ നിർമ്മിക്കുക',
'action-createaccount'        => 'ഈ ഉപയോക്തൃനാമം സൃഷ്ടിക്കുക',
'action-minoredit'            => 'ഈ തിരുത്തൽ ഒരു ചെറിയ തിരുത്തലായി അടയാളപ്പെടുത്തുക',
'action-move'                 => 'ഈ താൾ മാറ്റുക',
'action-move-subpages'        => 'ഈ താളും ഇതിന്റെ ഉപതാളുകളും നീക്കുക',
'action-move-rootuserpages'   => 'ഉപയോക്താവിന്റെ അടിസ്ഥാന താൾ മാറ്റുക',
'action-movefile'             => 'ഈ പ്രമാണം നീക്കുക',
'action-upload'               => 'ഈ പ്രമാണം അപ്‌ലോഡ് ചെയ്യുക',
'action-reupload'             => 'നിലവിലുള്ള പ്രമാണത്തിന്റെ ഉപരിയായി സ്ഥാപിക്കുക',
'action-reupload-shared'      => 'പങ്കു വെയ്ക്കപ്പെട്ടുപയോഗിക്കുന്ന ശേഖരണസ്ഥാനത്തുനിന്നുമുള്ള ഈ പ്രമാണത്തിന്റെ ഉപരിയായി സ്ഥാപിക്കുക',
'action-upload_by_url'        => 'ഈ പ്രമാണം ഒരു യൂ.ആർ.എല്ലിൽ നിന്നും അപ്‌ലോഡ് ചെയ്യുക',
'action-writeapi'             => 'തിരുത്താനുള്ള എ.പി.ഐ. ഉപയോഗിക്കുക',
'action-delete'               => 'ഈ താൾ മായ്ക്കുക',
'action-deleterevision'       => 'ഈ നാൾപ്പതിപ്പ് മായ്ക്കുക',
'action-deletedhistory'       => 'ഈ താളിന്റെ മായ്ക്കപ്പെട്ട ചരിത്രം കാണുക',
'action-browsearchive'        => 'മായ്ക്കപ്പെട്ട താളുകൾ അന്വേഷിക്കുക',
'action-undelete'             => 'ഈ താൾ പുനഃസ്ഥാപിക്കുക',
'action-suppressrevision'     => 'മറച്ചിരിക്കുന്ന ഈ നാൾ‌രൂപം പുനഃപരിശോധിക്കുക അല്ലെങ്കിൽ പുനഃസ്ഥാപിക്കുക',
'action-suppressionlog'       => 'ഈ സ്വകാര്യ രേഖ കാണുക',
'action-block'                => 'ഈ ഉപയോക്താവിനെ തിരുത്തുന്നതിൽ നിന്നും തടയുക',
'action-protect'              => 'ഈ താളിന്റെ സം‌രക്ഷണ മാനത്തിൽ വ്യത്യാസം വരുത്തുക',
'action-import'               => 'ഈ താൾ മറ്റൊരു വിക്കിയീൽ നിന്നും ഇറക്കുമതി ചെയ്യുക',
'action-importupload'         => 'അപ്‌ലോഡ് ചെയ്ത പ്രമാണത്തിൽ ഇന്നും ഈ താൾ ഇറക്കുമതി ചെയ്യുക',
'action-patrol'               => 'മറ്റുള്ളവരുടെ തിരുത്തൽ റോന്തുചുറ്റിയതായി അടയാളപ്പെടുത്തുക',
'action-autopatrol'           => 'താങ്കളുടെ തിരുത്തലിൽ റോന്തുചുറ്റിയതായി അടയാളപ്പെടുത്തി',
'action-unwatchedpages'       => 'ശ്രദ്ധിക്കാത്ത താളുകളുടെ പട്ടിക കാട്ടുക',
'action-trackback'            => 'പിന്തുടരൽ സമർപ്പിക്കുക',
'action-mergehistory'         => 'ഈ താളിന്റെ നാൾ‌വഴി ലയിപ്പിക്കുക',
'action-userrights'           => 'എല്ലാ ഉപയോക്തൃ അവകാശങ്ങളും തിരുത്തുക',
'action-userrights-interwiki' => 'മറ്റു വിക്കികളിൽ നിന്നുള്ള ഉപയോക്താക്കളുടെ ഉപയോക്തൃ അവകാശങ്ങൾ തിരുത്തുക',
'action-siteadmin'            => 'ഡേറ്റാബേസ് തുറക്കുക അല്ലെങ്കിൽ പൂട്ടുക',
'action-revisionmove'         => 'നാൾപ്പതിപ്പുകൾ മാറ്റുക',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|മാറ്റം|മാറ്റങ്ങൾ}}',
'recentchanges'                     => 'സമീപകാല മാറ്റങ്ങൾ',
'recentchanges-legend'              => 'സമീപകാല മാറ്റങ്ങളുടെ ക്രമീകരണം',
'recentchangestext'                 => '{{SITENAME}} സംരംഭത്തിലെ ഏറ്റവും പുതിയ മാറ്റങ്ങൾ ഇവിടെ കാണാം.',
'recentchanges-feed-description'    => 'ഈ ഫീഡ് ഉപയോഗിച്ച് വിക്കിയിലെ പുതിയ മാറ്റങ്ങൾ നിരീക്ഷിക്കുക.',
'recentchanges-label-legend'        => 'സൂചന: $1.',
'recentchanges-legend-newpage'      => '$1 - പുതിയ താൾ',
'recentchanges-label-newpage'       => 'ഈ തിരുത്തൽ മൂലം ഒരു പുതിയ താൾ സൃഷ്ടിക്കപ്പെട്ടിരിക്കുന്നു',
'recentchanges-legend-minor'        => '$1 - ചെറിയ തിരുത്ത്',
'recentchanges-label-minor'         => 'ഇതൊരു ചെറിയ തിരുത്തലാണ്',
'recentchanges-legend-bot'          => '$1 - യന്ത്രം ഉപയോഗിച്ചുള്ള തിരുത്ത്',
'recentchanges-label-bot'           => 'ഒരു യന്ത്രം നടത്തിയ തിരുത്താണിത്',
'recentchanges-legend-unpatrolled'  => '$1 - റോന്തുചുറ്റപ്പെടാത്ത തിരുത്ത്',
'recentchanges-label-unpatrolled'   => 'ഇതുവരെ റോന്തു ചുറ്റപ്പെടാത്ത ഒരു തിരുത്താണിത്',
'rcnote'                            => "കഴിഞ്ഞ {{PLURAL:$2|ദിവസം|'''$2''' ദിവസങ്ങൾക്കുള്ളിൽ}} സംഭവിച്ച, {{PLURAL:$1|'''1''' തിരുത്തൽ|'''$1''' തിരുത്തലുകൾ}} താഴെക്കാണാം. ശേഖരിച്ച സമയം: $4, $5.",
'rcnotefrom'                        => '<b>$2</b> മുതലുള്ള മാറ്റങ്ങൾ (<b>$1</b> എണ്ണം വരെ കാണാം).',
'rclistfrom'                        => '$1 മുതലുള്ള മാറ്റങ്ങൾ പ്രദർശിപ്പിക്കുക',
'rcshowhideminor'                   => 'ചെറുതിരുത്തലുകൾ $1',
'rcshowhidebots'                    => 'യന്ത്രങ്ങളെ $1',
'rcshowhideliu'                     => 'ലോഗിൻ ചെയ്തിട്ടുള്ളവരെ $1',
'rcshowhideanons'                   => 'അജ്ഞാത ഉപയോക്താക്കളെ $1',
'rcshowhidepatr'                    => 'റോന്തു ചുറ്റിയ മാറ്റങ്ങൾ $1',
'rcshowhidemine'                    => 'എന്റെ തിരുത്തലുകൾ $1',
'rclinks'                           => 'കഴിഞ്ഞ $2 ദിവസങ്ങൾക്കുള്ളിലുണ്ടായ $1 മാറ്റങ്ങൾ പ്രദർശിപ്പിക്കുക<br />$3',
'diff'                              => 'മാറ്റം',
'hist'                              => 'നാൾ‌വഴി',
'hide'                              => 'മറയ്ക്കുക',
'show'                              => 'പ്രദർശിപ്പിക്കുക',
'minoreditletter'                   => '(ചെ.)',
'newpageletter'                     => '(പു.)',
'boteditletter'                     => '(യ.)',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|ഒരു ഉപയോക്താവ്|$1 ഉപയോക്താക്കൾ}} ഈ താൾ ശ്രദ്ധിക്കുന്നുണ്ട്]',
'rc_categories'                     => 'വർഗ്ഗങ്ങളുടെ പരിധി ("|" ഉപയോഗിച്ച് പിരിക്കുക)',
'rc_categories_any'                 => 'ഏതും',
'newsectionsummary'                 => '/* $1 */ പുതിയ ഉപവിഭാഗം',
'rc-enhanced-expand'                => 'അധികവിവരങ്ങൾ കാട്ടുക (ജാവാസ്ക്രിപ്റ്റ് സജ്ജമായിരിക്കണം)',
'rc-enhanced-hide'                  => 'അധികവിവരങ്ങൾ മറയ്ക്കുക',

# Recent changes linked
'recentchangeslinked'          => 'അനുബന്ധ മാറ്റങ്ങൾ',
'recentchangeslinked-feed'     => 'അനുബന്ധ മാറ്റങ്ങൾ',
'recentchangeslinked-toolbox'  => 'അനുബന്ധ മാറ്റങ്ങൾ',
'recentchangeslinked-title'    => '$1 എന്ന താളുമായി ബന്ധപ്പെട്ട മാറ്റങ്ങൾ',
'recentchangeslinked-noresult' => 'ഈ താളിലേയ്ക്ക് കണ്ണികളുള്ള മറ്റ് താളുകൾക്ക് ഇവിടെ സൂചിപ്പിക്കപ്പെട്ട സമയത്ത് മാറ്റങ്ങളൊന്നും സം‌ഭവിച്ചിട്ടില്ല.',
'recentchangeslinked-summary'  => "ഒരു പ്രത്യേക താളിൽ നിന്നു കണ്ണി ചേർക്കപ്പെട്ടിട്ടുള്ള താളുകളിൽ അവസാനമായി വരുത്തിയ മാറ്റങ്ങളുടെ പട്ടിക താഴെ പ്രദർശിപ്പിച്ചിരിക്കുന്നു. ഈ പട്ടികയിൽ പെടുന്ന [[Special:Watchlist|താങ്കൾ ശ്രദ്ധിക്കുന്ന താളുകൾ]] '''കടുപ്പിച്ച്''' കാണിച്ചിരിക്കുന്നു.",
'recentchangeslinked-page'     => 'താളിന്റെ പേര്:',
'recentchangeslinked-to'       => 'തന്നിരിക്കുന്ന താളിലെ മാറ്റങ്ങൾക്കു പകരം ബന്ധപ്പെട്ട താളുകളിലെ മാറ്റങ്ങൾ കാണിക്കുക',

# Upload
'upload'                      => 'അപ്‌ലോഡ്‌',
'uploadbtn'                   => 'പ്രമാണം അപ്‌ലോഡ് ചെയ്യുക',
'reuploaddesc'                => 'വീണ്ടും അപ്‌ലോഡ് ചെയ്ത് നോക്കാനായി തിരിച്ചു പോവുക.',
'upload-tryagain'             => 'പുതുക്കിയ പ്രമാണ വിവരണങ്ങൾ സമർപ്പിക്കുക',
'uploadnologin'               => 'ലോഗിൻ ചെയ്തിട്ടില്ല',
'uploadnologintext'           => 'പ്രമാണങ്ങൾ അപ്‌ലോഡ് ചെയ്യാൻ താങ്കൾ [[Special:UserLogin|ലോഗിൻ]] ചെയ്തിരിക്കണം',
'upload_directory_missing'    => 'അപ്‌‌ലോഡ് ഡയറക്ടറി ($1) ലഭ്യമല്ല, അത് സൃഷ്ടിക്കാൻ വെബ്‌‌സെർവറിനു സാധിക്കില്ല.',
'upload_directory_read_only'  => 'വെബ്ബ് സെർ‌വറിനു അപ്‌ലോഡ് ഡയറക്ടറിയിലേക്ക് ($1) എഴുതാൻ കഴിഞ്ഞില്ല.',
'uploaderror'                 => 'അപ്‌ലോഡ് പിഴവ്',
'upload-recreate-warning'     => "'''ശ്രദ്ധിക്കുക: ഇതേ പേരിലുള്ള ഒരു പ്രമാണം മായ്ക്കുകയോ മാറ്റുകയോ ചെയ്തിരിക്കുന്നു.'''

ഈ താളിന്റെ മായ്ക്കൽ രേഖയും മാറ്റൽ രേഖയും സ്ഥിരീകരിക്കുന്നതിനായി നൽകിയിരിക്കുന്നു:",
'uploadtext'                  => "താഴെ കാണുന്ന ഫോം പ്രമാണങ്ങൾ അപ്‌ലോഡ് ചെയ്യുവാൻ വേണ്ടി ഉപയോഗിക്കുക.
നിലവിൽ അപ്‌ലോഡ് ചെയ്തിരിക്കുന്ന പ്രമാണങ്ങൾ കാണുവാൻ [[Special:FileList|അപ്‌ലോഡ് ചെയ്ത പ്രമാണങ്ങളുടെ പട്ടിക]] സന്ദർശിക്കുക. (പുതുക്കിയ) അപ്‌‌ലോഡുകൾ [[Special:Log/upload|അപ്‌ലോഡ് രേഖ]], മായ്ക്കപ്പെട്ടവ [[Special:Log/delete|മായ്ക്കൽ രേഖയിലും]] കാണാവുന്നതാണ്‌.

പ്രമാണം താളിൽ പ്രദർശിപ്പിക്കുവാൻ താഴെ കാണുന്ന ഒരു വഴി സ്വീകരിക്കുക

*'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki>''' പൂർണ്ണരൂപത്തിലുള്ള പ്രമാണം ഉപയോഗിക്കാൻ

* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></tt>''' 200 പിക്സൽ ഉള്ള പെട്ടിയിൽ പകരമുള്ള എഴുത്തടക്കം ഉപയോഗിക്കാൻ 
*'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>''' പ്രമാണം കാട്ടാതെ പ്രമാണത്തെ നേരിട്ടു കണ്ണി ചേർക്കാൻ",
'upload-permitted'            => 'അനുവദനീയമായ പ്രമാണ തരങ്ങൾ: $1.',
'upload-preferred'            => 'പ്രോത്സാഹിപ്പിക്കുന്ന പ്രമാണ തരങ്ങൾ: $1.',
'upload-prohibited'           => 'നിരോധിക്കപ്പെട്ട തരം പ്രമാണങ്ങൾ: $1.',
'uploadlog'                   => 'അപ്‌ലോഡ് പ്രവർത്തനരേഖ',
'uploadlogpage'               => 'അപ്‌ലോഡ് രേഖ',
'uploadlogpagetext'           => 'സമീപകാലത്ത് അപ്‌ലോഡ് ചെയ്ത പ്രമാണങ്ങളുടെ പട്ടിക താഴെ കാണാം.',
'filename'                    => 'പ്രമാണത്തിന്റെ പേര്',
'filedesc'                    => 'സംഗ്രഹം',
'fileuploadsummary'           => 'സംഗ്രഹം:',
'filereuploadsummary'         => 'പ്രമാണത്തിലെ മാറ്റങ്ങൾ:',
'filestatus'                  => 'പകർപ്പവകാശത്തിന്റെ സ്ഥിതി:',
'filesource'                  => 'സ്രോതസ്സ്:',
'uploadedfiles'               => 'അപ്‌ലോഡ് ചെയ്ത പ്രമാണങ്ങൾ',
'ignorewarning'               => 'മുന്നറിയിപ്പ് അവഗണിച്ച് പ്രമാണം സം‌രക്ഷിക്കുക',
'ignorewarnings'              => 'അറിയിപ്പുകൾ അവഗണിക്കുക',
'minlength1'                  => 'പ്രമാണത്തിന്റെ പേരിൽ ഒരക്ഷരമെങ്കിലും ഉണ്ടാവണം.',
'illegalfilename'             => 'പ്രമാണത്തിന്റെ "$1" എന്ന പേരിൽ, താളിന്റെ തലക്കെട്ടിൽ അനുവദനീയമല്ലാത്ത ചിഹ്നങ്ങൾ ഉണ്ട്. ദയവായി പ്രമാണം പുനർനാമകരണം നടത്തി വീണ്ടും അപ്‌ലോഡ് ചെയ്യുവാൻ ശ്രമിക്കുക.',
'badfilename'                 => 'പ്രമാണത്തിന്റെ പേര് "$1" എന്നാക്കി മാറ്റിയിരിക്കുന്നു.',
'filetype-mime-mismatch'      => 'മൈം തരവുമായി പ്രമാണത്തിന്റെ എക്സ്റ്റെൻഷൻ ഒത്തുപോകുന്നില്ല.',
'filetype-badmime'            => '"$1" എന്ന MIME ഇനത്തിലുള്ള പ്രമാണങ്ങൾ അപ്‌ലോഡ് ചെയ്യുന്നത് അനുവദനീയമല്ല.',
'filetype-bad-ie-mime'        => 'ഈ പ്രമാണത്തെ ഇന്റർനെറ്റ് എക്സ്‌‌പ്ലോറർ "$1" ആയി തിരിച്ചറിഞ്ഞിരിക്കുന്നു, ഇത് അപകടകരമായ തരം പ്രമാണമായതിനാൽ അനുവദനീയമല്ല.',
'filetype-unwanted-type'      => "'''\".\$1\"''' ഉപയോഗയോഗ്യമല്ലാത്ത ഒരു പ്രമാണ തരം ആണ്‌. {{PLURAL:\$3|പ്രമാണ തരം|പ്രമാണ തരങ്ങൾ}}  \$2 ആണ് അഭിലഷണീയം.",
'filetype-banned-type'        => "'''\".\$1\"''' അനുവദനീയമല്ലാത്ത ഒരു പ്രമാണ തരം ആണ്‌.
{{PLURAL:\$3|പ്രമാണ തരം|പ്രമാണ തരങ്ങൾ}} \$2 ആണ് അഭിലഷണീയം.",
'filetype-missing'            => 'പ്രമാണത്തിനു എക്സ്റ്റൻഷൻ (ഉദാ: ".jpg") ഇല്ല.',
'empty-file'                  => 'താങ്കൾ സമർപ്പിച്ച പ്രമാണം ശൂന്യമാണ്.',
'file-too-large'              => 'താങ്കൾ സമർപ്പിച്ച പ്രമാണം വളരെ വലുതാണ്.',
'filename-tooshort'           => 'പ്രമാണത്തിന്റെ പേര് വളരെ ചെറുതാണ്.',
'filetype-banned'             => 'ഈ തരത്തിലുള്ള പ്രമാണങ്ങൾ നിരോധിക്കപ്പെട്ടിരിക്കുന്നു.',
'verification-error'          => 'ഈ പ്രമാണം പ്രമാണ പരിശോധന വിജയിച്ചിട്ടില്ല.',
'hookaborted'                 => 'താങ്കൾ വരുത്താൻ ശ്രമിച്ച മാറ്റം ഒരു അനുബന്ധത്തിന്റെ കൊളുത്തിനാൽ റദ്ദാക്കപ്പെട്ടു.',
'illegal-filename'            => 'പ്രമാണത്തിന്റെ പേര് അനുവദനീയമല്ല.',
'overwrite'                   => 'നിലവിലുള്ള പ്രമാണത്തിന്റെ മുകളിൽ സ്ഥാപിക്കൽ അനുവദിച്ചിട്ടില്ല.',
'unknown-error'               => 'അപരിചിതമായ പിഴവ് സംഭവിച്ചിരിക്കുന്നു.',
'tmp-create-error'            => 'താത്കാലിക പ്രമാണം സൃഷ്ടിക്കാൻ കഴിയില്ല.',
'tmp-write-error'             => 'താത്കാലിക പ്രമാണം സ്ഥാപിക്കാൻ ശ്രമിക്കുമ്പോൾ പിഴവുണ്ടായി.',
'large-file'                  => 'പ്രമാണങ്ങളുടെ വലിപ്പം $1-ൽ കൂടരുതെന്നാണ്‌ നിഷ്ക്കർഷിക്കപ്പെട്ടിരിക്കുന്നത്. ഈ പ്രമാണത്തിന്റെ വലിപ്പം $2 ആണ്‌.',
'largefileserver'             => 'സെർ‌വറിൽ ചിട്ടപ്പെടുത്തിയതുപ്രകാരം ഈ പ്രമാണത്തിന്റെ വലിപ്പം അനുവദനീയമായതിലും കൂടുതലാണ്‌.',
'emptyfile'                   => 'താങ്കൾ അപ്‌ലോഡ് ചെയ്ത പ്രമാണം ശൂന്യമാണെന്നു കാണുന്നു. പ്രമാണത്തിന്റെ പേരിലുള്ള അക്ഷരത്തെറ്റായിരിക്കാം ഇതിനു കാരണം. ഈ പ്രമാണം അപ്‌ലോഡ് ചെയ്യണമോ എന്നതു ഒരിക്കൽ കൂടി പരിശോധിക്കുക.',
'fileexists'                  => "ഇതേ പേരിൽ വേറെ ഒരു പ്രമാണം നിലവിലുണ്ട്.
ദയവായി '''<tt>[[:$1]]</tt>''' പരിശോധിച്ച് പ്രസ്തുത പ്രമാണം മാറ്റണമോ എന്നു തീരുമാനിക്കുക.
[[$1|thumb]]",
'filepageexists'              => "ഈ പ്രമാണത്തിനുള്ള വിവരണതാൾ '''<tt>[[:$1]]</tt>''' എന്നു സൃഷ്ടിക്കപ്പെട്ടിട്ടുണ്ട്, പക്ഷേ ഇതേ പേരിൽ പ്രമാണം ഒന്നും നിലവിലില്ല.
വിവരണതാളിൽ താങ്കൾ ഇവിടെ ചേർക്കുന്ന ലഘുകുറിപ്പ് പ്രത്യക്ഷപ്പെടുന്നതല്ല.
അവിടെ ലഘുകുറിപ്പ് വരാൻ ആ താൾ താങ്കൾ സ്വയം തിരുത്തേണ്ടതാണ്.
[[$1|ലഘുചിത്രം]]",
'fileexists-extension'        => "ഇതേ പേരിൽ മറ്റൊരു പ്രമാണം നിലവിലുണ്ട്: [[$2|ലഘുചിത്രം]]
* ഇപ്പോൾ അപ്‌ലോഡ് ചെയ്ത പ്രമാണത്തിന്റെ പേര്‌: '''<tt>[[:$1]]</tt>'''
* നിലവിലുള്ള പ്രമാണത്തിന്റെ പേര്‌: '''<tt>[[:$2]]</tt>'''
വേറെ ഒരു പേരു തിരഞ്ഞെടുക്കുക.",
'fileexists-thumbnail-yes'    => "ഈ ചിത്രം വലിപ്പം കുറച്ച ഒന്നാണെന്നു ''(ലഘുചിത്രം)'' കാണുന്നു.
[[$1|ലഘുചിത്രം]]
ദയവായി '''<tt>[[:$1]]</tt>''' എന്ന ചിത്രം പരിശോധിക്കുക. 
[[:$1]] എന്ന ചിത്രവും ഈ ചിത്രവും ഒന്നാണെങ്കിൽ ലഘുചിത്രത്തിനു വേണ്ടി മാത്രമായി ചിത്രം അപ്‌ലോഡ് ചെയ്യേണ്ടതില്ല.",
'file-thumbnail-no'           => "പ്രമാണത്തിന്റെ പേര്‌  '''<tt>$1</tt>''' എന്നാണ്‌ തുടങ്ങുന്നത്.
ഇതു വലിപ്പം കുറച്ച ഒരു ചിത്രം ''(ലഘുചിത്രം)'' ആണെന്നു കാണുന്നു.
പൂർണ്ണ റെസലൂഷൻ ഉള്ള ചിത്രം ഉണ്ടെങ്കിൽ അതു അപ്‌ലോഡ് ചെയ്യുവാൻ താല്പര്യപ്പെടുന്നു, അല്ലെങ്കിൽ പ്രമാണത്തിന്റെ പേരു മാറ്റുവാൻ അഭ്യർത്ഥിക്കുന്നു.",
'fileexists-forbidden'        => 'ഈ പേരിൽ ഒരു പ്രമാണം നിലവിലുണ്ട്, അതു മാറ്റി സൃഷ്ടിക്കുക സാദ്ധ്യമല്ല.
താങ്കൾക്ക് ഈ ചിത്രം അപ്‌ലോഡ് ചെയ്തേ മതിയാവുയെങ്കിൽ, ദയവു ചെയ്തു വേറൊരു പേരിൽ ഈ പ്രമാണം അപ്‌ലോഡ് ചെയ്യുക. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'ഈ പേരിൽ ഒരു പ്രമാണം പങ്ക് വെയ്ക്കപ്പെട്ടുപയോഗിക്കുന്ന ശേഖരത്തിലുണ്ട്. താങ്കൾക്ക് ഈ പ്രമാണം അപ്‌ലോഡ് ചെയ്തേ മതിയാവുയെങ്കിൽ, ദയവായി തിരിച്ചു പോയി പുതിയ ഒരു പേരിൽ ഈ പ്രമാണം അപ്‌ലോഡ് ചെയ്യുക.[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'ഈ പ്രമാണം ഇനി പറയുന്ന {{PLURAL:$1|പ്രമാണത്തിന്റെ|പ്രമാണങ്ങളുടെ}} അപരനാണ്‌:',
'file-deleted-duplicate'      => 'ഈ പ്രമാണത്തിനു സദൃശമായ പ്രമാണം ([[$1]]) മുമ്പ് മായ്ക്കപ്പെട്ടിട്ടുണ്ട്.
ആ പ്രമാണത്തിന്റെ മായ്ക്കൽ ചരിത്രം എടുത്തു പരിശോധിച്ച ശേഷം വീണ്ടും അപ്‌‌ലോഡ് ചെയ്യുക.',
'successfulupload'            => 'അപ്‌ലോഡ് വിജയിച്ചിരിക്കുന്നു',
'uploadwarning'               => 'അപ്‌ലോഡ് മുന്നറിയിപ്പ്',
'uploadwarning-text'          => 'ദയവായി താഴെയുള്ള പ്രമാണ വിവരണങ്ങൾ പുതുക്കി വീണ്ടും ശ്രമിക്കുക.',
'savefile'                    => 'പ്രമാണം കാത്ത് സൂക്ഷിക്കുക',
'uploadedimage'               => '"[[$1]]" അപ്‌ലോഡ് ചെയ്തു.',
'overwroteimage'              => '"[[$1]]" എന്നതിന്റെ പുതിയ പതിപ്പ് അപ്‌ലോഡ് ചെയ്തിരിക്കുന്നു',
'uploaddisabled'              => 'അപ്‌ലോഡുകൾ അനുവദനീയമല്ല',
'copyuploaddisabled'          => 'യൂ.ആർ.എൽ. വഴിയുള്ള അപ്‌‌ലോഡ് നിർജ്ജീവമാക്കിയിരിക്കുന്നു.',
'uploadfromurl-queued'        => 'താങ്കളുടെ അപ്‌‌ലോഡ് നിർവഹിക്കാൻ ഉൾപ്പെടുത്തിയിരിക്കുന്നു.',
'uploaddisabledtext'          => 'പ്രമാണം അപ്‌ലോഡ് ചെയ്യുന്നതു സാദ്ധ്യമല്ലാതാക്കിയിരിക്കുന്നു.',
'php-uploaddisabledtext'      => 'പി.എച്ച്.പി.യിൽ പ്രമാണ അപ്‌‌ലോഡുകൾ സാദ്ധ്യമല്ലാതാക്കിയിരിക്കുന്നു.
ദയവായി file_uploads ക്രമീകരണങ്ങൾ പരിശോധിക്കുക.',
'uploadscripted'              => 'ഈ പ്രമാണത്തിൽ വെബ്ബ് ബ്രൗസർ തെറ്റായി വ്യാഖ്യാനിച്ചേക്കാവുന്ന HTML അല്ലെങ്കിൽ സ്ക്രിപ് കോഡുകൾ ഉണ്ട്.',
'uploadvirus'                 => 'പ്രമാണത്തിൽ വൈറസുണ്ട്! വിശദാംശങ്ങൾ: $1',
'upload-source'               => 'സ്രോതസ്സ് പ്രമാണം',
'sourcefilename'              => 'അപ്‌ലോഡ് ചെയ്യേണ്ട പ്രമാണത്തിന്റെ സ്രോതസ്സ് നാമം:',
'sourceurl'                   => 'സ്രോതസ്സ് യു.ആർ.എൽ:',
'destfilename'                => '{{SITENAME}} സംരംഭത്തിൽ ഉപയോഗിക്കേണ്ട പേര്:',
'upload-maxfilesize'          => 'പ്രമാണത്തിന്റെ വലിപ്പത്തിന്റെ കൂടിയ പരിധി: $1',
'upload-description'          => 'പ്രമാണ വിവരണം',
'upload-options'              => 'അപ്‌‌ലോഡ് ഐച്ഛികങ്ങൾ',
'watchthisupload'             => 'ഈ പ്രമാണം ശ്രദ്ധിക്കുക',
'filewasdeleted'              => 'ഈ പേരിലുള്ള ഒരു പ്രമാണം ഇതിനു മുൻപ് അപ്‌ലോഡ് ചെയ്യുകയും പിന്നീട് മായ്ക്കുകയും ചെയ്തിട്ടുള്ളതാണ്‌. ഈ പ്രമാണം തുടർന്നും അപ്‌ലോഡ് ചെയ്യുന്നതിനു മുൻപ് $1 പരിശോധിക്കേണ്ടതാണ്‌.',
'upload-wasdeleted'           => "'''മുന്നറിയിപ്പ്: മുൻപ് അപ്‌ലോഡ് ചെയ്യുകയും പിന്നീട് മായ്ക്കുകയും ചെയ്തിട്ടുള്ള ഒരു പ്രമാണമാണ്‌ താങ്കൾ അപ്‌ലോഡ് ചെയ്യാൻ ശ്രമിക്കുന്നത്.'''

ഈ പ്രമാണം അപ്‌ലോഡ് ചെയ്യുന്നതു തുടരണമോ എന്നതു പരിശോധിക്കുന്നത് നന്നായിരിക്കും.
താങ്കളുടെ പരിശോധനയ്ക്കായി പ്രമാണത്തിന്റെ മായ്ക്കൽ രേഖ ഇവിടെ കൊടുത്തിരിക്കുന്നു:",
'filename-bad-prefix'         => "താങ്കൾ അപ്‌ലോഡ് ചെയ്യുവാൻ ശ്രമിക്കുന്ന പ്രമാണത്തിന്റെ പേര്‌ '''\"\$1\"''' എന്നാണ്‌ തുടങ്ങുന്നത്. ഇതു ഡിജിറ്റൽ ക്യാമറയിൽ പടങ്ങൾക്കു യാന്ത്രികമായി ചേർക്കുന്ന പേരാണ്‌. ദയവു ചെയ്തു താങ്കൾ അപ്‌ലോഡ് ചെയ്യുന്ന പ്രമാണത്തെ വിശദീകരിക്കുന്ന അനുയോജ്യമായ ഒരു പേരു തിരഞ്ഞെടുക്കുക.",
'upload-successful-msg'       => 'താങ്കൾ അപ്‌‌ലോഡ് ചെയ്തത് ഇവിടെ ലഭ്യമാണ്: $1',
'upload-failure-subj'         => 'അപ്‌‌ലോഡിൽ പിഴവുണ്ട്',
'upload-failure-msg'          => 'താങ്കളുടെ അപ്‌‌ലോഡിൽ ഒരു പ്രശ്നമുണ്ട്:

$1',

'upload-proto-error'        => 'തെറ്റായ പ്രോട്ടോക്കോൾ',
'upload-proto-error-text'   => 'റിമോട്ട് അപ്‌ലോഡിനു <code>http://</code> അഥവാ <code>ftp://</code> എന്നു തുടങ്ങുന്ന URL വേണം.',
'upload-file-error'         => 'ആന്തരികപ്രശ്നം',
'upload-file-error-text'    => 'സെർവറിൽ ഒരു താൽക്കാലിക പ്രമാണം ഉണ്ടാക്കുവാൻ ശ്രമിക്കുമ്പോൾ ആന്തരികപ്രശ്നം സംഭവിച്ചു. ദയവായി [[Special:ListUsers/sysop|കാര്യനിർവാഹകരിലൊരാളെ]] സമീപിക്കുക.',
'upload-misc-error'         => 'കാരണം അജ്ഞാതമായ അപ്‌ലോഡ് പിഴവ്',
'upload-misc-error-text'    => 'അപ്‌ലോഡിങ്ങ് സമയത്ത് അജ്ഞാതമായ പിഴവ് സംഭവിച്ചു.
ദയവായി URL സാധുവാണോ എന്നും അതു പ്രാപ്യമാണോ എന്നും പരിശോധിച്ചതിനു ശേഷം വീണ്ടും പരിശ്രമിക്കുക.
തുടർന്നും പ്രശ്നം അവശേഷിക്കുകയാണെങ്കിൽ [[Special:ListUsers/sysop|കാര്യനി‌ർവാഹകരിലൊരാളെ]] സമീപിക്കുക.',
'upload-too-many-redirects' => 'യൂ.ആർ.എല്ലിൽ നിരവധി തിരിച്ചുവിടലുകളുണ്ട്',
'upload-unknown-size'       => 'വലിപ്പം അറിയില്ല',
'upload-http-error'         => 'ഒരു എച്ച്.റ്റി.റ്റി.പി. പിഴവു സംഭവിച്ചിരിക്കുന്നു: $1',

# img_auth script messages
'img-auth-accessdenied' => 'പ്രവേശനമില്ല',
'img-auth-nopathinfo'   => 'PATH_INFO ലഭ്യമല്ല.
താങ്കളുടെ സെർവർ ഈ വിവരം കൈമാറ്റം ചെയ്യാൻ തയ്യാറാക്കിയിട്ടില്ല.
അത് img_auth പിന്തുണയില്ലാത്ത സി.ജി.ഐ. അധിഷ്ഠിതമായ ഒന്നായിരിക്കാം.
http://www.mediawiki.org/wiki/Manual:Image_Authorization കാണുക.',
'img-auth-notindir'     => 'ആവശ്യപ്പെട്ട പാത അപ്‌‌ലോഡ് ഡയറക്റ്ററിയിൽ സജ്ജീകരിച്ചു നൽകിയിട്ടില്ല.',
'img-auth-badtitle'     => '"$1" എന്നതിൽ നിന്ന് സാധുവായ തലക്കെട്ട് സൃഷ്ടിക്കാൻ കഴിയില്ല.',
'img-auth-nologinnWL'   => 'താങ്കൾ ലോഗിൻ ചെയ്തിട്ടില്ല ഒപ്പം "$1" ശുദ്ധിപട്ടികയിൽ ഇല്ല.',
'img-auth-nofile'       => '"$1" എന്ന പ്രമാണം നിലവിലില്ല.',
'img-auth-isdir'        => 'താങ്കൾ "$1" എന്ന ഡയറക്ടറി എടുക്കാനാണു ശ്രമിക്കുന്നത്.
പ്രമാണങ്ങൾ എടുക്കാൻ മാത്രമേ അനുവദിക്കുള്ളു.',
'img-auth-streaming'    => 'സ്ട്രീമിങ് "$1".',
'img-auth-public'       => 'img_auth.php എന്ന ഗുണനിർവഹണം സ്വകാര്യ‌‌വിക്കികളിൽ പ്രമാണങ്ങൾ ഔട്ട്പുട്ട് ചെയ്യുന്നതിനുള്ളതാണ്.
ഈ വിക്കി ഒരു പൊതുജന വിക്കിയായാണ് ക്രമീകരിച്ചിരിക്കുന്നത്.
സുരക്ഷയ്ക്ക് ഏറ്റവും അനുകൂലിതമായെന്നതിനാൽ img_auth.php നിർജീവമാക്കിയിരിക്കുന്നു.',
'img-auth-noread'       => '"$1" എടുത്തുനോക്കാൻ ഉപയോക്താവിനു കഴിയില്ല.',

# HTTP errors
'http-invalid-url'      => 'അസാധുവായ യു.ആർ.എൽ.: $1',
'http-invalid-scheme'   => '"$1" രീതിയിലുള്ള യു.ആർ.എല്ലുകൾ പിന്തുണയ്ക്കുന്നില്ല',
'http-request-error'    => 'അഭ്യർത്ഥന അയയ്ക്കുന്നതിൽ അപരിചിതമായ പിഴവ്:',
'http-read-error'       => 'എച്ച്.റ്റി.റ്റി.പി. വിവരം പ്രദർശിപ്പിക്കുന്നതിൽ പിഴവ്.',
'http-timed-out'        => 'എച്ച്.റ്റി.റ്റി.പി. അഭ്യർത്ഥന സമയം കഴിഞ്ഞു.',
'http-curl-error'       => 'യു.ആർ.എൽ. ശേഖരിക്കുന്നതിൽ പിഴവ്: $1',
'http-host-unreachable' => 'യു.ആർ.എൽ.-ല്‍ എത്തിപ്പെടാന്‍ സാധിച്ചില്ല',
'http-bad-status'       => 'എച്ച്.റ്റി.റ്റി.പി. അഭ്യർത്ഥനാ വേളയിൽ ഒരു പിഴവുണ്ടായി: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL-ൽ എത്തിപ്പെടാൻ സാധിച്ചില്ല',
'upload-curl-error6-text'  => 'താങ്കൾ സമർപ്പിച്ച URL പ്രാപ്യമല്ല‌. ദയവായി URL സാധുവാണോ എന്നും സൈറ്റ് സജീവമാണോ എന്നും പരിശോധിക്കുക.',
'upload-curl-error28'      => 'അപ്‌ലോഡ് ടൈംഔട്ട്',
'upload-curl-error28-text' => 'ഈ സൈറ്റിൽ നിന്നു പ്രതികരണം ലഭിക്കുവാൻ ധാരാളം സമയമെടുക്കുന്നു. സൈറ്റ് സജീവമാണോ എന്നു പരിശോധിക്കുകയും കുറച്ച് സമയം കാത്തു നിന്നതിനു ശേഷം വീടും ശ്രമിക്കുകയും ചെയ്യുക. തിരക്കു കുറഞ്ഞ സമയത്ത് പരിശ്രമിക്കുന്നതാവും നല്ലത്.',

'license'            => 'പകർപ്പവകാശ വിവരങ്ങൾ:',
'license-header'     => 'അനുമതി',
'nolicense'          => 'ഒന്നും തിരഞ്ഞെടുത്തിട്ടില്ല',
'license-nopreview'  => '(പ്രിവ്യൂ ലഭ്യമല്ല)',
'upload_source_url'  => '(സാധുവായ, ആർക്കും ഉപയോഗിക്കാവുന്ന URL)',
'upload_source_file' => '(താങ്കളുടെ കമ്പ്യൂട്ടറിലുള്ള ഒരു പ്രമാണം)',

# Special:ListFiles
'listfiles-summary'     => 'ചുരുക്കം',
'listfiles_search_for'  => 'മീഡിയ പ്രമാണം തിരയുക:',
'imgfile'               => 'പ്രമാണം',
'listfiles'             => 'പ്രമാണങ്ങളുടെ പട്ടിക',
'listfiles_date'        => 'തിയതി',
'listfiles_name'        => 'പേര്',
'listfiles_user'        => 'ഉപയോക്താവ്',
'listfiles_size'        => 'വലിപ്പം',
'listfiles_description' => 'വിവരണം',
'listfiles_count'       => 'പതിപ്പുകൾ',

# File description page
'file-anchor-link'                  => 'പ്രമാണം',
'filehist'                          => 'പ്രമാണ നാൾ‌വഴി',
'filehist-help'                     => 'ഏതെങ്കിലും തീയതി/സമയ കണ്ണിയിൽ ഞെക്കിയാൽ പ്രസ്തുതസമയത്ത് ഈ പ്രമാണം എങ്ങനെയായിരുന്നു എന്നു കാണാം.',
'filehist-deleteall'                => 'എല്ലാം മായ്ക്കുക',
'filehist-deleteone'                => 'ഇതു മായ്ക്കുക',
'filehist-revert'                   => 'പൂർവ്വസ്ഥിതിയിലാക്കുക',
'filehist-current'                  => 'നിലവിലുള്ളത്',
'filehist-datetime'                 => 'തീയതി/സമയം',
'filehist-thumb'                    => 'ലഘുചിത്രം',
'filehist-thumbtext'                => 'മാറ്റം $1-ന്റെ ലഘുചിത്രം',
'filehist-nothumb'                  => 'ലഘുചിത്രമില്ല',
'filehist-user'                     => 'ഉപയോക്താവ്',
'filehist-dimensions'               => 'അളവുകൾ',
'filehist-filesize'                 => 'പ്രമാണത്തിന്റെ വലിപ്പം',
'filehist-comment'                  => 'അഭിപ്രായം',
'filehist-missing'                  => 'പ്രമാണം ലഭ്യമല്ല',
'imagelinks'                        => 'പ്രമാണത്തിലേക്കുള്ള കണ്ണികൾ',
'linkstoimage'                      => 'താഴെ കാണുന്ന {{PLURAL:$1|താളിൽ|$1 താളുകളിൽ}}  ഈ ചിത്രം ഉപയോഗിക്കുന്നു:',
'linkstoimage-more'                 => 'ഈ പ്രമാണത്തിലേയ്ക്ക് {{PLURAL:$1|ഒരു താളിലധികം കണ്ണി|$1 താളിലധികം കണ്ണികൾ}} ഉണ്ട്.
താഴെക്കൊടുത്തിരിക്കുന്ന പട്ടിക ഈ പ്രമാണത്തിലേയ്ക്കു മാത്രമുള്ള {{PLURAL:$1|ആദ്യ താളിന്റെ കണ്ണി|ആദ്യ $1 താളുകളുടെ കണ്ണികൾ}} കാട്ടുന്നു.
[[Special:WhatLinksHere/$2|മുഴുവൻ പട്ടികയും]] ലഭ്യമാണ്.',
'nolinkstoimage'                    => 'ഈ ചിത്രം/പ്രമാണം വിക്കിയിലെ താളുകളിലൊന്നിലും ഉപയോഗിക്കുന്നില്ല.',
'morelinkstoimage'                  => 'ഈ പ്രമാണത്തിലേയ്ക്കുള്ള [[Special:WhatLinksHere/$1|കൂടുതൽ കണ്ണികൾ]] കാണുക.',
'redirectstofile'                   => 'താഴെ ഈ പ്രമാണത്തിലേയ്ക്കുള്ള {{PLURAL:$1|പ്രമാണ തിരിച്ചുവിടലുകൾ|$1 പ്രമാണങ്ങളുടെ തിരിച്ചുവിടൽ}} കൊടുത്തിരിക്കുന്നു:',
'duplicatesoffile'                  => 'ഈ പ്രമാണത്തിന്റെ {{PLURAL:$1|ഒരു അപര പ്രമാണത്തെ|$1 അപര പ്രമാണങ്ങളെ}} താഴെ കൊടുത്തിരിക്കുന്നു ([[Special:FileDuplicateSearch/$2|കൂടുതൽ വിവരങ്ങൾ]]):',
'sharedupload'                      => 'ഇത് $1 സം‌രംഭത്തിൽ നിന്നുള്ള പ്രമാണമാണ്‌, മറ്റു സം‌രംഭങ്ങളും ഇതുപയോഗിക്കുന്നുണ്ടാകാം.',
'sharedupload-desc-there'           => 'ഈ പ്രമാണം $1 സംരംഭത്തിൽ നിന്നുമുള്ളതാണ്, മറ്റു പദ്ധതികൾ ഇതുപയോഗിക്കുന്നുണ്ടാകാം.
കൂടുതൽ വിവരങ്ങൾക്ക് ദയവായി [$2 പ്രമാണത്തിന്റെ വിവരണ താൾ] കാണുക.',
'sharedupload-desc-here'            => 'ഈ പ്രമാണം $1 സംരംഭത്തിൽ നിന്നുമുള്ളതാണ്, മറ്റു പദ്ധതികൾ ഇതുപയോഗിക്കുന്നുണ്ടാകാം.
ഈ [$2 പ്രമാണത്തിന്റെ വിവരണ താളിലുള്ള] വിവരങ്ങൾ താഴെ കൊടുത്തിരിക്കുന്നു.',
'filepage-nofile'                   => 'ഈ പേരിൽ ഒരു പ്രമാണവും നിലവിലില്ല.',
'filepage-nofile-link'              => 'ഈ പേരിൽ ഒരു പ്രമാണവും നിലവിലില്ല, താങ്കൾക്ക് [$1 അത് അപ്‌ലോഡ് ചെയ്യാവുന്നതാണ്‌]',
'uploadnewversion-linktext'         => 'ഈ ചിത്രത്തിലും മെച്ചപ്പെട്ടത് അപ്‌ലോഡ് ചെയ്യുക',
'shared-repo-from'                  => '$1 സംരംഭത്തിൽ നിന്ന്',
'shared-repo'                       => 'ഒരു പങ്കുവെക്കപ്പെട്ട സംഭരണി',
'shared-repo-name-wikimediacommons' => 'വിക്കിമീഡിയ കോമൺസ്',

# File reversion
'filerevert'                => '$1 തിരസ്ക്കരിക്കുക',
'filerevert-legend'         => 'പ്രമാണം തിരസ്ക്കരിക്കുക',
'filerevert-intro'          => "താങ്കൾ '''[[Media:$1|$1]]''' യെ, [$3, $2 ഉണ്ടായിരുന്ന $4 പതിപ്പിലേക്കു സേവ് ചെയ്യുകയാണ്‌].",
'filerevert-comment'        => 'കാരണം:',
'filerevert-defaultcomment' => '$2 ൽ ഉണ്ടായിരുന്ന $1 പതിപ്പിലേക്കു സേവ് ചെയ്തിരിക്കുന്നു',
'filerevert-submit'         => 'പൂർവ്വസ്ഥിതിയിലാക്കുക',
'filerevert-success'        => "'''[[Media:$1|$1]]''' യെ,  [$3, $2 ഉണ്ടായിരുന്ന $4] പതിപ്പിലേക്കു സേവ് ചെയ്തിരിക്കുന്നു.",
'filerevert-badversion'     => 'താങ്കൾ തന്ന സമയവുമായി യോജിക്കുന്ന മുൻ പതിപ്പുകൾ ഒന്നും തന്നെ ഈ പ്രമാണത്തിനില്ല.',

# File deletion
'filedelete'                  => '$1 മായ്ക്കുക',
'filedelete-legend'           => 'പ്രമാണം മായ്ക്കുക',
'filedelete-intro'            => "താങ്കൾ '''[[Media:$1|$1]]''' എന്ന പ്രമാണം അതിന്റെ എല്ലാ ചരിത്രവുമടക്കം നീക്കം ചെയ്യാൻ പോവുകയാണ്‌.",
'filedelete-intro-old'        => "താങ്കൾ '''[[Media:$1|$1]]''' യയുടെ [$3, $2 ഉണ്ടായിരുന്ന $4] പതിപ്പാണു മായ്ക്കുവാൻ പോകുന്നത്.",
'filedelete-comment'          => 'നീക്കം ചെയ്യാനുള്ള കാരണം:',
'filedelete-submit'           => 'മായ്ക്കുക',
'filedelete-success'          => "'''$1''' മായ്ച്ചു കഴിഞ്ഞു.",
'filedelete-success-old'      => "'''[[Media:$1|$1]]''' എന്ന മീഡിയയുടെ $3, $2-വിൽ ഉണ്ടായിരുന്ന പതിപ്പ് മായ്ച്ചിരിക്കുന്നു.",
'filedelete-nofile'           => "'''$1'''  നിലവിലില്ല.",
'filedelete-otherreason'      => 'മറ്റു/കൂടുതൽ കാരണങ്ങൾ:',
'filedelete-reason-otherlist' => 'മറ്റു കാരണങ്ങൾ',
'filedelete-reason-dropdown'  => '*നീക്കം ചെയ്യാനുള്ള സാധാരണ കാരണങ്ങൾ
** പകർപ്പവകാശ ലംഘനം
** നിലവിലുള്ള പ്രമാണത്തിന്റെ പകർപ്പ്
** പകർപ്പവകാശ വിവരങ്ങൾ ചേർത്തിട്ടില്ല',
'filedelete-edit-reasonlist'  => 'മായ്ക്കലിന്റെ കാരണം തിരുത്തുക',
'filedelete-maintenance'      => 'നന്നാക്കൽ പ്രവർത്തനങ്ങൾ പുരോഗമിക്കുന്നതിനാൽ പ്രമാണങ്ങളുടെ മായ്ക്കലും പുനഃസ്ഥാപിക്കലും താത്ക്കാലികമായി നിർത്തിവച്ചിരിക്കുന്നു.',

# MIME search
'mimesearch'         => 'MIME തിരയൽ',
'mimesearch-summary' => 'ഈ താൾ പ്രമാണങ്ങളെ അവയുടെ മൈം-തരം അനുസരിച്ച് അരിച്ചെടുക്കാൻ പ്രാപ്തമാക്കുന്നു:
നൽകേണ്ടവിധം: പ്രമാണത്തിന്റെ തരം/ഉപതരം, ഉദാ:<tt>image/jpeg</tt>.',
'mimetype'           => 'MIME തരം:',
'download'           => 'ഡൗൺലോഡ്',

# Unwatched pages
'unwatchedpages' => 'ആരും ശ്രദ്ധിക്കാത്ത താളുകൾ',

# List redirects
'listredirects' => 'തിരിച്ചുവിടൽ താളുകളുടെ പട്ടിക കാണിക്കുക',

# Unused templates
'unusedtemplates'     => 'ഉപയോഗിക്കപ്പെടാത്ത ഫലകങ്ങൾ',
'unusedtemplatestext' => '{{ns:template}} എന്ന നാമമേഖലയിൽ ഉള്ളതും ഒരു താളിലും ചേർത്തിട്ടുമില്ലാത്ത എല്ലാ ഫലകതാളുകളുടേയും പട്ടിക ഈ താളിൽ കാണാം. ഫലകങ്ങൾ മായ്ക്കുന്നതിനു മുൻപ് അതു മറ്റൊരു താളിലും ഉപയോഗിക്കുന്നില്ല എന്നുറപ്പാക്കുക.',
'unusedtemplateswlh'  => 'മറ്റു കണ്ണികൾ',

# Random page
'randompage'         => 'ഏതെങ്കിലും താൾ',
'randompage-nopages' => 'ഇനി കൊടുത്തിരിക്കുന്ന {{PLURAL:$2|നാമമേഖലയിൽ|നാമമേഖലകളിൽ}} താളുകൾ ഒന്നുമില്ല: $1.',

# Random redirect
'randomredirect'         => 'ക്രമരഹിതമായ തിരിച്ചുവിടൽ',
'randomredirect-nopages' => '"$1" എന്ന നാമമേഖലയിൽ തിരിച്ചുവിടൽ താളുകളൊന്നുമില്ല.',

# Statistics
'statistics'                   => 'സ്ഥിതിവിവരക്കണക്കുകൾ',
'statistics-header-pages'      => 'താൾ സ്ഥിതിവിവരക്കണക്കുകൾ',
'statistics-header-edits'      => 'തിരുത്തൽ സ്ഥിതിവിവരക്കണക്കുകൾ',
'statistics-header-views'      => 'സന്ദർശനങ്ങളുടെ സ്ഥിതിവിവരക്കണക്കുകൾ',
'statistics-header-users'      => 'ഉപയോക്താക്കളുടെ സ്ഥിതിവിവരക്കണക്കുകൾ',
'statistics-header-hooks'      => 'മറ്റു സ്ഥിതിവിവരക്കണക്കുകൾ',
'statistics-articles'          => 'ലേഖനങ്ങൾ',
'statistics-pages'             => 'താളുകൾ',
'statistics-pages-desc'        => 'സം‌വാദം താളുകൾ, തിരിച്ചുവിടലുകൾ തുടങ്ങിയവയടക്കം വിക്കിയിലെ എല്ലാ താളുകളും.',
'statistics-files'             => 'അപ്‌ലോഡ് ചെയ്തിട്ടുള്ള പ്രമാണങ്ങൾ',
'statistics-edits'             => '{{SITENAME}} സം‌രംഭത്തിന്റെ തുടക്കം മുതലുള്ള തിരുത്തലുകൾ',
'statistics-edits-average'     => 'ഒരു താളിൽ ശരാശരി തിരുത്തലുകൾ',
'statistics-views-total'       => 'ആകെ സന്ദർശനങ്ങൾ',
'statistics-views-peredit'     => 'ഓരോ തിരുത്തലിലും ഉള്ള എടുത്തുനോട്ടങ്ങൾ',
'statistics-users'             => 'രജിസ്റ്റർ ചെയ്തിട്ടുള്ള [[Special:ListUsers|ഉപയോക്താക്കൾ]]',
'statistics-users-active'      => 'സജീവ ഉപയോക്താക്കൾ',
'statistics-users-active-desc' => 'കഴിഞ്ഞ {{PLURAL:$1|ദിവസം|$1 ദിവസങ്ങൾക്കുള്ളിൽ}} പ്രവർത്തിച്ചിട്ടുള്ള ഉപയോക്താക്കൾ',
'statistics-mostpopular'       => 'ഏറ്റവുമധികം സന്ദർശിക്കപ്പെട്ട താളുകൾ',

'disambiguations'      => 'വിവക്ഷിതങ്ങളെ കുറിക്കുന്ന താളുകൾ',
'disambiguationspage'  => 'Template:നാനാർത്ഥം',
'disambiguations-text' => 'താഴെ കൊടുത്തിരിക്കുന്ന താളുകൾ വിവക്ഷിതങ്ങൾ താളിലേക്കു കണ്ണി ചേർക്കപ്പെട്ടിരിക്കുന്നു. അതിനു പകരം അവ ലേഖനതാളുകളിലേക്കു കണ്ണി ചേക്കേണ്ടതാണ്‌. <br /> ഒരു താളിനെ വിവക്ഷിത താൾ ആയി പരിഗണിക്കണമെങ്കിൽ അതു  [[MediaWiki:Disambiguationspage]] എന്ന താളിൽ നിന്നു കണ്ണി ചേർക്കപ്പെട്ട ഒരു ഫലകം ഉപയോഗിക്കണം.',

'doubleredirects'            => 'ഇരട്ട തിരിച്ചുവിടലുകൾ',
'doubleredirectstext'        => 'ഈ താളിൽ ഒരു തിരിച്ചുവിടലിൽ നിന്നും മറ്റു തിരിച്ചുവിടൽ താളുകളിലേയ്ക്ക് പോകുന്ന താളുകൾ കൊടുത്തിരിക്കുന്നു. ഓരോ വരിയിലും ഒന്നാമത്തേയും രണ്ടാമത്തേയും തിരിച്ചുവിടൽ താളിലേക്കുള്ള കണ്ണികളും, രണ്ടാമത്തെ തിരിച്ചുവിടൽ താളിൽ നിന്നു ശരിയായ ലക്ഷ്യതാളിലേക്കുള്ള കണ്ണികളും ഉൾക്കൊള്ളുന്നു.
<s>വെട്ടിക്കൊടുത്തിരിക്കുന്നവ</s> ശരിയാക്കേണ്ടതുണ്ട്.',
'double-redirect-fixed-move' => '[[$1]] മാറ്റിയിരിക്കുന്നു.
ഇത് ഇപ്പോൾ [[$2]] എന്നതിലേയ്ക്ക് തിരിച്ചുവിടപ്പെട്ടിരിക്കുന്നു.',
'double-redirect-fixer'      => 'തിരിച്ചുവിടൽ ശരിയാക്കിയത്',

'brokenredirects'        => 'മുറിഞ്ഞ തിരിച്ചുവിടലുകൾ',
'brokenredirectstext'    => 'താഴെക്കാണുന്ന തിരിച്ചുവിടലുകൾ നിലവിലില്ലാത്ത താളുകളിലേയ്ക്കാണ്‌:',
'brokenredirects-edit'   => 'തിരുത്തുക',
'brokenredirects-delete' => 'മായ്ക്കുക',

'withoutinterwiki'         => 'അന്തർഭാഷാകണ്ണികൾ ഇല്ലാത്ത താളുകൾ',
'withoutinterwiki-summary' => 'താഴെ പ്രദർശിപ്പിച്ചിരിക്കുന്ന താളുകളിൽ മറ്റു ഭാഷാ വിക്കികളിലേക്ക്  കണ്ണി ചേർത്തിട്ടില്ല.',
'withoutinterwiki-legend'  => 'പൂർവപദം',
'withoutinterwiki-submit'  => 'പ്രദർശിപ്പിക്കുക',

'fewestrevisions' => 'ഏറ്റവും ചുരുക്കം പ്രാവശ്യം തിരുത്തപ്പെട്ട താളുകൾ',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|ഒരു ബൈറ്റ്|$1 ബൈറ്റുകൾ}}',
'ncategories'             => '$1 {{PLURAL:$1|വർഗ്ഗം|വർഗ്ഗങ്ങൾ}}',
'nlinks'                  => '{{PLURAL:$1|ഒരു കണ്ണി|$1 കണ്ണികൾ}}',
'nmembers'                => '{{PLURAL:$1|ഒരു അംഗം|$1 അംഗങ്ങൾ}}',
'nrevisions'              => '{{PLURAL:$1|ഒരു പതിപ്പ്|$1 പതിപ്പുകൾ}}',
'nviews'                  => '{{PLURAL:$1|ഒരു സന്ദർശനം|$1 സന്ദർശനങ്ങൾ}}',
'specialpage-empty'       => 'ഈ താൾ ശൂന്യമാണ്.',
'lonelypages'             => 'അനാഥ താളുകൾ',
'lonelypagestext'         => 'താഴെക്കാണുന്ന താളുകളിലേക്ക് {{SITENAME}} സം‌രംഭത്തിലെ മറ്റു താളുകളിൽനിന്നും കണ്ണികളോ ഉൾപ്പെടുത്തലോ നിലവിലില്ല.',
'uncategorizedpages'      => 'വർഗ്ഗീകരിച്ചിട്ടില്ലാത്ത താളുകൾ',
'uncategorizedcategories' => 'വർഗ്ഗീകരിക്കപ്പെടാത്ത വർഗ്ഗങ്ങൾ',
'uncategorizedimages'     => 'വർഗ്ഗീകരിക്കപ്പെടാത്ത പ്രമാണങ്ങൾ',
'uncategorizedtemplates'  => 'വർഗ്ഗീകരിക്കാത്ത ഫലകങ്ങൾ',
'unusedcategories'        => 'ഉപയോഗത്തിലില്ലാത്ത വർഗ്ഗങ്ങൾ',
'unusedimages'            => 'ഉപയോഗിക്കപ്പെടാത്ത പ്രമാണങ്ങൾ',
'popularpages'            => 'ജനപ്രീതിയുള്ള താളുകൾ',
'wantedcategories'        => 'അവശ്യ വർഗ്ഗങ്ങൾ',
'wantedpages'             => 'അവശ്യ താളുകൾ',
'wantedpages-badtitle'    => 'ഫലങ്ങളുടെ ഗണത്തിൽ അസാധുവായ തലക്കെട്ട്: $1',
'wantedfiles'             => 'ആവശ്യമുള്ള പ്രമാണങ്ങൾ',
'wantedtemplates'         => 'അവശ്യ ഫലകങ്ങൾ',
'mostlinked'              => 'ഏറ്റവുമധികം കണ്ണികളാൽ ബന്ധിപ്പിക്കപ്പെട്ട താളുകൾ',
'mostlinkedcategories'    => 'ഏറ്റവും കൂടുതൽ താളുകൾ ബന്ധിപ്പിക്കപ്പെട്ട വർഗ്ഗങ്ങൾ',
'mostlinkedtemplates'     => 'ഫലകങ്ങളിലേക്ക് ഏറ്റവുമധികം കണ്ണിയുള്ള താളുകൾ',
'mostcategories'          => 'ഏറ്റവും കൂടുതൽ വർഗ്ഗങ്ങൾ ഉൾപെടുത്തിയിരിക്കുന്ന താളുകൾ',
'mostimages'              => 'ചിത്രങ്ങളിലേക്ക് ഏറ്റവുമധികം കണ്ണിയുള്ള താളുകൾ',
'mostrevisions'           => 'ഏറ്റവുമധികം തിരുത്തപ്പെട്ട താളുകൾ',
'prefixindex'             => 'പൂർവപദത്തോടു കൂടിയ എല്ലാ താളുകളും',
'shortpages'              => 'വിവരം ഏറ്റവും കുറവുള്ള താളുകൾ',
'longpages'               => 'വലിയ താളുകളുടെ പട്ടിക',
'deadendpages'            => 'അന്തർ വിക്കി കണ്ണിയാൽ ബന്ധിപ്പിക്കപ്പെടാത്ത താളുകൾ',
'deadendpagestext'        => 'താഴെക്കാണുന്ന താളുകളിൽനിന്ന് {{SITENAME}} സം‌രംഭത്തിലെ മറ്റൊരു താളിലേയ്ക്കും കണ്ണി ചേർത്തിട്ടില്ല.',
'protectedpages'          => 'സംരക്ഷിക്കപ്പെട്ടിരിക്കുന്ന താളുകൾ',
'protectedpages-indef'    => 'അനന്തകാലത്തേയ്ക്ക് സംരക്ഷിക്കപ്പെട്ടവ മാത്രം',
'protectedpages-cascade'  => 'നിർഝരിത സംരക്ഷണങ്ങൾ മാത്രം',
'protectedpagestext'      => 'താഴെ പ്രദർശിപ്പിച്ചിരിക്കുന്ന താളുകൾ തലക്കെട്ട് മാറ്റുന്നതിൽ നിന്നും തിരുത്തൽ വരുത്തുന്നതിൽ നിന്നും സം‌രക്ഷിച്ചിരിക്കുന്നു',
'protectedpagesempty'     => 'ഈ ചരങ്ങൾ ഉപയോഗിച്ചു താളുകൾ ഒന്നും തന്നെ സം‌രക്ഷിക്കപ്പെട്ടിട്ടില്ല.',
'protectedtitles'         => 'സംരക്ഷിക്കപ്പെട്ട താളുകൾ',
'protectedtitlestext'     => 'താഴെക്കാണുന്ന തലക്കെട്ടുകൾ സൃഷ്ടിക്കുന്നത് നിരോധിച്ചിരിക്കുന്നു',
'protectedtitlesempty'    => 'ഈ ചരങ്ങൾ ഉപയോഗിച്ചു തലക്കെട്ടുകൾ ഒന്നും തന്നെ സം‌രക്ഷിക്കപ്പെട്ടിട്ടില്ല.',
'listusers'               => 'ഉപയോക്താക്കളുടെ പട്ടിക',
'listusers-editsonly'     => 'തിരുത്തലുകൾ വരുത്തിയ ഉപയോക്താക്കളെമാത്രം കാണിക്കുക',
'listusers-creationsort'  => 'സൃഷ്ടിക്കപ്പെട്ട തീയതി അനുസരിച്ച് ക്രമീകരിക്കുക',
'usereditcount'           => '{{PLURAL:$1|ഒരു തിരുത്തൽ|$1 തിരുത്തലുകൾ}}',
'usercreated'             => '$1 $2-നു സൃഷ്ടിച്ചത്',
'newpages'                => 'പുതിയ താളുകൾ',
'newpages-username'       => 'ഉപയോക്തൃനാമം:',
'ancientpages'            => 'ഏറ്റവും പഴയ താളുകൾ',
'move'                    => 'തലക്കെട്ടു്‌ മാറ്റുക',
'movethispage'            => 'ഈ താൾ മാറ്റുക',
'unusedimagestext'        => 'താഴെ കൊടുത്തിരിക്കുന്ന പ്രമാണങ്ങൾ നിലവിലുണ്ട്, പക്ഷേ ഒരു താളിലും ഉൾപ്പെടുത്തിയിട്ടില്ല.
മറ്റു വെബ്‌‌ സൈറ്റുകൾ നേരിട്ടുള്ള യൂ.ആർ.എൽ. വഴി പ്രമാണത്തെ നേരിട്ട് ഉപയോഗിക്കുന്നുണ്ടാവുമെന്ന് ദയവായി ഓർക്കുക, അതുകൊണ്ട് സജീവമായ ഉപയോഗത്തെ ശ്രദ്ധിക്കാതെയാവാം ഇവിടെ പട്ടികയിൽ ഉൾപ്പെടുത്തിയിരിക്കുന്നത്.',
'unusedcategoriestext'    => 'താഴെ പറയുന്ന വർഗ്ഗങ്ങൾക്ക് താൾ നിലവിലുണ്ട്, എങ്കിലും മറ്റു താളുകളോ വർഗ്ഗങ്ങളോ അവ ഉപയോഗിക്കുന്നില്ല.',
'notargettitle'           => 'ലക്ഷ്യം നിർ‌വചിച്ചിട്ടില്ല',
'notargettext'            => 'ഈ പ്രക്രിയ പൂർത്തിയാക്കുവാൻ ആവശ്യമായ ലക്ഷ്യതാളിനേയോ ഉപയോക്താവിനേയോ താങ്കൾ സൂചിപ്പിച്ചിട്ടില്ല.',
'nopagetitle'             => 'ഇങ്ങനെ ഒരു താൾ നിലവിലില്ല',
'nopagetext'              => 'താങ്കൾ വ്യക്തമാക്കിയ ലക്ഷ്യതാൾ നിലവിലില്ല.',
'pager-newer-n'           => '{{PLURAL:$1|പുതിയ 1|പുതിയ $1}}',
'pager-older-n'           => '{{PLURAL:$1|പഴയ 1|പഴയ $1}}',
'suppress'                => 'മേൽനോട്ടം',

# Book sources
'booksources'               => 'പുസ്തക സ്രോതസ്സുകൾ',
'booksources-search-legend' => 'പുസ്തകസ്രോതസ്സുകൾക്കായി തിരയുക',
'booksources-isbn'          => 'ഐ.എസ്.ബി.എൻ.:',
'booksources-go'            => 'പോകൂ',
'booksources-text'          => 'പുതിയതും ഉപയോഗിച്ചതുമായ പുസ്തകങ്ങൾ വിൽക്കുന്ന സൈറ്റുകളിലേക്കുള്ള ലിങ്കുകളുടെ പട്ടിക ആണ്‌ താഴെ. താങ്കൾ തിരയുന്ന പുസ്തകത്തെ പറ്റിയുള്ള കൂടുതൽ വിവരങ്ങൾ ഈ പട്ടികയിൽ നിന്നു ലഭിച്ചേക്കാം:',
'booksources-invalid-isbn'  => 'തന്നിരിക്കുന്ന ഐ.എസ്.ബി.എൻ. സാധുവാണെന്നു തോന്നുന്നില്ല; യഥാർത്ഥ സ്രോതസ്സിൽ നിന്നും പകർത്തിയപ്പോൾ തെറ്റുപറ്റിയോ എന്നു പരിശോധിക്കുക',

# Special:Log
'specialloguserlabel'  => 'ഉപയോക്താവ്:',
'speciallogtitlelabel' => 'ശീർഷകം:',
'log'                  => 'പ്രവർത്തന രേഖകൾ',
'all-logs-page'        => 'എല്ലാ പൊതുരേഖകളും',
'alllogstext'          => '{{SITENAME}} സംരംഭത്തിൽ ലഭ്യമായ പ്രവർത്തന രേഖകൾ സംയുക്തമായി ഈ താളിൽ കാണാം. താങ്കൾക്ക് രേഖകളുടെ സ്വഭാവം, ഉപയോക്തൃനാമം(കേസ് സെൻസിറ്റീവ്), ബന്ധപ്പെട്ട താൾ (കേസ് സെൻസിറ്റീവ്) മുതലായവ തിരഞ്ഞെടുത്ത് അന്വേഷണം കൂടുതൽ ക്ഌപ്തപ്പെടുത്താവുന്നതാണ്.',
'logempty'             => 'ചേർച്ചയുള്ള ഇനങ്ങളൊന്നും പ്രവർത്തനരേഖയിൽ ഇല്ല.',
'log-title-wildcard'   => 'ഈ വാക്കിൽ തുടങ്ങുന്ന തിരച്ചിൽ ഫലങ്ങൾ',

# Special:AllPages
'allpages'          => 'എല്ലാ താളുകളും',
'alphaindexline'    => '$1 മുതൽ $2 വരെ',
'nextpage'          => 'അടുത്ത താൾ ($1)',
'prevpage'          => 'മുൻപത്തെ താൾ ($1)',
'allpagesfrom'      => 'താളുകളുടെ തുടക്കം:',
'allpagesto'        => 'ഇതിൽ അവസാനിക്കുന്ന താളുകൾ കാട്ടുക:',
'allarticles'       => 'എല്ലാ താളുകളും',
'allinnamespace'    => 'എല്ലാ താളുകളും ($1 നാമമേഖല)',
'allnotinnamespace' => 'എല്ലാ താളുകളും ($1 നാമമേഖലയിലല്ലാത്തത്)',
'allpagesprev'      => 'മുമ്പത്തെ',
'allpagesnext'      => 'അടുത്തത്',
'allpagessubmit'    => 'പോകൂ',
'allpagesprefix'    => 'പൂർവ്വപദമുള്ള താളുകൾ പ്രദർശിപ്പിക്കുക:',
'allpagesbadtitle'  => 'താളിനു നൽകിയ തലക്കെട്ട് അസാധുവാണ്‌ അല്ലെങ്കിൽ അന്തർ‌ഭാഷയ്ക്കുള്ളതോ അന്തർ‌വിക്കിയ്ക്കുള്ളതോ ആയ പൂർ‌വ്വപദം ഉപയോഗിച്ചിരിക്കുന്നു.
തലക്കെട്ടിൽ ഉപയോഗിക്കാൻ പാടില്ലാത്ത ഒന്നോ അതിലധികമോ ലിപികൾ ഇതിലുണ്ടാകാം.',
'allpages-bad-ns'   => '{{SITENAME}} സംരംഭത്തിൽ "$1" എന്ന നാമമേഖല നിലവിലില്ല.',

# Special:Categories
'categories'                    => 'വർഗ്ഗങ്ങൾ',
'categoriespagetext'            => 'താഴെ കൊടുത്തിരിക്കുന്ന {{PLURAL:$1|വർഗ്ഗത്തിൽ|വർഗ്ഗങ്ങളിൽ}} താളുകളും പ്രമാണങ്ങളുമുണ്ട്. [[Special:UnusedCategories|ഉപയോഗിക്കപ്പെടാത്ത വർഗ്ഗങ്ങൾ]] ഇവിടെ കാണിക്കുന്നില്ല. [[Special:WantedCategories|അവശ്യവർഗ്ഗങ്ങൾ]] കൂടി കാണുക.',
'categoriesfrom'                => 'ഇങ്ങനെ തുടങ്ങുന്ന വർഗ്ഗങ്ങൾ കാട്ടുക:',
'special-categories-sort-count' => 'എണ്ണത്തിനനുസരിച്ച് ക്രമപ്പെടുത്തുക',
'special-categories-sort-abc'   => 'അക്ഷരമാലാക്രമത്തിൽ ക്രമീകരിക്കുക',

# Special:DeletedContributions
'deletedcontributions'             => 'മായ്ക്കപ്പെട്ട ഉപയോക്തൃസംഭാവനകൾ',
'deletedcontributions-title'       => 'മായ്ക്കപ്പെട്ട ഉപയോക്തൃസംഭാവനകൾ',
'sp-deletedcontributions-contribs' => 'സം‌ഭാവനകൾ',

# Special:LinkSearch
'linksearch'       => 'വെബ്ബ് കണ്ണികൾ',
'linksearch-pat'   => 'തിരച്ചിലിന്റെ മാതൃക:',
'linksearch-ns'    => 'നാമമേഖല:',
'linksearch-ok'    => 'തിരയൂ',
'linksearch-text'  => '"*.wikipedia.org" പോലുള്ള വൈൽഡ് കാർഡുകൾ ഉപയോഗിക്കാവുന്നതാണ്‌.<br />
പിന്താങ്ങുന്ന പ്രോട്ടോക്കോളുകൾ: <tt>$1</tt>',
'linksearch-line'  => '$1,  $2ൽ നിന്നു കണ്ണി ചേർക്കപ്പെട്ടിരിക്കുന്നു.',
'linksearch-error' => 'ഹോസ്റ്റ്നെയിമിന്റെ തുടക്കത്തിൽ മാത്രമേ വൈൽഡ് കാർഡുകൾ വരാവൂ.',

# Special:ListUsers
'listusersfrom'      => 'ഇങ്ങനെ തുടങ്ങുന്ന ഉപയോക്താക്കളെ പ്രദർശിപ്പിക്കുക:',
'listusers-submit'   => 'പ്രദർശിപ്പിക്കുക',
'listusers-noresult' => 'ഈ സംഘത്തിൽ ഉൾപ്പെടുന്ന ഉപയോക്താക്കൾ ആരും ഇല്ല.',
'listusers-blocked'  => '(തടയപ്പെട്ടത്)',

# Special:ActiveUsers
'activeusers'            => 'സജീവ ഉപയോക്താക്കളുടെ പട്ടിക',
'activeusers-intro'      => 'ഇത് കഴിഞ്ഞ {{PLURAL:$1|ദിവസം|$1 ദിവസങ്ങളിൽ}} ഏതെങ്കിലും വിധത്തിലുള്ള പ്രവർത്തനങ്ങൾ ചെയ്ത ഉപയോക്താക്കളുടെ പട്ടികയാണ്.',
'activeusers-count'      => 'അവസാനത്തെ {{PLURAL:$3|ഒരു ദിവസം|$3 ദിവസങ്ങളിൽ}} നടത്തിയ {{PLURAL:$1|ഒരു തിരുത്തൽ|$1 തിരുത്തലുകൾ}}',
'activeusers-from'       => 'ഇങ്ങനെ തുടങ്ങുന്ന ഉപയോക്താക്കളെ കാട്ടുക:',
'activeusers-hidebots'   => 'യന്ത്രങ്ങളെ മറയ്ക്കുക',
'activeusers-hidesysops' => 'കാര്യനിർവാഹകരെ മറയ്ക്കുക',
'activeusers-noresult'   => 'ഉപയോക്താക്കളില്ല',

# Special:Log/newusers
'newuserlogpage'              => 'ഉപയോക്തൃ സൃഷ്ടിയുടെ രേഖ',
'newuserlogpagetext'          => 'പുതിയതായി അംഗത്വമെടുത്ത ഉപയോക്താക്കളുടെ പട്ടിക താഴെ കാണാം.',
'newuserlog-byemail'          => 'രഹസ്യവാക്ക് ഇമെയിൽ വഴി അയച്ചിരിക്കുന്നു',
'newuserlog-create-entry'     => 'പുതിയ ഉപയോക്താവ്',
'newuserlog-create2-entry'    => 'പുതിയ അംഗത്വം $1 സൃഷ്ടിച്ചിരിക്കുന്നു',
'newuserlog-autocreate-entry' => 'യാന്ത്രികമായി സൃഷ്ടിക്കപ്പെട്ട അംഗത്വം',

# Special:ListGroupRights
'listgrouprights'                      => 'ഉപയോക്തൃവിഭാഗത്തിന്റെ അവകാശങ്ങൾ',
'listgrouprights-summary'              => 'ഈ വിക്കിയിൽ നിർവ്വചിക്കപ്പെട്ടിരിക്കുന്ന ഉപയോക്തൃസംഘങ്ങളെയും, ആ സംഘങ്ങൾക്ക് പ്രാപ്തമായിട്ടുള്ള അവകാശങ്ങളേയും താഴെ കുറിച്ചിരിക്കുന്നു.
വ്യക്തിപരമായ അവകാശങ്ങളെ കുറിച്ച് [[{{MediaWiki:Listgrouprights-helppage}}|കൂടുതൽ വിവരങ്ങൾ]] ഉണ്ടാകാനിടയുണ്ട്.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">അവകാശം നൽകിയിരിക്കുന്നു</span>
* <span class="listgrouprights-revoked">അവകാശം നീക്കിയിരിക്കുന്നു</span>',
'listgrouprights-group'                => 'വിഭാഗം',
'listgrouprights-rights'               => 'അവകാശങ്ങൾ',
'listgrouprights-helppage'             => 'Help:സംഘാവകാശങ്ങൾ',
'listgrouprights-members'              => '(അംഗങ്ങളുടെ പട്ടിക)',
'listgrouprights-addgroup'             => '{{PLURAL:$2|സംഘം|സംഘങ്ങൾ}} ചേർക്കുക: $1',
'listgrouprights-removegroup'          => '{{PLURAL:$2|സംഘം|സംഘങ്ങൾ}} നീക്കംചെയ്യുക: $1',
'listgrouprights-addgroup-all'         => 'എല്ലാ സംഘങ്ങളേയും ചേർക്കുക',
'listgrouprights-removegroup-all'      => 'എല്ലാ സംഘങ്ങളേയും നീക്കം ചെയ്യുക',
'listgrouprights-addgroup-self'        => 'സ്വന്തം അംഗത്വത്തിലേയ്ക്ക് {{PLURAL:$2|സംഘത്തെ|സംഘങ്ങളെ}} ചേർക്കുക: $1',
'listgrouprights-removegroup-self'     => 'സ്വന്തം അംഗത്വത്തിൽ നിന്ന് {{PLURAL:$2|സംഘത്തെ|സംഘങ്ങളെ}} നീക്കം ചെയ്യുക: $1',
'listgrouprights-addgroup-self-all'    => 'എല്ലാ സംഘങ്ങളേയും സ്വന്തം അംഗത്വത്തിൽ ചേർക്കുക',
'listgrouprights-removegroup-self-all' => 'സ്വന്തം അംഗത്വത്തിൽ നിന്ന് എല്ലാ സംഘങ്ങളേയും നീക്കംചെയ്യുക',

# E-mail user
'mailnologin'          => 'അയയ്ക്കാനുള്ള വിലാസം ലഭ്യമല്ല',
'mailnologintext'      => 'മറ്റ് ഉപയോക്താക്കൾക്കു ഇമെയിലയക്കുവാൻ താങ്കൾ [[Special:UserLogin|ലോഗിൻ]] ചെയ്തിരിക്കുകയും, സാധുവായ ഒരു ഇമെയിൽ വിലാസം താങ്കളുടെ [[Special:Preferences|ക്രമീകരണങ്ങൾ]] താളിൽ സജ്ജീകരിച്ചിരിക്കുകയും വേണം.',
'emailuser'            => 'ഈ ഉപയോക്താവിനു ഇമെയിൽ അയക്കുക',
'emailpage'            => 'ഉപയോക്താവിന് ഇമെയിൽ അയക്കുക',
'emailpagetext'        => 'താഴെ കാണുന്ന ഫോം മറ്റൊരു ഉപയോക്താവിന്‌ ഇമെയിൽ അയക്കാൻ ഉപയോഗിക്കാവുന്നതാണ്.
[[Special:Preferences|ഉപയോക്താവിന്റെ ക്രമീകരണങ്ങളിൽ]] കൊടുത്തിട്ടുള്ള ഇമെയിൽ വിലാസം "ദാതാവ്" ആയി വരുന്നതാണ്‌, അതുകൊണ്ട് സ്വീകർത്താവിന്‌ താങ്കൾക്ക് നേരിട്ട് മറുപടി അയക്കാൻ കഴിയും.',
'usermailererror'      => 'മെയിലുണ്ടായ പിഴവ് തിരിച്ചയച്ചിരിക്കുന്നു:',
'defemailsubject'      => '{{SITENAME}} സം‌രംഭത്തിൽ നിന്നുള്ള ഇമെയിൽ',
'usermaildisabled'     => 'ഉപയോക്തൃ ഇമെയിൽ പ്രവർത്തനരഹിതമാക്കിയിരിക്കുന്നു',
'usermaildisabledtext' => 'ഈ വിക്കിയിലെ മറ്റുപയോക്താക്കൾക്ക് ഇമെയിൽ അയയ്ക്കാൻ താങ്കൾക്ക് കഴിയില്ല',
'noemailtitle'         => 'ഇമെയിൽ വിലാസം ഇല്ല',
'noemailtext'          => 'ഈ ഉപയോക്താവ് സാധുവായ ഇമെയിൽ വിലാസം നൽകിയിട്ടില്ല.',
'nowikiemailtitle'     => 'ഇമെയിൽ അനുവദിക്കപ്പെട്ടിട്ടില്ല',
'nowikiemailtext'      => 'ഈ ഉപയോക്താവ് മറ്റുള്ളവരിൽ നിന്നും ഇമെയിൽ സ്വീകരിക്കുന്നത് ഒഴിവാക്കിയിരിക്കുന്നു.',
'email-legend'         => 'മറ്റൊരു {{SITENAME}} ഉപയോക്താവിനു ഇമെയിൽ അയയ്ക്കുക',
'emailfrom'            => 'ദാതാവ്:',
'emailto'              => 'സ്വീകർത്താവ്:',
'emailsubject'         => 'വിഷയം:',
'emailmessage'         => 'സന്ദേശം:',
'emailsend'            => 'അയക്കൂ',
'emailccme'            => 'ഇമെയിലിന്റെ പകർപ്പ് എനിക്കും അയക്കുക.',
'emailccsubject'       => '$1 എന്ന ഉപയോക്താവിനയച്ച സന്ദേശത്തിന്റെ പകർപ്പ്: $2',
'emailsent'            => 'ഇമെയിൽ അയച്ചിരിക്കുന്നു',
'emailsenttext'        => 'താങ്കളുടെ ഇമെയിൽ അയച്ചു കഴിഞ്ഞിരിക്കുന്നു.',
'emailuserfooter'      => 'ഈ ഇമെയിൽ {{SITENAME}} സംരംഭത്തിലെ "ഉപയോക്താവിന്‌ ഇമെയിൽ അയയ്ക്കുക" എന്ന സൌകര്യം ഉപയോഗിച്ച് $1 എന്ന ഉപയോക്താവ് $2 എന്ന ഉപയോക്താവിന് അയച്ചതാണ്.',

# User Messenger
'usermessage-summary' => 'വ്യവസ്ഥാസന്ദേശം ഉപേക്ഷിക്കുക.',
'usermessage-editor'  => 'വ്യവസ്ഥാസന്ദേശകൻ',

# Watchlist
'watchlist'            => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക',
'mywatchlist'          => 'ഞാൻ ശ്രദ്ധിക്കുന്നവ',
'watchlistfor'         => "(ഉപയോക്താവ് '''$1''')",
'nowatchlist'          => 'താങ്കൾ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിൽ ഇനങ്ങളൊന്നുമില്ല.',
'watchlistanontext'    => 'താങ്കൾ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക കാണുവാനോ തിരുത്തുവാനോ $1 ചെയ്യുക.',
'watchnologin'         => 'ലോഗിൻ ചെയ്തിട്ടില്ല',
'watchnologintext'     => 'ശ്രദ്ധിക്കുന്ന താളിന്റെ പട്ടിക തിരുത്തുവാൻ താങ്കൾ [[Special:UserLogin|ലോഗിൻ]] ചെയ്തിരിക്കണം.',
'addedwatch'           => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിലേക്കു ചേർത്തിരിക്കുന്നു',
'addedwatchtext'       => "\"[[:\$1]]\" എന്ന ഈ താൾ താങ്കൾ [[Special:Watchlist|ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിലേക്കു]] ചേർത്തിരിക്കുന്നു. ഇനിമുതൽ ഈ താളിലും ബന്ധപ്പെട്ട സം‌വാദംതാളിലും ഉണ്ടാകുന്ന മാറ്റങ്ങൾ ഈ പട്ടികയിൽ ദൃശ്യമാവും. കൂടാതെ [[Special:RecentChanges|പുതിയ മാറ്റങ്ങൾ]] താളിൽ ഈ താളുകളിലെ മാറ്റങ്ങൾ താങ്കൾക്ക് എളുപ്പത്തിൽ തിരിച്ചറിയാൻ '''കടുപ്പത്തിൽ''' കാണിക്കുകയും ചെയ്യും.

ഇനി എപ്പോഴെങ്കിലും പ്രസ്തുത താൾ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിൽ നിന്നു നീക്കം ചെയ്യണമെങ്കിൽ, താളിന്റെ മുകളിലെ വരിയിൽ കാണുന്ന \"മാറ്റങ്ങൾ അവഗണിക്കുക\" എന്ന ടാബിൽ ഞെക്കിയാൽ മതിയാകും.",
'removedwatch'         => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിൽ നിന്നും നീക്കിയിരിക്കുന്നു',
'removedwatchtext'     => '"[[:$1]]" എന്ന താൾ താങ്കൾ [[Special:Watchlist|ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിൽ]] നിന്നും നീക്കം ചെയ്തിരിക്കുന്നു.',
'watch'                => 'മാറ്റങ്ങൾ ശ്രദ്ധിക്കുക',
'watchthispage'        => 'ഈ താൾ ശ്രദ്ധിക്കുക',
'unwatch'              => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിൽ നിന്നു മാറ്റുക',
'unwatchthispage'      => 'ശ്രദ്ധിക്കുന്നത് അവസാനിപ്പിക്കുക',
'notanarticle'         => 'ലേഖന താൾ അല്ല',
'notvisiblerev'        => 'മറ്റൊരു ഉപയോക്താവ് സൃഷ്ടിച്ച അവസാനത്തെ നാൾപ്പതിപ്പ് മായ്ച്ചിരിക്കുന്നു',
'watchnochange'        => 'താങ്കൾ ശ്രദ്ധിക്കുന്ന താളുകൾ ഒന്നും തന്നെ ഇക്കാലയളവിൽ തിരുത്തപ്പെട്ടിട്ടില്ല.',
'watchlist-details'    => 'സം‌വാദം താളുകൾ അല്ലാത്ത {{PLURAL:$1|$1 താൾ|$1 താളുകൾ}} ശ്രദ്ധിക്കുന്ന താളിന്റെ പട്ടികയിലുണ്ട്.',
'wlheader-enotif'      => '* ഇമെയിൽ‌ വിജ്ഞാപനം സാധ്യമാക്കിയിരിക്കുന്നു.',
'wlheader-showupdated' => "* താങ്കളുടെ അവസാന സന്ദർശനത്തിനു ശേഷം തിരുത്തപ്പെട്ട താളുകൾ  '''കടുപ്പിച്ച്''' കാണിച്ചിരിക്കുന്നു",
'watchmethod-recent'   => 'ശ്രദ്ധിക്കുന്ന താളുകൾക്കുവേണ്ടി പുതിയ മാറ്റങ്ങൾ പരിശോധിക്കുന്നു',
'watchmethod-list'     => 'ശ്രദ്ധിക്കുന്ന താളുകളിലെ പുതിയ മാറ്റങ്ങൾ പരിശോധിക്കുന്നു',
'watchlistcontains'    => 'താങ്കൾ {{PLURAL:$1|താൾ|താളുകൾ}} ശ്രദ്ധിക്കുന്നുണ്ട്.',
'iteminvalidname'      => "ഇനം '$1' ൽ പിഴവ്, അസാധുവായ പേര്‌‍...",
'wlnote'               => "കഴിഞ്ഞ {{PLURAL:$2|മണിക്കൂറിൽ|'''$2''' മണിക്കൂറിൽ}} നടന്ന {{PLURAL:$1|ഒരു പുതിയ മാറ്റം|'''$1''' പുതിയ മാറ്റങ്ങൾ}} താഴെ പ്രദർശിപ്പിച്ചിരിക്കുന്നു.",
'wlshowlast'           => 'ഒടുവിലത്തെ $1 മണിക്കൂറുകൾ $2 ദിനങ്ങൾ, $3 കാട്ടുക',
'watchlist-options'    => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ സജ്ജീകരണങ്ങൾ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ശ്രദ്ധിക്കുന്നു...',
'unwatching' => 'അവഗണിക്കുന്നു...',

'enotif_mailer'                => '{{SITENAME}} വിജ്ഞാപന മെയിലർ',
'enotif_reset'                 => 'എല്ലാ താളുകളും സന്ദർശിച്ചതായി രേഖപ്പെടുത്തുക',
'enotif_newpagetext'           => 'ഇതൊരു പുതിയ താളാണ്‌',
'enotif_impersonal_salutation' => '{{SITENAME}} ഉപയോക്താവ്',
'changed'                      => 'മാറ്റിയിരിക്കുന്നു',
'created'                      => 'സൃഷ്ടിച്ചു',
'enotif_subject'               => '{{SITENAME}} സംരംഭത്തിലെ $PAGETITLE എന്ന താൾ $PAGEEDITOR $CHANGEDORCREATED',
'enotif_lastvisited'           => 'താങ്കളുടെ അവസാന സന്ദർശനത്തിനു ശേഷമുണ്ടായ മാറ്റങ്ങൾ കാണുവാൻ  $1 സന്ദർശിക്കുക.',
'enotif_lastdiff'              => 'ഈ മാറ്റം ദർശിക്കാൻ $1 കാണുക.',
'enotif_anon_editor'           => 'അജ്ഞാത ഉപയോക്താവ് $1',
'enotif_body'                  => 'പ്രിയ $WATCHINGUSERNAME,


{{SITENAME}} സം‌രംഭത്തിലെ $PAGETITLE താൾ $PAGEEDITDATE-ൽ $PAGEEDITOR എന്ന ഉപയോക്താവ് $CHANGEDORCREATED, ഇപ്പോഴുള്ള പതിപ്പിനായി $PAGETITLE_URL കാണുക.

$NEWPAGE

തിരുത്തിയയാൾ നൽകിയ സം‌ഗ്രഹം: $PAGESUMMARY $PAGEMINOREDIT

തിരുത്തിയയാളെ ബന്ധപ്പെടുക:
മെയിൽ: $PAGEEDITOR_EMAIL
വിക്കി: $PAGEEDITOR_WIKI

താങ്കൾ ഈ താൾ സന്ദർശിക്കുന്നില്ലങ്കിൽ മറ്റ് അറിയിപ്പുകൾ ഒന്നുമുണ്ടാകുന്നതല്ല.
ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക സന്ദർശിച്ചും ഉൾപ്പെട്ട താളുകളിലെ അറിയിപ്പ് മുദ്രകൾ താങ്കൾക്ക് പുനഃക്രമീകരിക്കാവുന്നതാണ്‌.
             താങ്കളുടെ {{SITENAME}} സുഹൃദ് അറിയിപ്പ് സജ്ജീകരണം
         
--
ശ്രദ്ധിക്കുന്ന പട്ടികയിലെ ക്രമീകരണങ്ങളിൽ മാറ്റം വരുത്താൻ, സന്ദർശിക്കുക
{{fullurl:{{#special:Watchlist}}/edit}}

താൾ താങ്കൾ ശ്രദ്ധിക്കുന്നവയുടെ പട്ടികയിൽ നിന്ന് നീക്കംചെയ്യാൻ, സന്ദർശിക്കുക
$UNWATCHURL

അഭിപ്രായം അറിയിക്കാനും മറ്റു സഹായങ്ങൾക്കും:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'താൾ മായ്ക്കുക',
'confirm'                => 'സ്ഥിരീകരിക്കുക',
'excontent'              => "ഉള്ളടക്കം: '$1'",
'excontentauthor'        => "ഉള്ളടക്കം: '$1' ('[[Special:Contributions/$2|$2]]' മാത്രമേ ഈ താളിൽ തിരുത്തൽ നടത്തിയിട്ടുള്ളൂ)",
'exbeforeblank'          => "ശൂന്യമാക്കപ്പെടുന്നതിനു മുമ്പുള്ള ഉള്ളടക്കം: '$1'",
'exblank'                => 'താൾ ശൂന്യമായിരുന്നു',
'delete-confirm'         => '"$1" മായ്ക്കുക',
'delete-legend'          => 'മായ്ക്കുക',
'historywarning'         => "'''മുന്നറിയിപ്പ്''': താങ്കൾ മായ്ക്കുവാൻ പോകുന്ന താളിനു ഏകദേശം {{PLURAL:$1|ഒരു നാൾപ്പതിപ്പ്|$1 നാൾപ്പതിപ്പുകൾ}} ഉള്ള നാൾവഴി ഉണ്ട്:",
'confirmdeletetext'      => 'താങ്കൾ ഒരു താൾ അതിന്റെ തിരുത്തൽ ചരിത്രമടക്കം മായ്ക്കുവാൻ പോവുകയാണ്. താങ്കൾ ചെയ്യുന്നതിന്റെ പരിണതഫലം താങ്കൾക്കറിയാമെന്നും, താങ്കളുടെ ഈ മായ്ക്കൽ [[{{MediaWiki:Policy-url}}|വിക്കിയുടെ നയം]] അനുസരിച്ചാണു ചെയ്യുന്നതെന്നും ഉറപ്പാക്കുക.',
'actioncomplete'         => 'പ്രവൃത്തി പൂർത്തിയായിരിക്കുന്നു',
'actionfailed'           => 'പ്രവൃത്തി പരാജയപ്പെട്ടിരിക്കുന്നു',
'deletedtext'            => '"<nowiki>$1</nowiki>" മായ്ച്ചിരിക്കുന്നു. പുതിയതായി നടന്ന മായ്ക്കലുകളുടെ വിവരങ്ങൾ $2 ഉപയോഗിച്ച് കാണാം.',
'deletedarticle'         => '"[[$1]]" മായ്ച്ചിരിക്കുന്നു',
'suppressedarticle'      => '"[[$1]]" ഒതുക്കിയിരിക്കുന്നു',
'dellogpage'             => 'മായ്ക്കൽ രേഖ',
'dellogpagetext'         => 'സമീപകാലത്ത് മായ്ക്കപ്പെട്ട താളുകളുടെ പട്ടിക താഴെ കാണാം.',
'deletionlog'            => 'മായ്ക്കൽ രേഖ',
'reverted'               => 'പൂർ‌വ്വസ്ഥിതിയിലേക്കാക്കിയിരിക്കുന്നു.',
'deletecomment'          => 'നീക്കം ചെയ്യാനുള്ള കാരണം',
'deleteotherreason'      => 'മറ്റ്/കൂടുതൽ കാരണങ്ങൾ:',
'deletereasonotherlist'  => 'മറ്റു കാരണങ്ങൾ',
'deletereason-dropdown'  => '*മായ്ക്കാനുള്ള സാധാരണ കാരണങ്ങൾ
** സ്രഷ്ടാവ് ആവശ്യപ്പെട്ടതു പ്രകാരം
** പകർപ്പവകാശ ലംഘനം
** നശീകരണ പ്രവർത്തനം',
'delete-edit-reasonlist' => 'മായ്ക്കലിന്റെ കാരണം തിരുത്തുക',
'delete-toobig'          => 'ഈ താളിനു വളരെ വിപുലമായ തിരുത്തൽ ചരിത്രമുണ്ട്. $1 മേൽ {{PLURAL:$1|പതിപ്പുണ്ട്|പതിപ്പുകളുണ്ട്}}. ഇത്തരം താളുകൾ മായ്ക്കുന്നതു {{SITENAME}} സം‌രംഭത്തിന്റെ നിലനില്പ്പിനെ തന്നെ ബാധിക്കുമെന്നതിനാൽ ഈ താൾ മായ്ക്കുന്നതിനുള്ള അവകാശം പരിമിതപ്പെടുത്തിയിരിക്കുന്നു.',
'delete-warning-toobig'  => 'ഈ താളിനു വളരെ വിപുലമായ തിരുത്തൽ ചരിത്രമുണ്ട്. അതായത്, ഇതിനു് $1 മേൽ {{PLURAL:$1|പതിപ്പുണ്ട്|പതിപ്പുകളുണ്ട്}}. ഇത്തരം താളുകൾ മായ്ക്കുന്നതു {{SITENAME}} സം‌രംഭത്തിന്റെ ഡാറ്റാബേസ് ഓപ്പറേഷനെ ബാധിച്ചേക്കാം. അതിനാൽ വളരെ ശ്രദ്ധാപൂർവ്വം തുടർനടപടികളിലേക്കു നീങ്ങുക.',

# Rollback
'rollback'          => 'തിരുത്തലുകൾ റോൾബാക്ക് ചെയ്യുക',
'rollback_short'    => 'റോൾബാക്ക്',
'rollbacklink'      => 'റോൾബാക്ക്',
'rollbackfailed'    => 'റോൾബാക്ക് പരാജയപ്പെട്ടു',
'cantrollback'      => 'റോൾ ബാക്ക് ചെയ്യുവാൻ സാദ്ധ്യമല്ല. ഒരു ഉപയോക്താവ് മാത്രമാണു ഈ താളിൽ സം‌ഭാവന ചെയ്തിരിക്കുന്നത്.',
'alreadyrolled'     => '[[:$1]] എന്ന താളിൽ [[User:$2|$2]] ([[User talk:$2|Talk]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) നടത്തിയ തിരുത്തലുകൾ മുൻപ്രാപനം ചെയ്യാൻ സാധിക്കുന്നതല്ല. മറ്റാരോ താൾ തിരുത്തുകയോ മുൻപ്രാപനം ചെയ്യുകയോ ചെയ്തിരിക്കുന്നു.

താളിലെ അവസാന തിരുത്തൽ ചെയ്തിരിക്കുന്നത് [[User:$3|$3]] ([[User talk:$3|Talk]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) ആണ്.',
'editcomment'       => "തിരുത്തലിന്റെ ചുരുക്കം: \"''\$1''\" എന്നായിരുന്നു.",
'revertpage'        => '[[Special:Contributions/$2|$2]] ([[User talk:$2|സംവാദം]]) നടത്തിയ തിരുത്തലുകൾ നീക്കം ചെയ്തിരിക്കുന്നു; നിലവിലുള്ള പതിപ്പ് [[User:$1|$1]] സൃഷ്ടിച്ചതാണ്',
'revertpage-nouser' => '(ഉപയോക്തൃനാമം നീക്കിയിരിക്കുന്നു) നടത്തിയ തിരുത്തലുകൾ [[User:$1|$1]] സൃഷ്ടിച്ച അവസാന പതിപ്പിലേയ്ക്ക് മുൻപ്രാപനം ചെയ്തിരിക്കുന്നു',
'rollback-success'  => '$1 ചെയ്ത തിരുത്തൽ തിരസ്ക്കരിച്ചിരിക്കുന്നു; $2 ചെയ്ത തൊട്ടു മുൻപത്തെ പതിപ്പിലേക്ക് സേവ് ചെയ്യുന്നു.',

# Edit tokens
'sessionfailure-title' => 'സെഷൻ പരാജയപ്പെട്ടിരിക്കുന്നു',
'sessionfailure'       => 'താങ്കളുടെ ലോഗിൻ സെഷനിൽ പ്രശ്നങ്ങളുള്ളതായി കാണുന്നു;
സെഷൻ തട്ടിയെടുക്കൽ ഒഴിവാക്കാനുള്ള മുൻകരുതലായി ഈ പ്രവൃത്തി റദ്ദാക്കിയിരിക്കുന്നു.
ദയവായി പിന്നോട്ട് പോയി താങ്കൾ വന്ന താളിൽ ചെന്ന്, വീണ്ടും ശ്രമിക്കുക.',

# Protect
'protectlogpage'              => 'സംരക്ഷണ പ്രവർത്തനരേഖ',
'protectlogtext'              => 'താഴെ താളുകൾ സംരക്ഷിച്ചതിന്റേയും സംരക്ഷണം നീക്കിയതിന്റേയും പട്ടിക നൽകിയിരിക്കുന്നു.
ഇപ്പോൾ നിലവിലുള്ള എല്ലാ താൾ സംരക്ഷണവും കാണാൻ [[Special:ProtectedPages|സംരക്ഷിക്കപ്പെട്ട താളുകളുടെ പട്ടിക]] കാണുക.',
'protectedarticle'            => '"[[$1]]" സം‌രക്ഷിച്ചിരിക്കുന്നു',
'modifiedarticleprotection'   => '"[[$1]]" എന്ന താളിനുള്ള സം‌രക്ഷണമാനം മാറ്റിയിരിക്കുന്നു',
'unprotectedarticle'          => '"[[$1]]" സ്വതന്ത്രമാക്കി',
'movedarticleprotection'      => 'സംരക്ഷണ അളവുകൾ "[[$2]]" എന്നതിൽ നിന്ന് "[[$1]]" എന്നതിലേയ്ക്ക് മാറ്റിയിരിക്കുന്നു',
'protect-title'               => '"$1" നു സം‌രക്ഷണമാനം സജ്ജീകരിക്കുന്നു',
'prot_1movedto2'              => '[[$1]] എന്ന താളിന്റെ പേർ [[$2]] എന്നാക്കിയിരിക്കുന്നു',
'protect-legend'              => 'സം‌രക്ഷണം സ്ഥിരീകരിക്കുക',
'protectcomment'              => 'കാരണം:',
'protectexpiry'               => 'സംരക്ഷണ കാലാവധി:',
'protect_expiry_invalid'      => 'കാലാവധി തീരുന്ന സമയം അസാധുവാണ്.',
'protect_expiry_old'          => 'കാലവധി തീരുന്ന സമയം ഭൂതകാലത്തിലാണ്.',
'protect-unchain-permissions' => 'മറ്റ് സംരക്ഷണ ഐച്ഛികങ്ങൾ തുറക്കുക',
'protect-text'                => "താങ്കൾക്ക് ഇവിടെ '''<nowiki>$1</nowiki>''' എന്ന താളിന്റെ നിലവിലുള്ള സംരക്ഷണമാനം ദർശിക്കുകയും അതിൽ മാറ്റംവരുത്തുകയും ചെയ്യാം.",
'protect-locked-blocked'      => "തടയപ്പെട്ടിരിക്കുന്ന സമയത്ത് താങ്കൾക്ക് സം‌രക്ഷണ പരിധി മാറ്റുവാൻ സാധിക്കില്ല. '''$1''' എന്ന താളിന്റെ നിലവിലുള്ള ക്രമീകരണം ഇതാണ്‌:",
'protect-locked-dblock'       => "ഡാറ്റാബേസ് ബന്ധിച്ചിരിക്കുന്നതു കാരണം താങ്കൾക്കു സം‌രക്ഷണമാനം മാറ്റുവാൻ സാധിക്കില്ല.

'''$1''' എന്ന താളിന്റെ നിലവിലുള്ള ക്രമീകരണം ഇതാണ്‌:",
'protect-locked-access'       => "താളുകളുടെ സംരക്ഷണമാനത്തിൽ വ്യതിയാനം വരുത്തുവാനുള്ള അനുമതി താങ്കളുടെ അംഗത്വത്തിനില്ല.
'''$1''' എന്ന താളിന്റെ നിലവിലുള്ള ക്രമീകരണങ്ങൾ ഇതാ:",
'protect-cascadeon'           => 'ഈ താൾ നിർഝരിതസംരക്ഷിതമായ (cascading protection) {{PLURAL:$1|ഒരു താളിൽ|പല താളുകളിൽ}} ഉൾപ്പെടുത്തപ്പെടുത്തപ്പെട്ടിരിക്കുന്നതിനാൽ ഇത് സംരക്ഷിത താളാണ്. എന്നാൽ താങ്കൾക്ക് ഈ താളിന്റെ സംരക്ഷണമാനം മാറ്റുവാൻ കഴിയും, അങ്ങനെ ചെയ്താൽ നിർഝരിതസംരക്ഷണത്തിനു മാറ്റം വരികയില്ല.',
'protect-default'             => 'എല്ലാ ഉപയോക്താക്കളെയും അനുവദിക്കുക',
'protect-fallback'            => '"$1" അനുവാദം ആവശ്യമാണ്‌',
'protect-level-autoconfirmed' => 'അംഗത്വമെടുക്കാത്ത ഉപയോക്താക്കളെ തടയുക',
'protect-level-sysop'         => 'സിസോപ്പുകൾ മാത്രം',
'protect-summary-cascade'     => 'നിർഝരിതം',
'protect-expiring'            => 'കാലാവധി തീരുന്നത് - $1 (UTC)',
'protect-expiry-indefinite'   => 'അനിശ്ചിതകാലം',
'protect-cascade'             => 'ഈ താളിൽ ഉൾപ്പെട്ടിരിക്കുന്ന താളുകളെല്ലാം സംരക്ഷിക്കുക (നിർഝരിത സംരക്ഷണം)',
'protect-cantedit'            => 'ഈ താൾ തിരുത്തുവാനുള്ള അധികാരമില്ലാത്തതിനാൽ ഈ താളിന്റെ സംരക്ഷണമാനം മാറ്റുവാൻ താങ്കൾക്ക് സാധിക്കുകയില്ല.',
'protect-othertime'           => 'മറ്റ് കാലാവധി:',
'protect-othertime-op'        => 'മറ്റു കാലയളവ്',
'protect-existing-expiry'     => 'നിലവിലെ കാലാവധി: $3, $2',
'protect-otherreason'         => 'മറ്റുള്ള/പുറമേയുള്ള കാരണം:',
'protect-otherreason-op'      => 'മറ്റ് കാരണം',
'protect-dropdown'            => '*സംരക്ഷിക്കാനുള്ള കാരണങ്ങൾ
** അമിതമായ നശീകരണപ്രവർത്തനങ്ങൾ
** അമിതമായ പാഴ് എഴുത്ത് ഉൾപ്പെടുത്തൽ
** സൃഷ്ടിപരമല്ലാതെ ഭവിക്കുന്ന തിരുത്തൽ യുദ്ധം
** സന്ദർശകരുടെ എണ്ണം വളരെ കൂടുതലായ താൾ',
'protect-edit-reasonlist'     => 'സംരക്ഷണ കാരണങ്ങൾ തിരുത്തുക',
'protect-expiry-options'      => '1 മണിക്കൂർ:1 hour,1 ദിവസം:1 day,1 ആഴ്ച:1 week,2 ആഴ്ച:2 weeks,1 മാസം:1 month,3 മാസം:3 months,6 മാസം:6 months,1 വർഷം:1 year,അനന്തകാലം:infinite',
'restriction-type'            => 'അനുമതി:',
'restriction-level'           => 'പരിമിതപ്പെടുത്തലിന്റെ മാനം:',
'minimum-size'                => 'കുറഞ്ഞ വലിപ്പം',
'maximum-size'                => 'പരമാവധി വലിപ്പം',
'pagesize'                    => '(ബൈറ്റുകൾ)',

# Restrictions (nouns)
'restriction-edit'   => 'തിരുത്തുക',
'restriction-move'   => 'തലക്കെട്ടു്‌ മാറ്റുക',
'restriction-create' => 'താൾ സൃഷ്ടിക്കുക',
'restriction-upload' => 'അപ്‌ലോഡ്',

# Restriction levels
'restriction-level-sysop'         => 'പൂർണ്ണമായി സം‌രക്ഷിച്ചിരിക്കുന്നു',
'restriction-level-autoconfirmed' => 'ഭാഗികമായി സം‌രക്ഷിച്ചിരിക്കുന്നു',
'restriction-level-all'           => 'ഏതു തലവും',

# Undelete
'undelete'                   => 'നീക്കംചെയ്ത താളുകൾ കാണുക',
'undeletepage'               => 'നീക്കം ചെയ്ത താളുകൾ കാണുകയും പുനഃസ്ഥാപിക്കുകയും ചെയ്യുക',
'undeletepagetitle'          => "'''[[:$1|$1]] - എന്ന താളിന്റെ നീക്കം ചെയ്ത പതിപ്പുകളാണ് താഴെക്കൊടുത്തിരിക്കുന്നത്'''.",
'viewdeletedpage'            => 'നീക്കം ചെയ്ത താളുകൾ കാണുക',
'undeletepagetext'           => 'താഴെ കാണിച്ചിരിക്കുന്ന {{PLURAL:$1|താൾ|$1 താളുകൾ}} മായ്ക്കപ്പെട്ടതാണെങ്കിലും പത്തായത്തിലുള്ളതിനാൽ പുനഃസ്ഥാപിക്കാവുന്നതാണ്‌. പത്തായം സമയാസമയങ്ങളിൽ വൃത്തിയാക്കാനിടയുണ്ട്.',
'undelete-fieldset-title'    => 'നാൾപ്പതിപ്പുകൾ പുനഃസ്ഥാപിക്കുക',
'undeleteextrahelp'          => "താളിന്റെ മുഴുവൻ നാൾവഴിയും പുനഃസ്ഥാപിക്കാൻ എല്ലാ ചെക്ക്ബോക്സുകളും ശരിയിടാതെ വിട്ടശേഷം '''''പുനഃസ്ഥാപിക്കുക''''' എന്നത് ഞെക്കുക.
തിരഞ്ഞെടുത്തവ പുനഃസ്ഥാപിക്കാൻ, പുനഃസ്ഥാപിക്കേണ്ട നാൾപ്പതിപ്പിനുള്ള ചെക്ക്ബോക്സിൽ ശരിയിട്ടശേഷം '''''പുനഃസ്ഥാപിക്കുക''''' എന്നത് ഞെക്കുക.
'''''പുനഃക്രമീകരിക്കുക''''' എന്നതു ഞെക്കിയാൽ കുറിപ്പിടാനുള്ള പെട്ടിയും എല്ലാ ചെക്ക്ബോക്സുകളും ശൂന്യമാക്കുന്നതാണ്.",
'undeleterevisions'          => '$1 {{PLURAL:$1|പതിപ്പ്|പതിപ്പുകൾ}} പത്തായത്തിലാക്കി',
'undeletehistory'            => 'താങ്കൾ താൾ പുനഃസ്ഥാപിച്ചാൽ, എല്ലാ നാൾപ്പതിപ്പുകളും നാൾവഴിയിൽ പുനഃസ്ഥാപിക്കപ്പെടും.
മായ്ക്കലിനു ശേഷം പുതിയൊരു താൾ അതേ പേരിൽ സൃഷ്ടിക്കപ്പെട്ടിട്ടുണ്ടെങ്കിൽ, പുനഃസ്ഥാപിക്കപ്പെട്ട പതിപ്പുകൾ നാൾവഴിയിൽ പഴയവയായി പ്രത്യക്ഷപ്പെടുന്നതാണ്.',
'undeleterevdel'             => 'ഏറ്റവും ഉന്നത സ്ഥിതിയിലുള്ള താളോ പ്രമാണത്തിന്റെ നാൾപ്പതിപ്പോ ഭാഗികമായി മായ്ക്കപ്പെടുമെന്നതിനാൽ മായ്ക്കൽ പുനഃസ്ഥാപിക്കൽ നടത്താൻ കഴിയില്ല.
ഇത്തരം സന്ദർഭങ്ങളിൽ, താങ്കൾ ഏറ്റവും പുതിയ മായ്ക്കപ്പെട്ട നാൾപ്പതിപ്പുകൾ തിരഞ്ഞെടുക്കാതിരിക്കുകയോ മറയ്ക്കാതിരിക്കുകയോ ചെയ്യേണ്ടതാണ്.',
'undeletehistorynoadmin'     => 'ഈ താൾ മായ്ക്കപ്പെട്ടിരിക്കുന്നു. ഈ താൾ മായ്കാനുള്ള കാരണവും താൾ മായ്ക്കുന്നതിനു മുൻപ് തിരുത്തിയവരെ കുറിച്ചുള്ള വിവരങ്ങളും, താഴെ കൊടുത്തിരിക്കുന്നു. മായ്ക്കപ്പെട്ട ഈ പതിപ്പുകളുടെ ഉള്ളടക്കം അഡ്മിനിസ്റ്റ്രേറ്ററുമാർക്ക് മാത്രമേ പ്രാപ്യമാകൂ.',
'undelete-revision'          => '$1 താളിന്റെ ($4, $5 -ലെ) $3 സൃഷ്ടിച്ച പതിപ്പ് മായ്ച്ചിരിക്കുന്നു:',
'undeleterevision-missing'   => 'അസാധുവായ അല്ലെങ്കിൽ നഷ്ടപ്പെട്ട പതിപ്പ്. താങ്കളുടെ കണ്ണി ഒന്നുകിൽ തെറ്റായായിരിക്കാം അല്ലെങ്കിൽ ഒഴിവാക്കപ്പെട്ട ഒരു പതിപ്പായിരിക്കും താങ്കൾ തിരയുന്നത്.',
'undelete-nodiff'            => 'പഴയ പതിപ്പുകൾ ഒന്നും കണ്ടില്ല.',
'undeletebtn'                => 'പുനഃസ്ഥാപിക്കുക',
'undeletelink'               => 'കാണുക/പുനഃസ്ഥാപിക്കുക',
'undeleteviewlink'           => 'കാണുക',
'undeletereset'              => 'പുനഃക്രമീകരിക്കുക',
'undeleteinvert'             => 'വിപരീതം തിരഞ്ഞെടുക്കുക',
'undeletecomment'            => 'കാരണം:',
'undeletedarticle'           => '"[[$1]]" പുനഃസ്ഥാപിച്ചു',
'undeletedrevisions'         => '{{PLURAL:$1|1 പതിപ്പ്|$1 പതിപ്പുകൾ}} പുനഃസ്ഥാപിച്ചിരിക്കുന്നു',
'undeletedrevisions-files'   => '{{PLURAL:$1|1 പതിപ്പും|$1 പതിപ്പുകളും}} {{PLURAL:$2|1 പ്രമാണവും|$2 പ്രമാണങ്ങളും}} പുനഃസ്ഥാപിച്ചിരിക്കുന്നു',
'undeletedfiles'             => '{{PLURAL:$1|1 പ്രമാണം|$1 പ്രമാണങ്ങൾ}} പുനഃസ്ഥാപിച്ചു',
'cannotundelete'             => 'മായ്ക്കൽ തിരസ്ക്കരിക്കാനുള്ള ശ്രമം പരാജയപ്പെട്ടു. മറ്റാരെങ്കിലും ഇതിനു മുൻപ് മായ്ക്കൽ തിരസ്ക്കരിച്ചിരിക്കാം.',
'undeletedpage'              => "'''$1 പുനഃസ്ഥാപിച്ചിരിക്കുന്നു'''

പുതിയതായി നടന്ന ഒഴിവാക്കലുകളുടേയും പുനഃസ്ഥാപനങ്ങളുടേയും വിവരങ്ങൾ കാണാൻ [[Special:Log/delete|മായ്ക്കൽ ലോഗ്]] കാണുക.",
'undelete-header'            => 'അടുത്തകാലത്ത് നീക്കംചെയ്ത താളുകളുടെ പട്ടികയ്ക്ക് [[Special:Log/delete|നീക്കം ചെയ്യൽ പ്രവർത്തനരേഖ]] കാണുക.',
'undelete-search-box'        => 'നീക്കംചെയ്ത താളുകളിൽ തിരയുക',
'undelete-search-prefix'     => 'ഈ വാക്കിൽ തുടങ്ങുന്ന താളുകൾ കാണിക്കുക:',
'undelete-search-submit'     => 'തിരയൂ',
'undelete-filename-mismatch' => '$1 എന്ന സമയത്തുണ്ടാക്കിയ പതിപ്പിന്റെ മായ്ക്കുൽ തിരസ്ക്കരിക്കുവാൻ സാധിച്ചില്ല: പ്രമാണത്തിന്റെ പേരു യോജിക്കുന്നില്ല',
'undelete-bad-store-key'     => '$1 സമയത്തുണ്ടാക്കിയ പതിപ്പിന്റെ മായ്ക്കുൽ തിരസ്ക്കരിക്കുവാൻ സാധിച്ചില്ല: മായ്ക്കുന്നതിനു മുൻപേ പ്രമാണം അപ്രത്യക്ഷമായിരിക്കുന്നു.',
'undelete-cleanup-error'     => 'ഉപയോഗത്തിലില്ലാത്ത "$1" എന്ന പ്രമാണം മായ്ക്കുന്നതിൽ പിഴവ് സംഭവിച്ചു.',
'undelete-error-short'       => 'ഈ പ്രമാണത്തിന്റെ മായ്ക്കൽ തിരസ്ക്കരിക്കുന്നതിൽ പിഴവ്: $1',
'undelete-error-long'        => 'ഈ പ്രമാണം പുനഃസ്ഥാപിക്കുവാൻ ശ്രമിക്കുമ്പോൾ പിഴവുകൾ സംഭവിച്ചു:

$1',
'undelete-show-file-confirm' => '"<nowiki>$1</nowiki>" എന്ന പ്രമാണത്തിന്റെ $3 $2-ൽ ഉണ്ടായിരുന്ന മായ്ക്കപ്പെട്ട നാൾപ്പതിപ്പ് കാണണം എന്നുറപ്പാണോ?',
'undelete-show-file-submit'  => 'ശരി',

# Namespace form on various pages
'namespace'      => 'നാമമേഖല:',
'invert'         => 'വിപരീതം തിരഞ്ഞെടുക്കുക',
'blanknamespace' => '(മുഖ്യം)',

# Contributions
'contributions'       => 'ഉപയോക്താവിന്റെ സംഭാവനകൾ',
'contributions-title' => '$1 എന്ന ഉപയോക്താവിന്റെ സംഭാവനകൾ',
'mycontris'           => 'എന്റെ സംഭാവനകൾ',
'contribsub2'         => '$1 എന്ന ഉപയോക്താവിന്റെ $2.',
'nocontribs'          => 'ഈ മാനദണ്ഡങ്ങളുമായി യോജിക്കുന്ന മാറ്റങ്ങൾ ഒന്നും കണ്ടില്ല.',
'uctop'               => '(അവസാനത്തെ തിരുത്തൽ)',
'month'               => 'മാസം:',
'year'                => 'വർഷം:',

'sp-contributions-newbies'             => 'പുതിയ അംഗങ്ങൾ നടത്തിയ തിരുത്തലുകൾ മാത്രം',
'sp-contributions-newbies-sub'         => 'പുതിയ അംഗത്വങ്ങൾക്ക്',
'sp-contributions-newbies-title'       => 'പുതിയ അംഗത്വമെടുത്ത ഉപയോക്താക്കളുടെ സേവനങ്ങൾ',
'sp-contributions-blocklog'            => 'തടയൽ രേഖ',
'sp-contributions-deleted'             => 'മായ്ക്കപ്പെട്ട ഉപയോക്തൃസംഭാവനകൾ',
'sp-contributions-logs'                => 'പ്രവർത്തനരേഖകൾ',
'sp-contributions-talk'                => 'സംവാദം',
'sp-contributions-userrights'          => 'ഉപയോക്തൃ അവകാശങ്ങളുടെ പരിപാലനം',
'sp-contributions-blocked-notice'      => 'ഈ ഉപയോക്താവ് ഇപ്പോൾ തടയപ്പെട്ടിരിക്കുകയാണ്. അവലംബമായി തടയൽ രേഖയുടെ പുതിയ ഭാഗം താഴെ കൊടുത്തിരിക്കുന്നു:',
'sp-contributions-blocked-notice-anon' => 'ഈ ഐ.പി. വിലാസം ഇപ്പോൾ തടയപ്പെട്ടിരിക്കുകയാണ്.
അവലംബമായി തടയൽ രേഖയുടെ പുതിയഭാഗം താഴെ കൊടുത്തിരിക്കുന്നു:',
'sp-contributions-search'              => 'ചെയ്ത സേവനങ്ങൾ',
'sp-contributions-username'            => 'ഐ.പി. വിലാസം അഥവാ ഉപയോക്തൃനാമം:',
'sp-contributions-submit'              => 'തിരയൂ',

# What links here
'whatlinkshere'            => 'അനുബന്ധകണ്ണികൾ',
'whatlinkshere-title'      => '"$1" എന്ന താളിലേക്കുള്ള കണ്ണികൾ',
'whatlinkshere-page'       => 'താൾ:',
'linkshere'                => "താഴെക്കൊടുത്തിരിക്കുന്ന താളുകളിൽ നിന്നും '''[[:$1]]''' എന്ന താളിലേക്ക് കണ്ണികളുണ്ട്:",
'nolinkshere'              => "'''[[:$1]]''' എന്ന താളിലേക്ക് കണ്ണികളൊന്നും നിലവിലില്ല.",
'nolinkshere-ns'           => "തിരഞ്ഞെടുത്ത നാമമേഖലയിൽ '''[[:$1]]''' എന്ന താളിലേക്ക് മറ്റൊരു താളുകളിൽനിന്നും കണ്ണികളില്ല.",
'isredirect'               => 'തിരിച്ചുവിടൽ താൾ',
'istemplate'               => 'ഉൾപ്പെടുത്തൽ',
'isimage'                  => 'ചിത്രത്തിന്റെ കണ്ണി',
'whatlinkshere-prev'       => '{{PLURAL:$1|മുൻപത്തെത്|മുൻപത്തെ $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|അടുത്തത്|അടുത്ത $1}}',
'whatlinkshere-links'      => '← കണ്ണികൾ',
'whatlinkshere-hideredirs' => 'തിരിച്ചുവിടലുകൾ $1',
'whatlinkshere-hidetrans'  => 'ഉൾപ്പെടുത്തലുകൾ $1',
'whatlinkshere-hidelinks'  => 'കണ്ണികൾ $1',
'whatlinkshere-hideimages' => 'ചിത്രങ്ങളിൽ നിന്ന് $1 കണ്ണികൾ',
'whatlinkshere-filters'    => 'അരിപ്പകൾ',

# Block/unblock
'blockip'                         => 'ഉപയോക്താവിനെ തടയുക',
'blockip-title'                   => 'ഉപയോക്താവിനെ തടയുക',
'blockip-legend'                  => 'ഉപയോക്താവിനെ തടയുക',
'blockiptext'                     => 'ഏതെങ്കിലും ഐ.പി. വിലാസത്തേയോ ഉപയോക്താവിനേയോ തടയുവാൻ താഴെയുള്ള ഫോം ഉപയോഗിക്കുക.
[[{{MediaWiki:Policy-url}}|വിക്കിയുടെ നയം]] അനുസരിച്ച് നശീകരണപ്രവർത്തനം തടയാൻ മാത്രമേ ഇതു ചെയ്യാവൂ.
തടയാനുള്ള വ്യക്തമായ കാരണം (ഏതു താളിലാണു നശീകരണപ്രവർത്തനം നടന്നത് എന്നതടക്കം) താഴെ രേഖപ്പെടുത്തിയിരിക്കണം.',
'ipaddress'                       => 'ഐ.പി. വിലാസം:',
'ipadressorusername'              => 'ഐ.പി. വിലാസം അല്ലെങ്കിൽ ഉപയോക്തൃനാമം:',
'ipbexpiry'                       => 'കാലാവധി:',
'ipbreason'                       => 'കാരണം:',
'ipbreasonotherlist'              => 'മറ്റു കാരണം',
'ipbreason-dropdown'              => '*തടയലിനു യോഗ്യമായ കാരണങ്ങൾ
** തെറ്റായ വിവരങ്ങൾ ചേർക്കുക
** താളിൽ നിന്നു വിവരങ്ങൾ മായ്ക്കുക
** പുറം വെബ്ബ്സൈറ്റിലേക്കുള്ള പാഴ് കണ്ണികൾ ചേർക്കൽ
** അനാവശ്യം/അസംബന്ധം താളിലേക്കു ചേർക്കൽ
** മാന്യമല്ലാത്ത പെരുമാറ്റം
** ദുരുദ്ദേശത്തോടെ ഉപയോഗിക്കുന്ന നിരവധി അംഗത്വങ്ങൾ
** വിക്കിക്കു ചേരാത്ത ഉപയോക്തൃനാമം',
'ipbanononly'                     => 'അജ്ഞാത ഉപയോക്താക്കളെ മാത്രം തടയുക',
'ipbcreateaccount'                => 'അംഗത്വം സൃഷ്ടിക്കുന്നത് തടയുക',
'ipbemailban'                     => 'ഇമെയിൽ അയക്കുന്നതിൽ നിന്നു ഉപയോക്താവിനെ തടയുക',
'ipbenableautoblock'              => 'ഈ ഉപയോക്താവ് അവസാനം ഉപയോഗിച്ച ഐ.പി.യും തുടർന്ന് ഉപയോഗിക്കാൻ സാദ്ധ്യതയുള്ള ഐ.പി.കളും യാന്ത്രികമായി തടയുക',
'ipbsubmit'                       => 'ഈ ഉപയോക്താവിനെ തടയുക',
'ipbother'                        => 'മറ്റ് കാലാവധി:',
'ipboptions'                      => '2 മണിക്കൂർ നേരത്തേയ്ക്ക്:2 hours,1 ദിവസത്തേയ്ക്ക്:1 day,3 ദിവസത്തേയ്ക്ക്:3 days,1 ആഴ്ചത്തേയ്ക്ക്:1 week,2 ആഴ്ചത്തേയ്ക്ക്:2 weeks,1 മാസത്തേയ്ക്ക്:1 month,3 മാസത്തേയ്ക്ക്:3 months,6 മാസത്തേയ്ക്ക്:6 months,1 വർഷത്തേയ്ക്ക്:1 year,അനന്തകാലത്തേയ്ക്ക്:infinite',
'ipbotheroption'                  => 'മറ്റുള്ളവ',
'ipbotherreason'                  => 'മറ്റ്/കൂടുതൽ കാരണം:',
'ipbhidename'                     => 'തിരുത്തലുകൾ, പട്ടികകൾ എന്നിവയിൽ നിന്നും ഉപയോക്തൃനാമം മറയ്ക്കുക',
'ipbwatchuser'                    => 'ഈ ഉപയോക്താവിന്റെ താളും സംവാദം താളും ശ്രദ്ധിക്കുക',
'ipballowusertalk'                => 'തടയപ്പെട്ടിരിക്കുമ്പോഴും ഈ ഉപയോക്താവിനെ സ്വന്തം സംവാദം താളിൽ തിരുത്താൻ അനുവദിക്കുക',
'ipb-change-block'                => 'ഈ ക്രമീകരണപ്രകാരം ഉപയോക്താവിനെ വീണ്ടും തടയുക',
'badipaddress'                    => 'അസാധുവായ ഐ.പി. വിലാസം.',
'blockipsuccesssub'               => 'തടയൽ വിജയിച്ചിരിക്കുന്നു',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] എന്ന ഉപയോക്താവിനെ തടഞ്ഞിരിക്കുന്നു.<br />
തടയൽ പുനഃപരിശോധിക്കാൻ [[Special:IPBlockList|ഐ.പി. തടയൽ പട്ടിക]] കാണുക.',
'ipb-edit-dropdown'               => 'തടഞ്ഞതിന്റെ കാരണം തിരുത്തുക',
'ipb-unblock-addr'                => '$1 അംഗത്വത്തിനുള്ള തടയൽ നീക്കുക',
'ipb-unblock'                     => 'ഒരു ഐ.പി. വിലാസത്തിനോ ഉപയോക്താവിനോ ഉള്ള തടയൽ നീക്കംചെയ്യുക',
'ipb-blocklist-addr'              => '$1 അനുഭവിക്കുന്ന വിലക്കുകൾ',
'ipb-blocklist'                   => 'നിലവിലുള്ള തടയലുകൾ',
'ipb-blocklist-contribs'          => '$1 നൽകിയ സംഭാവനകൾ',
'unblockip'                       => 'ഉപയോക്താവിനുള്ള തടയൽ നീക്കുക',
'unblockiptext'                   => 'മുൻപ് തടയപ്പെട്ട ഐ.പി.യുടേയും ഉപയോക്താവിന്റേയും തിരുത്തൽ അവകാശം പുനഃസ്ഥാപിക്കാൻ താഴെയുള്ള ഫോം ഉപയോഗിക്കുക.',
'ipusubmit'                       => 'ഈ വിലക്ക് ഒഴിവാക്കുക',
'unblocked'                       => '[[User:$1|$1]] എന്ന ഉപയോക്താവിനുണ്ടായിരുന്ന തടയൽ നീക്കിയിരിക്കുന്നു',
'unblocked-id'                    => '$1 എന്ന തടയൽ നീക്കം ചെയ്തിരിക്കുന്നു',
'ipblocklist'                     => 'തടയപ്പെട്ട ഐ.പി. വിലാസങ്ങളും ഉപയോക്താക്കളും',
'ipblocklist-legend'              => 'തടഞ്ഞ ഒരു ഉപയോക്താവിനെ തിരയുക',
'ipblocklist-username'            => 'ഉപയോക്തൃനാമം അല്ലെങ്കിൽ ഐ.പി. വിലാസം:',
'ipblocklist-sh-userblocks'       => '$1 അംഗത്വ വിലക്കുകൾ',
'ipblocklist-sh-tempblocks'       => '$1 താൽക്കാലിക വിലക്കുകൾ',
'ipblocklist-sh-addressblocks'    => 'ഏക ഐ.പി. വിലക്കുകൾ $1',
'ipblocklist-submit'              => 'തിരയൂ',
'ipblocklist-localblock'          => 'പ്രാദേശിക തടയൽ',
'ipblocklist-otherblocks'         => 'മറ്റ് {{PLURAL:$1|തടയൽ|തടയലുകൾ}}',
'blocklistline'                   => '$1-നു, $3-യെ $2 തടഞ്ഞിരിക്കുന്നു ($4)',
'infiniteblock'                   => 'അനിശ്ചിത കാലം',
'expiringblock'                   => 'കാലാവധി തീരുന്നത് - $1 $2',
'anononlyblock'                   => 'അജ്ഞാത ഉപയോക്താക്കളെ മാത്രം',
'noautoblockblock'                => 'യാന്ത്രികതടയൽ ഒഴിവാക്കിയിരിക്കുന്നു',
'createaccountblock'              => 'അംഗത്വം സൃഷ്ടിക്കുന്നതിൽനിന്ന് തടഞ്ഞിരിക്കുന്നു',
'emailblock'                      => 'ഇമെയിൽ ഉപയോഗിക്കുന്നതു തടഞ്ഞിരിക്കുന്നു',
'blocklist-nousertalk'            => 'സ്വന്തം സം‌വാദ താളിൽ തിരുത്താൻ സാധിക്കില്ല',
'ipblocklist-empty'               => 'തടയൽ‌പ്പട്ടിക ശൂന്യമാണ്‌.',
'ipblocklist-no-results'          => 'ഈ ഐ.പി. വിലാസമോ ഉപയോക്തൃനാമമോ തടഞ്ഞിട്ടില്ല.',
'blocklink'                       => 'തടയുക',
'unblocklink'                     => 'സ്വതന്ത്രമാക്കുക',
'change-blocklink'                => 'തടയലിൽ മാറ്റം വരുത്തുക',
'contribslink'                    => 'സംഭാവനകൾ',
'autoblocker'                     => 'താങ്കളുടെ ഐ.പി. വിലാസം "[[User:$1|$1]]" എന്ന ഉപയോക്താവ് ഈ അടുത്ത് ഉപയോഗിക്കുകയും പ്രസ്തുത ഉപയോക്താവിനെ വിക്കിയിൽ നിന്നു തടയുകയും ചെയ്തിട്ടുള്ളതാണ്‌. അതിനാൽ താങ്കളും യാന്ത്രികമായി തടയപ്പെട്ടിരിക്കുന്നു. $1ന്റെ തടയലിനു സൂചിപ്പിക്കപ്പെട്ട കാരണം "$2" ആണ്‌.',
'blocklogpage'                    => 'തടയൽ രേഖ',
'blocklog-showlog'                => 'ഈ ഉപയോക്താവ് മുമ്പേ തടയപ്പെട്ടതാണ്.
തടയൽ രേഖ അവലംബമായി താഴെ കൊടുത്തിരിക്കുന്നു:',
'blocklog-showsuppresslog'        => 'ഈ ഉപയോക്താവ് മുമ്പേ തടയപ്പെടുകയും മറയ്ക്കപ്പെടുകയും ചെയ്തതാണ്.
അവലംബത്തിനായി ഒതുക്കൽ രേഖ താഴെ കൊടുത്തിരിക്കുന്നു:',
'blocklogentry'                   => '[[$1]]-നെ $2 കാലത്തേക്കു വിലക്കിയിരിക്കുന്നു $3',
'reblock-logentry'                => '[[$1]] തടയൽ നിബന്ധനകൾ മാറ്റിയിരിക്കുന്നു, തടയൽ അവസാനിക്കുന്നത് $2 $3',
'blocklogtext'                    => '{{SITENAME}} സംരംഭത്തിൽ പ്രവർത്തിക്കുന്നതിൽ നിന്ന് ഉപയോക്താക്കളെ തടഞ്ഞതിന്റേയും, പുനഃപ്രവർത്തനാനുമതി നൽകിയതിന്റേയും രേഖകൾ താഴെ കാണാം. {{SITENAME}} സംരംഭം സ്വയം  തടയുന്ന ഐ.പി. വിലാസങ്ങൾ ഈ പട്ടികയിൽ ഇല്ല. [[Special:IPBlockList|തടയപ്പെട്ടിട്ടുള്ള ഐ.പി. വിലാസങ്ങളുടെ പട്ടിക]] എന്നതാളിൽ നിലവിലുള്ള നിരോധനങ്ങളേയും തടയലുകളേയും കാണാവുന്നതാണ്.',
'unblocklogentry'                 => '$1 എന്ന ഉപയോക്താവിനെ പുനഃസ്ഥാപിച്ചിരിക്കുന്നു',
'block-log-flags-anononly'        => 'അജ്ഞാത ഉപയോക്താക്കളെ മാത്രം',
'block-log-flags-nocreate'        => 'അംഗത്വം സൃഷ്ടിക്കുന്നതും തടഞ്ഞിരിക്കുന്നു',
'block-log-flags-noautoblock'     => 'യാന്ത്രികതടയൽ സജ്ജമല്ലാതാക്കിയിരിക്കുന്നു',
'block-log-flags-noemail'         => 'ഇമെയിൽ അയയ്ക്കുന്നത് തടഞ്ഞിരിക്കുന്നു',
'block-log-flags-nousertalk'      => 'സ്വന്തം സംവാദം താളിൽ തിരുത്താനനുവാദമില്ല',
'block-log-flags-angry-autoblock' => 'മെച്ചപ്പെടുത്തിയ സ്വയംതടയൽ തയ്യാറായിരിക്കുന്നു',
'block-log-flags-hiddenname'      => 'ഉപയോക്തൃനാമം മറയ്ക്കപ്പെട്ടിരിക്കുന്നു',
'range_block_disabled'            => 'സിസോപ്പിനു റേഞ്ച് ബ്ലോക്കു ചെയ്യാനുള്ള സൗകര്യം ദുർബലപ്പെടുത്തുക.',
'ipb_expiry_invalid'              => 'കാലാവധി സമയം അസാധുവാണ്‌.',
'ipb_expiry_temp'                 => 'മറയ്ക്കപ്പെട്ട ഉപയോക്തൃനാമങ്ങളിലുള്ള തടയൽ സ്ഥിരമായിരിക്കണം.',
'ipb_hide_invalid'                => 'ഈ അംഗത്വം ഒതുക്കാൻ കഴിയില്ല; അതിന് വളരെയധികം തിരുത്തലുകൾ ഉണ്ട്.',
'ipb_already_blocked'             => '"$1" ഇതിനകം തന്നെ തടയപ്പെട്ടിരിക്കുന്നു.',
'ipb-needreblock'                 => '== നിലവിൽ തടയപ്പെട്ടതാണ് ==
$1 നിലവിൽ തടയപ്പെട്ടതാണ്.<br />
താങ്കൾ സജ്ജീകരണത്തിൽ മാറ്റം വരുത്തുവാൻ ഉദ്ദേശിക്കുന്നുണ്ടോ?',
'ipb-otherblocks-header'          => 'മറ്റ് {{PLURAL:$1|തടയൽ|തടയലുകൾ}}',
'ipb_cant_unblock'                => 'പിഴവ്: $1 എന്ന തടയൽ ഐ.ഡി. കാണുന്നില്ല. ഇതിനകം അതിന്റെ തടയൽ നീക്കം ചെയ്തിരിക്കാം.',
'ipb_blocked_as_range'            => 'പിഴവ്:  $1 എന്ന ഐ.പി.യെ നേരിട്ടല്ല തടഞ്ഞിട്ടുള്ളത്. അതിനാൽ തടയൽ നീക്കം ചെയ്യുവാൻ സാദ്ധ്യമല്ല. അതിനെ $2ന്റെ ഭാഗമായുള്ള റേഞ്ചിൽ ആണ്‌ തടഞ്ഞിട്ടുള്ളത്. അത് ഒഴിവാക്കാവുന്നതാണ്.',
'ip_range_invalid'                => 'അസാധുവായ ഐ.പി. റേഞ്ച്.',
'ip_range_toolarge'               => 'പരിധി നിശ്ചയിച്ചുള്ള തടയലുകൾ /$1 എന്നതിലും കൂടുതലാകാൻ അനുവദിക്കുന്നില്ല.',
'blockme'                         => 'എന്നെ തടയുക',
'proxyblocker'                    => 'പ്രോക്സി തടയൽ',
'proxyblocker-disabled'           => 'ഈ പ്രക്രിയ അനുവദനീയമല്ല.',
'proxyblockreason'                => 'ഓപ്പൺ പ്രോക്സി ആയതിനാൽ താങ്കളുടെ ഐ.പി. വിലാസത്തെ തടഞ്ഞിരിക്കുന്നു. ഇതു എന്തെങ്കിലും പിഴവ് മൂലം സംഭവിച്ചതാണെങ്കിൽ താങ്കളുടെ ഇന്റർനെറ്റ് സേവന ദാതാവിനെ സമീപിച്ചു ഈ സുരക്ഷാ പ്രശ്നത്തെ കുറിച്ച് ബോധിപ്പിക്കുക.',
'proxyblocksuccess'               => 'ചെയ്തു കഴിഞ്ഞു.',
'sorbsreason'                     => '{{SITENAME}} ഉപയോഗിക്കുന്ന DNSBL ൽ താങ്കളുടെ ഐ.പി. വിലാസം ഒരു ഓപ്പൺ പ്രോക്സിയായാണു രേഖപ്പെടുത്തിട്ടുള്ളത്.',
'sorbs_create_account_reason'     => '{{SITENAME}} ഉപയോഗിക്കുന്ന DNSBL ൽ താങ്കളുടെ ഐ.പി. വിലാസം ഒരു ഓപ്പൺ പ്രോക്സിയായാണു രേഖപ്പെടുത്തിട്ടുള്ളത്. താങ്കൾക്ക് അംഗത്വമെടുക്കാൻ സാദ്ധ്യമല്ല.',
'cant-block-while-blocked'        => 'താങ്കൾ തടയപ്പെട്ടിരിക്കുമ്പോൾ മറ്റുപയോക്താക്കളെ തടയാൻ താങ്കൾക്ക് സാധിക്കില്ല.',
'cant-see-hidden-user'            => 'താങ്കൾ തടയാൻ ശ്രമിക്കുന്ന ഉപയോക്താവ് മുമ്പേ തടയപ്പെടുകയും മറയ്ക്കപ്പെടുകയും ചെയ്യപ്പെട്ടതാണ്. താങ്കൾക്ക് ഉപയോക്താവിനെ മറയ്ക്കാനുള്ള അവകാശം ഇല്ലെങ്കിൽ, ഉപയോക്താവിനെതിരെ ഉള്ള തടയൽ കാണാനോ തിരുത്താനോ കഴിയുന്നതല്ല.',
'ipbblocked'                      => 'മറ്റുള്ളവരെ തടയാനോ അവരുടെ തടയൽ നീക്കാനോ താങ്കൾക്ക് കഴിയില്ല. കാരണം താങ്കൾ തന്നെ തടയപ്പെട്ടിരിക്കുകയാണ്',
'ipbnounblockself'                => 'താങ്കൾക്ക് സ്വന്തം തടയൽ നീക്കാൻ അനുമതിയില്ല',

# Developer tools
'lockdb'              => 'ഡാറ്റാബേസ് ബന്ധിക്കുക',
'unlockdb'            => 'ഡാറ്റാബേസ് സ്വതന്ത്രമാക്കുക',
'lockdbtext'          => 'ഡേറ്റാബേസ് പൂട്ടുന്നതു താളുകൾ തിരുത്തുവാനും, ക്രമീകരണങ്ങൾ മാറ്റുവാനും, ശ്രദ്ധിക്കുന്നവയുടെ പട്ടിക തിരുത്തുവാനും, ഡേറ്റാബേസിൽ മാറ്റങ്ങൾ വേണ്ട മറ്റു കാര്യങ്ങൾ ചെയ്യുവാനുമുള്ള ഉപയോക്താക്കളുടെ സൗകര്യം റദ്ദാക്കുന്നതാണ്.
ഇതാണ് താങ്കൾ ചെയ്യാനാഗ്രഹിക്കുന്നതെന്നും പരിപാലനം കഴിയുമ്പോൾ ഡേറ്റാബേസ് തുറന്നു കൊടുക്കുമെന്നും ദയവായി സ്ഥിരീകരിക്കുക.',
'unlockdbtext'        => 'ഡേറ്റാബേസിന്റെ പൂട്ടഴിക്കുന്നത് താളുകൾ തിരുത്തുവാനും, ക്രമീകരണങ്ങൾ മാറ്റുവാനും, ശ്രദ്ധിക്കുന്നവയുടെ പട്ടിക തിരുത്തുവാനും, ഡേറ്റാബേസിൽ മാറ്റങ്ങൾ വേണ്ട മറ്റു കാര്യങ്ങൾ ചെയ്യുവാനുമുള്ള ഉപയോക്താക്കളുടെ സൗകര്യം പുനഃസ്ഥാപിക്കുന്നതാണ്.
ഇതാണ് താങ്കൾ ചെയ്യാനാഗ്രഹിക്കുന്നതെന്ന് ദയവായി സ്ഥിരീകരിക്കുക.',
'lockconfirm'         => 'അതെ എനിക്കു തീർച്ചയായും ഡാറ്റബേസിനെ ബന്ധിക്കണം.',
'unlockconfirm'       => 'അതെ എനിക്കു തീർച്ചയായും ഡാറ്റാബേസിനെ സ്വതന്ത്രമാക്കണം.',
'lockbtn'             => 'ഡാറ്റാബേസ് ബന്ധിക്കുക',
'unlockbtn'           => 'ഡാറ്റാബേസ് സ്വതന്ത്രമാക്കുക',
'locknoconfirm'       => 'താങ്കൾ സ്ഥിരീകരണ പെട്ടി തിരഞ്ഞെടുത്തില്ല.',
'lockdbsuccesssub'    => 'ഡാറ്റാബേസ് ബന്ധിക്കുവാൻ സാധിച്ചില്ല',
'unlockdbsuccesssub'  => 'ഡാറ്റാബേസ് സ്വതന്ത്രമാക്കിയിരിക്കുന്നു',
'lockdbsuccesstext'   => 'ഡാറ്റാബേസ് ബന്ധിച്ചിരിക്കുന്നു.<br />
ശുദ്ധീകരണപ്രവർത്തനം കഴിഞ്ഞതിനു ശേഷം [[Special:UnlockDB|ഈ കണ്ണിയുപയോഗിച്ച്]] ഡാറ്റാബേസ് സ്വതന്ത്രമാക്കുക.',
'unlockdbsuccesstext' => 'ഡാറ്റാബേസ് സ്വതന്ത്രമാക്കിയിരിക്കുന്നു.',
'lockfilenotwritable' => 'ഡേറ്റാബേസ് പൂട്ടൽ പ്രമാണത്തിൽ മാറ്റങ്ങൾ വരുത്താൻ കഴിഞ്ഞില്ല.
ഡേറ്റാബേസ് പൂട്ടാനും തുറക്കാനും, ഇതിൽ വെബ് സെർവർ ഉപയോഗിച്ച് മാറ്റങ്ങൾ വരുത്താൻ കഴിയണം.',
'databasenotlocked'   => 'ഡാറ്റാബേസ് ബന്ധിച്ചിട്ടില്ല.',

# Move page
'move-page'                    => '$1 മാറ്റുക',
'move-page-legend'             => 'താൾ മാറ്റുക',
'movepagetext'                 => "താഴെയുള്ള ഫോം ഒരു താളിനെ പുനർനാമകരണം ചെയ്യാനുള്ളതാണ്.
താളിന്റെ പഴയരൂപങ്ങളും ഈ മാറ്റത്തിന് വിധേയമാക്കപ്പെടും.
പഴയ തലക്കെട്ട്, പുതിയ തലക്കെട്ടുള്ള താളിലേക്കുള്ള ഒരു തിരിച്ചുവിടൽ താളായി മാറും.
പഴയതാളിലേക്കുള്ള ലിങ്കുകൾ ഈ മാറ്റത്തിൽ മാറുകയില്ല.
[[Special:DoubleRedirects|ഇരട്ട തിരിച്ചുവിടലുകളോ]], [[Special:BrokenRedirects|ഫലപ്രദമല്ലാത്ത തിരിച്ചുവിടലുകളോ]] ഉണ്ടാകുന്നുണ്ടോയെന്ന് ദയവായി പരിശോധിക്കുക.
ലിങ്കുകൾ ശരിയായി പ്രവർത്തിക്കുന്നുണ്ടോ എന്ന് പരിശോധിച്ച് ഉറപ്പു വരുത്തേണ്ടത് താങ്കളുടെ ചുമതലയാണ്. 

താങ്കൾ പുതിയതായി ഉദ്ദേശിക്കുന്ന തലക്കെട്ടിൽ ഒരു താൾ നേരത്തേ നിലവിലുണ്ടെങ്കിൽ '''പുനർനാമകരണം സാധിക്കില്ല'''.
അല്ലെങ്കിൽ അതൊരു തിരിച്ചുവിടൽ താളോ, ശൂന്യമായ താളോ അതിനു മറ്റു പഴയരൂപങ്ങൾ ഇല്ലാതിരിക്കുകയോ ചെയ്യണം.
അതായത് താങ്കൾ ഒരു താൾ തെറ്റായി പുനർനാമകരണം ചെയ്താൽ മാത്രമേ അതിനേ തിരിച്ചാക്കാൻ സാധിക്കുകയുള്ളു.
നിലവിലുള്ള ഒരു താളിന്റെ മുകളിൽ അതേ തലക്കെട്ടിൽ മറ്റൊരു താളുണ്ടാക്കാൻ സാധിക്കില്ല.

'''മുന്നറിയിപ്പ്!:'''
ഈ പ്രവൃത്തി ഒരു നല്ലതാളിൽ അപ്രതീക്ഷിതവും, ഉഗ്രവുമായി തീർന്നേക്കാം.
മുന്നോട്ടു പോകുന്നതിനു മുമ്പ് താങ്കൾ ചെയ്യുന്നതെന്താണെന്ന് വ്യക്തമായി മനസ്സിലാക്കുക.",
'movepagetalktext'             => "'''ബന്ധപ്പെട്ട സം‌വാദം താളും സ്വയം മാറ്റപ്പെടാതിരിക്കാനുള്ള കാരണങ്ങൾ'''
*അതേ പേരിൽ തന്നെ ശൂന്യമല്ലാത്ത ഒരു സം‌വാദം താൾ നിലവിലുണ്ടെങ്കിൽ
*താങ്കൾ താഴെയുള്ള ചെക്‍ബോക്സ് ഉപയോഗിച്ചിട്ടില്ലങ്കിൽ

അത്തരം സന്ദർഭങ്ങളിൽ സം‌വാദം താളുകൾ താങ്കൾ സ്വയം കൂട്ടിച്ചേർക്കേണ്ടതാണ്.",
'movearticle'                  => 'മാറ്റേണ്ട താൾ',
'moveuserpage-warning'         => "'''മുന്നറിയിപ്പ്:''' ഉപയോക്താവിനുള്ള താളാണ് താങ്കൾ മാറ്റാൻ പോകുന്നത്. താൾ മാത്രമേ മാറുകയുള്ളു എന്നും ഉപയോക്താവിന്റെ പേര് ''മാറുകയില്ലെന്നും'' ദയവായി ഓർക്കുക.",
'movenologin'                  => 'ലോഗിൻ ചെയ്തിട്ടില്ല',
'movenologintext'              => 'തലക്കെട്ടു മാറ്റാനുള്ള അനുമതി കൈവരിക്കാൻ താങ്കൾ ഒരു രജിസ്റ്റേർഡ് ഉപയോക്താവായിരിക്കുകയും [[Special:UserLogin|ലോഗിൻ ചെയ്തിരിക്കുകയും]] ചെയ്യേണ്ടത് അത്യന്താപേക്ഷിതമാണ്‌.',
'movenotallowed'               => 'താളുകളുടെ തലക്കെട്ടു മാറ്റുവാനുള്ള അനുവാദം താങ്കൾക്കില്ല.',
'movenotallowedfile'           => 'പ്രമാണങ്ങൾ മാറ്റാനുള്ള അനുമതി താങ്കൾക്കില്ല.',
'cant-move-user-page'          => 'ഉപയോക്താവിനുള്ള താളുകളുടെ തലക്കെട്ട് മാറ്റാനുള്ള അനുമതി താങ്കൾക്കില്ല (ഉപതാളുകൾ ഉൾപ്പെടുന്നില്ല).',
'cant-move-to-user-page'       => 'ഉപയോക്താവിനുള്ള താളിന്റെ തലക്കെട്ടു മാറ്റാനുള്ള അനുമതി താങ്കൾക്കില്ല (ഉപയോക്താവിനുള്ള ഉപതാളുകൾ ഒഴിച്ച്).',
'newtitle'                     => 'പുതിയ തലക്കെട്ട്',
'move-watch'                   => 'ഈ താളിലെ മാറ്റങ്ങൾ ശ്രദ്ധിക്കുക',
'movepagebtn'                  => 'താൾ മാറ്റുക',
'pagemovedsub'                 => 'തലക്കെട്ടു മാറ്റം വിജയിച്ചിരിക്കുന്നു',
'movepage-moved'               => '\'\'\'"$1" എന്ന ലേഖനം "$2" എന്ന തലക്കെട്ടിലേക്ക് മാറ്റിയിരിക്കുന്നു\'\'\'',
'movepage-moved-redirect'      => 'ഒരു തിരിച്ചുവിടൽ സൃഷ്ടിച്ചിരിക്കുന്നു.',
'movepage-moved-noredirect'    => 'തിരിച്ചുവിടലിന്റെ സൃഷ്ടി ഒതുക്കിയിരിക്കുന്നു.',
'articleexists'                => 'ഈ പേരിൽ മറ്റൊരു താൾ ഉള്ളതായി കാണുന്നു, അല്ലെങ്കിൽ താങ്കൾ തിരഞ്ഞെടുത്ത തലക്കെട്ട് സ്വീകാര്യമല്ല. ദയവായി മറ്റൊരു തലക്കെട്ട് തിരഞ്ഞെടുക്കുക.',
'cantmove-titleprotected'      => 'താൾ സൃഷ്ടിക്കുന്നതിനു നിരോധനം ഏർപ്പെടുത്തിയിട്ടുള്ള ഒരു തലക്കെട്ടു താങ്കൾ തിരഞ്ഞെടുത്ത കാരണം താങ്കൾക്ക് താൾ ആ സ്ഥാനത്തേക്കു മാറ്റുവാൻ സാധിക്കില്ല.',
'talkexists'                   => "'''താളിന്റെ തലക്കെട്ട് വിജയകരമായി മാറ്റിയിരിക്കുന്നു. പക്ഷെ താളിന്റെ സംവാദത്തിനു അതേ പേരിൽ മറ്റൊരു സംവാദംതാൾ നിലവിലുള്ളതിനാൽ മാറ്റം സാധിച്ചില്ല. അതിനാൽ സംവാദംതാൾ താങ്കൾ തന്നെ സംയോജിപ്പിക്കുക.'''",
'movedto'                      => 'ഇവിടേക്ക് മാറ്റിയിരിക്കുന്നു',
'movetalk'                     => 'ബന്ധപ്പെട്ട സം‌വാദംതാളും കൂടെ നീക്കുക',
'move-subpages'                => 'ഉപതാളുകൾ  മാറ്റുക ( $1 വരെ)',
'move-talk-subpages'           => 'സംവാദം താളിന്റെ ഉപതാളുകൾ മാറ്റുക ($1 എണ്ണം)',
'movepage-page-exists'         => '$1 എന്ന താൾ നിലനിൽക്കുന്നുണ്ട്, അതിനു മുകളിൽ സൃഷ്ടിക്കാൻ സ്വതവേ കഴിയില്ല.',
'movepage-page-moved'          => '$1 എന്ന താൾ $2 എന്നു മാറ്റിയിരിക്കുന്നു.',
'movepage-page-unmoved'        => '$1 എന്ന താൾ $2 എന്നു മാറ്റാൻ സാദ്ധ്യമല്ല.',
'movepage-max-pages'           => 'പരമാവധി സാധ്യമായ {{PLURAL:$1|ഒരു താൾ|$1 താളുകൾ}} മാറ്റിയിരിക്കുന്നു, അതിൽ കൂടുതൽ മാറ്റാൻ സ്വതവേ സാധ്യമല്ല.',
'1movedto2'                    => 'തലക്കെട്ടു മാറ്റം:  [[$1]]  >>> [[$2]]',
'1movedto2_redir'              => 'നിലവിലുണ്ടായിരുന്ന തിരിച്ചുവിടൽ താളിലേക്ക് തലക്കെട്ടു മാറ്റം: [[$1]] >>>  [[$2]]',
'move-redirect-suppressed'     => 'തിരിച്ചുവിടൽ ഒതുക്കിയിരിക്കുന്നു',
'movelogpage'                  => 'മാറ്റങ്ങളുടെ രേഖ',
'movelogpagetext'              => 'തലക്കെട്ട് മാറ്റിയ താളുകളുടെ പട്ടിക താഴെ കാണാം.',
'movesubpage'                  => '{{PLURAL:$1|ഉപതാൾ|ഉപതാളുകൾ}}',
'movesubpagetext'              => 'ഈ താളിനുള്ള {{PLURAL:$1|ഒരു ഉപതാൾ|$1 ഉപതാളുകൾ}} താഴെ കൊടുത്തിരിക്കുന്നു.',
'movenosubpage'                => 'ഈ താളിന്‌ ഉപതാളുകൾ ഇല്ല',
'movereason'                   => 'കാരണം:',
'revertmove'                   => 'പൂർവ്വസ്ഥിതിയിലാക്കുക',
'delete_and_move'              => 'മായ്ക്കുകയും മാറ്റുകയും ചെയ്യുക',
'delete_and_move_text'         => '==താൾ മായ്ക്കേണ്ടിയിരിക്കുന്നു==

താങ്കൾ സൃഷ്ടിക്കാൻ ശ്രമിച്ച "[[:$1]]" എന്ന താൾ നിലവിലുണ്ട്. ആ താൾ മായ്ച്ച് പുതിയ തലക്കെട്ട് നൽകേണ്ടതുണ്ടോ?',
'delete_and_move_confirm'      => 'ശരി, താൾ നീക്കം ചെയ്യുക',
'delete_and_move_reason'       => 'താൾ മാറ്റാനായി മായ്ച്ചു',
'selfmove'                     => 'സ്രോതസ്സിന്റെ തലക്കെട്ടും ലക്ഷ്യത്തിന്റെ തലക്കെട്ടും ഒന്നാണ്‌. അതിനാൽ തലക്കെട്ടുമാറ്റം സാദ്ധ്യമല്ല.',
'immobile-source-namespace'    => '"$1" നാമമേഖലയിലെ താളുകൾ മാറ്റാൻ കഴിയില്ല',
'immobile-target-namespace'    => '"$1" നാമമേഖലയിലേയ്ക്ക് താളുകൾ മാറ്റാൻ കഴിയില്ല',
'immobile-target-namespace-iw' => 'അന്തർവിക്കി കണ്ണി താൾ മാറ്റാനുള്ള സാധുവായ ലക്ഷ്യമല്ല.',
'immobile-source-page'         => 'ഈ താൾ മാറ്റാൻ സാദ്ധ്യമല്ല',
'immobile-target-page'         => 'ലക്ഷ്യമാക്കിയ തലക്കെട്ടിലേക്ക് മാറ്റാൻ സാധിക്കില്ല.',
'imagenocrossnamespace'        => 'പ്രമാണം അതിനായി അല്ലാത്ത നാമമേഖലയിലേയ്ക്ക് മാറ്റാൻ കഴിയില്ല',
'imagetypemismatch'            => 'പുതിയ പ്രമാണത്തിന്റെ എക്സ്റ്റെൻഷൻ അതിന്റെ തരവുമായി ഒത്തുപോകുന്നില്ല.',
'imageinvalidfilename'         => 'പ്രമാണത്തിനു ലക്ഷ്യമിട്ട പേര് അസാധുവാണ്',
'fix-double-redirects'         => 'പഴയ തലക്കെട്ടിലേക്കുള്ള തിരിച്ചുവിടൽ താളുകളും ഇതോടൊപ്പം പുതുക്കുക',
'move-leave-redirect'          => 'പിന്നിൽ ഒരു തിരിച്ചുവിടൽ നിലനിർത്തുക',
'protectedpagemovewarning'     => "'''മുന്നറിയിപ്പ്:'''  കാര്യനിർവാഹക പദവിയുള്ളവർക്കു മാത്രം മാറ്റാൻ കഴിയുന്ന വിധത്തിൽ ഈ താൾ സംരക്ഷിക്കപ്പെട്ടിരിക്കുന്നു. അവലംബമായി രേഖകളിലെ ഏറ്റവും പുതിയ വിവരം താഴെ നൽകിയിരിക്കുന്നു:",
'semiprotectedpagemovewarning' => "'''കുറിപ്പ്:''' അംഗത്വമെടുത്ത ഉപയോക്താക്കൾക്കു മാത്രം മാറ്റാൻ കഴിയുന്ന വിധത്തിൽ ഈ താൾ സംരക്ഷിക്കപ്പെട്ടിരിക്കുന്നു. അവലംബമായി രേഖകളിലെ ഏറ്റവും പുതിയ വിവരം താഴെ കൊടുത്തിരിക്കുന്നു:",
'move-over-sharedrepo'         => '==പ്രമാണം നിലനിൽക്കുന്നുണ്ട്==
പങ്ക് ‌‌വെച്ചുപയോഗിക്കുന്ന ശേഖരണിയൊന്നിൽ [[:$1]] നിലനിൽക്കുന്നു. ഈ തലക്കെട്ടിലേയ്ക്ക് ഒരു പ്രമാണത്തെ മാറ്റുന്നത് പങ്ക് വെച്ചുപയോഗിക്കുന്ന പ്രമാണത്തെ അതിലംഘിക്കുന്നതാണ്.',
'file-exists-sharedrepo'       => 'താങ്കൾ തിരഞ്ഞെടുത്ത പ്രമാണ നാമം പങ്ക് വെയ്ക്കപ്പെട്ടുപയോഗിക്കുന്ന റെപ്പോസിറ്ററിയിൽ ഉപയോഗിക്കുന്നു.
ദയവായി മറ്റൊരു നാമം സ്വീകരിക്കുക.',

# Export
'export'            => 'താളുകൾ കയറ്റുമതി ചെയ്യുക',
'exporttext'        => 'ഒരു പ്രത്യേക താളിന്റേയോ താളുകളുടെ ഗണത്തിലേയോ എഴുത്തും നാൾവഴിയും എക്സ്.എം.എല്ലിൽ പൊതിഞ്ഞ് താങ്കൾക്ക് കയറ്റുമതി ചെയ്യാവുന്നതാണ്.
ഇത് മീഡിയവിക്കി ഉപയോഗിച്ചുള്ള മറ്റൊരു വിക്കിയിൽ [[Special:Import|ഇറക്കുമതി താൾ]] ഉപയോഗിച്ച് ഇറക്കുമതി ചെയ്യാവുന്നതാണ്.

താളുകൾ കയറ്റുമതി ചെയ്യാൻ, താഴെ കൊടുത്തിരിക്കുന്ന പെട്ടിയിൽ തലക്കെട്ടുകൾ, ഒരു വരിയിൽ ഒന്നു വീതം നൽകി, എല്ലാ നാൾപ്പതിപ്പും വേണോ അവസാനത്തെ തിരുത്തലിന്റെ വിവരങ്ങൾ ഉൾപ്പെടെയുള്ള ഇപ്പോഴുള്ള പതിപ്പ് മാത്രം മതിയോ എന്ന് തിരഞ്ഞെടുത്ത് നൽകുക.

പിന്നീട് ഇതിനായി താങ്കൾക്ക് ഒരു കണ്ണി, ഉദാഹരണത്തിന് "[[{{MediaWiki:Mainpage}}]]" എന്ന താളിന് [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]], ഉപയോഗിക്കാവുന്നതാണ്.',
'exportcuronly'     => 'നിലവിലുള്ള പതിപ്പ് മാത്രം ചേർക്കുക, പൂർണ്ണ തിരുത്തൽ ചരിത്രം വേണ്ട',
'exportnohistory'   => "----
'''കുറിപ്പ്:''' താളിന്റെ മുഴുവൻ നാൾവഴിയും ഈ ഫോം ഉപയോഗിച്ച് കയറ്റുമതി ചെയ്യുന്ന പ്രവർത്തനമികവിനെ ബാധിക്കുന്ന പ്രശ്നങ്ങളാൽ അനുവദിക്കുന്നില്ല.",
'export-submit'     => 'കയറ്റുമതി ചെയ്യുക',
'export-addcattext' => 'വർഗ്ഗത്തിൽനിന്നും താളുകൾ ചേർക്കുക:',
'export-addcat'     => 'ചേർക്കുക',
'export-addnstext'  => 'നാമമേഖലയിൽ നിന്നും താളുകൾ ചേർക്കുക:',
'export-addns'      => 'ചേർക്കുക',
'export-download'   => 'ഒരു പ്രമാണമാക്കി സൂക്ഷിക്കുക',
'export-templates'  => 'ഫലകങ്ങളും ഉൾപ്പെടുത്തുക',
'export-pagelinks'  => 'ഉൾപ്പെടുത്തേണ്ട കണ്ണികളുള്ള താളുകളുടെ ആഴം:',

# Namespace 8 related
'allmessages'                   => 'സന്ദേശസഞ്ചയം',
'allmessagesname'               => 'പേര്‌',
'allmessagesdefault'            => 'സ്വതവേയുള്ള ഉള്ളടക്കം',
'allmessagescurrent'            => 'നിലവിലുള്ള ഉള്ളടക്കം',
'allmessagestext'               => 'ഇത് മീഡിയവിക്കി നാമമേഖലയിൽ ലഭ്യമായ വ്യവസ്ഥാസന്ദേശങ്ങളുടെ ഒരു പട്ടിക ആണ്‌.
പ്രാമാണികമായ വിധത്തിൽ മീഡിയവിക്കിയുടെ പ്രാദേശീകരണം താങ്കൾ ഉദ്ദേശിക്കുന്നുവെങ്കിൽ ദയവായി [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation], [http://translatewiki.net translatewiki.net] എന്നീ താളുകൾ സന്ദർശിക്കുക.',
'allmessagesnotsupportedDB'     => "'''\$wgUseDatabaseMessages''' ബന്ധിച്ചിരിക്കുന്നതു കാരണം ഈ താൾ ഉപയോഗിക്കുവാൻ സാദ്ധ്യമല്ല.",
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
'thumbnail_error'          => 'ലഘുചിത്രം സൃഷ്ടിക്കുന്നതിൽ പിഴവ്: $1',
'djvu_page_error'          => 'DjVu താൾ പരിധിയ്ക്കു പുറത്താണ്',
'djvu_no_xml'              => 'DjVu പ്രമാണത്തിനു വേണ്ടി XML ശേഖരിക്കുവാൻ പറ്റിയില്ല',
'thumbnail_invalid_params' => 'ലഘുചിത്രത്തിനാവശ്യമായ ചരങ്ങൾ അസാധുവാണ്',
'thumbnail_dest_directory' => 'ലക്ഷ്യ ഡയറക്ടറി സൃഷ്ടിക്കുവാൻ സാധിച്ചില്ല',
'thumbnail_image-type'     => 'ചിത്രത്തിന്റെ തരം പിന്തുണക്കപ്പെട്ടതല്ല',
'thumbnail_gd-library'     => 'അപൂർണ്ണമായ ജി.ഡി. ലൈബ്രറി ക്രമീകരണം: ഫങ്ഷൻ $1 ലഭ്യമല്ല',
'thumbnail_image-missing'  => 'പ്രമാണം ലഭ്യമല്ലെന്നു കാണുന്നു: $1',

# Special:Import
'import'                     => 'താളുകൾ ഇറക്കുമതി ചെയ്യുക',
'importinterwiki'            => 'അന്തർ‌വിക്കി ഇറക്കുമതി',
'import-interwiki-text'      => 'വിക്കിയും ഇറക്കുമതി ചെയ്യാനുള്ള താളും തിരഞ്ഞെടുക്കുക.
പുതുക്കൽ തീയതികളും തിരുത്തിയ ആളുകളുടെ പേരും സൂക്ഷിക്കപ്പെടും.
അന്തർ‌വിക്കി ഇറക്കുമതിയുടെ എല്ലാ വിവരങ്ങളും [[Special:Log/import|ഇറക്കുമതി പ്രവർത്തനരേഖ]] എന്ന താളിൽ ശേഖരിക്കപ്പെടും.',
'import-interwiki-source'    => 'മൂല വിക്കി/താൾ:',
'import-interwiki-history'   => 'ഈ താളിന്റെ എല്ലാ പൂർവ്വചരിത്രവും പകർത്തുക',
'import-interwiki-templates' => 'എല്ലാ ഫലകങ്ങളും ഉൾപ്പെടുത്തുക',
'import-interwiki-submit'    => 'ഇറക്കുമതി',
'import-interwiki-namespace' => 'ഉദ്ദിഷ്ട നാമമേഖല:',
'import-upload-filename'     => 'പ്രമാണത്തിന്റെ പേര്‌',
'import-comment'             => 'കുറിപ്പ്:',
'importtext'                 => 'ദയവായി സ്രോതസ്സ് വിക്കിയിൽ നിന്ന് [[Special:Export|കയറ്റുമതി ഉപകരണം]] ഉപയോഗിച്ച് പ്രമാണം കയറ്റുമതി ചെയ്യുക.
അത് താങ്കളുടെ കമ്പ്യൂട്ടറിൽ ശേഖരിച്ച് ഇവിടെ അപ്‌‌ലോഡ് ചെയ്യുക.',
'importstart'                => 'താളുകൾ ഇറക്കുമതി ചെയ്യുന്നു...',
'import-revision-count'      => '{{PLURAL:$1|ഒരു പതിപ്പ്|$1 പതിപ്പുകൾ}}',
'importnopages'              => 'ഇറക്കുമതി ചെയ്യാൻ പറ്റിയ താളുകൾ ഇല്ല.',
'imported-log-entries'       => '{{PLURAL:$1|രേഖയിലെ ഒരുൾപ്പെടുത്തൽ|രേഖയിലെ $1 ഉൾപ്പെടുത്തലുകൾ}} ഇറക്കുമതി ചെയ്തു.',
'importfailed'               => 'ഇറക്കുമതി പരാജയപ്പെട്ടു: <nowiki>$1</nowiki>',
'importunknownsource'        => 'അപരിചിതമായ ഇറക്കുമതി സ്രോതസ്സ് തരം',
'importcantopen'             => 'ഇറക്കുമതി പ്രമാണം തുറക്കാൻ പറ്റിയില്ല',
'importbadinterwiki'         => 'മോശമായ അന്തർ‌വിക്കി കണ്ണി',
'importnotext'               => 'ശൂന്യം അല്ലെങ്കിൽ ഉള്ളടക്കം ഒന്നുമില്ല',
'importsuccess'              => 'ഇറക്കുമതി ചെയ്തുകഴിഞ്ഞു!',
'importhistoryconflict'      => 'പതിപ്പുകളുടെ ചരിത്രത്തിൽ പൊരുത്തക്കേട് (ഈ താൾ ഇതിനു മുൻപ് ഇറക്കുമതി ചെയ്തിട്ടുണ്ടാവാം)',
'importnosources'            => 'ട്രാൻസ്‌‌വിക്കി ഇറക്കുമതി സ്രോതസ്സുകളൊന്നും നിർവചിച്ചിട്ടില്ല, നേരിട്ടുള്ള നാൾവഴി അപ്‌‌ലോഡുകൾ പ്രവർത്തനരഹിതവുമാക്കിയിരിക്കുന്നു.',
'importnofile'               => 'ഇറക്കുമതി പ്രമാണങ്ങളൊന്നും അപ്‌‌ലോഡ് ചെയ്തിട്ടില്ല.',
'importuploaderrorsize'      => 'ഇറക്കുമതി ചെയ്ത പ്രമാണത്തിന്റെ അപ്‌‌ലോഡ് പരാജയപ്പെട്ടു.
പ്രമാണം അപ്‌‌ലോഡിങ്ങിനനുവദിക്കപ്പെട്ടിരിക്കുന്ന അളവിലും വലുതാണ്.',
'importuploaderrorpartial'   => 'ഇറക്കുമതി ചെയ്ത പ്രമാണത്തിന്റെ അപ്‌‌ലോഡ് പരാജയപ്പെട്ടു. 
പ്രമാണം ഭാഗികമായി അപ്‌‌ലോഡ് ചെയ്യപ്പെട്ടിരിക്കുന്നു.',
'importuploaderrortemp'      => 'ഇറക്കുമതി ചെയ്ത പ്രമാണത്തിന്റെ അപ്‌‌ലോഡ് പരാജയപ്പെട്ടു. 
തത്കാലത്തേയ്ക്കു വേണ്ടിയിരുന്ന ഒരു ഫോൾഡർ ലഭ്യമല്ല.',
'import-parse-failure'       => 'എക്സ്.എം.എൽ. ഇറക്കുമതി പാഴ്സ് പരാജയം',
'import-noarticle'           => 'ഇറക്കുമതി ചെയ്യാൻ താൾ ഇല്ല!',
'import-nonewrevisions'      => 'എല്ലാ പതിപ്പുകളും മുമ്പേ ഇറക്കുമതി ചെയ്തിട്ടുള്ളതാണ്‌.',
'xml-error-string'           => '$2 വരിയിൽ $1, നിര $3 (ബൈറ്റ് $4): $5',
'import-upload'              => 'എക്സ്.എം.എൽ. ഡേറ്റ അപ്‌‌ലോഡ് ചെയ്യുക',
'import-token-mismatch'      => 'സെഷൻ ഡാറ്റ നഷ്ടപ്പെട്ടതിനാൽ ദയവായി വീണ്ടും ശ്രമിക്കൂക',
'import-invalid-interwiki'   => 'താങ്കൾ നിർദ്ദേശിച്ച വിക്കിയിൽനിന്നും ഇറക്കുമതിചെയ്യാൻ സാധിച്ചില്ല',

# Import log
'importlogpage'                    => 'ഇറക്കുമതി പ്രവർത്തനരേഖ',
'importlogpagetext'                => 'മറ്റു വിക്കികളിൽ നിന്ന് താളുകൾ നാൾവഴിയടക്കം എടുക്കുന്ന കാര്യനിർവാഹക ഇറക്കുമതി.',
'import-logentry-upload'           => 'പ്രമാണ അപ്‌‌ലോഡ് വഴി [[$1]] ഇറക്കുമതി ചെയ്തിരിക്കുന്നു',
'import-logentry-upload-detail'    => '{{PLURAL:$1|ഒരു പതിപ്പ്|$1 പതിപ്പുകൾ}}',
'import-logentry-interwiki'        => 'അന്തർവിക്കി ഇറക്കുമതി $1',
'import-logentry-interwiki-detail' => '$2 എന്നതിൽ നിന്ന് {{PLURAL:$1|ഒരു പതിപ്പ്|$1 പതിപ്പുകൾ}}',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'താങ്കളുടെ ഉപയോക്തൃതാൾ',
'tooltip-pt-anonuserpage'         => 'താങ്കളുടെ ഐ.പി. വിലാസത്തിന്റെ ഉപയോക്തൃതാൾ',
'tooltip-pt-mytalk'               => 'താങ്കളുടെ സംവാദം താൾ',
'tooltip-pt-anontalk'             => 'ഈ ഐ.പി. വിലാസത്തിൽനിന്നുള്ള തിരുത്തലുകളെക്കുറിച്ചുള്ള സം‌വാദം',
'tooltip-pt-preferences'          => 'താങ്കളുടെ ക്രമീകരണങ്ങൾ',
'tooltip-pt-watchlist'            => 'താങ്കൾ ശ്രദ്ധിക്കുന്ന താളുകളിലെ മാറ്റങ്ങൾ',
'tooltip-pt-mycontris'            => 'താങ്കളുടെ സേവനങ്ങളുടെ പട്ടിക',
'tooltip-pt-login'                => 'ലോഗിൻ ചെയ്യണമെന്നു നിർബന്ധം ഇല്ലെങ്കിലും ലോഗിൻ ചെയ്യുവാൻ താല്പര്യപ്പെടുന്നു.',
'tooltip-pt-anonlogin'            => 'ലോഗിൻ ചെയ്തു തിരുത്തൽ നടത്തുവാൻ താല്പര്യപ്പെടുന്നു.',
'tooltip-pt-logout'               => 'ലോഗൗട്ട് ചെയ്യാനുള്ള കണ്ണി',
'tooltip-ca-talk'                 => 'വിവരദായക താളിനെക്കുറിച്ചുള്ള ചർച്ച',
'tooltip-ca-edit'                 => 'താങ്കൾക്ക് ഈ താൾ തിരുത്താവുന്നതാണ്. തിരുത്തിയ താൾ സേവ് ചെയ്യൂന്നതിനു മുൻപ് പ്രിവ്യൂ കാണുക.',
'tooltip-ca-addsection'           => 'പുതിയ വിഭാഗം തുടങ്ങുക',
'tooltip-ca-viewsource'           => 'ഈ താൾ സം‌രക്ഷിക്കപ്പെട്ടിരിക്കുന്നു. താങ്കൾക്ക് ഈ താളിന്റെ മൂലരൂപം കാണാവുന്നതാണ്‌.',
'tooltip-ca-history'              => 'ഈ താളിന്റെ പഴയ പതിപ്പുകൾ.',
'tooltip-ca-protect'              => 'ഈ താൾ സം‌രക്ഷിക്കുക',
'tooltip-ca-unprotect'            => 'ഈ താളിന്റെ സംരക്ഷണം ഒഴിവാക്കുക',
'tooltip-ca-delete'               => 'ഈ താൾ നീക്കം ചെയ്യുക',
'tooltip-ca-undelete'             => 'ഈ താൾ നീക്കം ചെയ്തതിനുമുമ്പ് വരുത്തിയ തിരുത്തലുകൾ പുനഃസ്ഥാപിക്കുക',
'tooltip-ca-move'                 => 'ഈ താളിന്റെ തലക്കെട്ടു്‌ മാറ്റുക',
'tooltip-ca-watch'                => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിലേയ്ക്ക് ഈ താൾ ചേർക്കുക',
'tooltip-ca-unwatch'              => 'ഈ താൾ ഞാൻ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിൽനിന്നു നീക്കുക',
'tooltip-search'                  => '{{SITENAME}} സംരംഭത്തിൽ തിരയുക',
'tooltip-search-go'               => 'ഈ പേരിൽ ഒരു താളുണ്ടെങ്കിൽ അതിലേക്കു പോവുക.',
'tooltip-search-fulltext'         => 'ഈ പേര് ഏതൊക്കെ താളിന്റെ ഉള്ളടക്കത്തിലുണ്ടെന്ന് എന്നു തിരയുന്നു',
'tooltip-p-logo'                  => 'പ്രധാനതാൾ സന്ദർശിക്കുക',
'tooltip-n-mainpage'              => 'പ്രധാനതാൾ സന്ദർശിക്കുക',
'tooltip-n-mainpage-description'  => 'പ്രധാനതാൾ സന്ദർശിക്കുക',
'tooltip-n-portal'                => 'പദ്ധതി താളിനെക്കുറിച്ച്, താങ്കൾക്കെന്തൊക്കെ ചെയ്യാം, കാര്യങ്ങൾ എവിടെനിന്ന് കണ്ടെത്താം',
'tooltip-n-currentevents'         => 'സമകാലീനസംഭവങ്ങളുടെ പശ്ചാത്തലം അന്വേഷിക്കുക',
'tooltip-n-recentchanges'         => 'വിക്കിയിലെ സമീപകാലമാറ്റങ്ങൾ',
'tooltip-n-randompage'            => 'ഏതെങ്കിലും ഒരു താൾ തുറക്കൂ',
'tooltip-n-help'                  => 'സഹായം ലഭ്യമായ ഇടം',
'tooltip-t-whatlinkshere'         => 'ഈ താളിലേക്കു കണ്ണിയാൽ ബന്ധിപ്പിക്കപ്പെട്ടിരിക്കുന്ന എല്ലാ വിക്കി താളുകളുടേയും പട്ടിക.',
'tooltip-t-recentchangeslinked'   => 'താളുകളിലെ പുതിയ മാറ്റങ്ങൾ',
'tooltip-feed-rss'                => 'ഈ താളിന്റെ RSS ഫീഡ്',
'tooltip-feed-atom'               => 'ഈ താളിന്റെ Atom ഫീഡ്',
'tooltip-t-contributions'         => 'ഉപയോക്താവിന്റെ സംഭാവനകളുടെ പട്ടിക കാണുക',
'tooltip-t-emailuser'             => 'ഈ ഉപയോക്താവിനു ഇമെയിൽ അയക്കുക',
'tooltip-t-upload'                => 'പ്രമാണങ്ങൾ അപ്‌ലോഡ് ചെയ്യുവാൻ',
'tooltip-t-specialpages'          => 'പ്രത്യേകതാളുകളുടെ പട്ടിക',
'tooltip-t-print'                 => 'ഈ താളിന്റെ അച്ചടി രൂപം',
'tooltip-t-permalink'             => 'താളിന്റെ ഈ പതിപ്പിന്റെ സ്ഥിരം കണ്ണി',
'tooltip-ca-nstab-main'           => 'വിവരദായക താൾ കാണുക',
'tooltip-ca-nstab-user'           => 'ഉപയോക്താവിന്റെ താൾ കാണുക',
'tooltip-ca-nstab-media'          => 'മീഡിയ താൾ കാണുക',
'tooltip-ca-nstab-special'        => "ഇതൊരു '''പ്രത്യേക''' താളാണ്‌. ഇത് തിരുത്തുക സാധ്യമല്ല.",
'tooltip-ca-nstab-project'        => 'പദ്ധതി താൾ കാണുക',
'tooltip-ca-nstab-image'          => 'പ്രമാണ താൾ കാണുക',
'tooltip-ca-nstab-mediawiki'      => 'വ്യവസ്ഥാസന്ദേശം കാണുക',
'tooltip-ca-nstab-template'       => 'ഫലകം കാണുക',
'tooltip-ca-nstab-help'           => 'സഹായം താൾ കാണുക',
'tooltip-ca-nstab-category'       => 'വർഗ്ഗം താൾ കാണുക',
'tooltip-minoredit'               => 'ഇത് ഒരു ചെറുതിരുത്തലായി അടയാളപ്പെടുത്തുക',
'tooltip-save'                    => 'മാറ്റങ്ങൾ സംരക്ഷിക്കുന്നു',
'tooltip-preview'                 => 'താങ്കൾ വരുത്തിയ മാറ്റത്തിന്റെ ഫലം എങ്ങനെയായിരിക്കുമെന്നു കാണുന്നതിനു താൾ സംരക്ഷിക്കുന്നതിനു മുൻപ് ഈ ബട്ടൺ ഉപയോഗിക്കുക!',
'tooltip-diff'                    => 'താങ്കൾ ഉള്ളടക്കത്തിൽ വരുത്തിയ മാറ്റങ്ങൾ ഏതൊക്കെയെന്നു പ്രദർശിപ്പിക്കുക',
'tooltip-compareselectedversions' => 'ഈ താളിന്റെ താങ്കൾ തിരഞ്ഞെടുത്ത രണ്ട് പതിപ്പുകൾ തമ്മിലുള്ള വ്യത്യാസം കാണുക.',
'tooltip-watch'                   => 'ഈ താൾ താങ്കൾ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിലേക്കു മാറ്റുക.',
'tooltip-recreate'                => 'താൾ മായ്ച്ചതാണെങ്കിലും പുനഃസൃഷ്ടിക്കുക',
'tooltip-upload'                  => 'അപ്‌ലോഡ് തുടങ്ങുക',
'tooltip-rollback'                => 'അവസാനത്തെ ആൾ നടത്തിയ തിരുത്തലുകളെ ഒരൊറ്റ ക്ലിക്ക് കൊണ്ട് മുൻ അവസ്ഥയിലേക്ക് തിരിച്ചുവെയ്ക്കാൻ "റോൾബാക്ക്" സഹായിക്കുന്നു.',
'tooltip-undo'                    => 'മാറ്റം തിരസ്കരിക്കുക എന്നത് ഈ മാറ്റം മുൻ അവസ്ഥയിലേക്ക് മാറ്റുകയും അത് എഡിറ്റ് ഫോമിൽ പ്രിവ്യൂ ആയി കാട്ടുകയും ചെയ്യും. അതുകൊണ്ട് തിരുത്തലിന്റെ കാരണം ചുരുക്കമായി നൽകാൻ സാധിക്കുന്നതാണ്.',
'tooltip-preferences-save'        => 'ക്രമീകരണങ്ങൾ ഓർത്തുവെയ്ക്കുക',
'tooltip-summary'                 => 'ചെറിയൊരു സംഗ്രഹം ചേർക്കുക',

# Stylesheets
'common.css' => '/* ഇവിടെ നൽകുന്ന സി.എസ്.എസ്. എല്ലാ ദൃശ്യരൂപങ്ങൾക്കും ബാധകമായിരിക്കും */',

# Scripts
'common.js' => '/* ഇവിടെ നൽകുന്ന ജാവാസ്ക്രിപ്റ്റ് എല്ലാ ഉപയോക്താക്കൾക്കും, എല്ലാ താളുകളിലും പ്രവർത്തിക്കുന്നതായിരിക്കും */',

# Metadata
'nodublincore'      => 'ഡബ്ലിൻ കോർ ആർ.ഡി.എഫ്. മെറ്റാഡേറ്റാ ഈ സെർവറിൽ സജ്ജമല്ല.',
'nocreativecommons' => 'ക്രിയേറ്റീവ് കോമൺസ് ആർ.ഡി.എഫ്. മെറ്റാഡേറ്റാ ഈ സെ‌‌ർവറിൽ സജ്ജമല്ല.',
'notacceptable'     => 'താങ്കളുടെ ക്ലയന്റിനു മനസ്സിലാക്കാൻ പാകത്തിലുള്ള തരത്തിൽ വിവരങ്ങൾ നൽകാൻ വിക്കി സെർവറിനു ശേഷിയില്ല.',

# Attribution
'anonymous'        => '{{SITENAME}} സംരംഭത്തിലെ അജ്ഞാത {{PLURAL:$1|ഉപയോക്താവ്|ഉപയോക്താക്കൾ}}',
'siteuser'         => '{{SITENAME}} ഉപയോക്താവ് $1',
'anonuser'         => '{{SITENAME}} പദ്ധതിയിലെ അജ്ഞാത ഉപയോക്താവ് $1',
'lastmodifiedatby' => '$2, $1 നു $3 ആണ്‌ ഈ താൾ അവസാനം പുതുക്കിയത്.',
'othercontribs'    => '$1 നടത്തിയ സൃഷ്ടിയെ അധികരിച്ച്.',
'others'           => 'മറ്റുള്ളവർ',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|ഉപയോക്താവ്‌|ഉപയോക്താക്കൾ}} $1',
'anonusers'        => '{{SITENAME}} പദ്ധതിയിലെ അജ്ഞാത {{PLURAL:$2|ഉപയോക്താവ്|ഉപയോക്താക്കൾ}} $1',
'creditspage'      => 'താളിനുള്ള കടപ്പാട്',
'nocredits'        => 'ഈ താളിന്റെ കടപ്പാട് വിവരങ്ങൾ ലഭ്യമല്ല.',

# Spam protection
'spamprotectiontitle' => 'പാഴ് എഴുത്ത് സം‌രക്ഷണ അരിപ്പ',
'spamprotectiontext'  => 'താങ്കൾക്ക് സേവ് ചെയ്യണമെന്നുള്ള താൾ പാഴ് എഴുത്ത് അരിപ്പയാൽ തടയപ്പെട്ടിരിക്കുന്നു.
ഇത് മിക്കവാറും കരിമ്പട്ടികയിലുള്ള പുറം വെബ്‌‌സൈറ്റിലേയ്ക്കുള്ള കണ്ണി ഉൾക്കൊള്ളുന്നതു കൊണ്ടാവാം.',
'spamprotectionmatch' => 'പാഴ് എഴുത്ത് അരിപ്പയെ ഉണർത്തിയ എഴുത്ത് താഴെ കൊടുത്തിരിക്കുന്നു: $1',
'spambot_username'    => 'മീഡിയാവിക്കിയിലെ പാഴ് എഴുത്ത് ശുദ്ധീകരണം',
'spam_reverting'      => '$1 എന്നതിലേയ്ക്കുള്ള കണ്ണികളില്ലാത്ത അവസാന നാൾപ്പതിപ്പിലേയ്ക്ക് മുൻപ്രാപനം ചെയ്യുന്നു',
'spam_blanking'       => '$1 എന്ന കണ്ണികളുള്ള നാൾപ്പതിപ്പുകളെല്ലാം ശൂന്യമാക്കുന്നു',

# Info page
'infosubtitle'   => 'താളിനുള്ള വിവരങ്ങൾ',
'numedits'       => 'തിരുത്തലുകളുടെ എണ്ണം (ലേഖനം): $1',
'numtalkedits'   => 'തിരുത്തലുകളുടെ എണ്ണം (സം‌വാദം താൾ): $1',
'numwatchers'    => 'ശ്രദ്ധിക്കുന്നവരുടെ എണ്ണം: $1',
'numauthors'     => 'വ്യത്യസ്തരായ രചയിതാക്കളുടെ എണ്ണം (താളിന്റെ): $1',
'numtalkauthors' => 'വ്യത്യസ്തരായ രചയിതാക്കളുടെ എണ്ണം (സം‌വാദം താളിന്റെ): $1',

# Skin names
'skinname-standard'    => 'സാർവത്രികം',
'skinname-nostalgia'   => 'ഗൃഹാതുരത്വം',
'skinname-cologneblue' => 'ക്ലോൺ നീല',
'skinname-monobook'    => 'മോണോബുക്ക്',
'skinname-myskin'      => 'എന്റിഷ്ടം',
'skinname-chick'       => 'സുന്ദരി',
'skinname-simple'      => 'ലളിതം',
'skinname-modern'      => 'നവീനം',
'skinname-vector'      => 'വെക്റ്റർ',

# Math options
'mw_math_png'    => 'എപ്പോഴും PNG ആയി പ്രദർശിപ്പിക്കുക',
'mw_math_simple' => 'വളരെ ലളിതമാണെങ്കിൽ HTML അല്ലെങ്കിൽ PNG',
'mw_math_html'   => 'പറ്റുമെങ്കിൽ HTML അല്ലെങ്കിൽ PNG',
'mw_math_source' => 'TeX ആയി തന്നെ പ്രദർശിപ്പിക്കുക (ടെക്സ്റ്റ് ബ്രൗസറുകൾക്ക്)',
'mw_math_modern' => 'ആധുനിക ബ്രൗസറുകൾക്കായി നിർദേശിക്കപ്പെട്ടത്',
'mw_math_mathml' => 'പറ്റുമെങ്കിൽ MathML (പരീക്ഷണാടിസ്ഥാനം)',

# Math errors
'math_failure'          => 'parse ചെയ്യുവാൻ പരാജയപ്പെട്ടു',
'math_unknown_error'    => 'കാരണമറിയാത്ത പിഴവ്',
'math_unknown_function' => 'അജ്ഞാതമായ ഫങ്ങ്ഷൻ',
'math_lexing_error'     => 'ലെക്സിങ് പിഴവ്',
'math_syntax_error'     => 'തെറ്റായ പദവിന്യാസം',
'math_image_error'      => 'PNG രൂപത്തിലേയ്ക്കുള്ള മാറ്റം പരാജയപ്പെട്ടു;
latex, dvips, gs, convert എന്നിവ ശരിയായാണോ ഇൻസ്റ്റോൾ ചെയ്തിരിക്കുന്നതെന്നു പരിശോധിക്കുക',
'math_bad_tmpdir'       => 'math temp ഡയറക്ടറി ഉണ്ടാക്കാനോ അതിലേക്കു എഴുതാനോ സാധിച്ചില്ല',
'math_bad_output'       => 'math output ഡയറക്ടറി ഉണ്ടാക്കാനോ അതിലേക്കു എഴുതാനോ സാധിച്ചില്ല',
'math_notexvc'          => 'പ്രവർത്തനസജ്ജമായ texvc ലഭ്യമല്ല;
സജ്ജീകരിച്ചെടുക്കാനുള്ള സഹായത്തിനു ദയവായി math/README കാണുക.',

# Patrolling
'markaspatrolleddiff'                 => 'റോന്തുചുറ്റിയതായി അടയാളപ്പെടുത്തുക',
'markaspatrolledtext'                 => 'ഈ താളിൽ റോന്തുചുറ്റിയതായി രേഖപ്പെടുത്തുക',
'markedaspatrolled'                   => 'റോന്തുചുറ്റിയതായി രേഖപ്പെടുത്തിയിരിക്കുന്നു',
'markedaspatrolledtext'               => '[[:$1]] എന്ന താളിന്റെ തിരഞ്ഞെടുത്ത നാൾപ്പതിപ്പിൽ റോന്തുചുറ്റിയതായി രേഖപ്പെടുത്തിയിരിക്കുന്നു',
'rcpatroldisabled'                    => 'പുതിയ മാറ്റങ്ങളുടെ റോന്തുചുറ്റൽ ദുർബലപ്പെടുത്തിയിരിക്കുന്നു',
'rcpatroldisabledtext'                => 'പുതിയ മാറ്റങ്ങളുടെ റോന്തുചുറ്റൽ സം‌വിധാനം ദുർബലപ്പെടുത്തിയിരിക്കുകയാണ്‌.',
'markedaspatrollederror'              => 'റോന്തുചുറ്റിയതായി അടയാളപ്പെടുത്തുക സാധ്യമല്ല',
'markedaspatrollederrortext'          => 'റോന്തുചുറ്റിയതായി അടയാളപ്പെടുത്തേണ്ട നാൾപ്പതിപ്പ താങ്കൾ വ്യക്തമാക്കേണ്ടതാണ്.',
'markedaspatrollederror-noautopatrol' => 'സ്വന്തം മാറ്റങ്ങൾ റോന്തുചുറ്റിയതായി അടയാളപ്പെടുത്തുക അനുവദനീയമല്ല.',

# Patrol log
'patrol-log-page'      => 'റോന്തുചുറ്റൽ പ്രവർത്തനരേഖ',
'patrol-log-header'    => 'റോന്തുചുറ്റപ്പെട്ട നാൾപ്പതിപ്പുകളുടെ രേഖയാണിത്',
'patrol-log-line'      => '$2 താളിലെ $1 റോന്തുചുറ്റപ്പെട്ടിരിക്കുന്നു $3',
'patrol-log-auto'      => '(യാന്ത്രികം)',
'patrol-log-diff'      => 'നാൾപ്പതിപ്പ് $1',
'log-show-hide-patrol' => 'റോന്തുചുറ്റൽ രേഖ $1',

# Image deletion
'deletedrevision'                 => '$1 എന്ന പഴയ പതിപ്പ് മായ്ച്ചിരിക്കുന്നു',
'filedeleteerror-short'           => 'പ്രമാണം നീക്കം ചെയ്യുമ്പോൾ പ്രശ്നം: $1',
'filedeleteerror-long'            => 'പ്രമാണം നീക്കം ചെയ്യുമ്പോൾ ചില പ്രശ്നങ്ങൾ സംഭവിച്ചു:

$1',
'filedelete-missing'              => '"$1" എന്ന പ്രമാണം നിലവില്ലാത്തതിനാൽ അതു നീക്കം ചെയ്യുക സാധ്യമല്ല.',
'filedelete-old-unregistered'     => 'താങ്കൾ ആവശ്യപ്പെട്ട "$1" എന്ന പതിപ്പ് ഡാറ്റാബേസിൽ ഇല്ല.',
'filedelete-current-unregistered' => 'താങ്കൾ ആവശ്യപ്പെട്ട "$1" എന്ന പ്രമാണം ഡാറ്റബേസിൽ ഇല്ല.',

# Browsing diffs
'previousdiff' => '← മുൻ‌പത്തെ വ്യത്യാസം',
'nextdiff'     => 'അടുത്ത വ്യത്യാസം →',

# Media information
'mediawarning'         => "'''മുന്നറിയിപ്പ്''': ഈ തരത്തിലുള്ള പ്രമാണത്തിൽ വിനാശകാരിയായ കോഡ് ഉണ്ടായേക്കാം. ഇതു തുറക്കുന്നതു താങ്കളുടെ കമ്പ്യൂട്ടറിനു അപകടമായി തീർന്നേക്കാം.<hr />",
'imagemaxsize'         => "ചിത്രത്തിന്റെ വലിപ്പം:<br />''(പ്രമാണത്തിന്റെ വിവരണ താളുകളിൽ)''",
'thumbsize'            => 'ലഘുചിത്രത്തിന്റെ വലിപ്പം:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|താൾ|താളുകൾ}}',
'file-info'            => '(പ്രമാണത്തിന്റെ വലിപ്പം: $1, MIME തരം: $2)',
'file-info-size'       => '($1 × $2 പിക്സൽ, പ്രമാണത്തിന്റെ വലിപ്പം: $3, MIME തരം: $4)',
'file-nohires'         => '<small>കൂടുതൽ വ്യക്തതയുള്ള ചിത്രം ലഭ്യമല്ല.</small>',
'svg-long-desc'        => '(SVG പ്രമാണം, നാമമാത്രമായ $1 × $2 പിക്സലുകൾ, പ്രമാണത്തിന്റെ വലിപ്പം: $3)',
'show-big-image'       => 'പൂർണ്ണ റെസലൂഷൻ',
'show-big-image-thumb' => '<small>ഈ പ്രിവ്യൂവിന്റെ വലിപ്പം: $1 × $2 പിക്സലുകൾ</small>',
'file-info-gif-looped' => 'ആവർത്തനക്കുരുക്ക് ഉണ്ടായിരിക്കുന്നു',
'file-info-gif-frames' => '{{PLURAL:$1|ഒരു ചട്ടം|$1 ചട്ടങ്ങൾ}}',

# Special:NewFiles
'newimages'             => 'പുതിയ പ്രമാണങ്ങളുടെ ഗാലറി',
'imagelisttext'         => '$2 നൽകിയിട്ടുള്ള {{PLURAL:$1|പ്രമാണത്തിന്റെ|$1 പ്രമാണങ്ങളുടെ}} പട്ടിക താഴെ കാണാം.',
'newimages-summary'     => 'ചുരുക്കം',
'newimages-legend'      => 'അരിപ്പ',
'newimages-label'       => 'പ്രമാണത്തിന്റെ പേര്‌ (അഥവാ പേരിന്റെ ഭാഗം)',
'showhidebots'          => '(യന്ത്രങ്ങളെ $1)',
'noimages'              => 'ഒന്നും കാണാനില്ല.',
'ilsubmit'              => 'തിരയൂ',
'bydate'                => 'ദിനക്രമത്തിൽ',
'sp-newimages-showfrom' => '$2, $1 നു ശേഷം അപ്‌ലോഡ് ചെയ്ത പ്രമാണങ്ങൾ പ്രദർശിപ്പിക്കുക',

# Bad image list
'bad_image_list' => 'എഴുത്ത് രീതി താഴെ കൊടുത്തിരിക്കുന്നു:

ലിസ്റ്റിലെ ഉൾപ്പെടുത്തലുകൾ മാത്രമേ കണക്കാക്കുകയുള്ളു (* എന്ന് തുടങ്ങുന്ന വരികൾ). മോശം പ്രമാണത്തിലേയ്ക്കുള്ള കണ്ണിയാവണം വരിയിലെ ആദ്യ കണ്ണി. അതേ വരിയിലുള്ള മറ്റ് കണ്ണികൾ അപവാദങ്ങളായിരിക്കും, അതായത്. പ്രമാണത്തിലേയ്ക്കുള്ള താളുകൾ ഒരു വരിയിൽ ഉൾപ്പെടുത്തണം.',

# Metadata
'metadata'          => 'മെറ്റാഡാറ്റ',
'metadata-help'     => 'ഡിജിറ്റൽ ക്യാമറയോ, സ്കാനറോ ഉപയോഗിച്ച് നിർമ്മിച്ചപ്പോഴോ ഡിജിറ്റൈസ് ചെയ്തപ്പോഴോ ചേർക്കപ്പെട്ട അധികവിവരങ്ങൾ ഈ പ്രമാണത്തിലുണ്ട്. ഈ പ്രമാണം അതിന്റെ ആദ്യസ്ഥിതിയിൽ നിന്നും മാറ്റിയിട്ടുണ്ടെങ്കിൽ, ചില വിവരങ്ങൾ ഇപ്പോഴുള്ള പ്രമാണത്തെ പൂർണ്ണമായി പ്രതിനിധീകരിക്കണമെന്നില്ല.',
'metadata-expand'   => 'അധികവിവരങ്ങൾ കാണിക്കുക',
'metadata-collapse' => 'അധികവിവരങ്ങൾ മറയ്ക്കുക',
'metadata-fields'   => 'ഈ സന്ദേശത്തിൽ തന്നിട്ടുള്ള EXIF മെറ്റാഡാറ്റ ഫീൽഡുകൾ ചിത്രത്തിന്റെ താളിൽ മെറ്റാഡാറ്റ ടേബിൾ മറഞ്ഞിരിക്കുമ്പോഴും ദൃശ്യമാകും. മറ്റുള്ള ഫീൽഡുകൾ സ്വതവേ മറഞ്ഞിരിക്കും.
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
'exif-bitspersample'               => 'ഓരോ ഘടകത്തിലുമുള്ള ബിറ്റുകൾ',
'exif-compression'                 => 'കംപ്രഷൻ രീതി',
'exif-photometricinterpretation'   => 'പിക്സൽ നിർമ്മിതി',
'exif-orientation'                 => 'വിന്യാസം',
'exif-samplesperpixel'             => 'ഘടകങ്ങളുടെ എണ്ണം',
'exif-planarconfiguration'         => 'ഡേറ്റാ ക്രമീകരണം',
'exif-xresolution'                 => 'തിരശ്ചീന റെസലൂഷൻ',
'exif-yresolution'                 => 'ലംബ റെസലൂഷൻ',
'exif-resolutionunit'              => 'X, Y എന്നിവയുടെ വ്യതിരിക്തത ഏകകം',
'exif-stripoffsets'                => 'ചിത്രത്തിന്റെ വിവര സ്ഥാനം',
'exif-rowsperstrip'                => 'ഓരോ സ്‌ട്രിപ്പിലുമുള്ള വരികളുടെ എണ്ണം',
'exif-stripbytecounts'             => 'ഓരോ ചുരുക്കപ്പെട്ട ഖണ്ഡത്തിലുമുള്ള ബൈറ്റുകൾ',
'exif-jpeginterchangeformatlength' => 'JPEG ഡാറ്റയുടെ ബൈറ്റ്സുകൾ',
'exif-transferfunction'            => 'കൈമാറ്റ രീതി',
'exif-datetime'                    => 'പ്രമാണത്തിന് മാറ്റം വരുത്തിയ തീയതിയും സമയവും',
'exif-imagedescription'            => 'ചിത്രത്തിന്റെ തലക്കെട്ട്',
'exif-make'                        => 'ഛായാഗ്രാഹി നിർമ്മാതാവ്',
'exif-model'                       => 'ഛായാഗ്രാഹി മോഡൽ',
'exif-software'                    => 'ഉപയോഗിച്ച സോഫ്റ്റ്‌വെയർ',
'exif-artist'                      => 'ഛായാഗ്രാഹകൻ',
'exif-copyright'                   => 'പകർപ്പവകാശ ഉടമ',
'exif-exifversion'                 => 'Exif ന്റെ വേർഷൻ',
'exif-flashpixversion'             => 'പിന്തുണയുള്ള ഫ്ലാഷ്‌‌പിക്സ് പതിപ്പ്',
'exif-colorspace'                  => 'കളർ സ്പേസ്',
'exif-componentsconfiguration'     => 'ഓരോ ഘടകത്തിന്റേയും അർത്ഥം',
'exif-compressedbitsperpixel'      => 'ചിത്രം ചുരുക്കുവാനുപയോഗിച്ചിരിക്കുന്ന മാർഗ്ഗം',
'exif-pixelydimension'             => 'ചിത്രത്തിന്റെ സാധുവായ വീതി',
'exif-pixelxdimension'             => 'ചിത്രത്തിന്റെ സാധുവായ ഉയരം',
'exif-makernote'                   => 'നിർമ്മാതാക്കളുടെ കുറിപ്പുകൾ',
'exif-usercomment'                 => 'ഉപയോക്താവിന്റെ കുറിപ്പുകൾ',
'exif-relatedsoundfile'            => 'ഇതുമായി ബന്ധമുള്ള ഓഡിയോ പ്രമാണം',
'exif-datetimeoriginal'            => 'ഡാറ്റ സൃഷ്ടിക്കപ്പെട്ട തീയതിയും സമയവും',
'exif-datetimedigitized'           => 'ഡിജിറ്റൈസ് ചെയ്ത തീയതിയും സമയവും',
'exif-subsectime'                  => 'തീയതി-സമയം ഉപസെക്കന്റുകൾ',
'exif-subsectimeoriginal'          => 'തീയതി-സമയം-യഥാർത്ഥ ഉപസെക്കന്റുകൾ',
'exif-subsectimedigitized'         => 'തീയതി-സമയം-ഡിജിറ്റൽവത്കരിച്ച ഉപസെക്കന്റുകൾ',
'exif-exposuretime'                => 'തുറന്നിരിക്കപ്പെട്ട സമയം',
'exif-exposuretime-format'         => '$1 സെക്കന്റ് ($2)',
'exif-fnumber'                     => 'F സംഖ്യ',
'exif-exposureprogram'             => 'എക്സ്പോഷർ പ്രോഗ്രാം',
'exif-spectralsensitivity'         => 'വർണ്ണരാജി സംവേദനത്വം',
'exif-oecf'                        => 'ഒപ്റ്റോഇലക്ട്രോണിക് പരിവർത്തന ഘടകം',
'exif-shutterspeedvalue'           => 'ഷട്ടർ സ്പീഡ്',
'exif-aperturevalue'               => 'അപ്പെർച്ചർ',
'exif-brightnessvalue'             => 'ബ്രൈറ്റ്നെസ്സ്',
'exif-subjectdistance'             => 'വസ്തുവിന്റെ അകലം',
'exif-meteringmode'                => 'മീറ്ററിൽ അളവെടുക്കുന്ന വിധം',
'exif-lightsource'                 => 'പ്രകാശ സ്രോതസ്സ്',
'exif-flash'                       => 'ഫ്ലാഷ്',
'exif-focallength'                 => 'ലെൻസിന്റെ ഫോക്കൽ ലെങ്ത്',
'exif-subjectarea'                 => 'വസ്തുവിന്റെ വിസ്തൃതി',
'exif-flashenergy'                 => 'ഫ്ലാഷ് എനർജി',
'exif-focalplanexresolution'       => 'ഫോക്കൽ തലം X നൽകുന്ന വ്യതിരിക്തത',
'exif-focalplaneyresolution'       => 'ഫോക്കൽ തലം Y നൽകുന്ന വ്യതിരിക്തത',
'exif-focalplaneresolutionunit'    => 'ഫോക്കൽ തലം വ്യതിരിക്തതയുടെ ഏകകം',
'exif-subjectlocation'             => 'വസ്തുവിന്റെ സ്ഥാനം',
'exif-exposureindex'               => 'Exposure index',
'exif-sensingmethod'               => 'സം‌വേദന രീതി',
'exif-filesource'                  => 'പ്രമാണത്തിന്റെ സ്രോതസ്സ്',
'exif-scenetype'                   => 'ദൃശ്യ തരം',
'exif-cfapattern'                  => 'സി.എഫ്.എ. ശ്രേണി',
'exif-exposuremode'                => 'എക്സ്പോഷർ മോഡ്',
'exif-whitebalance'                => 'വൈറ്റ് ബാലൻസ്',
'exif-digitalzoomratio'            => 'ഡിജിറ്റൽ സൂം അനുപാതം',
'exif-focallengthin35mmfilm'       => '35 മില്ലീമീറ്റർ ഫിലിമിലെ ഫോക്കസ് ദൂരം',
'exif-scenecapturetype'            => 'ദൃശ്യ ഗ്രഹണ തരം',
'exif-gaincontrol'                 => 'ദൃശ്യ നിയന്ത്രണം',
'exif-contrast'                    => 'കോൺ‌ട്രാസ്റ്റ്',
'exif-saturation'                  => 'സാച്ചുറേഷൻ',
'exif-sharpness'                   => 'ഷാർപ്പനെസ്',
'exif-devicesettingdescription'    => 'ഉപകരണ സജ്ജീകരണങ്ങളുടെ വിവരണം',
'exif-subjectdistancerange'        => 'വസ്തുവിന്റെ അകലത്തിന്റെ പരിധി',
'exif-imageuniqueid'               => 'ചിത്രത്തിന്റെ തനതായ ഐ.ഡി.',
'exif-gpsversionid'                => 'ജി.പി.എസ്. റ്റാഗ് പതിപ്പ്',
'exif-gpslatituderef'              => 'ഉത്തര അല്ലെങ്കിൽ ദക്ഷിണ അക്ഷാംശം',
'exif-gpslatitude'                 => 'അക്ഷാംശം',
'exif-gpslongituderef'             => 'പൂർവ്വരേഖാംശം അല്ലെങ്കിൽ പശ്ചിമരേഖാംശം',
'exif-gpslongitude'                => 'രേഖാംശം',
'exif-gpsaltituderef'              => 'ഉന്നതിയുടെ അവലംബം',
'exif-gpsaltitude'                 => 'ഉന്നതി',
'exif-gpstimestamp'                => 'GPS സമയം (ആറ്റോമിക് ക്ലോക്ക്)',
'exif-gpssatellites'               => 'അളക്കാൻ ഉപയോഗിച്ച കൃത്രിമോപഗ്രഹങ്ങൾ',
'exif-gpsstatus'                   => 'സ്വീകരണിയുടെ സ്ഥിതി',
'exif-gpsmeasuremode'              => 'അളവെടുക്കൽ രീതി',
'exif-gpsdop'                      => 'അളവുകളുടെ കൃത്യത',
'exif-gpsspeedref'                 => 'വേഗതയുടെ ഏകകം',
'exif-gpsspeed'                    => 'GPS പരിഗ്രാഹിയുടെ ഗതിവേഗം (Speed of GPS receiver)',
'exif-gpstrackref'                 => 'ചലനത്തിന്റെ ദിശയ്ക്കുള്ള അവലംബം',
'exif-gpstrack'                    => 'ചലനത്തിന്റെ ദിശ',
'exif-gpsimgdirectionref'          => 'ചിത്രത്തിന്റെ ദിശയ്ക്കുള്ള അവലംബം',
'exif-gpsimgdirection'             => 'ചിത്രത്തിന്റെ ദിശ',
'exif-gpsdestlatituderef'          => 'ലക്ഷ്യം വച്ചതിന്റെ അക്ഷാംശത്തിനുള്ള അവലംബം',
'exif-gpsdestlatitude'             => 'ലക്ഷ്യം വച്ചതിന്റെ അക്ഷാംശം',
'exif-gpsdestlongituderef'         => 'ലക്ഷ്യം വച്ചതിന്റെ രേഖാംശത്തിനുള്ള അവലംബം',
'exif-gpsdestlongitude'            => 'ലക്ഷ്യം വച്ചതിന്റെ രേഖാംശം',
'exif-gpsdestdistanceref'          => 'ലക്ഷ്യം വച്ചതിലേയ്ക്കുള്ള ദൂരത്തിനുള്ള അവലംബം',
'exif-gpsdestdistance'             => 'ലക്ഷ്യം വച്ചതിലേയ്ക്കുള്ള ദൂരം',
'exif-gpsprocessingmethod'         => 'ജി.പി.എസ്. പ്രക്രിയയുടെ പേര്',
'exif-gpsareainformation'          => 'GPS പ്രദേശത്തിന്റെ പേര്‌',
'exif-gpsdatestamp'                => 'GPS തീയ്യതി',

# EXIF attributes
'exif-compression-1' => 'ചുരുക്കാത്തത്',

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

'exif-exposureprogram-0' => 'നിർ‌വചിക്കപ്പെട്ടിട്ടില്ല',
'exif-exposureprogram-1' => 'കായികമായി',
'exif-exposureprogram-2' => 'സാധാരണ പ്രോഗ്രാം',
'exif-exposureprogram-3' => 'അപ്പെർച്ചർ മുൻ‌ഗണന',
'exif-exposureprogram-4' => 'ഷട്ടർ മുൻ‌ഗണന',
'exif-exposureprogram-5' => 'ക്രിയേറ്റീവ് പ്രോഗ്രാം (biased toward depth of field)',
'exif-exposureprogram-6' => 'ആക്ഷൻ പ്രോഗ്രാം (biased toward fast shutter speed)',
'exif-exposureprogram-7' => 'പോർട്ടറൈറ്റ് മോഡ് (for closeup photos with the background out of focus)',
'exif-exposureprogram-8' => 'ലാൻഡ് സ്കേപ്പ് മോഡ് (for landscape photos with the background in focus)',

'exif-subjectdistance-value' => '$1 മീറ്റർ',

'exif-meteringmode-0'   => 'അജ്ഞാതം',
'exif-meteringmode-1'   => 'ശരാശരി',
'exif-meteringmode-5'   => 'ശ്രേണി',
'exif-meteringmode-6'   => 'ഭാഗികം',
'exif-meteringmode-255' => 'മറ്റുള്ളവ',

'exif-lightsource-0'   => 'അജ്ഞാതം',
'exif-lightsource-1'   => 'പകൽ‌പ്രകാശം',
'exif-lightsource-2'   => 'ഫ്ലൂറോസെന്റ്',
'exif-lightsource-3'   => 'ടങ്ങ്സ്റ്റൺ (ധവള പ്രകാശം)',
'exif-lightsource-4'   => 'ഫ്ലാഷ്',
'exif-lightsource-9'   => 'തെളിഞ്ഞ കാലാവസ്ഥ',
'exif-lightsource-10'  => 'മൂടിക്കെട്ടിയ കാലാവസ്ഥ',
'exif-lightsource-11'  => 'തണൽ',
'exif-lightsource-12'  => 'പകൽ‌വെളിച്ച ഫ്ലൂറോസെന്റ് (D 5700 – 7100K)',
'exif-lightsource-13'  => 'പകൽ വെള്ള ഫ്ലൂറോസെന്റ് (N 4600 – 5400K)',
'exif-lightsource-14'  => 'ശീത വെള്ള ഫ്ലൂറോസെന്റ് (W 3900 – 4500K)',
'exif-lightsource-15'  => 'വെള്ള ഫ്ലൂറോസെന്റ് (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'മാതൃകാ വെളിച്ചം A',
'exif-lightsource-18'  => 'മാതൃകാ വെളിച്ചം B',
'exif-lightsource-19'  => 'മാതൃകാ വെളിച്ചം C',
'exif-lightsource-24'  => 'ഐ.എസ്.ഒ. സ്റ്റുഡിയോ റ്റങ്സ്റ്റൺ',
'exif-lightsource-255' => 'മറ്റു പ്രകാശ സ്രോതസ്സ്',

# Flash modes
'exif-flash-fired-0'    => 'ഫ്ലാഷ് ഉപയോഗിച്ചില്ല',
'exif-flash-fired-1'    => 'ഫ്ലാഷ് ഉപയോഗിച്ചു',
'exif-flash-mode-1'     => 'നിർബന്ധിത ഫ്ലാഷ് അടിയ്ക്കൽ',
'exif-flash-mode-2'     => 'നിർബന്ധിത ഫ്ലാഷ് ഒഴിവാക്കൽ',
'exif-flash-mode-3'     => 'സ്വയം പ്രവർത്തന രീതി',
'exif-flash-function-1' => 'ഫ്ലാഷ് സൗകര്യം ഇല്ല',
'exif-flash-redeye-1'   => 'ചുവന്ന-കണ്ണ് ഒഴിവാക്കുന്ന വിധം',

'exif-focalplaneresolutionunit-2' => 'ഇഞ്ച്',

'exif-sensingmethod-1' => 'നിർ‌വചിക്കപ്പെട്ടിട്ടില്ല',

'exif-scenetype-1' => 'നേരിട്ടു ഛായാഗ്രഹണം ചെയ്ത ചിത്രം',

'exif-customrendered-0' => 'സാധാരണ പ്രക്രിയ',
'exif-customrendered-1' => 'സാമ്പ്രദായിക പ്രക്രിയ',

'exif-exposuremode-0' => 'യാന്തിക എക്സ്പോഷർ',
'exif-exposuremode-1' => 'മാനുവൽ എക്സ്പോഷർ',

'exif-whitebalance-0' => 'യാന്ത്രിക വൈറ്റ് ബാലൻസ്',
'exif-whitebalance-1' => 'മാനുവൽ വൈറ്റ് ബാലൻസ്',

'exif-scenecapturetype-0' => 'സാധാരണം',
'exif-scenecapturetype-1' => 'ലാൻഡ്‌സ്കേപ്പ്',
'exif-scenecapturetype-2' => 'പോർട്ട്‌റൈറ്റ്',
'exif-scenecapturetype-3' => 'രാത്രി ദൃശ്യം',

'exif-gaincontrol-0' => 'ഒന്നുമില്ല',

'exif-contrast-0' => 'സാധാരണം',
'exif-contrast-1' => 'സോഫ്റ്റ്',
'exif-contrast-2' => 'ഹാർഡ്',

'exif-saturation-0' => 'സാധാരണം',
'exif-saturation-1' => 'ലോ സാച്ചുറേഷൻ',
'exif-saturation-2' => 'ഹൈ സാച്ചുറേഷൻ',

'exif-sharpness-0' => 'സാധാരണം',
'exif-sharpness-1' => 'സോഫ്റ്റ്',
'exif-sharpness-2' => 'ഹാർഡ്',

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
'exif-gpsstatus-v' => 'അളവുകളുടെ പരസ്പരപ്രയോഗക്ഷമത',

'exif-gpsmeasuremode-2' => 'ദ്വിമാന അളവ്',
'exif-gpsmeasuremode-3' => 'ത്രിമാന അളവ്',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'കിലോമീറ്റർ/മണിക്കൂർ',
'exif-gpsspeed-m' => 'മൈലുകൾ/മണിക്കൂർ',
'exif-gpsspeed-n' => 'നോട്ടുകൾ (Knots)',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'ശരിക്കുള്ള ദിശ',
'exif-gpsdirection-m' => 'കാന്തിക ദിശ',

# External editor support
'edit-externally'      => 'ഈ പ്രമാണം ഒരു ബാഹ്യ ആപ്ലിക്കേഷൻ ഉപയോഗിച്ച് തിരുത്തുക',
'edit-externally-help' => '(കൂടുതൽ വിവരത്തിനു http://www.mediawiki.org/wiki/Manual:External_editors കാണുക)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'എല്ലാം',
'imagelistall'     => 'എല്ലാം',
'watchlistall2'    => 'എല്ലാം',
'namespacesall'    => 'എല്ലാം',
'monthsall'        => 'എല്ലാം',
'limitall'         => 'എല്ലാം',

# E-mail address confirmation
'confirmemail'              => 'ഇമെയിൽ വിലാസം സ്ഥിരീകരിക്കൽ',
'confirmemail_noemail'      => '[[Special:Preferences|താങ്കളുടെ ക്രമീകരണങ്ങളുടെ കൂടെ]] സാധുവായൊരു ഇമെയിൽ വിലാസം സജ്ജീകരിച്ചിട്ടില്ല.',
'confirmemail_text'         => '{{SITENAME}} സം‌രംഭത്തിൽ ഇമെയിൽ സൗകര്യം ഉപയോഗിക്കണമെങ്കിൽ താങ്കൾ താങ്കളുടെ ഇമെയിൽ വിലാസത്തിന്റെ സാധുത തെളിയിച്ചിരിക്കണം. താങ്കളുടെ ഇമെയിൽ വിലാസത്തിലേക്ക് സ്ഥിരീകരണ മെയിൽ അയക്കുവാൻ താഴെയുള്ള ബട്ടൺ അമർത്തുക. താങ്കൾക്ക് അയക്കുന്ന ഇമെയിലിൽ ഒരു സ്ഥിരീകരണ കോഡ് ഉണ്ട്. ആ കോഡിൽ അമർത്തിയാൽ താങ്കളുടെ വിലാസത്തിന്റെ സാധുത തെളിയിക്കപ്പെടും.',
'confirmemail_pending'      => 'താങ്കളുടെ അംഗത്വം ഈ അടുത്ത് ഉണ്ടാക്കിയതാണെങ്കിൽ,  ഒരു സ്ഥിരീകരണ കോഡ് താങ്കൾക്ക് ഇമെയിൽ ചെയ്തിട്ടുണ്ട്.  പുതിയ സ്ഥിരീകരണ കോഡ് ആവശ്യപ്പെടാൻ ശ്രമിക്കുന്നതിനു മുൻപ് ആദ്യത്തെ സ്ഥിരീകരണ കോഡിനായി കുറച്ച് സമയം കാത്തിരിക്കൂ.',
'confirmemail_send'         => 'സ്ഥിരീകരണ കോഡ് (confirmation code) മെയിൽ ചെയ്യുക',
'confirmemail_sent'         => 'സ്ഥിരീകരണ ഇമെയിൽ അയച്ചിരിക്കുന്നു.',
'confirmemail_oncreate'     => 'ഒരു സ്ഥിരീകരണ കോഡ് താങ്കളുടെ ഇമെയിൽ വിലാസത്തിലേക്ക് അയച്ചിട്ടുണ്ട്.
ലോഗിൻ ചെയ്യുന്നതിനു ഈ കോഡ് ആവശ്യമില്ല. പക്ഷെ വിക്കിയിൽ ഇമെയിലുമായി ബന്ധപ്പെട്ട സേവനങ്ങൾ ഉപയോഗിക്കുന്നതിനു മുൻപ് പ്രസ്തുത കോഡ് ഉപയോഗിച്ച് ഇമെയിൽ സ്ഥിരീകരിച്ചിരിക്കണം.',
'confirmemail_sendfailed'   => '{{SITENAME}} സം‌രംഭത്തിന്‌ സ്ഥിരീകരണ ഇമെയിൽ അയക്കുവാൻ സാധിച്ചില്ല. വിലാസത്തിൽ സാധുവല്ലാത്ത അക്ഷരങ്ങൾ ഉണ്ടോ എന്നു ദയവായി  പരിശോധിക്കുക.

ഇമെയിൽ അയക്കാൻ ശ്രമിച്ചപ്പോൾ ലഭിച്ച മറുപടി: $1',
'confirmemail_invalid'      => 'അസാധുവായ സ്ഥിരീകരണ കോഡ്. കോഡിന്റെ കാലാവധി തീർന്നിരിക്കണം.',
'confirmemail_needlogin'    => 'ഇമെയിൽ വിലാസം സ്ഥിരീകരിക്കാൻ താങ്കൾ $1 ചെയ്തിരിക്കണം.',
'confirmemail_success'      => 'താങ്കളുടെ ഇമെയിൽ വിലാസം സ്ഥിരീകരിക്കപ്പെട്ടിരിക്കുന്നു. താങ്കൾക്ക് ഇനി [[Special:UserLogin|ലോഗിൻ ചെയ്ത്]] വിക്കി ആസ്വദിക്കാം.',
'confirmemail_loggedin'     => 'താങ്കളുടെ ഇമെയിൽ വിലാസം സ്ഥിരീകരിക്കപ്പെട്ടിരിക്കുന്നു.',
'confirmemail_error'        => 'താങ്കളുടെ സ്ഥിരീകരണം സൂക്ഷിച്ചുവയ്ക്കാനുള്ള ശ്രമത്തിനിടയ്ക്ക് എന്തോ പിഴവ് സംഭവിച്ചു.',
'confirmemail_subject'      => '{{SITENAME}} ഇമെയിൽ വിലാസ സ്ഥിരീകരണം',
'confirmemail_body'         => '$1 എന്ന ഐ.പി. വിലാസത്തിൽ നിന്നു (ഒരു പക്ഷെ താങ്കളായിരിക്കാം), "$2" എന്ന പേരോടു കൂടിയും ഈ ഇമെയിൽ വിലാസത്തോടു കൂടിയും {{SITENAME}} സം‌രംഭത്തിൽ ഒരു അംഗത്വം സൃഷ്ടിച്ചിരിക്കുന്നു.

ഈ അംഗത്വം താങ്കളുടേതാണ്‌ എന്നു സ്ഥിരീകരിക്കുവാനും {{SITENAME}} സം‌രംഭത്തിൽ ഇമെയിലുമായി ബന്ധപ്പെട്ട സേവനങ്ങൾ ഉപയോഗിക്കുവാനും താഴെ കാണുന്ന കണ്ണി ബ്രൗസറിൽ തുറക്കുക.

$3

അംഗത്വം ഉണ്ടാക്കിയത് താങ്കളല്ലെങ്കിൽ ഇമെയിൽ വിലാസ സ്ഥിരീകരണം റദ്ദാക്കുവാൻ താഴെയുള്ള കണ്ണി ബ്രൗസറിൽ തുറക്കുക.  

$5


ഈ സ്ഥിരീകരണ കോഡിന്റെ കാലാവധി  $4 നു തീരും.',
'confirmemail_body_changed' => '$1 എന്ന ഐ.പി. വിലാസത്തിൽ നിന്നും ആരോ ഒരാൾ, മിക്കവാറും താങ്കളായിരിക്കാം, {{SITENAME}} സംരംഭത്തിലെ "$2" എന്ന അംഗത്വത്തിന്റെ ഇമെയിൽ വിലാസം, ഈ വിലാസമായി മാറ്റി നൽകിയിരിക്കുന്നു.

ഈ അംഗത്വം ശരിക്കും താങ്കളുടേതാണെന്ന് സ്ഥിരീകരിക്കാനും, {{SITENAME}} സംരംഭത്തിൽ ഇമെയിൽ സൗകര്യങ്ങൾ പുനർസജ്ജമാക്കാനും, താങ്കളുടെ ബ്രൗസറിൽ ഇനി നൽകുന്ന കണ്ണി തുറക്കുക:

$3

അംഗതം താങ്കളുടെത് *അല്ല* എന്നുണ്ടെങ്കിൽ ഇമെയിൽ വിലാസത്തിന്റെ സ്ഥിരീകരണം റദ്ദാക്കുവാൻ താഴെ നൽകിയിരിക്കുന്ന കണ്ണി പരിശോധിക്കുക:

$5

ഈ സ്ഥിരീകരണ സൗകര്യം $4-നു അവസാനിക്കുന്നതാണ്.',
'confirmemail_invalidated'  => 'ഇമെയിൽ വിലാസത്തിന്റെ സ്ഥിരീകരണം റദ്ദാക്കിയിരിക്കുന്നു',
'invalidateemail'           => 'ഇമെയിൽ വിലാസ സ്ഥിരീകരണം റദ്ദാക്കുക',

# Scary transclusion
'scarytranscludedisabled' => '[അന്തർവിക്കി ഉൾപ്പെടുത്തൽ സജ്ജമല്ല]',
'scarytranscludefailed'   => '[$1നു ഫലകം കണ്ടുപിടിക്കാൻ പറ്റിയില്ല]',
'scarytranscludetoolong'  => '[URLനു നീളം വളരെ കൂടുതലാണ്]',

# Trackbacks
'trackbackbox'      => 'ഈ താളിനുള്ള പിന്തുടരലുകൾ: <br />
$1',
'trackbackremove'   => '([$1 മായ്ക്കുക])',
'trackbacklink'     => 'പിന്തുടരൽ',
'trackbackdeleteok' => 'ഈ പിന്തുടരൽ വിജയകരമായി മായ്ച്ചിരിക്കുന്നു.',

# Delete conflict
'deletedwhileediting' => "'''മുന്നറിയിപ്പ്''': താങ്കൾ തിരുത്തുവാൻ തുടങ്ങിയ ശേഷം താൾ മായ്ക്കപ്പെട്ടിരിക്കുന്നു!",
'confirmrecreate'     => "താങ്കൾ ഈ താൾ തിരുത്താൻ തുടങ്ങിയതിനുശേഷം [[User:$1|$1]] ([[User talk:$1|talk]]) എന്ന ഉപയോക്താവ് ഇങ്ങനെ ഒരു കാരണം നൽകി ഈ താൾ നീക്കം ചെയ്തു:
: ''$2''
ദയവായി താൾ പുനഃസൃഷ്ടിക്കേണ്ടതുണ്ടോ എന്ന് സ്ഥിരീകരിക്കുക.",
'recreate'            => 'പുനഃസൃഷ്ടിക്കുക',

# action=purge
'confirm_purge_button' => 'ശരി',
'confirm-purge-top'    => 'ഈ താളിന്റെ കാഷെ ക്ലീയർ ചെയ്യട്ടെ?',
'confirm-purge-bottom' => 'താൾ ശുദ്ധീകരിക്കുമ്പോൾ കാഷെ ഒഴിവാക്കുകയും, ഏറ്റവും പുതിയ പതിപ്പ് പ്രത്യക്ഷപ്പെടാൻ സമ്മർദ്ദം ചെലുത്തുകയും ചെയ്യുന്നതാണ്.',

# Multipage image navigation
'imgmultipageprev' => '← മുൻപത്തെ താൾ',
'imgmultipagenext' => 'അടുത്ത താൾ →',
'imgmultigo'       => 'പോകൂ!',
'imgmultigoto'     => '$1 താളിലേക്ക് പോകുക',

# Table pager
'ascending_abbrev'         => 'ആരോഹണം',
'descending_abbrev'        => 'അവരോഹണം',
'table_pager_next'         => 'അടുത്ത താൾ',
'table_pager_prev'         => 'മുൻപത്തെ താൾ',
'table_pager_first'        => 'ആദ്യത്തെ താൾ',
'table_pager_last'         => 'അവസാനത്തെ താൾ',
'table_pager_limit'        => 'ഓരോ താളിലും $1 ഇനങ്ങൾ വീതം പ്രദർശിപ്പിക്കുക',
'table_pager_limit_submit' => 'പോകൂ',
'table_pager_empty'        => 'ഫലങ്ങൾ ഒന്നുമില്ല',

# Auto-summaries
'autosumm-blank'   => 'താൾ ശൂന്യമാക്കി',
'autosumm-replace' => 'താളിലെ വിവരങ്ങൾ $1 എന്നാക്കിയിരിക്കുന്നു',
'autoredircomment' => '[[$1]] എന്ന താളിലേക്ക് തിരിച്ചുവിടുന്നു',
'autosumm-new'     => "'$1' താൾ സൃഷ്ടിച്ചിരിക്കുന്നു",

# Live preview
'livepreview-loading' => 'ശേഖരിച്ചുകൊണ്ടിരിക്കുന്നു…',
'livepreview-ready'   => 'ശേഖരിച്ചുകൊണ്ടിരിക്കുന്നു… തയ്യാർ!',
'livepreview-failed'  => 'തൽസമയ പ്രിവ്യൂ പരാജയപ്പെട്ടു. സാധാരണ പ്രിവ്യൂ പരീക്ഷിക്കുക.',
'livepreview-error'   => 'ബന്ധപ്പെടൽ പരാജയപ്പെട്ടു.  $1 "$2".
ദയവായി സാധാരണ പ്രിവ്യൂ ശ്രമിക്കുക.',

# Friendlier slave lag warnings
'lag-warn-normal' => '$1 {{PLURAL:$1|സെക്കന്റിനുള്ളിൽ|സെക്കന്റുകൾക്കുള്ളിൽ}} നടന്ന തിരുത്തലുകൾ ഈ പട്ടികയിൽ ഉണ്ടാകാനിടയില്ല.',
'lag-warn-high'   => 'ഡേറ്റാബേസ് സെർ‌വറിന്റെ കൂടിയ താമസം മൂലം, {{PLURAL:$1|ഒരു സെക്കന്റിൽ|$1 സെക്കന്റുകളിൽ}} നടന്ന മാറ്റങ്ങൾ പട്ടികയിൽ കാണണമെന്നില്ല.',

# Watchlist editor
'watchlistedit-numitems'       => 'താങ്കൾ സം‌വാദം താളുകൾ ഒഴിച്ച് {{PLURAL:$1|ഒരു താൾ|$1 താളുകൾ}} ശ്രദ്ധിക്കുന്നുണ്ട്.',
'watchlistedit-noitems'        => 'താങ്കൾ നിലവിൽ ഒരു താളും ശ്രദ്ധിക്കുന്നില്ല.',
'watchlistedit-normal-title'   => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക തിരുത്തുക',
'watchlistedit-normal-legend'  => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിൽ നിന്നും ഒഴിവാക്കുക',
'watchlistedit-normal-explain' => "താങ്കൾ ശ്രദ്ധിക്കുന്ന താളുകൾ താഴെ കൊടുത്തിരിക്കുന്നു. നീക്കം ചെയ്യേണ്ടവ തിരഞ്ഞെടുത്ത ശേഷം '''{{int:Watchlistedit-normal-submit}}''' എന്ന ബട്ടണിൽ ഞെക്കിയാൽ നീക്കം ചെയ്യപ്പെടുന്നതാണ്‌. താങ്കൾക്ക് [[Special:Watchlist/raw|പട്ടികയുടെ മൂല രൂപം]] തിരുത്തുകയും ചെയ്യാവുന്നതാണ്‌.",
'watchlistedit-normal-submit'  => 'തിരഞ്ഞെടുത്തവ നീക്കുക',
'watchlistedit-normal-done'    => '{{PLURAL:$1|ഒരു താൾ|$1 താളുകൾ}} താങ്കൾ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിൽ നിന്നും ഒഴിവാക്കിയിരിക്കുന്നു:',
'watchlistedit-raw-title'      => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയുടെ മൂലരൂപം തിരുത്തുക',
'watchlistedit-raw-legend'     => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയുടെ മൂലരൂപം തിരുത്തുക',
'watchlistedit-raw-explain'    => 'താങ്കളുടെ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയിലുള്ള താളുകൾ താഴെ കാണിച്ചിരിക്കുന്നു. ഒരു വരിയിൽ ഒരു താൾ മാത്രം വരത്തക്ക രീതിയിൽ ഈ പട്ടിക തിരുത്തി താളുകൾ കൂട്ടിച്ചേർക്കുകയോ ഒഴിവാക്കുകയോ ചെയ്യാം. തിരുത്തൽ പൂർത്തിയായാൽ "{{int:Watchlistedit-raw-submit}}"എന്ന ബട്ടൻ ഞെക്കുക.

[[Special:Watchlist/edit|ശ്രദ്ധിക്കുന്ന താളിന്റെ പട്ടിക തിരുത്തുക]] എന്ന താൾ ഉപയോഗിച്ചും താങ്കൾക്ക് പട്ടിക പുതുക്കാവുന്നതാണ്‌.',
'watchlistedit-raw-titles'     => 'തലക്കെട്ടുകൾ:',
'watchlistedit-raw-submit'     => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക പുതുക്കുക',
'watchlistedit-raw-done'       => 'താങ്കളുടെ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക പുതുക്കിയിരിക്കുന്നു.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 താൾ|$1 താളുകൾ}} പട്ടികയിലേക്കു ചേർത്തിരിക്കുന്നു:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 താൾ|$1 താളുകൾ}} പട്ടികയിൽ നിന്നു മാറ്റിയിരിക്കുന്നു:',

# Watchlist editing tools
'watchlisttools-view' => 'ബന്ധപ്പെട്ട മാറ്റങ്ങൾ കാട്ടുക',
'watchlisttools-edit' => 'ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടിക കാട്ടുക, തിരുത്തുക',
'watchlisttools-raw'  => 'താങ്കൾ ശ്രദ്ധിക്കുന്ന താളുകളുടെ പട്ടികയുടെ മൂലരൂപം തിരുത്തുക',

# Core parser functions
'unknown_extension_tag' => 'അജ്ഞാതമായ അനുബന്ധ റ്റാഗ് "$1"',
'duplicate-defaultsort' => '\'\'\'മുന്നറിയിപ്പ്:\'\'\' ക്രമപ്പെടുത്താനുള്ള ചാവിയായ "$2" മുമ്പ് ക്രമപ്പെടുത്താനുള്ള ചാവിയായിരുന്ന "$1" എന്നതിനെ അതിലംഘിക്കുന്നു.',

# Special:Version
'version'                          => 'പതിപ്പ്',
'version-extensions'               => 'ഇൻസ്റ്റോൾ ചെയ്തിട്ടുള്ള അനുബന്ധങ്ങൾ',
'version-specialpages'             => 'പ്രത്യേക താളുകൾ',
'version-variables'                => 'ചരങ്ങൾ',
'version-other'                    => 'മറ്റുള്ളവ',
'version-mediahandlers'            => 'മീഡിയ കൈകാര്യോപകരണങ്ങൾ',
'version-hooks'                    => 'കൊളുത്തുകൾ',
'version-extension-functions'      => 'അനുബന്ധങ്ങളുടെ കർത്തവ്യങ്ങൾ',
'version-skin-extension-functions' => 'ദൃശ്യരൂപാനുബന്ധങ്ങളുടെ കർത്തവ്യങ്ങൾ',
'version-hook-name'                => 'കൊളുത്തിന്റെ പേര്',
'version-hook-subscribedby'        => 'വരിക്കാരനായത്',
'version-version'                  => '(പതിപ്പ് $1)',
'version-license'                  => 'ലൈസൻസ്',
'version-software'                 => 'ഇൻസ്റ്റാൾ ചെയ്ത സോഫ്റ്റ്‌വെയർ',
'version-software-product'         => 'സോഫ്റ്റ്‌വെയർ ഉല്പ്പന്നം',
'version-software-version'         => 'വിവരണം',

# Special:FilePath
'filepath'         => 'പ്രമാണത്തിലേക്കുള്ള വിലാസം',
'filepath-page'    => 'പ്രമാണം:',
'filepath-submit'  => 'പോകൂ',
'filepath-summary' => 'ഈ പ്രത്യേക താൾ ഒരു പ്രമാണത്തിന്റെ പൂർണ്ണ വിലാസം പ്രദർശിപ്പിക്കുന്നു.
ചിത്രങ്ങൾ പൂർണ്ണ റെസലൂഷനോടു കൂടി പ്രദർശിപ്പിച്ചിരിക്കുന്നു. മറ്റുള്ള പ്രമാണ തരങ്ങൾ അതതു പ്രോഗ്രാമിൽ നേരിട്ടു തുറക്കാവുന്നതാണ്‌.

"{{ns:file}}:" എന്ന മുൻ‌കുറി ഇല്ലാതെ പ്രമാണത്തിന്റെ പേരു ടൈപ്പു ചെയൂക.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'ഒരേ പ്രമാണത്തിന്റെ പലപകർപ്പുകളുണ്ടോയെന്നു തിരയുക',
'fileduplicatesearch-summary'  => 'ഒരേ പ്രമാണം തന്നെ വിവിധപേരിലുണ്ടോയെന്നു ഹാഷ് വാല്യൂവധിഷ്ഠിതമായി തിരയുക.

പ്രമാണത്തിന്റെ പേര്‌ "{{ns:file}}:" എന്ന പൂർവ്വപദമില്ലാതെ നൽകുക.',
'fileduplicatesearch-legend'   => 'അപരനെ തിരയുക',
'fileduplicatesearch-filename' => 'പ്രമാണത്തിന്റെ പേര്:',
'fileduplicatesearch-submit'   => 'തിരയൂ',
'fileduplicatesearch-info'     => '$1 × $2 ബിന്ദു<br /> പ്രമാണത്തിന്റെ വലിപ്പം: $3<br />മൈം തരം: $4',
'fileduplicatesearch-result-1' => '"$1" എന്ന പ്രമാണത്തിന് സദൃശ അപരനില്ല.',
'fileduplicatesearch-result-n' => '"$1" എന്ന പ്രമാണത്തിന് {{PLURAL:$2|ഒരു സദൃശ അപരൻ|$2 സദൃശ അപരർ}} ഉണ്ട്.',

# Special:SpecialPages
'specialpages'                   => 'പ്രത്യേക താളുകൾ',
'specialpages-note'              => '----
* സർ‌വോപയോഗ പ്രത്യേക താളുകൾ.
* <strong class="mw-specialpagerestricted">ഉപയോഗം പരിമിതപ്പെടുത്തിയിരിക്കുന്ന പ്രത്യേക താളുകൾ.</strong>',
'specialpages-group-maintenance' => 'പരിചരണം ആവശ്യമായവ',
'specialpages-group-other'       => 'മറ്റു പ്രത്യേക താളുകൾ',
'specialpages-group-login'       => 'പ്രവേശിക്കുക / അംഗത്വം എടുക്കുക',
'specialpages-group-changes'     => 'പുതിയ മാറ്റങ്ങളും രേഖകളും',
'specialpages-group-media'       => 'മീഡിയ രേഖകളും അപ്‌ലോഡുകളും',
'specialpages-group-users'       => 'ഉപയോക്താക്കളും അവകാശങ്ങളും',
'specialpages-group-highuse'     => 'കൂടുതൽ ഉപയോഗിക്കപ്പെട്ട താളുകൾ',
'specialpages-group-pages'       => 'താളുകളുടെ പട്ടിക',
'specialpages-group-pagetools'   => 'താളുകൾക്കുള്ള ഉപകരണങ്ങൾ',
'specialpages-group-wiki'        => 'വിക്കി വിവരങ്ങളും ഉപകരണങ്ങളും',
'specialpages-group-redirects'   => 'തിരിച്ചുവിടൽ സംബന്ധിച്ച പ്രത്യേക താളുകൾ',
'specialpages-group-spam'        => 'പാഴ് എഴുത്ത് ഉപകരണങ്ങൾ',

# Special:BlankPage
'blankpage'              => 'ശൂന്യതാൾ',
'intentionallyblankpage' => 'ഈ താൾ മനഃപൂർ‌വ്വം ശൂന്യമായി ഇട്ടിരിക്കുന്നതാണ്‌.',

# Special:Tags
'tags'                    => 'സാധുവായ മാറ്റങ്ങളുടെ അനുബന്ധങ്ങൾ',
'tag-filter'              => '[[Special:Tags|അനുബന്ധങ്ങളുടെ]] അരിപ്പ:',
'tag-filter-submit'       => 'അരിപ്പ',
'tags-title'              => 'അനുബന്ധങ്ങൾ',
'tags-intro'              => 'സോഫ്റ്റ്‌വെയർ അടയാളപ്പെടുത്തിയ തിരുത്തുകളുടെ അനുബന്ധങ്ങളും, അവയുടെ അർത്ഥവും ഈ താളിൽ പ്രദർശിപ്പിക്കുന്നു.',
'tags-tag'                => 'റ്റാഗിന്റെ പേര്‌',
'tags-display-header'     => 'മാറ്റങ്ങളുടെ പട്ടികകളിലെ രൂപം',
'tags-description-header' => 'അർത്ഥത്തിന്റെ പൂർണ്ണ വിവരണം',
'tags-hitcount-header'    => 'അനുബന്ധമുള്ള മാറ്റങ്ങൾ',
'tags-edit'               => 'തിരുത്തുക',
'tags-hitcount'           => '$1 {{PLURAL:$1|മാറ്റം|മാറ്റങ്ങൾ}}',

# Database error messages
'dberr-header'      => 'ഈ വിക്കിയിൽ പ്രശ്നമുണ്ട്',
'dberr-problems'    => 'ക്ഷമിക്കണം! ഈ സൈറ്റിൽ സങ്കേതിക തകരാറുകൾ അനുഭവപ്പെടുന്നുണ്ട്.',
'dberr-again'       => 'കുറച്ച് മിനിട്ടുകൾ കാത്തിരുന്ന് വീണ്ടും തുറക്കുവാൻ ശ്രമിക്കുക.',
'dberr-info'        => '($1 എന്ന വിവരശേഖര സെർവറുമായി ബന്ധപ്പെടാൻ പറ്റിയില്ല)',
'dberr-usegoogle'   => 'അതേസമയം താങ്കൾക്ക് ഗൂഗിൾ വഴി തിരയുവാൻ ശ്രമിക്കാവുന്നതാണ്.',
'dberr-outofdate'   => 'ഞങ്ങളുടെ ഉള്ളടക്കത്തിന്റെ സൂചികകൾ കാലഹരണപ്പെട്ടതാകാമെന്ന് ഓർക്കുക.',
'dberr-cachederror' => 'ആവശ്യപ്പെട്ട താളിന്റെ കാഷ് ചെയ്യപ്പെട്ട പകർപ്പാണിത്, ഇത് ഇപ്പോഴുള്ളതാകണമെന്നില്ല.',

# HTML forms
'htmlform-invalid-input'       => 'താങ്കൾ നൽകിയ ചില വിവരങ്ങളിൽ അപാകതകളുണ്ട്',
'htmlform-select-badoption'    => 'താങ്കൾ നൽകിയ വില ഒരു സ്വീകാര്യമായ ഉപാധിയല്ല.',
'htmlform-int-invalid'         => 'താങ്കൾ നൽകിയ വില ഒരു പൂർണ്ണസംഖ്യയല്ല.',
'htmlform-float-invalid'       => 'താങ്കൾ നൽകിയ വില ഒരു അക്കമല്ല.',
'htmlform-int-toolow'          => 'താങ്കൾ നൽകിയത് ഏറ്റവും കുറഞ്ഞ വിലയായ $1-നു താഴെയാണ്',
'htmlform-int-toohigh'         => 'താങ്കൾ നൽകിയത് ഏറ്റവും കൂടിയ വിലയായ $1-നു മുകളിലാണ്',
'htmlform-required'            => 'ഈ മൂല്യം ആവശ്യമാണ്',
'htmlform-submit'              => 'സമർപ്പിക്കുക',
'htmlform-reset'               => 'മാറ്റങ്ങൾ വേണ്ട',
'htmlform-selectorother-other' => 'മറ്റുള്ളവ',

# Add categories per AJAX
'ajax-add-category'            => 'വർഗ്ഗം കൂട്ടിച്ചേർക്കുക',
'ajax-add-category-submit'     => 'കൂട്ടിച്ചേർക്കുക',
'ajax-confirm-title'           => 'പ്രവൃത്തി സ്ഥിരീകരിക്കുക',
'ajax-confirm-prompt'          => 'താങ്കൾക്ക് തിരുത്തലിന്റെ ചുരുക്കം താഴെ നൽകാവുന്നതാണ്‌.
"സേവ് ചെയ്യുക" എന്നത് ഞെക്കി താങ്കളുടെ തിരുത്തൽ സേവ് ചെയ്യാവുന്നതാണ്‌.',
'ajax-confirm-save'            => 'സേവ് ചെയ്യുക',
'ajax-add-category-summary'    => 'വർഗ്ഗം "$1" കൂട്ടിച്ചേർക്കുക',
'ajax-remove-category-summary' => 'വർഗ്ഗം "$1" നീക്കംചെയ്യുക',
'ajax-confirm-actionsummary'   => 'ചെയ്യേണ്ട പ്രവൃത്തി:',
'ajax-error-title'             => 'പിഴവ്',
'ajax-error-dismiss'           => 'ശരി',
'ajax-remove-category-error'   => 'ഈ വർഗ്ഗം മാറ്റാൻ കഴിയില്ല.
ഫലകങ്ങളുപയോഗിച്ചാണ് വർഗ്ഗം ചേർത്തിരിക്കുന്നതെങ്കിൽ സാധാരണ ഇങ്ങനെ സംഭവിക്കാറുണ്ട്.',

);

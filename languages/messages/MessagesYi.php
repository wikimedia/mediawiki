<?php
/** Yiddish (ייִדיש)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Chaim Shel
 * @author Jiddisch
 * @author Joystick
 * @author Kaganer
 * @author Reedy
 * @author Teak
 * @author Yidel
 * @author ווארצגאנג
 * @author לערי ריינהארט
 * @author פוילישער
 */

$fallback = 'he';

$rtl = true;

$namespaceNames = array(
	NS_MEDIA            => 'מעדיע',
	NS_SPECIAL          => 'באַזונדער',
	NS_TALK             => 'רעדן',
	NS_USER             => 'באַניצער',
	NS_USER_TALK        => 'באַניצער_רעדן',
	NS_PROJECT_TALK     => '$1_רעדן',
	NS_FILE             => 'טעקע',
	NS_FILE_TALK        => 'טעקע_רעדן',
	NS_MEDIAWIKI        => 'מעדיעװיקי',
	NS_MEDIAWIKI_TALK   => 'מעדיעװיקי_רעדן',
	NS_TEMPLATE         => 'מוסטער',
	NS_TEMPLATE_TALK    => 'מוסטער_רעדן',
	NS_HELP             => 'הילף',
	NS_HELP_TALK        => 'הילף_רעדן',
	NS_CATEGORY         => 'קאַטעגאָריע',
	NS_CATEGORY_TALK    => 'קאַטעגאָריע_רעדן',
);

$namespaceAliases = array(
	'באזונדער' => NS_SPECIAL,
	'באנוצער' => NS_USER,
	'באנוצער_רעדן' => NS_USER_TALK,
	'משתמש' => NS_USER,
	'שיחת_משתמש' => NS_USER_TALK,
	'משתמשת' => NS_USER,
	'שיחת_משתמשת' => NS_USER_TALK,
	'בילד' => NS_FILE,
	'בילד_רעדן' => NS_FILE_TALK,
	'מעדיעוויקי' => NS_MEDIAWIKI,
	'מעדיעוויקי_רעדן' => NS_MEDIAWIKI_TALK,
	'קאטעגאריע' => NS_CATEGORY,
	'קאטעגאריע_רעדן' => NS_CATEGORY_TALK,
	'באניצער' => NS_USER,
	'באניצער_רעדן' => NS_USER_TALK,
);
$namespaceGenderAliases = array(
	NS_USER      => array( 'male' => 'באַניצער', 'female' => 'באַניצערין' ),
	NS_USER_TALK => array( 'male' => 'באַניצער_רעדן', 'female' => 'באַניצערין_רעדן' ),
);

$specialPageAliases = array(
	'Activeusers'               => array( 'טעטיגע_באניצער' ),
	'Allmessages'               => array( 'סיסטעם_מעלדונגען' ),
	'Allpages'                  => array( 'אלע_בלעטער' ),
	'Ancientpages'              => array( 'אוראלטע_בלעטער' ),
	'Blankpage'                 => array( 'ליידיגער_בלאט' ),
	'Block'                     => array( 'בלאקירן' ),
	'BrokenRedirects'           => array( 'צעבראכענע_ווייטערפירונגען' ),
	'Categories'                => array( 'קאטעגאריעס' ),
	'ChangePassword'            => array( 'ענדערן_פאסווארט' ),
	'ComparePages'              => array( 'פארגלייהן_בלעטער' ),
	'Confirmemail'              => array( 'באשטעטיגן_ע-פאסט' ),
	'Contributions'             => array( 'בײַשטײַערונגען' ),
	'CreateAccount'             => array( 'שאפֿן_קאנטע' ),
	'Deadendpages'              => array( 'בלעטער_אן_פארבינדונגען' ),
	'DeletedContributions'      => array( 'אויסגעמעקעטע_בײַשטײַערונגען' ),
	'DoubleRedirects'           => array( 'פארטאפלטע_ווייטערפירונגען' ),
	'Emailuser'                 => array( 'שיקן_אן_ע-פאסט_צום_באניצער' ),
	'Export'                    => array( 'עקספארט' ),
	'Fewestrevisions'           => array( 'ווייניגסטע_רעוויזיעס' ),
	'Import'                    => array( 'אימפארט' ),
	'BlockList'                 => array( 'בלאקירן_ליסטע' ),
	'Listadmins'                => array( 'ליסטע_פון_סיסאפן' ),
	'Listbots'                  => array( 'ליסטע_פון_באטס' ),
	'Listfiles'                 => array( 'בילדער' ),
	'Listredirects'             => array( 'ווייטערפירונגען' ),
	'Listusers'                 => array( 'ליסטע_פון_באניצערס' ),
	'Lockdb'                    => array( 'פארשליסן_דאטנבאזע' ),
	'Log'                       => array( 'לאגביכער' ),
	'Lonelypages'               => array( 'פאר\'יתומ\'טע_בלעטער' ),
	'Longpages'                 => array( 'לאנגע_בלעטער' ),
	'MIMEsearch'                => array( 'זוכן_MIME' ),
	'Mostcategories'            => array( 'מערסטע_קאטעגאריעס' ),
	'Mostimages'                => array( 'מערסטע_פארבונדענע_בילדער' ),
	'Mostinterwikis'            => array( 'מערסטע_פארבונדענע_אינטערוויקיס' ),
	'Mostlinked'                => array( 'מערסטע_פארבונדענע_בלעטער' ),
	'Mostlinkedcategories'      => array( 'מערסטע_פארבונדענע_קאטעגאריעס' ),
	'Mostlinkedtemplates'       => array( 'מערסטע_פארבונדענע_מוסטערן' ),
	'Mostrevisions'             => array( 'מערסטע_רעוויזיעס' ),
	'Movepage'                  => array( 'באוועגן_בלאט' ),
	'Mycontributions'           => array( 'מיינע_ביישטייערן' ),
	'Mypage'                    => array( 'מײַן_בלאט' ),
	'Mytalk'                    => array( 'מײַן_שמועס_בלאט' ),
	'Myuploads'                 => array( 'מיינע_ארויפלאדונגען' ),
	'Newimages'                 => array( 'נייע_בילדער' ),
	'Newpages'                  => array( 'נייע_בלעטער' ),
	'Popularpages'              => array( 'פאפולערע_בלעטער' ),
	'Preferences'               => array( 'פרעפערענצן' ),
	'Prefixindex'               => array( 'בלעטער_וואס_הייבן_אן_מיט' ),
	'Protectedpages'            => array( 'געשיצטע_בלעטער' ),
	'Protectedtitles'           => array( 'געשיצטע_קעפלעך' ),
	'Randompage'                => array( 'צופעליג', 'צופעליגער_בלאט' ),
	'Randomredirect'            => array( 'צופעליק_ווײַטערפֿירן' ),
	'Recentchanges'             => array( 'לעצטע_ענדערונגען' ),
	'Revisiondelete'            => array( 'אויסמעקן_ווערסיעס' ),
	'Search'                    => array( 'זוכן' ),
	'Shortpages'                => array( 'קורצע_בלעטער' ),
	'Specialpages'              => array( 'באזונדערע_בלעטער' ),
	'Statistics'                => array( 'סטאטיסטיק' ),
	'Tags'                      => array( 'טאגן' ),
	'Unblock'                   => array( 'אויפבלאקירן' ),
	'Uncategorizedcategories'   => array( 'קאטעגאריעס_אן_קאטעגאריעס' ),
	'Uncategorizedimages'       => array( 'בילדער_אן_קאטעגאריעס' ),
	'Uncategorizedpages'        => array( 'בלעטער_אן_קאטעגאריעס' ),
	'Uncategorizedtemplates'    => array( 'מוסטערן_אן_קאטעגאריעס' ),
	'Unusedcategories'          => array( 'אומבאניצטע_קאטעגאריעס' ),
	'Unusedimages'              => array( 'אומבאניצטע_בילדער' ),
	'Unusedtemplates'           => array( 'אומבאניצטע_מוסטערן' ),
	'Unwatchedpages'            => array( 'נישט_אויפגעפאסטע_בלעטער' ),
	'Upload'                    => array( 'ארויפלאדן' ),
	'Userlogin'                 => array( 'באניצער_איינלאגירן' ),
	'Userlogout'                => array( 'ארויסלאגירן' ),
	'Userrights'                => array( 'באניצער_רעכטן' ),
	'Version'                   => array( 'ווערזיע' ),
	'Wantedcategories'          => array( 'געזוכטע_קאטעגאריעס' ),
	'Wantedfiles'               => array( 'געזוכטע_טעקעס' ),
	'Wantedpages'               => array( 'געזוכטע_בלעטער' ),
	'Wantedtemplates'           => array( 'געזוכטע_מוסטערן' ),
	'Watchlist'                 => array( 'אויפֿפאסן_ליסטע', 'מיין_אויפֿפאסן_ליסטע' ),
	'Whatlinkshere'             => array( 'בלעטער_וואס_פארבונדן_אהער' ),
	'Withoutinterwiki'          => array( 'בלעטער_אָן_אינטערוויקי' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#ווייטערפירן', '#הפניה', '#REDIRECT' ),
	'notoc'                     => array( '0', '__קיין_אינהאלט_טאבעלע__', '__ללא_תוכן_עניינים__', '__ללא_תוכן__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__קיין_גאלעריע__', '__ללא_גלריה__', '__NOGALLERY__' ),
	'toc'                       => array( '0', '__אינהאלט__', '__תוכן_עניינים__', '__תוכן__', '__TOC__' ),
	'noeditsection'             => array( '0', '__נישט_רעדאקטירן__', '__ללא_עריכה__', '__NOEDITSECTION__' ),
	'currentday'                => array( '1', 'לויפיקער_טאג', 'יום נוכחי', 'CURRENTDAY' ),
	'currentyear'               => array( '1', 'לויפֿיקע_יאָר', 'שנה נוכחית', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'לויפֿיקע_צײַט', 'שעה נוכחית', 'CURRENTTIME' ),
	'numberofpages'             => array( '1', 'צאל_בלעטער', 'מספר דפים כולל', 'מספר דפים', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'צאל_ארטיקלען', 'מספר ערכים', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'צאל_טעקעס', 'מספר קבצים', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'צאל_באניצער', 'מספר משתמשים', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'צאל_רעדאקטירונגען', 'מספר עריכות', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'בלאטנאמען', 'שם הדף', 'PAGENAME' ),
	'namespace'                 => array( '1', 'נאמענטייל', 'מרחב השם', 'NAMESPACE' ),
	'fullpagename'              => array( '1', 'פולבלאטנאמען', 'שם הדף המלא', 'FULLPAGENAME' ),
	'subpagename'               => array( '1', 'אונטערבלאטנאמען', 'שם דף המשנה', 'SUBPAGENAME' ),
	'talkpagename'              => array( '1', 'רעדנבלאטנאמען', 'שם דף השיחה', 'TALKPAGENAME' ),
	'subst'                     => array( '0', 'ס:', 'SUBST:' ),
	'img_thumbnail'             => array( '1', 'קליין', 'ממוזער', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'קליין=$1', 'ממוזער=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'רעכטס', 'ימין', 'right' ),
	'img_left'                  => array( '1', 'לינקס', 'שמאל', 'left' ),
	'img_none'                  => array( '1', 'אן', 'ללא', 'none' ),
	'img_width'                 => array( '1', '$1פיקס', '$1 פיקסלים', '$1px' ),
	'img_center'                => array( '1', 'צענטער', 'מרכז', 'center', 'centre' ),
	'img_sub'                   => array( '1', 'אונטער', 'תחתי', 'sub' ),
	'img_super'                 => array( '1', 'איבער', 'עילי', 'super', 'sup' ),
	'img_top'                   => array( '1', 'אויבן', 'למעלה', 'top' ),
	'img_middle'                => array( '1', 'אינמיטן', 'באמצע', 'middle' ),
	'img_bottom'                => array( '1', 'אונטן', 'למטה', 'bottom' ),
	'img_link'                  => array( '1', 'לינק=$1', 'קישור=$1', 'link=$1' ),
	'img_alt'                   => array( '1', 'טעקסט=$1', 'טקסט=$1', 'alt=$1' ),
	'grammar'                   => array( '0', 'גראמאטיק:', 'דקדוק:', 'GRAMMAR:' ),
	'plural'                    => array( '0', 'מערצאל:', 'רבים:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'פֿולער_נאמען:', 'כתובת מלאה:', 'FULLURL:' ),
	'raw'                       => array( '0', 'רוי:', 'ללא עיבוד:', 'RAW:' ),
	'displaytitle'              => array( '1', 'ווייזן_קעפל', 'כותרת תצוגה', 'DISPLAYTITLE' ),
	'language'                  => array( '0', '#שפראך:', '#שפה:', '#LANGUAGE:' ),
	'special'                   => array( '0', 'באזונדער', 'מיוחד', 'special' ),
	'defaultsort'               => array( '1', 'גרונטסארטיר:', 'מיון רגיל:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'hiddencat'                 => array( '1', '__באהאלטענע_קאטעגאריע__', '__באהאלטענע_קאט__', '__קטגוריה_מוסתרת__', '__HIDDENCAT__' ),
	'pagesize'                  => array( '1', 'בלאטגרייס', 'גודל דף', 'PAGESIZE' ),
	'url_wiki'                  => array( '0', 'וויקי', 'ויקי', 'WIKI' ),
	'pagesincategory_pages'     => array( '0', 'בלעטער', 'דפים', 'pages' ),
);


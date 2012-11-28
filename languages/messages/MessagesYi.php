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
	'בילד' => NS_FILE,
	'בילד_רעדן' => NS_FILE_TALK,
	'מעדיעוויקי' => NS_MEDIAWIKI,
	'מעדיעוויקי_רעדן' => NS_MEDIAWIKI_TALK,
	'קאטעגאריע' => NS_CATEGORY,
	'קאטעגאריע_רעדן' => NS_CATEGORY_TALK,
	'באניצער' => NS_USER,
	'באניצער_רעדן' => NS_USER_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'טעטיגע_באניצער' ),
	'Allmessages'               => array( 'סיסטעם_מעלדונגען' ),
	'Allpages'                  => array( 'אלע_בלעטער' ),
	'Ancientpages'              => array( 'אוראלטע_בלעטער' ),
	'Blankpage'                 => array( 'ליידיגער_בלאט' ),
	'Block'                     => array( 'בלאקירן' ),
	'Blockme'                   => array( 'בלאקירט_מיך' ),
	'BrokenRedirects'           => array( 'צעבראכענע_ווייטערפירונגען' ),
	'Categories'                => array( 'קאטעגאריעס' ),
	'ChangePassword'            => array( 'ענדערן_פאסווארט' ),
	'Confirmemail'              => array( 'באשטעטיגן_ע-פאסט' ),
	'Contributions'             => array( 'בײַשטײַערונגען' ),
	'CreateAccount'             => array( 'שאפֿן_קאנטע' ),
	'Deadendpages'              => array( 'בלעטער_אן_פארבינדונגען' ),
	'Disambiguations'           => array( 'באדייטן' ),
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
	'Log'                       => array( 'לאגביכער' ),
	'Lonelypages'               => array( 'פאר\'יתומ\'טע_בלעטער' ),
	'Longpages'                 => array( 'לאנגע_בלעטער' ),
	'MIMEsearch'                => array( 'זוכן_MIME' ),
	'Mostcategories'            => array( 'מערסטע_קאטעגאריעס' ),
	'Mostimages'                => array( 'מערסטע_פארבונדענע_בילדער' ),
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
	'redirect'                => array( '0', '#ווייטערפירן', '#הפניה', '#REDIRECT' ),
	'notoc'                   => array( '0', '__קיין_אינהאלט_טאבעלע__', '__ללא_תוכן_עניינים__', '__ללא_תוכן__', '__NOTOC__' ),
	'nogallery'               => array( '0', '__קיין_גאלעריע__', '__ללא_גלריה__', '__NOGALLERY__' ),
	'toc'                     => array( '0', '__אינהאלט__', '__תוכן_עניינים__', '__תוכן__', '__TOC__' ),
	'noeditsection'           => array( '0', '__נישט_רעדאקטירן__', '__ללא_עריכה__', '__NOEDITSECTION__' ),
	'noheader'                => array( '0', '__קיינקעפל__', '__ללא_כותרת__', '__NOHEADER__' ),
	'currentday'              => array( '1', 'לויפיקער_טאג', 'יום נוכחי', 'CURRENTDAY' ),
	'numberofpages'           => array( '1', 'צאל_בלעטער', 'מספר דפים כולל', 'מספר דפים', 'NUMBEROFPAGES' ),
	'numberofarticles'        => array( '1', 'צאל_ארטיקלען', 'מספר ערכים', 'NUMBEROFARTICLES' ),
	'pagename'                => array( '1', 'בלאטנאמען', 'שם הדף', 'PAGENAME' ),
	'namespace'               => array( '1', 'נאמענטייל', 'מרחב השם', 'NAMESPACE' ),
	'fullpagename'            => array( '1', 'פולבלאטנאמען', 'שם הדף המלא', 'FULLPAGENAME' ),
	'subpagename'             => array( '1', 'אונטערבלאטנאמען', 'שם דף המשנה', 'SUBPAGENAME' ),
	'talkpagename'            => array( '1', 'רעדנבלאטנאמען', 'שם דף השיחה', 'TALKPAGENAME' ),
	'subst'                   => array( '0', 'ס:', 'SUBST:' ),
	'img_thumbnail'           => array( '1', 'קליין', 'ממוזער', 'thumbnail', 'thumb' ),
	'img_manualthumb'         => array( '1', 'קליין=$1', 'ממוזער=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'               => array( '1', 'רעכטס', 'ימין', 'right' ),
	'img_left'                => array( '1', 'לינקס', 'שמאל', 'left' ),
	'img_none'                => array( '1', 'אן', 'ללא', 'none' ),
	'img_center'              => array( '1', 'צענטער', 'מרכז', 'center', 'centre' ),
	'img_sub'                 => array( '1', 'אונטער', 'תחתי', 'sub' ),
	'img_top'                 => array( '1', 'אויבן', 'למעלה', 'top' ),
	'img_bottom'              => array( '1', 'אונטן', 'למטה', 'bottom' ),
	'img_link'                => array( '1', 'לינק=$1', 'קישור=$1', 'link=$1' ),
	'img_alt'                 => array( '1', 'טעקסט=$1', 'טקסט=$1', 'alt=$1' ),
	'grammar'                 => array( '0', 'גראמאטיק:', 'דקדוק:', 'GRAMMAR:' ),
	'plural'                  => array( '0', 'מערצאל:', 'רבים:', 'PLURAL:' ),
	'fullurl'                 => array( '0', 'פֿולער_נאמען:', 'כתובת מלאה:', 'FULLURL:' ),
	'raw'                     => array( '0', 'רוי:', 'ללא עיבוד:', 'RAW:' ),
	'displaytitle'            => array( '1', 'ווייזן_קעפל', 'כותרת תצוגה', 'DISPLAYTITLE' ),
	'language'                => array( '0', '#שפראך:', '#שפה:', '#LANGUAGE:' ),
	'defaultsort'             => array( '1', 'גרונטסארטיר:', 'מיון רגיל:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'hiddencat'               => array( '1', '__באהאלטענע_קאטעגאריע__', '__באהאלטענע_קאט__', '__קטגוריה_מוסתרת__', '__HIDDENCAT__' ),
	'pagesize'                => array( '1', 'בלאטגרייס', 'גודל דף', 'PAGESIZE' ),
	'url_wiki'                => array( '0', 'וויקי', 'ויקי', 'WIKI' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'שטרייכט אונטער לינקען',
'tog-highlightbroken'         => 'צייכן אן צובראכענע לינקען <a href="" class="new">אזוי</a> (אדער: אזוי<a href="" class="internal">?</a>)',
'tog-justify'                 => 'גראד אויס פאראגראפן',
'tog-hideminor'               => 'באַהאַלטן מינערדיקע רעדאַקטירונגען אין לעצטע ענדערונגען',
'tog-hidepatrolled'           => 'באַהאַלטן פאַטראלירטע רעדאַקטירונגען אין לעצטע ענדערונגען',
'tog-newpageshidepatrolled'   => 'באַהאַלטן פאַטראלירטע בלעטער פון דער ליסטע פון נײַע בלעטער',
'tog-extendwatchlist'         => 'פארברייטערן די אויפפאסן ליסטע צו צייגן אלע פאסנדע ענדערונגען (אנדערשט: בלויז די לעצטע ענדערונג פון יעדן בלאט)',
'tog-usenewrc'                => 'ניצן פֿאַרבעסערטע לעצטע ענדערונגען (פֿאדערט JavaScript)',
'tog-numberheadings'          => 'נומערירן קעפלעך אויטאמאטיש',
'tog-showtoolbar'             => 'ווײַזן דעם געצייג-שטאנג',
'tog-editondblclick'          => 'ענדערן בלעטער דורך טאפל קליק (JavaScript)',
'tog-editsection'             => 'ערמעגליכט אפטייל ענדערן דורך [ענדערן] לינקס',
'tog-editsectiononrightclick' => 'באמעגליך פאראגראף ענדערונגען דורכן קוועטשן אויפן רעכטן<br />אויף אפטייל קעפל (JavaScript)',
'tog-showtoc'                 => 'ווייז דאס אינהאלט קעסטל<br />(פאר בלעטער מיט מער ווי 3 קעפלעך)',
'tog-rememberpassword'        => 'געדענק מיין אריינלאגירן אין דעם בלעטערער (ביז $1 {{PLURAL:$1|טאָג|טעג}})',
'tog-watchcreations'          => 'צולייגן בלעטער וואס איך באשאף און טעקעס וואס איך לאד ארויף צו מיין אכטונג ליסטע',
'tog-watchdefault'            => 'צולייגן בלעטער וואס איך רעדאקטיר צו מיין אכטונג ליסטע',
'tog-watchmoves'              => 'צולייגן בלעטער וואס איך באוועג און טעקעס וואס איך לאד ארויף צו מיין אכטונג ליסטע',
'tog-watchdeletion'           => 'צולייגן בלעטער וואס איך מעק אויס צו מיין אויפפאסונג ליסטע',
'tog-minordefault'            => 'באגרענעצן אלע רעדאַקטירונגען גרונטלעך אלס מינערדיק',
'tog-previewontop'            => 'צײַג די "פֿאָרויסיגע װײַזונג" גלײַך בײַ דער ערשטער באַאַרבעטונג',
'tog-previewonfirst'          => 'ווייזן פֿאראויסדיגע ווייזונג בײַ דער ערשטער רעדאקטירונג',
'tog-nocache'                 => 'מבטל זײַן האַלטן בלעטער אין זאַפאַס',
'tog-enotifwatchlistpages'    => 'שיקט מיר א בליצבריוו ווען א בלאט וואס איך פאס אויף ווערט געענדערט',
'tog-enotifusertalkpages'     => 'שיקט מיר ע-פאסט ווען עס ווערט געענדערט מיין באניצער רעדן בלאט',
'tog-enotifminoredits'        => 'שיקט מיר ע-פאסט אויך פֿאַר מינערדיקע רעדאַקטירונגען פֿון בלעטער',
'tog-enotifrevealaddr'        => 'דעק אויף מיין בליצפאסט אדרעס אין פאסט מודעות',
'tog-shownumberswatching'     => 'ווייזן דעם נומער פון בלאט אויפֿפאסערס',
'tog-oldsig'                  => 'איצטיגער אונטערשריפֿט:',
'tog-fancysig'                => 'באַהאַנדלן  אונטערשריפט אַלס וויקיטעקסט (אָן אויטאמאטישן לינק)',
'tog-externaleditor'          => 'ניצן א דרויסנדיגן רעדאקטירער גרונטלעך (נאר פֿאר מומחים, דאס פֿאדערט באזונדערע קאמפיוטער שטעלונגען).
[//www.mediawiki.org/wiki/Manual:External_editors ווײַטערע אינפֿארמאַציע.]',
'tog-externaldiff'            => 'ניצן א דרויסנדיגן פֿאַרגלײַכער גרונטלעך (נאר פֿאר מומחים, דאס פֿאדערט באזונדערע קאמפיוטער שטעלונגען)
[//www.mediawiki.org/wiki/Manual:External_editors ווײַטערע אינפֿארמאַציע.]',
'tog-showjumplinks'           => 'באמעגלעך צוטריט לינקס פון "שפרינג צו"',
'tog-uselivepreview'          => 'באנוצט זיך מיט לייוו פאראויסדיגע ווייזונג (JavaScript) (עקספירענמעטל)',
'tog-forceeditsummary'        => 'ווארן מיך ווען איך לייג א ליידיג קורץ ווארט ענדערונג',
'tog-watchlisthideown'        => 'באהאלט מיינע ענדערונגען פון דער אויפפאסן ליסטע',
'tog-watchlisthidebots'       => 'באהאלט באט עדיטס פון אויפפאסן ליסטע',
'tog-watchlisthideminor'      => 'באהאלט קליינע ענדערונגען פון דער אויפפאסן ליסטע',
'tog-watchlisthideliu'        => 'באהאלטן רעדאקטירונגען פון איינלאגירטע באניצערס פון דער אויפֿפאסונג ליסטע',
'tog-watchlisthideanons'      => 'באהאלטן רעדאקטירונגען פון אנאנימע באניצערס פון דער אויפֿפאסונג ליסטע',
'tog-watchlisthidepatrolled'  => 'באַהאַלטן פאַטראלירטע רעדאַקטירונגען פֿון דער אויפֿפאַסונג ליסטע',
'tog-nolangconversion'        => 'זיי מבטל פארשידענארטיגקייט אין קאנווערסאציע',
'tog-ccmeonemails'            => 'שיק מיר קאפיעס פון בליצבריוו וואס איך שיק צו אנדערע באַניצער',
'tog-diffonly'                => 'ווייז נישט אינהאלט אונטער די דיפערענץ',
'tog-showhiddencats'          => 'ווײַז באהאלטענע קאטעגאריעס',
'tog-norollbackdiff'          => 'היפט איבער ווײַזן אונטערשייד נאכן אויספֿירן א צוריקדריי',

'underline-always'  => 'אייביג',
'underline-never'   => 'קיינמאל',
'underline-default' => 'בלעטערער גרונטשטעלונג',

# Font style option in Special:Preferences
'editfont-style'     => 'רעדאקטירונג פאנט סטיל:',
'editfont-default'   => 'בלעטערער גרונט־אויסווייל',
'editfont-monospace' => 'פֿאנט מיט באשטימטער ברייט',
'editfont-sansserif' => 'פאנטס אן קיין תגים (sans-serif)',
'editfont-serif'     => 'סעריף שריפֿט',

# Dates
'sunday'        => 'זונטיג',
'monday'        => 'מאָנטיג',
'tuesday'       => 'דינסטיג',
'wednesday'     => 'מיטװאָך',
'thursday'      => 'דאָנערשטאג',
'friday'        => 'פרייטיג',
'saturday'      => 'שבת',
'sun'           => "זונ'",
'mon'           => "מאנ'",
'tue'           => "דינ'",
'wed'           => "מיט'",
'thu'           => "דאנ'",
'fri'           => "פריי'",
'sat'           => 'שבת',
'january'       => 'יאַנואַר',
'february'      => 'פֿעברואַר',
'march'         => 'מערץ',
'april'         => 'אַפּריל',
'may_long'      => 'מײַ',
'june'          => 'יוני',
'july'          => 'יולי',
'august'        => 'אויגוסט',
'september'     => 'סעפּטעמבער',
'october'       => 'אָקטאָבער',
'november'      => 'נאָוועמבער',
'december'      => 'דעצעמבער',
'january-gen'   => 'יאנואר',
'february-gen'  => 'פעברואר',
'march-gen'     => 'מערץ',
'april-gen'     => 'אפריל',
'may-gen'       => 'מיי',
'june-gen'      => 'יוני',
'july-gen'      => 'יולי',
'august-gen'    => 'אויגוסט',
'september-gen' => 'סעפטעמבער',
'october-gen'   => 'אקטאבער',
'november-gen'  => 'נאוועמבער',
'december-gen'  => 'דעצעמבער',
'jan'           => 'יאַנ׳',
'feb'           => 'פֿעב׳',
'mar'           => 'מער׳',
'apr'           => 'אַפּר׳',
'may'           => 'מײַ',
'jun'           => 'יונ׳',
'jul'           => 'יול׳',
'aug'           => 'אויג׳',
'sep'           => 'סעפּ׳',
'oct'           => 'אָקט׳',
'nov'           => 'נאָוו׳',
'dec'           => 'דעצ׳',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|קאַטעגאָריע|קאַטעגאָריעס}}',
'category_header'                => 'אַרטיקלען אין קאַטעגאָריע "$1"',
'subcategories'                  => 'אונטערקאַטעגאָריעס',
'category-media-header'          => 'מעדיע אין קאטעגאריע "$1"',
'category-empty'                 => "'''די קאטעגאריע האט נישט קיין בלעטער אדער מעדיע.'''",
'hidden-categories'              => '{{PLURAL:$1|באהאלטענע קאטעגאריע|באהאלטענע קאטעגאריעס}}',
'hidden-category-category'       => 'באהאלטענע קאטעגאריעס',
'category-subcat-count'          => '{{PLURAL:$2|די קאטעגאריע האט נאר די פֿאלגנדע אונטער-קאטעגאריע|די קאטעגאריע האט די פֿאלגנדע {{PLURAL:$1|אונטער-קאטעגאריע|$1 אונטער-קאטעגאריעס}}, פֿון $2 קאטעגאריעס אינגאנצן}}.',
'category-subcat-count-limited'  => 'די קאטעגאריע האט די פאלגנדע {{PLURAL:$1|אונטערקאטעגאריע|$1 אונטערקאטעגאריעס}}.',
'category-article-count'         => '{{PLURAL:$2|די קאטעגאריע האט נאר דעם פֿאלגנדן בלאט|די קאטעגאריע האט {{PLURAL:$1| דעם פֿאלגנדן בלאט|די פֿאלגנדע $1 בלעטער}}, פֿון $2 אינגאנצן}}.',
'category-article-count-limited' => 'די קאטעגאריע אנטהאלט {{PLURAL:$1|דעם פאלגנדן בלאט|די פאלגנדע $1 בלעטער}}.',
'category-file-count'            => '{{PLURAL:$2|די קאטעגאריע האט נאר די פֿאלגנדע טעקע|די קאטעגאריע האט די פֿאלגנדע {{PLURAL:$1| טעקע|$1 טעקעס}}, פֿון $2 אינגאנצן}}.',
'category-file-count-limited'    => 'די פֿאלגנדע {{PLURAL:$1|טעקע|$1 טעקעס}} זענען אין דער דאזיגע קאטעגאריע.',
'listingcontinuesabbrev'         => '(המשך)',
'index-category'                 => 'אינדעקסירטע בלעטער',
'noindex-category'               => 'אומאינדעקסירטע בלעטער',
'broken-file-category'           => 'בלעטער מיט צעבראכענע טעקע לינקען',

'about'         => 'וועגן',
'article'       => 'אינהאלט בלאט',
'newwindow'     => '(עפֿנט זיך אין א נײַעם פענסטער)',
'cancel'        => 'זיי מבטל',
'moredotdotdot' => 'נאך…',
'mypage'        => 'מײַן בלאט',
'mytalk'        => 'שמועס',
'anontalk'      => 'דאס רעדן פון דעם IP',
'navigation'    => 'נאַוויגאַציע',
'and'           => '&#32;און',

# Cologne Blue skin
'qbfind'         => 'טרעף',
'qbbrowse'       => 'בלעטערט',
'qbedit'         => 'ענדערן',
'qbpageoptions'  => 'דער בלאט',
'qbpageinfo'     => 'קאנטעקסט',
'qbmyoptions'    => 'מיינע בלעטער',
'qbspecialpages' => 'ספעציעלע בלעטער',
'faq'            => 'מערסטע געפרעגטע פראגעס',
'faqpage'        => 'Project:מערסטע געפרעגט פראגעס',

# Vector skin
'vector-action-addsection'       => 'צושטעלן טעמע',
'vector-action-delete'           => 'אויסמעקן',
'vector-action-move'             => 'באַוועגן',
'vector-action-protect'          => 'שיצן',
'vector-action-undelete'         => 'מבטל זיין אויסמעקן',
'vector-action-unprotect'        => 'ענדערונג באַשיצונג',
'vector-simplesearch-preference' => 'דערמעגלעכן פֿאַרפשוטערטן זוך פאַס (נאר וועקטאר)',
'vector-view-create'             => 'שאַפֿן',
'vector-view-edit'               => 'רעדאַקטירן',
'vector-view-history'            => 'ווײַזן היסטאָריע',
'vector-view-view'               => 'לייענען',
'vector-view-viewsource'         => 'ווײַזן מקור',
'actions'                        => 'אַקציעס',
'namespaces'                     => 'נאָמענטיילן',
'variants'                       => 'װאַריאַנטן',

'errorpagetitle'    => 'פֿעלער',
'returnto'          => 'צוריקקערן צו $1.',
'tagline'           => 'פֿון {{SITENAME}}',
'help'              => 'הילף',
'search'            => 'זוכן',
'searchbutton'      => 'זוכן',
'go'                => 'גיין',
'searcharticle'     => 'גיין',
'history'           => 'בלאט היסטאריע',
'history_short'     => 'היסטאריע',
'updatedmarker'     => 'דערהיינטיגט זינט מיין לעצטע וויזיט',
'printableversion'  => 'דרוק ווערסיע',
'permalink'         => 'שטענדיגער לינק',
'print'             => 'דרוק',
'view'              => 'באַקוקן',
'edit'              => 'רעדאַקטירן',
'create'            => 'שאַפֿן',
'editthispage'      => 'ענדערן דעם בלאט',
'create-this-page'  => 'שאַפֿן דעם בלאַט',
'delete'            => 'אויסמעקן',
'deletethispage'    => 'אויסמעקן דעם בלאַט',
'undelete_short'    => 'צוריקשטעלן {{PLURAL:$1|איין רעדאַקטירונג|$1 רעדאַקטירונגען}}',
'viewdeleted_short' => 'באַקוקן {{PLURAL:$1|איין געמעקטע ענדערונג|$1 געמעקטע ענדערונגען}}',
'protect'           => 'באשיצן',
'protect_change'    => 'טוישן',
'protectthispage'   => 'באשיץ דעם בלאט',
'unprotect'         => 'ענדערונג באַשיצונג',
'unprotectthispage' => 'ענדערן באַשיצונג פון דעם בלאַט',
'newpage'           => 'נייער בלאַט',
'talkpage'          => 'שמועסט איבער דעם בלאט',
'talkpagelinktext'  => 'שמועס',
'specialpage'       => 'ספעציעלער בלאט',
'personaltools'     => 'פערזענלעכע געצייג',
'postcomment'       => 'נייע אפטיילונג',
'articlepage'       => 'זען אינהאַלט בלאַט',
'talk'              => 'שמועס',
'views'             => 'קוקן',
'toolbox'           => 'געצייג קאסטן',
'userpage'          => 'זען באַניצער בלאַט',
'projectpage'       => 'זען פראיעקט בלאַט',
'imagepage'         => 'זען טעקע בלאט',
'mediawikipage'     => 'זען מעלדונג בלאַט',
'templatepage'      => 'זעט מוסטער בלאט',
'viewhelppage'      => 'זעט הילף בלאט',
'categorypage'      => 'זען קאַטעגאריע בלאַט',
'viewtalkpage'      => 'זעט שמועס',
'otherlanguages'    => 'אין אַנדערע שפראַכן',
'redirectedfrom'    => '(אַריבערגעפֿירט פון $1)',
'redirectpagesub'   => 'ווייטערפירן בלאט',
'lastmodifiedat'    => 'דער בלאט איז לעצט געווארן מאדיפיצירט $2, $1.',
'viewcount'         => 'דער בלאט איז געווארן געליינט {{PLURAL:$1|איין מאל|$1 מאל}}.',
'protectedpage'     => 'באשיצטער בלאט',
'jumpto'            => 'שפּרינג צו:',
'jumptonavigation'  => 'נאַוויגאַציע',
'jumptosearch'      => 'זוכן',
'view-pool-error'   => 'אנטשולדיגט, די סערווערס זענען איבערגעפילט איצט.
צופיל באניצער פרובירן צו ליינען דעם בלאט.
ביטע ווארטן א ביסל צייט בעפאר איר פרובירט ווידער אריינגיין אינעם בלאט.

$1',
'pool-timeout'      => 'אַריבער די צײַט וואַרטן פֿאר דער שליסונג',
'pool-queuefull'    => 'ריי איז פֿול',
'pool-errorunknown' => 'אומבאַקאַנטער פֿעלער',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'וועגן {{SITENAME}}',
'aboutpage'            => 'Project:וועגן',
'copyright'            => 'דער אינהאַלט איז בארעכטיגט אונטער $1.',
'copyrightpage'        => '{{ns:project}}:קאפירעכטן',
'currentevents'        => 'אקטועלע געשעענישן',
'currentevents-url'    => 'Project:אקטועלע געשענישען',
'disclaimers'          => 'געזעצליכע אויפֿקלערונג',
'disclaimerpage'       => 'Project:קלארשטעלונג',
'edithelp'             => 'הילף וויאזוי צו ענדערן',
'edithelppage'         => 'Help:ענדערן',
'helppage'             => 'Help:אינהאַלט',
'mainpage'             => 'הויפט זייט',
'mainpage-description' => 'הויפט זייט',
'policy-url'           => 'Project:פאליסי',
'portal'               => 'קאַווע שטיבל',
'portal-url'           => 'Project:קאַווע שטיבל',
'privacy'              => 'פּריוואַטקייט פּאליסי',
'privacypage'          => 'Project:פּריוואַטקייט פאליסי',

'badaccess'        => 'דערלויבניש גרײַז',
'badaccess-group0' => 'איר זענט נישט בארעכטיגט צו טאן די אקציע וואס איר ווילט.',
'badaccess-groups' => 'די אקציע וואס איר האט פארלאנגט צו טאן איז באגרעניצט צו באניצערס אין {{PLURAL:$2|דער גרופע| איינער פון די גרופעס}}: $1.',

'versionrequired'     => 'ווערסיע $1 פֿון מעדיעוויקי געפֿאדערט',
'versionrequiredtext' => 'ווערסיע $1 פֿון מעדיעוויקי איז געפֿאדערט צו ניצן דעם בלאט.
פֿאר מער אינפֿארמאציע זעט [[Special:Version|ווערסיע בלאט]].',

'ok'                      => 'יאָ',
'backlinksubtitle'        => '→ $1',
'retrievedfrom'           => 'גענומען פֿון "$1"',
'youhavenewmessages'      => 'איר האט $1 ($2).',
'newmessageslink'         => 'נייע מעלדונגען',
'newmessagesdifflink'     => 'לעצטע ענדערונג',
'youhavenewmessagesmulti' => 'איר האט נייע מעלדונגען אין $1',
'editsection'             => 'באַאַרבעטן',
'editold'                 => 'רעדאַקטירן',
'viewsourceold'           => 'ווײַזן מקור',
'editlink'                => 'רעדאַקטירן',
'viewsourcelink'          => 'ווײַזן מקור',
'editsectionhint'         => 'ענדערן אפטיילונג: $1',
'toc'                     => 'אינהאַלט',
'showtoc'                 => 'ווײַזן',
'hidetoc'                 => 'באַהאַלטן',
'collapsible-collapse'    => 'אײַנציען',
'collapsible-expand'      => 'פֿאַרברייטערן',
'thisisdeleted'           => 'זען אדער צוריקשטעלן $1?',
'viewdeleted'             => 'זען $1?',
'restorelink'             => '{{PLURAL:$1|איין געמעקטע ענדערונג|$1 געמעקטע ענדערונגען}}',
'feedlinks'               => 'פיטערן:',
'feed-invalid'            => 'אומגילטיק אַבאנאַמענט פֿיטער טיפ.',
'feed-unavailable'        => 'סינדיקאציע פֿיטערן זענען נישט פֿאַראַן',
'site-rss-feed'           => 'RSS פֿאַר $1',
'site-atom-feed'          => 'Atom פֿאַר $1',
'page-rss-feed'           => 'RSS פֿון $1',
'page-atom-feed'          => 'Atom פֿון $1',
'feed-atom'               => 'אטאם',
'feed-rss'                => 'אר.עס.עס.',
'red-link-title'          => '$1 (בלאט טוט נאָך נישט עקזיסטירן)',
'sort-descending'         => 'סארטירן אַראָפ',
'sort-ascending'          => 'סארטירן אַרויף',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'אַרטיקל',
'nstab-user'      => 'באַניצער בלאט',
'nstab-media'     => 'מעדיע בלאט',
'nstab-special'   => 'ספעציעלער בלאט',
'nstab-project'   => 'פראיעקט בלאט',
'nstab-image'     => 'בילד טעקע',
'nstab-mediawiki' => 'מעלדונג',
'nstab-template'  => 'מוסטער',
'nstab-help'      => 'הילף בלאט',
'nstab-category'  => 'קאַטעגאָריע',

# Main script and global functions
'nosuchaction'      => 'נישטא אזא אקציע',
'nosuchactiontext'  => "די אַקציע ספעציפֿירט דורך דעם URL איז נישט גילטיג.
איר האט מעגלעך אַרײַנגעקלאַפט פֿאַלש, אדער נאָכגעפֿאלגט א פֿאַלשן לינק.
ס'קען אויך זײַן א באַג אין דעם ווייכוואַרג געניצט אין {{SITENAME}}.",
'nosuchspecialpage' => 'נישטא אזא ספעציעלער בלאט',
'nospecialpagetext' => "<strong>איר האט געבעטן אן אומגילטיגן באַזונדערבלאט.</strong>

מ'קען טרעפֿן א ליסטע פון אלע באַזונדערבלעטער בײַ [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'                => 'פעלער',
'databaseerror'        => 'דאטנבאזע פעלער',
'dberrortext'          => 'א דאטנבאזע זוכונג סינטאקס גרייז האט פאסירט.
דאס קען זיין צוליב א באג אינעם ווייכווארג.
די לעצטע דאטנבאזע זוכונג איז געווען:
<blockquote><code>$1</code></blockquote>
פון דער פונקציע "<code>$2</code>".
דאטנבאזע האט צוריקגעגעבן גרייז "<samp>$3: $4</samp>".',
'dberrortextcl'        => 'א דאטנבאזע זוכונג סינטאקס גרייז האט פאסירט.
די לעצטע דאטנבאזע זוכונג איז געווען:
"$1"
פון דער פונקציע "$2".
דאטנבאזע האט צוריקגעגעבן גרייז "$3: $4".',
'laggedslavemode'      => 'ווארענונג: בלאט טוט מעגליך נישט אנטהאלטן לעצטיגע דערהײַנטיגונגען.',
'readonly'             => 'דאַטנבאַזע פאַרשפאַרט',
'enterlockreason'      => 'שטעלט א סיבה פארן אפשפאר, אריינגערעכנט א געשאצטער צייט אויף ווען דאס וועט זיך צוריקעפענען די פארשפארונג.',
'readonlytext'         => 'די דאַטנבאַזע איז איצט פארשפארט צו נייע ענדערונגן און מאדיפאקאציעס, ווארשיינליך פאר רוטינע טעכנישע דאטנבאזע אויפהאלטונג, וואס דערנאך וועט דאס צוריק פֿונקציאנירן נארמאל.

דער אדמיניסטראטור וואס האט זי פארשפארט האט אָנגעגעבן די סיבה: $1',
'missing-article'      => 'די דאטנבאזע האט נישט געפונען דעם טעקסט פונעם בלאט וואס זי האט געדארפט טרעפן, מיטן נאמען "$1" $2.

דאס איז אפט פארשאפן דורך אן אלטער פארבינדונג צו א ווערסיע פארגלייכונג אדער א היסטאריע פארבינדונג צו אן אויסגעמעקן בלאט.

טאמער דאס האט נישט פאסירט, קען זיין א באג אין דער ווייכווארג.
זייט אזוי גוט מודיע זיין צו א [[Special:ListUsers/sysop|סיסאפ]],   מערקן אן דעם URL.',
'missingarticle-rev'   => '(רעוויזיע נומער: $1)',
'missingarticle-diff'  => '(אונטערשייד: $1, $2)',
'readonly_lag'         => 'די דאטעבאזע איז געווארן אויטאמטיש אפגעשפארט כדי צו דערמעגליכן פאר די אונטער דאטע באזע סערווערס צו ווערן דערהיינטיגט פון דעם אויבער סערווער.',
'internalerror'        => 'אינערווייניגער פֿעלער',
'internalerror_info'   => 'אינערווייניגער פֿעלער: $1',
'fileappenderrorread'  => 'קען נישט לייענען "$1" בײַם צוגעבן.',
'fileappenderror'      => 'האט נישט געקענט צולייגן "$1" צו "$2".',
'filecopyerror'        => 'האט נישט געקענט קאפירן "$1" צו "$2".',
'filerenameerror'      => 'נאמען טויש פֿאַר "$1" צו "$2" איז נישט אדורכגעגאנגען.',
'filedeleteerror'      => 'אויסמעקן "$1" נישט דורך.',
'directorycreateerror' => 'קען נישט באשאפן דירעקטארי "$1".',
'filenotfound'         => 'קען נישט געפינען טעקע "$1".',
'fileexistserror'      => 'קען נישט שרײַבן צו טעקע "$1": טעקע עקסיסטירט שוין',
'unexpected'           => 'אומערווארטערטער ווערד: "$1"="$2"',
'formerror'            => 'פֿעלער: קען נישט שיקן פֿארעם.',
'badarticleerror'      => 'מען קען נישט טאן די אקציע וואס איר ווילט אויף דעם בלאט.',
'cannotdelete'         => 'נישט געווען מעגלעך אויסמעקן דעם בלאט אדער די טעקע "$1".
קען זיין  אז דאס איז שוין געווארן אויסגעמעקט דורך אן אנדערן.',
'cannotdelete-title'   => 'מען קען נישט אויסמעקן בלאט "$1"',
'badtitle'             => 'שלעכט קעפל',
'badtitletext'         => "דאס קעפל פון דעם געזוכטן בלאט איז געווען אומגעזעצליך, ליידיג, אן אינטערשפראך אדער אינטערוויקי לינק וואס פאסט נישט, אדער אנטהאט כאראקטערס וואס מ'קען נישט ניצן אין א קעפל.",
'perfcached'           => "די פאלגנדע דאטן זענען גענומען פונעם 'זאַפאַס' און מעגלעך נישט אקטועל. מאקסימום {{PLURAL:$1|איין רעזולטאט איז|$1 רעזולטאטן זענען}} פאראן אין זאפאס.",
'perfcachedts'         => 'די פאלגנדע דאטן זענען פונעם זאַפאַס, וואס איז לעצט געווארן דערהײַנטיגט $1. מאקסימום {{PLURAL:$4|איין רעזולטאט איז|$4 רעזולטאטן זענען}} פאראן אין זאפאס',
'querypage-no-updates' => 'דערהיינטיגן דעם בלאט איז איצט אומערמעגלעכט.
דאטן דא וועט נישט דערווייל ווערן באנייט.',
'wrong_wfQuery_params' => 'די פארעמעטערס אריינגפיטערט צו wfQuery() זענען נישט ריכטיג:<br />
פֿונקיציע: $1<br />
פֿארלאנג: $2',
'viewsource'           => 'ווײַזן מקור',
'viewsource-title'     => 'באקוקן מקור פֿון $1',
'actionthrottled'      => 'די אַקציע איז באַגרענעצט',
'actionthrottledtext'  => 'אלס מאָסמיטל קעגן ספאַם, זענט איר באַגרענעצט פֿון דורכפֿירן די פעולה צופֿיל מאל אין א קורצער צײַט. ביטע פרובירט נאכאַמאָל אין א פאר מינוט.',
'protectedpagetext'    => 'דער בלאט איז פארשפארט צו אפהאלטן ענדערונגן.',
'viewsourcetext'       => 'איר קענט זען און קאפירן דעם מקור פון דעם בלאַט:',
'viewyourtext'         => "איר קענט באקוקן דעם מקור פון '''אייערע רעדאקטירונגען''' צו דעם בלאט:",
'protectedinterface'   => 'דער בלאַט שטעלט צו באניצער־אויבערפלאך טעקסט פֿאַרן װײכװאַרג אויף דער דאזיקער וויקי, און איז פֿאַרשפּאַרט כּדי צו פֿאַרמײַדן װאַנדאַליזם.
כדי צולייגן אדער ענדערן איבערזעצונגען פאר אלע וויקיס, זייט אזוי גוט ניצן [//translatewiki.net/ translatewiki.net], דער מעדיעוויקי לאקאליזאציע פראיעקט.',
'editinginterface'     => "'''ווארענונג:''' איר באַאַרבעט א בלאט וואס איז געניצט צוצושטעלן אינטערפֿייס טעקסט פאר דער ווייכווארג. ענדערונגען אין דעם בלאַט וועלן טוישן דאס אויסזען פון די סיסטעם מודעות פאר אלע אנדערע באניצער אויף דער וויקי.
כדי צולייגן אדער ענדערן איבערזעצונגען, באַטראַכטס באַניצן [//translatewiki.net/ translatewiki.net], דער מעדיעוויקי לאקאַליזאציע פראיעקט.",
'sqlhidden'            => '(SQL פארלאנג באהאלטן)',
'cascadeprotected'     => 'דער בלאט איז פארשפארט צום ענדערן וויבאלד ער איז איינגעשלאסן אין איינע פון די פאלגנדע {{PLURAL:$1|בלאט, וואס איז|בלעטער, וואס זענען}} באשיצט מיט דער קאסקייד אפציע:

$2',
'namespaceprotected'   => "איר זענט נישט ערלויבט צו רעדאקטירן בלעטער אינעם '''$1''' נאמענטייל.",
'customcssprotected'   => 'איר האט נישט רשות צו רעדאַקטירן דעם CSS בלאַט, ווײַל ער אַנטהאַלט די פערזענלעכע באַשטימונגען פון אַן אַנדער באַניצער.',
'customjsprotected'    => 'איר האט נישט רשות צו רעדאַקטירן דעם JavaScript בלאַט, ווײַל ער אַנטהאַלט די פערזענלעכע באַשטימונגען פון אַן אַנדער באַניצער.',
'ns-specialprotected'  => 'מען קען נישט רעדאגירן ספעציעלע בלעטער.',
'titleprotected'       => 'דער טיטל איז געשיצט פון ווערן געשאפֿן דורך  [[User:$1|$1]].
די אורזאך איז  \'\'$2".',

# Virus scanner
'virus-badscanner'     => "שלעכטע קאנפֿיגוראציע: אומבאוואוסטער ווירוס איבערקוקער: ''$1''",
'virus-scanfailed'     => 'איבערקוקן נישט געראטן (קאד: $1)',
'virus-unknownscanner' => 'אומבאוואוסטער אנטי־ווירוס:',

# Login and logout pages
'logouttext'                 => "'''איר האָט זיך ארויסלאָגירט.'''

איר קענט ממשיך זיין ניצן {{SITENAME}} אַנאנים, אדער איר קענט  [[Special:UserLogin|צוריק אריינלאגירן]] מיט דעם זעלבן אדער אן אנדער באַניצער נאָמען. באמערקט אז געוויסע בלעטער קענען זיך ווייטער ארויסשטעלן אזוי ווי ווען איר זענט אריינלאגירט, ביז איר וועט אויסליידיגן דעם בלעטערער זאפאס.",
'welcomecreation'            => '== ברוך הבא, $1! ==
אייער קאנטע איז באשאפן געווארן. נישט פארגעסן צו ענדערן אייערע [[Special:Preferences|{{SITENAME}} פרעפֿערענצן]].',
'yourname'                   => 'באַניצער נאָמען:',
'yourpassword'               => 'פאסווארט',
'yourpasswordagain'          => 'ווידער אריינקלאפן פאסווארט',
'remembermypassword'         => 'געדיינק מײַן אַרײַנלאגירן אויף דעם קאמפיוטער (ביז  $1 {{PLURAL:$1|טאָג|טעג}})',
'securelogin-stick-https'    => 'בלייַבן פארבונדן צו HTTPS נאָכן ארײַנלאָגירן',
'yourdomainname'             => 'אײַער געביט:',
'externaldberror'            => 'עס איז אדער פארגעקומען אן אויטענטיקאציע דאטנבאזע פעלער אדער איר זענט נישט ערמעגליכט צו דערהיינטיגן אייער דרויסנדיגע קאנטע.',
'login'                      => 'אַרײַנלאָגירן',
'nav-login-createaccount'    => 'ארײַנלאָגירן / זיך אײַנשרײַבן',
'loginprompt'                => 'איר מוסט ערלויבן קיכלעך ("cookies") אויף צו אַרײַנלאָגירן אינעם {{SITENAME}}.',
'userlogin'                  => 'ארײַנלאָגירן / זיך אײַנשרײַבן',
'userloginnocreate'          => 'אַרײַנלאגירן',
'logout'                     => 'אַרױסלאָגירן',
'userlogout'                 => 'אַרױסלאָגירן',
'notloggedin'                => 'נישט איינגעשריבן',
'nologin'                    => "איר האט נישט קיין קאנטע? '''$1'''.",
'nologinlink'                => 'שאַפֿן אַ קאנטע',
'createaccount'              => 'שאַפֿן אַ נײַע קאנטע',
'gotaccount'                 => "האסטו שוין א קאנטע? '''$1'''.",
'gotaccountlink'             => 'אַרײַנלאגירן',
'userlogin-resetlink'        => 'פארגעסן אײַערע אַרײַנלאָגירן פרטים?',
'createaccountmail'          => 'דורך ע-פאסט',
'createaccountreason'        => 'אורזאַך:',
'badretype'                  => 'די פאסווערטער וואס איר האט אריינגעלייגט זענען נישט אייניג.',
'userexists'                 => 'דער באַניצער נאָמען איז שוין געניצט. 
ביטע קלײַבט אױס אַן אַנדער נאָמען.',
'loginerror'                 => 'לאגירן פֿעלער',
'createaccounterror'         => 'האט נישט געקענט שאַפֿן קאנטע: $1',
'nocookiesnew'               => 'די באניצער קאנטע איז באשאפן, אבער איר זענט נישט אריינלאגירט.
{{SITENAME}} ניצט קיכלעך אריינצולאגירן באניצער.
איר האט קיכלעך נישט-ערמעגלעכט. 
ביטע ערמעגלעכט זיי, דאן טוט אריינלאגירן מיט אייערע נייע באניצער נאמען און פאסווארט.',
'nocookieslogin'             => '{{SITENAME}} נוצט קיכלעך כדי אַרײַנלאגירן באַניצער. 
בײַ אײַך זענען קיכלעך אומדערמעגלעכט. 
ביטע אַקטיווירט זיי און פרובירט נאכאַמאָל.',
'nocookiesfornew'            => 'מען האט נישט געשאַפֿן די באַניצער קאנטע, ווײַל מען האט נישט געקענט באַשטעטיקן איר מקור.
טוט פֿעסטשטעלן אָז קיכלעך זענען אַקטיווירט, לאָדט אָן נאכאַמאל דעם בלאַט און פרואווט ווידער.',
'noname'                     => 'איר האט נישט אַרײַנגעשטעלט קײַן גילטיקן באַניצער נאָמען.',
'loginsuccesstitle'          => 'אריינלאגירט מיט הצלחה',
'loginsuccess'               => "'''איר זענט אַצינד אַרײַנלאָגירט אין {{SITENAME}} ווי \"\$1\".'''",
'nosuchuser'                 => 'נישטא קיין באניצער מיטן נאמען  "$1".

קוקט איבער אײַער אויסלייג, אדער [[Special:UserLogin/signup|שאַפֿט א נײַע קאנטע]].',
'nosuchusershort'            => 'נישטאָ קיין באַניצער מיטן נאָמען "$1". 
ביטע באַשטעטיקט דעם אויסלייג.',
'nouserspecified'            => 'איר ברויכט ספעציפֿיצירן א באַניצער-נאָמען.',
'login-userblocked'          => 'דער באַניצער איז בלאקירט. ארײַנלאגירן נישט ערלויבט.',
'wrongpassword'              => 'אומריכטיגע פאסווארט אריינגעלייגט.
ביטע פרובירט נאכאמאל.',
'wrongpasswordempty'         => 'פאסווארט אריינגעלייגט איז געווען ליידיג.
ביטע פרובירט נאכאמאל.',
'passwordtooshort'           => 'פאַסווערטער מוזן זײַן כאטש {{PLURAL:$1|איין כאַראַקטער|$1 כאַראַקטערס}}.',
'password-name-match'        => 'אײַער פאַסווארט מוז זײַן אנדערש פון אײַער באַניצער נאָמען.',
'password-login-forbidden'   => 'באַניצן דעם נאָמען און שפּריכוואָרט איז פאַרבאָטן.',
'mailmypassword'             => 'שיקט מיין נייע פאסווארט',
'passwordremindertitle'      => 'ניי צייטווייליג פאסווארט פאר {{SITENAME}}',
'passwordremindertext'       => 'עמעצער (מסתמא איר, פֿון IP אדרעס $1)
האט געבעטן א ניי פאַסווארט פֿאר {{SITENAME}} ($4).
א פראוויזאריש פאַסווארט פֿאר באניצער  "$2" איז איצט "$3".
איר זאלט אריינלאגירן און אויסקלויבן א נײַ פאַסווארט.
דאס פראוויזארישע פאַסווארט וועט אויסגיין נאָך {{PLURAL:$5|איין טאג|$5 טעג}}

אויב איינער אנדערשט האט געמאכט די ביטע, אדער איר האט יא געדענקט אייער פאסווארט און איר טוט מער נישט באגערן דאס צו טוישן, קענט איר איגנארירן די מעלדונג און ווייטער ניצן אייער אלטע פאַסווארט.',
'noemail'                    => 'ס\'איז נישט רעקארדירט קיין ע-פאסט אַדרעס פֿאַר באַניצער  "$1".',
'noemailcreate'              => 'איר דאַרפֿט פֿאַרזארגן א גילטיגן ע-פאסט אַדרעס',
'passwordsent'               => 'א ניי פאסווארט איז געשיקט געווארן צום ע-פאסט אדרעס רעגיסטרירט פאר "$1".
ביטע ווידער אריינלאגירן נאך דעם וואס איר באקומט עס.',
'blocked-mailpassword'       => 'אייער איי פי אדרעס איז בלאקירט צו רעדאקטירן, דערוועגן זענט איר נישט ערלויבט צו באניצן מיטן פאסווארט ווידעראויפלעבונג פֿונקציע כדי צו פארמיידן סיסטעם קרומבאניץ.',
'eauthentsent'               => 'א באשטעטיגונג ע-בריוו איז געשיקט געווארן צו דעם באשטימטן ע-פאסט אדרעס. איידער סיי וואס אנדערע ע-פאסט וועט ווערן געשיקט צו דער קאנטע, וועט איר דארפן פאלגן די אנווייזונגען אין דער מעלדונג כדי צו זיין זיכער אז די קאנטע איז טאקע אייערס.',
'throttled-mailpassword'     => "א פאסווארט דערמאנונג איז שוין געשיקט געווארן, אין {{PLURAL:$1|דער לעצטער שעה|די לעצטע $1 שעה'ן}}. כדי צו פארמײַדן שלעכט באניצן, נאר איין פאסווארט דערמאנונג וועט געשיקט ווערן אין {{PLURAL:$1|א שעה |$1 שעה'ן}}.",
'mailerror'                  => 'פֿעלער שיקנדיג פאסט: $1',
'acct_creation_throttle_hit' => 'באַזוכער צו דער וויקי וואס באַניצן אייער IP אַדרעס האָבן שױן באַשאַפֿן {{PLURAL:$1|1 קאנטע|$1 קאנטעס}} במשך דעם לעצטן טאָג, דעם מאַקסימום וואָס מען ערלויבט אין דעם פעריאד.

דערפֿאַר קענען באַזוכער וואס באַניצן דעם  IP אַדרעס נישט מער שאַפֿן נײַע קאָנטעס דערווײַל.',
'emailauthenticated'         => 'אייער ע-פאסט אדרעס איז באשטעטיגט געווארן אום $2, $3.',
'emailnotauthenticated'      => 'אײַער ע-פאסט אדרעס איז נאכנישט באשטעטיגט. קיין ע-פאסט וועט נישט ווערן געשיקט פון קיין איינע פון די פאלגנדע אייגנקייטן.',
'noemailprefs'               => 'ספעציפֿירט אן ע־פאסט אַדרעס אין אײַער פרעפֿערענצן כדי די פֿעאיקייטן זאלן אַרבעטן.',
'emailconfirmlink'           => 'באַשטעטיקט אײַער ע-פאסט אַדרעס',
'invalidemailaddress'        => 'דער ע-פאסט אדרעס קען נישט אקצעפטירט ווערן ווייל ער שיינט צו האבן אן אומגילטיגן פֿארמאט.
ביטע אריינלייגן א גוט-פארמאטירטן אדרעס אדער ליידיגט אויס דאס פֿעלד.',
'cannotchangeemail'          => "מ'קען נישט ענדערן קאנטע ע־פאסט אדרעסן אין דער וויקי.",
'accountcreated'             => 'די קאָנטע איז באַשאַפֿן',
'accountcreatedtext'         => 'די באניצער קאנטע פאר $1 איז באַשאַפֿן געווארן.',
'createaccount-title'        => 'קאנטע באשאפֿן אין {{SITENAME}}',
'createaccount-text'         => 'עמעצער האט באשאפֿן א קאנטע פֿאר אייער ע-פאסט אדרעס אין {{SITENAME}} ($4) מיטן נאמען "$2" און  פאסווארט "$3". איר דארפט אצינד איינלאגירן און ענדערן דאס פאסווארט.

איר קענט איגנארירן די מעלדונג, ווען די קאנטע איז באשאפֿן בטעות.',
'usernamehasherror'          => 'באַניצער נאמען טאָר נישט אַנטהאַלטן קיין לייטער סימבאל',
'login-throttled'            => 'איר האט געפרוווט צופֿיל מאל אריינלאגירן.
זייט אזוי גוט און וואַרט איידער איר פרוווט נאכאמאל.',
'login-abort-generic'        => 'אײַער ארײַנלאגירונג איז נישט געווען דערפֿאלגרייך—אָפגעשטעלט',
'loginlanguagelabel'         => 'שפראך: $1',
'suspicious-userlogout'      => ' אײַער בקשה אַרויסלאָגירן זיך איז אפגעלייגט געווארן ווייַל אייגנטלעך איז זי געשיקט דורך אַ צעבראכענעם בלעטערער אָדער א פראקסי מיט א זאפאס.',

# E-mail sending
'php-mail-error-unknown' => 'אומבאַקאַנט טעות אין()mail פֿונקציע פֿון PHP.',
'user-mail-no-addy'      => 'געפרוווט צו שיקן ע-פּאָסט אָן אַן ע-פּאָסט אַדרעס.',

# Change password dialog
'resetpass'                 => 'ענדערן קאנטע פאסווארט',
'resetpass_announce'        => 'איר האט אריינלאגירט מיט א פראוויזארישן קאד געשיקט דורכן ע-פאסט. צו פארענדיגן אריינלאגירן, ברויכט איר אנשטעלן א ניי פאסווארט דא:',
'resetpass_text'            => '<!-- לייגט צו טעקסט דא -->',
'resetpass_header'          => 'ענדערן קאנטע פאסווארט',
'oldpassword'               => 'אַלטע פאַסווארט:',
'newpassword'               => 'ניי פּאסוואָרט:',
'retypenew'                 => 'ווידער שרײַבן פאַסווארט:',
'resetpass_submit'          => 'שטעלן פאסווארט און אריינלאגירן',
'resetpass_success'         => 'אייער פאַסווארט איז געטוישט געווארן מיט דערפֿאלג! איצט טוט מען אייך אריינלאגירן…',
'resetpass_forbidden'       => 'פאסווערטער קענען נישט ווערן געטוישט',
'resetpass-no-info'         => 'איר דארפֿט זיין אריינלאגירט צוצוקומען גלייך צו דעם דאזיגן בלאט.',
'resetpass-submit-loggedin' => 'טוישן פאסווארט',
'resetpass-submit-cancel'   => 'אַנולירן',
'resetpass-wrong-oldpass'   => 'אומגילטיג צײַטווײַליק אדער לויפֿיק פאַסווארט.
איר האט מעגלעך שוין געטוישט אייער פאַסווארט מיט הצלחה אדער געבעטן א נײַ  צײַטווײַליק פאַסווארט.',
'resetpass-temp-password'   => 'צײַטווייליק פאַסווארט:',

# Special:PasswordReset
'passwordreset'                    => 'צוריקשטעלן פאַסווארט',
'passwordreset-text'               => 'דערגאַנט די פאָרעם צו באַקומען אַן ע-פּאָסט דערמאָנונג פון די פרטים פֿון אײַער קאנטע.',
'passwordreset-legend'             => 'צוריקשטעלן פאַסווארט',
'passwordreset-disabled'           => 'מען האט אומאַקטיוויטר צוריקשטעלן פאַסווערטער אויף דער וויקי.',
'passwordreset-pretext'            => '{{PLURAL:$1| | קלאַפט אַרײַן איינע פֿון די דאַטן אונטן}}',
'passwordreset-username'           => 'באַניצער נאָמען:',
'passwordreset-domain'             => 'דאמען:',
'passwordreset-capture'            => 'זען  דעם געשיקטן ע־בריוו?',
'passwordreset-capture-help'       => 'אַז איר צייכנט דאס קעסטל, וועט מען ווײַזן דעם ע־בריוו (מיטן פראוויזארישן פאַסווארט) צו אײַך ווי אויך ווערן געשיקט צום באַניצער.',
'passwordreset-email'              => 'בליצפּאָסט אַדרעס:',
'passwordreset-emailtitle'         => 'קאנטע פרטים אין {{SITENAME}}',
'passwordreset-emailtext-ip'       => 'עמעצער (מסתמא איר, פון IP אדרעס $1) האט געבעטן א דערמאנונג פון אייערע
קאנטע פרטים פאר {{SITENAME}} ($4). די פאלגנדע באניצער {{PLURAL:$3|קאנטע איז|קאנטעס זענען}}
פארבונדן מיט דעם ע־פאסט אדרעס:

$2

{{PLURAL:$3|דאס פראוויזארישע פאסווארט|די פראוויזארישע פאסווערטער}} וועלן אויסגיין נאך {{PLURAL:$5|איין טאג|$5 טעג}}.
איר זאלט אריינלאגירן און קלויבן א נייע פאסווארט אצינד. טאמער א צווייטער האט געשיקט די בקשה, 
אדער ווען איר געדענקט יא אייער פריעריקע פאסווארט, און וויל עס נישט ענדערן,
 קענט איר איגנארירן דעם אנזאג און ניצן ווייטער דאס אלטע פאסווארט.',
'passwordreset-emailtext-user'     => 'באניצער $1 אויף  {{SITENAME}} האט געבעטן א דערמאנונג פון אייערע קאנטע פרטים פאר {{SITENAME}} ($4). 
די פאלגנדע באניצער {{PLURAL:$3|קאנטע איז|קאנטעס זענען}} פארבונדן מיט דעם ע־פאסט אדרעס:

$2

{{PLURAL:$3|דאס פראוויזארישע פאסווארט|די פראוויזארישע פאסווערטער}} וועלן אויסגיין נאך {{PLURAL:$5|איין טאג|$5 טעג}}.
איר זאלט אריינלאגירן און קלויבן א נייע פאסווארט אצינד. טאמער א צווייטער האט געשיקט די בקשה, 
אדער ווען איר געדענקט יא אייער פריעריקע פאסווארט, און וויל עס נישט ענדערן,
 קענט איר איגנארירן דעם אנזאג און ניצן ווייטער דאס אלטע פאסווארט.',
'passwordreset-emailelement'       => 'באַניצער נאָמען: $1 
פראוויזארישער פּאַראָל: $2',
'passwordreset-emailsent'          => "מ'האט געשיקט א דערמאָנונג ע-פּאָסט.",
'passwordreset-emailsent-capture'  => 'מען האט געשיקט א דערמאנונג בליצבריוו, וואס ווערט געוויזן אונטן.',
'passwordreset-emailerror-capture' => 'מען האט געשאפן א דערמאנונג בליצבריוו, וואס ווערט געוויזן אונטן, אבער שיקן צום באניצער איז דורכגעפאלן: $1',

# Special:ChangeEmail
'changeemail'          => 'ענדערן ע-פּאָסט אַדרעס',
'changeemail-header'   => 'ענדערן קאנטע ע-פּאָסט אַדרעס',
'changeemail-text'     => 'דערגאַנצט די פֿאָרעם צו ענדערן אייער ע-פּאָסט אַדרעס. איר וועט דאַרפֿן אַרײַנגעבן אײַער פּאַראָל צו באַשטעטיקן די ענדערונג.',
'changeemail-no-info'  => 'איר דאַרפֿט זיין אַרײַנלאגירט צוצוקומען גלײַך צו דעם דאָזיגן בלאַט.',
'changeemail-oldemail' => 'קראַנטער ע-פּאָסט אַדרעס:',
'changeemail-newemail' => 'נײַער בליצפּאָסט אַדרעס:',
'changeemail-none'     => '(קיין)',
'changeemail-submit'   => 'ענדערן ע־פאסט אדרעס',
'changeemail-cancel'   => 'אַנולירן',

# Edit page toolbar
'bold_sample'     => 'דיקער טעקסט',
'bold_tip'        => 'דיקער טעקסט',
'italic_sample'   => "דאס וועט מאכן ''שיף'' די אויסגעוועלט ווארט.",
'italic_tip'      => "דאס וועט מאכן ''שיף'' די אויסגעוועלט פאנט.",
'link_sample'     => 'שרײַבט דאָ אַרײַן די װערטער װאָס װעט זײַן אַ לינק צו {{SITENAME}} אַרטיקל אין דעם נושא',
'link_tip'        => "מאך דאס א '''לינק''' צו א וויקיפעדיע ארטיקל",
'extlink_sample'  => 'http://www.example.com לינק טיטל',
'extlink_tip'     => 'דערויסענדיגע לינק (געדענק http:// פרעפיקס)',
'headline_sample' => 'קעפּל',
'headline_tip'    => 'קעפּל -2טער שטאפל',
'nowiki_sample'   => 'נישט פֿארמאַטירטער טעקסט',
'nowiki_tip'      => 'נישט פֿאָרמאַטירטער טעקסט',
'image_sample'    => 'PictureFileName.jpg|קליין|250px|לייגט דא א קעפל פֿארן בילד',
'image_tip'       => 'טעקע געוויזן אין בלאט',
'media_sample'    => 'ביישפיל.ogg',
'media_tip'       => 'פארבינדונג צו א מעדיע טעקע',
'sig_tip'         => 'אייער אינטערשריפט, מיט א צייט סטעמפּל ווען איר האט אונטערגעשריבן.',
'hr_tip'          => 'א שטרייך אין די ברייט, (נישט נוצן אפט)',

# Edit pages
'summary'                          => 'קורץ וואָרט:',
'subject'                          => 'טעמע/קעפל:',
'minoredit'                        => 'דאס איז א מינערדיגע ענדערונג',
'watchthis'                        => 'טוט אױפֿפּאַסן דעם בלאט',
'savearticle'                      => 'אױפֿהיטן בלאַט',
'preview'                          => 'פאראויסדיגע ווייזונג',
'showpreview'                      => 'פֿאָרױסדיגע װײַזונג',
'showlivepreview'                  => 'לעבעדיגע פאראויסדיגע ווייזונג',
'showdiff'                         => 'ווײַז די ענדערונגען',
'anoneditwarning'                  => "'''ווארענונג:''' איר זענט נישט אריינלאגירט אין אייער קאנטע. אייער איי פי אדרעס וועט ווערן דאקומענטירט אין דעם בלאטס היסטאריע פון ענדערונגען. אויב זארגט איר זיך פאר פריוואטקייטן, ביטע טוט זיך אריינלאגירן.",
'anonpreviewwarning'               => "''איר זענט נישט אַרײַנלאגירט. אויפֿהיטן וועט ארײַנשרײַבן אײַער IP אַדרעס אין דער רעדאַקטירונג היסטאריע פונעם בלאַט.''",
'missingsummary'                   => "'''דערמאנונג:''' איר האט נישט אויסגעפילט דעם קורץ ווארט אויפקלערונג אויף אייער עדיט. אויב וועט איר דרוקן נאכאמאל אויף  \"היט אפ דעם בלאט\", וועט אייער ענדערונג ווערן געהיטן אן דעם.",
'missingcommenttext'               => 'ביטע שטעלט אריין א אנמערקונג פון אונטן.',
'missingcommentheader'             => "'''דערמאַנונג:''' איר האט נישט אַרײַנגעשטעלט א טעמע/קעפל פאר דער אנמערקונג. אויב וועט איר דרוקן נאכאמאל אויפן \"{{int:savearticle}}\", וועט אייער ענדערונג ווערן אפגעהיטן אן דעם.",
'summary-preview'                  => 'סך-הכל פאראויסדיגע ווייזונג:',
'subject-preview'                  => 'טעמע/קעפל פאראויסדיגע ווייזונג:',
'blockedtitle'                     => 'באנוצער איז בלאקירט',
'blockedtext'                      => '\'\'\'אייער באניצער נאמען אדער IP אדרעס איז געווארן בלאקירט.\'\'\'

דעם בלאק האט $1 געמאכט פון וועגן \'\'$2\'\'.

* בלאקירן הייבט אן: $8
* בלאקירן גייט אויס: $6
* בלאק מכוון צו: $7

איר קענט זיך ווענדן צו $1 אדער צו אנדערע [[{{MediaWiki:Grouppage-sysop}}|אדמיניסטראטארן]] אדורכצורעדן דעם בלאק.

גיט אכט אז איר קענט נישט ניצן די "שיקט דעם באניצער א ע-פאסט" אייגנקייט אויב האט איר נישט איינגעשטעלט אין אייערע [[Special:Preferences|קונטע פרעפערענצן]] א גילטיקן בליצפאסט אדרעסדאס אדער איר זענט בלאקירט פון שיקן בליצפאסט.

אייער IP אדרעס איז $3, און דער בלאק האט נומער #$5. ביטע שיקט איינעם פון די צוויי (אדער זיי ביידע) ווען איר ווענדט זיך צו די אדמיניסטראטורן.',
'autoblockedtext'                  => 'אײַער [[IP אדרעס|אײַ־פּי־אַדרעס]] איז בלאָקירט געװאָרן אױטאָמאַטיש, צוליב דעם װאָס אַן אַנדער באַניצער װאָס איז בלאָקירט געװאָרן דורך $1 האָט זיך געניצט דעם דאָזיקן אײַ־פּי.
די אורזאַך פֿון דער בלאָקירונג איז:

:\'\'\'$2\'\'\'

* אנהייב פון דער בלאקירונג: $8
* ענדע פון דער בלאָקירונג: $6
* וועמען בלאקירט: ִ$7

איר קענט זיך פֿאַרבינדן דורכן בליצבריװ מיט $1 אָדער מיט יעדן אַנדערן [[{{MediaWiki:Grouppage-sysop}}|סיסאָפּ]] צו דיסקוטירן װעגן דער בלאָקירונג.

אױב האָט איר ניט אַרײַנגעקלאַפּט אײַער בליצפּאָסט־אַדרעס אין אײַערע [[Special:Preferences|פּרעפֿערענצן]] אדער איר זענט בלאקירט פון שיקן בליצפאסט, קענט איר זיך \'\'נישט\'\' ניצן די אפציע "שיקט דעם באניצער אן ע-פאסט".

אייער יעצטיגער IP אדרעס איז $3, און דער בלאָקירונג־נומער איז #$5.
ביטע צײכנט עס אָן בשעת איר װענדט זיך צו די סיסאָפּן.',
'blockednoreason'                  => 'קיין טעם נישט געגעבן',
'whitelistedittext'                => 'איר ברויכט צו $1 צו ענדערן בלעטער.',
'confirmedittext'                  => 'אויף אייך ליגט קודם די פֿליכט צו באשטעטיגן אייער ע־פאסט אדרעס איידער איר רעדאַקטירט בלעטער.
ביטע שטעלט און באשטעטיגט אייער ע־פאסט אדרעס דורך אייערע [[Special:Preferences|באַניצער פרעפֿערענצן]] .',
'nosuchsectiontitle'               => 'נישט געפֿינען אָפטיילונג',
'nosuchsectiontext'                => "איר האט פרובירט ענדערן אן אפטיילונג וואס עקזעסטירט נישט.
קען זײַן מ'האט זי באַוועגט אדער אויסגעמעקט ווען איר האט באקוקט דעם בלאַט.",
'loginreqtitle'                    => 'אריינלאגירן פארלאנגט זיך',
'loginreqlink'                     => 'לאגירט אריין',
'loginreqpagetext'                 => 'איר מוזט $1 כדי צו זען אנדערע בלעטער.',
'accmailtitle'                     => 'פאסווארט געשיקט',
'accmailtext'                      => 'א צופֿעליק פאַסווארט פֿאַר [[User talk:$1|$1]] איז געשיקט געוואָרן צו $2.

דאָס פאַסווארט פאר דער נײַער קאנטע קען מען טוישן אויפֿן [[Special:ChangePassword|טוישן פאַסווארט]] בלאַט נאָכן ארײַנלאגירן.',
'newarticle'                       => '(ניי)',
'newarticletext'                   => "איר זענט געקומען צו אַ בלאַט וואָס עקזיסטירט נאָך נישט!
כדי שאַפֿן דעם בלאַט, קלאַפט אַרײַן טעקסט אין דעם קעסטל אונטן (זעט דעם [[{{MediaWiki:Helppage}}|הילף בלאַט]] פֿאַר מער אינפֿארמאַציע).
אויב איר זענט אַהערגעקומען בטעות, דרוקט דאָס '''Back''' קנעפל אין אײַער בלעטערער.",
'anontalkpagetext'                 => "----'''דאָס איז א רעדן בלאַט פון א אַן אַנאנימען באַניצער וואָס האט נאך נישט געשאַפֿן קיין קאנטע, אדער באניצט זיך נישט דערמיט. דערוועגן, מוזן מיר זיך באניצן מיט זיין IP אדרעס כדי אים צו אידענטיפיצירן. עס קען זיין אז עטלעכע אנדערע ניצן אויך דעם  IP אדרעס. אויב זענט איר אן אנאנימער באַניצער וואס שפירט אז איר האט באקומען מעלדונגען וואס זענען נישט שייך צו אייך, ביטע [[Special:UserLogin/signup|שאַפֿט א קאנטע]] אדער [[Special:UserLogin|טוט זיך אריינלאגירן]] כדי צו פארמיידן דאס אין די עתיד זיך פארמישן מיט אנדערע אַנאנימע באַניצערס.'''",
'noarticletext'                    => 'דערווייל איז נישט פאַרהאן קיין שום טעקסט אין דעם בלאט.
איר קענט [[Special:Search/{{PAGENAME}}|זוכן דעם בלאט טיטל]] אין אנדערע בלעטער,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} זוכן די רעלעוואנטע לאגביכער],
אדער [{{fullurl:{{FULLPAGENAME}}|action=edit}} רעדאַקטירן דעם בלאט]</span>.',
'noarticletext-nopermission'       => 'דערווײַל איז נישט פאַראַן קיין שום טעקסט אין דעם בלאַט.
איר קענט [[Special:Search/{{PAGENAME}}| זוכן דעם בלאט טיטל]] אין אנדערע בלעטער,
אדער <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} נאָכזוכן די רעלעוואנטע לאגביכער]</span>.',
'userpage-userdoesnotexist'        => 'באניצער קאנטע "$1" איז נישט אײַנגעשריבן. 
קוקט איבער צי איר ווילט שאפֿן/רעדאקטירן דעם בלאט.',
'userpage-userdoesnotexist-view'   => 'באניצער קאנטע "$1" איז נישט איינגעשריבן.',
'blocked-notice-logextract'        => 'דער באַניצער איז דערווייַל פֿאַרשפאַרט.
די לעצטע בלאָקירן לאג אַקציע איז צוגעשטעלט אונטן:',
'clearyourcache'                   => "'''אַכטונג:''' נאכן אויפֿהיטן, ברויכט איר אפשר נאך אַריבערגיין דעם בלעטערערס זאַפאַס צו זען די ענדערונגען.

* '''פֿייערפוקס/סאפֿארי:''' האלט אראפ ''שיפֿט'' בשעתן דרוקן ''Reload'', אדער דרוקט ''Ctrl-F5'' אדער ''Ctrl-R'' (אויף א מאקינטאש ''⌘-R'')

* '''גוגל כראם:''' דרוקט ''Ctrl-Shift-R'' (אויף א מאקינטאש ''⌘-Shift-R'')

* '''אינטערנעט עקספלארער:''' האלט אראפ ''Ctrl'' בשעתן קליקן ''Refresh'', אדער  דרוקט ''Ctrl-F5''

* '''אפערע:''' ליידיגט אויס דעם זאַפאַס אין ''Tools → Preferences'' (''העדפות'' > ''כלים'')",
'usercssyoucanpreview'             => "'''טיפ:''' נוצט דאס {{int:showpreview}} קנעפל אויספרובירן אייער CSS בעפארן אויפהיטן.",
'userjsyoucanpreview'              => "'''טיפ:''' נוצט דאס {{int:showpreview}} קנעפל אויספרובירן אייער  JavaScript בעפארן אויפהיטן.",
'usercsspreview'                   => "'''געדענקט אז איר טוט בלויז פאראויס זען אייער באניצער CSS.'''
'''ער איז דערווייל נאכנישט אויפֿגעהיטן!'''",
'userjspreview'                    => "'''געדענקט אַז איר טוט בלויז אויספרואוון\\פֿאראויסזען אייער באַניצער JavaScript.'''
'''עס איז דערווײַל נאכנישט אָפגעהיטן!'''",
'sitecsspreview'                   => "'''געדענקט אַז איר טוט בלויז פֿאראויסזען דעם דאָזיקן CSS קאד.'''
'''ער איז דערווײַל נאכנישט אויפֿגעהיטן!'''",
'sitejspreview'                    => "'''געדענקט אַז איר טוט בלויז פֿאראויסזען דעם דאָזיקן JavaScript קאד.'''
'''ער איז דערווײַל נאכנישט אויפֿגעהיטן!'''",
'userinvalidcssjstitle'            => "'''ווארענונג:''' סאיז נישטא קיין סקין \"\$1\". גדענקט אז קאסטעם .css און .js בלעטער נוצען לאוער קעיס טיטול, e.g. {{ns:user}}:Foo/vector.css ווי אנדערשט צו {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(דערהיינטיגט)',
'note'                             => "'''באמערקונג:'''",
'previewnote'                      => "'''געדענקט אז דאס איז נאָר אין אַ פֿאָרויסיקע ווייזונג.'''
אייערע ענדערונגען זענען נאָך נישט געהיט!",
'previewconflict'                  => 'די פֿאראויסיגע ווייזונג רעפלעקטירט דעם טעקסט און דער אויבערשטע טעקסט ענדערונג אָפטיילונג וויאזוי דאס וועט אויסזען אויב וועט איר דאס אָפהיטן.',
'session_fail_preview'             => "'''אנטשולדיגט! מען האט נישט געקענט פראצעסירן אייער ענדערונג צוליב א פארלוסט פון סעסיע דאטע. ביטע פרובירט נאכאמאל. אויב ס'ארבעט נאך אלס ניט, פרובירט [[Special:UserLogout|ארויסלאגירן]] און זיך צוריק אריינלאגירן.",
'session_fail_preview_html'        => "''''''אַנטשולדיקט! מיר קענען נישט פּראָצעסירן אײַער ענדערונג צוליב א פֿאַרלוסט פֿון סעסיע דאַטע.''''''

''װײַל די װיקי האט רױע HTML ערמעגליכט, דער פֿאָרױסיקער װײַזונג איז באַאַלטן אַלס אַ באַװאָרענונג אַנטקעגן JavaScript אַטאַקירונגען.''

'''אױב דאַס איז אַ כשרע רעדאַקציע פרוּװ, פּרובירט נאָכאַמאָל. אױב דאָס גײט נאָכאַלץ ניט, פּרובירט [[Special:UserLogout|ארױסלאָגירן]] און װידער אַרײַנלאָגירן. '''",
'token_suffix_mismatch'            => "'''אייער רעדאקטירונג איז געווארן אפגעווארפן ווייל אייער בראוזער האט אפגעווארפן די נקודות ביים רעדאקטירן.'''
די ענדערונג איז געווארן אפגעווארפן כדי נישט צו אנמאכן א חורבן אין די טעקסט פונעם בלאט.
דאס געשענט מייסטענס ווען איר נוצט אן אנאניאמער פראקסי סערווער.",
'edit_form_incomplete'             => "'''טייל פון דער רעדאקטירונג פֿארעם זענען נישט אנגעקומען צום סארווער; קאנטראלירט אז אייערע רעדאקטירונגען זענען פולשטענדיק און פרובירט נאכאמאל.'''",
'editing'                          => 'ענדערן $1',
'editingsection'                   => 'ענדערט $1 (אפטיילונג)',
'editingcomment'                   => 'רעדאַקטירן $1 (נײַע אפטיילונג)',
'editconflict'                     => 'ענדערן קאנפליקט: $1',
'explainconflict'                  => "איינער אנדערשט האט געטוישט דעם בלאט זינט איר האט אנגעהויבן דאס צו ענדערן.
דער אויבערשטער טעקסט אפטייל אנטהאלט דעם בלאט טעקסט ווי עס טוט איצט עקזעסטירן.
אייערע ענדערונגן זענען געוויזן אין דער אונטערשטער טעקסט אפטיילונג.
איר וועט דארפן צאמשטעלן אייערע ענדערונגען אינעם עקזעסטירנדן טעקסט.
'''בלויז''' דער טעקסט אינעם אויבערשטען טעקסט אפטיילונג וועט ווערן אפגעהיטן ווען איר וועט קוועטשן \"טוט אויפֿהיטן\".",
'yourtext'                         => 'אייער טעקסט',
'storedversion'                    => 'אוועגעלייגטע ווערסיע',
'nonunicodebrowser'                => "'''ווארענונג: אייער בלעטערער איז נישט יוניקאד געהארכיק.
אן ארום-ארבעט איז אין פלאץ אייך צו ערלויבן צו ענדערן בלעטער מיט זיכערקייט: non-ASCII אותיות וועלן ערשיינען אין די ענדערען קעסטל ווי hexadecimal קאדס.'''",
'editingold'                       => "'''פאָרזיכטיג! באארבעטסט יעצט נישט קיין אקטועלע ווערסיע, אויב דו וועסט היטן דעם באארבעטונג, וועט די לעצטע ענדרענונגען גיין קאַפוט.'''",
'yourdiff'                         => 'אונטערשיידן',
'copyrightwarning'                 => "<small>ביטע מערקט אויף אז אייערע אלע ביישטייערונגען אינעם '''{{SITENAME}}''' ערשיינען אונטער דעם $2 דערלויבן (זעט $1 פֿאַר מער פרטים). אויב איר וויִלט נישט לאזן אַנדערע ענדערן אײַערע בײַשטײַערונגען און פֿאַרשפרייטן אייער אַרבעט - ביטע שרײַבט זיי נישט דאָ.<br />
איר זאָגט צו אז איר האט געשריבן אן אייגענעם אינהאַלט, אדער האט איר באקומען ערלויבעניש צו שרײַבן אים דאָ.</small>",
'copyrightwarning2'                => "'''אכטונג:''' אנדערע באניצערס קענען מעקן און ענדערן אייערע ביישטייערונגען צו {{SITENAME}}.
אויב ווילט איר נישט  אז אייער ארבעט זאל זיין הפקר פאר אנדערע דאס צו באארבעטן – פארשפרייט זי נישט דא.

אזוי אויך, זאגט איר צו אז איר האט דאס געשריבן אליין, אדער דאס איבערקאפירט פון א מקור מיט פולן רשות דאס מפקיר זיין (זעט $1 פאר מער פרטים).
'''זיכט נישט באניצן מיט שטאף וואס איז באשיצט מיט קאפירעכטן!'''",
'longpageerror'                    => "'''פעלער: דער טעקסט וואס איר האט ארײַנגעשטעלט איז לאנג {{PLURAL:$1|איין קילאבייט|$1 קילאבייטן}}, וואס איז לענגער פון דעם מאקסימום פון {{PLURAL:$2|איין קילאבייט|$2 קילאבייטן}}. 
ער קען נישט ווערן אפגעהיטן.'''",
'readonlywarning'                  => "'''ווארענונג: די דאטעבאזע איז געווארן פארשלאסן פאר סייט אויפהאלטונג,
ממילא וועט איר נישט קענען אפהיטן אייערע ענדערונגען אצינד. איר קענט קאפירן און ארײַנלייגן דעם טעקסט אריין צו א טעקסט טעקע און דאס דארטן אפהיטן פאר שפעטער.'''

דער אדמיניסטראטאר וואס האט זי פארשלאסן האט מסביר געווען אזוי: $1",
'protectedpagewarning'             => "'''ווארענונג:  דער בלאט איז געווארן פארשפארט אז בלויז באניצערס מיט סיסאפ פריווילעגיעס קענען אים ענדערן.'''
די פארגאנגענע לאגבוך באשרײַבונג ווערט געוויזן דא:",
'semiprotectedpagewarning'         => "'''באמערקונג:''' דער דאזיגער בלאַט איז פֿאַרשפאַרט אז בלויז איינגעשריבענע באניצערס קענען אים ענדערן.
די פֿאַרגאַנגענע לאגבוך באשרײַבונג ווערט געוויזן דאָ:",
'cascadeprotectedwarning'          => "'''ווארענונג:''' דער בלאט איז פארשפארט אז בלויז סיסאפן קענען אים ענדערן, וויבאלד ער איז איינגעשלאסן אין {{PLURAL:$1| דעם פאלגנדן בלאט, וואס איז|די פאלגנדע בלעטער, וואס זענען}} קאסקאד באשיצט:",
'titleprotectedwarning'            => "'''אזהרה: דער בלאט איז פֿארשפאַרט טא דארף מען [[Special:ListGroupRights|ספעציפֿישע רעכטן]] צו שאפֿן אים.'''
די פֿאַרגאַנגענע לאגבוך באשרײַבונג ווערט געוויזן דאָ:",
'templatesused'                    => '{{PLURAL:$1|מוסטער|מוסטערן}} באנוצט אויף דעם בלאט:',
'templatesusedpreview'             => '{{PLURAL:$1|מוסטער| מוסטערן}}  באַניצט  אין דעם פֿאָראױסדיקן אױסקוק:',
'templatesusedsection'             => '{{PLURAL:$1|מוסטער|מוסטערן}} באנוצט אין דעם אפטיילונג:',
'template-protected'               => '(באשיצט)',
'template-semiprotected'           => '(טיילווייז באשיצט)',
'hiddencategories'                 => 'דער דאזיגער בלאט געהערט צו {{PLURAL:$1|איין באהאלטענער קאטעגאריע|$1 באהאלטענע קאטעגאריעס}}:',
'edittools'                        => '<!-- טעקסט דא וועט געוויזן ווערן אונטער ענדערן און ארויפלאדירן פארעמס. -->',
'nocreatetitle'                    => 'בלאט באשאפן באגרעניצט',
'nocreatetext'                     => 'די סייט האט באגרעניצט די מעגליכקייט צו שאפן נייע בלעטער.
איר קענט צוריקגיין און ענדערן דעם עקזיסטירנדן בלאט, אדער [[Special:UserLogin|לאגירט זיך אריין אדער שאפט א קאנטע]].',
'nocreate-loggedin'                => 'איר זענט נישט ערלויבט צו שאַפֿן נײַע בלעטער.',
'sectioneditnotsupported-title'    => 'רעדאקטירן אפטיילונגען נישט געשטיצט.',
'sectioneditnotsupported-text'     => 'רעדאַקטירן אָפטיילונגען נישט געשטיצט אויף דעם בלאַט',
'permissionserrors'                => 'ערלויבענישן פעלערס',
'permissionserrorstext'            => 'איר זענט נישט ערלויבט צו טון דאס, פֿאַר {{PLURAL:$1|דער פֿאלגנדער סיבה|די פֿאלגנדע סיבות}}:',
'permissionserrorstext-withaction' => 'איר זענט נישט ערלויבט צו $2, וועגן {{PLURAL:$1|דער פֿאלגנדער סיבה| די פֿאלגנדע סיבות}}:',
'recreate-moveddeleted-warn'       => "'''ווארענונג: איר שאפט א נייעם בלאט וואס איז שוין איינמאל  געווארן אויסגעמעקט.'''

איר זאלט איבערטראכטן צי עס פאַסט רעדאַקטירן דעם בלאַט ווײַטער.
די אויסמעקן און באַוועגן לאגביכער ווערן געוויזן דא:",
'moveddeleted-notice'              => 'דער בלאט איז געווארן אויסגעמעקט.
די אויסמעקן און באַוועגן לאגביכער פונעם בלאט ווערן געוויזן דא אונטן.',
'log-fulllog'                      => 'באַקוקן פֿולן לאגבוך',
'edit-hook-aborted'                => 'רעדאַקטירונג אַנולירט דורך Hook.
נישטא קיין הסבר.',
'edit-gone-missing'                => "נישט מעגלעך צו דערהיינטיגן דעם בלאט.
ס'ווייזט אויס אז ער איז אויסגעמעקט.",
'edit-conflict'                    => 'רעדאקטירן קאנפֿליקט.',
'edit-no-change'                   => "מ'האט איגנארירט אײַער רעדאַקטירונג, ווײַל קיין שום ענדערונג איז נישט געמאַכט צום טעקסט.",
'edit-already-exists'              => 'נישט מעגליך צו שאַפֿן נייע בלאט.
ער עקזיסטירט שוין.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''אזהרה:''' דער בלאט אנטהאלט צופיל טייערע פארזירער רופן.

ער דארף האבן ווינציגער פון  $2 {{PLURAL:$2|רוף|רופן}}, אבער אצינד {{PLURAL:$1|איז דא $1 רוף|זענען דא $1 רופן}}.",
'expensive-parserfunction-category'       => 'בלעטער מיט צופֿיל טייערע פאַרזער פֿונקציאן רופֿן',
'post-expand-template-inclusion-warning'  => "'''אכטונג:''' איינגעשלאסענע מוסטערן אין דעם בלאט זענען צו גרויס.
טייל מוסטערן וועלן נישט ווערן איינגעשלאסן.",
'post-expand-template-inclusion-category' => 'בלעטער וואו דאס מוסטער איינשליסן איז צו גרויס',
'post-expand-template-argument-warning'   => "'''ווארענונג''': די בלאט אנטהאלט צו ווייניגסטענס איין טאמפלעיט פארעמיטער וואס איז צו גרויס. די דאזיגע פארעמיטערס זענען אויסגעלאזט געווארן.",
'post-expand-template-argument-category'  => 'בלעטער וואס זענען פון דעם ארויסגעלאזט געווארן טאמפלעיט פאראמעטערס',
'parser-template-loop-warning'            => 'מוסטער שלייף געטראפן: [[$1]]',
'parser-template-recursion-depth-warning' => 'מוסטער רעקורסיע טיף מאקסימום איבערגעשטיגן ($1)',
'language-converter-depth-warning'        => 'אַריבער דעם שפּראַך קאַנווערטער טיף לימיט ($1)',

# "Undo" feature
'undo-success' => 'די ענדערונג קען ווערן מבוטל. ביטע נאכקוקן די פארגלייך פון אונטן צו זיין זיכער אז דאס איז וואס איר ווילט טאן, און דערנאך היט-אפ די ענדערונגן פון אונטן צו ענדיגן דאס בטל מאכן די ענדערונג.',
'undo-failure' => 'די ענדערונג קען נישט מבוטל ווערן צוליב סתירות מיט צווישנצייטלעכע ענדערונגען.',
'undo-norev'   => "ס'איז נישט מעגלעך צוריקקערן די רעדאַקטירונג ווײַל זי עקסיסטירט נישט אדער איז אויסגעמעקט.",
'undo-summary' => 'זיי מבטל רי-ווערסיע $1 פון [[Special:Contributions/$2|$2]] ([[User talk:$2|רעדן]])',

# Account creation failure
'cantcreateaccounttitle' => 'מען קען נישט באשאפֿן קאנטע',
'cantcreateaccount-text' => 'שאפֿן קאנטעס פון דעם IP אדרעס (<b>$1</b>) איז געווארן בלאקירט דורך [[User:$3|$3]]. די סיבה געגעבן פֿון $3 איז "$2".',

# History pages
'viewpagelogs'           => 'װײַזן לאָג-ביכער פֿאַר דעם בלאַט',
'nohistory'              => 'נישטא קיין ענדערן היסטאריע פאר דעם בלאט.',
'currentrev'             => 'איצטיגע ווערסיע',
'currentrev-asof'        => 'לויפיקע רעוויזיע פון $1',
'revisionasof'           => 'רעוויזיע ביי $1',
'revision-info'          => 'רעוויזיע ביי $1 פון $2',
'previousrevision'       => '→ עלטערע  ווערסיע',
'nextrevision'           => 'נײַערע ווערסיע ←',
'currentrevisionlink'    => 'איצטיגע ווערסיע',
'cur'                    => 'איצט',
'next'                   => 'קומענדיגע',
'last'                   => 'לעצטע',
'page_first'             => 'ערשט',
'page_last'              => 'לעצט',
'histlegend'             => "פֿארגלייכן  אויסקלויב: צייכנט די קנעפלעך פֿון די ווערסיעס צו פֿארגלײַכן, און קלאפט  Enter אדער דאס קנעפל '''{{int:compareselectedversions}}'''.<br />
שליסל: '''({{int:cur}})''' = אונטערשייד פֿון לויפֿיגער ווערסיע, '''({{int:last}})''' = אונטערשייד פֿון פֿריערדיגער ווערסיע, '''({{int:last}})''' = מינערדיקע רעדאקטירונג",
'history-fieldset-title' => 'בלעטערט די היסטאריע',
'history-show-deleted'   => 'נאר אויסגעמעקט',
'histfirst'              => 'ערשטע',
'histlast'               => 'לעצטיגע',
'historysize'            => '({{PLURAL:$1|1 בייט|$1 בייטן}})',
'historyempty'           => '(ליידיג)',

# Revision feed
'history-feed-title'          => 'ווערסיע היסטאריע',
'history-feed-description'    => 'ווערסיע היסטאריע פאר דעם בלאט אויפן וויקי',
'history-feed-item-nocomment' => '$1 אין $2',
'history-feed-empty'          => 'דער געבעטענער בלאט עקזיסטירט נישט.
עס איז מעגליך אויסגעמעקט געווארן פון דער וויקי, אדער דער נאמען געטוישט.
פרובירט [[Special:Search|צו זיכן אין וויקי]] נאך רעלאווענטע נייע בלעטער.',

# Revision deletion
'rev-deleted-comment'         => '(קורץ־ווארט אראָפגענומען)',
'rev-deleted-user'            => '(באנוצער נאמען אראפגענומען)',
'rev-deleted-event'           => '(לאגירן אקציע אראפגענומען)',
'rev-deleted-user-contribs'   => '[באַניצער נאָמען אָדער IP אַדרעס אראפגענומען - רעדאַקטירונג פֿאַרבאָרגן פֿון בייַשטייַערונגען]',
'rev-deleted-text-permission' => "די בלאט רעוויזיע איז געווארן '''אויסגעמעקט '''.
עס איז מעגלעך דא נאך פרטים אין דעם
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} אויסמעקונג לאג].",
'rev-deleted-text-unhide'     => "די בלאט רעוויזיע איז געווארן '''אויסגעמעקט '''.
עס איז מעגלעך דא נאך פרטים אין דעם
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} אויסמעקונג לאג].
איר קענט נאך  [$1 באקוקן די רעוויזיע] אויב איר ווילט גיין ווײַטער.",
'rev-suppressed-text-unhide'  => "די בלאט רעוויזיע איז געווארן '''באהאלטן'''.
עס איז מעגלעך דא נאך פרטים אין דעם [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} באהעלטעניש לאג].
איר קענט נאך [$1 באקוקן די רעוויזיע] אויב איר ווילט גיין ווײַטער.",
'rev-deleted-text-view'       => "די בלאט רעוויזיע איז געווארן '''אויסגעמעקט'''.
איר קענט זען זי;
עס איז מעגלעך דא נאך פרטים אין דעם [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} אויסמעקונג לאג].",
'rev-suppressed-text-view'    => "די בלאט רעוויזיע איז געווארן '''באהאלטן '''.
איר קענט זען זי;
עס איז מעגלעך דא נאך פרטים אין דעם [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} באהעלטעניש לאג].",
'rev-deleted-no-diff'         => "איר קענט נישט באקוקן דעם אונטערשייד ווײַל איינע פון די ווערסיעס איז געווארן '''אויסגעמעקט'''.
פרטים קען מען געפֿונען אינעם [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} אויסמעקן לאגבוך].",
'rev-suppressed-no-diff'      => "איר קענט נישט זען דעם אונטערשייד ווייַל איינע פון די רעוויזיעס איז געווארן '''אויסגעמעקט'''.",
'rev-deleted-unhide-diff'     => "איינע פון די ווערסיעס איז געווארן  '''אויסגעמעקט'''.
פרטים קען מען געפונען אינעם [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} אויסמעקן לאגבוך].
איר קענט דאך [$1 זען דעם אונטערשייד].",
'rev-suppressed-unhide-diff'  => "איינע פון די ווערסיעס איז געווארן  '''אונטערדריקט'''.
פרטים קען מען געפונען אינעם [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} אונטערדריקן לאגבוך].
איר קענט דאך [$1 זען דעם אונטערשייד].",
'rev-deleted-diff-view'       => "איינע פון די ווערסיעס פון דעם אונטערשייד איז געווארן '''אויסגעמעקט '''.
איר קענט זען זי; פרטים קען מען געפונען אין דעם [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} אויסמעקונג לאגבוך].",
'rev-suppressed-diff-view'    => "איינע פון די ווערסיעס פון דעם אונטערשייד איז געווארן '''אונטערדריקט '''.
איר קענט זען זי; פרטים קען מען געפונען אין דעם [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} אונטערדריקונג לאגבוך].",
'rev-delundel'                => 'ווײַזן/באַהאַלטן',
'rev-showdeleted'             => 'ווײַזן',
'revisiondelete'              => 'אויסמעקן\\צוריקשטעלן רעוויזיעס',
'revdelete-nooldid-title'     => 'ציל ווערסיע נישט גילטיג',
'revdelete-nooldid-text'      => 'איר האט נישט ספעציפירט קיין ציל ווערסיע דורצוכפירן די פונקציע.',
'revdelete-nologtype-title'   => 'קיין לאג טיפ נישט געקליבן',
'revdelete-nologtype-text'    => 'איר האט נישט ספעציפֿירט קיין לאג טיפ דורצוכפֿירן די פֿונקציע.',
'revdelete-nologid-title'     => 'אומגילטיגער לאג־פֿאַרשרײַב',
'revdelete-nologid-text'      => 'אדער האט איר נישט ספעציפֿיצירט א ציל לאגטיפ אדער איז נישט פֿאַרהאַן דער ספעציפֿיצירטער לאגטיפ.',
'revdelete-no-file'           => 'די ספעציפֿירטע טעקע עקזיסטירט נישט.',
'revdelete-show-file-confirm' => 'צי זענט איר זעכער איר ווילט באַקוקן אַן אויסגעמעקטע רעוויזיע פון דער טעקע "<nowiki>$1</nowiki>" פון $2 בשעה $3?',
'revdelete-show-file-submit'  => 'יא',
'revdelete-selected'          => "'''{{PLURAL:$2|אויסדערוויילטע ווערסיע| אויסדערוויילטע ווערסיעס}} פון [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1| אויסדערוויילטע לאג אקציע|אויסדערוויילטע לאג אקציעס}}:'''",
'revdelete-text'              => "'''אויסגעמעקטע רעוויזיעס און געשעענישן וועלן בלייבן אין דער בלאט היסטאריע און די לאגביכער, אבער טיילן פון זייער אינהאלט וועט ווערן אומגרייכלעך צום קהל. '''
אנדערע סיסאפן אויף {{SITENAME}} וועלן נאך האבן צוטריט צום באהאלטענעם אינהאלט און קענען אים צוריקשטעלן דורך דעם זעלבן אייבערפלאך,  אחוץ ווען מען שטעלט נאך באגרענעצונגען.",
'revdelete-confirm'           => 'זייט אזוי גוט און באשטעטיקט אז דאס איז טאקע אייער כוונה, אז איר פארשטייט די קאנסעקווענצן, און אז איר טוט דאס לויט  [[{{MediaWiki:Policy-url}}|דער פאליסי]].',
'revdelete-suppress-text'     => "באהאלטן זאל בלויז גענוצט ווערן '''נאר''' אין די פאלגענדע פעלער:
* אויפדעקונג פון פריוואטקייט אינפארמאציע
* ''היים אדרעסן, טעלעפאן נומערן, אדער סאשעל סעקיורעטי, א.א.וו.:'''",
'revdelete-legend'            => 'שטעלט ווייזונג באגרענעצונגען',
'revdelete-hide-text'         => 'באהאלט אינהאלט פון ווערסיע',
'revdelete-hide-image'        => 'באהאלט טעקע אינהאלט',
'revdelete-hide-name'         => 'באהאלט אקציע און ציל',
'revdelete-hide-comment'      => 'באהאלט ענדערן הערה',
'revdelete-hide-user'         => "באַהאַלטן רעדאַקטאר'ס באניצער-נאמען/IP-אַדרעס",
'revdelete-hide-restricted'   => 'באהאלט אינפארמאציע אויך פון אדמיניסטראטורן פונקט ווי פשוטע באנוצער',
'revdelete-radio-same'        => '(נישט ענדערן)',
'revdelete-radio-set'         => 'יא',
'revdelete-radio-unset'       => 'ניין',
'revdelete-suppress'          => 'באַהאַלטן אינפֿארמאַציע פון אַדמיניסטראַטארן ווי אויך אנדערע',
'revdelete-unsuppress'        => 'טוה אפ באגרענעצונגן אין גענדערטע רעוויזיעס',
'revdelete-log'               => 'אורזאַך:',
'revdelete-submit'            => 'צושטעלן צו {{PLURAL:$1|סעלעקטירטער רעוויזיע| סעלעקטירטע רעוויזיעס}}',
'revdelete-success'           => "'''רעוויזיע זעבאַרקייט דערפֿאלגרייך דערהײַנטיקט.'''",
'revdelete-failure'           => "'''נישט מעגלעך צו דערהײַנטיקן רעוויזיע זעבאַרקייט:'''
$1",
'logdelete-success'           => "'''לאג באהאלטן איז סוקסעספול איינגעשטעלט.'''",
'logdelete-failure'           => "'''נישט מעגלעך צו שטעלן לאג זעבאַרקייט:'''
$1",
'revdel-restore'              => 'טויש די זעבארקייט',
'revdel-restore-deleted'      => 'אויסגעמעקטע ווערסיעס',
'revdel-restore-visible'      => 'זעבאַרע ווערסיעס',
'pagehist'                    => 'בלאט היסטאריע',
'deletedhist'                 => 'אויסגעמעקטע ווערסיעס',
'revdelete-hide-current'      => 'פֿעלער בײַם באַהאַלטן דעם איינהייט פֿון $2, $1: דאָס איז די לויפֿיגע ווערסיע.
מען קען זי נישט פֿאַרבאָרגן.',
'revdelete-show-no-access'    => 'פֿעלער בײַם ווייַזן דעם איינהייט פֿון $2 , $1 : דער איינהייט איז אָנגעצייכנט געווארן "באַשרענקט".
איר האט נישט קיין צוטריט צו אים.',
'revdelete-modify-no-access'  => 'פֿעלער בײַם מאדיפֿיצירן דעם איינהייט פֿון $2 , $1 : דער איינהייט איז אָנגעצייכנט געווארן "באַשרענקט".
איר האט נישט קיין צוטריט צו אים.',
'revdelete-modify-missing'    => 'פֿעלער בײַ מאדיפֿיצירן  דעם איינס ID $1: ער פֿעלט פֿון דער דאַטנבאַזע!',
'revdelete-no-change'         => "'''ווארענונג:''' דער איינהייט פֿון $2 , $1 האט שוין די געבעטענע זעבאַרקייט איינשטעלונגען.",
'revdelete-concurrent-change' => 'גרײַז בײַם מאדיפֿיצירן דעם איינהייט פֿון דאַטע $2 , $1 : ווײַזט אויס אַז זייַן סטאַטוס איז געווארן געענדערט דורך א צווייטן בשעת איר האט געפרוווט צו מאָדיפיצירן אים
ביטע זײַט בודק די לאָגביכער.',
'revdelete-only-restricted'   => 'פֿעלער בײַם באַהאַלטן דאס איינסל פֿון  $2, $1: איר קענט נישט באהאלטן פרטים פון אַדמיניסטראטורן נאר אויב איר וויילט אויס איינע פון די אַנדערע באַהאַלטן ברירות.',
'revdelete-reason-dropdown'   => '*אלגעמיינע אויסמעקן סיבות
** קאפירעכט ברעכן
** נישט פאַסנדיקע הערה אדער פערזענלעכע אינפֿארמאַציע
** נישט פאַסנדיקער באַניצער נאמען
** אינפֿארמאַציע מעגלעך צו זיין לשון הרע',
'revdelete-otherreason'       => 'אנדער/צוגעגעבענע סיבה:',
'revdelete-reasonotherlist'   => 'אנדער סיבה',
'revdelete-edit-reasonlist'   => 'רעדאַקטירן אויסמעקן סיבות',
'revdelete-offender'          => 'רעוויזיע מחבר:',

# Suppression log
'suppressionlog' => 'באהאלטונגען לאג',

# History merging
'mergehistory'                     => 'צונויפֿגיסן בלאט היסטאריעס',
'mergehistory-header'              => 'דער בלאַט דערלויבט אײַך צונויפֿגיסן רעוויזיעס פֿון דער היסטאריע פֿון א מקור בלאַט אין א נײַערן בלאַט.
שטעלט פֿעסט אַז די ענדערונג וועט האַלטן דעם סדר פֿון דער היסטאריע.',
'mergehistory-box'                 => 'צונויפֿגיסן רעוויזיעס פֿון צוויי בלעטער:',
'mergehistory-from'                => 'מקור בלאַט:',
'mergehistory-into'                => 'פֿארציל בלאַט:',
'mergehistory-list'                => 'צוזאשמעלצונג ענדערונג היסטאריע',
'mergehistory-go'                  => 'צייג צוזאמשמעלצונג ענדערונגן',
'mergehistory-submit'              => 'צונויפֿגיסן רעוויזיעס',
'mergehistory-empty'               => 'קיין רעוויזיעס קען נישט ווערן צונויפֿגעגאסן.',
'mergehistory-success'             => '{{PLURAL:$3|איין גירסא|$3 גירסאות}} פֿון [[:$1]] צונויפֿגעגאסן אין [[:$2]] מיט דערפֿאלג.',
'mergehistory-fail'                => 'נישט מעגלעך אדורכצופֿירן היסטאריע צונויפֿגאס, ביטע זײַט בודק די בלאַט און צײַט פאַראַמעטערס.',
'mergehistory-no-source'           => 'מקור בלאַט $1 עקזיסטירט נישט.',
'mergehistory-no-destination'      => 'פֿארציל בלאַט $1 עקזיסטירט נישט.',
'mergehistory-invalid-source'      => 'מקור בלאַט מוז זײַן א גילטיק קעפל.',
'mergehistory-invalid-destination' => 'פֿארציל בלאַט מוז זײַן א גילטיק קעפל.',
'mergehistory-autocomment'         => 'צונויפֿגעגאסן [[:$1]] אין [[:$2]]',
'mergehistory-comment'             => 'צונויפֿגעגאסן [[:$1]] אין [[:$2]]: $3',
'mergehistory-same-destination'    => 'מקור און ציל בלעטער זענען די זעלבע',
'mergehistory-reason'              => 'אורזאַך:',

# Merge log
'mergelog'           => 'צונויפֿגיסן לאג-בוך',
'pagemerge-logentry' => 'צונויפֿגעגאסן [[$1]] אין [[$2]] (גרסאות ביז $3)',
'revertmerge'        => 'מבטל זײַן צאמשטעל',
'mergelogpagetext'   => "אונטן איז א ליסטע פון לעצטנסדיגע צונויפגיסונגען פון איין בלאט'ס היסטאריע אין א צווייטער.",

# Diffs
'history-title'            => 'רעוויזיע היסטאריע פֿון $1',
'difference'               => '(אונטערשייד צווישן ווערסיעס)',
'difference-multipage'     => '(אונטערשייד צווישן בלעטער)',
'lineno'                   => 'שורה $1:',
'compareselectedversions'  => 'פארגלייך סעלעקטירטע ווערסיעס',
'showhideselectedversions' => 'ווײַזן/באַהאַלטן געקליבענע רעוויזיעס',
'editundo'                 => 'אַנולירן',
'diff-multi'               => '({{PLURAL:$1|איין מיטלסטע ווערסיע |$1 מיטלסטע ווערסיעס}} פֿון {{PLURAL:$2|איין באַניצער|$2 באַניצער}} נישט געוויזן.)',
'diff-multi-manyusers'     => '({{PLURAL:$1|איין מיטלסטע ווערסיע |$1 מיטלסטע ווערסיעס}} פֿון מער ווי {{PLURAL:$2|איין באַניצער|$2 באַניצער}} נישט געוויזן.)',

# Search results
'searchresults'                    => 'זוכן רעזולטאטן',
'searchresults-title'              => 'זוכן רעזולטאַטן פֿאַר "$1"',
'searchresulttext'                 => 'לערנען מער ווי צו זוכן אין {{SITENAME}}, זעט  [[{{MediaWiki:Helppage}}|{{int:help}}]]',
'searchsubtitle'                   => 'איר האט געזוכט \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|אלע בלעטער וואס הייבן אן "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|אלע בלעטער וואס פֿאַרבינדן צו "$1"]])',
'searchsubtitleinvalid'            => "'''$1''' איר האט געזוכט",
'toomanymatches'                   => 'צו פֿיל רעזולטאַטן, ביטע פרואווט אן אנדער זוך',
'titlematches'                     => 'בלאט קעפל שטימט',
'notitlematches'                   => 'קיין שום בלאט האט נישט א צוגעפאסט קעפל',
'textmatches'                      => 'בלעטער מיט פאַסנדיקן אינהאַלט',
'notextmatches'                    => 'נישטאָ קיין בלעטער מיט פאַסנדיקן אינהאַלט',
'prevn'                            => '{{PLURAL:$1|פֿריערדיקער|$1 פֿריערדיקע}}',
'nextn'                            => '{{PLURAL:$1|$1}} קומענדיגע',
'prevn-title'                      => '{{PLURAL:$1|פֿריערדיגער $1 רעזולטאַט|פֿריערדיגע $1 רעזולטאַטן}}',
'nextn-title'                      => '{{PLURAL:$1|קומענדיקער רעזולטאַט|קומענדיקע $1 רעזולטאַטן}}',
'shown-title'                      => 'ווײַזן $1  {{PLURAL:$1|רעזולטאַט| רעזולטאַטן}} אויף א בלאַט',
'viewprevnext'                     => 'קוקט אויף ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'זוכן ברירות',
'searchmenu-exists'                => "'''ס'איז פֿאַראַן א בלאַט מיטן נאמען \"[[:\$1]]\" אין דער וויקי'''",
'searchmenu-new'                   => "'''באַשאַפֿן דעם בלאַט \"[[:\$1]]\" אויף דער וויקי'''",
'searchhelp-url'                   => 'Help:אינהאַלט',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|בלעטערן בלעטער מיט דעם פרעפֿיקס]]',
'searchprofile-articles'           => 'אינהאלט בלעטער',
'searchprofile-project'            => 'הילף און פראיעקט בלעטער',
'searchprofile-images'             => 'מולטימעדיע',
'searchprofile-everything'         => 'אלץ',
'searchprofile-advanced'           => 'פֿארגעשריטן',
'searchprofile-articles-tooltip'   => 'זוכן אין $1',
'searchprofile-project-tooltip'    => 'זוכן אין $1',
'searchprofile-images-tooltip'     => 'זוכן טעקעס',
'searchprofile-everything-tooltip' => 'זוך אינעם גאנצען אינהאלט (אריינגערעכנט רעדן בלעטער)',
'searchprofile-advanced-tooltip'   => 'זוכן אין צוגעשטעלטע ָנאָמענטיילן',
'search-result-size'               => '$1 ({{PLURAL:$2|איין ווארט|$2 ווערטער}})',
'search-result-category-size'      => '{{PLURAL:$1|1 מיטגליד|$1 מיטגלידער}} ({{PLURAL:$2|1 אונטער־קאַטעגאריע|$2 אונטער־קאַטעגאריעס}}, {{PLURAL:$3|1 טעקע|$3 טעקעס}})',
'search-result-score'              => 'שייכותדיקייט: $1%',
'search-redirect'                  => '(ווײַטערפֿירן $1)',
'search-section'                   => '(אפטיילונג $1)',
'search-suggest'                   => 'צי האט איר געמיינט: $1',
'search-interwiki-caption'         => 'שוועסטער פראיעקטן',
'search-interwiki-default'         => '$1 רעזולטאטן:',
'search-interwiki-more'            => '(נאך)',
'search-mwsuggest-enabled'         => 'מיט פארשלאגן',
'search-mwsuggest-disabled'        => 'אן פארשלאגן',
'search-relatedarticle'            => 'פארבינדן',
'mwsuggest-disable'                => 'בטל מאכן פארשלאגן AJAX',
'searcheverything-enable'          => 'זוכן אין אלע נאמענטיילן',
'searchrelated'                    => 'פארבינדן',
'searchall'                        => 'אלץ',
'showingresults'                   => "ווייזן ביז {{PLURAL:$1|רעזולטאט '''איינס'''|'''$1''' רעזולטאטן}} אנגעפאנגן פון נומער #'''$2''':",
'showingresultsnum'                => "ווייזן {{PLURAL:$3|רעזולטאט '''איינס'''|'''$3''' רעזולטאטן}} אנגעפאנגן פון נומער #'''$2''':",
'showingresultsheader'             => "{{PLURAL:$5|רעזולטאַט '''$1''' פֿון '''$3'''| רעזולטאַטן '''$1 - $2''' פֿון '''$3'''}} פֿאַר '''$4'''",
'nonefound'                        => "'''  אכטונג''': בלויז אין טייל נאמענטיילן ווערט געזוכט גרונטלעך.
איר קענט שרייבן'''all:''' בעפאר דער זוך טערמין כדי צו זוכן אין אלע בלעטער (אריינגערעכנט שמועס בלעטער, מוסטערן, א.א.וו.), אדער שרייבן בעפארן זוך-טערמין דעם נאמענטייל וואס איר זענט אינטערסירט דערין.",
'search-nonefound'                 => 'נישטא קיין רעזולטאטן פֿאַר דער שאלה.',
'powersearch'                      => 'זוכן',
'powersearch-legend'               => 'ווײַטהאלטן זוכן',
'powersearch-ns'                   => 'זוכן אין נאמענטיילן:',
'powersearch-redir'                => 'ווײַז ווײַטערפֿירונג בלעטער',
'powersearch-field'                => 'זוך',
'powersearch-togglelabel'          => 'קאנטראלירן:',
'powersearch-toggleall'            => 'אלע',
'powersearch-togglenone'           => 'קיין',
'search-external'                  => 'דרויסנדיק זוכן',
'searchdisabled'                   => "{{SITENAME}} זוך איז אָפאַקטיווירט.
צווישנצײַט קענט איר זוכן מיט גוגל.
געב אכט אז ס'איז מעגלעך אַז זייער אינדעקס פֿון {{SITENAME}} אינהאַלט איז אפשר פֿאַרעלטערט.",

# Quickbar
'qbsettings'                => 'גיכפאַס',
'qbsettings-none'           => 'גארנישט',
'qbsettings-fixedleft'      => 'קבוע לינקס',
'qbsettings-fixedright'     => 'קבוע רעכטס',
'qbsettings-floatingleft'   => 'שווימנדיג לינקס',
'qbsettings-floatingright'  => 'שווימנדיג רעכטס',
'qbsettings-directionality' => 'פֿעסט, אפהענגיק אויף דער שריפֿט ריכטונג פֿון אײַער שפראַך.',

# Preferences page
'preferences'                   => 'פרעפֿערענצן',
'mypreferences'                 => 'פּרעפֿערענצן',
'prefs-edits'                   => 'צאָל ענדערונגען:',
'prefsnologin'                  => 'נישט אריינלאגירט',
'prefsnologintext'              => 'איר דארפט זיין  <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} אריינלאגירט]</span> כדי צו ענדערן באניצער פרעפֿערענצן.',
'changepassword'                => 'טוישן פאַסווארט',
'prefs-skin'                    => 'סקין',
'skin-preview'                  => 'פארויסדיגע ווייזונג',
'datedefault'                   => 'נישטא קיין פרעפערענץ',
'prefs-beta'                    => 'בעטאַ אייגנשאַפֿטן',
'prefs-datetime'                => 'דאטום און צייט',
'prefs-labs'                    => 'לאַבאראַטאריע מעגלעכקייטן',
'prefs-personal'                => 'באַנוצער פראָפֿיל',
'prefs-rc'                      => 'לעצטע ענדערונגען',
'prefs-watchlist'               => 'אויפפאסונג ליסטע',
'prefs-watchlist-days'          => 'טעג צו ווייזן אין דער אויפפאסונג ליסטע:',
'prefs-watchlist-days-max'      => 'העכסטן $1 {{PLURAL:$1|טאג|טעג}}',
'prefs-watchlist-edits'         => 'מאַקסימום נומער פון נײַע ענדערונגען צו ווייַזן אין פֿאַרברייטערטער אויפֿפאַסונג ליסטע:',
'prefs-watchlist-edits-max'     => 'מאַקסימום נומער: 1000',
'prefs-watchlist-token'         => 'אויפֿפאַסונג ליסטע סימן:',
'prefs-misc'                    => 'פֿאַרשידנס',
'prefs-resetpass'               => 'טוישן פאַסווארט',
'prefs-changeemail'             => 'ענדערן ע־פאסט אדרעס',
'prefs-setemail'                => 'שטעלן אַן ע-פּאָסט אַדרעס',
'prefs-email'                   => 'ע־פאסט אפציעס',
'prefs-rendering'               => 'אויסזען',
'saveprefs'                     => 'אויפֿהיטן',
'resetprefs'                    => 'אוועקנעמען נישט-אויפגעהיטענע ענדערונגען',
'restoreprefs'                  => 'צוריקשטעלן אלע גרונטלעכע שטעלונגען',
'prefs-editing'                 => 'באַאַרבעטן',
'prefs-edit-boxsize'            => 'גרויס פונעם רעדאקטירונג פענסטער.',
'rows'                          => 'שורות:',
'columns'                       => 'עמודים:',
'searchresultshead'             => 'זוכן',
'resultsperpage'                => 'צאל טרעפֿן אין א בלאַט:',
'stub-threshold'                => 'שוועל פֿאַר <a href="#" class="stub">שטומף לינק</a> פֿאָרמאַטירונג (בייטן):',
'stub-threshold-disabled'       => 'אַנולירט',
'recentchangesdays'             => 'צאל פון טעג צו ווייזן אין די לעצטע ענדערונגן:',
'recentchangesdays-max'         => 'מאַקסימום $1 {{PLURAL:$1|טאָג|טעג}}',
'recentchangescount'            => 'די צאָל רעדאַקטירונגען צו ווײַזן גרונטלעך:',
'prefs-help-recentchangescount' => 'כולל לעצטע ענדערונגען, בלאַט היסטאָריעס, און לאָגביכער.',
'savedprefs'                    => 'אייערע פרעפערענצן איז אפגעהיטן געווארן.',
'timezonelegend'                => 'צײַט זאנע:',
'localtime'                     => 'לאקאלע צייט:',
'timezoneuseserverdefault'      => 'ניצן סערווירער גרונט ($1)',
'timezoneuseoffset'             => 'אַנדער (ספעציפֿירט אונטערשייד)',
'timezoneoffset'                => 'אונטערשייד¹:',
'servertime'                    => 'סארווער צײַט:',
'guesstimezone'                 => 'אנפֿילן פֿון בלעטערער',
'timezoneregion-africa'         => 'אפריקע',
'timezoneregion-america'        => 'אמעריקע',
'timezoneregion-antarctica'     => 'אנטארקטיקע',
'timezoneregion-arctic'         => 'ארקטיק',
'timezoneregion-asia'           => 'אזיע',
'timezoneregion-atlantic'       => 'אטלאנטישער אקעאן',
'timezoneregion-australia'      => 'אויסטראליע',
'timezoneregion-europe'         => 'אייראפע',
'timezoneregion-indian'         => 'אינדישער אקעאן',
'timezoneregion-pacific'        => 'פאציפישער אקעאן',
'allowemail'                    => 'ערלויבן אנדערע צו שיקן אײַך ע־פאסט',
'prefs-searchoptions'           => 'זוכן',
'prefs-namespaces'              => 'נאָמענטיילן',
'defaultns'                     => 'אנדערשט זוך אין די נאמענטיילן:',
'default'                       => 'גרונטלעך',
'prefs-files'                   => 'טעקעס',
'prefs-custom-css'              => 'באַניצער דעפֿינירט CSS',
'prefs-custom-js'               => 'באַניצער דעפֿינירט JS',
'prefs-common-css-js'           => 'שותפֿותדיקער CSS/JS פֿאַר אַלע אויספֿארמירונגען:',
'prefs-reset-intro'             => 'איר קענט ניצן דעם בלאַט צוריקצושטעלן אײַערע פרעפֿערענצן גרונטלעך פֿאַרן ארט.
מען קען דאָס נישט אַנולירן.',
'prefs-emailconfirm-label'      => 'ע-פאסט באַשטעטיקונג:',
'prefs-textboxsize'             => 'גרייס פֿון רעדאַקטירונג פֿענסטער',
'youremail'                     => 'ע-פאסט:',
'username'                      => 'באַניצער־נאָמען:',
'uid'                           => 'באַנוצער־נומער:',
'prefs-memberingroups'          => 'מיטגליד אין {{PLURAL:$1|גרופע|גרופעס}}:',
'prefs-registration'            => 'אײַנשרײַבן צײַט:',
'yourrealname'                  => 'עכטער נאמען *:',
'yourlanguage'                  => 'שפּראַך:',
'yourvariant'                   => 'אינהאַלט שפּראַך וואַריאַנט:',
'prefs-help-variant'            => 'אײַער פרעפֿערירטער וואַריאַנט אדער ארטאגראַפֿיע צו צייגן די אינהאַלט בלעטער פֿון דער וויקי.',
'yournick'                      => 'חתימה:',
'prefs-help-signature'          => 'באַמערקונגען אויף רעדן בלעטער זאָלן זיין אונטערגעשריבן מיט "<nowiki> ~ ~ ~ ~ </nowiki>" וואָס וועט ווערן פֿאַרוואַנדלט אין אײַער חתימה מיט א צײַטשטעמפל.',
'badsig'                        => 'נישט גילטיקער רויער אונטערשריפט. ביטע קאנטראלירט די HTML טאַגן.',
'badsiglength'                  => 'אונטערשריפט צו לאנג; מוז זיין ווינציגער פון {{PLURAL:$1|איין אות|$1 אותיות}}.',
'yourgender'                    => 'מין:',
'gender-unknown'                => 'נישט ספעציפֿיצירט',
'gender-male'                   => 'זכר',
'gender-female'                 => 'נקבה',
'prefs-help-gender'             => 'אפציאנאַל: באניצט בכדי דאס ווייכוואַרג זאל אײַך אַדרעסירן מיטן געהעריגן מין פֿארעם. די אינפֿארמאַציע ווערט ידוע צו אַלעמען.',
'email'                         => 'ע-פאסט',
'prefs-help-realname'           => '* עכטער נאמען (אפציאנאל): אויב וועט איר אויסוועלן צוצישטעלן דאס, וועט גענוצט ווערן צו געבן אטריביאציע צו אייער ארבייט.',
'prefs-help-email'              => 'ע-פאסט אַדרעס איז ברירהדיק, אבער עס דערמעגליכט אז מען קען אייך שיקן א ניי פאסווארט טאמער איר פֿארגעסט דאָס אַלטע.',
'prefs-help-email-others'       => 'איר קענט אויך אויסקלייבן צו לאזן אנדערע פֿארבינדן מיט אייך דורך ע־פאסט דורך א לינק אויף אייער באניצער אדער שמועס בלאט. 
מען וועט נישט אנטפלעקן אייער ע־פאסט אדרעס ווען אנדערע פֿארבינדן זיך מיט אייך.',
'prefs-help-email-required'     => 'בליצפאסט אדרעס באדארפט.',
'prefs-info'                    => 'גרונטלעכע אינפֿארמאַציע',
'prefs-i18n'                    => 'אינטערנאַציאנאַליזאַציע',
'prefs-signature'               => 'אונטערשריפֿט',
'prefs-dateformat'              => 'דאַטע פֿארמאַט',
'prefs-timeoffset'              => 'צײַט אונטערשייד',
'prefs-advancedediting'         => 'פֿארגעשריטענע אפציעס',
'prefs-advancedrc'              => 'פֿארגעשריטענע אפציעס',
'prefs-advancedrendering'       => 'פֿארגעשריטענע אפציעס',
'prefs-advancedsearchoptions'   => 'פֿארגעשריטענע אפציעס',
'prefs-advancedwatchlist'       => 'פֿארגעשריטענע אפציעס',
'prefs-displayrc'               => 'ווײַזן אפציעס',
'prefs-displaysearchoptions'    => 'ווײַזן אפציעס',
'prefs-displaywatchlist'        => 'ווײַזן אפציעס',
'prefs-diffs'                   => 'צווישנשיידן',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'ע-פּאָסט אַדרעס זעט אויס גילטיק',
'email-address-validity-invalid' => 'לייגט אַרײַן א גילטיקן ע־פאסט אַדרעס',

# User rights
'userrights'                   => 'באנוצער רעכטן פארוואלטערשאפט',
'userrights-lookup-user'       => 'פֿאַרוואַלטן באניצער גרופעס',
'userrights-user-editname'     => 'לייגט אריין א באנוצער-נאמען:',
'editusergroup'                => 'רעדאַגירן באַניצער גרופּעס',
'editinguser'                  => "ענדערן באַניצער רעכטן פון באַניצער '''[[User:$1|$1]]'''   $2",
'userrights-editusergroup'     => 'רעדאַקטירן באַניצער גרופעס',
'saveusergroups'               => 'אָפהיטן באַניצער גרופעס',
'userrights-groupsmember'      => 'מיטגליד פון:',
'userrights-groupsmember-auto' => 'אויטאמטישער מיטגליד פֿון:',
'userrights-groups-help'       => 'איר מעגט ענדערן די גרופעס צו וועמען דער באַניצער געהערט:
*א מאַרקירט קעסטל באַדײַט אָז דער באַניצער איז א מיטגליד אין דער גרופע.
* אַן אוממאַרקירט קעסטל באַדײַט אָז דער באַניצער איז נישט קיין מיטגליד אין דער גרופע.
* א * ווײַזט אַז איר קענט נישט אַראפנעמען די גרופע נאָך דעם וואָט איר האט זי צוגעלייגט, אדער פֿאַרקערט.',
'userrights-reason'            => 'אורזאַך:',
'userrights-no-interwiki'      => 'איר האט נישט קיין ערלויבניש צו רעדאַקטירן באַניצער רעכטן אויף אַנדערע וויקיס.',
'userrights-nodatabase'        => 'דאַטנבאַזע $1 אדער עקזיסטירט נישט אדער איז נישט ארטיק.',
'userrights-nologin'           => 'איר דאַרפֿט [[Special:UserLogin| אַרײַנלאגירן]] מיט א סיסאפ קאנטע צו באַשטימען באַניצער רעכטן.',
'userrights-notallowed'        => 'אײַער קאנטע האט נישט קיין ערלויבניש צוצולייגן אדער אוועקנעמען באַניצער רעכטן.',
'userrights-changeable-col'    => 'גרופעס איר קענט ענדערן',
'userrights-unchangeable-col'  => 'גרופעס איר קענט נישט ענדערן',

# Groups
'group'               => 'גרופע:',
'group-user'          => 'באניצערס',
'group-autoconfirmed' => 'באַשטעטיקטע באַניצער',
'group-bot'           => 'באטס',
'group-sysop'         => 'סיסאפן',
'group-bureaucrat'    => 'ביוראקראטן',
'group-suppress'      => 'אויפֿזעער',
'group-all'           => '(אלע)',

'group-user-member'          => '{{GENDER:$1|באַניצער|באַניצערין}}',
'group-autoconfirmed-member' => '{{GENDER:$1|באַשטעטיקטער באַניצער|באַשטעטיקטע באַניצערין}}',
'group-bot-member'           => '{{GENDER:$1|באט}}',
'group-sysop-member'         => '{{GENDER:$1|סיסאפ}}',
'group-bureaucrat-member'    => '{{GENDER:$1|ביוראקראַט}}',
'group-suppress-member'      => '{{GENDER:$1|אויפֿזעער|אויפֿזעערין}}',

'grouppage-user'          => '{{ns:project}}:אײַנגעשריבענער באניצער',
'grouppage-autoconfirmed' => '{{ns:project}}:אויטאבאַשטעטיגטע באַניצער',
'grouppage-bot'           => '{{ns:project}}:באטס',
'grouppage-sysop'         => '{{ns:project}}:אדמיניסטראטורן',
'grouppage-bureaucrat'    => '{{ns:project}}:ביראקראט',
'grouppage-suppress'      => '{{ns:project}}:אויפֿזעער',

# Rights
'right-read'                  => 'ליינען בלעטער',
'right-edit'                  => 'רעדאקטירן בלעטער',
'right-createpage'            => 'שאַפֿן בלעטער (וואָס זענען נישט שמועס בלעטער)',
'right-createtalk'            => 'שאַפֿן שמועס בלעטער',
'right-createaccount'         => 'שאַפֿן נײַע באַניצער קאנטעס',
'right-minoredit'             => 'צייכן רעדאקטירונגען אלס  מינערדיק',
'right-move'                  => 'באוועג בלעטער',
'right-move-subpages'         => 'באַוועגן בלעטער מיט זייערע אונטערבלעטער',
'right-move-rootuserpages'    => 'באַוועגן באַניצער הויפטבלעטער',
'right-movefile'              => 'באַוועגן טעקעס',
'right-suppressredirect'      => 'נישט שאַפֿן א ווײַטערפֿירונג פֿונעם אַלטן בלאַט בײַם באַוועגן אַ בלאַט',
'right-upload'                => 'ארויפלאדן טעקעס',
'right-reupload'              => 'איבערשרײַבן עקסיסטירנדע טעקע',
'right-reupload-own'          => "איבערשרײַבן עקזיסטירנדע טעקעס וואָס מ'האט אַליין אַרויפֿגעלאָדן",
'right-reupload-shared'       => 'אריבערשרייבן טעקעס אויפן געמיינזאם מעדיע רעפאזיטאריום',
'right-upload_by_url'         => 'ארויפֿלאָדן טעקעס פֿון אַ URL',
'right-purge'                 => 'ליידיקן דעם זייטל־זאפאס פאר א בלאט אן באשטעטיקונג',
'right-autoconfirmed'         => 'רעדאקטירן האלב-געשיצטע בלעטער',
'right-bot'                   => 'באַהאַנדלונג ווי אַן אויטאמאַטישער פראצעס',
'right-nominornewtalk'        => 'מינערדיקע רעדאקטירונגען צו שמועס בלעטער זאלן נישט שאפן די "נייע מודעות" מעלדונג',
'right-writeapi'              => 'ניצן דעם שרײַבן API',
'right-delete'                => 'מעקן בלעטער',
'right-bigdelete'             => 'אויסמעקן בלעטער מיט לאַנגע היסטאריעס',
'right-deleterevision'        => 'מעקן און צוריקשטעלן ספעציפישע רעוויזיעס פון בלעטער',
'right-deletedhistory'        => 'אײַערע אויסגעמעקטע היסטאריע פֿאַרשרײַבונגען, אן זייער אסאציאירטן טעקסט',
'right-deletedtext'           => 'באַקוקן אויסגעמעקטן טעקסט און ענדערונגען צווישן אויסגעמעקטע ווערסיעס',
'right-browsearchive'         => 'זוכן אויסגעמעקטע בלעטער',
'right-undelete'              => 'צוריקשטעלן א בלאט',
'right-suppressrevision'      => 'קוק-איבער און דריי-צוריק רעוויזיעס באהאלטן פון אדימיניסטראטורן',
'right-suppressionlog'        => 'זען פריוואַטע לאגביכער',
'right-block'                 => 'בלאקירן אַנדערע באַניצער פֿון רעדאַקטירן',
'right-blockemail'            => 'בלאקירן א באַניצער פֿון שיקן ע־פאסט',
'right-hideuser'              => 'בלאקירן באַניצער־נאָמען און פֿאַרבארגן אים',
'right-unblockself'           => 'זיך אליין אויפֿשפאַרן',
'right-protect'               => 'ענדערן שוץ ניוואען און רעדאַגירן געשיצטע בלעטער',
'right-editprotected'         => 'רעדאַגירן געשיצטע בלעטער (אָן קאַסקאַדן שוץ)',
'right-editinterface'         => 'רעדאַקטירן די באַניצער אייבערפֿלאַך',
'right-editusercssjs'         => 'רעדאַקטירן אַנדערע באַניצערס CSS און JS טעקעס',
'right-editusercss'           => 'רעדאַקטירן אַנדערע באַניצערס CSS טעקעס',
'right-edituserjs'            => 'רעדאַקטירן אַנדערע באַניצערס JS טעקעס',
'right-rollback'              => 'גיך צוריקדרייען די רעדאַקטירונגען פונעם לעצטן באַניצער וואס האט רעדאַקטירט א געוויסן בלאַט',
'right-markbotedits'          => 'מאַרקירן צוריקגעזעצטע רעדאַגירונגען ווי באט רעדאַגירונגען',
'right-noratelimit'           => 'נישט ווערן באַגרענעצט דורך לימיטאַציע',
'right-import'                => 'אימפארטירן בלעטער פון אנדערע וויקיס',
'right-importupload'          => 'אימפארטירן בלעטער דורך ארויפֿלאָדן טעקע',
'right-patrol'                => 'צייכנען די רעדאַקטירונגען פֿון אַנדערע ווי פאַטראלירט',
'right-autopatrol'            => 'אייגענע באַאַרבעטונגען אויטאמאַטיש מאַרקירט ווי קאנטראלירט',
'right-patrolmarks'           => 'באַקוקן לעצטע ענדערונגען פּאַטראָל מאַרקירונגען',
'right-unwatchedpages'        => 'באַקוקן די ליסטע פֿון נישט אויפֿגעפאַסטע בלעטער',
'right-mergehistory'          => 'צונויפֿגיסן די היסטאריע פֿון בלעטער',
'right-userrights'            => 'רעדאַקטירן אלע באַניצער רעכטן',
'right-userrights-interwiki'  => 'רעדאַקטירן באַניצער רעכטן פֿון באַניצער אויף אנדערע וויקיס',
'right-siteadmin'             => 'פארשליס און שליס-אויף די דאטעבאזע',
'right-override-export-depth' => 'עקספארטירן בלעטער כולל געלינקטע בלעטער ביז א טיף פון 5',
'right-sendemail'             => 'שיקן ע-פאסט צו אנדערע באניצער',
'right-passwordreset'         => 'באַקוקן פאַסווארט צוריקשטעלן ע־בריוו',

# User rights log
'rightslog'                  => 'באַניצער רעכטן לאג',
'rightslogtext'              => 'דאָס איז אַ לאג פֿון ענדערונגען צו באַניצער רעכטן.',
'rightslogentry'             => 'געביטן די מיטגלידערשאַפֿט פֿאַר $1 פֿון $2 אויף $3',
'rightslogentry-autopromote' => 'אויטאמאטיש פראמאווירט פון $2 צו $3',
'rightsnone'                 => '(גארנישט)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'ליינען דעם בלאַט',
'action-edit'                 => 'רעדאקטירן דעם בלאַט',
'action-createpage'           => 'שאַפֿן בלעטער',
'action-createtalk'           => 'שאַפֿן שמועס בלעטער',
'action-createaccount'        => 'שאַפֿן די באַניצער קאנטע',
'action-minoredit'            => 'באַצייכענען די רעדאַקטירונג ווי מינערדיק',
'action-move'                 => 'באַוועגן דעם בלאַט',
'action-move-subpages'        => 'באַוועגן דעם בלאַט מיט זײַנע אונטערבלעטער',
'action-move-rootuserpages'   => 'באַוועגן באַניצער הויפטבלעטער',
'action-movefile'             => 'באַוועגן די טעקע',
'action-upload'               => 'אַרויפֿלאָדן די טעקע',
'action-reupload'             => 'איבערשרײַבן די עקזיסטירנדע טעקע',
'action-reupload-shared'      => 'אריבערשרייבן די טעקע אין א געמיינזאמער רעפאזיטאריע',
'action-upload_by_url'        => 'ארויפֿלאָדן די טעקע פֿון א URL',
'action-writeapi'             => 'ניצן דעם שרײַבן API',
'action-delete'               => 'אויסמעקן דעם בלאַט',
'action-deleterevision'       => 'אויסמעקן די רעוויזיע',
'action-deletedhistory'       => "באַקוקן דעם בלאט'ס אויסגעמעקטע היסטאריע",
'action-browsearchive'        => 'זוכן אויסגעמעקטע בלעטער',
'action-undelete'             => 'צוריקשטעלן דעם בלאט',
'action-suppressrevision'     => 'איבערגיין און צוריקשטעלן די פֿאַרבארגטע רעוויזיע',
'action-suppressionlog'       => 'באקוקן דעם פריוואטן לאג',
'action-block'                => 'בלאקירן דעם באַניצער פֿון רעדאַקטירן',
'action-protect'              => 'ענדערן שיצונג ניוואען פֿאַר דעם בלאַט',
'action-rollback'             => 'גיך צוריקדרייען די רעדאַקטירונגען פונעם לעצטן באַניצער וואס האט רעדאַקטירט א געוויסן בלאַט',
'action-import'               => 'אימפארטירן דעם בלאַט פֿון אַן אַנדער וויקי',
'action-importupload'         => 'אימפארטירן דעם בלאַט דורך ארויפֿלאָדן אַ טעקע',
'action-patrol'               => "אנצייכענען אנדערס' רעדאקטירונגן אלס נאכגעקוקט",
'action-autopatrol'           => 'אנצוצייכענען אייערע רעדאקטירונגן אלס איבערגעקוקטע',
'action-unwatchedpages'       => 'זען די ליסטע פון נישט אויפֿגעפאַסטע בלעטער',
'action-mergehistory'         => 'צונויפֿגיסן די היסטאריע פֿון דעם בלאַט',
'action-userrights'           => 'רעדאַקטירן אלע באַניצער רעכטן',
'action-userrights-interwiki' => 'רעדאַקטירן רעכטן פון באַניצער אויף אַנדערע וויקיס',
'action-siteadmin'            => 'שליסן אדער אויפשליסן די דאטנבאזע',
'action-sendemail'            => 'שיקן ע־פאסט',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|ענדערונג|$1 ענדערונגען}}',
'recentchanges'                     => 'לעצטע ענדערונגען',
'recentchanges-legend'              => 'ברירות פאר לעצטע ענדערונגען',
'recentchangestext'                 => 'גיי נאך די לעצטע ענדערונגען צו דער וויקי אויף דעם בלאט.',
'recentchanges-feed-description'    => 'גייט נאך די לעצטע ענדערונגען צו דער וויקי אין דעם בלאט.',
'recentchanges-label-newpage'       => 'די רעדאַקטירונג האט באשאפֿן א נײַעם בלאַט',
'recentchanges-label-minor'         => 'דאָס איז אַ מינערדיקע רעדאַקטירונג',
'recentchanges-label-bot'           => ' די רעדאַקטירונג האט אויסגעפירט א באט',
'recentchanges-label-unpatrolled'   => 'די רעדאקטירונג איז נאך נישט נאכגעקוקט',
'rcnote'                            => "אונטן {{PLURAL:$1|איז '''1''' ענדערונג|זײַנען די לעצטע '''$1''' ענדערונגען}} אין {{PLURAL:$2|דעם לעצטן טאג|די לעצטע $2 טעג}}, ביז $5, $4.",
'rcnotefrom'                        => "פֿאלגנד זענען די ענדערונגען זײַט '''$2''' (ביז '''$1''')",
'rclistfrom'                        => 'װײַזן נײַע ענדערונגען פֿון $1',
'rcshowhideminor'                   => '$1 מינערדיגע ענדערונגען',
'rcshowhidebots'                    => '$1 ראבאטן',
'rcshowhideliu'                     => '$1 אײַנגעשריבענע באַניצערס',
'rcshowhideanons'                   => '$1 אַנאָנימע באַנוצערס',
'rcshowhidepatr'                    => '$1 פאַטראלירטע ענדערונגען',
'rcshowhidemine'                    => '$1 מײַנע רעדאַקטירוננגען',
'rclinks'                           => 'װײַזן די לעצטע $1 ענדערונגען אין די לעצטע $2 טעג.<br />$3',
'diff'                              => 'אונטערשייד',
'hist'                              => 'היסטאריע',
'hide'                              => 'באַהאַלטן',
'show'                              => 'ווייזן',
'minoreditletter'                   => 'מ',
'newpageletter'                     => 'נ',
'boteditletter'                     => 'ב',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|איין באַניצער פאַסט|$1 באַניצערס פאַסן}} אויף]',
'rc_categories'                     => 'גרענעץ פֿאַר קאַטעגאריעס (אָפשיידן מיט "|")',
'rc_categories_any'                 => 'אלע',
'rc-change-size-new'                => '$1 {{PLURAL:$1|בייט|בייטן}} נאך דער ענדערונג',
'newsectionsummary'                 => '/* $1 */ נייע אפטיילונג',
'rc-enhanced-expand'                => 'צייג דעטאלען (פארלאנגט זיך JavaScript)',
'rc-enhanced-hide'                  => 'באהאלט דעטאלן',
'rc-old-title'                      => 'געשאפן לכתחילה מיטן נאמען "$1"',

# Recent changes linked
'recentchangeslinked'          => 'פֿאַרבונדענע ענדערונגען',
'recentchangeslinked-feed'     => 'פֿאַרבונדענע ענדערונגען',
'recentchangeslinked-toolbox'  => 'פֿאַרבונדענע ענדערונגען',
'recentchangeslinked-title'    => 'ענדערונגען פֿארבונדן מיט $1',
'recentchangeslinked-noresult' => 'נישט געווען קיין ענדערונגען אין פֿארבונדענע בלעטער אין דער תקופה.',
'recentchangeslinked-summary'  => "אט א רשימה פון נייע ענדערונגען צו בלעטער פארבונדן פון א ספעציפישן בלאט (אדער מיטגליד בלעטער פון א ספעציפישער קאטעגאריע).
בלעטער אויף [[Special:Watchlist|אייער אויפפאסונג ליסטע]] זענען געוויזן '''דיק'''.",
'recentchangeslinked-page'     => 'בלאַט נאָמען:',
'recentchangeslinked-to'       => 'צייג ענדערונגען צו בלעטער פארבינדן צו דעם בלאט אנשטאט',

# Upload
'upload'                      => 'אַרױפֿלאָדן בילדער/טעקעס',
'uploadbtn'                   => 'אַרױפֿלאָדן טעקע',
'reuploaddesc'                => 'אַנולירן אַרויפֿלאָד און צוריקגיין צו דער אַרויפֿלאָדן פֿארעם',
'upload-tryagain'             => 'פֿאָרלייגן מאדיפֿיצירטע טעקע באַשרײַבונג',
'uploadnologin'               => 'נישט אַרײַנלאגירט',
'uploadnologintext'           => 'איר מוזט זײַן [[Special:UserLogin| אַרײַנלאָָגירט]] כדי ארויפֿצולאָדן טעקעס',
'upload_directory_missing'    => 'די ארויפלאד דירעקטאריע ($1) פעלט און דער וועבסערווירער קען זי נישט שאפן.',
'upload_directory_read_only'  => 'דער וועבסארווער קען נישט שרייבן צום ארויפלאדן ארכיוו "$1".',
'uploaderror'                 => 'אַרויפֿלאָדן פֿעלער',
'upload-recreate-warning'     => "'''ווארענונג: א טעקע מיט דעם נאמען איז געווארן אויסגעמעקט אדער באוועגט.'''

דאס אויסמעקן־ און באוועגן־לאגבוך פאר דעם בלאט זענען געוויזן דא:",
'uploadtext'                  => "באניצט דעם פֿארעם אַרויפֿצולאָדן טעקעס.
כדי צו זען אדער זוכן טעקעס וואס זענען שוין אַרויפֿגעלאָדן ווענדט זיך צו דער [[Special:FileList|ליסטע פֿון אַרויפֿגעלאָדענע טעקעס]]; (ווידער)אַרויפֿלאָדונגען ווערן אויך לאגירט אינעם  [[Special:Log/upload| אַרויפֿלאָדן לאג-בוך]], אויסמעקונגען אינעם [[Special:Log/delete|אויסמעקן לאג-בוך]].

כדי אײַנשליסן א טעקע אין א בלאַט, באניצט א לינק אין איינעם פון די פֿאלגנדע פֿארעמען:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''' צו ניצן די פֿולע ווערסיע פֿון דער טעקע
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|טעקסט קעפל]]</nowiki></code>''' צו ניצן א 200 פיקסל ברייט ווערסיע אין א קעסטל אויף דער לינקער זײַט, מיט דער שילדערונג 'טעקסט קעפל'
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' פֿאר א גראָדער פֿאַרבינדונג צו דער טעקע אָן צו ווײַזן זי",
'upload-permitted'            => 'ערלויבטע טעקע טיפן: $1.',
'upload-preferred'            => 'פרעפֿרירטע טעקע טיפן: $1.',
'upload-prohibited'           => 'פֿאַרווערענע טעקע טיפן: $1.',
'uploadlog'                   => 'ארויפלאָדן לאָגבוך',
'uploadlogpage'               => 'ארויפֿלאדן לאג',
'uploadlogpagetext'           => 'פֿאָלגנד איז אַ ליסטע פֿון די לעצטע אַרױפֿגעלאָדענע טעקעס.
זעט די  [[Special:NewFiles|גאלאריע פֿון נײַע טעקעס]] פֿאַר א מער וויזועלע איבערבליק.',
'filename'                    => 'טעקע נאמען',
'filedesc'                    => 'רעזומע',
'fileuploadsummary'           => 'רעזומע:',
'filereuploadsummary'         => 'טעקע ענדערונגען:',
'filestatus'                  => 'קאפירעכט סטאַטוס:',
'filesource'                  => 'מקור:',
'uploadedfiles'               => 'ארויפֿגעלאדעטע טעקעס',
'ignorewarning'               => 'איגנאָרירן ווארענונג און אויפֿהיטן טעקע סיי ווי סיי',
'ignorewarnings'              => 'איגנארירן וואָרענונגען',
'minlength1'                  => 'א טעקע נאמען מוז האבן כאטש איין אות.',
'illegalfilename'             => 'דער טעקע־נאָמען "$1" אַנטהאַלט כאַראַקטערס וואָס זענען נישט ערלויבט אין בלאַט טיטלען.
ביטע גיט דער טעקע א נײַעם נאמען און פּרובירט ארויפֿלאָדן נאכאַמאָל.',
'filename-toolong'            => 'טעקע נעמען קען נישט זײַן לענגער ווי 240 בייטן.',
'badfilename'                 => 'טעקע נאמען איז געטוישט צו "$1".',
'filetype-mime-mismatch'      => 'טעקע סופֿיקס ".$1" שטימט נישט מיטן MIME טיפ פון דער טעקע($2).',
'filetype-badmime'            => 'טעקעס מיטן  MIME טיפ "$1" טאר מען נישט ארויפלאדן.',
'filetype-bad-ie-mime'        => 'נישט מעגלעך ארויפלאד די טעקע ווייל אינטערנעץ עקספלארער וועט זי דערקענען ווי "$1", וואס איז א נישט דערלויבטער און פאטענציאעל געפערליכער טעקע טיפ.',
'filetype-unwanted-type'      => "'''\".\$1\"''' איז אן אומרעקאמענדירטער טעקע־טיפ. {{PLURAL:\$3|רעקאמענדירטער טעקע־טיפ איז|רעקאמענדירטע טעקע־טיפן זענען}} \$2.",
'filetype-banned-type'        => '\'\'\'".$1"\'\'\' {{PLURAL:$4|איז נישט קיין דערלויבטער טעקע־טיפ |זענען נישט קיין דערלויבטע טעקע־טיפן}}. {{PLURAL:$3|דערלויבטער טעקע־טיפ איז|דערלויבטע טעקע־טיפן זענען}} $2.',
'filetype-missing'            => 'די טעקע האט נישט קיין פארברייטערונג (למשל ".jpg").',
'empty-file'                  => 'די טעקע וואָס איר האט אײַנגעגעבן איז ליידיג.',
'file-too-large'              => 'די טעקע וואָס איר האט אײַנגעגעבן איז צו גרויס.',
'filename-tooshort'           => 'דער טעקענאמען איז צו קורץ',
'filetype-banned'             => 'דער טיפ טעקע איז געאַסרט',
'verification-error'          => 'די טעקע איז נישט אדורכגעגאנגען טעקע פרואוואונג.',
'hookaborted'                 => 'די מאדיפיצירונג איר האט פרובירט קען נישט ווערן דורכגעפירט צוליב א פארברייטערונג.',
'illegal-filename'            => 'דער טעקע־נאָמען איז נישט ערלויבט',
'overwrite'                   => 'מען טאָר נישט איבערשרײַבן אַן עקזיסטירנדע טעקע.',
'unknown-error'               => 'אַן אומבאַקאַנט טעות איז פֿארגעקומען.',
'tmp-create-error'            => 'קען נישט שאַפֿן צייַטווייַליקע טעקע.',
'tmp-write-error'             => 'טעות בײַם שרייַבן צייַטווייַליקע טעקע.',
'large-file'                  => 'רעקאמענדירט אז טעקעס זאל נישט זײַן גרעסער פֿון$1;
די טעקע איז $2.',
'largefileserver'             => 'די טעקע איז גרעסער פונעם מאקסימום פאר דעם סערווער.',
'emptyfile'                   => 'די טעקע וואס איר האט ארויפֿלגעלאָדן איז ליידיג.
עס קען זיין אז די סיבה איז פשוט א טייפא אינעם טעקע־נאמען.
ביטע קוקט איבער צי איר ווילט ארויפֿלאדן  די דאזיקע טעקע.',
'windows-nonascii-filename'   => 'די וויקי שטיצט נישט טעקע־נעמען מיט ספעציעלע צייכענען.',
'fileexists'                  => 'א טעקע מיט דעם נאָמען עקזיסטירט שוין, ביטע זײַט בודק <strong>[[:$1]]</strong> ווען איר זענט נישט זיכער אַז איר ווילט זי ענדערן.
[[$1|thumb]]',
'fileexists-extension'        => 'א טעקע מיט אן ענלעכן נאמען עקזיסטירט שוין: [[$2|thumb]]
* נאמען פון דער טעקע וואס ווערט ארויפגעלאָדן: <strong>[[:$1]]</strong>
* נאמען פון דער פֿאראנענער טעקע: <strong>[[:$2]]</strong>
זײַט אזוי גוט און קלויבט אן אנדער נאמען.',
'file-thumbnail-no'           => "דער טעקע־נאמען הייבט אן מיט <strong>$1</strong>.
זי זעט אויס ווי א פארקלענערט בילד ''(מיניאטור)''.
טאמער האט איר דאס בילד אין פולער רעזאלוציע טוט עס ארויפלאדן, אנדערשט זייט אזוי גוט און ענדערט דעם טעקע־נאמען.",
'fileexists-forbidden'        => 'א טעקע מיט דעם נאָמען עקזיסטירט שוין, און מען קען זי נישט אַריבערשרײַבן. 
אויב איר ווילט דאך אַרויפֿלאָדן אײַער טעקע, ביטע גיין צוריק און ניצן אַן אַנדער נאָמען. 
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'א טעקע מיט דעם נאָמען עקזיסטירט שוין אינעם צענטראַלן אַרכיוו.
אויב איר ווילט דאך אַרויפֿלאָדן אײַער טעקע, ביטע גיין צוריק און ניצן אַן אַנדער נאָמען. 
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'די טעקע איז א דופליקאַט פון די פֿאלגנדע {{PLURAL:$1|טעקע|טעקעס}}:',
'file-deleted-duplicate'      => "א טעקע אידענטיש מיט דער טעקע ([[:$1]]) האט מען שוין אויסגעמעקט.
איר זאלט קאנטראלירן דער טעקע'ס אויסמעקן היסטאריע איידער איר טוט ארויפלאדן פונדאסניי.",
'uploadwarning'               => 'אַרויפֿלאָדן וואָרענונג',
'uploadwarning-text'          => 'זײַט אַזוי גוט מאדיפֿיצירן די טעקע באַשרייבונג און פרובירט נאכאַמאָל.',
'savefile'                    => 'אױפֿהיטן טעקע',
'uploadedimage'               => 'אַרױפֿגעלאָדן "[[$1]]"',
'overwroteimage'              => 'אַרויפֿגעלאָדן א נײַע ווערסיע פון "[[$1]]"',
'uploaddisabled'              => 'אַרויפֿלאָדן טעקעס מבוטל',
'copyuploaddisabled'          => 'ארויפלאדן דורך URL אומאקטיווירט',
'uploadfromurl-queued'        => 'אייער ארויפֿלאד איז אין דער רייע.',
'uploaddisabledtext'          => 'אַרויפֿלאָדן טעקעס נישט דערמעגלעכט.',
'php-uploaddisabledtext'      => 'אַרויפֿלאָדן טעקעס נישט דערמעגלעכט אין PHP.
זייט אזוי גוט בודק זיין די file_uploads שטעלונג.',
'uploadscripted'              => 'די טעקע האט א סקריפט אדער HTML קאד וואס קען ווערן פֿאלש אויסגעטייטשט דורך א בלעטערער',
'uploadvirus'                 => 'די טעקע האָט אַ ווירוס! פרטים: <div dir="rtl">$1</div>',
'upload-source'               => 'מקור טעקע',
'sourcefilename'              => 'מקור טעקע נאמען:',
'sourceurl'                   => 'מקור URL:',
'destfilename'                => 'ציל טעקע נאמען:',
'upload-maxfilesize'          => 'מאַקסימום טעקע גרייס: $1',
'upload-description'          => 'טעקע שילדערונג',
'upload-options'              => "אַרויפֿלאָדן ברירה'ס",
'watchthisupload'             => 'אויפֿפאַסן דעם בלאט',
'filewasdeleted'              => 'א טעקע מיט דעם נאמען האט מען שוין ארויפגעלאדן און דערנאך אויסגעמעקט.
איר זאלט בודק זיין דעם $1 איידער איר הייבט אן ארויפלאדן ווידעראמאל.',
'upload-success-subj'         => 'דערפֿאלגרייכער ארויפֿלאָד',
'upload-success-msg'          => 'אײַער אַרויפֿלאָד פֿון [$2] איז געווען דערפֿאלגרייך. עס איז פֿאַראָן דאָ: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'אַרויפֿלאָדן פראבלעם',
'upload-failure-msg'          => "ס'איז געווען א פראבלעם מיט אײַער אָרויפֿלאַד פֿון [$2]:

$1",
'upload-warning-subj'         => 'אַרויפֿלאָדן וואָרענונג',
'upload-warning-msg'          => 'געווען א פראבלעם מיט אײַער ארויפֿלאָד פֿון [$2]. איר קענט צוריקקערן צום [[Special:Upload/stash/$1|ארויפֿלאָדן פֿארעם]] צו פֿאררעכטן דעם פראבלעם.',

'upload-proto-error'        => 'פֿאלשער פראטאקאל',
'upload-proto-error-text'   => 'ביי א ווייטן ארויפלאד דארף דער URL אנהייבן מיט <code>http://</code> אדער <code>ftp://</code>.',
'upload-file-error'         => 'אינערליכער פֿעלער',
'upload-file-error-text'    => 'אן אינערלעכע פֿעלער האט פאסירט ביים פרובירן צו שאפֿן א פראוויזארישע טעקע אויפֿן סארווער.
ביטע פֿארבינדט זיך מיט א [[Special:ListUsers/sysop|סיסאפ]].',
'upload-misc-error'         => 'אומבאַוואוסטער ארויפֿלאָדן גרײַז',
'upload-misc-error-text'    => 'אן אומבאקאנטער גרייז האט פאסירט בשעת דעם ארויפלאד.
ביטע באשטעטיקט אז דער  URL איז גילטיק און דערגרייכבאר און פרובירט נאכאמאל.
ווען דער פראבלעם בלייבט ווייטער, קאנטאקטירט  א [[Special:ListUsers/sysop|סיסאפ]].',
'upload-too-many-redirects' => 'דער URL אַנטהאַלט צופֿיל ווײַטערפֿירונגען.',
'upload-unknown-size'       => 'אומוויסנדע גרייס',
'upload-http-error'         => 'א HTTP גרײַז האט פאַסירט: $1',

# File backend
'backend-fail-stream'        => 'קען נישט מאכן שטראמען טעקע $1.',
'backend-fail-notexists'     => 'נישט פֿאראן די טעקע $1.',
'backend-fail-invalidpath'   => '$1 איז נישט קיין גילטיקער שפייכלערן שטעג.',
'backend-fail-delete'        => 'קען נישט אויסמעקן טעקע $1.',
'backend-fail-alreadyexists' => 'די טעקע $1 עקזיסטירט שוין.',
'backend-fail-store'         => "מ'קען נישט שפייכלערן טעקע $1 בײַ $2.",
'backend-fail-copy'          => 'האט נישט געקענט קאפירן "$1" צו "$2".',
'backend-fail-move'          => 'האט נישט געקענט באוועגן "$1" צו "$2".',
'backend-fail-opentemp'      => 'קען נישט עפֿענען צייַטווייַליקע טעקע.',
'backend-fail-writetemp'     => 'קען נישט שרײַבן צו צייַטווייַליקער טעקע.',
'backend-fail-closetemp'     => 'קען נישט שליסן צייַטווייַליקע טעקע.',
'backend-fail-read'          => 'קען נישט ליינען טעקע "$1".',
'backend-fail-create'        => 'קען נישט שרייבן טעקע "$1".',

# Lock manager
'lockmanager-notlocked'       => 'מ\'קען נישט אויפֿשליסן "$1"; ער איז נישט פֿארשלאסן.',
'lockmanager-fail-deletelock' => 'נישט מעגלעך אויסמעקן שלאס טעקע פאר "$1".',

# ZipDirectoryReader
'zip-wrong-format' => 'ספעציפירטע טעקע איז נישט קיין ZIP טעקע.',

# Special:UploadStash
'uploadstash'         => 'אַרויפֿלאָד רעזערוו',
'uploadstash-clear'   => 'אויסמעקן טעקעס פון זאפאס',
'uploadstash-nofiles' => 'איר האט נישט קיין טעקעס אין זאפאס.',
'uploadstash-refresh' => 'דערפֿרישן די רשימה פון טעקעס',

# img_auth script messages
'img-auth-accessdenied' => 'צוטריט אָפגעזאָגט',
'img-auth-badtitle'     => 'קען נישט שאפֿן א גילטיקן טיטל פֿון "$1"',
'img-auth-nologinnWL'   => 'איר זענט נישט ארײַנלאגירט און "$1" איז נישט אין דער ווײַסער ליסטע.',
'img-auth-nofile'       => 'טעקע "$1" עקזיסטירט נישט.',
'img-auth-isdir'        => 'איר פֿארזיכט צוצוטרעטן אן ארכיוו "$1".
נאר טעקע צוטריט איז ערלויבט.',
'img-auth-streaming'    => 'שטראָמענדיק "$1".',
'img-auth-noread'       => 'באניצער האט נישט קיין דערלויבניש צו ליינען "$1".',

# HTTP errors
'http-invalid-url'      => 'אומגילטיג URL: $1',
'http-invalid-scheme'   => 'URL אדרעסן מיט דער "$1" סכעמע ווערן נישט געשטיצט.',
'http-request-error'    => 'HTTP בקשה דורכגעפאלן צוליב אומבאוואוסטער פעלער.',
'http-read-error'       => 'HTTP לייענען גרײַז.',
'http-timed-out'        => 'HTTP בקשה אויסגעגאַנגען.',
'http-curl-error'       => 'גרײַז בײַם ברענגען URL: $1',
'http-host-unreachable' => "מ'קען נישט דערגרייכן דעם URL",
'http-bad-status'       => "ס'איז געווען א פראבלעם ביים HTTP פֿאַרלאַנג: $1 $2",

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'  => "מ'קען נישט דערגרייכן URL",
'upload-curl-error28' => 'אַרויפֿלאָדן צײַט־ענדע',

'license'            => 'ליצענץ:',
'license-header'     => 'ליצענץ:',
'nolicense'          => 'גארנישט',
'license-nopreview'  => '(פֿאראויסקוק נישט פֿאַראַן)',
'upload_source_url'  => ' (א גילטיקע , צוגעגנלעכער URL)',
'upload_source_file' => '(א טעקע אויף אײַער קאמפיוטער)',

# Special:ListFiles
'listfiles_search_for'  => 'זוכן פֿאַר מעדיע נאָמען:',
'imgfile'               => 'טעקע',
'listfiles'             => 'טעקע ליסטע',
'listfiles_thumb'       => 'געמינערט בילד',
'listfiles_date'        => 'דאטע',
'listfiles_name'        => 'נאמען',
'listfiles_user'        => 'באַניצער',
'listfiles_size'        => 'גרייס',
'listfiles_description' => 'באַשרײַבונג',
'listfiles_count'       => 'ווערסיעס',

# File description page
'file-anchor-link'                  => 'בילד טעקע',
'filehist'                          => 'היסטאריע פֿון דער טעקע',
'filehist-help'                     => 'קליקט אויף א דאטע/צײַט צו זען דאס בילד אזוי ווי עס איז דעמאלסט געווען',
'filehist-deleteall'                => 'אויסמעקן אלץ',
'filehist-deleteone'                => 'אויסמעקן',
'filehist-revert'                   => 'צוריקשטעלן',
'filehist-current'                  => 'לויפיק',
'filehist-datetime'                 => 'דאטע/צײַט',
'filehist-thumb'                    => 'געמינערטע בילד',
'filehist-thumbtext'                => 'געמינערטע בילד פֿאַר דער װערסיע פֿון דער דאַטע $1',
'filehist-nothumb'                  => 'קיין פֿאַרקלענערט בילד',
'filehist-user'                     => 'באניצער',
'filehist-dimensions'               => 'געמעסטן',
'filehist-filesize'                 => 'טעקע גרייס',
'filehist-comment'                  => 'באמערקונג',
'filehist-missing'                  => 'טעקע פעלט',
'imagelinks'                        => 'טעקע באַניץ',
'linkstoimage'                      => '{{PLURAL:$1|דער פאלגנדער בלאט ניצט|די פאלגנדע בלעטער ניצן}} דאס דאזיגע בילד:',
'linkstoimage-more'                 => "מער ווי $1 {{PLURAL:$1|בלאַט פֿאַרבינדט|בלעטער פֿאַרבינדן}} צו דער דאזיגער טעקע.
די פֿאלגנדע ליסטע ווײַזט  {{PLURAL:$1|דעם ערשטן בלאַט לינק|די ערשטע $1 בלאַט לינקען}} צו דער טעקע.
ס'איז פֿאַראַן[[Special:WhatLinksHere/$2|פֿולע רשימה]].",
'nolinkstoimage'                    => 'נישטא קיין בלעטער וואס ניצן דאס דאזיגע בילד.',
'morelinkstoimage'                  => 'באַקוקן  [[Special:WhatLinksHere/$1|מער לינקען]] צו דער טעקע.',
'linkstoimage-redirect'             => '$1 (טעקע ווײַטערפֿירונג) $2',
'duplicatesoffile'                  => 'די פֿאלגנדע {{PLURAL:$1|טעקע דופליקירט|$1 טעקעס דופליקירן}} די דאזיגע טעקע ([[Special:FileDuplicateSearch/$2|נאך פרטים]]):',
'sharedupload'                      => 'די טעקע איז פֿון $1 און מען מעג זי ניצן אין אנדערע פראיעקטן.',
'sharedupload-desc-there'           => 'די טעקע איז פֿון $1 און מען מעג זי ניצן אין אנדערע פראיעקטן.
זעט דעם [$2 טעקע באשרייבונג בלאט] פאר מער אינפארמאציע.',
'sharedupload-desc-here'            => 'די טעקע איז פֿון $1 און מען מעג זי ניצן אין אנדערע פראיעקטן.
די באשרייבונג פון איר  [$2 טעקע באשרייבונג בלאט] דארט ווערן געוויזן אונטן.',
'filepage-nofile'                   => 'עס עקזיסטירט נישט קיין טעקע מיט דעם נאמען.',
'filepage-nofile-link'              => 'עס עקזיסטירט נישט קיין טעקע מיט דעם נאמען, אבער איר קענט זי [$1 ארויפֿלאָדן].',
'uploadnewversion-linktext'         => 'ארויפֿלאדן א נײַע ווערסיע פֿוו דער טעקע',
'shared-repo-from'                  => 'פֿון $1',
'shared-repo'                       => 'א געמיינזאַמער זאַפאַס',
'shared-repo-name-wikimediacommons' => 'וויקימעדיע קאמאנס',

# File reversion
'filerevert'                => 'צוריקדרייען $1',
'filerevert-legend'         => 'צוריקדרייען טעקע',
'filerevert-intro'          => "איר האַלט בײַ צוריקשטעלן די טעקע '''[[Media:$1|$1]]''' צו דער [$4 ווערסיע פֿון $3, $2].",
'filerevert-comment'        => 'אורזאַך:',
'filerevert-defaultcomment' => 'צוריקגעשטעלט צו דער ווערסיע פֿון $2, $1',
'filerevert-submit'         => 'צוריקדרייען',
'filerevert-success'        => "'''[[Media:$1|$1]]''' צוריקגשטעלט צו דער [$4 ווערסיע פֿון $3, $2].",
'filerevert-badversion'     => 'נישט פאראן קיין פריערדיקע לאקאלע ווערסיע פון דער טעקע מיטן געזוכטן צייטשטעמפל.',

# File deletion
'filedelete'                   => 'מעק אויס $1',
'filedelete-legend'            => 'מעק אויס טעקע',
'filedelete-intro'             => "איר האַלט בײַ אויסמעקן די טעקע '''[[Media:$1|$1]]''' צוזאַמען מיט גאָר איר היסטאריע.",
'filedelete-intro-old'         => "איר מעקט אויס די ווערסיע פֿון  '''[[Media:$1|$1]]''' פֿון [$4 $3, $2].",
'filedelete-comment'           => 'אורזאַך:',
'filedelete-submit'            => 'אויסמעקן',
'filedelete-success'           => "'''$1''' איז געווען אויסגעמעקט.",
'filedelete-success-old'       => "די ווערסיע פֿון '''[[Media:$1|$1]]''' פֿון $3, $2 איז געווארן אויסגעמעקט.",
'filedelete-nofile'            => "'''$1''' עקזיסטירט נישט.",
'filedelete-nofile-old'        => "נישט פאראן קיין ארכיווירטע ווערסיע פון '''$1''' מיט די ספעציפירטע אייגנקייטן.",
'filedelete-otherreason'       => 'אנדער/נאך א סיבה:',
'filedelete-reason-otherlist'  => 'אַנדער אורזאַך',
'filedelete-reason-dropdown'   => '*אַלגעמיינע אויסמעקן סיבות
** קאפירעכט פֿאַרלעצונג
** דופליקאַט',
'filedelete-edit-reasonlist'   => 'רעדאַקטירן אויסמעקן סיבות',
'filedelete-maintenance'       => 'אויסמעקן און צוריקשטעלן טעקעס צײַטווײַליק אומדערמעגלעכט בשעת אויפהאלטן.',
'filedelete-maintenance-title' => 'מען קען נישט אויסמעקן די טעקע',

# MIME search
'mimesearch' => 'זוך MIME',
'mimetype'   => 'MIME טיפ:',
'download'   => 'אַראָפלאָדן',

# Unwatched pages
'unwatchedpages' => 'בלעטער וואס זענען נישט אויפגעפאסט',

# List redirects
'listredirects' => 'ליסטע פון ווײַטערפֿירונגען',

# Unused templates
'unusedtemplates'     => 'נישט באניצטע מוסטערן',
'unusedtemplatestext' => 'דער בלאט ווײַזט אלע בלעטער אינעם {{ns:template}} נאמענטייל וואס זענען נישט אײַנגעשלאסן אין אן אנדער בלאט. געדענקט צו באקוקן אנדערע בלעטער פאר לינקען צו די מוסטערן איידער איר מעקט זיי אויס.',
'unusedtemplateswlh'  => 'אנדערע פֿאַרבינדונגען',

# Random page
'randompage'         => 'צופֿעליגער אַרטיקל',
'randompage-nopages' => 'נישטא קיין בלעטער אין {{PLURAL:$2|דעם פאלגנדן נאמענטייל |די פאלגנדע נאמענטיילן}} "$1".',

# Random redirect
'randomredirect'         => 'צופֿעליק ווײַטערפֿירן',
'randomredirect-nopages' => 'נישטא קיין ווײַטערפֿירונגען אין דעם נאמענטייל $1.',

# Statistics
'statistics'                   => 'סטאַטיסטיק',
'statistics-header-pages'      => 'בלעטער סטאטיסטיק',
'statistics-header-edits'      => 'רעדאקטירן סטאַטיסטיק',
'statistics-header-views'      => 'זען סטאטיסטיק',
'statistics-header-users'      => 'באניצער סטאטיסטיק',
'statistics-header-hooks'      => 'אנדערע סטאטיסטיק',
'statistics-articles'          => 'אינהאלט בלעטער',
'statistics-pages'             => 'בלעטער',
'statistics-pages-desc'        => 'אלע בלעטער אין דער וויקי, כולל רעדן בלעטער, ווייטערפירונגען, אא"וו',
'statistics-files'             => 'ארויפֿגעלאדענע טעקעס',
'statistics-edits'             => 'רעדאַקטירונגען זײַט {{SITENAME}} איז אויפֿגעשטעלט',
'statistics-edits-average'     => 'דורכשניט רעדאַקטירונגען אין א בלאַט',
'statistics-views-total'       => 'צאל קוקן אינגאַנצן',
'statistics-views-total-desc'  => 'באַקוקן צו נישט־פֿאַרהאַנען בלעטער און באַזונדערע בלעטער זענען נישט אַרייַנגערעכנט.',
'statistics-views-peredit'     => 'צאל קוקן צו א רעדאַקטירונג',
'statistics-users'             => 'איינגעשריבענע [[Special:ListUsers|באניצערס]]',
'statistics-users-active'      => 'טעטיקע באניצערס',
'statistics-users-active-desc' => 'באניצערס וואס האבן דורכגעפירט א פעולה אין די לעצטע {{PLURAL:$1|טאג|$1 טעג}}',
'statistics-mostpopular'       => 'מערסטע געזען בלעטער',

'disambiguations'      => 'בלעטער וואס פֿארבינדן מיט באדייטן בלעטער',
'disambiguationspage'  => 'Template:באדייטן',
'disambiguations-text' => "די קומענדיגע בלעטער פארבינדן צו א '''באדייטן בלאט'''. זיי ברויכן ענדערשט פֿארבינדן צו דעם רעלעוואנטן טעמע בלאט.<br />א בלאט ווערט פאררעכענט פאר א באדײַטן בלאט אויב ער באניצט זיך מיט א מוסטער וואס איז פארבינדען פון [[MediaWiki:Disambiguationspage]].",

'doubleredirects'                   => 'געטאפלטע ווײַטערפֿירונגען',
'doubleredirectstext'               => 'דער בלאט רעכנט אויס בלעטער וואס פירן ווייטער צו אנדערע ווייטערפירן בלעטער.
יעדע שורה אנטהאלט א לינק צום ערשטן און צווייטן ווייטערפירונג, ווי אויך די ציל פון דער צווייטער ווייטערפירונג, וואס רוב מאל געפינט זיך די ריכטיגע ציל וואו די ערשטע ווייטערפירונג זאל ווייזן.
<del>אויסגעשטראכענע</del> טעמעס זענען שוין געלייזט.',
'double-redirect-fixed-move'        => '[[$1]] איז געווארן באוועגט, און איז יעצט א ווייטערפֿירונג צו [[$2]]',
'double-redirect-fixed-maintenance' => 'פֿאַררעכטן געטאפלטע ווײַטערפֿירונג פֿון [[$1]] צו [[$2]].',
'double-redirect-fixer'             => 'מתקן ווײַטערפֿירונגען',

'brokenredirects'        => 'צעבראָכענע ווײַטערפֿירונגען',
'brokenredirectstext'    => 'די פֿאלגנדע ווײַטערפֿירונגען פֿאַרבינדן צו בלעטער וואס עקזיסטירן נאך נישט:',
'brokenredirects-edit'   => 'ענדערן',
'brokenredirects-delete' => 'אויסמעקן',

'withoutinterwiki'         => 'בלעטער אן שפראך פֿארבינדונגען',
'withoutinterwiki-summary' => 'די פֿאלגנדע בלעטער פֿאַרבינדן נישט מיט אַנדערע שפראַך ווערסיעס',
'withoutinterwiki-legend'  => 'פרעפֿיקס',
'withoutinterwiki-submit'  => 'ווײַזן',

'fewestrevisions' => 'בלעטער מיט די מינדערסטע רעוויזיעס',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|בייט|בייטן}}',
'ncategories'             => '{{PLURAL:$1|קאטעגאריע|$1 קאטעגאריעס}}',
'nlinks'                  => '$1 {{PLURAL:$1|לינק|לינקען}}',
'nmembers'                => '$1 {{PLURAL:$1|בלאט|בלעטער}}',
'nrevisions'              => '{{PLURAL:$1|איין רעוויזיע|$1 רעוויזיעס}}',
'nviews'                  => '{{PLURAL:$1|איין קוק|$1 קוקן}}',
'nimagelinks'             => 'געניצט אויף $1 {{PLURAL:$1|בלאַט|בלעטער}}',
'ntransclusions'          => 'געניצט אויף $1 {{PLURAL:$1|בלאַט|בלעטער}}',
'specialpage-empty'       => 'דער בלאט איז ליידיג.',
'lonelypages'             => "פֿאר'יתומ'טע בלעטער",
'lonelypagestext'         => 'די פֿאלגנדע בלעטער זענען נישט פֿאַרבינדן פֿון אדער אריבערגעשלאסן אין אנדערע בלעטער אין {{SITENAME}}.',
'uncategorizedpages'      => 'אומקאטעגאריזירטע בלעטער',
'uncategorizedcategories' => 'אומקאטעגאריזירטע קאטעגאריעס',
'uncategorizedimages'     => 'אומקאטעגאריזירטע טעקעס',
'uncategorizedtemplates'  => 'אומקאטעגאריזירטע מוסטערן',
'unusedcategories'        => 'נישט געניצטע קאטעגאריעס',
'unusedimages'            => 'נישט געניצטע טעקעס',
'popularpages'            => 'פאפולערע בלעטער',
'wantedcategories'        => 'געזוכטע קאטעגאריעס',
'wantedpages'             => 'געזוכטע בלעטער',
'wantedpages-badtitle'    => 'אומגילטיקער טיטל אין רעזולטאַט: $1',
'wantedfiles'             => 'געזוכטע טעקעס',
'wantedfiletext-cat'      => 'די פֿאלגנדע טעקעס ווערן געניצט אבער זיי עקזיסטירן נישט. טעקעס פון פֿרעמדע רעפאזיטאריעס קענען ווערן אריינגערעכנט טראץ זיי עקזיסטירן יא. אזעלכע גרייזן וועלן ווערן <del>אויסגעשריכן </del>. דערצו, בלעטער וואס ניצן אומעקזיסטירנדע טעקעס ווערן אריינגערעכנט אין [[:$1]].',
'wantedtemplates'         => 'געזוכטע מוסטערן',
'mostlinked'              => 'מערסט פֿארבינדענע בלעטער',
'mostlinkedcategories'    => 'מערסט פֿארבינדענע קאטעגאריעס',
'mostlinkedtemplates'     => 'מערסט פֿארבינדענע מוסטערן',
'mostcategories'          => 'אַרטיקלען מיט די מערסטע קאַטעגאָריעס',
'mostimages'              => 'מערסט פֿארבונדענע טעקעס',
'mostrevisions'           => 'אַרטיקלען מיט די מערסטע באַאַרבעטונגען',
'prefixindex'             => 'פּרעפֿיקס אינדעקס',
'prefixindex-namespace'   => 'אלע בלעטער מיט פרעפֿיקס ($1 נאמענטייל)',
'shortpages'              => 'קורצע בלעטער',
'longpages'               => 'לאנגע בלעטער',
'deadendpages'            => 'בלינדע בלעטער',
'deadendpagestext'        => 'די פאלגנדע בלעטער לינקען נישט צו קיין אנדערע בלעטער אין דער וויקי.',
'protectedpages'          => 'געשיצטע בלעטער',
'protectedpages-indef'    => 'בלויז אומבאַשרענקטע באַשוצינגען',
'protectedpages-cascade'  => 'בלויז קאַסקאַדירנדיקע באַשיצונגען',
'protectedpagestext'      => 'די פֿאלגנדע בלעטער זענען געשיצט פון רעדאַקטירן און באוועגן:',
'protectedpagesempty'     => 'אצינד זענען קיין בלעטער נישט געשיצט מיט די דאזיגע פאַראַמעטערס.',
'protectedtitles'         => 'געשיצטע קעפלעך',
'protectedtitlestext'     => 'די פֿאלגנדע קעפלעך זענען געשיצט פון באשאפֿן:',
'protectedtitlesempty'    => 'אצינד זענען קיין קעפלעך נישט באַשיצט מיט די דאזיגע פאַראַמעטערס.',
'listusers'               => 'באַניצער ליסטע',
'listusers-editsonly'     => 'ווייזן נאר באניצערס מיט רעדאקטירונגען',
'listusers-creationsort'  => 'סארטירן לויט דער שאַפן דאַטע',
'usereditcount'           => '{{PLURAL:$1|רעדאַקטירונג|$1 רעדאַקטירונגען}}',
'usercreated'             => '{{GENDER:$3|געשאַפֿן}} אום $2, $1',
'newpages'                => 'נייע בלעטער',
'newpages-username'       => 'באַניצער נאָמען:',
'ancientpages'            => 'עלטסטע בלעטער',
'move'                    => 'באַװעגן',
'movethispage'            => 'באוועג דעם בלאט',
'unusedimagestext'        => 'די פֿאלגנדע טעקעס עקזיסטירן אבער ווערן נישט גענוצט אין קיין שום בלאַט.
גיט אַכט אז אנדערע וועבערטער קענען פֿארבינדן צו א טעקע מיט א דירעקטן URL, און קענען דעריבער באווײַזן זיך דאָ כאטש זיי זענען אין אקטיוון באניץ.',
'unusedcategoriestext'    => 'די פֿאלגנדע קאטעגאריעס עקסיסטירן, אבער קיין בלאט אדער קאטעגאריע ניצט זיי נישט.',
'notargettitle'           => 'קיין ציל',
'notargettext'            => 'איר האט נישט ספעציפֿירט קיין ציל בלאַט אדער באַניצער אויף וועמען אויסצופֿירן די פעולה.',
'nopagetitle'             => 'נישטא אזא ציל בלאט',
'nopagetext'              => 'דער ציל בלאט וואס איר האט ספעציפֿירט עקזיסטירט נישט.',
'pager-newer-n'           => '{{PLURAL:$1|נײַערע|$1 נײַערע}}',
'pager-older-n'           => '{{PLURAL:$1|עלטערע|$1 עלטערע}}',
'suppress'                => 'אויפֿזען',
'querypage-disabled'      => 'דער באַזונדער־בלאַט איז אומאַקטיווירט צוליב אויספֿירונג סיבות.',

# Book sources
'booksources'               => 'דרויסנדיגע ליטעראַטור ISBN',
'booksources-search-legend' => 'זוכן פאר דרויסנדע ביכער מקורות',
'booksources-go'            => 'גיין',
'booksources-text'          => 'אונטן איז א ליסטע פון סייטס וואס פֿארקויפֿן נייע און גענוצטע ביכער און האבן אויך נאך אינפֿארמאציע וועגן די ביכער וואס איר זוכט:',
'booksources-invalid-isbn'  => 'דאָס געגעבענע ISBN זעט נישט אויס צו זיין גילטיק; קאנטראלירט פֿאַר גרײַזן בײַם קאפּירן פון דעם ערשטיקן מקור.',

# Special:Log
'specialloguserlabel'  => 'אויספֿירער:',
'speciallogtitlelabel' => 'ציל (טיטל אדער באניצער):',
'log'                  => 'לאגביכער',
'all-logs-page'        => 'אלע פובליקע לאגביכער',
'alllogstext'          => 'קאמבינירטער אויסשטעל פון אלע לאגביכער פון {{SITENAME}} בנמצא.
מען קען פֿאַרשמעלרן די אויסוואל דורך אויסוויילן א סארט לאג, באַניצער נאמען אדער אנרירנדע בלעטער.',
'logempty'             => 'נישטא קיין פאַסנדיקע זאכן אין לאג.',
'log-title-wildcard'   => 'זוכן טיטלען וואס הייבן אָן מיט דעם טעקסט',

# Special:AllPages
'allpages'          => 'אַלע בלעטער',
'alphaindexline'    => '$1 ביז $2',
'nextpage'          => 'קומענדיקער בלאַט ($1)',
'prevpage'          => 'פֿריִערדיקער בלאַט ($1)',
'allpagesfrom'      => 'ווייזן בלעטער אנגעהויבן פון:',
'allpagesto'        => 'ווייזן בלעטער ביז:',
'allarticles'       => 'אַלע אַרטיקלען',
'allinnamespace'    => 'אלע בלעטער ($1 נאָמענטייל )',
'allnotinnamespace' => 'אלע בלעטער (נישט אין נאמענטייל  $1)',
'allpagesprev'      => 'פריערדיגע',
'allpagesnext'      => 'נעקסט',
'allpagessubmit'    => 'גיי',
'allpagesprefix'    => 'בלעטער וואס זייער נאמען הייבט זיך אן מיט…:',
'allpagesbadtitle'  => 'דער אײַנגעגעבענער נאָמען איז אומגילטיק: לײדיק, אַנטהאַלט אינטערװיקי. עס איז מעגליך אז ער אנטהאלט אותיות וואס מען קען נישט ניצן אין קעפלעך.',
'allpages-bad-ns'   => '{{SITENAME}} האט נישט קיין נאָמענטייל "$1".',

# Special:Categories
'categories'                    => 'קאַטעגאָריעס',
'categoriespagetext'            => 'די פֿאלגענדע {{PLURAL:$1| קאַטעגאָריע אַנטהאַלט|קאַטעגאָריעס אַנטהאַלטן}} בלעטער אדער מעדיע.
[[Special:UnusedCategories|אומבאַניצטע קאַטעגאריעס]] זענען נישט געוויזן דא.
זעט אויך [[Special:WantedCategories|געזוכטע קאַטעגאריעס]].',
'categoriesfrom'                => 'ווײַזן קאַטעגאריעס אָנהייבנדיג פֿון:',
'special-categories-sort-count' => 'סארטיר לויטן צאל בלעטער',
'special-categories-sort-abc'   => 'סארטירן אַלפֿאַבעטיש',

# Special:DeletedContributions
'deletedcontributions'             => 'אויסגעמעקטע באַניצער בײַשטײַערונגען',
'deletedcontributions-title'       => 'אויסגעמעקטע באַניצער בײַשטײַערונגען',
'sp-deletedcontributions-contribs' => 'בײַשטײַערונגען',

# Special:LinkSearch
'linksearch'      => 'דרויסנדע לינקען זוך',
'linksearch-pat'  => 'זוך מוסטער:',
'linksearch-ns'   => 'נאמענטייל:',
'linksearch-ok'   => 'זוכן',
'linksearch-line' => '$1 פֿאַרבונדן פֿון $2',

# Special:ListUsers
'listusersfrom'      => 'ווײַזן באניצער אנהייבנדיג פון:',
'listusers-submit'   => 'ווײַז',
'listusers-noresult' => 'קיין באניצער נישט געטראפֿן.',
'listusers-blocked'  => '(בלאקירט)',

# Special:ActiveUsers
'activeusers'            => 'ליסטע פֿון אַקטיווע באַניצער',
'activeusers-intro'      => 'דאָס איז א ליסטע פֿון באַניצער וואָס זענען געווען אַקטיוו אינערהאָלב  $1 {{PLURAL:$1|דעם לעצטן טאָג|די לעצטע $1 טעג}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|באַאַרבעטונג|באַאַרבעטונגען}} אין  {{PLURAL:$3|דעם לעצטן טאָג|די לעצטע $3 טעג}}',
'activeusers-from'       => 'ווײַזן באַניצער אָנהייבנדיג פון:',
'activeusers-hidebots'   => 'באַהאַלטן באטן',
'activeusers-hidesysops' => 'באַהאַלטן סיסאפן',
'activeusers-noresult'   => 'קיין באניצער נישט געטראפֿן.',

# Special:Log/newusers
'newuserlogpage'     => 'נייע באַניצערס לאָג-בוך',
'newuserlogpagetext' => 'דאס איז א לאג פון באַניצערס אײַנשרײַבונגען.',

# Special:ListGroupRights
'listgrouprights'                      => 'באַניצער גרופע רעכטן',
'listgrouprights-summary'              => "פֿאלגנד איז א רשימה פֿון באַניצער גרופעס דעפֿינירט אויף דער דאָזיקער וויקי, מיט זײַערע אַסאציאירטע צוטריט רעכטן.
ס'קען זײַן  [[{{MediaWiki:Listgrouprights-helppage}}|מער אינפֿארמאַציע]] וועגן איינציקע רעכטן.",
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">נאָכגעגעבן רעכט</span> 
 * <span class="listgrouprights-revoked">אָפגערופֿן רעכט</span>',
'listgrouprights-group'                => 'גרופע',
'listgrouprights-rights'               => 'רעכטן',
'listgrouprights-helppage'             => 'Help: גרופע רעכטן',
'listgrouprights-members'              => '(רשימה פֿון מיטגלידער)',
'listgrouprights-addgroup'             => 'קען צולייגן {{PLURAL:$2|גרופע|גרופעס}}: $1',
'listgrouprights-removegroup'          => 'קען אראפנעמען {{PLURAL:$2|גרופע|גרופעס}}: $1',
'listgrouprights-addgroup-all'         => 'רשות צוצולייגן אלע גרופעס',
'listgrouprights-removegroup-all'      => 'רשות אוועקצונעמען אלע גרופעס',
'listgrouprights-addgroup-self'        => 'צולייגן {{PLURAL:$2|גרופע|גרופעס}} צו אייגענער קאנטע: $1',
'listgrouprights-removegroup-self'     => 'א§ראָפנעמען {{PLURAL:$2|גרופּע |גרופּעס}} פון אייגענער קאנטע: $1',
'listgrouprights-addgroup-self-all'    => 'צולייגן אַלע גרופעס צו אייגענער קאנטע',
'listgrouprights-removegroup-self-all' => 'אראָפנעמען אַלע גרופעס פֿון אייגענער קאנטע',

# E-mail user
'mailnologin'          => 'נישטא קיין אדרעס צו שיקן',
'mailnologintext'      => 'איר ברויכט זײַן [[Special:UserLogin|אַרײַנלאגירט]] און האָבן א גילטיגן ע־פאסט אַדרעס אין אײַער [[Special:Preferences|פרעפֿערענצן]] צו שיקן ע־פאסט צו אַנדערע באַניצער.',
'emailuser'            => 'שיקן ע-פאסט צו דעם באַניצער',
'emailpage'            => 'שיקן ע-פאסט צו באַניצער',
'emailpagetext'        => 'איר קענט ניצן די פֿארעם אונטן צו שיקן א בליצבריוו צו {{GENDER:$1|דעם דאזיגן באַניצער|דער דאזיגער באַניצערין}}.
דער ע-פאסט אדרעס וואס איר האט אריינגעלייגט אין [[Special:Preferences| אייערע באַניצער פרעפערנעצן]] וועט זיך ווײַזן כאילו דאס איז געקומען פון דארטן, בכדי צו דערמעגלעכן א תשובה.',
'usermailererror'      => 'בליצבריוו האט צוריקגעשיקט א טעות:',
'defemailsubject'      => 'ע-פאסט פון באַניצער "$1" {{SITENAME}}',
'usermaildisabled'     => 'באַניצער ע־פאסט אומאַקטיוויזירט',
'usermaildisabledtext' => 'איר קענט נישט שיקן ע־פאסט צו אנדערע באַניצערס אויף דער דאָזיקער וויקי',
'noemailtitle'         => 'נישטא קיין אי-מעיל אדרעס',
'noemailtext'          => 'דער באַניצער האט נישט באשטימט קיין גילטיקן ע-פאסט אדרעס.',
'nowikiemailtitle'     => 'קיין ע-פאסט נישט דערלויבט',
'nowikiemailtext'      => 'דער באַניצער האט געקליבן נישט באַקומען ע־פאסט פֿון אַנדערע באַניצער.',
'emailnotarget'        => 'נישט־פֿאראן אדער אומגילטיקער באַניצער־נאָמען פאר באַקומער.',
'emailtarget'          => 'אַרײַנגעבן באַניצער־נאָמען פון באַקומער',
'emailusername'        => 'באַניצער נאָמען:',
'emailusernamesubmit'  => 'אײַנגעבן',
'email-legend'         => 'שיקן ע-פאסט צו אַן אַנדער {{SITENAME}} באַניצער',
'emailfrom'            => 'פֿון:',
'emailto'              => 'צו:',
'emailsubject'         => 'טעמע:',
'emailmessage'         => 'מעלדונג:',
'emailsend'            => 'שיקן',
'emailccme'            => 'שיק מיר דורך ע־פאסט א קאפיע פֿון מיין מעלדונג.',
'emailccsubject'       => 'קאפיע פון אײַער מעלדונג צו $1: $2',
'emailsent'            => 'ע-פאסט געשיקט',
'emailsenttext'        => 'אײַער אי-בריוו איז געשיקט געווארן.',
'emailuserfooter'      => 'דער בליצבריוו איז געשיקט געווארן דורך$1 צו $2 מיט דער  "שיקן בליצבריוו"  פֿונקציע בײַ {{SITENAME}}.',

# User Messenger
'usermessage-summary'  => 'איבערלאזן סיסטעם אָנזאָג',
'usermessage-editor'   => 'סיסטעם שליח',
'usermessage-template' => 'MediaWiki:באניצער־מעלדונג',

# Watchlist
'watchlist'            => 'מיין אויפפַּאסונג ליסטע',
'mywatchlist'          => 'אויפפַּאסונג ליסטע',
'watchlistfor2'        => 'פֿאַר $1 $2',
'nowatchlist'          => 'איר האט נישט קיין שום בלעטער אין אייער אויפפַּאסונג ליסטע.',
'watchlistanontext'    => 'ביטע $1 כדי צו זען אדער ענדערן בלעטער אין אייער אַכטגעבן ליסטע.',
'watchnologin'         => 'איר זענט נישט אַרײַנלאגירט',
'watchnologintext'     => 'איר דארפֿט זיין [[Special:UserLogin|אריינגלאגירט]] צו מאדיפֿיצירן אייער אויפפַּאסונג־ליסטע.',
'addwatch'             => 'צולייגן צו דער אויפֿפאַסונג ליסטע',
'addedwatchtext'       => "דער בלאט \"[[:\$1]]\" איז צוגעלײגט געוואָרן צו אײַער [[Special:Watchlist|אויפֿפאַסונג ליסטע]].

ענדערונגען צו דעם בלאַט און צו זײַן פארבינדענעם רעדן בלאַט וועלן זײַן אויסגערעכענט דא.
און דער בלאט וועט זיין '''דיק''' אין דער [[Special:RecentChanges|ליסטע פון לעצטע ענדערונגען]] צו גרינגער מאכן דאס אויפֿפאַסן.",
'removewatch'          => 'אַראָפּנעמען פון דער אויפֿפאַסונג ליסטע',
'removedwatchtext'     => 'דער בלאַט "[[:$1]]" איז אָפּגעראַמט געוואָרן פון [[Special:Watchlist|אייער אױפֿפּאַסונג ליסטע]].',
'watch'                => 'אױפֿפּאַסן',
'watchthispage'        => 'טוט אױפֿפּאַסן דעם בלאט',
'unwatch'              => 'אויפֿהערן אויפֿפּאַסן',
'unwatchthispage'      => 'ענדיגן אויפֿפאַסן',
'notanarticle'         => 'דאס איז נישט קיין אינהאלט בלאט',
'notvisiblerev'        => 'די באארבעטונג איז געווארן אויסגעמעקט',
'watchnochange'        => 'קיינע פֿון אײַערע אויפֿגעפאַסטע בלעטער האבן זיך געענדערט אין דעם צײַט פעריאד געוויזן.',
'watchlist-details'    => '{{PLURAL:$1|איין בלאט|$1 בלעטער}} אין אייער אויפֿפאסן ליסטע (נישט רעכענען  רעדן בלעטער).',
'wlheader-enotif'      => '* ע-פאסט מעלדונג ערמעגליכט.',
'wlheader-showupdated' => "* בלעטער געענדערט זײַט אײַער לעצטן וויזיט זען געוויזן '''דיק'''",
'watchmethod-recent'   => 'קאנטראלירן לעצטע ענדערונגען פֿאַר אויפֿגעפאַסטע בלעטער',
'watchmethod-list'     => 'קאנטראלירן בלעטער אין אַכטונג־ליסטע פֿאַר לעצטע ענדערונגען',
'watchlistcontains'    => 'אייער אויפֿפאסונג ליסטע אנטהאלט {{PLURAL:$1|איין בלאט|$1 בלעטער}}.',
'iteminvalidname'      => "פּראָבלעם מיט '$1', אומגילטיקער נאָמען ...",
'wlnote'               => "אונטן {{PLURAL:$1|איז די לעצטע ענדערונג|זענען די לעצטע '''$1''' ענדערונגען}} אין {{PLURAL:$2|דער לעצטער שעה|די לעצטע '''$2''' שעה'ן}} ביז $3, $4.",
'wlshowlast'           => "(ווײַזן די לעצטע $1 שעה'ן | $2 טעג | $3)",
'watchlist-options'    => 'אויפֿפאַסן ליסטע ברירות',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'       => 'אויפפאסענדונג…',
'unwatching'     => 'נעמט אראפ פון אויפפאסונג ליסטע…',
'watcherrortext' => 'א גרײַז האט פאסירט ביים ענדערן אײַערע אויפֿפאסן ליסטע אײַנשטעלונגען פֿאר "$1".',

'enotif_mailer'                => 'נאטיפאקאציע שיקער {{SITENAME}}',
'enotif_reset'                 => 'באַצייכענען אלע בלעטער שוין געזען',
'enotif_newpagetext'           => 'דאס איז א נייער בלאט.',
'enotif_impersonal_salutation' => '{{SITENAME}} באַניצער',
'changed'                      => 'געטוישט',
'created'                      => 'געשאַפֿן',
'enotif_subject'               => 'דער בלאט $PAGETITLE אין {{SITENAME}} $CHANGEDORCREATED דורך $PAGEEDITOR',
'enotif_lastvisited'           => 'זעט $1 פֿאַר אלע ענדערונגען זינט אײַער לעצטן וויזיט.',
'enotif_lastdiff'              => 'זעט $1 פאר דער ענדערונג.',
'enotif_anon_editor'           => 'אַנאנימער באַניצער $1',
'enotif_body'                  => 'טײַערער $WATCHINGUSERNAME,

דער {{SITENAME}} בלאט $PAGETITLE איז געווארן $CHANGEDORCREATED אום $PAGEEDITDATE דורך $PAGEEDITOR, זעט $PAGETITLE_URL פאר דער איצטיגער ווערסיע.

$NEWPAGE

ענדערערס קורץ ווארט: $PAGESUMMARY $PAGEMINOREDIT

פארבינדט זיך צום שרייבער:
ע-פאסט: $PAGEEDITOR_EMAIL
וויקי: $PAGEEDITOR_WIKI

עס וועט מער נישט זיין קיין מעלדונגען אין פאל פון נאך ענדערונגען נאר אויב איר וועט באזוכן דעם בלאט.
איר קענט אויך צוריקשטעלן די מעלדונגען פאנען פון אלע אייערע אויפֿגעפאסטע בלעטער אין אייער אויפפאסונג ליסטע.
             
אייער פֿריינטליכע  {{SITENAME}} מעלדונגען סיסטעם

--
צו ענדערן אייער אויפֿפאסונג ליסטע, באזוכט
{{canonicalurl:{{#special:EditWatchlist}}}}

כדי אויסמעקן דעם בלאט פון אײַער אויפֿפאַסונג ליסטע, באַזוכט
$UNWATCHURL

פאר מער הילף:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'מעק אויס בלאט',
'confirm'                => 'באַשטעטיגן',
'excontent'              => 'אינהאלט געווען: "$1"',
'excontentauthor'        => "אינהאלט געווען: '$1' (און דער איינציגסטער באארבעטער איז געווען '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => 'אינהאַלט בעפֿאַרן אויסליידיגן איז געווען: "$1"',
'exblank'                => 'בלאט איז געווען ליידיג',
'delete-confirm'         => 'אויסמעקן $1',
'delete-legend'          => 'אויסמעקן',
'historywarning'         => 'אכטונג – איר גייט אויסמעקן א בלאט וואָס האט א היסטאריע מיט $1 {{PLURAL:$1|ווערסיע|ווערסיעס}}:',
'confirmdeletetext'      => 'איר גייט איצט אויסמעקן א בלאט צוזאַמען מיט זײַן גאנצע היסטאריע.

ביטע באשטעטיגט אז דאס איז טאקע אייער כוונה, אז איר פארשטייט פולערהייט די קאנסקווענסן פון דעם אַקט, און אז דאס איז אין איינקלאנג מיט [[{{MediaWiki:Policy-url}}|דער פאליסי]].',
'actioncomplete'         => 'די אַקציע אָט זיך דורכגעפֿירט',
'actionfailed'           => 'אקציע דורכגעפאלן',
'deletedtext'            => '"$1" אויסגעמעקט.
זעט $2 פֿאַר א רשימה פֿון לעצטיגע אויסמעקונגען.',
'dellogpage'             => 'אויסמעקונג לאג',
'dellogpagetext'         => 'ווייטער איז א ליסטע פון די מערסט לעצטיגע אויסמעקונגען.',
'deletionlog'            => 'אויסמעקונג לאג',
'reverted'               => 'צוריקגעשטעלט צו פֿריערדיקער באַאַרבעטונג',
'deletecomment'          => 'אורזאַך:',
'deleteotherreason'      => 'אנדער/נאך אן אורזאך:',
'deletereasonotherlist'  => 'אנדער אורזאך',
'deletereason-dropdown'  => '* געוויינטלעכע אויסמעקן אורזאכן
** פֿארלאנג פֿון שרייבער
** קאפירעכט ברעכונג
** וואנדאליזם
** נישט יידיש',
'delete-edit-reasonlist' => 'רעדאַקטירן די אויסמעקן סיבות',
'delete-toobig'          => 'דער בלאַט האט א גרויסע רעדאקטירונג היסטאריע, מער ווי $1 {{PLURAL:$1|רעוויזיע|רעוויזיעס}}. אויסמעקן אזעלכע בלעטער איז באַגרענעצט געווארן בכדי צו פֿאַרמײַדן א צופֿעליגע פֿאַרשטערונג פֿון  {{SITENAME}}.',
'delete-warning-toobig'  => 'דער בלאַט האט א גרויסע רעדאקטירונג היסטאריע, מער ווי $1 {{PLURAL:$1|רעוויזיע|רעוויזיעס}}. אויסמעקן אים קען פֿאַרשטערן דאַטנבאַזע אפעראַציעס פֿון {{SITENAME}}; זײַט פֿארזיכטיג איידער איר מעקט אויס.',

# Rollback
'rollback'          => 'צוריקדרייען רעדאַקטירונגען',
'rollback_short'    => 'צוריקדרייען',
'rollbacklink'      => 'צוריקדרייען',
'rollbackfailed'    => 'צוריקדרייען דורכגעפֿאַלן',
'cantrollback'      => 'מען קען נישט צוריקדרייען די ענדערונג – דער לעצטער בײַשטייערער איז דער איינציגסטער שרײַבער אין דעם בלאַט.',
'alreadyrolled'     => 'מען קען נישט צוריקדרייען די לעצטע ענדערונג פון בלאט [[:$1]] פֿון
[[User:$2|$2]] ([[User talk:$2|רעדן]]{{int:pipe-separator}} [[Special:Contributions/$2|{{int:contribslink}}]]);
אן אנדערער האט שוין געענדערט אדער צוריקגעדרייט דעם בלאט.

די לעצטע ענדערונג צום בלאַט איז געווען פון [[User:$3|$3]] ([[User talk:$3|רעדן]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "קורץ ווארט איז געווען: \"'''\$1'''\".",
'revertpage'        => 'רעדאַקטירונגען פֿון  [[Special:Contributions/$2|$2]] צוריקגענומען ([[User talk:$2|רעדן]])  צו דער לעצטער ווערסיע פֿון [[User:$1|$1]]',
'revertpage-nouser' => 'צוריקגעשטעלט רעדאַקטירונגען פֿון (באַניצער־נאָמען אַראָפגענומען) צו לעצטער רעוויזיע פֿון [[User:$1|$1]]',
'rollback-success'  => 'צוריקגעדרייט רעדאַקטירונגען פֿון $1 צו דער לעצטע ווערסיע פֿון $2',

# Edit tokens
'sessionfailure-title' => 'זיצונג דורכפֿאַל',
'sessionfailure'       => "ווײַזט אויס אז ס'איז דא א פראבלעם מיט אייער ארײַנלאגירן; די פעולה איז געווארן אנולירט צו פֿאַרהיטן קעגן פֿאַרשטעלן אייער סעסיע. זייט אזוי גוט און גייט צוריק צום פֿריערדיקן בלאט, און פרובירט נאכאַמאָל.",

# Protect
'protectlogpage'              => 'באשיצונג לאָג-בוך',
'protectedarticle'            => 'געשיצט [[$1]]',
'modifiedarticleprotection'   => 'געענדערט באשיצונג שטאפל פון [[$1]]',
'unprotectedarticle'          => 'אראפגענומען שוץ פון "[[$1]] "',
'movedarticleprotection'      => 'באוועגט די שיץ באשטימונגען פֿון "[[$2]]" אויף "[[$1]]"',
'protect-title'               => 'ענדערן שיץ ניווא פֿאַר "$1"',
'protect-title-notallowed'    => 'באקוקן שיץ־ניווא פון "$1"',
'prot_1movedto2'              => '[[$1]] אריבערגעפירט צו [[$2]]',
'protect-badnamespace-title'  => 'אומשיצבארער נאמענטייל',
'protect-badnamespace-text'   => 'בלעטער אין דעם נאמענטייל קען מען נישט שיצן.',
'protect-legend'              => 'באַשטעטיגן שיץ',
'protectcomment'              => 'אורזאַך:',
'protectexpiry'               => 'גייט אויס:',
'protect_expiry_invalid'      => 'אויסגיין צײַט אומגילטיג.',
'protect_expiry_old'          => 'שוין דערנאך דער אויסגיין צײַט.',
'protect-unchain-permissions' => 'אויפֿשליסן נאך שיץ אפציעס',
'protect-text'                => "איר מעגט זען און ענדערן דעם שוץ ניווא דא פֿארן בלאט '''$1'''.",
'protect-locked-blocked'      => "איר קען נישט ענדערן דעם שוץ ניווא בעת ווען איר זענט בלאקירט.
פֿאלגנד זענען די לויפֿיגע שטעלונגען פֿארן בלאט '''$1''':",
'protect-locked-access'       => "אייער קאנטע האט נישט קיין ערלויבניש צו ענדערן בלאט שיצונג ניוואען.
דא זענען די לויפֿיקע שטעלונגען פֿאַר דעם בלאַט '''$1''':",
'protect-cascadeon'           => 'דער בלאַט איז געשיצט אַצינד ווײַל ער איז אײַנגעשלאסן אין  {{PLURAL:$1|דעם פֿאלגנדן בלאַט, וואס האט|די פֿאלגנדע בלעטער, וואס האבן}} קאַסקאַדירטע שיצונג.

איר קענט ענדערן דעם שיצונג ניווא פונעם בלאַט, אבער דאס וועט נישט ווירקן אויף דער קאַסקאַדירטער שיצונג .',
'protect-default'             => 'אלע באניצער ערלויבט',
'protect-fallback'            => 'פֿאדערט "$1" ערלויבניש',
'protect-level-autoconfirmed' => 'בלאקירן נײַע און ניט אײַנגעשריבענע באַניצערס',
'protect-level-sysop'         => 'נאר סיסאפן',
'protect-summary-cascade'     => 'קאסקאדירן',
'protect-expiring'            => 'גייט אויס $1 (UTC)',
'protect-expiring-local'      => 'לאזט אויס $1',
'protect-expiry-indefinite'   => 'אומבאַשטימט',
'protect-cascade'             => 'שיץ בלעטער איינגעשלאסן אין דעם בלאט (קאסקאד שיץ)',
'protect-cantedit'            => 'איר קען נישט ענדערן די שוץ ניוואען פֿון דעם בלאט, ווײַל איר האט נישט קיין רשות  צו רעדאקטירן אים.',
'protect-othertime'           => 'אנדער צייט:',
'protect-othertime-op'        => 'אנדער צײַט',
'protect-existing-expiry'     => 'עקזיסטירנדע אויסלאז צײַט: $3, $2',
'protect-otherreason'         => 'אנדער/ווײַטערדיקע סיבה:',
'protect-otherreason-op'      => 'אַנדער סיבה',
'protect-dropdown'            => '* געוויינטלעכע סיבות פאר שיצן
** אסאך וואנדאליזם
** אסאך ספאם
** אומנוציקער רעדאקטירונג קריג
** שטארק געניצטער בלאט',
'protect-edit-reasonlist'     => 'רעדאַקטירן שיצן סיבות',
'protect-expiry-options'      => 'שעה:1 hour,טאג:1 day,וואך:1 week,וואכן:2 weeks,חודש:1 month,דריי חדשים:3 months,זעקס חדשים:6 months,יאר:1 year,אייביג:infinite',
'restriction-type'            => 'ערלויבניש:',
'restriction-level'           => 'באַשיצונג ניווא:',
'minimum-size'                => 'מינימום גרייס',
'maximum-size'                => 'מאקסימום גרייס:',
'pagesize'                    => '(בייטן)',

# Restrictions (nouns)
'restriction-edit'   => 'רעדאַקטירן',
'restriction-move'   => 'באוועגן',
'restriction-create' => 'שאַפֿן',
'restriction-upload' => 'אַרויפֿלאָדן',

# Restriction levels
'restriction-level-sysop'         => 'פֿֿולע באַשיצונג',
'restriction-level-autoconfirmed' => 'האַלב באַשיצט',
'restriction-level-all'           => 'וואָסער ניווא',

# Undelete
'undelete'                   => 'זען אויסגעמעקטע בלעטער',
'undeletepage'               => 'זען און צוריקשטעלן אויסגעמעקט בלעטער',
'undeletepagetitle'          => "'''פֿאלגנד באַשטייט פֿון אויסגעמעקטע ווערסיע פֿון [[:$1]]'''.",
'viewdeletedpage'            => 'זען אויסגעמעקטע בלעטער',
'undeletepagetext'           => 'The following {{PLURAL:$1|דער פֿאלגנדער בלאַט איז געווארן אויסגעמעקט אבער קען|די פֿאלגנדע $1  בלעט ער זענען געווארן אויסגעמעקט אבער קענען}} נאך  ווערן צוריקגעשטעלט פֿון אַרכיוו.
פֿון צײַט צו צײַט רייניגט מען אויס דעם אַרכיוו.',
'undelete-fieldset-title'    => 'צוריקשטעלן רעוויזיעס',
'undeleteextrahelp'          => "צוריקצושטעלן די גאנצע געשיכטע, פונעם בלאט, דרוקט נישט אויף קיין איין ווערסיע, און דרוקט '''''{{int:undeletebtn}}'''''.
צוריקצושטעלן נאר געוויסע ווערסיעס, קלויבט אויס  די רעוויזיעס וואס איר ווילט, און דרוקט אויף '''''{{int:undeletebtn}}'''''.",
'undeleterevisions'          => '{{PLURAL:$1|איין ווערסיע|$1 ווערסיעס}} אַרכיווירט',
'undeletehistory'            => 'אויב איר שטעלט צוריק דעם בלאַט, וועלן אַלע רעוויזיעס ווערן צוריקגעשטעלט אין דער היסטאריע.
אויב מען האט באַשאַפֿן א בלאַט מיטן זעלבן נאָמען זײַטן אויסמעקן, וועלן די צוריקגעשטעלטע רעוויזיעס זיך באַווײַזן אין דער פֿריערדיקער היסטאריע.',
'undeletehistorynoadmin'     => 'דער בלאַט איז געווארן אויסגעמעקט. 
 די סיבה פֿאַרן אויסמעקן ווערט געוויזן אין דער רעזומע אונטן, צוזאמען מיט פרטים פון די באַניצער וואס האבן רעדאַקטירט דעם בלאַט פֿאַרן אויסמעקן. 
 דער טעקסט פון די אויסגעמעקטע ווערסיעס איז דערגרײַכלעך בלויז צו סיסאפן.',
'undelete-revision'          => 'אויסגעמעקטע ווערסיע פֿון $1 (פֿון $4, אום $5) פֿון $3:',
'undeleterevision-missing'   => 'אומגילטיקע אדער פֿעלנדיקע ווערסיע.
אדער האט איר א פֿאַלשן לינק, אדער די ווערסיע איז געווארן צוריקגעשטעלט אדער אראָפגענומען פֿונעם אַרכיוו.',
'undelete-nodiff'            => 'קיין פֿריערדיגע ווערסיע נישט געטראפֿן.',
'undeletebtn'                => 'צוריקשטעלן',
'undeletelink'               => 'קוקן/צוריקשטעלן',
'undeleteviewlink'           => 'באַקוקן',
'undeletereset'              => 'צוריקשטעלן',
'undeleteinvert'             => 'איבערקערן דעם אויסקלויב',
'undeletecomment'            => 'אורזאַך:',
'undeletedrevisions'         => '{{PLURAL:$1|1 רעוויזיע|$1 רעוויזיעס}} צוריקגעשטעלט',
'undeletedrevisions-files'   => '{{PLURAL:$1|1 רעוויזיע|$1 רעוויזיעס}} און  {{PLURAL:$2|1 טעקע|$2 טעקעס}} צוריקגעשטעלט',
'undeletedfiles'             => '{{PLURAL:$1|1 טעקע|$1 טעקעס}} צוריקגעשטעלט',
'cannotundelete'             => 'צוריקשטעלונג איז דורכגעפאלן; עס איז מעגליך אז אן אנדערע האט דאס שוין צוריקגעשטעלט.',
'undeletedpage'              => "'''דער בלאט $1 איז געווארן צוריקגעשטעלט.'''

זעט דעם [[Special:Log/delete| אויסמעקן לאג]] פֿאר א ליסטע פון די לעצטע אויסגעמעקטע און צוריקגעשטעלטע בלעטער.",
'undelete-header'            => 'זעט [[Special:Log/delete|דעם אויסמעקונג זשורנאַל]] פֿאַר בלעטער וואָס זענען לעצטנס געווארן אויסגעמעקט recently deleted pages.',
'undelete-search-title'      => 'זוכן אויסגעמעקטע בלעטער',
'undelete-search-box'        => 'זוכן אויסגעמעקטע בלעטער',
'undelete-search-prefix'     => 'ווײַז בלעטער וואס הייבן אן מיט:',
'undelete-search-submit'     => 'זוכן',
'undelete-no-results'        => 'נישט געטראפן קיין צוגעפאסטע בלעטער אין אויסמעקונג ארכיוו.',
'undelete-error'             => 'גרייז ביים צוריקשטעלן בלאט',
'undelete-error-short'       => 'טעות ביים צוריקשטעלן טעקע: $1',
'undelete-error-long'        => 'גרײַזן געטראפֿן בײַם ווידערשטעלן די טעקע:

$1',
'undelete-show-file-confirm' => 'צי זענט איר זעכער איר ווילט באַקוקן די אויסגעמעקטע רעוויזיע פון דער טעקע "<nowiki>$1</nowiki>" פון $2 בשעה $3?',
'undelete-show-file-submit'  => 'יא',

# Namespace form on various pages
'namespace'                     => 'נאמענטייל:',
'invert'                        => 'ווײַז אַלע אויסער די',
'namespace_association'         => 'אָנגעבונדענער נאָמענטייל',
'tooltip-namespace_association' => 'צייכנט דאס קעסטל כדי איינשליסן דעם שמועס אדער סוביעקט נאמענטייל וואס געהערט צום אויסגעקליבענעם נאמענטייל',
'blanknamespace'                => '(הויפט)',

# Contributions
'contributions'       => "באניצער'ס בײַשטײַערונגען",
'contributions-title' => 'בײַשטײַערונגען פֿון באַניצער $1',
'mycontris'           => 'בײַשטײַערונגען',
'contribsub2'         => 'וועגן $1 ($2)',
'nocontribs'          => 'נישט געטראפן קיין ענדערונגען צוזאמעגעפאסט מיט די קריטעריעס.',
'uctop'               => '(לעצטע)',
'month'               => 'ביז חודש:',
'year'                => 'ביז יאר:',

'sp-contributions-newbies'             => 'ווײַזן בײַשטײַערונגען נאר פֿון נײַע באַניצערס',
'sp-contributions-newbies-sub'         => 'פאר נייע קאנטעס',
'sp-contributions-newbies-title'       => 'בײַשטײַערונגען פֿון נײַע באַניצערס',
'sp-contributions-blocklog'            => 'בלאקירן לאג',
'sp-contributions-deleted'             => 'אויסגעמעקטע באַניצער בײַשטײַערונגען',
'sp-contributions-uploads'             => 'אַרויפֿלאָדונגען',
'sp-contributions-logs'                => 'לאגביכער',
'sp-contributions-talk'                => 'שמועס',
'sp-contributions-userrights'          => 'באַניצער רעכטן פֿאַרוואַלטונג',
'sp-contributions-blocked-notice'      => 'דער באַניצער איז אַצינד בלאקירט. פֿאלגנד איז די לעצטע אַקציע אינעם פֿאַרשפאַרונג לאגבוך:',
'sp-contributions-blocked-notice-anon' => 'דער IP אַדרעס איז דערווייַל פֿאַרשפאַרט.
די לעצטע בלאָקירן לאג אַקציע איז צוגעשטעלט אונטן:',
'sp-contributions-search'              => 'זוכן בײַשטײַערונגען',
'sp-contributions-username'            => 'באניצער נאמען אדער IP אדרעס:',
'sp-contributions-toponly'             => 'בלויז ווײַזן רעדאַקטירונגען וואָס זענען די לעצטיקע רעוויזיעס',
'sp-contributions-submit'              => 'זוכן',

# What links here
'whatlinkshere'            => 'װאָס פֿאַרבינדט אַהער',
'whatlinkshere-title'      => 'בלעטער וואס פֿארבינדן צו $1',
'whatlinkshere-page'       => 'בלאַט:',
'linkshere'                => "די פאלגנדע בלעטער פארבינדן צום בלאט '''[[:$1]]''':",
'nolinkshere'              => "קיין שום בלאט פארבינדט נישט צו '''[[:$1]]'''.",
'nolinkshere-ns'           => "קיין בלעטער פֿאַרבינדן נישט צו '''[[:$1]]''' אינעם אויסגעקליבענעם נאמענטייל.",
'isredirect'               => 'ווײַטערפירן בלאט',
'istemplate'               => 'אײַנשליסן',
'isimage'                  => '!טעקע לינק',
'whatlinkshere-prev'       => '{{PLURAL:$1|פֿריערדיגער|$1 פֿריערדיגע}}',
'whatlinkshere-next'       => '{{PLURAL:$1|קומענדיגער|$1 קומענדיגע}}',
'whatlinkshere-links'      => '→ פֿארבינדונגען',
'whatlinkshere-hideredirs' => '$1 ווײַטערפֿירונגען',
'whatlinkshere-hidetrans'  => '$1 אַריבערשליסונגען',
'whatlinkshere-hidelinks'  => '$1 פֿאַרבינדונגען',
'whatlinkshere-hideimages' => '$1 טעקע פֿאַרבינדונגען',
'whatlinkshere-filters'    => 'פֿילטערס',

# Block/unblock
'autoblockid'                     => 'אויטאמאטיש בלאק #$1',
'block'                           => 'בלאקירן באַניצער',
'unblock'                         => 'אויפֿבלאקירן באניצער',
'blockip'                         => 'בלאקירן באַניצער',
'blockip-title'                   => 'בלאקירן באַניצער',
'blockip-legend'                  => 'בלאקירן באַניצער',
'blockiptext'                     => "באניצט די פארעם דא אונטן כדי צו בלאקירן שרײַבן רעכטן פֿון איינגעשריבענע באניצער אדער סתם ספעציפישע איי פי אדרעסן.

אזאלכע בלאקירונגען מוזן דורכגעפירט ווערן בלויז צו פֿאַרמײַדן וואַנדאַליזם, און לויט די [[{{MediaWiki:Policy-url}}|פארשריפטן און פאליסיס]].

ביטע שרײַבט ארויס קלאָר די ספעציפֿישע סיבה (למשל, ציטירן וועלכע בלעטער מ'האט וואַנדאַליזירט).",
'ipadressorusername'              => 'IP אדרעס אדער באַניצער נאמען:',
'ipbexpiry'                       => 'אויסגיין:',
'ipbreason'                       => 'אורזאַך:',
'ipbreasonotherlist'              => 'אנדער סיבה',
'ipbreason-dropdown'              => '* פֿארשפרייטע בלאקירן סיבות
** ארײַנלייגן פֿאלשע אינפֿארמאציע
** אויסמעקן אינהאַלט פֿון בלעטער
** פֿארפֿלייצן לינקען צו דרויסנדיקע ערטער
** ארײַנלייגן שטותים/טאָטעריש אין בלעטער
** סטראשעט און שטערט
** קרומבאניצן מערערע קאנטעס
** באַניצער נאָמען פראבלעמאַטיש',
'ipbcreateaccount'                => 'פֿאַרמײַדן שאַפֿן קאנטעס',
'ipbemailban'                     => 'פֿארמײַדן באַניצער פון שיקן ע־פאסט',
'ipbenableautoblock'              => 'אויטאמאַטיש בלאקירן דעם לעצטן IP אַדרעס פֿ
פֿון דעם באַניצער, און אַבי וועלכן IP אַדרעס ער פרובירט צו ניצן',
'ipbsubmit'                       => 'בלאקירן דעם באַניצער',
'ipbother'                        => 'אַנדער צײַט',
'ipboptions'                      => '2 שעהן:2 hours,
1 טאָג:1 day,
3 טעג:3 days,
1 װאָך:1 week,
2 װאָכן:2 weeks,
1 מאָנאַט:1 month,
3 מאָנאַטן:3 months,
6 מאָנאַטן:6 months,
1 יאָר:1 year,
אויף אייביק:infinite',
'ipbotheroption'                  => 'אַנדער',
'ipbotherreason'                  => 'אנדער/נאך א סיבה:',
'ipbhidename'                     => 'באַהאַלטן באַניצער נאָמען פֿון רעדאַקטירונגען און רשימות',
'ipbwatchuser'                    => 'אויפֿפאַסן דעם באַניצערס באַניצער און רעדן בלעטער',
'ipb-confirm'                     => 'באַשטעטיקן בלאָק',
'badipaddress'                    => 'נישט קיין גוטער IP אַדרעס.',
'blockipsuccesssub'               => 'בלאק איז דורכגפירט מיט דערפֿאלג',
'blockipsuccesstext'              => 'באַניצער [[Special:Contributions/$1|$1]] <br />איז פֿאַרשפאַרט.
זעט די [[Special:BlockList|ליסטע פון בלאקירטע באַניצער]] כדי צו זען די בלאקירונגען.',
'ipb-blockingself'                => 'איר האַלט בײַ בלאקירן זיך אַליין! איר ווילט דאָס טאַקע טון?',
'ipb-confirmhideuser'             => 'איר האלט ביי בלאקירן א באניצער וואס האט "באהאלטן באניצער" סטאטוס. דאס וועט פארשטעקן דעם באניצערס נאמען אין אלע ליסטעס און לאגביכער. צי זענט איר זיכער אז איר ווילט דאס טאקע טון?',
'ipb-edit-dropdown'               => 'רעדאקטיר בלאקירונג סיבות',
'ipb-unblock-addr'                => 'אויפֿבלאקירן $1',
'ipb-unblock'                     => 'אויפֿבלאקירן א באַניצער נאמען אדער IP אדרעס',
'ipb-blocklist'                   => 'זעט עקזיסטירנדע בלאקירונגען',
'ipb-blocklist-contribs'          => 'בײַשטײַערונגען פֿון $1',
'unblockip'                       => 'אויפֿבלאקירן באניצער',
'unblockiptext'                   => 'מיט דעם פארמולאר קענט איר צוריקשטעלן שרייבן ערלויבניש צו אן IP אדרעס אדער באניצער נאמען וואס איז געווען בלאקירט.',
'ipusubmit'                       => 'אוועקנעמען דעם בלאק',
'unblocked'                       => '[[User:$1|$1]] איז געווארן באַפֿרייט פון זײַן בלאק',
'unblocked-range'                 => '$1 איז באפרייט פון בלאקירן',
'unblocked-id'                    => 'בלאק $1 איז געווארן אַראָפגענומען.',
'blocklist'                       => 'בלאקירטע באַניצער',
'ipblocklist'                     => 'בלאקירטע באַניצער',
'ipblocklist-legend'              => 'געפֿינען א בלאקירטן באניצער',
'blocklist-userblocks'            => 'באהאלטן קאנטע בלאקן',
'blocklist-tempblocks'            => 'באהאלטן צײַטווײַליקע בלאקן',
'blocklist-addressblocks'         => 'באהאלטן ענצעלע IP בלאקן',
'blocklist-timestamp'             => 'צײַטשטעמפל',
'blocklist-target'                => 'ציל',
'blocklist-expiry'                => 'גייט אויס:',
'blocklist-by'                    => 'בלאקירן סיסאפ',
'blocklist-params'                => 'בלאקירן פאַראַמעטערס',
'blocklist-reason'                => 'אורזאַך',
'ipblocklist-submit'              => 'זוכן',
'ipblocklist-localblock'          => 'לאקאַלע בלאקירונג',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|אַנדער בלאקירונג|אַנדערע בלאקירונגען}}',
'infiniteblock'                   => 'אויף אייביק',
'expiringblock'                   => 'גייט אויס אום $1 $2',
'anononlyblock'                   => 'בלויז אַנאנימע',
'noautoblockblock'                => 'אויטאבלאק אומאַקטיווירט',
'createaccountblock'              => 'קאנטע באשאפֿן בלאקירט',
'emailblock'                      => 'בליצפאסט בלאקירט',
'blocklist-nousertalk'            => 'קען נישט רעדאַקטירן דעם אייגענעם רעדן בלאַט',
'ipblocklist-empty'               => 'בלאקירן ליסטע איז  ליידיג.',
'ipblocklist-no-results'          => 'דער געזוכטער IP אַדרעס אָדער באַניצער נאמען איז ניט פֿאַרשפאַרט.',
'blocklink'                       => 'ארויסטרייבן',
'unblocklink'                     => 'באַפֿרײַען',
'change-blocklink'                => 'ענדערן בלאק',
'contribslink'                    => 'באַניצערס בײַשטײַערונגען',
'emaillink'                       => 'שיקן ע־פאסט',
'autoblocker'                     => 'דו ביסט געבלאקט אטאמאטיק ווייל דו טיילסט זיך די IP אדרעס מיט [[User:$1|$1]]. דער סיבה וואס איז אנגעבען געווארן  [[User:$1|$1]] איז: "$2".',
'blocklogpage'                    => 'בלאקירן לאג',
'blocklog-showlog'                => '{{GENDER:$1|דער באַניצער|די באַניצערין}} איז שוין געווארן פֿאַרשפאַרט אַמאָל.
דער בלאקירונג לאג איז צוגעשטעלט אונטן:',
'blocklog-showsuppresslog'        => '{{GENDER:$1|דער באַניצער|די באַניצערין}} איז שוין געווארן פֿאַרשפאַרט און פֿאַרבארגט אַמאָל.
דער פֿאַרשטיקונג לאג איז צוגעשטעלט אונטן:',
'blocklogentry'                   => 'בלאקירט "[[$1]]" אויף אַ תקופה פון $2 $3',
'reblock-logentry'                => 'גענדערט די בלאקירונג דעפיניציעס פון [[$1]] מיטן צייט אפלויף פון $2 $3',
'blocklogtext'                    => 'דאס איז א לאג בוך פון אלע בלאקירונגען און באפרייונגען פֿון באניצער. 
איי פי אדרעסן וואס זענען בלאקירט אויטאמאטיש ווערן נישט אויסגערעכענט דא.
זעט די איצטיקע [[Special:BlockList|ליסטע פון בלאקירטע באניצער]].',
'unblocklogentry'                 => 'אומבלאקירט $1',
'block-log-flags-anononly'        => 'בלויז אַנאנימע באַניצער',
'block-log-flags-nocreate'        => 'קאָנטע שאַפֿן איז פֿאַרשפּאַרט',
'block-log-flags-noautoblock'     => 'אויטא-בלאקיר איז בטל',
'block-log-flags-noemail'         => 'שיקן ע-פאסט  בלאקירט',
'block-log-flags-nousertalk'      => 'ענדערן אייגן שמועס בלאט בלאקירט',
'block-log-flags-angry-autoblock' => 'פארבעסערטע אויטא-בלאקירונג דערמעגליכט',
'block-log-flags-hiddenname'      => 'באַניצער נאָמען באַהאַלטן',
'range_block_disabled'            => 'די סיסאפ מעגליכקייט צו בלאקירן רענזש בלאקס איז אומ-ערמעגליכט.',
'ipb_expiry_invalid'              => 'אויסגיין צײַט אומגילטיג.',
'ipb_expiry_temp'                 => 'בלאקירן מיט פאַרבאָרגן באַניצער נאָמען מוז זייַן אויף אייביק.',
'ipb_hide_invalid'                => 'נישט געקענט פֿאַרשטיקן די קאנטע; זי האט מעגלעך צופיל רעדאַקטירונגען.',
'ipb_already_blocked'             => '"$1" איז שוין בלאקירט',
'ipb-needreblock'                 => '$1 איז שוין בלאקירט. צי ווילט איר טוישן די באַצייכנונגען?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|אנדער בלאקירונג|אנדערע בלאקירונגען}}',
'unblock-hideuser'                => 'איר קענט נישט אומבלאקירן דעם באניצער, ווײַל זײַן באַניצער נאָמען איז פֿאַרבארגן.',
'ipb_cant_unblock'                => "גרײַז: בלאק ID $1 נישט געפֿונען. 
ס'מעגליך שוין געווארן באַפֿרייט.",
'ipb_blocked_as_range'            => "טעות: דער IP אַדרעס $1 איז נישט בלאקירט גראָד און מען קען אים נישט אויפֿבלאקירן.
דאך איז ער בלאקירט אַלס א טייל פֿון דעם אָפשטאַנד $2, וואָס מ'קען יא אויפֿבלאקירן.",
'ip_range_invalid'                => 'אומריכטיגער IP גרייך.',
'ip_range_toolarge'               => 'אָפשטאַנדן גרעסער ווי /$1 קען מען נישט בלאקירן.',
'blockme'                         => 'בלאקירט מיך',
'proxyblocker'                    => 'פראקסי בלאקער',
'proxyblocker-disabled'           => 'די  פֿונקציע איז אומאַקטיווירט.',
'proxyblockreason'                => 'אייער איי.פי. אדרעס איז געווארן געבלאקט צוליב דעם ווייל דאס איז א אפענער פראקסי. ביטע פארבינדט זיך מיט אייער אינטערנעט סערוויס פראוויידער אדער טעקס סאפארט צו אינפארמירן זיי איבער דעם ערענסטן זיכערהייט פראבלעם.',
'proxyblocksuccess'               => 'געטאן.',
'cant-block-while-blocked'        => 'איר קען נישט בלאקירן קיין אנדערע באניצער ווען איר זענט אליין בלאקירט.',
'ipbblocked'                      => 'איר קען נישט בלאקירן אדער אויפבלאקירן אנדערע באניצער, ווייל איר זענט אליין בלאקירט.',
'ipbnounblockself'                => 'איר זענט נישט ערלויבט זיך אליין אויסבלאקירן',

# Developer tools
'lockdb'              => 'פֿאַרשליסן די דאַטנבאַזע',
'unlockdb'            => 'אויפֿשליסן די דאַטנבאַזע',
'lockconfirm'         => 'יא, איך וויל פֿאַרשליסן די דאַטנבאַזע.',
'unlockconfirm'       => 'יא, איך וויל באמת אויפשליסן די דאטעבאזע.',
'lockbtn'             => 'פֿאַרשליסן דאַטנבאַזע',
'unlockbtn'           => 'אויפֿשליסן די דאַטנבאַזע',
'locknoconfirm'       => 'איר האט נישט אָנגעצייכנט דאָס באַשטעטיקונג קעסטל.',
'lockdbsuccesssub'    => 'דאַטנבאַזע פֿאַרשפאַרט מיט הצלחה',
'unlockdbsuccesssub'  => 'דאטנבאזע שלאס אראפגענומען',
'lockdbsuccesstext'   => 'די דאטנבאזע איז געשלאסן .<br />
געדענקט [[Special:UnlockDB|אוועקנעמען דעם שלאס ]] ווען אייער אויפהאלטונד איז געענדיקט.',
'unlockdbsuccesstext' => 'די דאַטנבאַזע איז געווארן אויפֿגעשלאסן',
'databasenotlocked'   => 'די דאַטנבאַזע איז נישט פֿאַרשלאסן.',
'lockedbyandtime'     => '(דורך $1 אום $2 בײַ $3)',

# Move page
'move-page'                    => 'באַוועגן $1',
'move-page-legend'             => 'באַוועגן בלאַט',
'movepagetext'                 => "זיך באניצן מיט דעם פֿארעם וועט פֿארענדערן דעם נאמען פֿון דעם בלאט, און וועט אריבערפֿירן זיין געשיכטע צום נייעם נאמען.

דאס אלטע קעפל וועט ווערן א ווייטערפֿירן בלאט צום נייעם נאמען.

איר קענט דערהיינטיגן ווייטערפֿירונגען צום אלטן נאמען אויטאמאטיש.

טאמער נישט, טוט פֿארזיכערן אז עס איז נישטא קיין [[Special:DoubleRedirects|געטאפלטע]] אדער [[Special:BrokenRedirects|צעבראכענע]] ווייטערפֿירונגען.

איר זענט פֿאראנטווארטלעך זיכער מאכן אז אלע פֿארבינדונגען ווערן געריכטעט צו דער געהעריגער ריכטונג.

אכטונג: דער בלאט וועט נישט ווערן אריבערגעפֿירט אויב עס איז שוין דא א בלאט אונטער דעם נייעם נאמען, אחוץ ווען ער איז ליידיג. אדער ער איז א ווייטערפֿירונג בלאט, און ער האט נישט קיין געשיכטע פון ענדערונגען. פשט דערפֿון, אז איר קענט איבערקערן א ווייטערפֿירונג וואס איר האט אט געמאכט בטעות, און איר קענט נישט אריבערשרייבן אן עקסיסטירנדן בלאט.

'''ווארענונג:''' אזא ענדערונג קען זיין דראסטיש און נישט געווינטשען פאר א פאפולערן בלאט; ביטע פֿארזיכערט אז איר פֿארשטייט די ווייטגרייכנדע קאנסקווענסן צו דער אקציע בעפֿאר איר פֿירט דאס אויס.",
'movepagetext-noredirectfixer' => "זיך באניצן מיט דעם פֿארעם אונטן וועט פֿארענדערן דעם נאמען פֿון דעם בלאט, און וועט אריבערפֿירן זיין געשיכטע צום נייעם נאמען.

דאס אלטע קעפל וועט ווערן א ווייטערפֿירן בלאט צום נײַעם נאמען.

טוט פֿארזיכערן אז עס בלײַבן נישט קיין [[Special:DoubleRedirects|געטאפלטע]] אדער [[Special:BrokenRedirects|צעבראכענע]] ווייטערפֿירונגען.

איר זענט פֿאראנטווארטלעך זיכער מאכן אז אלע פֿארבינדונגען ווערן געריכטעט צו דער געהעריגער ריכטונג.

אַכטונג: דער בלאַט וועט '''נישט''' ווערן אַריבערגעפֿירט אויב עס איז שוין דאָ א בלאט אונטער דעם נײַעם נאמען, אחוץ ווען ער איז ליידיג. אדער ער איז א ווײַטערפֿירונג בלאט, און ער האט נישט קיין געשיכטע פון ענדערונגען. 
פשט דערפֿון, אז איר קענט איבערקערן א ווייטערפֿירונג וואס איר האט אט געמאכט בטעות, און איר קענט נישט אריבערשרײַבן אַן עקסיסטירנדן בלאט.

'''ווארענונג:''' אזא ענדערונג קען זיין דראַסטיש און נישט געוואונטשן פֿאַר א פאפולערן בלאַט; ביטע פֿאַזיכערט אז איר פֿאַרשטייט די ווײַטגרייכנדע קאנסעקווענסן צו דער אַקציע בעפֿאַר איר גייט ווײַטער.",
'movepagetalktext'             => "דער רעדן בלאט וועט ווערן באַוועגט אויטאמאֵטיש מיט אים, '''אחוץ:'''
* ס'איז שוין דא א נישט-ליידיגער בלאט מיטן נייעם נאמען, אדער.
* איר נעמט  אראפ דעם צייכן פונעם קעסטל אונטן.

אין די פֿעלער, וועט איר דארפֿן באַוועגן אדער צונויפֿגיסן דעם בלאט האַנטלעך, ווען איר ווילט.",
'movearticle'                  => 'באוועג בלאט:',
'moveuserpage-warning'         => "'''ווארענונג:''' איר האלט ביי באוועגן א באניצער בלאט. ביטע באמערקט אז נאר דער בלאט ווערט באוועגט אבער דער באניצער נאמען ווערט ''נישט'' געענדערט.",
'movenologin'                  => 'איר זענט נישט אַריינלאָגירט',
'movenologintext'              => 'איר דארפֿט זיך אײַנשרײַבן און זײַן  [[Special:UserLogin|אַרײַנלאגירט]] צו באַוועגן א בלאַט.',
'movenotallowed'               => 'איר זענט נישט דערלויבט צו באוועגן בלעטער.',
'movenotallowedfile'           => 'איר האט נישט קיין רשות צו באוועגן טעקעס.',
'cant-move-user-page'          => 'איר זענט נישט דערלויבט צו באַוועגן באַניצער בלעטער (אחוץ אונטערבלעטער).',
'cant-move-to-user-page'       => 'איר זענט נישט דערלויבט צו באַוועגן א בלאַט צו א באַניצער בלאַט (אַחוץ צו א באַניצער אונטערבלאַט).',
'newtitle'                     => 'צו נייעם קעפל:',
'move-watch'                   => 'אויפֿפאַסן אויף דעם בלאַט',
'movepagebtn'                  => 'באוועגן',
'pagemovedsub'                 => 'באַוועגט מיט הצלחה',
'movepage-moved'               => 'דער בלאט "$1" איז אריבערגעפֿירט געווארן צו "$2".',
'movepage-moved-redirect'      => 'ווײַטערפֿירונג  געשאַפֿן.',
'movepage-moved-noredirect'    => 'שאַפֿן  א ווײַטערפֿירונג פֿאַרשטיקט.',
'articleexists'                => 'א בלאט מיט דעם נאָמען עקזיסטירט שוין, אדער דעם נאָמען וואס איר האט אויסדערוויילט איז נישט גילטיק.
ביטע קלייבט אויס אן אנדער נאָמען.',
'cantmove-titleprotected'      => 'איר קענט נישט באַוועגן א בלאַט צו דעם נאמען, ווייל דאס נייע קעפל איז געשיצט פֿון ווערן געשאַפֿן',
'talkexists'                   => "דער בלאט אליין איז באוועגט מיט דערפֿאלג, אבער דער רעדן בלאט האט מען נישט באוועגט ווײַל ס'איז שוין דא א בלאט מיט דעם זעלבן נאמען. זײַט אזוי גוט פֿאראייניגט זיי האנטלעך.",
'movedto'                      => 'באַוועגט צו',
'movetalk'                     => 'באוועגט אסאסיצירטע רעדן בלאט',
'move-subpages'                => 'באוועגן אונטערבלעטער (ביז $1)',
'move-talk-subpages'           => 'באַוועגן אונטערבלעטער פֿון רעדן בלאַט (ביז $1)',
'movepage-page-exists'         => "דער בלאַט $1 עקזיסטירט שוין און מ'קען אים נישט אויטאָמאַטיש איבערשרײַבן.",
'movepage-page-moved'          => 'דער בלאַט $1 איז געוורן באַוועגט צו $2.',
'movepage-page-unmoved'        => 'מען קען נישט באוועגן בלאט $1 צו $2.',
'movepage-max-pages'           => 'דער מאקסימום פון $1 {{PLURAL:$1|בלאט|בלעטער}} האט מען שוין באוועגט און נאך בלעטער וועט מען נישט באוועגן אויטאמאטיש.',
'movelogpage'                  => 'באוועגן לאג',
'movelogpagetext'              => 'פֿאלגנד איז א ליסטע פֿון  בלעטער באוועגט.',
'movesubpage'                  => '{{PLURAL:$1|אונטערבלאַט|אונטערבלעטער}}',
'movesubpagetext'              => 'דער בלאַט האט $1 {{PLURAL:$1|אונטערבלאַט|אונטערבלעטער}} געוויזן אונטן.',
'movenosubpage'                => 'דער דאָזיגער בלאַט האט נישט קיין אונטערבלעטער.',
'movereason'                   => 'אורזאַך:',
'revertmove'                   => 'צוריקדרייען',
'delete_and_move'              => 'אויסמעקן און באוועגן',
'delete_and_move_text'         => '== אויסמעקן פארלאנגט ==
דער ציל בלאַט "[[:$1]]" עקזיסטירט שוין.
צי ווילט איר אים אויסמעקן כדי צו ערמעגליכן די באוועגונג?',
'delete_and_move_confirm'      => 'יא, מעק אויס דעם בלאט',
'delete_and_move_reason'       => 'אויסגעמעקט כדי צו קענען באוועגן פֿון "[[$1]]"',
'selfmove'                     => 'מקור און ציל קעפלעך זענען גלײַך; מען קען נישט באוועגן א בלאט צו זיך זעלבסט.',
'immobile-source-namespace'    => 'נישט מעגלעך צו באוועגן בלעטער אין נאמענטייל "$1"',
'immobile-target-namespace'    => 'מען קען נישט באַוועגן בלעטער צום נאמענטייל "$1"',
'immobile-target-namespace-iw' => 'אינטערוויקי לינק איז נישט קיין גילטיקער ציל פאר א בלאט באוועגונג.',
'immobile-source-page'         => 'דער דאזיגער בלאט קען נישט ווערן באוועגט.',
'immobile-target-page'         => 'קען נישט באַוועגן צו דעם ציל טיטל.',
'imagenocrossnamespace'        => 'קען נישט באַוועגן טעקע צו נישט-טעקע נאָמענטייל',
'nonfile-cannot-move-to-file'  => 'קען נישט באַוועגן וואָס איז נישט קיין טעקע צום טעקע נאָמענטייל',
'imagetypemismatch'            => 'ני נײַע טעקע־פֿאַרברייטערונג איז נישט צוגעפאַסט צו איר טיפ.',
'imageinvalidfilename'         => 'דער ציל טעקע נאָמען איז נישט גילטיק.',
'fix-double-redirects'         => 'דערהײַנטיקן ווײַטערפֿירונגען צום ארגינעלן טיטל',
'move-leave-redirect'          => 'איבערלאזן א ווײַטערפֿירונג',
'move-over-sharedrepo'         => '== טעקע עקזיסטירט ==
[[:$1]] עקזיסטירט אויף א געטיילטן רעפאזיטאריום. ווען מען באוועגט א טעקע צו דעם טיטל וועט דאס איבערשרייבן די געטיילטע טעקע.',
'file-exists-sharedrepo'       => "ס'איז שוין פאראן א טעקע מיטן געקליבענעם נאמען אויף א געמיינזאם רעפאזיטאריום.
זייט אזוי גוט קלייבט אן אנדער נאמען.",

# Export
'export'            => 'עקספארטירן בלעטער',
'exporttext'        => 'איר קענט עקספארטירן דעם טעקסט און די ענדערונג היסטאריע פון א געוויסן בלאט אדער עטלעכע בלעטער ארומגענומען מיט אביסל XML. דאס קען ווערן אימפארטירט אין אן אנדער וויקי ניצנדיג מעדיע-וויקי דורך
דעם [[Special:Import|אימפארט בלאט]].

צו עקספארטירן בלעטער, לייגט אריין די טיטלען אין דעם טעקסט קעסטל פון אונטן, איין טיטל פאר א שורה, און קלויבט אויס צי איר דארפט די לויפיגע ווערסיע, ווי אויך די אלטע ווערסיעס, מיט די בלאט היסטאריע שורות, אדער בלויז די איצטיגע ווערסיע מיט דער קורץ ווארט אינפארמאציע פון דער לעצטער ענדערונג.

אין דעם לעצטן פאל קענט איר אויך ניצן א לינק, למשל [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] פארן בלאט [[{{MediaWiki:Mainpage}}]].',
'exportall'         => 'עקספארטירן אלע בלעטער',
'exportcuronly'     => 'רעכן אריין בלויז די איצטיגע רע-ווערסיע, נישט די פולער היסטאריע',
'exportnohistory'   => "----
'''באמערקונג:''' עקספארטירן די פולער היסטאריע פון בלעטער דורך די פארעם איז געווארן אומ-ערמעגליכט צוליב פערפארמענס סיבות.",
'exportlistauthors' => 'כולל זיין א פולשטענדיקע ליסטע פון ביישטייערער פאר יעדן בלאט',
'export-submit'     => 'עקספארט',
'export-addcattext' => 'צולייגן בלעטער פֿון דער קאַטעגאריע:',
'export-addcat'     => 'צולייגן',
'export-addnstext'  => 'צולייגן בלעטער פֿון נאמענטייל:',
'export-addns'      => 'צולייגן',
'export-download'   => 'אויפֿהיטן אלס טעקע',
'export-templates'  => 'אײַנשליסן מוסטערן',
'export-pagelinks'  => 'אײַנשליסן פֿאַרבונדענע בלעטער ביז א טיף פֿון:',

# Namespace 8 related
'allmessages'                   => 'סיסטעם מעלדונגען',
'allmessagesname'               => 'נאָמען',
'allmessagesdefault'            => 'גרונטלעכער טעקסט',
'allmessagescurrent'            => 'איצטיגער טעקסט',
'allmessagestext'               => 'אט דאס איז א ליסטע פון אלע סיסטעם מעלדונגען וואס זענען פאראן אין דעם  {{ns:mediawiki}}  נאמענטייל, וואס באדינען דעם אויפהאלט פונעם סייט.

סיסאפן קענען ענדערן די מעלדונגען דורך דרוקן אויפן נאמען פון דער מעלדונג.

ביטע באזוכט [//www.mediawiki.org/wiki/Localisation מעדיעוויקי לאקאליזאציע] און [//translatewiki.net בעטאוויקי] אויב איר ווילט ביישטייערן צו דער גענערישער מעדיעוויקי לאקאליזאציע.',
'allmessagesnotsupportedDB'     => 'מען קען זיך נישט באניצן מיט דעם בלאט וויבאלד די $wgUseDatabseMessages איז געווארן בטל.',
'allmessages-filter-legend'     => 'פילטער',
'allmessages-filter'            => 'פֿילטערן לויטן סטאטוס פון מעלדונג:',
'allmessages-filter-unmodified' => 'נישט מאדיפֿיצירט',
'allmessages-filter-all'        => 'אלע',
'allmessages-filter-modified'   => 'מאדיפֿיצירט',
'allmessages-prefix'            => 'פֿילטערן לויט פרעפֿיקס',
'allmessages-language'          => 'שפראַך:',
'allmessages-filter-submit'     => 'צייגן',

# Thumbnails
'thumbnail-more'           => 'פארגרעסער',
'filemissing'              => 'טעקע פֿעלט',
'thumbnail_error'          => 'גרײַז בײַם באשאפֿן דאס קליינבילד: $1',
'djvu_page_error'          => 'DjVu בלאט ארויס פֿון גרייך',
'djvu_no_xml'              => "מ'קען נישט באקומען דעם XML פֿאַר דער DjVu טעקע",
'thumbnail-temp-create'    => "מ'קען נישט שאפן א פראוויזארישע פארקלענערטע טעקע",
'thumbnail-dest-create'    => "מ'קען נישט שפייכלערן פארקלענערונג צום ציל",
'thumbnail_invalid_params' => 'אומגילטיגע קליינבילד פאראמעטערס',
'thumbnail_dest_directory' => "מ'קען נישט שאפֿן דעם ציל קארטאטעק",
'thumbnail_image-type'     => 'בילד טיפ נישט געשטיצט',
'thumbnail_gd-library'     => 'אומפולשטענדיקע קאנפיגוראציע פאר דער GD-ביבליאטעק: פונקציע $1 פעלט',
'thumbnail_image-missing'  => 'טעקע פֿעלט אייגנטלעך: $1',

# Special:Import
'import'                     => 'אימפארטירן בלעטער',
'importinterwiki'            => 'אריבערוויקי אימפארט',
'import-interwiki-text'      => 'קלויבט אויס א וויקי און אן ארטיקל קעפל צו אימפארטירן.
די דאטעס און די נעמען פון די רעדאקטארן וועט ווערן געהיטן.
אלע צווישנוויקי אימפארט אקציעס ווערן פארשריבן אינעם   [[Special:Log/import|אימפארט לאג]].',
'import-interwiki-source'    => 'מקור וויקי/בלאט',
'import-interwiki-history'   => 'קאפירן אלע היסטאריע ווערסיעס פאר דעם בלאט',
'import-interwiki-templates' => 'איינשילסן אלע מוסטערן',
'import-interwiki-submit'    => 'אימפארט',
'import-interwiki-namespace' => 'ציל נאמענטייל:',
'import-upload-filename'     => 'טעקע נאמען:',
'import-comment'             => 'הערה:',
'importtext'                 => 'ביטע עקספארטירט די טעקע פון דער מקור וויקי ניצנדיג דאס [[Special:Export|עקספארט הילפמיטל]], שפייכלט אײַן אויף אײַער קאמפיוטער און לאדט אַרויף דא.',
'importstart'                => 'אימפארטירט בלעטער…',
'import-revision-count'      => '{{PLURAL:$1|איין ווערסיע|$1 ווערסיעס}}',
'importnopages'              => 'נישטא קיין בלעטער צו אימפארטירן.',
'imported-log-entries'       => '$1 {{PLURAL:$1|לאג אַקציע|לאג אַקציעס}} אימפארטירט.',
'importfailed'               => 'אימפארט דורכגעפֿאלן: <nowiki>$1</nowiki>',
'importunknownsource'        => 'אומבאקאנטער  אימפארט טיפ',
'importcantopen'             => 'נישט געקענט עפֿענען אימפארט טעקע',
'importbadinterwiki'         => 'שלעכטע אינטערוויקי לינק',
'importnotext'               => 'ליידיג אדער נישט קיין טעקסט',
'importsuccess'              => '!אימפארט אדורכגעפירט מיט דערפאלג!',
'importhistoryconflict'      => 'פאראן א קאנפליקט מיט דער עקזיסטירנדע היסטאריע רעוויזיע (מעגליך אז דער בלאט איז געווארן אימפארטירט שוין פריער)',
'importnosources'            => 'קיין מקורות פֿאַר צווישן־וויקי אימפארט, און דירעקט היסטאריע אַרויפֿלאָדן איז נישט דערמעגלעכט אַצינד.',
'importnofile'               => 'קיין אימפארט טעקע איז נישט ארויפֿגעלאדן.',
'importuploaderrorsize'      => 'אַרויפֿלאָדן פֿון אימפארט טעקע דורכגעפֿאלן.
די טעקע איז גרעסער פֿון דער דערלויבטער אַרויפֿלאָדן גרייס.',
'importuploaderrorpartial'   => 'אַרויפֿלאָדן פֿון אימפארט טעקע דורכגעפֿאלן.
די טעקע איז נאר טיילווייז אַרויפֿגעלאָדן.',
'importuploaderrortemp'      => 'אַרויפֿלאָדן פֿון אימפארט טעקע דורכגעפֿאלן.
אַ פראוויזארישער טעקע־האלטער פֿעלט.',
'import-parse-failure'       => 'פֿעלער בײַם אימפארטירן XML',
'import-noarticle'           => 'נישטא קיין בלאט צו אימפארטירן!',
'import-nonewrevisions'      => 'אַלע רעוויזיעס שוין אימפארטירט.',
'xml-error-string'           => '$1 בײַ שורה $2, זייל $3 (בייט $4): $5',
'import-upload'              => 'אַרויפֿלאָדן XML דאַטן',
'import-token-mismatch'      => 'אָנווער פון סעסיע דאַטן. 
 ביטע פרובירט נאכאמאל.',
'import-invalid-interwiki'   => 'נישט מעגלעך צו אימפארטירן פון ספעציפֿירטער וויקי.',
'import-error-edit'          => 'דעם בלאט "$1" קען מען נישט אימפארטירן ווייל איר האט נישט די רעכט אים צו רעדאקטירן.',
'import-error-create'        => 'דעם  בלאט "$1" קען מען נישט אימפארטירן ווייל איר האט נישט די רעכט צו שאפן אים.',
'import-error-interwiki'     => 'דעם בלאט "$1"  קען מען נישט אימפארטירן ווייל זיין נאמען איז רעזערווירט פאר דרויסנדיקער פארבינדונג (אינטערוויקי).',
'import-error-special'       => 'דעם בלאט "$1" קען מען נישט אימפארטירן ווייל ער געהערט צו א באזונדערן נאמענטייל וואס אנטהאלט נישט קיין בלעטער.',
'import-error-invalid'       => 'דעם בלאט "$1" קען מען נישט אימפארטירן ווייל זיין נאמען איז אומגילטיק.',

# Import log
'importlogpage'                    => 'אימפארט לאגבוך',
'importlogpagetext'                => 'אַדמיניסטראַטיווער אימפארט פון בלעטער מיט רעדאַגירן היסטאריע פֿון ​​אַנדערע וויקיס.',
'import-logentry-upload'           => 'האט אימפארטירט [[$1]] דורך טעקע אַרויפֿלאָדן',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|רעוויזיע|רעוויזיעס}}',
'import-logentry-interwiki'        => 'אריבערגעוויקיט $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|רעוויזיע|רעוויזיעס}} פֿון $2',

# JavaScriptTest
'javascripttest'                => 'JavaScript טעסט',
'javascripttest-disabled'       => 'די  פֿונקציע איז אומאַקטיווירט אין דער דאזיקער וויקי.',
'javascripttest-title'          => 'דורכפירנדיק $1 בדיקות',
'javascripttest-pagetext-skins' => 'קלויבט א באניצער־אייבערפלאך מיט וואס דורכצופירן די בדיקות:',
'javascripttest-qunit-intro'    => 'זעט [$1 דאקומענטאציע פאר טעסטן] בײַ mediawiki.org.',
'javascripttest-qunit-heading'  => 'מעדיעוויקי JavaScript QUnit קאנטראל־פראגראם',

# Tooltip help for the actions
'tooltip-pt-userpage'                 => 'אייער באניצער בלאט',
'tooltip-pt-anonuserpage'             => 'באַניצער בלאַט פון דעם IP אַדרעס',
'tooltip-pt-mytalk'                   => 'אייער שמועס בלאט',
'tooltip-pt-anontalk'                 => 'שמועס איבער באטייליגען פון די איי.פי.',
'tooltip-pt-preferences'              => 'אייערע פרעפערענצן',
'tooltip-pt-watchlist'                => 'ליסטע פון בלעטער וואס איר טוט אויפפאסן נאך ענדערונגן',
'tooltip-pt-mycontris'                => 'ליסטע פון אייערע ביישטייערונגען',
'tooltip-pt-login'                    => "עס איז רעקאָמענדירט זיך אײַנשרײַבן; ס'איז אבער נישט קיין פֿליכט",
'tooltip-pt-anonlogin'                => "עס איז רעקאָמענדירט זיך אײַנשרײַבן; ס'איז אָבער נישט קײַן פֿליכט",
'tooltip-pt-logout'                   => 'ארויסלאגירן',
'tooltip-ca-talk'                     => 'שמועס וועגן דעם אינהאַלט בלאַט',
'tooltip-ca-edit'                     => "איר קענט ענדערן דעם בלאט. ביטע באניצט דעם ''פֿאָרויסדיקער אויסקוק'' קנעפל בעפֿארן אפהיטן",
'tooltip-ca-addsection'               => 'אָנהייבן א נײַע אָפטיילונג',
'tooltip-ca-viewsource'               => 'דאס איז א פֿארשלאסענער בלאט, איר קענט נאר באַקוקן זיין מקור',
'tooltip-ca-history'                  => 'פריערדיגע ווערסיעס פון דעם בלאט.',
'tooltip-ca-protect'                  => 'באשיצט דעם בלאט',
'tooltip-ca-unprotect'                => 'ענדערן באַשיצונג פון דעם בלאַט',
'tooltip-ca-delete'                   => 'אויסמעקן דעם בלאט',
'tooltip-ca-undelete'                 => 'צוריק דרייען די ענדערונגען פון דעם בלאט פארן מעקן',
'tooltip-ca-move'                     => 'באַוועגן דעם בלאַט',
'tooltip-ca-watch'                    => 'לייגט צו דעם בלאט אויפצופאסן',
'tooltip-ca-unwatch'                  => 'נעמט אראפ דעם בלאט פון אויפפאסן',
'tooltip-search'                      => 'זוכט אינעם סייט',
'tooltip-search-go'                   => 'גייט צו א בלאט מיט אט דעם נאמען, אויב ער עקסיסטירט',
'tooltip-search-fulltext'             => 'זוכט דעם טעקסט אין די בלעטער',
'tooltip-p-logo'                      => 'הויפט זייט',
'tooltip-n-mainpage'                  => 'באַזוכט דעם הויפּט־זײַט',
'tooltip-n-mainpage-description'      => 'באַזוכן דעם הויפט בלאַט',
'tooltip-n-portal'                    => 'גייט אריין אין די געמיינדע צו שמועסן',
'tooltip-n-currentevents'             => 'מער אינפארמאציע איבער אקטועלע געשענישען',
'tooltip-n-recentchanges'             => 'ליסטע פון לעצטע ענדערונגען',
'tooltip-n-randompage'                => 'וועלט אויס א צופעליגער בלאט',
'tooltip-n-help'                      => 'הילף',
'tooltip-t-whatlinkshere'             => 'אלע בלעטער וואס פארבינדען צו דעם בלאט',
'tooltip-t-recentchangeslinked'       => 'אלע ענדערונגען פון בלעטער וואס זענען אהער פארבינדען',
'tooltip-feed-rss'                    => 'דערהײַנטיגט אויטאמאטיש פון אר.עס.עס. RSS',
'tooltip-feed-atom'                   => 'לייג צו אן אטאמאטישער אפדעיט דורך אטאם Atom',
'tooltip-t-contributions'             => 'אלע בײַשטײַערונגען פון דעם באניצער',
'tooltip-t-emailuser'                 => 'שיקן א בליצבריוו צו דעם בַאניצער',
'tooltip-t-upload'                    => 'ארויפלאדן טעקעס',
'tooltip-t-specialpages'              => 'אלע ספעציעלע בלעטער',
'tooltip-t-print'                     => 'דרוק ווערסיע פון דעם בלאט',
'tooltip-t-permalink'                 => 'פערמאנענטע פֿארבינדונג צו דער דאזיגער ווערסיע פֿונעם בלאט',
'tooltip-ca-nstab-main'               => 'זעט דעם אינהאַלט בלאַט',
'tooltip-ca-nstab-user'               => 'זעט דעם באניצער בלאט',
'tooltip-ca-nstab-media'              => 'קוקט אין דעם מעדיע בלאט',
'tooltip-ca-nstab-special'            => "דאס איז א ספעציעלער בלאט, מ'קען אים נישט ענדערן",
'tooltip-ca-nstab-project'            => 'באקוקט דעם פראיעקט בלאט',
'tooltip-ca-nstab-image'              => 'באקוקט דעם טעקע בלאט',
'tooltip-ca-nstab-mediawiki'          => 'באקוקט די סיסטעם מעלדונגען',
'tooltip-ca-nstab-template'           => 'זעט דעם מוסטער',
'tooltip-ca-nstab-help'               => 'זעט דעם הילף בלאַט',
'tooltip-ca-nstab-category'           => 'באקוקט דעם קאטעגאריע בלאט',
'tooltip-minoredit'                   => 'באצייכענען דאס אלס מינערדיגע ענדערונג',
'tooltip-save'                        => 'אויפֿהיטן אייערע ענדערונגען',
'tooltip-preview'                     => 'פֿארויסדיגע ווײַזונג, זײַט אזוי גוט באניצט די געלעגנהייט פֿארן אויפֿהיטן!',
'tooltip-diff'                        => 'ווײַזן אייערע ענדערונגען צום טעקסט',
'tooltip-compareselectedversions'     => 'פארגלײַכם די צוויי ווערסיעס פון דעם בלאט',
'tooltip-watch'                       => 'לייגט צו דעם בלאט צו אייער אויפֿפאסונג ליסטע',
'tooltip-watchlistedit-normal-submit' => 'אַראָפנעמען בלעטער',
'tooltip-watchlistedit-raw-submit'    => 'דערהיינטיגן אויפפאסונג ליסטע',
'tooltip-recreate'                    => 'ווידערשאַפֿן דעם בלאַט כאטש ער איז אַמאל אויסגעמעקט',
'tooltip-upload'                      => 'הייב אן אויפלאדן',
'tooltip-rollback'                    => '"צוריקדרייען" דרײט צוריק רעדאַקטירונג(ען) צו דעם בלאַט פֿונעם לעצטן בײַשטײַערער מיט אײן קװעטש',
'tooltip-undo'                        => 'עפֿנט דעם רעדאַגיר־פֿענסטער אין אַ פֿאָרױסדיקן אױסקוק כּדי צוריקדרײען די רעדאַקציע. עס איז מעגלעך צוצולײגן אַ סיבה דערװעגן אין דעם "קורץ װאָרט" קעסטל.',
'tooltip-preferences-save'            => 'היטן פרעפֿערענצן',
'tooltip-summary'                     => 'אײַנגעבן א קורצע רעזומע',

# Stylesheets
'common.css'      => '/* CSS געשריבן דא וועט אפילירן און באיינפלוסן אלע סקינס */',
'standard.css'    => '/* CSS געשטעלט דא ווירקט אויפן סטאנדארט סקין */',
'nostalgia.css'   => '/* CSS געשטעלט דא ווירקט נאר אויפן נאסטאלגיע סקין */',
'cologneblue.css' => '/* CSS געשטעלט דא ווירקט נאר אויפן קעלנישן־בלוי סקין */',
'monobook.css'    => '/* סטייל דא געלייגט וועט באאיינפלוסן דעם Monobook סקין */',
'myskin.css'      => '/* CSS געשטעלט דא ווירקט אויפן MySkin סקין */',
'chick.css'       => '/* CSS געשטעלט דא ווירקט אויפן טשיק סקין */',
'simple.css'      => '/* CSS געשטעלט דא ווירקט אויפן איינפאך סקין */',
'modern.css'      => '/* CSS געשטעלט דא ווירקט אויפן מאדערנעם סקין */',
'vector.css'      => '/* CSS געשטעלט דא ווירקט נאר אויפן וועקטאר סקין */',

# Scripts
'common.js' => '/* אלע סקריפטן פון JavaScript דא געשריבן וועט לויפן פאר אלע באנוצער ווען זיי וועלן לאדירן דעם בלאט */',

# Metadata
'notacceptable' => 'דער וויקי סערווער קען נישט צושטעלן דאַטן אין אַ פֿאָרמאַט וואָס אײַער קליענט קען לייענען.',

# Attribution
'anonymous'        => '{{PLURAL:$1|אַנאנימער באַניצער| אַנאנימע באַניצערס}} פֿון {{SITENAME}}',
'siteuser'         => 'באַניצער {{SITENAME}} $1',
'anonuser'         => '{{SITENAME}} אַנאנימער באַניצער $1',
'lastmodifiedatby' => 'די לעצטע ענדערונג פֿון דעם בלאַט איז געווען $2, $1 דורך $3.',
'othercontribs'    => 'באזירט אויף ארבעט פון $1.',
'others'           => 'אנדערע',
'siteusers'        => '{{PLURAL:$2|באַניצער| באַניצערס}} {{SITENAME}} $1',
'anonusers'        => '{{SITENAME}} {{PLURAL:$2| אַנאנימער באַניצער|אַנאנימע באַניצער}} $1',
'creditspage'      => 'בלאט קרעדיטס',
'nocredits'        => 'נישט פאראן קיין אינפארמאציע פאר דעם בלאט.',

# Spam protection
'spamprotectiontitle' => 'ספעם באשיצונג פילטער',
'spambot_username'    => 'מעדיעוויקי ספאם פוצן',
'spam_reverting'      => 'צוריקגעשטעלט צו דער לעצטער ווערסיע אן לינקען צו $1',

# Info page
'pageinfo-title'            => 'אינפֿאָרמאַציע פֿאַר "$1"',
'pageinfo-header-edits'     => '!רעדאַקטירן היסטאריע',
'pageinfo-header-watchlist' => 'אויפֿפאַסונג ליסטע',
'pageinfo-header-views'     => 'קוקן',
'pageinfo-subjectpage'      => 'בלאַט',
'pageinfo-talkpage'         => 'רעדן בלאַט',
'pageinfo-watchers'         => '!צאָל בלאט אויפֿפאַסער',
'pageinfo-edits'            => 'צאָל ענדערונגען',
'pageinfo-authors'          => 'סה"כ צאָל באַזונדערע שרײַבער',
'pageinfo-views'            => 'צאַל קוקן',
'pageinfo-viewsperedit'     => 'צאל קוקן צו א רעדאַקטירונג',

# Skin names
'skinname-standard'    => 'קלאסיש',
'skinname-nostalgia'   => 'נאסטאלגיע',
'skinname-cologneblue' => 'קעלניש בלוי',
'skinname-monobook'    => 'מאנאבוק',
'skinname-myskin'      => 'מיין סקין',
'skinname-chick'       => 'טשיק',
'skinname-simple'      => 'איינפֿאַך',
'skinname-modern'      => 'מאדערן',
'skinname-vector'      => 'וועקטאר',

# Patrolling
'markaspatrolleddiff'                 => 'באצייכענען אלס פאטראלירט',
'markaspatrolledtext'                 => 'באצייכענען בלאט אלס פאטראלירט',
'markedaspatrolled'                   => 'באצייכנט אלס פאטראלירט',
'markedaspatrolledtext'               => 'די אויסגעקליבענע ענדערונג פֿון [[:$1]] איז באַצייכנט געווארן אַלס פאַטארלירט.',
'rcpatroldisabled'                    => 'פאַטראלירן ענדערונגען איז  מבוטל געווארן',
'rcpatroldisabledtext'                => 'די לעצטע ענדערונגען פאַטראלירן אייגנקייט איז אצינד בטל.',
'markedaspatrollederror'              => 'נישט מעגלעך צו צייכענען אלס פאַטראלירט',
'markedaspatrollederrortext'          => 'איר דארפֿט ספעציפֿירן א ווערזיע צו באַצייכענען אלס פאַטראלירט.',
'markedaspatrollederror-noautopatrol' => 'איר טאר נישט באַצייכענען די אייגענע ענדערונגען אלס פאַטראלירט.',

# Patrol log
'patrol-log-page'      => 'פאטראלירן לאג-בוך',
'patrol-log-header'    => 'דאס איז א לאג-בוך פון פאַטראליטע רעוויזיעס.',
'log-show-hide-patrol' => '$1 פאַטראלירן לאג-בוך',

# Image deletion
'deletedrevision'                 => 'אויסגעמעקט אלטע ווערסיע $1.',
'filedeleteerror-short'           => 'גרייז ביים אויסמעקן טעקע: $1',
'filedeleteerror-long'            => 'גרײַזן געטראפֿן בײַם אויסמעקן די טעקע:

$1',
'filedelete-missing'              => 'קען נישט אויסמעקן טעקע "$1", ווייל זי עקזיסטירט נישט.',
'filedelete-old-unregistered'     => 'די ספעציפֿירטע טעקע ווערסיע "$1" געפֿינט זיך נישט אין דער דאַטנבאַזע.',
'filedelete-current-unregistered' => 'די טעקע "$1" איז נישט אין דער דאטנבאזע.',
'filedelete-archive-read-only'    => 'דער וועבסארווער קען נישט שרייבן צום ארכיוו פֿארצייכעניש "$1".',

# Browsing diffs
'previousdiff' => 'פריערדיקער אונטערשייד →',
'nextdiff'     => 'קומענדיקע ווערסיע ←',

# Media information
'mediawarning'           => "'''ווארענונג''': דער טעקע טיפ קען אנטהאלטן בייזוויליקן קאד.
דורכפירן דעם קאד קען שעדיקן אייער סיסטעם.",
'imagemaxsize'           => "מאקסימאלע בילד גרייס :<br />''(פאר טעקע באשרייבונג בלעטער)''",
'thumbsize'              => 'קליינבילד גרייס:',
'widthheight'            => '$1 × $2',
'widthheightpage'        => '$1 × $2, {{PLURAL:$3|איין בלאט|$3 בלעטער}}',
'file-info'              => 'טעקע גרייס: $1, MIME טיפ: $2',
'file-info-size'         => '$1 × $2 פיקסעל, טעקע גרייס: $3, טיפ MIME: $4',
'file-info-size-pages'   => '$1 × $2 פיקסעלן, טעקע גרייס: $3, MIME טיפ: $4,  $5 {{PLURAL:$5|בלאט|בלעטער}}',
'file-nohires'           => 'נישטא מיט א העכערער רעזאלוציע.',
'svg-long-desc'          => 'טעקע SVG, נאמינעל: $1 × $2 פיקסעלן, טעקע גרייס: $3',
'show-big-image'         => 'בילד מיט דער גרעסטער רעזאלוציע',
'show-big-image-preview' => 'גרייס פון דעם פארויסקוק: $1.',
'show-big-image-other'   => '{{PLURAL:$2|אנדער רעזאלוציע|אנדערע רעזאלוציעס}}: $1.',
'show-big-image-size'    => '$1 × $2 פיקצעלן',
'file-info-gif-looped'   => 'אין א פעטליע',
'file-info-gif-frames'   => '$1 {{PLURAL:$1| ראַם | ראָמען}}',
'file-info-png-looped'   => 'אין א פעטליע',
'file-info-png-repeat'   => 'געשפילט{{PLURAL:$1|איינמאָל|$1 מאָל}}',
'file-info-png-frames'   => '$1 {{PLURAL:$1|ראם|ראמען}}',

# Special:NewFiles
'newimages'             => 'גאַלעריע פון ​​נײַע טעקעס',
'imagelisttext'         => 'פֿאלגנד איז א ליסטע פון {{PLURAL:$1|איין טעקע|$1 טעקעס}}, סארטירט $2:',
'newimages-summary'     => 'דער באַזונדערער בלאַט ווײַזט די לעצטע ארויפֿגעלאָדענע טעקעס.',
'newimages-legend'      => 'פֿילטער',
'newimages-label'       => 'טעקע נאָמען (אדער אַ טײל דערפֿון):',
'showhidebots'          => '($1 ראָבאָטן)',
'noimages'              => 'נישטא קיין בילדער.',
'ilsubmit'              => 'זוכן',
'bydate'                => 'לויטן דאטום',
'sp-newimages-showfrom' => 'באַװײַזן נײַע טעקעס פון $2, $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1, $2 × $3',
'seconds-abbrev' => '$1ס',
'minutes-abbrev' => '$1מ',
'seconds'        => '{{PLURAL:$1|$1 סעקונדע|$1 סעקונדעס}}',
'minutes'        => '{{PLURAL:$1|$1 מינוט|$1 מינוט}}',
'hours'          => '{{PLURAL:$1|$1 שעה|$1 שעה}}',
'days'           => '{{PLURAL:$1|$1 טאג|$1 טעג}}',
'ago'            => 'פֿאַר $1',

# Bad image list
'bad_image_list' => 'דער פֿאָרמאַט איז װי פֿאָלגנדיק:

נאר חלקים פון אַ רשימה (שורות װעלכע הײבן זיך אָן מיט *) זײַנען אין באַטראַכט.

דער ערשטער פֿאַרבינדונג אין אַ שורה מוז זײַן אַ פֿאַרבינדונג צו אַ שלעכטער טעקע.

אַלע פֿאָלגנדיקע פֿאַרבינדונגען אין דער זעלבער שורה זײַנען גערעכנט װי אַן אױסנאַם, װי צום בײַשפּיל, בלעטער װעלכע די טעקע קען װערן געפֿאַלן אין קאָנטינואיטער.',

# Metadata
'metadata'          => 'מעטאדאטע',
'metadata-help'     => 'די טעקע אנטהאלט נאך אינפֿארמאציע, אפשר פֿונעם דיגיטאלן אפאראט אדער סקענער וואס האט באשאפֿן דאס בילד.

ווען די טעקע איז געענדערט געווארן פון איר ארגינעלן מצב, עטלעכע פרטים זענען מעגלעך נישט פאסיג צו דער היינטיקער טעקע.',
'metadata-expand'   => 'ווײַזן פֿארברייטערטע פרטים',
'metadata-collapse' => 'באהאלטן פֿארברייטערטע פרטים',
'metadata-fields'   => 'בילד מעטאַדאַטן פֿעלדער אויסגערעכנט אונטן וועט מען ארײַנשליסן אין דער בילד בלאַט אויסשטעלונג ווען די מעטאַדאַטן טאַבעלע איז פֿאַרקלענערט.
אנדערע וועלן נארמאַלווייזע ווערן באַהאַלטן.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth'                  => 'ברייט',
'exif-imagelength'                 => 'הייך',
'exif-bitspersample'               => 'ביטס פער באשטאנדטייל',
'exif-compression'                 => 'צאמקוועטשן סכעמע',
'exif-photometricinterpretation'   => ' פיקסעל צוזאמענשטעל',
'exif-orientation'                 => 'אריענטאַציע',
'exif-samplesperpixel'             => 'צאל קאמאפאנענטן',
'exif-planarconfiguration'         => 'דאטן איינארדנונג',
'exif-xresolution'                 => 'האריזאנטאלע רעזאלוציע',
'exif-yresolution'                 => 'ווערטיקאלע רעזאלוציע',
'exif-stripoffsets'                => 'בילדדאטן פלאציר',
'exif-rowsperstrip'                => 'צאל שורות אין א שטרייף',
'exif-stripbytecounts'             => 'בייטן אין א קאמפרימירטן שטרייף',
'exif-jpeginterchangeformatlength' => 'בייטן פון JPEG דאטן',
'exif-datetime'                    => 'טעקע ענדערונג דאטע און צײַט',
'exif-imagedescription'            => 'בילד טיטל',
'exif-make'                        => 'פֿאטא-אפאראט פֿאבריצירער',
'exif-model'                       => 'פֿאטא-אפאראט מאדעל',
'exif-software'                    => 'ווייכוואַרג באניצט',
'exif-artist'                      => 'מחבר',
'exif-copyright'                   => 'קאפירעכטן האלטער',
'exif-exifversion'                 => 'Exif ווערסיע',
'exif-flashpixversion'             => 'ווערסיע Flashpix געשטיצטע',
'exif-colorspace'                  => 'קאליר רוים',
'exif-componentsconfiguration'     => 'מיינונג פון יעדן באשטאנדטייל',
'exif-compressedbitsperpixel'      => 'בילד צוזאמקוועטשן מאוד',
'exif-pixelydimension'             => 'בילד ברייט',
'exif-pixelxdimension'             => 'בילד הייך',
'exif-usercomment'                 => 'באנוצער קאמענטורן',
'exif-relatedsoundfile'            => 'פֿאַרבונדענע אוידיאָ טעקע',
'exif-datetimeoriginal'            => 'דאטום און צייט פון דאַטן באשאפונג',
'exif-datetimedigitized'           => 'דאטום און צייט פון דיזשיטייזונג',
'exif-subsectime'                  => 'צײַטפונקט (אונטערסעקונדן)',
'exif-subsectimeoriginal'          => 'פֿאַרפֿאַסן צײַטפונקט (אונטערסעקונדן)',
'exif-subsectimedigitized'         => 'דיגיטאַליזירן צײַטפונקט (אונטערסעקונדן)',
'exif-exposuretime'                => 'באַלײַכטן צייט',
'exif-exposuretime-format'         => '$1 סעק ($2)',
'exif-fnumber'                     => 'נומער F',
'exif-exposureprogram'             => 'אויפדעקונג פראגראם',
'exif-shutterspeedvalue'           => 'APEX לעדל גיך',
'exif-aperturevalue'               => 'APEX עפֿענונג',
'exif-brightnessvalue'             => 'APEX העלקייט',
'exif-exposurebiasvalue'           => 'באַלײַכטן נייגונג',
'exif-maxaperturevalue'            => 'מאקסימום גרייס פון עפענונג',
'exif-subjectdistance'             => 'סוביעקט ווייט',
'exif-meteringmode'                => 'מעסטן מאָדע',
'exif-lightsource'                 => 'ליכט מקור',
'exif-flash'                       => 'בליץ',
'exif-focallength'                 => 'לענס פֿאקאַלע לענג',
'exif-focallength-format'          => '$1 מ"מ',
'exif-subjectarea'                 => 'סוביעקט געגנט',
'exif-flashenergy'                 => 'פלעש ענערגיע',
'exif-focalplanexresolution'       => 'פאקוס־שטח האריזאנטאל',
'exif-focalplaneyresolution'       => 'פאקוס־שטח ווערטיקאל',
'exif-focalplaneresolutionunit'    => 'פאקוס־שטח רעזאלוציע איינהייט',
'exif-subjectlocation'             => 'סוביעקט ארט',
'exif-exposureindex'               => 'באַלײַכטן אינדעקס',
'exif-sensingmethod'               => 'דערשפירן מעטאד',
'exif-filesource'                  => 'מקור פֿון דער טעקע',
'exif-scenetype'                   => 'סצענע טיפ',
'exif-customrendered'              => 'קאסטעם בילד פראצעסירונג',
'exif-exposuremode'                => 'באַלײַכטן מצב',
'exif-whitebalance'                => 'ווײַס באַלאַנס',
'exif-digitalzoomratio'            => 'דיגיטאלער זום פארהעלטעניש',
'exif-focallengthin35mmfilm'       => 'פאקאל לענג אין 35 מ"מ פילם',
'exif-scenecapturetype'            => 'סצענע אויפנעם טיפ',
'exif-gaincontrol'                 => 'סצענע קאנטראל',
'exif-contrast'                    => 'קאנטראסט',
'exif-saturation'                  => 'זעטיקונג',
'exif-sharpness'                   => 'שארף',
'exif-devicesettingdescription'    => 'אפאראט שטעלונגען אראפמאלונג',
'exif-imageuniqueid'               => 'בילד־ID',
'exif-gpslatituderef'              => 'צפון אדער דרום גארטל־ליניע',
'exif-gpslatitude'                 => 'גארטל־ליניע',
'exif-gpslongituderef'             => 'מזרח אדער מערב לענג',
'exif-gpslongitude'                => 'געאגראַפֿישע לענג',
'exif-gpsaltituderef'              => 'אלטיטוט רעפערענץ',
'exif-gpsaltitude'                 => 'הייך',
'exif-gpstimestamp'                => 'GPS צייט (אטאם־זייגער)',
'exif-gpssatellites'               => 'סאטעליטן געניצט פאר מעסטן',
'exif-gpsdop'                      => 'מאס פוקנטליכקייט',
'exif-gpsspeedref'                 => 'גיך איינהייט',
'exif-gpsspeed'                    => 'גיך פון GPS־אויפֿנעמער',
'exif-gpsimgdirectionref'          => 'רעפערענץ פאר ריכטונג פון בילד',
'exif-gpsimgdirection'             => 'ריכטונג פון בילד',
'exif-gpsdestlatituderef'          => 'רעפֿערענץ פֿאַר ברייט־ליניע פון ציל',
'exif-gpsdestlatitude'             => 'ברייט־ליניע פֿון ציל',
'exif-gpsdestlongituderef'         => 'רעפֿערענץ פֿאַר לענג־ליניע פֿון ציל',
'exif-gpsdestlongitude'            => 'לענג־ליניע פֿון ציל',
'exif-gpsdestbearingref'           => 'רעפֿערענץ פֿאַר ריכטונג פון ציל',
'exif-gpsdestbearing'              => 'ריכטונג פֿון ציל',
'exif-gpsdestdistanceref'          => 'רעפֿערענץ פֿאַר ווײַטקייט פֿון ציל',
'exif-gpsdestdistance'             => 'ווײַטקייט צום ציל',
'exif-gpsprocessingmethod'         => 'נאמען פון GPS פראצעסירן מעטאד',
'exif-gpsareainformation'          => 'נאמען פון GPS געגענט',
'exif-gpsdatestamp'                => 'GPS דאטע',
'exif-gpsdifferential'             => 'GPS דיפראנציאלע קאקרעקציע',
'exif-jpegfilecomment'             => 'JPEG טעקע הערה',
'exif-keywords'                    => 'שליסלווערטער',
'exif-worldregioncreated'          => "וועלטראיאן וואו מ'האט גענומען דאס בילד",
'exif-countrycreated'              => "לאנד וואו מ'האט געמאכט דאס בילד",
'exif-countrycodecreated'          => "קאד פארן לאנד וואו מ'האט געמאכט דאס בילד",
'exif-provinceorstatecreated'      => "פראווינץ אדער שטאַט וואו מ'האט גענומען דאס בילד",
'exif-citycreated'                 => "שטאָט וואו מ'האט געמאכט דאס בילד",
'exif-worldregiondest'             => 'וועלטראיאן געוויזן',
'exif-countrydest'                 => 'לאנד געוויזן',
'exif-countrycodedest'             => 'קאד פאר לאנד געוויזן',
'exif-provinceorstatedest'         => 'פראווינץ אדער שטאַט געוויזן',
'exif-citydest'                    => 'געוויזענע שטָאט',
'exif-objectname'                  => 'קורצער טיטל',
'exif-specialinstructions'         => 'באזונדערע אנווייזונגען',
'exif-headline'                    => 'קעפל',
'exif-credit'                      => 'קרעדיט/פארזארגער',
'exif-source'                      => 'מקור',
'exif-editstatus'                  => 'רעדאקציאנעלער סטאטוס פון בילד',
'exif-urgency'                     => 'דרינגלעכקייט',
'exif-locationdest'                => 'געוויזענע לאקאציע',
'exif-locationdestcode'            => 'קאד פֿון געוויזענער לאקאציע',
'exif-writer'                      => 'שרײַבער',
'exif-languagecode'                => 'שפראַך',
'exif-iimversion'                  => 'IIM ווערסיע',
'exif-iimcategory'                 => 'קאַטעגאָריע',
'exif-iimsupplementalcategory'     => 'אונטער־קאטעגאריעס',
'exif-datetimeexpires'             => 'נישט צו ניצן נאָך',
'exif-datetimereleased'            => 'באַפֿרייט אום',
'exif-originaltransmissionref'     => 'ארגינעלער טראנסמיסיע פלאצירונג קאד',
'exif-identifier'                  => 'אידענטיפֿיצירער',
'exif-lens'                        => 'געניצטער לינז',
'exif-serialnumber'                => 'סעריע־נומער פון קאמערע',
'exif-cameraownername'             => 'אייגנטימער פון קאמערע',
'exif-label'                       => 'צעטל',
'exif-datetimemetadata'            => 'דאטע ווען מעטאדאטן זענען געווען לעצט געענדערט',
'exif-nickname'                    => 'אויספארמעלער נאמען פון בילד',
'exif-rating'                      => 'שאצונג (פֿון 5)',
'exif-rightscertificate'           => 'רעכטן פארוואלטונג צערטיפיקאט',
'exif-copyrighted'                 => 'קאפירעכט סטאַטוס',
'exif-copyrightowner'              => 'קאפירעכטן האלטער',
'exif-usageterms'                  => 'ניץ באַדינגונג',
'exif-licenseurl'                  => 'URL פֿאר קאפירעכט ליצענץ',
'exif-morepermissionsurl'          => 'אלטערנאטיווע ליצענצירן אינפארמאציע',
'exif-pngfilecomment'              => 'PNG טעקע הערה',
'exif-contentwarning'              => 'אינהאלט ווארענונג',
'exif-giffilecomment'              => 'GIF טעקע הערה',
'exif-intellectualgenre'           => 'ארט  איינהייט',
'exif-subjectnewscode'             => 'טעמע קאד',

# EXIF attributes
'exif-compression-1' => 'אומ-צאמגעקוועטשט',

'exif-copyrighted-true'  => 'געשיצט מיט קאפירעכט',
'exif-copyrighted-false' => 'פובליקער געביט',

'exif-unknowndate' => 'אומבאַוואוסטע דאַטע',

'exif-orientation-1' => 'נארמאַל',
'exif-orientation-3' => 'ראטירט 180°',
'exif-orientation-6' => 'ראטירט 90° קעגן זייגער',
'exif-orientation-7' => 'ראטירט  90° מיטן זייגער און איבערגעדרייט ווערטיקאל',
'exif-orientation-8' => 'ראטירט 90° מיטן זייגער',

'exif-planarconfiguration-1' => 'גראבער פֿארמאט',
'exif-planarconfiguration-2' => 'פֿלאכער פֿארמאט',

'exif-colorspace-65535' => 'נישט קאליברירט',

'exif-componentsconfiguration-0' => "ס'עקזיסטירט נישט.",

'exif-exposureprogram-0' => 'נישט דעפענירט',
'exif-exposureprogram-1' => 'האַנטלעך',
'exif-exposureprogram-2' => 'נארמאלער פראגראם',
'exif-exposureprogram-3' => 'עפענען פריאריטעט',
'exif-exposureprogram-4' => 'צאמשפארן פריאריטעט',
'exif-exposureprogram-5' => 'שאפענדע פראגראם (בייעסט אין ריכטונג פוןדי טיפקייט פעלד)',
'exif-exposureprogram-6' => 'אקטיוו פראגראם (בייעס אויפן ריכטונג צו צומאכן ספיד)',
'exif-exposureprogram-7' => 'פארטרעיט מצב (פאר קלאוסאפ בילדער אין די בעקגראונד ארויס פון פאקוס)',
'exif-exposureprogram-8' => 'לענדסקעיפ מצב (פאר בילדער פון פאנאראמעס וואס בעקגראונד איז אין פאקוס)',

'exif-subjectdistance-value' => '$1 מעטער',

'exif-meteringmode-0'   => 'אומבאוויסט',
'exif-meteringmode-1'   => 'דורכשניט',
'exif-meteringmode-5'   => 'מוסטער',
'exif-meteringmode-6'   => 'טיילווײַז',
'exif-meteringmode-255' => 'אנדער',

'exif-lightsource-0'  => 'אומבאַוויסט',
'exif-lightsource-1'  => 'טאָגליכט',
'exif-lightsource-2'  => 'פֿלוארעסצירנד',
'exif-lightsource-3'  => 'טונגסטן (צעהיצעדיק ליכט)',
'exif-lightsource-4'  => 'בליץ',
'exif-lightsource-9'  => 'פֿייַן וועטער',
'exif-lightsource-10' => 'פאַרוואָלקנטער וועטער',
'exif-lightsource-11' => 'שאָטן',

'exif-focalplaneresolutionunit-2' => 'אינטשעס',

'exif-sensingmethod-1' => 'אומדעפינירט',

'exif-customrendered-0' => 'נארמאלער פראצעס',
'exif-customrendered-1' => 'קאסטעם פראצעס',

'exif-exposuremode-0' => 'אויטאמאטיש באַלײַכטן',
'exif-exposuremode-1' => 'האַנט־באַלײַכטן',
'exif-exposuremode-2' => 'אטאמאטישער לייסטל',

'exif-scenecapturetype-0' => 'סטאנדארט',
'exif-scenecapturetype-1' => 'לאַנדשאַפֿט',
'exif-scenecapturetype-2' => 'פארטרעט',
'exif-scenecapturetype-3' => 'נאַכט סצענע',

'exif-gaincontrol-0' => 'גארנישט',
'exif-gaincontrol-1' => 'נידעריגע צובאקומען ארויף',
'exif-gaincontrol-2' => 'הויכע צובאקומען ארויף',
'exif-gaincontrol-3' => 'נידעריגע צובאקומען אראפ',
'exif-gaincontrol-4' => 'הויכע צובאקומען אראפ',

'exif-contrast-0' => 'נארמאל',
'exif-contrast-1' => 'ווייך',
'exif-contrast-2' => 'האַרט',

'exif-saturation-0' => 'נארמאַל',

'exif-sharpness-0' => 'נארמאל',
'exif-sharpness-1' => 'ווייך',
'exif-sharpness-2' => 'הארט',

'exif-subjectdistancerange-0' => 'אומבאַוויסט',
'exif-subjectdistancerange-1' => 'מאקרא',
'exif-subjectdistancerange-2' => 'נאנטע ווייזונג',
'exif-subjectdistancerange-3' => 'ווײַטע ווײַזונג',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'צפון ברייט',
'exif-gpslatitude-s' => 'דרום ברייט',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'מזרח לענג',
'exif-gpslongitude-w' => 'מערב לענג',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|מעטער|מעטער}} איבערן ים־שפיגלl',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|מעטער|מעטער}} אונטערן ים־שפיגל',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'ק"מ אין א שעה',
'exif-gpsspeed-m' => 'מייל פער שעה',
'exif-gpsspeed-n' => 'ים מײַלן א שעה',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'קילאמעטער',
'exif-gpsdestdistance-m' => 'מייל',
'exif-gpsdestdistance-n' => 'ים־מייל',

'exif-gpsdop-excellent' => 'אויסגעצייכנט ($1)',
'exif-gpsdop-good'      => 'גוט ($1)',
'exif-gpsdop-moderate'  => 'מיטלמעסיק ($1)',
'exif-gpsdop-fair'      => 'נישקשהדיק ($1)',
'exif-gpsdop-poor'      => 'שוואך ($1)',

'exif-objectcycle-a' => 'נאר אינדערפרי',
'exif-objectcycle-p' => 'נאר אוונד',
'exif-objectcycle-b' => 'סיי אינדערפרי סיי אין אוונט',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'ריכטיגע דירעקציע',
'exif-gpsdirection-m' => 'מאגנאטיק ריכטונג',

'exif-ycbcrpositioning-1' => 'צענטרירט',
'exif-ycbcrpositioning-2' => 'אין זעלבן ארט',

'exif-dc-contributor' => 'בײַשטײַערער',
'exif-dc-coverage'    => 'ערטלעכער אדער צייטלעכער פארנעם פון מעדיע',
'exif-dc-date'        => 'דאטע(ס)',
'exif-dc-publisher'   => 'פֿאַרלעגער',
'exif-dc-relation'    => 'ענלעכע מעדיע',
'exif-dc-rights'      => 'רעכט',
'exif-dc-source'      => 'אריגינעלע מעדיע',
'exif-dc-type'        => 'סארט מעדיע',

'exif-rating-rejected' => 'אפגעווארפֿן',

'exif-isospeedratings-overflow' => 'גרעסער פֿון 65535',

'exif-iimcategory-ace' => 'קונסט, קולטור און פאַרווײַלונג',
'exif-iimcategory-clj' => 'פאַרברעכן און געזעץ',
'exif-iimcategory-dis' => 'קאַטאַסטראפֿעס און אַקצידענטן',
'exif-iimcategory-fin' => 'עקאנאמיע און געשעפֿט',
'exif-iimcategory-edu' => 'בילדונג',
'exif-iimcategory-evn' => 'סביבה',
'exif-iimcategory-hth' => 'געזונט',
'exif-iimcategory-hum' => 'מענטשלעכער אינטערעס',
'exif-iimcategory-lab' => 'אַרבעט',
'exif-iimcategory-lif' => 'לעבנסטיל און פֿרייַע צייַט',
'exif-iimcategory-pol' => 'פּאָליטיק',
'exif-iimcategory-rel' => 'רעליגיע און גלויבן',
'exif-iimcategory-sci' => 'וויסנשאַפֿט און טעכנאָלאָגיע',
'exif-iimcategory-soi' => 'סאציאלע פֿראגעס',
'exif-iimcategory-spo' => 'ספארט',
'exif-iimcategory-war' => 'מלחמה, קאָנפליקט און אומרו',
'exif-iimcategory-wea' => 'וועטער',

'exif-urgency-normal' => 'נאָרמאַל ($1)',
'exif-urgency-low'    => 'נידעריק ($1)',
'exif-urgency-high'   => 'הויך ($1)',
'exif-urgency-other'  => 'באניצער־דעפינירטע פריאריטעט ($1)',

# External editor support
'edit-externally'      => 'רעדאַקטירט די טעקע מיט א דרויסנדיגער אַפליקאַציע',
'edit-externally-help' => 'זעט די [//www.mediawiki.org/wiki/Manual:External_editors אויפֿשטעל אנווייזונגען] פאר מער אינפארמאציע.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'אַלע',
'namespacesall' => 'אַלע',
'monthsall'     => 'אלע',
'limitall'      => 'אַלע',

# E-mail address confirmation
'confirmemail'              => 'באַשטעטיקט בליצפּאָסט אַדרעס',
'confirmemail_noemail'      => 'איר האט נישט קיין גוטן בליצבריוו אַדרעס אין אײַער [[Special:Preferences|באניצער פרעפֿערענצן]].',
'confirmemail_text'         => 'די וויקי פארלאנגט אז איר זאלט באשטעטיגן אייער בליצפאסט אדרעס איידער איר באניצט זיך מיט דער ע-פאסט באדינסט. דרוקט אויפן קנעפל אונטן כדי צו שיקן א באשטעטיגונג קאד צו אייער אדרעס. לאדט אן דעם לינק אין אייער בלעטערער צו באשטעטיגן אז אייער אדרעס איז גילטיג.',
'confirmemail_pending'      => 'א באשטעטיגונג קאד איז שוין געשיקט געווארן צו אייך;
אויב איר האט לעצטנס באַשאַפֿן אײַער קאנטע, איז אפשר כדאי ווארטן א פאר מינוט ביז דאס דערגרייכט אייך ווי איידער צו בעטן א נייעם קאד.',
'confirmemail_send'         => 'שיקט באַשטעטיקונג קאד',
'confirmemail_sent'         => 'באשטעטיקונג בליצברװ געשיקט.',
'confirmemail_oncreate'     => 'א באשטעטיגונג קאד איז געשיקט געווארן צו אייער ע־פאסט אדרעס.
דער קאד ווערט נישט פֿארלאנגט צו קענען ארײַנלאגירן, אבער איר וועט אים דארפן דערלאנגן אויף צו קענען באניצן מיט ע־פאסט באַזירטע איינהייטן אין דער וויקי.',
'confirmemail_sendfailed'   => '{{SITENAME}} האט נישט געקענט שיקן אייך די באשטעטיגונג קאד. ביטע טוט קאנטראלירן אייער אדרעס אויב עס האט נישט קיין טעות.

ע-פאסט צוריגעקערט: $1',
'confirmemail_invalid'      => 'נישט קיין גוטער באַשטעטיקן קאָד. ער איז מעגלעך אויסגעגאַנגען.',
'confirmemail_needlogin'    => 'איר ברויכט דורכפֿירן $1 כדי צו באשטעטיגן אײַער ע-פאסט אדרעס.',
'confirmemail_success'      => 'אײַער בליצפּאָסט אַדרעס איז באַשטעטיקט געװאָרן.
איר קענט איצט [[Special:UserLogin|אַרײַנלאגירן]] און הנאה האָבן פֿון דער וויקי.',
'confirmemail_loggedin'     => 'אייער ע־פאסט אדרעס איז איצט געווארן באשטעטיגט.',
'confirmemail_error'        => 'עפעס איז געגאנגען שלעכט מיט אָפּהיטן אײַער באַשטעטיקונג.',
'confirmemail_subject'      => '{{SITENAME}} בליצבריװ אדרעס באַשטעטיקונג',
'confirmemail_body'         => 'עמעצער, ווארשיינליך איר (פון איי פי אדרעס: $1), האט איינגעשריבן די קונטע: "$2" מיט אט דעם בליצפאסט אדרעס אויף {{SITENAME}}.

כדי זיכער מאכן אז די קונטע געהערט טאקע צו אייך, ביטע טוט עפענען דעם לינק אין די אייער בלעטערער:

$3

אויב *נישט* איר האט איינגעשריבן די קונטע, פאלגט די דאזיגע לינק מבטל צו זיין די ע-פאסט אדרעס באשטעטיגונג:

$5

דער באשטעטיגונג קאד גייט אויס $4.',
'confirmemail_body_changed' => 'עמעצער, ווארשיינליך איר, פֿון IP אַדרעס: $1, האט געענדערט דעם ע־פאסט אַדרעס פֿון דער קאנטע "$2" צו דעם אדרעס אויף {{SITENAME}}.

צו באַשטעטיקן אַז די קונטע געהערט טאקע צו אייך און ווידער אַקטיווירן ע־פאסט דינסטן אויף {{SITENAME}}, ביטע טוט עפֿענען דעם לינק אין אייער בלעטערער:

$3

אויב די קאנטע געהערט *נישט* אײַך, פאלגט די דאָזיגע לינק מבטל צו זיין די ע-פאסט אדרעס באשטעטיגונג:

$5

דער באשטעטיגונג קאד גייט אויס $4.',
'confirmemail_body_set'     => 'עמעצער, ווארשיינליך איר, פֿון IP אַדרעס $1, 
האט געענדערט דעם ע־פאסט אַדרעס פֿון דער קאנטע "$2" צו דעם אדרעס אויף {{SITENAME}}.

צו באַשטעטיקן אַז די קאנטע געהערט טאקע צו אייך און ווידער אַקטיווירן ע־פאסט דינסטן אויף {{SITENAME}}, ביטע טוט עפֿענען דעם לינק אין אייער בלעטערער:

$3

אויב די קאנטע געהערט *נישט* אײַך, פאלגט די דאָזיגע לינק מבטל צו זיין די ע-פאסט אדרעס באשטעטיגונג:

$5

די באשטעטיגונג קאד גייט אויס $4.',
'confirmemail_invalidated'  => 'בליצפאסט אדרעס באשטעטיקונג אַנולירט',
'invalidateemail'           => 'אַנולירן בליצפאסט באַשטעטיקונג',

# Scary transclusion
'scarytranscludedisabled' => '[אינטערוויקי אריבערשליסן איז אַנולירט]',
'scarytranscludetoolong'  => '[URL צו לאנג]',

# Delete conflict
'deletedwhileediting' => 'ווארענונג: דער בלאט איז געווארן אויסגעמעקט נאכדעם וואס איר האט אנגעהויבן רעדאקטירן!',
'confirmrecreate'     => "באנוצער [[User:$1|$1]] ([[User talk:$1|רעדן]]) האט אויסגעמעקט דעם בלאט נאכדעם וואס איר האט אנגעהויבן דאס צו ענדערן, אלס אנגעבליכער סיבה:
:'''$2'''
ביטע באשטעטיגט אז איר ווילט טאקע צוריקשטעלן דעם בלאט.",
'recreate'            => 'שאַפֿן פֿונדאסניי',

# action=purge
'confirm_purge_button' => 'אויספֿירן',
'confirm-purge-top'    => 'אויסקלארן די קאשעי פון דעם בלאט?',

# action=watch/unwatch
'confirm-watch-button'   => 'יאָ',
'confirm-watch-top'      => 'צולייגן דעם בלאט צו אייער אויפֿפאסונג ליסטע?',
'confirm-unwatch-button' => 'יאָ',
'confirm-unwatch-top'    => 'אראפנעמען דעם בלאט פון אייער אויפפאסונג ליסטע?',

# Multipage image navigation
'imgmultipageprev' => '→ פֿריערדיגער בלאַט',
'imgmultipagenext' => 'צום קומענדיגן בלאט ←',
'imgmultigo'       => 'גייט!',
'imgmultigoto'     => 'אריבער צו בלאט $1',

# Table pager
'ascending_abbrev'         => 'ארויף',
'descending_abbrev'        => 'נידערן',
'table_pager_next'         => 'נעקסטער בלאט',
'table_pager_prev'         => 'פריערדיקער בלאט',
'table_pager_first'        => 'ערשטער בלאט',
'table_pager_last'         => 'לעצטער בלאט',
'table_pager_limit'        => 'ווײַז $1 פרטים א בלאט',
'table_pager_limit_label'  => 'איינהייטן אין א בלאַט',
'table_pager_limit_submit' => 'גיין',
'table_pager_empty'        => 'קיין רעזולטאטן',

# Auto-summaries
'autosumm-blank'   => 'אויסגעליידיקט דעם בלאט',
'autosumm-replace' => "פֿאַרבײַט דעם בלאַט מיט '$1'",
'autoredircomment' => 'ווייטערפירן צו [[$1]]',
'autosumm-new'     => "געשאַפֿן בלאַט מיט '$1'",

# Live preview
'livepreview-loading' => 'לאדנדיג…',
'livepreview-ready'   => 'לאדנדיג… גרייט!',

# Watchlist editor
'watchlistedit-numitems'      => 'אײַער אויפֿפאַסונג ליסטע אַנטהאַלט {{PLURAL:$1|1 טיטל|$1 טיטלען}}, אויסשליסנדיק שמועסבלעטער.',
'watchlistedit-noitems'       => 'אײַער אויפֿפאַסן ליסטע איז ליידיג.',
'watchlistedit-normal-title'  => 'רעדאַקטירן די אויפֿפאַסונג ליסטע',
'watchlistedit-normal-legend' => 'אַראָפנעמען בלעטער פון דער אויפֿפאסן ליסטע',
'watchlistedit-normal-submit' => 'אַראָפנעמען בלעטער',
'watchlistedit-normal-done'   => '{{PLURAL:$1|1 טיטל איז|$1 טיטלען זענען}} געווארן אראפגענומען פון אייער אויפפאסונג־ליסטע:',
'watchlistedit-raw-title'     => 'רעדאַקטירן די רויע אויפֿפאַסונג ליסטע',
'watchlistedit-raw-legend'    => 'רעדאַקטירן די רויע אויפֿפאַסונג ליסטע',
'watchlistedit-raw-titles'    => 'טיטלען:',
'watchlistedit-raw-submit'    => 'דערהיינטיג אויפפאסונג ליסטע',
'watchlistedit-raw-done'      => 'אייער אויפֿפאַסונג ליסטע איז געווארן דערהײַנטיקט',
'watchlistedit-raw-added'     => '{{PLURAL:$1|1 טיטל איז|$1 טיטלען זענען}} געווען צוגעלייגט:',
'watchlistedit-raw-removed'   => '{{PLURAL:$1|1 טיטל איז|$1 טיטלען זענען}} געווען אַראָפגענומען:',

# Watchlist editing tools
'watchlisttools-view' => 'ווייזן שייכדיגע ענדערונגען',
'watchlisttools-edit' => 'זען און רעדאקטירן די אויפֿפאסונג ליסטע',
'watchlisttools-raw'  => 'רעדאקטירן די רויע אויפֿפאסונג ליסטע',

# Iranian month names
'iranian-calendar-m1'  => 'פֿאַרוואַרדין',
'iranian-calendar-m2'  => 'ארדיבעהעשט',
'iranian-calendar-m3'  => 'כארדאַד',
'iranian-calendar-m4'  => 'טיר',
'iranian-calendar-m5'  => 'מארדאַד',
'iranian-calendar-m6'  => 'שאַהריוואַר',
'iranian-calendar-m7'  => 'מעהר',
'iranian-calendar-m8'  => 'אַבאַן',
'iranian-calendar-m9'  => 'אַזאַר',
'iranian-calendar-m10' => 'דיי',
'iranian-calendar-m11' => 'באַהמאַן',
'iranian-calendar-m12' => 'עספֿאַנד',

# Hijri month names
'hijri-calendar-m9'  => 'ראמאדאן',
'hijri-calendar-m10' => 'שאוואל',
'hijri-calendar-m11' => 'דהו אל־קאדא',
'hijri-calendar-m12' => 'דהו אל־הידזשא',

# Hebrew month names
'hebrew-calendar-m1'      => 'תשרי',
'hebrew-calendar-m2'      => 'חשוון',
'hebrew-calendar-m3'      => 'כסלו',
'hebrew-calendar-m4'      => 'טבת',
'hebrew-calendar-m5'      => 'שבט',
'hebrew-calendar-m6'      => 'אדר',
'hebrew-calendar-m6a'     => "אדר א'",
'hebrew-calendar-m6b'     => "אדר ב'",
'hebrew-calendar-m7'      => 'ניסן',
'hebrew-calendar-m8'      => 'אייר',
'hebrew-calendar-m9'      => 'סיון',
'hebrew-calendar-m10'     => 'תמוז',
'hebrew-calendar-m11'     => 'אב',
'hebrew-calendar-m12'     => 'אלול',
'hebrew-calendar-m1-gen'  => 'תשרי',
'hebrew-calendar-m2-gen'  => 'חשוון',
'hebrew-calendar-m3-gen'  => 'כסלו',
'hebrew-calendar-m4-gen'  => 'טבת',
'hebrew-calendar-m5-gen'  => 'שבט',
'hebrew-calendar-m6-gen'  => 'אדר',
'hebrew-calendar-m6a-gen' => "אדר א'",
'hebrew-calendar-m6b-gen' => "אדר ב'",
'hebrew-calendar-m7-gen'  => 'ניסן',
'hebrew-calendar-m8-gen'  => 'אייר',
'hebrew-calendar-m9-gen'  => 'סיון',
'hebrew-calendar-m10-gen' => 'תמוז',
'hebrew-calendar-m11-gen' => 'אב',
'hebrew-calendar-m12-gen' => 'אלול',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|רעדן]])',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'ווארענונג:\'\'\' גרונט סארטשליסל "$2" פֿאָרט איבערן פֿריערדיגן גרונט סארטשליסל "$1".',

# Special:Version
'version'                      => 'ווערסיע',
'version-extensions'           => 'אינסטאלירטע פארברייטערונגען',
'version-specialpages'         => 'ספעציעלע בלעטער',
'version-variables'            => 'וואַריאַבלען',
'version-skins'                => 'באניצער־אייבערפלאכן',
'version-other'                => 'אנדער',
'version-hooks'                => 'Hook סטרוקטורן',
'version-extension-functions'  => 'פארברייטערן פונקציעס',
'version-parser-extensiontags' => 'פארזער פארברייטערן טאַגן',
'version-hook-name'            => 'נאמען פון hook',
'version-version'              => '(ווערסיע $1)',
'version-license'              => 'ליצענץ',
'version-poweredby-others'     => 'אַנדערע',
'version-software'             => 'אינסטאַלירט ווייכוואַרג',
'version-software-product'     => 'פראדוקט',
'version-software-version'     => 'ווערסיע',

# Special:FilePath
'filepath'        => 'טעקע שטעג',
'filepath-page'   => 'טעקע:',
'filepath-submit' => 'גיין',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'זוכן דופליקאַטע טעקעס',
'fileduplicatesearch-summary'   => 'זוכן דופליקאטע טעקעס באזירט אויף האש־ווערטן.',
'fileduplicatesearch-legend'    => 'זוכן א דופליקאַט',
'fileduplicatesearch-filename'  => 'טעקע:',
'fileduplicatesearch-submit'    => 'זוכן',
'fileduplicatesearch-info'      => '$1 × $2 פיקסעל<br />טעקע גרייס: $3<br /> טיפ MIME: $4',
'fileduplicatesearch-noresults' => 'קיין טעקע מיטן נאמען "$1" נישט געטראפֿן.',

# Special:SpecialPages
'specialpages'                   => 'ספּעציעלע זײַטן',
'specialpages-note'              => '----
* נארמאַלע באַזונדערע בלעטער.
* <strong class="mw-specialpagerestricted">באַגרענעצטע באַזונדערע בלעטער.</strong>
* <span class="mw-specialpagecached">באַזונדערע בלעטער פֿון זאַפאַס (קענען זײַן פֿאַרעלטערט).</span>',
'specialpages-group-maintenance' => 'אויפֿהאַלטונג באַריכטן',
'specialpages-group-other'       => 'אַנדערע ספעציעלע בלעטער',
'specialpages-group-login'       => 'ארײַנלאגירן / שאַפֿן קאנטע',
'specialpages-group-changes'     => 'לעצטע ענדערונגען און לאג-ביכער',
'specialpages-group-media'       => 'מעדיע באַריכטן און ארויפֿלאדן',
'specialpages-group-users'       => 'באַניצערס און רעכטן',
'specialpages-group-highuse'     => 'בלעטער וואס זענען געניצט אסאך',
'specialpages-group-pages'       => 'ליסטעס פֿון בלעטער',
'specialpages-group-pagetools'   => 'געצייג פֿאר בלעטער',
'specialpages-group-wiki'        => 'וויקי דאַטן און געצייג',
'specialpages-group-redirects'   => 'ווײַטערפֿירן ספעציעלע בלעטער',
'specialpages-group-spam'        => 'ספאַם געצייג',

# Special:BlankPage
'blankpage'              => 'ליידיגער בלאַט',
'intentionallyblankpage' => 'דער בלאַט איז ליידיג בכוונה',

# External image whitelist
'external_image_whitelist' => ' # לאזט די שורה גענוי ווי זי איז<pre>
#לייגט רעגולערע אויסדרוק פֿראגמענטן (נאר דעם טייל צווישן די //) אונטן
#מען וועט זיי צופאסן מיט די  URL אדרעסן פון אויסערלעכע בילדער
#די וואס זענען צוגעפאסט וועט מען אויסשטעלן ווי בילדער, אנדערשט וועט  מען ווייזן א לינק צום בילד
#שורות וואס הייבן אן # ווערן גערעכנט הערות
#דאס איז נישט אפהענגיק אין קליינע אדער גרויסע אותיות

#אלע רעגולערע אויסדרוקן זאל מען שטעלן העכער פון דער שורה. לאזט די שורה גענוי ווי זי איז</pre>',

# Special:Tags
'tags'                    => 'גילטיקע ענדערונג טאַגן',
'tag-filter'              => '[[Special:Tags|מאַרקירונג]] פֿילטער:',
'tag-filter-submit'       => 'פֿילטער',
'tags-title'              => 'טאַגן',
'tags-intro'              => 'דער בלאַט ווײַזט די טאַגן מיט וואס דאס ווייכווארג קען צייכענען אַן רעדאַגירונג, און זייער באַטייַט.',
'tags-tag'                => 'טאַג נאָמען',
'tags-display-header'     => 'אויסזען אין ענדערונג רשימות',
'tags-description-header' => 'פֿולי באַשרייַבונג פון באַטײַט',
'tags-hitcount-header'    => 'מארקירטע ענדערונגען',
'tags-edit'               => 'רעדאַקטירן',
'tags-hitcount'           => ' {{PLURAL:$1|ענדערונג|$1 ענדערונגען}}',

# Special:ComparePages
'comparepages'                => 'פאַרגלייַכן בלעטער',
'compare-selector'            => 'פאַרגלייַכן בלאַט רעוויזיעס',
'compare-page1'               => 'עמוד 1',
'compare-page2'               => 'עמוד 2',
'compare-rev1'                => 'רעוויזיע 1',
'compare-rev2'                => 'רעוויזיע 2',
'compare-submit'              => 'פֿאַרגלייַכן',
'compare-invalid-title'       => 'דעם טיטל איר האט ספעציפֿירט איז אומגילטיק.',
'compare-title-not-exists'    => 'דעם טיטל וואס איר האט ספעציפֿירט עקזיסטירט נישט',
'compare-revision-not-exists' => 'די רעוויזיע וואס איר האט ספעציפֿירט עקזיסטירט נישט.',

# Database error messages
'dberr-header'      => 'די וויקי האט א פראבלעם',
'dberr-problems'    => 'אנטשולדיגט! דער דאזיקער סייט האט טעכנישע פראבלעמען.',
'dberr-again'       => 'וואַרט א פאָר מינוט און לאָדנט אָן ווידער.',
'dberr-info'        => '(קען נישט פֿאַרבינדן מיטן דאַטנבאַזע באַדינער: $1)',
'dberr-usegoogle'   => 'אינצווישנצײַט קענט איר פרובירן זוכן דורך גוגל.',
'dberr-outofdate'   => 'גיט אַכט אַז זײַערע אינדעקסן פֿון אונזער אינהאַלט איז מעגלעך פֿאַרעלטערט.',
'dberr-cachederror' => 'דאָס איז אַן אײַנגעשפייכלערט קאפיע פֿון  דעם געפֿאדערטן בלאַט, און קען זײַן פֿאַרעלטערט.',

# HTML forms
'htmlform-invalid-input'       => 'עס זענען פֿאַראַן פּראָבלעמען מיט א טייל פון ​​אייער אַרייַנוואַרג',
'htmlform-select-badoption'    => 'דער ווערט וואַס איר האט ספּעציפֿיצירט איז נישט קיין גילטיקע אָפּציע.',
'htmlform-int-invalid'         => 'דער ווערט וואָס איר האט ספעציפֿירט איז נישט קיין גאַנצצאָל.',
'htmlform-float-invalid'       => 'דער ווערט וואָס איר האט ספעציפֿירט איז נישט קיין צאָל.',
'htmlform-int-toolow'          => 'דער ווערט וואָס איר האט ספעציפֿיצירט איז אונטער דעם מינימום $1',
'htmlform-int-toohigh'         => 'דער ווערט וואָס איר האט ספעציפֿיצירט איז העכער דעם מאַקסימום $1',
'htmlform-required'            => 'דער ווערט איז געפֿאדערט',
'htmlform-submit'              => 'אײַנגעבן',
'htmlform-reset'               => 'צוריקשטעלן ענדערונגען',
'htmlform-selectorother-other' => 'אַנדער',

# SQLite database support
'sqlite-has-fts' => '$1 מיט פולן-טעקסט זוכן שטיץ',
'sqlite-no-fts'  => '$1 אָן פֿולן-טעקסט זוכן שטיץ',

# New logging system
'logentry-delete-delete'              => '$1 האט אויסגעמעקט בלאט $3',
'logentry-delete-restore'             => '$1 האט צוריקגעשטעלט בלאט $3',
'logentry-delete-event'               => '$1 האט געענדערט די זעבארקייט פון {{PLURAL:$5|א לאגבוך אקטיוויטעט|$5 לאגבוך אקטיוויטעטן}} אויף $3: $4',
'logentry-delete-revision'            => '$1 האט געענדערט די זעבארקייט פון  {{PLURAL:$5|א רעוויזיע|$5 רעוויזיעס}} אויף בלאט $3: $4',
'logentry-delete-event-legacy'        => '$1 האט געענדערט די זעבארקייט פון לאגבוך אקטיוויטעטן אויף $3',
'logentry-delete-revision-legacy'     => '$1 האט געענדערט די זעבארקייט פון רעוויזיעס אויף בלאט $3',
'logentry-suppress-delete'            => '$1 האט אונטערדריקט בלאט $3',
'logentry-suppress-event'             => '$1 האט געהיימלעך געענדערט די זעבארקייט פון {{PLURAL:$5|א לאגבוך אקטיוויטעט|$5 לאגבוך אקטיוויטעטן}} אויף $3: $4',
'logentry-suppress-revision'          => '$1 האט געהיימלעך געענדערט די זעבארקייט פון  {{PLURAL:$5|א רעוויזיע|$5 רעוויזיעס}} אויף בלאט $3: $4',
'logentry-suppress-event-legacy'      => '$1 האט געהיימלעך געענדערט די זעבארקייט פון לאגבוך אקטיוויטעטן אויף $3',
'logentry-suppress-revision-legacy'   => '$1 האט געהיימלעך געענדערט די זעבארקייט פון רעוויזיעס אויף בלאט $3',
'revdelete-content-hid'               => 'אינהאלט פארהוילן',
'revdelete-summary-hid'               => 'רעדאקטירונג קאנספעקט פארהוילן',
'revdelete-uname-hid'                 => 'באניצער־נאמען פארהוילן',
'revdelete-content-unhid'             => 'אינהאלט ארויסגעגעבן',
'revdelete-summary-unhid'             => 'רעדאקטירונג קאנספעקט ארויסגעגעבן',
'revdelete-uname-unhid'               => 'באַניצער נאָמען ארויסגעגעבן',
'revdelete-restricted'                => 'צוגעלייגט באגרעניצונגען פאר סיסאפן',
'revdelete-unrestricted'              => 'אוועקגענומען באגרעניצונגען פאר סיסאפן',
'logentry-move-move'                  => '$1 האט באוועגט בלאט $3 צו $4',
'logentry-move-move-noredirect'       => '$1 האט באוועגט בלאט $3 צו $4 אן לאזן א ווייטערפירונג',
'logentry-move-move_redir'            => '$1 האט באוועגט $3 צו $4 אריבער ווייטערפירונג',
'logentry-move-move_redir-noredirect' => '$1 האט באוועגט $3 צו $4 אריבער א ווייטערפירונג אן לאזן א  ווייטערפירונג',
'logentry-patrol-patrol'              => '$1 האט מארקירט רעוויזיע $4 פון בלאט $3 ווי קאנטראלירט',
'logentry-patrol-patrol-auto'         => '$1 האט אויטאמאטיש מארקירט רעוויזיע $4 פון בלאט $3 ווי קאנטראלירט',
'logentry-newusers-newusers'          => 'באניצער קאנטע $1 געשאפן געווארן',
'logentry-newusers-create'            => 'באניצער קאנטע $1 געשאפן געווארן',
'logentry-newusers-create2'           => 'באניצער קאנטע $1 געשאפן געווארן דורך $3',
'logentry-newusers-autocreate'        => 'קאנטע $1 באשאפן אויטאמאטיש',
'newuserlog-byemail'                  => 'פאַסווארט געשיקט דורך ע-פאסט',

# Feedback
'feedback-bugornote' => 'ווען איר זענט גרייט צו באשרייבן א טעכנישן פראבלעם ביטע [$1 מעלדט א פעלער].
אנדערש, קענט איר ניצן די גרינגע פארעם אונטן. מען וועט צולייגן אייער הערה צום בלאט "[$3 $2]", צוזאמען מיט אייער באניצער נאמען און וועלכן בלעטערער איר ניצט.',
'feedback-subject'   => 'טעמע:',
'feedback-message'   => 'מעלדונג:',
'feedback-cancel'    => 'אַנולירן',
'feedback-submit'    => 'ארײַנגעבן פֿידבעק',
'feedback-adding'    => 'צולייגן פֿידבעק צו בלאַט...',
'feedback-error1'    => 'טעות: אומבאַקאַנטער רעזולטאַט פון API',
'feedback-error2'    => 'טעות: רעדאַקטירן דורכפֿאַל',
'feedback-error3'    => 'טעות: קיין ענטפֿער פון API',
'feedback-thanks'    => 'ייש"כ! אײַער פֿידבעק איז געווארן ארויפגעלעגט צום בלאט "[$2 $1]".',
'feedback-close'     => 'ערליידיקט',
'feedback-bugcheck'  => 'געוואלדיק! אבער זייט בודק אז עס איז נישט איינער פון די [$1 באוואוסטע באגן].',
'feedback-bugnew'    => "כ'האב בודק געווען. רעפארטירט א נייעם באג.",

# API errors
'api-error-badaccess-groups'              => 'איר האט נישט קיין רעכטן אַרויפֿלאָדן טעקעס אויף דער וויקי.',
'api-error-badtoken'                      => 'אינערלעכער גרײַז: סימן טויג נישט.',
'api-error-copyuploaddisabled'            => 'אַרויפֿלאָדן דורך URL איז אומאַקטיווירט אויף דעם סערווירער.',
'api-error-duplicate'                     => 'שוין דאָ אין דער וויקי {{PLURAL:$1|[$2 ָאַן אַנדער טעקע]|[$2 אַנדערע טעקעס]}} מיטן זעלבן תוכן.',
'api-error-duplicate-archive'             => "ס'איז שוין געווען {{PLURAL:$1| [ $2 אַן אַנדער טעקע] | געווען [ $2 עטלעכע אַנדערע טעקעס]}} אויף דעם פּלאַץ מיט דעם זעלביקן תוכן, אָבער {{PLURAL:$1| עס איז | זיי זענען}}  געווארן אויסגעמעקט.",
'api-error-duplicate-archive-popup-title' => 'פֿאַרטאפלטע {{PLURAL:$1| טעקע | טעקעס}} וואָס זענען שוין געווארן אויסגעמעקט',
'api-error-duplicate-popup-title'         => 'פֿאַרטאפלטע {{PLURAL:$1| טעקע | טעקעס}}',
'api-error-empty-file'                    => 'די טעקע וואָס איר האט אײַנגעגעבן איז ליידיג.',
'api-error-emptypage'                     => 'שאפן נייע ליידיקע בלעטער איז נישט ערלויבט.',
'api-error-fetchfileerror'                => 'אינערלעכער גרײַז: עפעס איז קאַליע געווארן בײַם ברענגען די טעקע.',
'api-error-file-too-large'                => 'די טעקע וואָס איר האט אײַנגעגעבן איז צו גרויס.',
'api-error-filename-tooshort'             => 'דער טעקע־נאָמען איז צו קורץ.',
'api-error-filetype-banned'               => 'דער טיפ טעקע איז געאַסרט.',
'api-error-filetype-missing'              => 'די טעקע פֿעלט אַן ענדונג.',
'api-error-hookaborted'                   => 'די מאדיפיצירונג איר האט פרובירט קען נישט ווערן דורכגעפירט צוליב א פארברייטערונג.',
'api-error-http'                          => 'אינערלעכער גרײַז: נישט געקענט פֿאַרבינדן צום סערווירער.',
'api-error-illegal-filename'              => 'דער טעקע־נאָמען איז נישט ערלויבט.',
'api-error-internal-error'                => 'אינערלעכער גרײַז: עפעס איז קאַליע געווארן בײַם פראצעסירן אײַער אַרופֿלאָד אויף דער וויקי.',
'api-error-invalid-file-key'              => 'אינערלעכער גרײַז: נישט געטראפֿן טעקע אין צײַטווײַליקן שפייכלער',
'api-error-missingparam'                  => 'אינערלעכער גרײַז: פֿעלן פאראמעטערס אין פֿאַרלאַנג',
'api-error-missingresult'                 => 'אינערלעכער גרײַז: נישט געקענט פֿעסטשטעלן צי קאפירן איז געווען דערפֿאלגרייך.',
'api-error-mustbeloggedin'                => 'איר דארפֿט זײַן אַרײַנלאגירט אַרויפֿצולאָדן טעקעס.',
'api-error-mustbeposted'                  => 'אינערלעכער גרײַז: פֿאַרלאַנג פֿאדערט HTTP POST.',
'api-error-noimageinfo'                   => 'דער אַרויפֿלאָד איז געווען דערפֿאלגרײַך, אָבער דער סערווירער האט נישט געגעבן אונדז קיין אינפֿאָרמאַציע וועגן דער טעקע.',
'api-error-nomodule'                      => 'אינערלעכער גרײַז: קיין ארויפֿלאָדן מאָדול נישט געשטעלט.',
'api-error-ok-but-empty'                  => 'אינערלעכער גרײַז: קיין ענטפֿער פֿון סערווירער.',
'api-error-overwrite'                     => 'מען טאָר נישט איבערשרײַבן אַן עקזיסטירנדע טעקע.',
'api-error-stashfailed'                   => 'אינערלעכער גרײַז: סערווירער האט נישט געקענט אײַנשפייכלערן צייַטווייַליקע טעקע.',
'api-error-timeout'                       => 'דער סערווירער האט ניט געענטפֿערט אינערהאַלב דער דערוואַרטעטער צייַט.',
'api-error-unclassified'                  => 'אַן אומבאַקאַנט טעות איז פֿארגעקומען.',
'api-error-unknown-code'                  => 'אומבאַקאַנט טעות: " $1 "',
'api-error-unknown-error'                 => 'אינערלעכער גרײַז: עפעס איז קאַליע געווארן בײַם אַרויפֿלאָדן אײַער טעקע.',
'api-error-unknown-warning'               => 'אומבאַקאַנטע ווארענונג: $1',
'api-error-unknownerror'                  => 'אומבאַקאַנט טעות: " $1 "',
'api-error-uploaddisabled'                => 'ארויפֿלאָדן איז אומאַקטיווירט אויף דער וויקי',
'api-error-verification-error'            => 'די טעקע איז מעגלעך פֿארדארבן, אדער האט א פֿאַלשע ענדונג.',

);

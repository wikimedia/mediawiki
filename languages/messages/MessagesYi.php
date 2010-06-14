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
	'DoubleRedirects'           => array( 'פארטאפלטע ווייטערפירונגען' ),
	'BrokenRedirects'           => array( 'צעבראכענע ווייטערפירונגען' ),
	'Disambiguations'           => array( 'באדייטן' ),
	'Userlogin'                 => array( 'באניצער איינלאגירן' ),
	'Userlogout'                => array( 'ארויסלאגירן' ),
	'CreateAccount'             => array( 'שאפֿן קאנטע' ),
	'Preferences'               => array( 'פרעפערענצן' ),
	'Watchlist'                 => array( 'אויפֿפאסן ליסטע', 'מיין אויפֿפאסן ליסטע' ),
	'Recentchanges'             => array( 'לעצטע ענדערונגען' ),
	'Upload'                    => array( 'ארויפלאדן' ),
	'Listfiles'                 => array( 'בילדער' ),
	'Newimages'                 => array( 'נייע בילדער' ),
	'Listusers'                 => array( 'ליסטע פון באניצערס' ),
	'Statistics'                => array( 'סטאטיסטיק' ),
	'Randompage'                => array( 'צופעליג', 'צופעליגער בלאט' ),
	'Lonelypages'               => array( 'פאר\'יתומ\'טע בלעטער' ),
	'Uncategorizedpages'        => array( 'בלעטער אן קאטעגאריעס' ),
	'Uncategorizedcategories'   => array( 'קאטעגאריעס אן קאטעגאריעס' ),
	'Uncategorizedimages'       => array( 'בילדער אן קאטעגאריעס' ),
	'Uncategorizedtemplates'    => array( 'מוסטערן אן קאטעגאריעס' ),
	'Unusedcategories'          => array( 'אומבאניצטע קאטעגאריעס' ),
	'Unusedimages'              => array( 'אומבאניצטע בילדער' ),
	'Wantedpages'               => array( 'געזוכטע בלעטער' ),
	'Wantedcategories'          => array( 'געזוכטע קאטעגאריעס' ),
	'Wantedfiles'               => array( 'געזוכטע טעקעס' ),
	'Wantedtemplates'           => array( 'געזוכטע מוסטערן' ),
	'Mostlinked'                => array( 'מערסטע פארבונדענע בלעטער' ),
	'Mostlinkedcategories'      => array( 'מערסטע פארבונדענע קאטעגאריעס' ),
	'Mostlinkedtemplates'       => array( 'מערסטע פארבונדענע מוסטערן' ),
	'Mostimages'                => array( 'מערסטע פארבונדענע בילדער' ),
	'Mostcategories'            => array( 'מערסטע קאטעגאריעס' ),
	'Mostrevisions'             => array( 'מערסטע רעוויזיעס' ),
	'Fewestrevisions'           => array( 'ווייניגסטע רעוויזיעס' ),
	'Shortpages'                => array( 'קורצע בלעטער' ),
	'Longpages'                 => array( 'לאנגע בלעטער' ),
	'Newpages'                  => array( 'נייע בלעטער' ),
	'Ancientpages'              => array( 'אוראלטע בלעטער' ),
	'Deadendpages'              => array( 'בלעטער אן פארבינדונגען' ),
	'Protectedpages'            => array( 'געשיצטע בלעטער' ),
	'Protectedtitles'           => array( 'געשיצטע קעפלעך' ),
	'Allpages'                  => array( 'אלע בלעטער' ),
	'Prefixindex'               => array( 'בלעטער וואס הייבן אין מיט' ),
	'Ipblocklist'               => array( 'בלאקירן ליסטע' ),
	'Specialpages'              => array( 'באזונדערע בלעטער' ),
	'Contributions'             => array( 'בײַשטײַערונגען' ),
	'Emailuser'                 => array( 'שיקן אן ע-פאסט צום באניצער' ),
	'Confirmemail'              => array( 'באשטעטיגן ע-פאסט' ),
	'Whatlinkshere'             => array( 'בלעטער וואס פארבונדן אהער' ),
	'Movepage'                  => array( 'באוועגן בלאט' ),
	'Blockme'                   => array( 'בלאקירט מיך' ),
	'Categories'                => array( 'קאטעגאריעס' ),
	'Export'                    => array( 'עקספארט' ),
	'Version'                   => array( 'ווערזיע' ),
	'Allmessages'               => array( 'סיסטעם מעלדונגען' ),
	'Log'                       => array( 'לאגביכער' ),
	'Blockip'                   => array( 'בלאקירן' ),
	'Import'                    => array( 'אימפארט' ),
	'Unwatchedpages'            => array( 'נישט אויפגעפאסטע בלעטער' ),
	'Listredirects'             => array( 'ווייטערפירונגען' ),
	'Unusedtemplates'           => array( 'אומבאניצטע מוסטערן' ),
	'Randomredirect'            => array( 'צופעליק ווײַטערפֿירן' ),
	'Mypage'                    => array( 'מײַן בלאט' ),
	'Mytalk'                    => array( 'מײַן שמועס בלאט' ),
	'Mycontributions'           => array( 'מיינע ביישטייערן' ),
	'Listadmins'                => array( 'ליסטע פון סיסאפן' ),
	'Listbots'                  => array( 'ליסטע פון באטס' ),
	'Popularpages'              => array( 'פאפולערע בלעטער' ),
	'Search'                    => array( 'זוכן' ),
	'Resetpass'                 => array( 'ענדערן פאסווארט' ),
	'Withoutinterwiki'          => array( 'בלעטער אָן אינטערוויקי' ),
	'Blankpage'                 => array( 'ליידיגער בלאט' ),
	'Tags'                      => array( 'טאגן' ),
	'Activeusers'               => array( 'טעטיגע באניצער' ),
);

$defaultUserOptionOverrides = array(
	# Swap sidebar to right side by default
	'quickbar' => 2,
);

$magicWords = array(
	'redirect'              => array( '0', '#ווייטערפירן', '#הפניה', '#REDIRECT' ),
	'notoc'                 => array( '0', '__קיין_אינהאלט_טאבעלע__', '__ללא_תוכן_עניינים__', '__ללא_תוכן__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__קיין_גאלעריע__', '__ללא_גלריה__', '__NOGALLERY__' ),
	'toc'                   => array( '0', '__אינהאלט__', '__תוכן_עניינים__', '__תוכן__', '__TOC__' ),
	'noeditsection'         => array( '0', '__נישט_רעדאקטירן__', '__ללא_עריכה__', '__NOEDITSECTION__' ),
	'numberofarticles'      => array( '1', 'צאל ארטיקלען', 'מספר ערכים', 'NUMBEROFARTICLES' ),
	'pagename'              => array( '1', 'בלאטנאמען', 'שם הדף', 'PAGENAME' ),
	'namespace'             => array( '1', 'נאמענטייל', 'מרחב השם', 'NAMESPACE' ),
	'fullpagename'          => array( '1', 'פולבלאטנאמען', 'שם הדף המלא', 'FULLPAGENAME' ),
	'subpagename'           => array( '1', 'אונטערבלאטנאמען', 'שם דף המשנה', 'SUBPAGENAME' ),
	'talkpagename'          => array( '1', 'רעדנבלאטנאמען', 'שם דף השיחה', 'TALKPAGENAME' ),
	'subst'                 => array( '0', 'ס:', 'SUBST:' ),
	'img_thumbnail'         => array( '1', 'קליין', 'ממוזער', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'קליין=$1', 'ממוזער=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'רעכטס', 'ימין', 'right' ),
	'img_left'              => array( '1', 'לינקס', 'שמאל', 'left' ),
	'img_none'              => array( '1', 'אן', 'ללא', 'none' ),
	'img_center'            => array( '1', 'צענטער', 'מרכז', 'center', 'centre' ),
	'img_sub'               => array( '1', 'אונטער', 'תחתי', 'sub' ),
	'grammar'               => array( '0', 'גראמאטיק:', 'דקדוק:', 'GRAMMAR:' ),
	'plural'                => array( '0', 'מערצאל:', 'רבים:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'פֿולער נאמען:', 'כתובת מלאה:', 'FULLURL:' ),
	'raw'                   => array( '0', 'רוי:', 'ללא עיבוד:', 'RAW:' ),
	'displaytitle'          => array( '1', 'ווייזן קעפל', 'כותרת תצוגה', 'DISPLAYTITLE' ),
	'language'              => array( '0', '#שפראך:', '#שפה:', '#LANGUAGE:' ),
	'defaultsort'           => array( '1', 'גרונטסארטיר:', 'מיון רגיל:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'pagesize'              => array( '1', 'בלאטגרייס', 'גודל דף', 'PAGESIZE' ),
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
'tog-rememberpassword'        => 'געדענק מיין לאגירן אין דעם קאמפיוטער',
'tog-watchcreations'          => 'לייג צו בלעטער וואס איך באשאף צו מיין אכטונג ליסטע',
'tog-watchdefault'            => 'אויפפאסן אױטאָמאַטיש די ארטיקלען װאָס איך באַאַרבעט',
'tog-watchmoves'              => 'לייג צו בלעטער וואס איך באוועג צו מיין אכטונג ליסטע',
'tog-watchdeletion'           => 'צולייגן בלעטער וואס איך מעק אויס צו מיין אויפפאסונג ליסטע',
'tog-minordefault'            => 'באגרענעצן אלע רעדאַקטירונגען גרונטלעך אלס מינערדיק',
'tog-previewontop'            => 'צײַג די "פֿאָרויסיגע װײַזונג" גלײַך בײַ דער ערשטער באַאַרבעטונג',
'tog-previewonfirst'          => 'ווייזן פֿאראויסדיגע ווייזונג בײַ דער ערשטער רעדאקטירונג',
'tog-nocache'                 => 'מבטל זיין האלטן בלעטער אין קעש זכרון',
'tog-enotifwatchlistpages'    => 'שיק מיר א בליצבריוו ווען א בלאט וואס איך פאס אויף ווערט געענדערט',
'tog-enotifusertalkpages'     => 'שיקט מיר ע-פאסט ווען עס ווערט געענדערט מיין באניצער רעדן בלאט',
'tog-enotifminoredits'        => 'שיקט מיר ע-פאסט אויך פֿאַר מינערדיקע רעדאַקטירונגען פֿון בלעטער',
'tog-enotifrevealaddr'        => 'דעק אויף מיין בליצפאסט אדרעס אין פאסט מודעות',
'tog-shownumberswatching'     => 'ווייזן דעם נומער פון בלאט אויפֿפאסערס',
'tog-oldsig'                  => 'פאראויסדיגער ווייזונג פונעם איצטיגער אונטערשריפט:',
'tog-fancysig'                => 'באַהאַנדלן  אונטערשריפט אַלס וויקיטעקסט (אָן אויטאמאטישן לינק)',
'tog-externaleditor'          => 'ניצט א דרויסנדיגן רעדאקטירער גרונטלעך (נאר פֿאר מומחים, דאס פֿאדערט באזונדערע קאמפיוטער שטעלונגען)',
'tog-externaldiff'            => 'ניצט א דרויסנדיגן פֿארגלייכער גרונטלעך (נאר פֿאר מומחים, דאס פֿאדערט באזונדערע קאמפיוטער שטעלונגען)',
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
'sunday'        => 'זונטאג',
'monday'        => 'מאָנטיג',
'tuesday'       => 'דינסטאג',
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
'march'         => 'מאַרץ',
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
'mar'           => 'מאַר׳',
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

'mainpagetext'      => "'''מעדיעוויקי אינסטאלירט מיט דערפאלג.'''",
'mainpagedocfooter' => "גיט זיך אן עצה מיט [http://meta.wikimedia.org/wiki/Help:Contents באניצער'ס וועגווײַזער] פֿאר אינפֿארמאציע וויאזוי זיך באנוצן מיט וויקי ווייכוואַרג.

== נוצליכע וועבלינקען פֿאַר אנהייבערס ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings רשימה פון קאנפֿיגוראציעס]
* [http://www.mediawiki.org/wiki/Manual:FAQ אפֿט געפֿרעגטע שאלות]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce מעדיעוויקי באפֿרײַאונג פאסטליסטע]",

'about'         => 'וועגן',
'article'       => 'אינהאלט בלאט',
'newwindow'     => '(עפֿנט זיך אין א נײַעם פענסטער)',
'cancel'        => 'זיי מבטל',
'moredotdotdot' => 'נאך…',
'mypage'        => 'מײַן בלאט',
'mytalk'        => 'מײַן שמועס',
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
'vector-action-addsection'   => 'צושטעלן טעמע',
'vector-action-delete'       => 'אויסמעקן',
'vector-action-move'         => 'באַוועגן',
'vector-action-protect'      => 'שיצן',
'vector-action-undelete'     => 'מבטל זיין אויסמעקן',
'vector-action-unprotect'    => 'אראפנעמען שיץ',
'vector-namespace-category'  => 'קאַטעגאָריע',
'vector-namespace-help'      => 'הילף בלאַט',
'vector-namespace-image'     => 'טעקע',
'vector-namespace-main'      => 'בלאַט',
'vector-namespace-media'     => 'מעדיע בלאַט',
'vector-namespace-mediawiki' => 'מודעה',
'vector-namespace-project'   => 'פּראָיעקט בלאַט',
'vector-namespace-special'   => 'באַזונדערער בלאַט',
'vector-namespace-talk'      => 'שמועס',
'vector-namespace-template'  => 'מוסטער',
'vector-namespace-user'      => 'באַניצער בלאַט',
'vector-view-create'         => 'שאַפֿן',
'vector-view-edit'           => 'רעדאַקטירן',
'vector-view-history'        => 'ווײַזן היסטאָריע',
'vector-view-view'           => 'לייענען',
'vector-view-viewsource'     => 'ווײַזן מקור',
'actions'                    => 'אַקציעס',
'namespaces'                 => 'נאָמענטיילן',
'variants'                   => 'װאַריאַנטן',

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
'info_short'        => 'אינפארמאציע',
'printableversion'  => 'ווערסיע פֿאַר פּרינטן',
'permalink'         => 'שטענדיגער לינק',
'print'             => 'דרוק',
'edit'              => 'רעדאַקטירן',
'create'            => 'שאפֿן',
'editthispage'      => 'ענדערן דעם בלאט',
'create-this-page'  => 'שאַפֿן דעם בלאַט',
'delete'            => 'אויסמעקן',
'deletethispage'    => 'מעק אויס דעם בלאט',
'undelete_short'    => 'צוריקשטעלן {{PLURAL:$1|איין רעדאַקטירונג|$1 רעדאַקטירונגען}}',
'protect'           => 'באשיצן',
'protect_change'    => 'טוישן',
'protectthispage'   => 'באשיץ דעם בלאט',
'unprotect'         => 'באַפֿרײַען',
'unprotectthispage' => 'באפריי דעם בלאט',
'newpage'           => 'נייער בלאַט',
'talkpage'          => 'שמועסט איבער דעם בלאט',
'talkpagelinktext'  => 'שמועס',
'specialpage'       => 'ספעציעלער בלאט',
'personaltools'     => 'פערזענלעכע געצייג',
'postcomment'       => 'נייע אפטיילונג',
'articlepage'       => 'זעט אינהאלט בלאט',
'talk'              => 'שמועס',
'views'             => 'קוקן',
'toolbox'           => 'געצייג קאסטן',
'userpage'          => 'זעהט באנוצער בלאט',
'projectpage'       => 'זעהט פראיעקט בלאט',
'imagepage'         => 'זען טעקע בלאט',
'mediawikipage'     => 'זעה מעסעזש בלאט',
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

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'וועגן {{SITENAME}}',
'aboutpage'            => 'Project:וועגן',
'copyright'            => 'דער אינהאַלט איז בארעכטיגט אונטער $1.',
'copyrightpage'        => '{{ns:project}}:קאפירעכטן',
'currentevents'        => 'אקטועלע געשעענישן',
'currentevents-url'    => 'Project:אקטועלע געשענישען',
'disclaimers'          => 'געזעצליכע אויפֿקלערונג',
'disclaimerpage'       => 'Project:געזעצליכע אויפֿקלערונג',
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
דאס טעות קען זיין צוליב א באג אינעם ווייכווארג.
די לעצטע דאטנבאזע זוכונג איז געווען:
<blockquote><tt>$1</tt></blockquote>
פון דער פונקציע "<tt>$2</tt>".
דאטנבאזע האט צוריקגעגעבן גרייז "<tt>$3: $4</tt>".',
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
'filecopyerror'        => 'קאפי "$1" צו "$2" איז נישט דורך.',
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
'badtitle'             => 'שלעכט קעפל',
'badtitletext'         => "דאס קעפל פון דעם געזוכטן בלאט איז געווען אומגעזעצליך, ליידיג, אן אינטערשפראך אדער אינטערוויקי לינק וואס פאסט נישט, אדער אנטהאט כאראקטערס וואס מ'קען נישט ניצן אין א קעפל.",
'perfcached'           => "די פאלגנדע דאטן זענען גענומען פונעם 'זאַפאַס' און מעגלעך נישט אקטועל.",
'perfcachedts'         => 'די פאלגנדע דאטן זענען פונעם זאַפאַס, וואס איז לעצט געווארן דערהײַנטיגט $1.',
'querypage-no-updates' => 'דערהיינטיגן דעם בלאט איז איצט אומערמעגלעכט.
דאטן דא וועט נישט דערווייל ווערן באנייט.',
'wrong_wfQuery_params' => 'די פארעמעטערס אריינגפיטערט צו wfQuery() זענען נישט ריכטיג:<br />
פֿונקיציע: $1<br />
פֿארלאנג: $2',
'viewsource'           => 'ווײַזן מקור',
'viewsourcefor'        => 'פֿאַר $1',
'actionthrottled'      => 'די אַקציע איז באַגרענעצט',
'actionthrottledtext'  => 'אלס מאָסמיטל קעגן ספאַם, זענט איר באַגרענעצט פֿון דורכפֿירן די פעולה צופֿיל מאל אין א קורצער צײַט. ביטע פרובירט נאכאַמאָל אין א פאר מינוט.',
'protectedpagetext'    => 'דער בלאט איז פארשפארט צו אפהאלטן ענדערונגן.',
'viewsourcetext'       => 'איר קענט זעהן און קאפיען די מקור פון דעם בלאט:',
'protectedinterface'   => 'דער בלאַט שטעלט צו אינטערפֿייס טעקסט פֿאַרן װײכװאַרג, און איז פֿאַרשפּאַרט כּדי צו פֿאַרמײַדן װאַנדאַליזם.',
'editinginterface'     => "'''ווארענונג:''' איר באַאַרבעט א בלאט וואס איז געניצט צוצושטעלן אינטערפֿייס טעקסט פאר דער ווייכווארג. ענדערונגען אין דעם בלאַט וועלן טוישן די אויסזען פון דער סיסטעם מודעות פאר אלע אנדערע באניצערס.
פֿאַר איבערזעצן, באַטראַכטס באַניצן [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], דער מעדיעוויקי לאקאַליזאציע פראיעקט.",
'sqlhidden'            => '(SQL פארלאנג באהאלטן)',
'cascadeprotected'     => 'דער בלאט איז פארשפארט צום ענדערן וויבאלד ער איז איינגעשלאסן אין איינע פון די פאלגנדע {{PLURAL:$1|בלאט, וואס איז|בלעטער, וואס זענען}} באשיצט מיט דער קאסקייד אפציע:

$2',
'namespaceprotected'   => "איר זענט נישט ערלויבט צו רעדאקטירן בלעטער אינעם '''$1''' נאמענטייל.",
'customcssjsprotected' => 'איר האט נישט רשות צו רעדאַקטירן דעם בלאַט, ווײַל ער אַנטהאַלט די פערזענלעכע באַשטימונגען פון אַן אַנדער באַניצער.',
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
'remembermypassword'         => 'געדיינק מיך',
'yourdomainname'             => 'דיין דאמיין:',
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
'nologinlink'                => 'באשאפֿט א קאנטע',
'createaccount'              => 'באשאפֿט א נייע קאנטע',
'gotaccount'                 => "האסט שוין א קאנטע? '''$1'''.",
'gotaccountlink'             => 'אריינלאגירן',
'createaccountmail'          => 'דורך ע-פאסט',
'badretype'                  => 'די פאסווערטער וואס איר האט אריינגעלייגט זענען נישט אייניג.',
'userexists'                 => 'דער באַנוצער נאָמען איז שוין אין באַנוץ. ביטע קלײַב אױס אַן אַנדער נאָמען.',
'loginerror'                 => 'לאגירן פֿעלער',
'createaccounterror'         => 'האט נישט געקענט שאַפֿן קאנטע: $1',
'nocookiesnew'               => 'די באניצער קאנטע איז באשאפן, אבער איר זענט נישט אריינלאגירט.
{{SITENAME}} ניצט קוקיס אריינצולאגירן באניצערס. 
איר האט קוקיס נישט-ערמעגלעכט. ביטע ערמעגלעכט זיי, דאן טוט אריינלאגירן מיט אייער באניצער נאמען און פאסווארט.',
'nocookieslogin'             => '{{SITENAME}} נוצט קוקיס צו אריינלאגירן באנוצער. דו האסט אומ-ערמעגליכט דיינע קוקיס. ביטע דערמעגליך זיי און פרובירט נאכאמאל.',
'noname'                     => 'איר האט נישט ספעציסיפירט א געזעצליכער באנוצער נאמען.',
'loginsuccesstitle'          => 'אריינלאגירונג סוסקסעספול',
'loginsuccess'               => "'''דו ביסט יעצט אַרײַנלאָגירט אַלץ \"\$1\" אינעם {{SITENAME}}.'''",
'nosuchuser'                 => 'נישטא קיין באניצער מיטן נאמען  "$1".

קוקט איבער אייער אויסלייג, אדער [[Special:UserLogin/signup|באשאפֿט א נייע קאנטע]].',
'nosuchusershort'            => 'נישטא קיין באנוצער מיטן נאמען "<nowiki>$1</nowiki>". קוק איבער דיין ספעלונג.',
'nouserspecified'            => 'איר ברויכט ספעציפיזירן א באנוצער-נאמען.',
'login-userblocked'          => 'דער באַניצער איז בלאקירט. ארײַנלאגירן נישט ערלויבט.',
'wrongpassword'              => 'אומריכטיגע פאסווארט אריינגעלייגט, ביטע פרובירט נאכאמאל.',
'wrongpasswordempty'         => 'פאסווארט אריינגעלייגט איז געווען ליידיג, ביטע פרובירט נאכאמאל.',
'passwordtooshort'           => 'פאַסווערטער מוזן זײַן כאטש {{PLURAL:$1|איין כאַראַקטער|$1 כאַראַקטערס}}.',
'password-name-match'        => 'אײַער פאַסווארט מוז זײַן אנדערש פון אײַער באַניצער נאָמען.',
'mailmypassword'             => 'שיקט מיין נייע פאסווארט',
'passwordremindertitle'      => 'ניי צייטווייליג פאסווארט פאר {{SITENAME}}',
'passwordremindertext'       => 'עמעצער (מסתמא איר, פֿון IP אדרעס $1)
האט געבעטן א ניי פאַסווארט פֿאר {{SITENAME}} ($4).
א פראוויזאריש פאַסווארט פֿאר באניצער  "$2" איז איצט "$3".
איר זאלט אריינלאגירן און אויסקלויבן א נײַ פאַסווארט.
דאס פראוויזארישע פאַסווארט וועט אויסגיין נאָך {{PLURAL:$5|איין טאג|$5 טעג}} 

אויב איינער אנדערשט האט געמאכט די ביטע, אדער איר האט יא געדענקט אייער פאסווארט און איר טוט מער נישט באגערן דאס צו טוישן, קענט איר איגנארירן די מעלדונג און ווייטער ניצן אייער אלטע פאַסווארט.',
'noemail'                    => 'ס\'איז נישט רעקארדירט קיין אי-מעיל אדרעס פאר באנוצער  "$1".',
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
'noemailprefs'               => 'ספעציפיר אן אי-מעיל אדרעס פאר די פיטשערס צו ארבייטן.',
'emailconfirmlink'           => 'באשטעטיגט דיין אייער ע-פאסט אדרעס',
'invalidemailaddress'        => 'דער ע-פאסט אדרעס קען נישט אקצעפטירט ווערן ווייל ער שיינט צו האבן אן אומגילטיגן פֿארמאט. 
ביטע אריינלייגן א גוט-פארמאטירטן אדרעס אדער ליידיגט אויס די פעלד.',
'accountcreated'             => 'די קאָנטע איז באַשאַפֿן',
'accountcreatedtext'         => 'די באניצער קאנטע פאר $1 איז באַשאַפֿן געווארן.',
'createaccount-title'        => 'קאנטע באשאפֿן אין {{SITENAME}}',
'createaccount-text'         => 'עמעצער האט באשאפֿן א קאנטע פֿאר אייער ע-פאסט אדרעס אין {{SITENAME}} ($4) מיטן נאמען "$2" און  פאסווארט "$3". איר דארפט אצינד איינלאגירן און ענדערן דאס פאסווארט.

איר קענט איגנארירן די מעלדונג, ווען די קאנטע איז באשאפֿן בטעות.',
'usernamehasherror'          => 'באַניצער נאמען טאָר נישט אַנטהאַלטן קיין לייטער סימבאל',
'login-throttled'            => 'איר האט געפרוווט צופֿיל מאל אריינלאגירן.
זייט אזוי גוט און וואַרט איידער איר פרוווט נאכאמאל.',
'loginlanguagelabel'         => 'שפראך: $1',
'suspicious-userlogout'      => ' אײַער בקשה אַרויסלאָגירן זיך איז אפגעלייגט געווארן ווייַל אייגנטלעך איז זי געשיקט דורך אַ צעבראכענעם בלעטערער אָדער א פראקסי מיט א זאפאס.',

# Password reset dialog
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

# Edit page toolbar
'bold_sample'     => 'טייפּט דאָ אַריין די טעקסט זאל זיין דיק',
'bold_tip'        => "דאס טוישט צו '''בּאָלד (דיק)''' דאס אויסגעוועלטע ווארט.",
'italic_sample'   => "דאס וועט מאכן ''שיף'' די אויסגעוועלט ווארט.",
'italic_tip'      => "דאס וועט מאכן ''שיף'' די אויסגעוועלט פאנט.",
'link_sample'     => 'שרײַבט דאָ אַרײַן די װערטער װאָס װעט זײַן אַ לינק צו {{SITENAME}} אַרטיקל אין דעם נושא',
'link_tip'        => "מאך דאס א '''לינק''' צו א וויקיפעדיע ארטיקל",
'extlink_sample'  => 'http://www.example.com לינק טיטל',
'extlink_tip'     => 'דערויסענדיגע לינק (געדענק http:// פרעפיקס)',
'headline_sample' => 'קעפּל',
'headline_tip'    => 'קעפּל -2טער שטאפל',
'math_sample'     => 'לייגט אריין פארמל דא',
'math_tip'        => 'מאטעמאטישע פארמל (LaTeX)',
'nowiki_sample'   => 'אינסערט נישט-פארמארטירטע טעקסט דא',
'nowiki_tip'      => 'דאָס וועט איגנאָרירן די וויקי פֿאָרמאַטינג קאָוד',
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
'missingsummary'                   => "'''דערמאנונג:''' איר האט נישט אויסגעפילט דעם קורץ ווארט אויפקלערונג אויף אייער עדיט. אויב וועט איר דרוקן נאכאמאל אויף  \"היט אפ דעם בלאט\", וועט אייער ענדערונג ווערן געהיטן אן דעם.",
'missingcommenttext'               => 'ביטע שטעלט אריין א אנמערקונג פון אונטן.',
'missingcommentheader'             => "'''דערמאנונג:''' איר האט נישט אריינגעשטעלט א טעמע/קעפל פאר דעם אנמערקונג. אויב וועט איר דרוקן נאכאמאל אויפן \"היט-אפ בלאט\", וועט אייער ענדערונג ווערן אפגעהיטן אן דעם.",
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
'blockedoriginalsource'            => "די טעקסט פון מקור פון '''$1''' ווערט געוויזן אונטן:",
'blockededitsource'                => "די טעקסט פון '''אייערע ענדערונגן''' צו '''$1''' ווערט געוויזן אונטן:",
'whitelistedittitle'               => 'אַרײַנלאגירן פֿאַרלאַנגט צו ענדערן',
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
כדי שאַפֿן דעם בלאַט, קלאַפט אַרײַן דעם טעקסט אין דעם קעסטל אונטן (זעט דעם [[{{MediaWiki:Helppage}}|הילף בלאַט]] פֿאַר מער אינפֿארמאַציע).
אויב איר זענט אַהערגעקומען בטעות, דרוקט דעם '''Back''' קנעפל אין אײַער בלעטערער.",
'anontalkpagetext'                 => "----'''דאס איז א רעדן בלאט פון א אן אנאנימען באַניצער וואס האט נאך נישט באַשאַפֿן קיין קאנטע, אדער באניצט זיך נישט דערמיט. דערוועגן, מוזן מיר זיך באניצן מיט זיין IP אדרעס כדי אים צו אידענטיפיצירן. עס קען זיין אז עטלעכע אנדערע ניצן אויך דעם  IP אדרעס. אויב זענט איר אן אנאנימער באַניצער וואס שפירט אז איר האט באקומען מעלדונגען וואס זענען נישט שייך צו אייך, ביטע [[Special:UserLogin/signup|באַשאַפֿט א קאנטע]] אדער [[Special:UserLogin|טוט זיך אריינלאגירן]] כדי צו פארמיידן דאס אין די עתיד זיך פארמישן מיט אנדערע אנאנימע באַניצערס.'''",
'noarticletext'                    => 'דערווייל איז נישט פאַרהאן קיין שום טעקסט אין דעם בלאט.
איר קענט [[Special:Search/{{PAGENAME}}|זוכן דעם בלאט טיטל]] אין אנדערע בלעטער,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} זוכן די רעלעוואנטע לאגביכער],
אדער [{{fullurl:{{FULLPAGENAME}}|action=edit}} רעדאַקטירן דעם בלאט].',
'noarticletext-nopermission'       => 'דערווײַל איז נישט פאַראַן קיין שום טעקסט אין דעם בלאַט.
איר קענט [[Special:Search/{{PAGENAME}}| זוכן דעם בלאט טיטל]] אין אנדערע בלעטער,
אדער <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} נאָכזוכן די רעלעוואנטע לאגביכער]</span>.',
'userpage-userdoesnotexist'        => 'באניצער קאנטע "$1" איז נישט אײַנגעשריבן. קוקט איבער צי איר ווילט שאפֿן/רעדאקטירן דעם בלאט.',
'userpage-userdoesnotexist-view'   => 'באניצער קאנטע "$1" איז נישט איינגעשריבן.',
'blocked-notice-logextract'        => 'דער באַניצער איז דערווייַל פֿאַרשפאַרט.
די לעצטע בלאָקירן לאג אַקציע איז צוגעשטעלט אונטן:',
'clearyourcache'                   => "'''אכטונג: נאכן אויפֿהיטן, ברויכט איר אפשר נאך אריבערגיין דעם בלעטערס קאש זיכרון (cache) צו זען די ענדערונגען.'''

'''מאזילא/סאפֿארי/פֿייערפוקס:''' האלט אראפ ''שיפֿט'' בשעתן דרוקן ''Reload'', אדער דרוקט ''Ctrl-F5'' אדער ''Ctrl-R'' (אויף א מאקינטאש ''Cmd-R'');

'''קאנקעראר''': קליקט ''Reload'' אדער דרוקט ''F5''

'''אינטערנעט עקספלארער''': האלט ''Ctrl'' בשעתן קליקן ''Refresh'', אדער  דרוקט ''Ctrl-F5'';

'''אפערע:''' מען ליידיגט אויס דעם קאש אין ''Tools → Preferences'' (''העדפות'' > ''כלים'')",
'usercssyoucanpreview'             => "'''טיפ:''' נוצט דאס {{int:showpreview}} קנעפל אויספרובירן אייער  CSS בעפאר אפהיטן.",
'userjsyoucanpreview'              => "'''טיפ:''' נוצט דאס {{int:showpreview}} קנעפל אויספרובירן אייער  JavaScript בעפאר אפהיטן.",
'usercsspreview'                   => "'''געדענקט אז איר טוט בלויז פאראויס זען אייער באניצער CSS.'''
'''ער איז דערווייל נאכנישט אויפֿגעהיטן!'''",
'userjspreview'                    => "'''געדענקט אז איר טוט בלויז טעסטן\\פאראויסזעהן אייער באנוצער JavaScript, עס איז דערווייל נאכנישט אפגעהיטן!'''",
'userinvalidcssjstitle'            => "'''ווארענונג:''' סאיז נישטא קיין סקין \"\$1\". גדענקט אז קאסטעם .css און .js בלעטער נוצען לאוער קעיס טיטול, e.g. {{ns:user}}:Foo/monobook.css ווי אנדערשט צו {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(דערהיינטיגט)',
'note'                             => "'''באמערקונג:'''",
'previewnote'                      => "'''דאס איז נאָר אין אַ פֿאָרויסיקע ווייזונג, דער אַרטיקל איז דערווייל נאָך נישט געהיט!'''",
'previewconflict'                  => 'די פאראויסדיגע ווייזונג רעפלעקטירט די טעקסט און די אויבערשטע טעקסט ענדערונג אפטיילונג וויאזוי דאס וועט אויסזעהן אויב וועט איר אויסוועילן צו דאס אפהיטן.',
'session_fail_preview'             => "'''אנטשולדיגט! מען האט נישט געקענט פראצעסירן אייער ענדערונג צוליב א פארלוסט פון סעסיע דאטע. ביטע פרובירט נאכאמאל. אויב ס'ארבעט נאך אלס ניט, פרובירט [[Special:UserLogout|ארויסלאגירן]] און זיך צוריק אריינלאגירן.",
'session_fail_preview_html'        => "''''''אַנטשולדיקט! מיר קענען נישט פּראָצעסירן אײַער ענדערונג צוליב א פֿאַרלוסט פֿון סעסיע דאַטע.''''''

''װײַל די װיקי האט רױע HTML ערמעגליכט, דער פֿאָרױסיקער װײַזונג איז באַאַלטן אַלס אַ באַװאָרענונג אַנטקעגן JavaScript אַטאַקירונגען.''

'''אױב דאַס איז אַ כשרע רעדאַקציע פרוּװ, פּרובירט נאָכאַמאָל. אױב דאָס גײט נאָכאַלץ ניט, פּרובירט [[Special:UserLogout|ארױסלאָגירן]] און װידער אַרײַנלאָגירן. '''",
'token_suffix_mismatch'            => "'''אייער רעדאקטירונג איז געווארן אפגעווארפן ווייל אייער בראוזער האט אפגעווארפן די נקודות ביים רעדאקטירן.'''
די ענדערונג איז געווארן אפגעווארפן כדי נישט צו אנמאכן א חורבן אין די טעקסט פונעם בלאט.
דאס געשענט מייסטענס ווען איר נוצט אן אנאניאמער פראקסי סערווער.",
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
'copyrightwarning'                 => "<small>ביטע מערקט אויף אז אייערע אלע ביישטייערונגען אינעם '''{{SITENAME}}''' ערשיינען אונטער דעם $2 דערלויבן (מער פרטים זעה $1). אויב איר וויִלט נישט לאזן אנדערע ענדערן אייערע ביישטייערונגען און פארשפרייטן אייער ארבעט - ביטע שרייבט זיי נישט דא.<br />
איר זאגט צו אז איר האט געשריבן אן אייגענעם אינהאַלט, אדער האט איר באקומען ערלויבעניש זיי דא צו שרייבן.</small>",
'copyrightwarning2'                => "'''אכטונג:''' אנדערע באניצערס קענען מעקן און ענדערן אייערע ביישטייערונגען צו {{SITENAME}}. 
אויב ווילט איר נישט  אז אייער ארבעט זאל זיין הפקר פאר אנדערע דאס צו באארבעטן – פארשפרייט זי נישט דא. 

אזוי אויך, זאגט איר צו אז איר האט דאס געשריבן אליין, אדער דאס איבערקאפירט פון א מקור מיט פולן רשות דאס מפקיר זיין (זעט $1 פאר מער פרטים). 
'''זיכט נישט באניצן מיט שטאף וואס איז באשיצט מיט קאפירעכטן!'''",
'longpagewarning'                  => "'''ווארענונג: דער בלאט איז לאנג $1 קילאבייטן; געוויסע בלעטערערס קענען מעגלעך האבן פראבלעמען צו רעדאקטירן בלעטער וואס גרייכן אדער זענען לענגער פֿון 32kb.
ביטע באטראכט איינטיילן דעם בלאט צו קלענערע אפטיילונגען.'''",
'longpageerror'                    => "'''פעילער: די טעקסט וואס איר האט אריינגעשטעלט איז $1 קילאבייטס לאנג, וואס איז לענגער פון די מאקסימום פון $2 קילאבייטס. עס קען נישט ווערן אפגעהיטן.'''",
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
'nocreatetext'                     => 'די סייט האט באגרעניצט די מעגליכקייט צו באשאפן נייע בלעטער.
איר קענט צוריקגיין און ענדערן די עקזיסטירנדע בלאט, אדער [[Special:UserLogin|לאגירט זיך אריין און באשאפט א קאנטע]].',
'nocreate-loggedin'                => 'איר זענט נישט ערלויבט צו שאַפֿן נײַע בלעטער.',
'sectioneditnotsupported-title'    => 'רעדאקטירן אפטיילונגען נישט געשטיצט.',
'sectioneditnotsupported-text'     => 'רעדאַקטירן אָפטיילונגען נישט געשטיצט אויף דעם בלאַט',
'permissionserrors'                => 'ערלויבענישן פעילערס',
'permissionserrorstext'            => 'איר זענט נישט ערלויבט צו טון דאס, פֿאַר  {{PLURAL:$1|דער פֿאלגנדער סיבה|די פֿאלגנדע סיבות}}:',
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
'undo-failure' => 'די ענדערונג קען נישט ווערן אומ-געטאן צוליב קאנפליקטינג אינטערמידיעט ענדערונגן.',
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
'previousrevision'       => '→ עלטערער ווערסיע',
'nextrevision'           => 'נייע ווערסיע ←',
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
'history-feed-empty'          => 'דער געבעטענער בלאט עקזעסטירט נישט.
עס איז מעגליך אויסגעמעקט געווארן פון די וויקי, אדער די נאמען געטוישט.
פרובירט [[Special:Search|צו זיכן אין וויקי]] נאך רעלאווענטע נייע בלעטער.',

# Revision deletion
'rev-deleted-comment'         => '(אנמערקונג אראפגענומען)',
'rev-deleted-user'            => '(באנוצער נאמען אראפגענומען)',
'rev-deleted-event'           => '(לאגירן אקציע אראפגענומען)',
'rev-deleted-user-contribs'   => '[באַניצער נאָמען אָדער IP אַדרעס אראפגענומען - רעדאַקטירונג פֿאַרבאָרגן פֿון בייַשטייַערונגען]',
'rev-deleted-text-permission' => "די בלאט רעוויזיע איז געווארן '''אויסגעמעקט '''. 
עס איז מעגלעך דא נאך פרטים אין דעם
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} אויסמעקונג לאג].",
'rev-deleted-text-unhide'     => "די בלאט רעוויזיע איז געווארן '''אויסגעמעקט '''. 
עס איז מעגלעך דא נאך פרטים אין דעם
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} אויסמעקונג לאג].
אלס סיסאפ קענט איר נאך  [$1 באקוקן די רעוויזיע] אויב איר ווילט גיין ווײַטער.",
'rev-suppressed-text-unhide'  => "די בלאט רעוויזיע איז געווארן '''באהאלטן'''. 
עס איז מעגלעך דא נאך פרטים אין דעם [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} באהעלטעניש לאג].
אלס סיסאפ קענט איר נאך  [$1 באקוקן די רעוויזיע] אויב איר ווילט גיין ווײַטער.",
'rev-deleted-text-view'       => "די בלאט רעוויזיע איז געווארן '''אויסגעמעקט '''. 
אלס סיסאפ קענט איר זען זי;
עס איז מעגלעך דא נאך פרטים אין דעם [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} אויסמעקונג לאג].",
'rev-suppressed-text-view'    => "די בלאט רעוויזיע איז געווארן '''באהאלטן '''. 
אלס סיסאפ קענט איר זען זי;
עס איז מעגלעך דא נאך פרטים אין דעם [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} באהעלטעניש לאג].",
'rev-delundel'                => 'ווייז/באהאלט',
'rev-showdeleted'             => 'ווײַזן',
'revisiondelete'              => 'אויסמעקן\\צוריקשטעלן רעוויזיעס',
'revdelete-nooldid-title'     => 'ציל ווערסיע נישט גילטיג',
'revdelete-nooldid-text'      => 'איר האט נישט ספעציפירט קיין ציל ווערסיע דורצוכפירן די פונקציע.',
'revdelete-nologtype-title'   => 'קיין לאג טיפ נישט געקליבן',
'revdelete-nologtype-text'    => 'איר האט נישט ספעציפֿירט קיין לאג טיפ דורצוכפֿירן די פֿונקציע.',
'revdelete-nologid-title'     => 'אומגילטיגער לאג־פֿאַרשרײַב',
'revdelete-no-file'           => 'די טעקע ספעציפֿירט עקזיסטירט נישט.',
'revdelete-show-file-confirm' => 'צי זענט איר זעכער איר ווילט באַקוקן אן אויסגעמעקטע רעוויזיע פון דער טעקע "<nowiki>$1</nowiki>" פון $2 בשעה $3?',
'revdelete-show-file-submit'  => 'יא',
'revdelete-selected'          => "'''{{PLURAL:$2|אויסדערוויילטע ווערסיע| אויסדערוויילטע ווערסיעס}} פון [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1| אויסדערוויילטע לאג אקציע|אויסדערוויילטע לאג אקציעס}}:'''",
'revdelete-suppress-text'     => "באהאלטן זאל בלויז גענוצט ווערן '''נאר''' אין די פאלגענדע פעלער:
* אויפדעקונג פון פריוואטקייט אינפארמאציע
* ''היים אדרעסן, טעלעפאן נומערן, אדער סאשעל סעקיורעטי, א.א.וו.:'''",
'revdelete-legend'            => 'שטעלט ווייזונג באגרענעצונגען',
'revdelete-hide-text'         => 'באהאלט אינהאלט פון ווערסיע',
'revdelete-hide-image'        => 'באהאלט טעקע אינהאלט',
'revdelete-hide-name'         => 'באהאלט אקציע און ציל',
'revdelete-hide-comment'      => 'באהאלט ענדערן הערה',
'revdelete-hide-user'         => "באהאלט רעדאקטער'ס באנוצער-נאמען/איי.פי.",
'revdelete-hide-restricted'   => 'באהאלט אינפארמאציע אויך פון אדמיניסטראטורן פונקט ווי פשוטע באנוצער',
'revdelete-radio-same'        => '(נישט ענדערן)',
'revdelete-radio-set'         => 'יא',
'revdelete-radio-unset'       => 'ניין',
'revdelete-suppress'          => 'באַהאַלטן אינפֿארמאַציע פון אַדמיניסטראַטארן ווי אויך אנדערע',
'revdelete-unsuppress'        => 'טוה אפ באגרענעצונגן אין גענדערטע רעוויזיעס',
'revdelete-log'               => 'סיבה פארן אויסמעקן:',
'revdelete-submit'            => 'צושטעלן צו {{PLURAL:$1|סעלעקטירטער רעוויזיע| סעלעקטירטע רעוויזיעס}}',
'revdelete-logentry'          => 'געענדרט רעוויזיע זעבארקייט פון [[$1]]',
'logdelete-logentry'          => 'געענדרט פאסירונג זעבארקייט פון [[$1]]',
'revdelete-success'           => "'''רעוויזיע זעבאַרקייט דערפֿאלגרייך דערהײַנטיקט.'''",
'revdelete-failure'           => "'''נישט מעגלעך צו דערהײַנטיקן רעוויזיע זעבאַרקייט:'''
$1",
'logdelete-success'           => "'''לאג באהאלטן איז סוקסעספול איינגעשטעלט.'''",
'logdelete-failure'           => "'''נישט מעגלעך צו שטעלן לאג זעבאַרקייט:'''
$1",
'revdel-restore'              => 'טויש די זעבארקייט',
'pagehist'                    => 'בלאט היסטאריע',
'deletedhist'                 => 'אויסגעמעקטע ווערסיעס',
'revdelete-content'           => 'אינהאלט',
'revdelete-summary'           => 'רעדאקטירונג קיצור',
'revdelete-uname'             => 'באניצער נאמען',
'revdelete-restricted'        => 'פארמערט באגרעניצונגען פאר סיסאפן',
'revdelete-unrestricted'      => 'אוועקגענומען באגרעניצונגען פאר סיסאפן',
'revdelete-hid'               => 'באהאלטן $1',
'revdelete-unhid'             => 'מבטל געווען באהאלטן $1',
'revdelete-log-message'       => '$1 פֿאר  {{PLURAL:$2|איין רעוויזיע|$2 רעוויזיעס}}',
'logdelete-log-message'       => '$1 פֿאר {{PLURAL:$2|איין פאסירונג|$2 פאסירונגען}}',
'revdelete-modify-missing'    => 'פֿעלער בײַ מאדיפֿיצירן  דעם איינס ID $1: ער פֿעלט פֿון דער דאַטנבאַזע the database!',
'revdelete-only-restricted'   => 'פֿעלער בײַם באַהאַלטן דאס איינסל פֿון  $2, $1: איר קענט נישט באהאלטן פרטים פון אַדמיניסטראטורן נאר אויב איר וויילט אויס איינע פון די אַנדערע באַהאַלטן ברירות.',
'revdelete-reason-dropdown'   => '*אלגעמיינע אויסמעקן סיבות
** קאפירעכט ברעכן
** פערזענלעכע אינפֿארמאציע
** אינפארמאציע מעגלעך צו זיין לשון הרע',
'revdelete-otherreason'       => 'אנדער/צוגעגעבענע סיבה:',
'revdelete-reasonotherlist'   => 'אנדער סיבה',
'revdelete-edit-reasonlist'   => 'רעדאַקטירן אויסמעקן סיבות',
'revdelete-offender'          => 'רעוויזיע מחבר:',

# Suppression log
'suppressionlog' => 'באהאלטונגען לאג',

# History merging
'mergehistory'                     => 'צונויפֿגיסן בלאט היסטאריעס',
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
'lineno'                   => 'שורה $1:',
'compareselectedversions'  => 'פארגלייך סעלעקטירטע ווערסיעס',
'showhideselectedversions' => 'ווײַזן/באַהאַלטן געקליבענע רעוויזיעס',
'editundo'                 => 'אַנולירן',
'diff-multi'               => '({{PLURAL:$1|איין מיטלסטע ווערסיע |$1 מיטלסטע ווערסיעס}}  נישט געוויזן.)',

# Search results
'searchresults'                    => 'זוכן רעזולטאטן',
'searchresults-title'              => 'זוכן רעזולטאַטן פֿאַר "$1"',
'searchresulttext'                 => 'לערנען מער ווי צו זוכן אין {{SITENAME}}, זעט  [[{{MediaWiki:Helppage}}|{{int:help}}]]',
'searchsubtitle'                   => 'איר האט געזוכט \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|אלע בלעטער וואס הייבן אן "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|אלע בלעטער וואס פֿאַרבינדן צו "$1"]])',
'searchsubtitleinvalid'            => "'''$1''' איר האט געזוכט",
'toomanymatches'                   => 'צו פֿיל רעזולטאַטן, ביטע פרואווט אן אנדער זוך',
'titlematches'                     => 'בלאט קעפל שטימט',
'notitlematches'                   => 'קיין שום בלאט האט נישט א צוגעפאסט קעפל',
'textmatches'                      => 'בלעטער מיט פאסענדע אינהאלט',
'notextmatches'                    => 'נישטא קיין בלעטער מיט פאסענדע אינהאלט',
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

# Quickbar
'qbsettings'               => 'גיכפאַס',
'qbsettings-none'          => 'גארנישט',
'qbsettings-fixedleft'     => 'קבוע לינקס',
'qbsettings-fixedright'    => 'קבוע רעכטס',
'qbsettings-floatingleft'  => 'שווימנדיג לינקס',
'qbsettings-floatingright' => 'שווימנדיג רעכטס',

# Preferences page
'preferences'                   => 'פרעפֿערענצן',
'mypreferences'                 => 'פּרעפֿערענצן',
'prefs-edits'                   => 'צאָל ענדערונגען:',
'prefsnologin'                  => 'נישט אריינלאגירט',
'prefsnologintext'              => 'איר דארפט זיין  <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} אריינלאגירט]</span> כדי צו ענדערן באניצער פרעפֿערענצן.',
'changepassword'                => 'טוישן פאַסווארט',
'prefs-skin'                    => 'סקין',
'skin-preview'                  => 'פארויסדיגע ווייזונג',
'prefs-math'                    => 'פאָרמאַל',
'datedefault'                   => 'נישטא קיין פרעפערענץ',
'prefs-datetime'                => 'דאטום און צייט',
'prefs-personal'                => 'באַנוצער פראָפֿיל',
'prefs-rc'                      => 'לעצטע ענדערונגען',
'prefs-watchlist'               => 'אויפפאסונג ליסטע',
'prefs-watchlist-days'          => 'טעג צו ווייזן אין דער אויפפאסונג ליסטע:',
'prefs-watchlist-days-max'      => '(מאקסימום 7 טעג)',
'prefs-watchlist-edits'         => 'מאַקסימום נומער פון נײַע ענדערונגען צו ווייַזן אין פֿאַרברייטערטער אויפֿפאַסונג ליסטע:',
'prefs-watchlist-edits-max'     => '(מאקסימום צאל: 1000)',
'prefs-watchlist-token'         => 'אויפֿפאַסונג ליסטע סימן:',
'prefs-misc'                    => 'פֿאַרשידנס',
'prefs-resetpass'               => 'טוישן פאַסווארט',
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
'contextlines'                  => 'שורות פער רעזולטאט',
'contextchars'                  => 'קאנטעקסט פער שורה',
'stub-threshold'                => 'שוועל פֿאַר <a href="#" class="stub">שטומף לינק</a> פֿאָרמאַטירונג (בייטן):',
'recentchangesdays'             => 'צאל פון טעג צו ווייזן אין די לעצטע ענדערונגן:',
'recentchangesdays-max'         => 'מאַקסימום $1 {{PLURAL:$1|טאָג|טעג}}',
'recentchangescount'            => 'די צאָל רעדאַקטירונגען צו ווײַזן גרונטלעך:',
'prefs-help-recentchangescount' => 'כולל לעצטע ענדערונגען, בלאַט היסטאָריעס, און לאָגביכער.',
'savedprefs'                    => 'אייערע פרעפערענצן איז אפגעהיטן געווארן.',
'timezonelegend'                => 'צײַט זאנע:',
'localtime'                     => 'לאקאלע צייט:',
'timezoneuseserverdefault'      => 'ניצן סערווירער גרונט',
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
'prefs-searchoptions'           => 'ברירות פאר זוכן',
'prefs-namespaces'              => 'נאָמענטיילן',
'defaultns'                     => 'אנדערשט זוך אין די נאמענטיילן:',
'default'                       => 'גרונטלעך',
'prefs-files'                   => 'טעקעס',
'prefs-custom-css'              => 'באַניצער דעפֿינירט CSS',
'prefs-custom-js'               => 'באַניצער דעפֿינירט JS',
'prefs-common-css-js'           => 'שותפֿותדיקער CSS/JS פֿאַר אַלע אויספֿארמירונגען:',
'prefs-reset-intro'             => 'איר קענט ניצן דעם בלאַט צוריקצושטעלן אײַערע פרעפֿערענצן גרונטלעך פֿאַרן ארט.  to reset your preferences to the site defaults.
מען קען דאָס נישט אַנולירן.',
'prefs-emailconfirm-label'      => 'ע-פאסט באַשטעטיקונג:',
'prefs-textboxsize'             => 'גרייס פֿון רעדאַקטירונג פֿענסטער',
'youremail'                     => 'ע-פאסט:',
'username'                      => 'באַנוצער־נאָמען:',
'uid'                           => 'באַנוצער־נומער:',
'prefs-memberingroups'          => 'מיטגליד אין {{PLURAL:$1|גרופע|גרופעס}}:',
'prefs-registration'            => 'אײַנשרײַבן צײַט:',
'yourrealname'                  => 'עכטער נאמען *:',
'yourlanguage'                  => 'שפּראַך:',
'yourvariant'                   => 'װאַריאַנט',
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
'prefs-help-email'              => 'ע-פאסט אדרעס איז ברירהדיק, אבער עס דערמעגליכט אז מען קען אייך שיקן א ניי פאסווארט טאמער איר פֿארגעסט דאס אלטע.
איר קענט אויך לאזן אנדערע צו קאנטאקטן אייך דורך אייער באניצער אדער באניצער רעדן בלאט אן ארויסגעבן אייער אידענטיטעט.',
'prefs-help-email-required'     => 'בליצפאסט אדרעס באדארפט.',
'prefs-info'                    => 'גרונטלעכע אינפֿארמאַציע',
'prefs-i18n'                    => 'אינטערנאַציאנאַליזאַציע',
'prefs-signature'               => 'אונטערשריפֿט',
'prefs-dateformat'              => 'דאַטע פֿארמאַט',
'prefs-timeoffset'              => 'צײַט אונטערשייד',
'prefs-advancedediting'         => 'אדוואנסירטע אפציעס',
'prefs-advancedrc'              => 'אדוואנסירטע אפציעס',
'prefs-advancedrendering'       => 'אדוואנסירטע אפציעס',
'prefs-advancedsearchoptions'   => 'אדוואנסירטע אפציעס',
'prefs-advancedwatchlist'       => 'אדוואנסירטע אפציעס',
'prefs-display'                 => 'ווייז אפציעס',
'prefs-diffs'                   => 'צווישנשיידן',

# User rights
'userrights'                   => 'באנוצער רעכטן פארוואלטערשאפט',
'userrights-lookup-user'       => 'פֿאַרוואַלטן באניצער גרופעס',
'userrights-user-editname'     => 'לייגט אריין א באנוצער-נאמען:',
'editusergroup'                => 'עדיט באנוצער גרופעס',
'editinguser'                  => "ענדערן באניצער רעכטן פון באניצער user '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'רעדאַקטירן באַניצער גרופעס',
'saveusergroups'               => 'אָפהיטן באַניצער גרופעס',
'userrights-groupsmember'      => 'מיטגליד פון:',
'userrights-groupsmember-auto' => 'אויטאמטישער מיטגליד פֿון:',
'userrights-groups-help'       => 'איר מעגט ענדערן די גרופעס צו וועמען דער באַניצער געהערט:
*א מאַרקירט קעסטל באַדײַט אָז דער באַניצער איז א מיטגליד אין דער גרופע.
* אַן אוממאַרקירט קעסטל באַדײַט אָז דער באַניצער איז נישט קיין מיטגליד אין דער גרופע.
* א * ווײַזט אַז איר קענט נישט אַראפנעמען די גרופע נאָך דעם וואָט איר האט זי צוגעלייגט, אדער פֿאַרקערט.',
'userrights-reason'            => 'סיבה:',
'userrights-no-interwiki'      => 'איר האט נישט קיין ערלויבניש צו רעדאַקטירן באַניצער רעכטן אויף אַנדערע וויקיס.',
'userrights-nodatabase'        => 'דאַטנבאַזע $1 אדער עקזיסטירט נישט אדער איז נישט ארטיק.',
'userrights-nologin'           => 'איר דאַרפֿט [[Special:UserLogin| אַרײַנלאגירן]] מיט א סיסאפ קאנטע צו באַשטימען באַניצער רעכטן.',
'userrights-notallowed'        => 'אייער קאנטע האט נישט קיין ערלויבניש צו באשטימען באניצער רעכטן.',
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

'group-user-member'          => 'באניצער',
'group-autoconfirmed-member' => 'באַשטעטיקטער באַניצער',
'group-bot-member'           => 'באט',
'group-sysop-member'         => 'סיסאפ',
'group-bureaucrat-member'    => 'ביוראקראט',
'group-suppress-member'      => 'אויפֿזעער',

'grouppage-user'          => '{{ns:project}}:אײַנגעשריבענער באניצער',
'grouppage-autoconfirmed' => '{{ns:project}}:אויטאבאַשטעטיגטע באַניצער',
'grouppage-bot'           => '{{ns:project}}:באטס',
'grouppage-sysop'         => '{{ns:project}}:אדמיניסטראטורן',
'grouppage-bureaucrat'    => '{{ns:project}}:ביראקראט',
'grouppage-suppress'      => '{{ns:project}}:אויפֿזעער',

# Rights
'right-read'                 => 'ליינען בלעטער',
'right-edit'                 => 'רעדאקטירן בלעטער',
'right-createpage'           => 'שאַפֿן בלעטער (וואָס זענען נישט שמועס בלעטער)',
'right-createtalk'           => 'שאַפֿן שמועס בלעטער',
'right-createaccount'        => 'שאַפֿן נײַע באַניצער קאנטעס',
'right-minoredit'            => 'צייכן רעדאקטירונגען אלס  מינערדיק',
'right-move'                 => 'באוועג בלעטער',
'right-move-subpages'        => 'באַוועגן בלעטער מיט זייערע אונטערבלעטער',
'right-move-rootuserpages'   => 'באַוועגן באַניצער הויפטבלעטער',
'right-movefile'             => 'באַוועגן טעקעס',
'right-suppressredirect'     => 'נישט שאַפֿן א ווײַטערפֿירונג פֿונעם אַלטן בלאַט בײַם באַוועגן אַ בלאַט',
'right-upload'               => 'ארויפלאדן טעקעס',
'right-reupload'             => 'איבערשרײַבן עקסיסטירנדע טעקע',
'right-upload_by_url'        => 'ארויפֿלאָדן טעקעס פֿון אַ URL',
'right-autoconfirmed'        => 'רעדאקטירן האלב-געשיצטע בלעטער',
'right-delete'               => 'מעקן בלעטער',
'right-bigdelete'            => 'אויסמעקן בלעטער מיט לאַנגע היסטאריעס',
'right-deleterevision'       => 'מעקן און צוריקשטעלן ספעציפישע רעוויזיעס פון בלעטער',
'right-deletedhistory'       => 'אײַערע אויסגעמעקטע היסטאריע פֿאַרשרײַבונגען, אן זייער אסאציאירטן טעקסט',
'right-deletedtext'          => 'באַקוקן אויסגעמעקטן טעקסט און ענדערונגען צווישן אויסגעמעקטע ווערסיעס',
'right-browsearchive'        => 'זוכן אויסגעמעקטע בלעטער',
'right-undelete'             => 'צוריקשטעלן א בלאט',
'right-suppressrevision'     => 'קוק-איבער און דריי-צוריק רעוויזיעס באהאלטן פון אדימיניסטראטורן',
'right-suppressionlog'       => 'זעה פריוואטע לאגס',
'right-block'                => 'בלאקירן אַנדערע באַניצער פֿון רעדאַקטירן',
'right-blockemail'           => 'בלאקירן א באַניצער פֿון שיקן ע־פאסט',
'right-editinterface'        => 'רעדאַקטירן די באַניצער אייבערפֿלאַך',
'right-editusercssjs'        => 'רעדאַקטירן אַנדערע באַניצערס CSS און JS טעקעס',
'right-editusercss'          => 'רעדאַקטירן אַנדערע באַניצערס CSS טעקעס',
'right-edituserjs'           => 'רעדאַקטירן אַנדערע באַניצערס JS טעקעס',
'right-rollback'             => 'גיך צוריקדרייען די רעדאַקטירונגען פונעם לעצטן באַניצער וואס האט רעדאַקטירט א געוויסן בלאַט',
'right-import'               => 'אימפארטירן בלעטער פון אנדערע וויקיס',
'right-importupload'         => 'אימפארטירן בלעטער דורך ארויפֿלאָדן טעקע',
'right-patrol'               => 'צייכנען די רעדאַקטירונגען פֿון אַנדערע ווי פאַטראלירט',
'right-patrolmarks'          => 'באַקוקן לעצטע ענדערונגען פּאַטראָל מאַרקירונגען',
'right-unwatchedpages'       => 'באַקוקן די ליסטע פֿון נישט אויפֿגעפאַסטע בלעטער',
'right-mergehistory'         => 'צונויפֿגיסן די היסטאריע פֿון בלעטער',
'right-userrights'           => 'רעדאַקטירן אלע באַניצער רעכטן',
'right-userrights-interwiki' => 'רעדאַקטירן באַניצער רעכטן פֿון באַניצער אויף אנדערע וויקיס',
'right-siteadmin'            => 'פארשליס און שליס-אויף די דאטעבאזע',
'right-reset-passwords'      => 'צוריקשטעלן אַנדערע באַניצערס פאַסווערטער',
'right-sendemail'            => 'שיקן ע-פאסט צו אנדערע באניצער',

# User rights log
'rightslog'      => 'באַניצער רעכטן לאג',
'rightslogtext'  => 'דאָס איז אַ לאג פֿון ענדערונגען צו באַניצער רעכטן.',
'rightslogentry' => 'געביטן די מיטגלידערשאַפֿט פֿאַר $1 פֿון $2 אויף $3',
'rightsnone'     => '(גארנישט)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'ליינען דעם בלאַט',
'action-edit'                 => 'רעדאקטירן דעם בלאַט',
'action-createpage'           => 'שאַפֿן בלעטער',
'action-createtalk'           => 'שאַפֿן שמועס בלעטער',
'action-createaccount'        => 'שאַפֿן די באַניצער קאנטע',
'action-minoredit'            => 'באַצייכנען די רעדאַקטירונג ווי מינערדיק',
'action-move'                 => 'באַוועגן דעם בלאַט',
'action-move-subpages'        => 'באַוועגן דעם בלאַט מיט זײַנע אונטערבלעטער',
'action-move-rootuserpages'   => 'באַוועגן באַניצער הויפטבלעטער',
'action-movefile'             => 'באַוועגן די טעקע',
'action-upload'               => 'אַרויפֿלאָדן די טעקע',
'action-reupload'             => 'איבערשרײַבן די עקזיסטירנדע טעקע',
'action-upload_by_url'        => 'ארויפֿלאָדן די טעקע פֿון א URL',
'action-writeapi'             => 'ניצן דעם שרײַבן API',
'action-delete'               => 'אויסמעקן דעם בלאַט',
'action-deleterevision'       => 'אויסמעקן די רעוויזיע',
'action-deletedhistory'       => "באַקוקן דעם בלאט'ס אויסגעמעקטע היסטאריע",
'action-browsearchive'        => 'זוכן אויסגעמעקטע בלעטער',
'action-undelete'             => 'צוריקשטעלן דעם בלאט',
'action-suppressionlog'       => 'באקוקן דעם פריוואטן לאג',
'action-block'                => 'בלאקירן דעם באַניצער פֿון רעדאַקטירן',
'action-protect'              => 'ענדערן שיצונג ניוואען פֿאַר דעם בלאַט',
'action-import'               => 'אימפארטירן דעם בלאַט פֿון אַן אַנדער וויקי',
'action-importupload'         => 'אימפארטירן דעם בלאַט דורך ארויפֿלאָדן אַ טעקע',
'action-patrol'               => "אנצייכענען אנדערס' רעדאקטירונגן אלס נאכגעקוקט",
'action-autopatrol'           => 'אנצוצייכענען אייערע רעדאקטירונגן אלס איבערגעקוקטע',
'action-unwatchedpages'       => 'זעה די ליסטע פון נישט אויפגעפאסטע בלעטער',
'action-mergehistory'         => 'צונויפֿגיסן די היסטאריע פֿון דעם בלאַט',
'action-userrights'           => 'רעדאַקטירן אלע באַניצער רעכטן',
'action-userrights-interwiki' => 'רעדאַקטירן רעכטן פון באַניצער אויף אַנדערע וויקיס',
'action-siteadmin'            => 'שליסן אדער אויפשליסן די דאטנבאזע',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|ענדערונג|$1 ענדערונגען}}',
'recentchanges'                     => 'לעצטע ענדערונגען',
'recentchanges-legend'              => 'ברירות פאר לעצטע ענדערונגען',
'recentchangestext'                 => 'גיי נאך די לעצטע ענדערונגען צו דער וויקי אויף דעם בלאט.',
'recentchanges-feed-description'    => 'גייט נאך די לעצטע ענדערונגען צו דער וויקי אין דעם בלאט.',
'recentchanges-label-legend'        => 'לעגענדע: $1.',
'recentchanges-legend-newpage'      => '$1 - נײַער בלאַט',
'recentchanges-label-newpage'       => 'די רעדאַקטירונג האט באשאפֿן א נײַעם בלאַט',
'recentchanges-legend-minor'        => '$1 - מינערדיקע רעדאַקטירונג',
'recentchanges-label-minor'         => 'דאָס איז אַ מינערדיקע רעדאַקטירונג',
'recentchanges-legend-bot'          => '$1 - באט רעדאַקטירונג',
'recentchanges-label-bot'           => ' די רעדאַקטירונג האט אויסגעפירט א באט',
'recentchanges-legend-unpatrolled'  => '$1 - אומפאַטראלירטע רעדאַקטירונג',
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
'newsectionsummary'                 => '/* $1 */ נייע אפטיילונג',
'rc-enhanced-expand'                => 'צייג דעטאלען (פארלאנגט זיך JavaScript)',
'rc-enhanced-hide'                  => 'באהאלט דעטאלן',

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
'upload'                => 'אַרױפֿלאָדן בילדער/טעקעס',
'uploadbtn'             => 'אַרױפֿלאָדן טעקע',
'upload-tryagain'       => 'פֿאָרלייגן מאדיפֿיצירטע טעקע באַשרײַבונג',
'uploadnologin'         => 'נישט אַרײַנלאגירט',
'uploadnologintext'     => 'איר מוזט זײַן [[Special:UserLogin| אַרײַנלאָָגירט]] כדי ארויפֿצולאָדן טעקעס',
'uploaderror'           => 'אַרויפֿלאָדן פֿעלער',
'uploadtext'            => "באניצט דעם פֿארעם אַרויפֿצולאָדן טעקעס.
כדי צו זען אדער זוכן טעקעס וואס זענען שוין אַרויפֿגעלאָדן ווענדט זיך צו דער [[Special:FileList|ליסטע פֿון אַרויפֿגעלאָדענע טעקעס]]; (ווידער)אַרויפֿלאָדונגען ווערן אויך לאגירט אינעם  [[Special:Log/upload| אַרויפֿלאָדן לאג-בוך]], אויסמעקונגען אינעם [[Special:Log/delete|אויסמעקן לאג-בוך]].

כדי אײַנשליסן א טעקע אין א בלאַט, באניצט א לינק אין איינעם פון די פֿאלגנדע פֿארעמען:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' צו ניצן די פֿולע ווערסיע פֿון דער טעקע
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|טעקסט קעפל]]</nowiki></tt>''' צו ניצן א 200 פיקסל ברייט ווערסיע אין א קעסטל אויף דער לינקער זײַט, מיט דער שילדערונג 'טעקסט קעפל'
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' פֿאר א גראָדער פֿאַרבינדונג צו דער טעקע אָן צו ווײַזן זי",
'upload-permitted'      => 'ערלויבטע טעקע טיפן: $1.',
'upload-preferred'      => 'פרעפֿרירטע טעקע טיפן: $1.',
'upload-prohibited'     => 'פֿאַרווערענע טעקע טיפן: $1.',
'uploadlog'             => 'ארויפלאָדן לאָגבוך',
'uploadlogpage'         => 'ארויפֿלאדן לאג',
'uploadlogpagetext'     => 'פֿאָלגנד איז אַ ליסטע פֿון די לעצטע אַרױפֿגעלאָדענע טעקעס.
זעט די  [[Special:NewFiles|גאלאריע פֿון נײַע טעקעס]] פֿאַר א מער וויזועלע איבערבליק.',
'filename'              => 'טעקע נאמען',
'filedesc'              => 'רעזומע',
'fileuploadsummary'     => 'רעזומע:',
'filereuploadsummary'   => 'טעקע ענדערונגען:',
'filestatus'            => 'קאפירעכט סטאַטוס:',
'filesource'            => 'מקור:',
'uploadedfiles'         => 'ארויפֿגעלאדעטע טעקעס',
'ignorewarning'         => 'איגנאָרירן ווארענונג און אויפֿהיטן טעקע סיי ווי סיי',
'ignorewarnings'        => 'איגנארירן וואָרענונגען',
'minlength1'            => 'א טעקע נאמען מוז האבן כאטש איין אות.',
'illegalfilename'       => 'דער טעקענאָמען "$1" אַנטהאַלט כאַראַקטערס וואָס זענען נישט ערלויבט אין בלאַט טיטלען.
ביטע געבן א נײַעם נאמען דער טעקע און פּרובירט ארויפֿלאָדן נאכאַמאָל.',
'badfilename'           => 'טעקע נאמען איז געטוישט צו "$1".',
'filetype-missing'      => 'די טעקע האט נישט קיין פארברייטערונג (למשל ".jpg").',
'large-file'            => 'רעקאמענדירט אז טעקעס זאל נישט זײַן גרעסער פֿון$1;
די טעקע איז $2.',
'emptyfile'             => 'די טעקע וואס איר האט ארויפֿלגעלאָדן איז ליידיג.
עס קען זיין אז די סיבה איז פשוט א טייפא. 
ביטע קוקט איבער צי איר ווילט ארויפֿלאדן  די דאזיקע טעקע.',
'fileexists'            => "א טעקע מיט דעם נאָמען עקזיסטירט שוין, ביטע זײַט בודק '''<tt>[[:$1]]</tt>''' ווען איר זענט נישט זיכער אַז איר ווילט זי ענדערן.
[[$1|thumb]]",
'file-exists-duplicate' => 'די טעקע איז א דופליקאַט פון די פֿאלגנדע {{PLURAL:$1|טעקע|טעקעס}}:',
'successfulupload'      => 'דערפֿאלגרייכער ארויפֿלאָד',
'uploadwarning'         => 'אַרויפֿלאָדן וואָרענונג',
'uploadwarning-text'    => 'זײַט אַזוי גוט מאדיפֿיצירן די טעקע באַשרייבונג און פרובירט נאכאַמאָל.',
'savefile'              => 'טעקע אױפֿהיטן',
'uploadedimage'         => 'אַרױפֿגעלאָדן "[[$1]]"',
'overwroteimage'        => 'אַרויפֿגעלאָדן א נײַע ווערסיע פון "[[$1]]"',
'uploaddisabled'        => 'אַרויפֿלאָדן טעקעס מבוטל',
'uploaddisabledtext'    => 'אַרויפֿלאָדן טעקעס נישט דערמעגלעכט אצינד.',
'uploadscripted'        => 'די טעקע האט א סקריפט אדער HTML קאד וואס קען ווערן פֿאלש אויסגעטייטשט דורך א בלעטערער',
'uploadvirus'           => 'די טעקע האָט אַ ווירוס! פרטים: <div style="direction:rtl;">$1</div>',
'upload-source'         => 'מקור טעקע',
'sourcefilename'        => 'מקור טעקע נאמען:',
'sourceurl'             => 'מקור URL:',
'destfilename'          => 'ציל טעקע נאמען:',
'upload-maxfilesize'    => 'מאַקסימום טעקע גרייס: $1',
'upload-description'    => 'טעקע שילדערונג',
'upload-options'        => "אַרויפֿלאָדן ברירה'ס",
'watchthisupload'       => 'אויפֿפאַסן דעם בלאט',

'upload-proto-error'        => 'פאלשער פראטאקאל',
'upload-file-error'         => 'אינערליכער פעלער',
'upload-misc-error'         => 'אומבאַוואוסטער ארויפֿלאָדן גרײַז',
'upload-too-many-redirects' => 'דער URL אַנטהאַלט צופֿיל ווײַטערפֿירונגען.',
'upload-unknown-size'       => 'אומוויסנדע גרייס',
'upload-http-error'         => 'א HTTP גרײַז האט פאַסירט: $1',

# img_auth script messages
'img-auth-accessdenied' => 'צוטריט אָפגעזאָגט',
'img-auth-nologinnWL'   => 'איר זענט נישט ארײַנלאגירט און "$1" איז נישט אין דער ווײַסער ליסטע.',
'img-auth-nofile'       => 'טעקע "$1" עקזיסטירט נישט.',
'img-auth-streaming'    => 'שטראָמען $1.',

# HTTP errors
'http-invalid-url'      => 'אומגילטיג URL: $1',
'http-curl-error'       => 'גרײַז בײַם ברענגען URL: $1',
'http-host-unreachable' => "מ'קען נישט דערגרייכן דעם URL",
'http-bad-status'       => "ס'איז געווען א פראבלעם ביים HTTP פֿאַרלאַנג: $1 $2",

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => "מ'קען נישט דערגרייכן URL",

'license'            => 'ליצענץ:',
'license-header'     => 'ליצענץ:',
'nolicense'          => 'גארנישט',
'license-nopreview'  => '(פֿאראויסקוק נישט פֿאַראַן)',
'upload_source_file' => '(א טעקע אויף אײַער קאמפיוטער)',

# Special:ListFiles
'listfiles_search_for'  => 'זוכן פֿאַר מעדיע נאָמען:',
'imgfile'               => 'טעקע',
'listfiles'             => 'טעקע ליסטע',
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
'imagelinks'                        => 'פֿאַרבינדונגען צום בילד',
'linkstoimage'                      => '{{PLURAL:$1|דער פאלגנדער בלאט ניצט|די פאלגנדע בלעטער ניצן}} דאס דאזיגע בילד:',
'linkstoimage-more'                 => "מער ווי $1 {{PLURAL:$1|בלאַט פֿאַרבינדט|בלעטער פֿאַרבינדן}} צו דער דאזיגער טעקע.
די פֿאלגנדע ליסטע ווײַזט  {{PLURAL:$1|דעם ערשטן בלאַט לינק|די ערשטע $1 בלאַט לינקען}} צו דער טעקע.
ס'איז פֿאַראַן[[Special:WhatLinksHere/$2|פֿולע רשימה]].",
'nolinkstoimage'                    => 'נישטא קיין בלעטער וואס ניצן דאס דאזיגע בילד.',
'morelinkstoimage'                  => 'באַקוקן  [[Special:WhatLinksHere/$1|מער לינקען]] צו דער טעקע.',
'redirectstofile'                   => 'די פֿאלגנדע {{PLURAL:$1|טעקע פֿירט אריבער|$1 טעקעס פֿירן אריבער}} צו דער דאזיגער טעקע:',
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

# File deletion
'filedelete'                  => 'מעק אויס $1',
'filedelete-legend'           => 'מעק אויס טעקע',
'filedelete-intro'            => "איר האַלט בײַ אויסמעקן די טעקע '''[[Media:$1|$1]]''' צוזאַמען מיט גאָר איר היסטאריע.",
'filedelete-intro-old'        => "איר מעקט אויס די ווערסיע פֿון  '''[[Media:$1|$1]]''' פֿון [$4 $3, $2].",
'filedelete-comment'          => "פארוואס מ'האט געמעקט:",
'filedelete-submit'           => 'אויסמעקן',
'filedelete-success'          => "'''$1''' איז געווען אויסגעמעקט.",
'filedelete-success-old'      => "די ווערסיע פֿון '''[[Media:$1|$1]]''' פֿון $3, $2 איז געווארן אויסגעמעקט.",
'filedelete-nofile'           => "'''$1''' עקזיסטירט נישט.",
'filedelete-otherreason'      => 'אנדער/נאך א סיבה:',
'filedelete-reason-otherlist' => 'אַנדער אורזאַך',
'filedelete-reason-dropdown'  => '*אַלגעמיינע אויסמעקן סיבות
** קאפירעכט פֿאַרלעצונג
** דופליקאַט',
'filedelete-edit-reasonlist'  => 'רעדאַקטירן אויסמעקן סיבות',

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
'statistics-views-peredit'     => 'צאל קוקן צו א רעדאַקטירונג',
'statistics-users'             => 'איינגעשריבענע [[Special:ListUsers|באניצערס]]',
'statistics-users-active'      => 'טעטיקע באניצערס',
'statistics-users-active-desc' => 'באניצערס וואס האבן דורכגעפירט א פעולה אין די לעצטע {{PLURAL:$1|טאג|$1 טעג}}',
'statistics-mostpopular'       => 'מערסטע געזען בלעטער',

'disambiguations'      => 'באדייטן בלעטער',
'disambiguationspage'  => 'Template:באדייטן',
'disambiguations-text' => "די קומענדיגע בלעטער פארבינדען צו א '''באדייטן בלאט'''. זיי ברויכן ענדערשט פֿארבינדן צו דער רעלעוואנטער טעמע בלאט.<br />א בלאט ווערט פאררעכענט אלס א בלאט ווערט גערעכנט פאר א באדײַטן בלאט אויב ער באניצט זיך מיט א מוסטער וואס איז פארבינדען פון [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'געטאפלטע ווײַטערפֿירונגען',
'doubleredirectstext'        => 'דער בלאט רעכנט אויס בלעטער וואס פירן ווייטער צו אנדערע ווייטערפירן בלעטער.
יעדע שורה אנטהאלט א לינק צום ערשטן און צווייטן ווייטערפירונג, ווי אויך די ציל פון דער צווייטער ווייטערפירונג, וואס רוב מאל געפינט זיך די ריכטיגע ציל וואו די ערשטע ווייטערפירונג זאל ווייזן.
<s>אויסגעשטראכענע</s> טעמעס זענען שוין געלייזט.',
'double-redirect-fixed-move' => '[[$1]] איז געווארן באוועגט, און איז יעצט א ווייטערפֿירונג צו [[$2]]',
'double-redirect-fixer'      => 'מתקן ווײַטערפֿירונגען',

'brokenredirects'        => 'צעבראָכענע ווײַטערפֿירונגען',
'brokenredirectstext'    => 'די פֿאלגנדע ווײַטערפֿירונגען פֿאַרבינדן צו בלעטער וואס עקזיסטירן נאך נישט:',
'brokenredirects-edit'   => 'ענדערן',
'brokenredirects-delete' => 'אויסמעקן',

'withoutinterwiki'         => 'בלעטער אן שפראך פֿארבינדונגען',
'withoutinterwiki-summary' => 'די פֿאלגנדע בלעטער פֿאַרבינדן נישט מיט אַנדערע שפראַך ווערסיעס',
'withoutinterwiki-legend'  => 'פרעפֿיקס',
'withoutinterwiki-submit'  => 'ווײַז',

'fewestrevisions' => 'בלעטער מיט די מינדערסטע רעוויזיעס',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|בייט|בייטן}}',
'ncategories'             => '{{PLURAL:$1|קאטעגאריע|$1 קאטעגאריעס}}',
'nlinks'                  => '$1 {{PLURAL:$1|לינק|לינקען}}',
'nmembers'                => '$1 {{PLURAL:$1|בלאט|בלעטער}}',
'nrevisions'              => '{{PLURAL:$1|איין רעוויזיע|$1 רעוויזיעס}}',
'nviews'                  => '{{PLURAL:$1|איין קוק|$1 קוקן}}',
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
'wantedtemplates'         => 'געזוכטע מוסטערן',
'mostlinked'              => 'מערסט פֿארבינדענע בלעטער',
'mostlinkedcategories'    => 'מערסט פֿארבינדענע קאטעגאריעס',
'mostlinkedtemplates'     => 'מערסט פֿארבינדענע מוסטערן',
'mostcategories'          => 'אַרטיקלען מיט די מערקסטע קאַטעגאָריעס',
'mostimages'              => 'מערסט פֿארבונדענע טעקעס',
'mostrevisions'           => 'אַרטיקלען מיט די מערסטע באַאַרבעטונגען',
'prefixindex'             => 'פּרעפֿיקס אינדעקס',
'shortpages'              => 'קורצע בלעטער',
'longpages'               => 'לאנגע בלעטער',
'deadendpages'            => 'בלינדע בלעטער',
'deadendpagestext'        => 'די פאלגנדע בלעטער לינקען נישט צו קיין אנדערע בלעטער אין דער וויקי.',
'protectedpages'          => 'געשיצטע בלעטער',
'protectedpages-indef'    => 'שטענדיגע באשוצינגן בלויז',
'protectedpagestext'      => 'די פֿאלגנדע בלעטער זענען געשיצט פון רעדאַקטירן און באוועגן:',
'protectedpagesempty'     => 'אצינד זענען קיין בלעטער נישט געשיצט מיט די דאזיגע פאַראַמעטערס.',
'protectedtitles'         => 'געשיצטע קעפלעך',
'protectedtitlestext'     => 'די פֿאלגנדע קעפלעך זענען געשיצט פון באשאפֿן:',
'protectedtitlesempty'    => 'אצינד זענען קיין קעפלעך נישט באַשיצט מיט די דאזיגע פאַראַמעטערס.',
'listusers'               => 'ליסטע פון באניצערס',
'listusers-editsonly'     => 'ווייזן נאר באניצערס מיט רעדאקטירונגען',
'listusers-creationsort'  => 'סארטירן לויט דער שאַפן דאַטע',
'usereditcount'           => '{{PLURAL:$1|רעדאַקטירונג|$1 רעדאַקטירונגען}}',
'usercreated'             => 'געשאַפֿן אום $2, $1',
'newpages'                => 'נייע בלעטער',
'newpages-username'       => 'באניצער נאמען:',
'ancientpages'            => 'עלטסטע בלעטער',
'move'                    => 'באַװעגן',
'movethispage'            => 'באוועג דעם בלאט',
'unusedcategoriestext'    => 'די פֿאלגנדע קאטעגאריעס עקסיסטירן, אבער קיין בלאט ניצט זיי נישט.',
'notargettitle'           => 'נישטא קיין ציל',
'notargettext'            => 'איר האט נישט ספעציפֿירט קיין ציל בלאַט אדער באַניצער אויף וועמען אויסצופֿירן די פעולה.',
'nopagetitle'             => 'נישטא אזא ציל בלאט',
'nopagetext'              => 'דער ציל בלאט וואס איר האט ספעציפֿירט עקזיסטירט נישט.',
'pager-newer-n'           => '{{PLURAL:$1|נײַערע|$1 נײַערע}}',
'pager-older-n'           => '{{PLURAL:$1|עלטערע|$1 עלטערע}}',
'suppress'                => 'אויפֿזען',

# Book sources
'booksources'               => 'דרויסנדיגע ליטעראַטור ISBN',
'booksources-search-legend' => 'זוכן פאר דרויסנדע ביכער מקורות',
'booksources-go'            => 'גיין',
'booksources-text'          => 'אונטן איז א ליסטע פון סייטס וואס פֿארקויפֿן נייע און גענוצטע ביכער און האבן אויך נאך אינפֿארמאציע וועגן די ביכער וואס איר זוכט:',

# Special:Log
'specialloguserlabel'  => 'באַניצער:',
'speciallogtitlelabel' => 'קעפל:',
'log'                  => 'לאג-ביכער',
'all-logs-page'        => 'אלע פובליק לאגס',
'alllogstext'          => 'קאמבינירטער אויסשטעל פון אלע לאגס פון {{SITENAME}}.
מען קען פֿאַרשמעלרן די אויסוואל דורך אויסוועלן דעם סארט לאג, באַניצער נאמען אדער אנרירנדע בלעטער.',
'logempty'             => 'נישטא קיין ענדליכע זאכן אין דעם לאג.',
'log-title-wildcard'   => 'זוך טיטלען וואס הייבן אָן מיט דעם טעקסט',

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
'linksearch'      => 'דרויסנדע לינקען',
'linksearch-ns'   => 'נאמענטייל:',
'linksearch-ok'   => 'זוכן',
'linksearch-line' => '$1 פֿאַרבונדן פֿון $2',

# Special:ListUsers
'listusersfrom'      => 'ווײַזן באניצער אנהייבנדיג פון:',
'listusers-submit'   => 'ווײַז',
'listusers-noresult' => 'קיין באניצער נישט געטראפֿן.',
'listusers-blocked'  => '(בלאקירט)',

# Special:ActiveUsers
'activeusers-hidebots'   => 'באַהאַלטן באטן',
'activeusers-hidesysops' => 'באַהאַלטן סיסאפן',
'activeusers-noresult'   => 'קיין באניצער נישט געטראפֿן.',

# Special:Log/newusers
'newuserlogpage'              => 'נייע באַניצערס לאָג-בוך',
'newuserlogpagetext'          => 'דאס איז א לאג פון באַניצערס אײַנשרײַבונגען.',
'newuserlog-byemail'          => 'פאַסווארט געשיקט דורך ע-פאסט',
'newuserlog-create-entry'     => 'נײַער באניצער',
'newuserlog-create2-entry'    => 'געשאפֿן נײַע קאנטע $1',
'newuserlog-autocreate-entry' => 'קאנטע באַשאַפֿן אויטאמאַטיש',

# Special:ListGroupRights
'listgrouprights'                      => 'באַניצער גרופע רעכטן',
'listgrouprights-summary'              => "פֿאלגנד איז א רשימה פֿון באַניצער גרופעס דעפֿינירט אויף דער דאָזיקער וויקי, מיט זײַערע אַסאציאירטע צוטריט רעכטן.
ס'קען זײַן  [[{{MediaWiki:Listgrouprights-helppage}}|מער אינפֿארמאַציע]] וועגן איינציקע רעכטן.",
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
'emailuser'            => 'אַרויסשיקן ע-פאסט צו דעם באַניצער',
'emailpage'            => 'אַרויסשיקן ע-פאסט צו באַניצער.',
'emailpagetext'        => 'איר קענט ניצן דעם פֿארעם אונטן צו שיקן אן בליצבריוו צו דעם דאזיגן באַניצער.
דער ע-פאסט אדרעס וואס איר האט אריינגעלייגט אין [[Special:Preferences| אייערע באניצער פרעפערנעצן]] וועט זיך ווייזן כאילו דאס איז געקומען פון דארטן, בכדי צו דערמעגלעכן א תשובה.',
'usermailererror'      => 'בליצבריוו האט צוריקגעשיקט א טעות:',
'defemailsubject'      => 'ע-פאסט {{SITENAME}}',
'usermaildisabledtext' => 'איר קענט נישט שיקן ע־פאסט צו אנדערע באַניצערס אויף דער דאָזיקער וויקי',
'noemailtitle'         => 'נישטא קיין אי-מעיל אדרעס',
'noemailtext'          => 'דער באַניצער האט נישט באשטימט קיין גילטיקן ע-פאסט אדרעס.',
'nowikiemailtitle'     => 'קיין ע-פאסט דערלויבט',
'nowikiemailtext'      => 'דער באַניצער האט געקליבן נישט באַקומען ע־פאסט פֿון אַנדערע באַניצער.',
'email-legend'         => 'אַרויסשיקן ע-פאסט צו אַן אַנדער {{SITENAME}} באַניצער',
'emailfrom'            => 'פון',
'emailto'              => 'צו',
'emailsubject'         => 'טעמע:',
'emailmessage'         => 'מעלדונג:',
'emailsend'            => 'שיק',
'emailccme'            => 'שיק מיר דורך ע־פאסט א קאפיע פֿון מיין מעלדונג.',
'emailccsubject'       => 'קאפי פון דיין מעסעדזש צו $1: $2',
'emailsent'            => 'ע-פאסט געשיקט',
'emailsenttext'        => 'דיין אי-מעיל מעסעדזש איז געשיקט געווארן.',
'emailuserfooter'      => 'דער בליצבריוו איז געשיקט געווארן דורך$1 צו $2 מיט דער  "שיקן בליצבריוו"  פֿונקציע בײַ {{SITENAME}}.',

# Watchlist
'watchlist'            => 'מיין אויפפַּאסונג ליסטע',
'mywatchlist'          => 'מיין אויפפַּאסונג ליסטע',
'watchlistfor'         => "(פאר '''$1''')",
'nowatchlist'          => 'איר האט נישט קיין שום בלעטער אין אייער אויפפַּאסונג ליסטע.',
'watchlistanontext'    => 'ביטע $1 כדי צו זען אדער ענדערן בלעטער אין אייער אַכטגעבן ליסטע.',
'watchnologin'         => 'איר זענט נישט אַרײַנלאגירט',
'watchnologintext'     => 'איר מוזט זיין אריינגעסיינט [[Special:UserLogin|אריינגלאגירט]] צו מאדיפֿיצירן אייער אויפפַּאסן ליסטע.',
'addedwatch'           => 'דער בלאַט איז צוגעלייגט געוואָרן צו דער אויפֿפאַסונג ליסטע',
'addedwatchtext'       => "דער בלאט \"[[:\$1]]\" איז צוגעלײגט געוואָרן צו אײַער [[Special:Watchlist|אויפֿפאַסונג ליסטע]].

ענדערונגען צו דעם בלאַט און צו זײַן פארבינדענעם רעדן בלאַט וועלן זײַן אויסגערעכענט דא.
און דער בלאט וועט זיין '''דיק''' אין דער [[Special:RecentChanges|ליסטע פון לעצטע ענדערונגען]] צו גרינגער מאכן דאס אויפֿפאַסן.",
'removedwatch'         => 'אַראָפּגענומען געווארן פון דער אויפֿפאַסונג ליסטע',
'removedwatchtext'     => 'דער בלאַט "[[:$1]]" איז אָפּגעראַמט געוואָרן פון [[Special:Watchlist|אייער אױפֿפּאַסונג ליסטע]].',
'watch'                => 'אױפֿפּאַסן',
'watchthispage'        => 'טוט אױפֿפּאַסן דעם בלאט',
'unwatch'              => 'אויפֿהערן אויפֿפּאַסן',
'unwatchthispage'      => 'ענדיגן אויפֿפאַסן',
'notanarticle'         => 'דאס איז נישט קיין אינהאלט בלאט',
'notvisiblerev'        => 'די באארבעטונג איז געווארן אויסגעמעקט',
'watchnochange'        => 'קיינע פֿון אײַערע אויפֿגעפאַסטע בלעטער זענען באַאַרבעט געווארן אין דעם צײַט פעריאד געוויזן.',
'watchlist-details'    => '{{PLURAL:$1|איין בלאט|$1 בלעטער}} אין אייער אויפֿפאסן ליסטע (נישט רעכענען  רעדן בלעטער).',
'wlheader-enotif'      => '* ע-פאסט מעלדונג ערמעגליכט.',
'wlheader-showupdated' => "* בלעטער געענדערט זײַט אײַער לעצטן וויזיט זען געוויזן '''דיק'''",
'watchmethod-recent'   => 'קאנטראלירן לעצטע ענדערונגען פֿאַר אויפֿגעפאַסטע בלעטער',
'watchmethod-list'     => 'קאנטראלירן בלעטער אין אַכטגעבן־ליסטע פֿאַר נײַע רעדאַקטירונגען',
'watchlistcontains'    => 'אייער אויפֿפאסונג ליסטע אנטהאלט {{PLURAL:$1|איין בלאט|$1 בלעטער}}.',
'iteminvalidname'      => "פּראָבלעם מיט '$1', אומגילטיקער נאָמען ...",
'wlnote'               => "אונטן {{PLURAL:$1|איז די לעצטע ענדערונג|זענען די לעצטע '''$1''' ענדערונגען}} אין {{PLURAL:$2|דער לעצטער שעה|די לעצטע '''$2''' שעה'ן}}.",
'wlshowlast'           => "(ווײַזן די לעצטע $1 שעה'ן | $2 טעג | $3)",
'watchlist-options'    => 'אויפֿפאַסן ליסטע ברירות',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'אויפפאסענדונג…',
'unwatching' => 'נעמט אראפ פון אויפפאסונג ליסטע…',

'enotif_mailer'                => 'נאטיפאקאציע שיקער {{SITENAME}}',
'enotif_reset'                 => 'באצייכן אלע בלעטער שוין געזעהן',
'enotif_newpagetext'           => 'דאס איז א נייער בלאט.',
'enotif_impersonal_salutation' => 'באנוצער {{SITENAME}}',
'changed'                      => 'געטוישט',
'created'                      => 'באשאפן',
'enotif_subject'               => 'דער בלאט $PAGETITLE אין {{grammar:תחילית|{{SITENAME}}}} $CHANGEDORCREATED דורך $PAGEEDITOR',
'enotif_lastvisited'           => 'זעה $1 פאר אלע ענדערונגען זינט אייער לעצטער וויזיט.',
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
{{fullurl:{{#special:Watchlist}}/edit}}

כדי אויסמעקן דעם בלאט פון אײַער אויפֿפאַסונג ליסטע, באַזוכט
$UNWATCHURL

פאר מער הילף:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'מעק אויס בלאט',
'confirm'                => 'באַשטעטיגן',
'excontent'              => 'אינהאלט געווען: "$1"',
'excontentauthor'        => "אינהאלט געווען: '$1' (און דער איינציגסטער בארבייטער איז געווען '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => 'אינהאַלט בעפֿאַרן אויסליידיגן איז געווען: "$1"',
'exblank'                => 'בלאט איז געווען ליידיג',
'delete-confirm'         => 'אויסמעקן $1',
'delete-backlink'        => '→ $1',
'delete-legend'          => 'אויסמעקן',
'historywarning'         => 'אכטונג – איר גייט אויסמעקן א בלאט וואָס האט א היסטאריע מיט $1 {{PLURAL:$1|ווערסיע|ווערסיעס}}:',
'confirmdeletetext'      => 'איר גייט איצט אויסמעקן א בלאט צוזאַמען מיט זײַן גאנצע היסטאריע.

ביטע באשטעטיגט אז דאס איז טאקע אייער כוונה, אז איר פארשטייט פולערהייט די קאנסקווענסן פון דעם אַקט, און אז דאס איז אין איינקלאנג מיט [[{{MediaWiki:Policy-url}}|דער פאליסי]].',
'actioncomplete'         => 'די אַקציע אָט זיך דורכגעפֿירט',
'actionfailed'           => 'אקציע דורכגעפאלן',
'deletedtext'            => '"<nowiki>$1</nowiki>" אויסגעמעקט. 
זעט $2 פֿאַר א רשימה פֿון לעצטיגע אויסמעקונגען.',
'deletedarticle'         => 'אויסגעמעקט "[[$1]]"',
'suppressedarticle'      => 'באַהאַלטן "[[$1]]"',
'dellogpage'             => 'אויסמעקונג לאג',
'dellogpagetext'         => 'ווייטער איז א ליסטע פון די מערסט לעצטיגע אויסמעקונגען.',
'deletionlog'            => 'אויסמעקונג לאג',
'reverted'               => 'צוריקגעשטעלט צו פֿריערדיקער באַאַרבעטונג',
'deletecomment'          => 'סיבה פארן אויסמעקן:',
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
'rollback'         => 'צוריקדרייען רעדאַקטירונגען',
'rollback_short'   => 'צוריקדרייען',
'rollbacklink'     => 'צוריקדרייען',
'rollbackfailed'   => 'צוריקדרייען דורכגעפֿאַלן',
'cantrollback'     => 'מען קען נישט צוריקדרייען די ענדערונג – דער לעצטער בײַשטייערער איז דער איינציגסטער שרײַבער אין דעם בלאַט.',
'alreadyrolled'    => 'מען קען נישט צוריקדרייען די לעצטע ענדערונג פון בלאט [[:$1]] פֿון
[[User:$2|$2]] ([[User talk:$2|רעדן]]{{int:pipe-separator}} [[Special:Contributions/$2|{{int:contribslink}}]]); 
אן אנדערער האט שוין געענדערט אדער צוריקגעדרייט דעם בלאט.

די לעצטע ענדערונג צום בלאַט איז געווען פון [[User:$3|$3]] ([[User talk:$3|רעדן]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'      => "קורץ ווארט איז געווען: \"'''\$1'''\".",
'revertpage'       => 'רעדאַקטירונגען פֿון  [[Special:Contributions/$2|$2]] צוריקגענומען ([[User talk:$2|רעדן]])  צו דער לעצטער ווערסיע פֿון [[User:$1|$1]]',
'rollback-success' => 'צוריקגעדרייט רעדאַקטירונגען פֿון $1 צו דער לעצטע ווערסיע פֿון $2',

# Edit tokens
'sessionfailure' => "ווײַזט אויס אז ס'איז דא א פראבלעם מיט אייער ארײַנלאגירן; די פעולה איז געווארן אנולירט צו פֿאַרהיטן קעגן פֿאַרשטעלן אייער סעסיע. זייט אזוי גוט און גייט צוריק צום פֿריערדיקן בלאט, און פרובירט נאכאַמאָל.",

# Protect
'protectlogpage'              => 'באשיצונג לאָג-בוך',
'protectedarticle'            => 'געשיצט [[$1]]',
'modifiedarticleprotection'   => 'געענדערט באשיצונג שטאפל פון [[$1]]',
'unprotectedarticle'          => 'אומגעשיצט "[[$1]]"',
'movedarticleprotection'      => 'באוועגט די שיץ באשטימונגען פֿון "[[$2]]" אויף "[[$1]]"',
'protect-title'               => 'ענדערן שיץ ניווא פֿאַר "$1"',
'prot_1movedto2'              => '[[$1]] אריבערגעפירט צו [[$2]]',
'protect-legend'              => 'באַשטעטיגן שיץ',
'protectcomment'              => 'אורזאַך:',
'protectexpiry'               => 'גייט אויס:',
'protect_expiry_invalid'      => 'אויסגיין צײַט אומגילטיג.',
'protect_expiry_old'          => 'שוין דערנאך דער אויסגיין צײַט.',
'protect-unchain-permissions' => 'אויפֿשליסן נאך שיץ אפציעס',
'protect-text'                => "איר מעגט זען און ענדערן דעם שוץ ניווא דא פֿארן בלאט '''<nowiki>$1</nowiki>'''.",
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
'undelete'                  => 'זען אויסגעמעקטע בלעטער',
'undeletepage'              => 'זען און צוריקשטעלן אויסגעמעקט בלעטער',
'undeletepagetitle'         => "'''פֿאלגנד באַשטייט פֿון אויסגעמעקטע ווערסיע פֿון [[:$1]]'''.",
'viewdeletedpage'           => 'זען אויסגעמעקטע בלעטער',
'undeletepagetext'          => 'The following {{PLURAL:$1|דער פֿאלגנדער בלאַט איז געווארן אויסגעמעקט אבער קען|די פֿאלגנדע $1  בלעט ער זענען געווארן אויסגעמעקט אבער קענען}} נאך  ווערן צוריקגעשטעלט פֿון אַרכיוו.
פֿון צײַט צו צײַט רייניגט מען אויס דעם אַרכיוו.',
'undelete-fieldset-title'   => 'צוריקשטעלן רעוויזיעס',
'undeleteextrahelp'         => "צוריקצושטעלן דעם בלאט מיט זײַן גאנצע געשיכטע, דרוקט נישט אויף קיין איין ווערסיע, און דרוקט '''צוריקשטעלן'''. 
צוריקצושטעלן נאר געוויסע ווערסיעס, קלויבט אויס אונטן די רעוויזיעס וואס איר ווילט, און דרוקט אויף '''צוריקשטעלן'''. 
דרוקן אויף '''איבערמאכן''' וועט אומאויסקלויבן אלע ווערסיעס און אויסמעקן אלעס אין דעם קאמענטארן קעסטל.",
'undeleterevisions'         => '{{PLURAL:$1|איין ווערסיע|$1 ווערסיעס}} אַרכיווירט',
'undeletehistory'           => 'אויב איר שטעלט צוריק דעם בלאַט, וועלן אַלע רעוויזיעס ווערן צוריקגעשטעלט אין דער היסטאריע.
אויב מען האט באַשאַפֿן א בלאַט מיטן זעלבן נאָמען זײַטן אויסמעקן, וועלן די צוריקגעשטעלטע רעוויזיעס זיך באַווײַזן אין דער פֿריערדיקער היסטאריע.',
'undelete-nodiff'           => 'קיין פֿריערדיגע באַאַרבעטונג נישט געטראפֿן.',
'undeletebtn'               => 'צוריקשטעלן',
'undeletelink'              => 'קוקן/צוריקשטעלן',
'undeleteviewlink'          => 'באַקוקן',
'undeletereset'             => 'צוריקשטעלן',
'undeleteinvert'            => 'איבערקערן דעם אויסקלויב',
'undeletecomment'           => 'אורזאַך:',
'undeletedarticle'          => 'צוריק געשטעלט "[[$1]]"',
'undeletedrevisions'        => '{{PLURAL:$1|1 רעוויזיע|$1 רעוויזיעס}} צוריקגעשטעלט',
'undeletedrevisions-files'  => '{{PLURAL:$1|1 רעוויזיע|$1 רעוויזיעס}} און  {{PLURAL:$2|1 טעקע|$2 טעקעס}} צוריקגעשטעלט',
'undeletedfiles'            => '{{PLURAL:$1|1 טעקע|$1 טעקעס}} צוריקגעשטעלט',
'cannotundelete'            => 'צוריקשטעלונג איז דורכגעפאלן; עס איז מעגליך אז אן אנדערע האט דאס שוין צוריקגעשטעלט.',
'undeletedpage'             => "'''דער בלאט $1 איז געווארן צוריקגעשטעלט.'''

זעט דעם [[Special:Log/delete| אויסמעקן לאג]] פֿאר א ליסטע פון די לעצטע אויסגעמעקטע און צוריקגעשטעלטע בלעטער.",
'undelete-search-box'       => 'זוכן אויסגעמעקטע בלעטער',
'undelete-search-prefix'    => 'ווײַז בלעטער וואס הייבן אן מיט:',
'undelete-search-submit'    => 'זוכן',
'undelete-error-short'      => 'טעות ביים צוריקשטעלן טעקע: $1',
'undelete-show-file-submit' => 'יא',

# Namespace form on various pages
'namespace'      => 'נאמענטייל:',
'invert'         => 'ווײַז אַלע אויסער די',
'blanknamespace' => '(הויפט)',

# Contributions
'contributions'       => "באניצער'ס בײַשטײַערונגען",
'contributions-title' => 'בײַשטײַערונגען פֿון באַניצער $1',
'mycontris'           => 'מײַנע בײַשטײַערונגען',
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
'sp-contributions-logs'                => 'לאגביכער',
'sp-contributions-talk'                => 'שמועס',
'sp-contributions-userrights'          => 'באַניצער רעכטן פֿאַרוואַלטונג',
'sp-contributions-blocked-notice'      => 'דער באַניצער איז אַצינד בלאקירט. פֿאלגנד איז די לעצטע אַקציע אינעם פֿאַרשפאַרונג לאגבוך:',
'sp-contributions-blocked-notice-anon' => 'דער IP אַדרעס איז דערווייַל פֿאַרשפאַרט.
די לעצטע בלאָקירן לאג אַקציע איז צוגעשטעלט אונטן:',
'sp-contributions-search'              => 'זוכן בײַשטײַערונגען',
'sp-contributions-username'            => 'באניצער נאמען אדער IP אדרעס:',
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
'isimage'                  => 'ווײַזן א בילד',
'whatlinkshere-prev'       => '{{PLURAL:$1|פֿריערדיגער|$1 פֿריערדיגע}}',
'whatlinkshere-next'       => '{{PLURAL:$1|קומענדיגער|$1 קומענדיגע}}',
'whatlinkshere-links'      => '→ פֿארבינדונגען',
'whatlinkshere-hideredirs' => '$1 ווײַטערפֿירונגען',
'whatlinkshere-hidetrans'  => '$1 אַריבערשליסונגען',
'whatlinkshere-hidelinks'  => '$1 פֿאַרבינדונגען',
'whatlinkshere-hideimages' => '$1 בילדער פֿאַרבינדונגען',
'whatlinkshere-filters'    => 'פֿילטערס',

# Block/unblock
'blockip'                         => 'באניצער ארויסטרייבן',
'blockip-title'                   => 'בלאקירן באַניצער',
'blockip-legend'                  => 'בלאקירן באַניצער',
'blockiptext'                     => "באניצט די פארעם דא אונטן כדי צו בלאקירן שרײַבן רעכטן פֿון איינגעשריבענע באניצער אדער סתם ספעציפישע איי פי אדרעסן.

אזאלכע בלאקירונגען מוזן דורכגעפירט ווערן בלויז צו פֿאַרמײַדן וואַנדאַליזם, און לויט די [[{{MediaWiki:Policy-url}}|פארשריפטן און פאליסיס]].

ביטע שרײַבט ארויס קלאָר די ספעציפֿישע סיבה (למשל, ציטירן וועלכע בלעטער מ'האט וואַנדאַליזירט).",
'ipaddress'                       => 'IP אדרעס:',
'ipadressorusername'              => 'IP אדרעס אדער באַניצער נאמען:',
'ipbexpiry'                       => 'אויסגיין:',
'ipbreason'                       => 'סיבה:',
'ipbreasonotherlist'              => 'אנדער סיבה',
'ipbreason-dropdown'              => '* פֿארשפרייטע בלאקירן סיבות
** ארײַנלייגן פֿאלשע אינפֿארמאציע
** אויסמעקן אינהאַלט פֿון בלעטער
** פֿארפֿלייצן לינקען צו דרויסנדיקע ערטער
** באַניצער נאמען פראבלעמאטיש
** ארײַנלייגן שטותים/טאָטעריש אין בלעטער
** סטראשעט און שטערט
** קרומבאניצן מערערע קאנטעס
** פראבלעמישער באניצער נאמען',
'ipbanononly'                     => 'בלאקירן נאר אנאנימע באַניצערס',
'ipbcreateaccount'                => 'פֿאַרמײַדן שאַפֿן קאנטעס',
'ipbemailban'                     => 'פֿארמײַדן באַניצער פון שיקן ע־פאסט',
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
'badipaddress'                    => 'נישט קיין גוטער IP אַדרעס.',
'blockipsuccesssub'               => 'בלאק איז דורכגפירט מיט דערפֿאלג',
'blockipsuccesstext'              => 'באנוצער [[Special:Contributions/$1|$1]] <br />איז פארשפארט.
זעהט די [[Special:IPBlockList|ליסטע פון בלאקירטע באנוצער]] כדי צו זעהן די בלאקירונגן.',
'ipb-edit-dropdown'               => 'רעדאקטיר בלאקירונג סיבות',
'ipb-unblock-addr'                => 'אויפֿבלאקירן $1',
'ipb-unblock'                     => 'אויפֿבלאקירן א באַניצער נאמען אדער IP אדרעס',
'ipb-blocklist-addr'              => 'אַקטועלע בלאקירונגען פֿאַר $1',
'ipb-blocklist'                   => 'זעט עקזיסטירנדע בלאקירונגען',
'ipb-blocklist-contribs'          => 'בײַשטײַערונגען פֿון $1',
'unblockip'                       => 'אויפֿבלאקירן באניצער',
'ipusubmit'                       => 'אוועקנעמען דעם בלאק',
'unblocked-id'                    => 'בלאק $1 איז געווארן אַראָפגענומען.',
'ipblocklist'                     => 'ליסטע פון בלאקירטע באניצערס און IP אדרעסן',
'ipblocklist-legend'              => 'געפֿינען א בלאקירטן באניצער',
'ipblocklist-username'            => 'באניצער נאמען אדער IP אדרעס:',
'ipblocklist-submit'              => 'זוכן',
'ipblocklist-localblock'          => 'לאקאַלע בלאקירונג',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|אַנדער בלאקירונג|אַנדערע בלאקירונגען}}',
'blocklistline'                   => '$1 $2 האט פֿאַרשפאַרט $3 ($4)',
'infiniteblock'                   => 'אויף אייביק',
'expiringblock'                   => 'גייט אויס אום $1 $2',
'anononlyblock'                   => 'אנינאנימעס בלויז',
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
'autoblocker'                     => 'דו ביסט געבלאקט אטאמאטיק ווייל דו טיילסט זיך די IP אדרעס מיט [[User:$1|$1]]. דער סיבה וואס איז אנגעבען געווארן  [[User:$1|$1]] איז: "$2".',
'blocklogpage'                    => 'בלאקירן לאג',
'blocklog-showlog'                => '{{GENDER:$1|דער באַניצער|די באַניצערין}} איז שוין געווארן פֿאַרשפאַרט אַמאָל. 
דער בלאקירונג לאג איז צוגעשטעלט אונטן:',
'blocklog-showsuppresslog'        => '{{GENDER:$1|דער באַניצער|די באַניצערין}} איז שוין געווארן פֿאַרשפאַרט און פֿאַרבארגט אַמאָל. 
דער פֿאַרשטיקונג לאג איז צוגעשטעלט אונטן:',
'blocklogentry'                   => 'בלאקירט "[[$1]]" אויף אַ תקופה פון $2 $3',
'reblock-logentry'                => 'גענדערט די בלאקירונג דעפיניציעס פון [[$1]] מיטן צייט אפלויף פון $2 $3',
'blocklogtext'                    => 'דאס איז א לאג בוך פון אלע בלאקירונגען און באפרייונגען פֿון באניצערס. איי פי אדרעסן וואס זענען בלאקירט אויטאמאטיש ווערן נישט אויסגערעכענט דא.
זעט די איצטיגע [[Special:IPBlockList|ליסטע פון בלאקירטע באניצערס]].',
'unblocklogentry'                 => 'אומבלאקירט $1',
'block-log-flags-anononly'        => 'בלויז אנינאמע באנוצער',
'block-log-flags-nocreate'        => 'קאָנטע שאַפֿן איז פֿאַרשפּאַרט',
'block-log-flags-noautoblock'     => 'אויטא-בלאקיר איז בטל',
'block-log-flags-noemail'         => 'שיקן ע-פאסט  בלאקירט',
'block-log-flags-nousertalk'      => 'ענדערן אייגן שמועס בלאט בלאקירט',
'block-log-flags-angry-autoblock' => 'פארבעסערטע אויטא-בלאקירונג דערמעגליכט',
'block-log-flags-hiddenname'      => 'באַניצער נאָמען באַהאַלטן',
'range_block_disabled'            => 'די סיסאפ מעגליכקייט צו בלאקירן רענזש בלאקס איז אומ-ערמעגליכט.',
'ipb_expiry_invalid'              => 'אפלויפונג צייט אומ-געזעליך.',
'ipb_already_blocked'             => '"$1" איז שוין בלאקירט',
'ipb-needreblock'                 => '== שוין בלאקירט == 
$1 איז שוין בלאקירט. צי ווילט איר טוישן די באַצייכנונגען?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|אנדער בלאקירונג|אנדערע בלאקירונגען}}',
'ipb_cant_unblock'                => "פעילער: בלאק איי.די. $1 געפינט זיך נישט. ס'מעגליך שוין באפרייט געווארן.",
'ip_range_invalid'                => 'אומריכטיגער IP גרייך.',
'blockme'                         => 'בלאקירט מיך',
'proxyblocker'                    => 'פראקסי בלאקער',
'proxyblocker-disabled'           => 'די  פֿונקציע איז אומאַקטיווירט.',
'proxyblockreason'                => 'אייער איי.פי. אדרעס איז געווארן געבלאקט צוליב דעם ווייל דאס איז א אפענער פראקסי. ביטע פארבינדט זיך מיט אייער אינטערנעט סערוויס פראוויידער אדער טעקס סאפארט צו אינפארמירן זיי איבער דעם ערענסטן זיכערהייט פראבלעם.',
'proxyblocksuccess'               => 'געטאן.',

# Developer tools
'lockdb'              => 'פֿאַרשליסן די דאַטנבאַזע',
'unlockdb'            => 'אויפֿשליסן די דאַטנבאַזע',
'lockconfirm'         => 'יא, איך וויל פארשפארן די דאטעבאזע.',
'unlockconfirm'       => 'יא, איך וויל באמת אויפשליסן די דאטעבאזע.',
'lockbtn'             => 'פֿאַרשליסן דאַטנבאַזע',
'unlockbtn'           => 'אויפֿשליסן די דאַטנבאַזע',
'lockdbsuccesssub'    => 'דאַטנבאַזע פֿאַרשפאַרט מיט הצלחה',
'unlockdbsuccesstext' => 'די דאַטנבאַזע איז געווארן אויפֿגעשלאסן',
'databasenotlocked'   => 'די דאַטנבאַזע איז נישט פֿאַרשלאסן.',

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
'movepagetalktext'             => "דער רעדן בלאט וועט ווערן באַוועגט אויטאמאֵטיש מיט אים, '''אחוץ:'''
* ס'איז שוין דא א נישט-ליידיגער בלאט מיטן נייעם נאמען, אדער.
* איר נעמט  אראפ דעם צייכן פונעם קעסטל אונטן.

אין די פֿעלער, וועט איר דארפֿן באַוועגן אדער צונויפֿגיסן דעם בלאט האַנטלעך, ווען איר ווילט.",
'movearticle'                  => 'באוועג בלאט:',
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
'articleexists'                => 'א בלאט מיט דעם נאמען עקזיסטירט שוין, אדער די נאמען וואס איר האט אויסגעוועילט איז נישט געזעצליך.
ביטע אויסוועילן אן אנדער נאמען.',
'cantmove-titleprotected'      => 'איר קענט נישט באַוועגן א בלאַט צו דעם נאמען, ווייל דאס נייע קעפל איז געשיצט פֿון ווערן געשאַפֿן',
'talkexists'                   => "דער בלאט אליין איז באוועגט מיט דערפֿאלג, אבער דער רעדן בלאט האט מען נישט באוועגט ווײַל ס'איז שוין דא א בלאט מיט דעם זעלבן נאמען. זײַט אזוי גוט פֿאראייניגט זיי האנטלעך.",
'movedto'                      => 'באַוועגט צו',
'movetalk'                     => 'באוועגט אסאסיצירטע רעדן בלאט',
'move-subpages'                => 'באוועגן אונטערבלעטער (ביז $1)',
'move-talk-subpages'           => 'באַוועגן אונטערבלעטער פֿון רעדן בלאַט (ביז $1)',
'movepage-page-moved'          => 'דער בלאַט $1 איז געוורן באַוועגט צו $2.',
'movepage-page-unmoved'        => 'מען קען נישט באוועגן בלאט $1 צו $2.',
'1movedto2'                    => '[[$1]] אריבערגעפירט צו [[$2]]',
'1movedto2_redir'              => '[[$1]] איז אַריבער צו [[$2]] אַנטשטאָט א ווײַטערפֿירונג',
'move-redirect-suppressed'     => 'ווײַטערפֿירונג פֿאַרשטיקט',
'movelogpage'                  => 'באוועגן לאג',
'movelogpagetext'              => 'פֿאלגנד איז א ליסטע פֿון  בלעטער באוועגט.',
'movesubpage'                  => '{{PLURAL:$1|אונטערבלאַט|אונטערבלעטער}}',
'movesubpagetext'              => 'דער בלאַט האט $1 {{PLURAL:$1|אונטערבלאַט|אונטערבלעטער}} געוויזן אונטן.',
'movenosubpage'                => 'דער דאָזיגער בלאַט האט נישט קיין אונטערבלעטער.',
'movereason'                   => 'אורזאך:',
'revertmove'                   => 'צוריקדרייען',
'delete_and_move'              => 'אויסמעקן און באוועגן',
'delete_and_move_text'         => '== אויסמעקן פארלאנגט ==
דער ציל בלאַט "[[:$1]]" עקזיסטירט שוין. 
צי ווילט איר אים אויסמעקן כדי צו ערמעגליכן די באוועגונג?',
'delete_and_move_confirm'      => 'יא, מעק אויס דעם בלאט',
'delete_and_move_reason'       => 'אויסמעקן כדי צו קענען באוועגן',
'selfmove'                     => 'מקור און ציל קעפלעך זענען גלײַך; מען קען נישט באוועגן א בלאט צו זיך זעלבסט.',
'immobile-source-namespace'    => 'נישט מעגלעך צו באוועגן בלעטער אין נאמענטייל "$1"',
'immobile-target-namespace'    => 'מען קען נישט באַוועגן בלעטער צום נאמענטייל "$1"',
'immobile-target-namespace-iw' => 'אינטערוויקי לינק איז נישט קיין גילטיקער ציל פאר א בלאט באוועגונג.',
'immobile-source-page'         => 'דער דאזיגער בלאט קען נישט ווערן באוועגט.',
'immobile-target-page'         => 'קען נישט באַוועגן צו דעם ציל טיטל.',
'imagenocrossnamespace'        => 'קען נישט באַוועגן טעקע צו נישט-טעקע נאָמענטייל',
'imageinvalidfilename'         => 'דער ציל טעקע נאמען איז נישט גילטיק.',
'move-leave-redirect'          => 'איבערלאזן א ווײַטערפֿירונג',

# Export
'export'            => 'עקספארטירן בלעטער',
'exporttext'        => 'איר קענט עקספארטירן דעם טעקסט און די ענדערונג היסטאריע פון א געוויסן בלאט אדער עטלעכע בלעטער ארומגענומען מיט אביסל XML. דאס קען ווערן אימפארטירט אין אן אנדער וויקי ניצנדיג מעדיע-וויקי דורך
דעם [[Special:Import|אימפארט בלאט]].

צו עקספארטירן בלעטער, לייגט אריין די טיטלען אין דעם טעקסט קעסטל פון אונטן, איין טיטל פאר א שורה, און קלויבט אויס צי איר דארפט די לויפיגע ווערסיע, ווי אויך די אלטע ווערסיעס, מיט די בלאט היסטאריע שורות, אדער בלויז די איצטיגע ווערסיע מיט דער קורץ ווארט אינפארמאציע פון דער לעצטער ענדערונג.

אין דעם לעצטן פאל קענט איר אויך ניצן א לינק, למשל [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] פארן בלאט [[{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'רעכן אריין בלויז די איצטיגע רע-ווערסיע, נישט די פולער היסטאריע',
'exportnohistory'   => "----
'''באמערקונג:''' עקספארטירן די פולער היסטאריע פון בלעטער דורך די פארעם איז געווארן אומ-ערמעגליכט צוליב פערפארמענס סיבות.",
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

ביטע באזוכט [http://www.mediawiki.org/wiki/Localisation מעדיעוויקי לאקאליזאציע] און [http://translatewiki.net בעטאוויקי] אויב איר ווילט ביישטייערן צו דער גענערישער מעדיעוויקי לאקאליזאציע.',
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
'thumbnail_invalid_params' => 'אומגילטיגע קליינבילד פאראמעטערס',
'thumbnail_dest_directory' => "מ'קען נישט שאפֿן דעם ציל קארטאטעק",
'thumbnail_image-type'     => 'בילד טיפ נישט געשטיצט',
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
'importtext'                 => 'ביטע עקספארטירט די טעקע פון דער מקור וויקי ניצנדיג דאס [[Special:Export|עקספארט הילפמיטל]], שפייכלט איין אויף אייער הארטדיסק און לאדט אויף דא.',
'importstart'                => 'אימפארטירט בלעטער…',
'import-revision-count'      => '{{PLURAL:$1|איין ווערסיע|$1 ווערסיעס}}',
'importnopages'              => 'נישטא קיין בלעטער צו אימפארטירן.',
'importfailed'               => 'אימפארט דורכגעפֿאלן: <nowiki>$1</nowiki>',
'importunknownsource'        => 'אומבאקאנטער  אימפארט טיפ',
'importcantopen'             => 'נישט געקענט עפֿענען אימפארט טעקע',
'importbadinterwiki'         => 'שלעכטע אינטערוויקי לינק',
'importnotext'               => 'ליידיג אדער נישט קיין טעקסט',
'importsuccess'              => '!אימפארט אדורכגעפירט מיט דערפאלג!',
'importhistoryconflict'      => 'קאנפליקטינג היסטאריע רעוויזיע עקזעסטירט (מעגליך אז דער בלאט איז געווארן אימפארטירט שוין פון פריער)',
'importnosources'            => 'קיין מקורות פֿאַר צווישן־וויקי אימפארט, און דירעקט היסטאריע אַרויפֿלאָדן איז נישט דערמעגלעכט אַצינד.',
'importnofile'               => 'קיין אימפארט טעקע איז נישט ארויפֿגעלאדן.',
'importuploaderrorsize'      => 'אַרויפֿלאָדן פֿון אימפארט טעקע דורכגעפֿאלן.
די טעקע איז גרעסער פֿון דער דערלויבטער אַרויפֿלאָדן גרייס.',
'importuploaderrorpartial'   => 'אַרויפֿלאָדן פֿון אימפארט טעקע דורכגעפֿאלן.
די טעקע איז נאר טיילווייז אַרויפֿגעלאָדן.',
'importuploaderrortemp'      => 'אַרויפֿלאָדן פֿון אימפארט טעקע דורכגעפֿאלן.
אַ פראוויזארישער טעקע־האלטער פֿעלט.',
'import-noarticle'           => 'נישטא קיין בלאט צו אימפארטירן!',
'import-upload'              => 'אַרויפֿלאָדן XML דאַטן',
'import-invalid-interwiki'   => 'נישט מעגלעך צו אימפארטירן פון ספעציפֿירטער וויקי.',

# Import log
'importlogpage'                    => 'אימפארט לאג',
'import-logentry-upload'           => 'האט אימפארטירט [[$1]] דורך טעקע אַרויפֿלאָדן',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|רעוויזיע|רעוויזיעס}}',
'import-logentry-interwiki'        => 'אריבערגעוויקיט $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|רעוויזיע|רעוויזיעס}} פֿון $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'אייער באניצער בלאט',
'tooltip-pt-anonuserpage'         => 'באניצער בלאט פון  דעם אנאנימען באניצער',
'tooltip-pt-mytalk'               => 'אייער שמועס בלאט',
'tooltip-pt-anontalk'             => 'שמועס איבער באטייליגען פון די איי.פי.',
'tooltip-pt-preferences'          => 'אייערע פרעפערענצן',
'tooltip-pt-watchlist'            => 'ליסטע פון בלעטער וואס איר טוט אויפפאסן נאך ענדערונגן',
'tooltip-pt-mycontris'            => 'ליסטע פון אייערע ביישטייערונגען',
'tooltip-pt-login'                => 'עס איז רעקאָמענדירט זיך צו אײַנשרײַבן: אבער, עס איז נישט קיין פליכט',
'tooltip-pt-anonlogin'            => 'עס איז רעקאָמענדירט זיך צו אײַנשרײַבן, אָבער, עס איז נישט קײַן פֿליכט',
'tooltip-pt-logout'               => 'ארויסלאגירן',
'tooltip-ca-talk'                 => 'שמועס איבער די אינהאלט בלאט',
'tooltip-ca-edit'                 => "איר קענט ענדערן דעם בלאט, ביטע באנוצט זיך מיט ''פארויסדיגע ווייזונג'' קנעפל בעפארן אפהיטען",
'tooltip-ca-addsection'           => 'הייב אן א נייער שמועס אפטיילונג',
'tooltip-ca-viewsource'           => 'דאס איז א פֿארשלאסענער בלאט, איר קענט נאר באַקוקן זיין מקור',
'tooltip-ca-history'              => 'פריערדיגע ווערסיעס פון דעם בלאט.',
'tooltip-ca-protect'              => 'באשיצט דעם בלאט',
'tooltip-ca-unprotect'            => 'אומבאַשיצן דעם בלאַט',
'tooltip-ca-delete'               => 'אויסמעקן דעם בלאט',
'tooltip-ca-undelete'             => 'צוריק דרייען די ענדערונגען פון דעם בלאט פארן מעקן',
'tooltip-ca-move'                 => 'באוועגט דעם בלאט',
'tooltip-ca-watch'                => 'לייגט צו דעם בלאט אויפצופאסן',
'tooltip-ca-unwatch'              => 'נעמט אראפ דעם בלאט פון אויפפאסן',
'tooltip-search'                  => 'זוכט אינעם סייט',
'tooltip-search-go'               => 'גייט צו א בלאט מיט אט דעם נאמען, אויב ער עקסיסטירט',
'tooltip-search-fulltext'         => 'זוכט דעם טעקסט אין די בלעטער',
'tooltip-p-logo'                  => 'הויפט זייט',
'tooltip-n-mainpage'              => 'באַזוכט דעם הויפּט־זײַט',
'tooltip-n-mainpage-description'  => 'באַזוכן דעם הויפט בלאַט',
'tooltip-n-portal'                => 'גייט אריין אין די געמיינדע צו שמועסן',
'tooltip-n-currentevents'         => 'מער אינפארמאציע איבער אקטועלע געשענישען',
'tooltip-n-recentchanges'         => 'ליסטע פון לעצטע ענדערונגען',
'tooltip-n-randompage'            => 'וועלט אויס א צופעליגער בלאט',
'tooltip-n-help'                  => 'הילף',
'tooltip-t-whatlinkshere'         => 'אלע בלעטער וואס פארבינדען צו דעם בלאט',
'tooltip-t-recentchangeslinked'   => 'אלע ענדערונגען פון בלעטער וואס זענען אהער פארבינדען',
'tooltip-feed-rss'                => 'דערהײַנטיגט אויטאמאטיש פון אר.עס.עס. RSS',
'tooltip-feed-atom'               => 'לייג צו אן אטאמאטישער אפדעיט דורך אטאם Atom',
'tooltip-t-contributions'         => 'אלע בײַשטײַערונגען פון דעם באניצער',
'tooltip-t-emailuser'             => 'שיקן א בליצבריוו צו דעם בַאניצער',
'tooltip-t-upload'                => 'ארויפלאדן טעקעס',
'tooltip-t-specialpages'          => 'אלע ספעציעלע בלעטער',
'tooltip-t-print'                 => 'דרוק ווערסיע פון דעם בלאט',
'tooltip-t-permalink'             => 'פערמאנענטע פֿארבינדונג צו דער דאזיגער ווערסיע פֿונעם בלאט',
'tooltip-ca-nstab-main'           => 'זעט דעם אינהאַלט בלאַט',
'tooltip-ca-nstab-user'           => 'זעט דעם באניצער בלאט',
'tooltip-ca-nstab-media'          => 'קוקט אין דעם מעדיע בלאט',
'tooltip-ca-nstab-special'        => "דאס איז א ספעציעלער בלאט, מ'קען אים נישט ענדערן",
'tooltip-ca-nstab-project'        => 'באקוקט דעם פראיעקט בלאט',
'tooltip-ca-nstab-image'          => 'באקוקט דעם טעקע בלאט',
'tooltip-ca-nstab-mediawiki'      => 'באקוקט די סיסטעם מעלדונגען',
'tooltip-ca-nstab-template'       => 'זעט דעם מוסטער',
'tooltip-ca-nstab-help'           => 'זעהט די הילף בלעטער',
'tooltip-ca-nstab-category'       => 'באקוקט דעם קאטעגאריע בלאט',
'tooltip-minoredit'               => 'באצייכן דאס אלס מינערדיגע ענדערונג',
'tooltip-save'                    => 'היט אויף אייערע ענדערונגען',
'tooltip-preview'                 => 'פֿארויסדיגע ווײַזונג, זײַט אזוי גוט באניצט די געלעגנהייט פֿארן אויפֿהיטן!',
'tooltip-diff'                    => 'ווײַזן אייערע ענדערונגען צום טעקסט',
'tooltip-compareselectedversions' => 'פארגלײַכם די צוויי ווערסיעס פון דעם בלאט',
'tooltip-watch'                   => 'לייגט צו דעם בלאט צו אייער אויפֿפאסונג ליסטע',
'tooltip-recreate'                => 'ווידערשאַפֿן דעם בלאַט כאטש ער איז אַמאל אויסגעמעקט',
'tooltip-upload'                  => 'הייב אן אויפלאדן',
'tooltip-rollback'                => '"צוריקדרייען" דרײט צוריק רעדאַקטירונג(ען) צו דעם בלאַט פֿונעם לעצטן בײַשטײַערער מיט אײן קװעטש',
'tooltip-undo'                    => 'עפֿנט דעם רעדאַגיר־פֿענסטער אין אַ פֿאָרױסדיקן אױסקוק כּדי צוריקדרײען די רעדאַקציע. עס איז מעגלעך צוצולײגן אַ סיבה דערװעגן אין דעם "קורץ װאָרט" קעסטל.',

# Stylesheets
'common.css'   => '/* CSS געשריבן דא וועט אפילירן און באיינפלוסן אלע סקינס */',
'monobook.css' => '/* סטייל דא געלייגט וועט באיינפלוסן די בעקגראונד Monobook בלויז */',
'vector.css'   => '/* CSS געשטעלט דא ווירקט נאר אויפן Vector סקין */',

# Scripts
'common.js' => '/* אלע סקריפטן פון JavaScript דא געשריבן וועט לויפן פאר אלע באנוצער ווען זיי וועלן לאדירן דעם בלאט */',

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

# Spam protection
'spamprotectiontitle' => 'ספעם באשיצונג פילטער',
'spambot_username'    => 'מעדיעוויקי ספאם פוצן',
'spam_reverting'      => 'צוריקגעשטעלט צו דער לעצטער ווערסיע אן לינקען צו $1',

# Info page
'infosubtitle' => 'אינפארמאציע וועגן בלאט',
'numedits'     => 'צאל פון רעדאקציעס (בלאט): $1',

# Math errors
'math_unknown_error'    => 'אומבאַקאַנטער פֿעלער',
'math_unknown_function' => 'אומבאַקאַנטע פֿונקציע',
'math_syntax_error'     => 'סינטאקס גרייז',

# Patrolling
'markaspatrolleddiff'                 => 'באצייכנען אלס פאטראלירט',
'markaspatrolledtext'                 => 'באצייכנען בלאט אלס פאטראלירט',
'markedaspatrolled'                   => 'באצייכנט אלס פאטראלירט',
'markedaspatrolledtext'               => 'די אויסגעקליבענע ענדערונג פֿון [[:$1]] איז געצייכנט געווארן אלס פאַטארלירט.',
'rcpatroldisabled'                    => 'פאַטראלירן ענדערונגען איז  מבוטל געווארן',
'rcpatroldisabledtext'                => 'די לעצטע ענדערונגען פאַטראלירן אייגנקייט איז אצינד בטל.',
'markedaspatrollederror'              => 'נישט מעגלעך צו צייכענען אלס פאַטראלירט',
'markedaspatrollederrortext'          => 'איר דארפֿט ספעציפֿירן א ווערזיע צו באַצייכענען אלס פאַטראלירט.',
'markedaspatrollederror-noautopatrol' => 'איר טאר נישט באַצייכענען די אייגענע ענדערונגען אלס פאַטראלירט.',

# Patrol log
'patrol-log-page'      => 'פאטראלירן לאג-בוך',
'patrol-log-header'    => 'דאס איז א לאג-בוך פון פאַטראליטע רעוויזיעס.',
'patrol-log-line'      => 'געצייכנט $1 פון בלאט $2 ווי פאַטראלירט $3',
'patrol-log-auto'      => '(אויטאמאַטיש)',
'patrol-log-diff'      => 'רעוויזיע $1',
'log-show-hide-patrol' => '$1 פאַטראלירן לאג-בוך',

# Image deletion
'deletedrevision'                 => 'אויסגעמעקט אלטע ווערסיע $1.',
'filedeleteerror-short'           => 'גרייז ביים אויסמעקן טעקע: $1',
'filedelete-missing'              => 'קען נישט אויסמעקן טעקע "$1", ווייל זי עקזיסטירט נישט.',
'filedelete-current-unregistered' => 'די טעקע "$1" איז נישט אין דער דאטנבאזע.',
'filedelete-archive-read-only'    => 'דער וועבסארווער קען נישט שרייבן צום ארכיוו פֿארצייכעניש "$1".',

# Browsing diffs
'previousdiff' => 'פריִערדיקע ווערסיע →',
'nextdiff'     => 'קומענדיקע ווערסיע ←',

# Media information
'widthheightpage'      => '$1×$2, {{PLURAL:$3|איין בלאט|$3 בלעטער}}',
'file-info'            => '(טעקע גרייס: $1, MIME טיפ: $2)',
'file-info-size'       => '($1 × $2 פיקסעל, טעקע גרייס: $3, טיפ MIME: $4)',
'file-nohires'         => '<small>נישטא מיט א העכערע רעזאלוציע.</small>',
'svg-long-desc'        => '(טעקע SVG, נאמינעל: $1 × $2 פיקסעלן, טעקע גרייס: $3)',
'show-big-image'       => 'בילד מיט דער גרעסטער רעזאלוציע',
'show-big-image-thumb' => '<small>גרייס פון דער ווײַזונג: $1 × $2 פיקסעלן</small>',
'file-info-gif-frames' => '$1 {{PLURAL:$1|ראם|ראמען}}',

# Special:NewFiles
'newimages'             => 'גאַלעריע פֿון נײַע בילדער',
'imagelisttext'         => 'פאלגנד א ליסטע פון {{PLURAL:$1|איין בילד|$1 בילדער}}, סארטירט $2:',
'newimages-summary'     => 'דער באזונדערער בלאט ווײַזט די לעצטע ארויפגעלאדענע טעקעס',
'newimages-legend'      => 'פֿילטער',
'newimages-label'       => 'טעקע נאָמען (אדער אַ טײל פֿון אים):',
'showhidebots'          => '($1 ראָבאָטן)',
'noimages'              => 'נישטא קיין בילדער.',
'ilsubmit'              => 'זוכן',
'bydate'                => 'לויטן דאטום',
'sp-newimages-showfrom' => 'באַװײַזן נײַע טעקעס פון $2, $1',

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
'metadata-fields'   => 'די פֿאלגנדע EXIF מעטאדאטע ווערן געוויזן אפילו ווען די מעטאדאטע טאבעלע איז אײַנגעפֿאלן:
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'               => 'ברייט',
'exif-imagelength'              => 'הייך',
'exif-bitspersample'            => 'ביטס פער באשטאנדטייל',
'exif-compression'              => 'צאמקוועטשן סקיעם',
'exif-orientation'              => 'אריענטאַציע',
'exif-datetime'                 => 'טעקע ענדערונג דאטע און צײַט',
'exif-imagedescription'         => 'בילד טיטל',
'exif-make'                     => 'פֿאטא-אפאראט פֿאבריצירער',
'exif-model'                    => 'פֿאטא-אפאראט מאדעל',
'exif-software'                 => 'ווייכוואַרג באניצט',
'exif-artist'                   => 'מחבר',
'exif-copyright'                => 'קאפירעכטן האלטער',
'exif-exifversion'              => 'Exif ווערסיע',
'exif-flashpixversion'          => 'ווערסיע Flashpix סאפארטעד',
'exif-colorspace'               => 'ספעיס קאליר',
'exif-componentsconfiguration'  => 'מיינונג פון יעדן באשטאנדטייל',
'exif-compressedbitsperpixel'   => 'בילד צוזאמקוועטשן מאוד',
'exif-pixelydimension'          => 'גילטיגע ברייט פֿון בילד',
'exif-pixelxdimension'          => 'גילטיגע הייך פֿון בילד',
'exif-makernote'                => 'פֿאַבריצירער הערות',
'exif-usercomment'              => 'באנוצער קאמענטורן',
'exif-datetimeoriginal'         => 'דאטום און צייט פון דאַטן באשאפונג',
'exif-datetimedigitized'        => 'דאטום און צייט פון דיזשיטייזונג',
'exif-exposuretime'             => 'באַלײַכטן צייט',
'exif-exposuretime-format'      => '$1 סעק ($2)',
'exif-fnumber'                  => 'נומער F',
'exif-exposureprogram'          => 'אויפדעקונג פראגראם',
'exif-shutterspeedvalue'        => 'לעדל גיך',
'exif-aperturevalue'            => 'עפֿן',
'exif-brightnessvalue'          => 'אפֿנקייט',
'exif-exposurebiasvalue'        => 'באַלײַכטן נייגונג',
'exif-maxaperturevalue'         => 'מאקסימום גרייס פון עפענונג',
'exif-subjectdistance'          => 'סוביעקט ווייט',
'exif-lightsource'              => 'ליכט מקור',
'exif-flash'                    => 'בליץ',
'exif-focallength'              => 'לענס פֿאקאַלע לענג',
'exif-focallength-format'       => '$1 מ"מ',
'exif-subjectarea'              => 'סוביעקט געגנט',
'exif-flashenergy'              => 'פלעש ענערגיע',
'exif-focalplanexresolution'    => 'פאקאל פלעין עקס רעזאלוציע',
'exif-focalplaneresolutionunit' => 'פאקאל פלעין רעזאלוציע מאס',
'exif-exposureindex'            => 'עקספאוזשער אינדעקס',
'exif-filesource'               => 'מקור פֿון דער טעקע',
'exif-scenetype'                => 'סצענע טיפ',
'exif-cfapattern'               => 'פעטערן CFA',
'exif-customrendered'           => 'קאסטעם בילד פראצעסירונג',
'exif-exposuremode'             => 'עקספאוזשער מאוד',
'exif-digitalzoomratio'         => 'דיזשיטאלער זום ראשיאו',
'exif-focallengthin35mmfilm'    => 'פאקאל לענג אין 35 מ"מ פילם',
'exif-gaincontrol'              => 'סצענע קאנטראל',
'exif-contrast'                 => 'קאנטראסט',
'exif-devicesettingdescription' => 'זאך סעטינגס אראפמאלונג',
'exif-gpslatitude'              => 'לאטיטוד',
'exif-gpslongituderef'          => 'מזרח אדער מערב לענג',
'exif-gpslongitude'             => 'געאגראַפֿישע לענג',
'exif-gpsaltituderef'           => 'אלטיטוט רעפערענץ',
'exif-gpsaltitude'              => 'אלטיטוט',
'exif-gpsdop'                   => 'מאס פוקנטליכקייט',
'exif-gpsimgdirectionref'       => 'רעפערענץ פאר ריכטונג פון בילד',
'exif-gpsimgdirection'          => 'ריכטונג פון בילד',
'exif-gpsdestlatituderef'       => 'רעפערענץ פאר לעטיטוד פון דעסטינאציע',
'exif-gpsdestlatitude'          => 'לעטיטוד פון דעסטינאציע',
'exif-gpsdestlongituderef'      => 'רעפערענץ פאר לענגטיטוד פון דעטינאציע',
'exif-gpsdestlongitude'         => 'לאנגטיטוד פון דעסטינאציע',
'exif-gpsdestbearingref'        => 'רעפערענץ פאר ריכטונג פון דעסטינאציע',
'exif-gpsdestbearing'           => 'ריכטונג פון דעסטינאציע',
'exif-gpsdestdistanceref'       => 'רעפערענץ פאר ווייטקייט פון דעסטינאציע',
'exif-gpsdestdistance'          => 'ווייטקייט פון דעטינאציע',
'exif-gpsareainformation'       => 'נאמען פון GPS געגענט',
'exif-gpsdatestamp'             => 'דאטום GPS',
'exif-gpsdifferential'          => 'דיפראנציאלע קאקרעקציע GPS',

# EXIF attributes
'exif-compression-1' => 'אומ-צאמגעקוועטשט',

'exif-unknowndate' => 'אומבאַוואוסטע דאַטע',

'exif-orientation-1' => 'נארמאַל',

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
'exif-meteringmode-255' => 'אנדער',

'exif-lightsource-0' => 'אומבאַוויסט',

'exif-focalplaneresolutionunit-2' => 'אינטשעס',

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
'exif-contrast-2' => 'הארט',

'exif-saturation-0' => 'נארמאַל',

'exif-sharpness-0' => 'נארמאל',
'exif-sharpness-1' => 'ווייך',
'exif-sharpness-2' => 'הארט',

'exif-subjectdistancerange-0' => 'אומבאַוויסט',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'צפון לעטיטוד',
'exif-gpslatitude-s' => 'דרום לאטיטוד',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'מזרח לענג',
'exif-gpslongitude-w' => 'מערב לענג',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'ק"מ אין א שעה',
'exif-gpsspeed-m' => 'מייל פער שעה',
'exif-gpsspeed-n' => 'ים מײַלן א שעה',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'ריכטיגע דירעקציע',
'exif-gpsdirection-m' => 'מאגנאטיק ריכטונג',

# External editor support
'edit-externally'      => 'רעדאַקטירט די טעקע מיט א דרויסנדיגער אַפליקאַציע',
'edit-externally-help' => 'זעט די [http://www.mediawiki.org/wiki/Manual:External_editors אויפֿשטעל אנווייזונגען] פאר מער אינפארמאציע.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'אַלע',
'imagelistall'     => 'אַלע',
'watchlistall2'    => 'אַלע',
'namespacesall'    => 'אַלע',
'monthsall'        => 'אלע',
'limitall'         => 'אַלע',

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
'confirmemail_invalidated'  => 'בליצפאסט אדרעס באשטעטיקונג אַנולירט',
'invalidateemail'           => 'אַנולירן בליצפאסט באַשטעטיקונג',

# Scary transclusion
'scarytranscludetoolong' => '[URL צו לאנג]',

# Trackbacks
'trackbackremove' => '([$1 אויסמעקן])',

# Delete conflict
'deletedwhileediting' => 'ווארענונג: דער בלאט איז געווארן אויסגעמעקט נאכדעם וואס איר האט אנגעהויבן רעדאקטירן!',
'confirmrecreate'     => "באנוצער [[User:$1|$1]] ([[User talk:$1|רעדן]]) האט אויסגעמעקט דעם בלאט נאכדעם וואס איר האט אנגעהויבן דאס צו ענדערן, אלס אנגעבליכער סיבה:
:'''$2'''
ביטע באשטעטיגט אז איר ווילט טאקע צוריקשטעלן דעם בלאט.",
'recreate'            => 'שאַפֿן פֿונדאסניי',

# action=purge
'confirm_purge_button' => 'אויספֿירן',
'confirm-purge-top'    => 'אויסקלארן די קאשעי פון דעם בלאט?',

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
'watchlistedit-noitems'       => 'אײַער אויפֿפאַסן ליסטע איז ליידיג.',
'watchlistedit-normal-title'  => 'רעדאַקטירן די אויפֿפאַסונג ליסטע',
'watchlistedit-normal-legend' => 'אַראָפנעמען בלעטער פון דער אויפֿפאסן ליסטע',
'watchlistedit-normal-submit' => 'אַראָפנעמען בלעטער',
'watchlistedit-raw-title'     => 'רעדאַקטירן די רויע אויפֿפאַסונג ליסטע',
'watchlistedit-raw-legend'    => 'רעדאַקטירן די רויע אויפֿפאַסונג ליסטע',
'watchlistedit-raw-titles'    => 'טיטלען:',
'watchlistedit-raw-submit'    => 'דערהיינטיג אויפפאסונג ליסטע',
'watchlistedit-raw-done'      => 'אייער אויפֿפאַסונג ליסטע איז געווארן דערהײַנטיקט',

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

# Hebrew month names
'hebrew-calendar-m9'      => 'סיון',
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

# Core parser functions
'duplicate-defaultsort' => '\'\'\'ווארענונג:\'\'\' גרונט סארטשליסל "$2" פֿאָרט איבערן פֿריערדיגן גרונט סארטשליסל "$1".',

# Special:Version
'version'                  => 'ווערסיע',
'version-specialpages'     => 'ספעציעלע בלעטער',
'version-variables'        => 'וואַריאַבלען',
'version-other'            => 'אנדער',
'version-version'          => '(ווערסיע $1)',
'version-software-product' => 'פראדוקט',
'version-software-version' => 'ווערסיע',

# Special:FilePath
'filepath-page' => 'טעקע:',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'זוכן דופליקאַטע טעקעס',
'fileduplicatesearch-legend'   => 'זוכן א דופליקאַט',
'fileduplicatesearch-filename' => 'טעקע:',
'fileduplicatesearch-submit'   => 'זוכן',

# Special:SpecialPages
'specialpages'                   => 'ספּעציעלע זײַטן',
'specialpages-note'              => '----
* נארמאַלע ספעציעלע בלעטער.
* <strong class="mw-specialpagerestricted">באַגרענעצטע ספעציעלע בלעטער.</strong>',
'specialpages-group-maintenance' => 'אויפֿהאַלטונג באַריכטן',
'specialpages-group-other'       => 'אַנדערע ספעציעלע בלעטער',
'specialpages-group-login'       => 'ארײַנלאגירן / אײַנשרײַבן',
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

# Special:Tags
'tag-filter-submit' => 'פֿילטער',
'tags-title'        => 'טאַגן',
'tags-tag'          => 'טאַג נאָמען',
'tags-edit'         => 'רעדאַקטירן',
'tags-hitcount'     => ' {{PLURAL:$1|ענדערונג|$1 ענדערונגען}}',

# Database error messages
'dberr-header'   => 'די וויקי האט א פראבלעם',
'dberr-problems' => 'אנטשולדיגט! דער דאזיקער סייט האט טעכנישע פראבלעמען.',
'dberr-again'    => 'וואַרט א פאָר מינוט און לאָדנט אָן ווידער.',

# HTML forms
'htmlform-float-invalid'       => 'דער ווערט וואָס איר האט ספעציפֿירט איז נישט קיין צאל.',
'htmlform-submit'              => 'איינגעבן',
'htmlform-reset'               => 'צוריקשטעלן ענדערונגען',
'htmlform-selectorother-other' => 'אנדער',

# Add categories per AJAX
'ajax-add-category'            => 'צולייגן קאַטעגאריע',
'ajax-add-category-submit'     => 'צולייגן',
'ajax-confirm-save'            => 'אויפֿהיטן',
'ajax-add-category-summary'    => 'צולייגן קאַטעגאריע "$1"',
'ajax-remove-category-summary' => 'אַוועקנעמען קאַטעגאריע "$1"',
'ajax-error-title'             => 'גרײַז',
'ajax-error-dismiss'           => 'אויספֿירן',

);

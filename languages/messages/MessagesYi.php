<?php
/** Yiddish (ייִדיש)
  *
  * @package MediaWiki
  * @subpackage Language
  */
$fallback = 'he';

$namespaceNames = array(
	NS_MEDIA          => 'מעדיע',
	NS_SPECIAL        => 'באַזונדער',
	NS_MAIN           => '',
	NS_TALK           => 'רעדן',
	NS_USER           => 'באַניצער',
	NS_USER_TALK      => 'באַניצער_רעדן',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_רעדן',
	NS_IMAGE          => 'בילד',
	NS_IMAGE_TALK     => 'בילד_רעדן',
	NS_MEDIAWIKI      => 'מעדיעװיקי',
	NS_MEDIAWIKI_TALK => 'מעדיעװיקי_רעדן',
	NS_TEMPLATE       => 'מוסטער',
	NS_TEMPLATE_TALK  => 'מוסטער_רעדן',
	NS_HELP           => 'הילף',
	NS_HELP_TALK      => 'הילף_רעדן',
	NS_CATEGORY       => 'קאַטעגאָריע',
	NS_CATEGORY_TALK  => 'קאַטעגאָריע_רעדן'
);

$namespaceAliases = array(
	'באזונדער' => NS_SPECIAL,
	'באנוצער' => NS_USER,
	'באנוצער_רעדן' => NS_USER_TALK,
	'מעדיעוויקי' => NS_MEDIAWIKI,
	'מעדיעוויקי_רעדן' => NS_MEDIAWIKI_TALK,
	'קאטעגאריע' => NS_CATEGORY,
	'קאטעגאריע_רעדן' => NS_CATEGORY_TALK,
	'באניצער' => NS_USER,
	'באניצער_רעדן' => NS_USER_TALK,
);

$rtl = true;
$defaultUserOptionOverrides = array(
	# Swap sidebar to right side by default
	'quickbar' => 2,
);

/**
 * Magic words.
 * Disabling the Hebrew ones.
 */
$magicWords = array(
	'redirect'               => array( 0,    '#REDIRECT'              ),
	'notoc'                  => array( 0,    '__NOTOC__'              ),
	'nogallery'              => array( 0,    '__NOGALLERY__'          ),
	'forcetoc'               => array( 0,    '__FORCETOC__'           ),
	'toc'                    => array( 0,    '__TOC__'                ),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__'      ),
	'start'                  => array( 0,    '__START__'              ),
	'currentmonth'           => array( 1,    'CURRENTMONTH'           ),
	'currentmonthname'       => array( 1,    'CURRENTMONTHNAME'       ),
	'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN'    ),
	'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV'     ),
	'currentday'             => array( 1,    'CURRENTDAY'             ),
	'currentday2'            => array( 1,    'CURRENTDAY2'            ),
	'currentdayname'         => array( 1,    'CURRENTDAYNAME'         ),
	'currentyear'            => array( 1,    'CURRENTYEAR'            ),
	'currenttime'            => array( 1,    'CURRENTTIME'            ),
	'currenthour'            => array( 1,    'CURRENTHOUR'            ),
	'localmonth'             => array( 1,    'LOCALMONTH'             ),
	'localmonthname'         => array( 1,    'LOCALMONTHNAME'         ),
	'localmonthnamegen'      => array( 1,    'LOCALMONTHNAMEGEN'      ),
	'localmonthabbrev'       => array( 1,    'LOCALMONTHABBREV'       ),
	'localday'               => array( 1,    'LOCALDAY'               ),
	'localday2'              => array( 1,    'LOCALDAY2'              ),
	'localdayname'           => array( 1,    'LOCALDAYNAME'           ),
	'localyear'              => array( 1,    'LOCALYEAR'              ),
	'localtime'              => array( 1,    'LOCALTIME'              ),
	'localhour'              => array( 1,    'LOCALHOUR'              ),
	'numberofpages'          => array( 1,    'NUMBEROFPAGES'          ),
	'numberofarticles'       => array( 1,    'NUMBEROFARTICLES'       ),
	'numberoffiles'          => array( 1,    'NUMBEROFFILES'          ),
	'numberofusers'          => array( 1,    'NUMBEROFUSERS'          ),
	'pagename'               => array( 1,    'PAGENAME'               ),
	'pagenamee'              => array( 1,    'PAGENAMEE'              ),
	'namespace'              => array( 1,    'NAMESPACE'              ),
	'namespacee'             => array( 1,    'NAMESPACEE'             ),
	'talkspace'              => array( 1,    'TALKSPACE'              ),
	'talkspacee'             => array( 1,    'TALKSPACEE'              ),
	'subjectspace'           => array( 1,    'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'          => array( 1,    'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'           => array( 1,    'FULLPAGENAME'           ),
	'fullpagenamee'          => array( 1,    'FULLPAGENAMEE'          ),
	'subpagename'            => array( 1,    'SUBPAGENAME'            ),
	'subpagenamee'           => array( 1,    'SUBPAGENAMEE'           ),
	'basepagename'           => array( 1,    'BASEPAGENAME'           ),
	'basepagenamee'          => array( 1,    'BASEPAGENAMEE'          ),
	'talkpagename'           => array( 1,    'TALKPAGENAME'           ),
	'talkpagenamee'          => array( 1,    'TALKPAGENAMEE'          ),
	'subjectpagename'        => array( 1,    'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'       => array( 1,    'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                    => array( 0,    'MSG:'                   ),
	'subst'                  => array( 0,    'SUBST:'                 ),
	'msgnw'                  => array( 0,    'MSGNW:'                 ),
	'end'                    => array( 0,    '__END__'                ),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb'     ),
	'img_manualthumb'        => array( 1,    'thumbnail=$1', 'thumb=$1'),
	'img_right'              => array( 1,    'right'                  ),
	'img_left'               => array( 1,    'left'                   ),
	'img_none'               => array( 1,    'none'                   ),
	'img_width'              => array( 1,    '$1px'                   ),
	'img_center'             => array( 1,    'center', 'centre'       ),
	'img_framed'             => array( 1,    'framed', 'enframed', 'frame' ),
	'img_page'               => array( 1,    'page=$1', 'page $1'     ),
	'int'                    => array( 0,    'INT:'                   ),
	'sitename'               => array( 1,    'SITENAME'               ),
	'ns'                     => array( 0,    'NS:'                    ),
	'localurl'               => array( 0,    'LOCALURL:'              ),
	'localurle'              => array( 0,    'LOCALURLE:'             ),
	'server'                 => array( 0,    'SERVER'                 ),
	'servername'             => array( 0,    'SERVERNAME'             ),
	'scriptpath'             => array( 0,    'SCRIPTPATH'             ),
	'grammar'                => array( 0,    'GRAMMAR:'               ),
	'notitleconvert'         => array( 0,    '__NOTITLECONVERT__', '__NOTC__'),
	'nocontentconvert'       => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__'),
	'currentweek'            => array( 1,    'CURRENTWEEK'            ),
	'currentdow'             => array( 1,    'CURRENTDOW'             ),
	'localweek'              => array( 1,    'LOCALWEEK'              ),
	'localdow'               => array( 1,    'LOCALDOW'               ),
	'revisionid'             => array( 1,    'REVISIONID'             ),
	'revisionday'            => array( 1,    'REVISIONDAY'            ),
	'revisionday2'           => array( 1,    'REVISIONDAY2'           ),
	'revisionmonth'          => array( 1,    'REVISIONMONTH'          ),
	'revisionyear'           => array( 1,    'REVISIONYEAR'           ),
	'revisiontimestamp'      => array( 1,    'REVISIONTIMESTAMP'      ),
	'plural'                 => array( 0,    'PLURAL:'                ),
	'fullurl'                => array( 0,    'FULLURL:'               ),
	'fullurle'               => array( 0,    'FULLURLE:'              ),
	'lcfirst'                => array( 0,    'LCFIRST:'               ),
	'ucfirst'                => array( 0,    'UCFIRST:'               ),
	'lc'                     => array( 0,    'LC:'                    ),
	'uc'                     => array( 0,    'UC:'                    ),
	'raw'                    => array( 0,    'RAW:'                   ),
	'displaytitle'           => array( 1,    'DISPLAYTITLE'           ),
	'rawsuffix'              => array( 1,    'R'                      ),
	'newsectionlink'         => array( 1,    '__NEWSECTIONLINK__'     ),
	'currentversion'         => array( 1,    'CURRENTVERSION'         ),
	'urlencode'              => array( 0,    'URLENCODE:'             ),
	'anchorencode'           => array( 0,    'ANCHORENCODE'           ),
	'currenttimestamp'       => array( 1,    'CURRENTTIMESTAMP'       ),
	'localtimestamp'         => array( 1,    'LOCALTIMESTAMP'         ),
	'directionmark'          => array( 1,    'DIRECTIONMARK', 'DIRMARK' ),
	'language'               => array( 0,    '#LANGUAGE:'             ),
	'contentlanguage'        => array( 1,    'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'       => array( 1,    'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'         => array( 1,    'NUMBEROFADMINS'         ),
	'formatnum'              => array( 0,    'FORMATNUM'              ),
	'padleft'                => array( 0,    'PADLEFT'                ),
	'padright'               => array( 0,    'PADRIGHT'               ),
	'special'                => array( 0,    'special',               ),
	'defaultsort'            => array( 1,    'DEFAULTSORT:'           ),
);

$messages = array(
'tog-usenewrc'          => 'פֿאַרבעסערטע "לעצטע ענדערונגען" (JavaScript)',
'tog-watchdefault'      => 'נאָכפֿאָלג אױטאָמאַטיש די װערטן װאָס איך באַאַרבעט',
'tog-previewontop'      => 'צײַגן דעם "פֿאָרויסיקע װײַזונג" גלײַך בײַם ערשטע באַאַרבעטונג',
'tog-fancysig'          => '<br />
Raw signatures (without automatic link)',
'sunday'                => 'זונטיק',
'monday'                => 'מאָנטיק',
'tuesday'               => 'דינסטיק',
'wednesday'             => 'מיטװאָך',
'thursday'              => 'דאָנערשטיק',
'friday'                => 'פֿרײַטיק',
'saturday'              => 'שבת',
'january'               => 'יאַנואַר',
'february'              => 'פֿעברואַר',
'march'                 => 'מאַרץ',
'april'                 => 'אַפּריל',
'may_long'              => 'מײַ',
'june'                  => 'יוני',
'july'                  => 'יולי',
'august'                => 'אױגוסט',
'september'             => 'סעפּטעמבער',
'october'               => 'אָקטאָבער',
'november'              => 'נאָװעמבער',
'december'              => 'דעצעמבער',
'jan'                   => 'יאַנ׳',
'feb'                   => 'פֿעב׳',
'mar'                   => 'מאַר׳',
'apr'                   => 'אַפּר׳',
'may'                   => 'מײַ',
'jun'                   => 'יונ׳',
'jul'                   => 'יול׳',
'aug'                   => 'אױג׳',
'sep'                   => 'סעפּ׳',
'oct'                   => 'אָקט׳',
'nov'                   => 'נאָװ׳',
'dec'                   => 'דעץ׳',
'categories'            => 'קאַטעגאָריעס',
'pagecategories'        => '{{PLURAL:$1|קאַטעגאָריע|קאַטעגאָריעס}}',
'category_header'       => 'אַרטיקלען אין קאַטעגאָריע "$1"',
'subcategories'         => 'אונטערקאַטעגאָריעס',
'mainpage'              => 'ערשטע זײַט',
'portal'                => 'געמיינדע',
'portal-url'            => 'Project:געמיינדע',
'about'                 => 'איבער',
'help'                  => 'הילף',
'helppage'              => 'Help:אינהאַלט',
'sitesupport'           => 'נדבֿות',
'sitesupport-url'       => 'Project:נדבֿות',
'edithelp'              => 'הילף ווי צו באַאַרבעטן',
'newwindow'             => '(עס ווערט געהעפֿנט אין אַ נײַעם פענסטער)',
'cancel'                => 'מבטל זײַן',
'qbedit'                => 'ענדערן',
'mytalk'                => 'מײַן רעדן בלאַט',
'navigation'            => 'נאַװיגאַציע',
'currentevents'         => 'נײַקײטן',
'currentevents-url'     => '{{ns:project}}:נײַקײטן',
'disclaimers'           => '
פֿאַרלײקענען',
'privacy'               => 'פערזענלעכקײַט דורכפֿירונג',
'returnto'              => 'צוריקקערן צו $1.',
'tagline'               => 'פֿון {{SITENAME}}',
'search'                => 'זוכן',
'searchbutton'          => 'זוכן',
'go'                    => 'גײן',
'searcharticle'                    => 'גײן',
'history'               => 'אױפֿלאַגעס / מחברים',
'history_short'         => 'געשיכטע',
'printableversion'      => 'דרוק פֿעיקע װערסיע',
'permalink'             => 'אײביקער בונד',
'edit'                  => 'ענדערן און פארעכטן',
'delete'                => 'אַראָפּנעמען',
'protect'               => 'אױסהיטן',
'unprotect'             => 'באַפֿרײַען',
'newpage'               => 'א נײַעם בלאַט',
'talkpage'              => 'רעדן',
'specialpage'           => 'באַזונדער',
'talk'                  => 'רעדן און שמועסן',
'toolbox'               => 'מכשירים',
'otherlanguages'        => 'אין אַנדערע שפראַכן',
'redirectedfrom'        => '(אַריבערגעפֿירט פון $1)',
'lastmodifiedat'          => 'די לעצטע ענדערונג פון די בלאט איז געווען $2, $1.',
'copyright'             => 'אינהאַלט שטייט אונטער $1.',
'jumptosearch'          => 'זוכן',
'ok'                    => 'יאָ',
'youhavenewmessages'    => 'דו האָסט $1 ($2).',
'newmessageslink'       => 'אַ נײַעם מעלדונג',
'newmessagesdifflink'   => 'אונטערשייד פון לעצטע ווערסיע',
'editsection'           => 'בעאַרבעטן',
'toc'                   => 'אינהאַלט',
'showtoc'               => 'װאַיִזן',
'hidetoc'               => 'באַהאַלטן',
'thisisdeleted'         => 'זעה אדער שטעל צוריק $1?',
'viewdeleted'           => 'װאַיִזן $1?',
'nstab-main'            => 'אַרטיקל',
'nstab-user'            => 'באַנוצער זײַט',
'nstab-media'           => 'מעדיע',
'nstab-special'         => 'באַזונדער',
'nstab-image'           => 'בילד',
'nstab-mediawiki'       => 'בשׂורה',
'nstab-template'        => 'מוסטער',
'nstab-help'            => 'הילף',
'nstab-category'        => 'קאַטעגאָריע',
'logouttext'            => '<strong>האָסט זיך ארויסלאָגירט מיט הצלחה.</strong>',
'yourname'              => 'באַנוצער־נאָמען',
'yourpassword'          => 'שפּריכװאָרט',
'remembermypassword'    => 'געדיינק מיך',
'login'                 => 'אַרײַנלאָגירן',
'loginprompt'           => 'איר מוסט ערלויבן קיכלעך ("cookies") אויף צו אַרײַנלאָגירן אינעם {{SITENAME}}.',
'userlogin'             => 'אײַנלאָגירן / זיך אײַנשרײַבן',
'logout'                => 'אַרױסלאָגירן',
'userlogout'            => 'אַרױסלאָגירן',
'notloggedin'           => 'נישט איינגעשריבן',
'youremail'             => 'בליצאַדרעס (*)',
'username'              => 'באַנוצער־נאָמען:',
'uid'                   => 'באַנוצער־נומער:',
'yourlanguage'          => 'שפּראַך:',
'yourvariant'           => 'װאַריאַנט',
'yournick'              => 'אונטערשריפט',
'email'                 => 'בליצבריוו',
'loginsuccess'          => '\'\'\'דו ביסט יעצט אַרײַנלאָגירט אַלץ "$1" אינעם {{SITENAME}}.\'\'\'',
'bold_sample'           => 'טייפּט דאָ אַריין די ווארט אדער ווערטער וואס זאל זיין מיט דיקע אותיות',
'bold_tip'              => 'דאס טוישט צו \'\'\'בּאָלד (דיק)\'\'\' די אויסגעוועלטע ווארט.',
'italic_sample'         => 'דאס וועט מאכן \'\'שיף\'\' די אויסגעוועלט ווארט.',
'italic_tip'            => 'דאס וועט מאכן \'\'שיף\'\' די אויסגעוועלט פאנט.',
'link_sample'           => 'שרײַבט דאָ אַרײַן די װערטער װאָס װעט זײַן אַ לינק צו {{SITENAME}} אַרטיקל אין דעם נושא',
'link_tip'              => 'מאך דאס א \'\'\'לינק\'\'\' צו א וויקיפעדיע ארטיקל',
'headline_sample'       => 'לייג דא אריין דעם טעקסט פונעם נייעם קעפּל',
'headline_tip'          => 'אַ נײַער קעפּל, (אײַנצוטײלן דעם אַרטיקל)',
'nowiki_sample'         => 'אינסערט נישט-פארמארטירטע טעקסט דא',
'nowiki_tip'            => 'דאָס וועט איגנאָרירן די וויקי פֿאָרמאַטינג קאָוד',
'image_tip'             => 'לייג ארויף א בילד',
'sig_tip'               => 'אייער אינטערשריפט, מיט א צייט סטעמפּל ווען איר האט אונטערגעשריבן.',
'hr_tip'                => 'א שטרייך אין די ברייט, (נישט נוצן אפט)',
'summary'               => 'קורץ וואָרט',
'minoredit'             => '‏איך האָב נאָר עטוואָס באַאַרבעט',
'watchthis'             => 'זײט אױפֿפּאַסן',
'savearticle'           => 'זײט אױפֿהיט',
'preview'               => 'פאראויסיגע ווייזונג',
'showpreview'           => 'פֿאָרױסיקע װײַזונג',
'showdiff'              => 'ווײַז מײַן בײַטונג',
'blockedtext'           => 'דיין באנוצער נאמען אדער דיין IP אדרעס איז פאַרשפאַרט געווארן דורך $1 פון וועגן $2.
<p>קענסט זיך ווענדן צו $1 אדער צו אנדערע [[{{ns:Project}}:Administrators|דירעקטארס]] צו דורכרעדן וועגן דעם.<p>

Note that you may not use the "e-mail this user" feature unless you have a valid e-mail address registered in your [[{{ns:Special}}:Preferences|user preferences]].

Your IP address is $3. Please include this address in any queries you make.',
'loginreqlink'          => 'login',
'newarticletext'        => '\'\'\'דער בלאַט עקזיסטירט נאָך נישט!\'\'\' איר קענט יעצט שרײַבן אַ נײַעם אַרטיקל אין די אונטערשטע קעסטל. (זעהט דעם [[הילף:ווי צו שרייבן ווערטן|הילף בלאַט]] ווי אַזוי צו שרײַבן אַרטיקלען).',
'clearyourcache'        => '<div dir="ltr">
\'\'\'Note:\'\'\' After saving, you have to bypass your browser\'s cache to see the changes. \'\'\'Mozilla/Safari/Konqueror:\'\'\' hold down \'\'Shift\'\' while clicking \'\'Reload\'\' (or press \'\'Ctrl-Shift-R\'\'), \'\'\'IE:\'\'\' press \'\'Ctrl-F5\'\', \'\'\'Opera:\'\'\' press \'\'F5\'\'.
</div>',
'previewnote'           => '<strong>דאס איז נאָר אין אַ פֿאָרויסיקע ווייזונג, דער אַרטיקל איז דערווייל נאָך נישט געהיט!</strong>',
'editing'               => 'בעארבעטן $1',
'editinguser'               => 'בעארבעטן $1',
'editconflict'          => 'ענדערן קאנפליקט: $1',
'editingold'            => '<div style="background: #FFBDBD; border: 1px solid #BB7979; color: #000000; font-weight: bold; margin: 2em 0 1em; padding: .5em 1em; vertical-align: middle; clear: both;">פאָרזיכטיג! \'\'באארבעטסט יעצט נישט קיין אקטועלע ווערסיע, אויב דו וועסט היטן דעם באארבעטונג, וועט די לעצטע ענדרענונגען גיין קאַפוט.\'\'<!-- [[{{ns:Project}}:Reverting|removed]] -->.‎</div>',
'copyrightwarning'      => '<small>ביטע מערק אויף אז דיינע אלע טיילונגען אינעם \'\'\'{{SITENAME}}\'\'\' איז אונטער דעם [http://www.gnu.org/copyleft/fdl.html $2] דערלויבן (מער פרטים זעה $1). אויב דו וויִלסט נישט זאלן דיינע טיילונגען דערשיינען ווערן און זאלן אנדערע קענען קאפירן דיין אינהאַלט - ביטע שרייב זיי נישט אַהער. איר זאגט צו אז איר האט געשריבן אן אייגענעם אינהאַלט, אדער האט איר באקומען א ערלויבונג צו איר שרייבן</small>',
'currentrev'            => 'נײַע באַאַרבעטונג',
'previousrevision'      => '→ Older revision',
'nextrevision'          => 'Newer revision ←',
'last'                  => 'צו לעצט',
'histlegend'            => 'Diff selection: mark the radio boxes of the versions to compare and hit enter or the button at the bottom.<br />
Legend: (cur) = difference with current version,
(צו לעצט) = difference with preceding version, מ = minor edit.',
'difference'            => '(אונטערשייד צווישן באַאַרבעטונגען)',
'compareselectedversions'=> 'פארגלייך סעלעקטירטע ווערסיעס',
'searchresulttext'      => 'לערנען מער ווי צו זוכן אינעם {{SITENAME}} [[{{ns:Help}}:זוכן|קוועטשט אַהער]]',
'searchsubtitle'           => '[[:$1]]',
'searchsubtitleinvalid'           => '$1',
'noexactmatch'             => 'דערווייל איז נאָך נישטאָ א בלאט מיט דעם טיטל.<br /> איר זײַט געלאדנט [[:$1|אויפשרייבן א נייעם בלאט]], אדער [[Project:בעטן ווערטן|בעטן פון פריינד]] זאלן זיי שרייבן.',
'viewprevnext'          => '($1) ($2) ($3).',
'powersearch'           => 'זוכן',
'blanknamespace'        => '(אַרטיקל)',
'preferences'           => 'אײַנשטעלן',
'changepassword'        => 'שפּריכװאָרט איבערמאַכן',
'skin'                  => 'סקין',
'math'                  => 'פאָרמאַל',
'datetime'              => 'דאַטע אונד צײַט',
'prefs-personal'        => 'באַנוצער פראָפֿיל',
'prefs-rc'              => 'לעצטע ענדערונגען',
'prefs-misc'            => 'באַאַרבעטן',
'saveprefs'             => 'אױפֿהיטן',
'resetprefs'            => 'צוריק שטעלן צום נאָרמאַל',
'oldpassword'           => 'אַלטע שפּריכװאָרט:',
'newpassword'           => 'נייע פּעסוואָרד:',
'retypenew'             => 'שפריכוואָרט ווידער שרײַבן:',
'textboxsize'           => 'באַאַרבעטן',
'rows'                  => 'שורות:',
'columns'               => 'זײַלן:',
'searchresultshead'     => 'זוכן',
'recentchangescount'    => 'דער צאָל פון ליניעס אין די לעצטע ענדערונגען:',
'allowemail'            => 'ערלויבן אנדערע צו אײַך שיקן בליצבריוון',
'files'                 => 'טעקעס',
'changes'               => 'ענדערונגען',
'recentchanges'         => 'לעצטע ענדערונגען',
'rcnote'                => 'אונטער זײַנען די לעצטע <strong>$1</strong> ענדערונגען אין די לעצטע <strong>$2</strong> טאָג. $3',
'rclistfrom'            => 'װײַזן די נײַע ענדערונגען זײַט $1',
'rcshowhideminor'       => '$1 מינערדיקע רעדאַקטירן',
'rcshowhidebots'        => '$1 ראָבאָטן',
'rcshowhideliu'         => '$1 אײַנגעשריבינע באַנוצערס',
'rcshowhideanons'       => '$1 אַנאָנימע באַנוצערס',
'rcshowhidepatr'        => '$1 טעכנישע אַקציעס',
'rcshowhidemine'        => '$1 מײַנע טיילונגען',
'rclinks'               => 'װײַזן די לעצטע $1 ענדערונגען אין דעם לעצטע $2 טאָג.<br />$3',
'diff'                  => 'אונטערשייד',
'hist'                  => 'געשיכטע',
'hide'                  => 'באַהאַלטן',
'show'                  => 'װאַיִזן',
'minoreditletter'       => 'מ',
'newpageletter'         => 'נ',
'sectionlink'           => '←',
'upload'                => 'בילדער/פיילס אַרױפֿלאָדירן',
'uploadbtn'             => 'טעקע אַרױפֿלאָדירן',
'uploadlog'             => 'אויפלאָדירע לאָגבוך',
'savefile'              => 'טעקע אױפֿהיטן',
'uploadedimage'         => 'אַרױפֿלאָדירט "[[$1]]"',
'imagelisttext'         => 'Below is a list of $1 files sorted $2.',
'ilsubmit'              => 'זוכן',
'statistics'            => 'סטאַטיסטיק',
'sitestatstext'         => 'יעצט איז דא \'\'\'$2\'\'\' אַרטיקלען אינעם [[{{SITENAME}}]].

און \'\'\'$1\'\'\' בלעטער (אריינגערעכנט מיט די אַרומנעמיקע בלעטער ווי "רעדן בלעטער", "רידיירעקטן" א.א.וו).

‎\'\'\'$8\'\'\' files have been uploaded.‎

\'\'\'$4\'\'\' באַאַרבעטונגען.
דורכשניטלעך \'\'\'$5\'\'\' באַאַרבעטונגען פאַר יעדן בלאַט.',
'disambiguationspage'   => '{{ns:template}}:באַטײַטן',
'brokenredirects'       => 'צובראָכענע רידיירעקטן',
'nbytes'                => '$1 bytes',
'nlinks'                => '$1 לינקן',
'wantedpages'           => 'װינטשט זײטן',
'mostcategories'        => 'אַרטיקלען מיט די מערקסטע קאַטעגאָריעס',
'mostrevisions'         => 'אַרטיקלען מיט די מערקסטע באַאַרבעטונגען',
'randompage'            => 'צופֿעליקער אַרטיקל',
'specialpages'          => 'ספּעציעלע זײטן',
'recentchangeslinked'   => 'פֿאַרבונדענע ענדערונגען',
'ancientpages'          => 'עלטסטער זײטן',
'move'                  => 'באַװעגן',
'booksources'           => 'דרויסנדיקע ליטעראַטור ISBN',
'categoriespagetext'    => 'די ווײַטערדיקע קאַטעגאָריען עקסיסטירט אין {{SITENAME}}.',
'allpagessubmit'        => 'גיין',
'emailpage'             => 'אימעיל\'ט דעם באנוצער.',
'defemailsubject'       => 'וויקיפעדיער בליצבריוו',
'emailfrom'             => 'פון',
'emailto'               => 'צו',
'emailsubject'          => 'טעמע',
'emailmessage'          => 'מעלדונג',
'emailsend'             => 'שיקן',
'watchlist'             => 'אַכטונגע ליסט',
'addedwatch'            => 'צוגעלייגט געוואָרן צום "אַכטונגע ליסט"',
'addedwatchtext'        => 'דער אַרטיקל "[[:$1]]" איז צוגעלײגט געוואָרן צו דײַן [[{{ns:Special}}:Watchlist|אַכטונגע ליסט]].

<div dir="ltr">
Future changes to this page and its associated Talk page will be listed there,
and the page will appear \'\'\'bolded\'\'\' in the [[{{ns:Special}}:Recentchanges|list of recent changes]] to
make it easier to pick out.

<p>If you want to remove the page from your watchlist later, click "Stop watching" in the sidebar.</p>
</div>',
'removedwatch'          => 'אַראָפּגענומען פונעם "אַכטונגע ליסט"',
'removedwatchtext'      => 'דער אַרטיקל "[[:$1]]" איז אָפּגעראַמעט געוואָרן פון דײַן אַכטונגע ליסט',
'watch'                 => 'אױפֿפּאַסן',
'watchthispage'         => 'זײט אױפֿפּאַסן',
'unwatch'               => 'אויפֿהערן אויפֿפּאַסן',
'wlhideshowown'         => '$1 מײנע רעדאַקטירן.',
'wlhideshowbots'        => '$1 ראָבאָט רעדאַקטירן.',
'deletepage'            => 'זײט אַראָפּנעמען',
'excontent'             => 'מיטן אינהאַלט: \'$1\'',
'excontentauthor'       => 'מיטן אינהאַלט: \'$1\' (זיין איינציגער באַאַרבעטער: \'$2\')',
'rollback_short'        => 'אויפֿריכטן',
'rollbacklink'          => 'צוריקדרייען',
'revertpage'            => 'אויפֿגעריכט פון באַנוצער $2 צוריק צום לעצטע ווערסיע פון באַנוצער $1',
'undeletebtn'           => 'Restore!',
'namespace'             => 'באַגרעניצן צו:',
'invert'                => 'ווײַז אַלע אויסער די',
'contributions'         => 'באנוצער\'ס אלע טיילונגען',
'mycontris'             => 'מײַנע טיילונגען',
'whatlinkshere'         => 'װאָס די אױף דאָס זײט פֿאַרבינדט',
'blockip'               => 'באַניצער אַרױסטרײבן',
'ipbother'              => 'אַנדער צײַט',
'ipboptions'            => '15 מינוטן:15 minutes,
1 שעה:1 hour,
2 שעהן:2 hours,
1 טאָג:1 day,
3 טעג:3 days,
1 װאָך:1 week,
2 װאָכן:2 weeks,
1 מאָנאַט:1 month,
3 מאָנאַטן:3 months,
6 מאָנאַטן:6 months,
1 יאָר:1 year,
אויף אייביק:infinite',
'ipbotheroption'        => 'אַנדער',
'infiniteblock'         => 'אויף אייביק',
'blocklink'             => 'אַרױסטרײַבן',
'unblocklink'           => 'באַפֿרײַען',
'contribslink'          => 'באַנוצערס שרײַבונגען',
'blocklogentry'         => 'פֿאַשפּאַרט "[[$1]]" אויף אַ תקופה פון $2',
'pagemovedsub'          => 'באַוועגט מיט הצלחה',
'pagemovedtext'         => 'Page "[[$1]]" באַוועגנט צו "[[$2]]".',
'movedto'               => 'באַוועגנט צו',
'1movedto2'             => '[[:$1]] באַוועגנט צו [[:$2]]',
'1movedto2_redir'       => '[[:$1]] באַוועגט צו [[:$2]] פון',
'revertmove'            => 'צוריקדרייען',
'allmessagesname'       => 'נאָמען',
'tooltip-search'        => 'זוכן {{SITENAME}} [alt-f]',
'tooltip-diff'          => 'Show which changes you made to the text. [alt-v]',
'lastmodifiedatby'        => 'די לעצטע ענדערונג פון די בלאט איז געווען $2, $1 ביי $3.',
'and'                   => 'און',
'subcategorycount'      => 'ס\'איז דאָ $1 אונטערקאַטעגאָריעס צו די קאַטעגאָריע.',
'categoryarticlecount'  => 'ס\'איז דאָ $1 אַרטיקלען אין די קאַטעגאָריע.',
'monobook.js'           => '/* <div style="direction: ltr;"><pre> */
var ta = new Object();
ta["pt-userpage"]           = [".", "מיין באניצער בלאט"];
ta["pt-anonuserpage"]       = [".", "באניצער בלאט פון אנינונימער באניצער"];
ta["pt-mytalk"]             = ["n", "מיין רעדן בלאט"];
ta["pt-anontalk"]           = ["n", "רעדן אויף אנינונימע באטייליגען"];
ta["pt-preferences"]        = ["",  "מיינע פעיווערעטס"];
ta["pt-watchlist"]          = ["l", "אויפפּאסן בלעטער"];
ta["pt-mycontris"]          = ["y", "מיינע באטייליגונגן"];
ta["pt-login"]              = ["o", "ביטע איינשרייבן, אבער עס איז נישט קיין חוב"];
ta["pt-anonlogin"]          = ["o", "סבעסער איינשרייבן, אבער עס איז נישט קיין חוב"];
ta["pt-logout"]             = ["", "זיך אויסשרייבן"];
ta["ca-talk"]               = ["t", "שמועס אויף דעם בלאט"];
ta["ca-edit"]               = ["e", "קענסט פארעכטן דעם בלאט. ביטע זיך באניצן מיט קנעפל \"פאראויסיגע ווייזונג\" בעפארן אויפהיטן."];
ta["ca-addsection"]         = ["+", "לייג צו אייער ווארט צו דעם שמועס"];
ta["ca-viewsource"]         = ["e", "דאס איז א פארשלאסן בלאט, קענסט נאר קוקן איר מקור"];
ta["ca-history"]            = ["h", "פריערדיגע ווערסיעס פון דעם בלאט."];
ta["ca-protect"]            = ["=", "הגנו על דף זה"];
ta["ca-delete"]             = ["d", "אויסמעקן דעם בלאט"];
ta["ca-undelete"]           = ["d", "צוריק דרייען די ענדערונגען פון דעם בלאט פארן מעקן"];
ta["ca-move"]               = ["m", "פירט אריבער דעם בלאט"];
ta["ca-watch"]              = ["w", "לייגט צו דעם בלאט אויפצופאסן"];
ta["ca-unwatch"]            = ["w", "נעמט אראפ דעם בלאט פון אויפפאסן"];
ta["search"]                = ["f", "זוכט אינעם סייט"];
ta["p-logo"]                = ["",  "הויפט זייט"];
ta["n-mainpage"]            = ["z", "באזוכט דעם הויפט זייט"];
ta["n-portal"]              = ["",  "גייט אריין אין די געמיינדע צו שמועסן"];
ta["n-currentevents"]       = ["",  "לעצטע אינפארמאציע איבער טואונגען פון וויקיפעדיע"];
ta["n-recentchanges"]       = ["r", "ליסטע פון לעצטע ענדערונגען"];
ta["n-randompage"]          = ["x", "וועלט אויס א צופעליגער בלאט"];
ta["n-help"]                = ["",  "הילף"];
ta["n-sitesupport"]         = ["",  "צדקה אויפצוהאלטן דעם סייט"];
ta["t-whatlinkshere"]       = ["j", "אלע בלעטער וואס פארבינדען צו דעם בלאט"];
ta["t-recentchangeslinked"] = ["k", "אלע ענדערונגען פון בלעטער וואס זענען אהער פארבינדען"];
ta["feed-rss"]              = ["",  "לייגט צו אן אטאמאטישער אפדעיט פון אר.עס.עס. RSS"];
ta["feed-atom"]             = ["",  "לייג צו אן אטאמאטישער אפדעיט דורך אטאם Atom"];
ta["t-contributions"]       = ["",  "אלע שרייבאכצער פון דעם באנוצער"];
ta["t-emailuser"]           = ["",  "שיקט אן אימעיל פאר דעם באניצער"];
ta["t-upload"]              = ["u", "לייגט ארויף פיילס און בילדער"];
ta["t-specialpages"]        = ["q", "אלע ספעציעלע בלעטער"];
ta["ca-nstab-main"]         = ["c", "בליקט אינעם אינהאלט בלאט"];
ta["ca-nstab-user"]         = ["c", "קוקט אין באניצער בלאט"];
ta["ca-nstab-media"]        = ["c", "קוקט אין די מידיע בלעטער"];
ta["ca-nstab-special"]      = ["",  "דאס איז א ספעציעלע בלאט, מקען איר נישט ענדערן"];
ta["ca-nstab-project"]      = ["a", "צפו בדף המיזם"];
ta["ca-nstab-image"]        = ["c", "צפו בדף תיאור התמונה"];
ta["ca-nstab-mediawiki"]    = ["c", "צפו בהודעת המערכת"];
ta["ca-nstab-template"]     = ["c", "צפו בתבנית"];
ta["ca-nstab-help"]         = ["c", "באזוכט די הילף בלעטער"];
ta["ca-nstab-category"]     = ["c", "באזוכט די קאטעגאריע בלעטער"];

function bidiSwitchSetup() {
	var editform = document.getElementById("wpTextbox1");
	if (editform == null) {
		return;
	}
	
	bidiAddButton(editform, "Default", function(style) {
		style.direction = "inherit";
		style.unicodeBidi = "inherit";
	});
	bidiAddButton(editform, "dir=ltr", function(style) {
		style.direction = "ltr";
	});
	bidiAddButton(editform, "dir=rtl", function(style) {
		style.direction = "rtl";
	});
	bidiAddButton(editform, "bidi=normal", function(style) {
		style.unicodeBidi = "normal";
	});
	bidiAddButton(editform, "bidi=override", function(style) {
		style.unicodeBidi = "bidi-override";
	});
}

function bidiAddButton(before, label, action) {
	var button = document.createElement("input");
	button.type = "button";
	button.value = label;
	button.onclick = function(event) {
		var box = document.getElementById("wpTextbox1");
		if (box == null) {
			alert("Broken! Edit box missing.");
		} else {
			//var style = document.getOverrideStyle(box, null);
			var style = box.style;
			action(style);
		}
	}
	before.parentNode.insertBefore(button, before);
}

hookEvent(\'load\', bidiSwitchSetup);

/* </pre></div> */',
'previousdiff'          => 'פריעריגע אונטערשייד →',
'nextdiff'              => 'קומענדיקע אונטערשייד ←',
'newimages'             => 'גאַלעריע אויף נײַע בילדער',
'showhidebots'          => '($1 ראָבאָמן)',
'exif-artist'           => 'מחבר',
'exif-componentsconfiguration-0'=> 'עס עקזיסטירט נישט.',
'recentchangesall'      => 'אַלע',
'imagelistall'          => 'אַלע',
'watchlistall1'         => 'אַלע',
'watchlistall2'         => 'אַלע',
'namespacesall'         => 'אַלע',
'confirm_purge'         => '<span dir="ltr">Clear the cache of this page?</span> $1',
'confirm_purge_button'  => 'יאָ',
);
?>

<?php
/** Wolof (Wolof)
 *
 * @addtogroup Language
 *
 * @author Ibou
 * @author SF-Language
 * @author Siebrand
 * @author SPQRobin
 */

$fallback = 'fr';

$messages = array(
# User preference toggles
'tog-underline'               => 'Rëddaatu lëkkalekaay yi :',
'tog-highlightbroken'         => 'Wone leen <a href="" class="new">ñu xonq</a> lëkkalekaay yiy yóbbe ciy xët yu amul (lu ko moy :  lu mel nii <a href="" class="internal">?</a>)',
'tog-justify'                 => 'Leru-maaseel xise yi',
'tog-hideminor'               => 'Nëbb coppite yu néewal yi mujj',
'tog-extendwatchlist'         => 'Jëfandikul limu toppte gi ñu gënal',
'tog-usenewrc'                => 'Jëfandikul coppite yu mujj yi ñu gënal (JavaScript)',
'tog-numberheadings'          => 'Koj yi jox lim seen bopp',
'tog-showtoolbar'             => 'Wone bànqaasu njëlu coppite bi (JavaScript)',
'tog-editondblclick'          => 'Cuq cuqaatal ngir soppi aw xët (JavaScript)',
'tog-editsection'             => 'Soppi ab xaaj jaare ko cib lëkkalekaay [Soppi]',
'tog-editsectiononrightclick' => 'Soppi ab xaaj cib cuqub ndeyjoor ci kojam  (JavaScript)',
'tog-showtoc'                 => 'Wone tëralinu ne-ne yi (ngir xët yi ëpp 3 xaaj)',
'tog-rememberpassword'        => 'Fattaliku sama baatujàll(cookie)',
'tog-editwidth'               => 'Wone palanteeru coppite gi ci yaatuwaay bépp',
'tog-watchcreations'          => 'Yokk ci sama limu toppte xët yi may sos',
'tog-watchdefault'            => 'Yokk ci sama limu toppte xët yi may soppi',
'tog-watchmoves'              => 'Yokk ci sama limu toppte xët yi may tuddaat',
'tog-watchdeletion'           => 'Yokk ci sama limu toppte xët yi may far',
'tog-minordefault'            => 'jàppe samay coppite ni yu néewal saa su ne',
'tog-previewontop'            => 'Tegal wonendi ngi ci kaw balaa boyotu coppite gi',
'tog-previewonfirst'          => 'wone wonendi gi su dee soppi gu njëkk la',
'tog-nocache'                 => 'Doxadil ndenciti xët yi',
'tog-enotifwatchlistpages'    => 'Yónne ma ab bataaxal su ab xët bu ne ci sama limu toppte soppikoo',
'tog-enotifusertalkpages'     => 'Yónne ma ab bataaxal su ay coppite amee ci sama xëtu waxtaanuwaay',
'tog-enotifminoredits'        => 'Yónne ma ab bataaxal donte coppite yu néew la ñu',
'tog-enotifrevealaddr'        => 'Wone sama makkaan gu mbëjfeppal ci bataaxali yëgle yi',
'tog-shownumberswatching'     => "Wone limu jëfandikukat yi'y topp wii xët",
'tog-fancysig'                => 'Xaatim bu ñumm (amul lëkkalekaay bu boppal)',
'tog-externaleditor'          => 'jëfandiku soppikat bu biti saa su ne',
'tog-externaldiff'            => 'Jëfandiku ab méngalekaay bu biti saa su ne',
'tog-showjumplinks'           => 'Doxalal lëkkalekaay yii di « joowin » ak « seet »',
'tog-uselivepreview'          => 'Jëfandikul wonendi gu gaaw gi (JavaScript)',
'tog-forceeditsummary'        => 'Wax ma ko suma mottaliwul ndefu boyotu sanni-kàddu bi',
'tog-watchlisthideown'        => 'Nëbb samay coppite ci limu toppte gi',
'tog-watchlisthidebots'       => 'Nëbb coppite yi bot yi def ci biir limu toppte bi',
'tog-watchlisthideminor'      => 'Nëbb coppite yu néewal yi ci biir limu toppte bi',
'tog-ccmeonemails'            => 'Yónnee ma ab duppi bu bataaxal yi may yónnee yeneen jëfandikukat yi',
'tog-diffonly'                => 'Bul wone ndefu xët yi ci biir diffs yi',
'tog-showhiddencats'          => 'Wone wàll yi nëbbu',

'underline-always'  => 'Saa su ne',
'underline-never'   => 'Mukku',
'underline-default' => 'Aju ci joowukaay bi',

'skinpreview' => '(Wonendil)',

# Dates
'sunday'        => 'dibéer',
'monday'        => 'altine',
'tuesday'       => 'talaata',
'wednesday'     => 'alarba',
'thursday'      => 'alxamis',
'friday'        => 'ajjuma',
'saturday'      => 'gaawu',
'sun'           => 'dib',
'mon'           => 'alt',
'tue'           => 'tal',
'wed'           => 'ala',
'thu'           => 'alx',
'fri'           => 'ajj',
'sat'           => 'gaa',
'january'       => 'Tamxarit',
'february'      => 'Diggi-gamu',
'march'         => 'Gamu',
'april'         => 'Rakki-gamu',
'may_long'      => 'Rakkaati-gamu',
'june'          => 'Maami-koor',
'july'          => 'Ndeyi-koor',
'august'        => 'Baraxlu',
'september'     => 'Koor',
'october'       => 'Kori',
'november'      => 'Diggi-tabaski',
'december'      => 'Tabaski',
'january-gen'   => 'Tamxarit',
'february-gen'  => 'Diggi-gamu',
'march-gen'     => 'Gamu',
'april-gen'     => 'Rakki-gamu',
'may-gen'       => 'Rakkaati-gamu',
'june-gen'      => 'Maami-koor',
'july-gen'      => 'Ndeyi-koor',
'august-gen'    => 'Baraxlu',
'september-gen' => 'Koor',
'october-gen'   => 'Kori',
'november-gen'  => 'Diggi-tabaski',
'december-gen'  => 'Tabaski',
'jan'           => 'Tam',
'feb'           => 'Dig',
'mar'           => 'Gam',
'apr'           => 'Rak',
'may'           => 'Rakkaati',
'jun'           => 'Maa',
'jul'           => 'Nde',
'aug'           => 'Bar',
'sep'           => 'Koo',
'oct'           => 'Kor',
'nov'           => 'Diggi-ta',
'dec'           => 'Tab',

# Categories related messages
'categories'                     => 'Wàll',
'categoriespagetext'             => 'Wàll yii ñoo am ci biir wiki gi.',
'special-categories-sort-count'  => 'nose sàq',
'special-categories-sort-abc'    => 'nose abajada',
'pagecategories'                 => '{{PLURAL:$1|Wàll |Wàll }}',
'category_header'                => 'Xët yi ci wàll gi « $1 »',
'subcategories'                  => 'Ron-wàll',
'category-media-header'          => 'Jukki yi ci wàll wi « $1 »',
'category-empty'                 => "''Nii-nii wàll wii ëmbul tus, dub ron-wàll, dub jukki, dub dencukaay. ''",
'hidden-categories'              => '{{PLURAL:$1|wàll bi nëbbu|wàll yi nëbbu}}',
'hidden-category-category'       => 'Wàll yi nëbbu', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|bii wàll benn ron-wàll rekk la am, di biy toftal.|Bii wàll am na {PLURAL:$1|ron-wàll|$1 ciy ron-wàll}}, ci lim bu tollook $2.}}',
'category-subcat-count-limited'  => 'Bii wàll am na {{PLURAL:$1|ron-wàll|$1 ciy ron-wàll}}.',
'category-article-count'         => '{{PLURAL:$2|Bii wàll wenn xët rekk la am, di wiy toftal.| Bii wàll {{PLURAL:$1|xët wiy toftal|$1 xët yiy toftal}} la ëmb, ci lim bu tollook $2.}}',
'category-article-count-limited' => 'Bii wàll ëmb na {{PLURAL:$1|xët wiy toftal |$1 xët yiy toftal}}.',
'category-file-count'            => '{{PLURAL:$2|Bii wàll wenn xët rekk la ëmb, di wiy toftal ci suuf.|Bii wàll ëmb na {{PLURAL:$1| xët|$1 ciy xët}}, ci lim bu tollook  $2.}}',
'category-file-count-limited'    => 'Bii wàll moo ëmb {{PLURAL:$1|xët wiy toftal|$1 xët yiy toftal}}.',
'listingcontinuesabbrev'         => '(desit)',

'mainpagetext'      => "<big>'''Sampug MediaWiki gi sotti na . '''</big>",
'mainpagedocfooter' => 'Saytul [http://meta.wikimedia.org/wiki/Ndimbal:Ndefu Gindikaayu jëfandikukat bi] ngir yeneeni xibaar ci jëfandiku gu tëriin gi.

== Tambali ak MediaWiki ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Limu jumtukaayi kocc-koccal gi]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ MediaWiki]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Limu waxtaan ci liy-génn ci MediaWiki]',

'about'          => 'Ci mbirim',
'article'        => 'Jukki',
'newwindow'      => '(Day ubbeeku ci beneen palanteer)',
'cancel'         => 'Neenal',
'qbfind'         => 'Seet',
'qbbrowse'       => 'Lemmi',
'qbedit'         => 'Soppi',
'qbpageoptions'  => 'Xëtuw tànneef',
'qbpageinfo'     => 'Xëtuw xibaar',
'qbmyoptions'    => 'Samay tànneef',
'qbspecialpages' => 'Xëti jagleel yi',
'moredotdotdot'  => 'Ak yeneen...',
'mypage'         => 'Samaw xët',
'mytalk'         => 'Xëtu waxtaanuwaay',
'anontalk'       => 'Waxtaan ak bii IP',
'navigation'     => 'Joowiin',
'and'            => 'ak',

# Metadata in edit box
'metadata_help' => 'Jéeginjoxe :',

'errorpagetitle'    => 'Njuumte',
'returnto'          => 'Dellu ci wii xët $1.',
'tagline'           => 'Ab jukkib {{SITENAME}}.',
'help'              => 'Ndimbal',
'search'            => 'Seet',
'searchbutton'      => 'Seet',
'go'                => 'Ayca',
'searcharticle'     => 'Ayca',
'history'           => 'Jaar-jaaru xët wi',
'history_short'     => 'Jaar-jaar',
'updatedmarker'     => 'Ci samag nemmeeku gu mujj lañ ko soppi',
'info_short'        => 'Xibaar',
'printableversion'  => 'Sumb mu móoluwu',
'permalink'         => 'Lëkkalekaay yu fi nekkandi',
'print'             => 'Móol',
'edit'              => 'Soppi',
'create'            => 'Sos',
'editthispage'      => 'Soppi xët wii',
'create-this-page'  => 'Sos wii xët',
'delete'            => 'Far',
'deletethispage'    => 'Far wii xët',
'undelete_short'    => 'Loppanti {{PLURAL:$1|1 coppite| $1 ciy coppite}}',
'protect'           => 'Aar',
'protect_change'    => 'soppi',
'protectthispage'   => 'Aar wii xët',
'unprotect'         => 'Aaradi',
'unprotectthispage' => 'Aaradil wii xët',
'newpage'           => 'Xët wu bees',
'talkpage'          => 'Xëtu waxtaanuwaay',
'talkpagelinktext'  => 'Waxtaan',
'specialpage'       => 'Xëtu jagleel',
'personaltools'     => 'Samay jumtukaay',
'postcomment'       => 'Yokk ab sanni-kàddu',
'articlepage'       => 'Gis jukki bi',
'talk'              => 'Waxtaan',
'views'             => 'Wonewiin',
'toolbox'           => 'Boyotu jumtukaay yi',
'userpage'          => 'Xëtu jëfandikukat',
'projectpage'       => 'Wone xëtu sémb wi',
'imagepage'         => 'Wone xëtu nataal wi',
'mediawikipage'     => 'Wone xëtu bataaxal wi',
'templatepage'      => 'Xool xëtu royuwaay wi',
'viewhelppage'      => 'Xoolal xëtu ndimbal wi',
'categorypage'      => 'Xool xëtu wàll yi',
'viewtalkpage'      => 'Xëtu waxtaanuwaay',
'otherlanguages'    => 'Yeneeni làkk',
'redirectedfrom'    => '(Yoonalaat gu jóge $1)',
'redirectpagesub'   => 'Xëtu yoonalaat',
'lastmodifiedat'    => 'Coppite bu mujj bu xët wii $1 ci $2.<br />', # $1 date, $2 time
'viewcount'         => 'Xët wii yër nañ ko $1 yoon.',
'protectedpage'     => 'Xët wi dañ koo aar',
'jumpto'            => 'Dem :',
'jumptonavigation'  => 'Joowiin',
'jumptosearch'      => 'Seet',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Ci mbiri {{SITENAME}}',
'aboutpage'         => 'Project:Ci mbiri',
'bugreports'        => 'Ngértey njuumte yi',
'bugreportspage'    => 'Project:Ngértey njuumte',
'copyright'         => 'Ëmbit li jàppandi na ci $1.',
'currentevents'     => 'Luy xew',
'currentevents-url' => 'Project:Luy xew',
'disclaimers'       => 'Ay aartu',
'disclaimerpage'    => 'Project:Aartu yu daj',
'edithelp'          => 'Ndimbal',
'edithelppage'      => 'Help:Nooy soppee aw xët',
'faq'               => 'Laaj yi ëpp',
'faqpage'           => 'Project:FAQ',
'helppage'          => 'Help:Ndimbal',
'mainpage'          => 'Xët wu njëkk',
'policy-url'        => 'Project:àtte',
'portal'            => 'Askan',
'portal-url'        => 'Project:Xët wu njëkk',
'privacy'           => 'Politigu mbóot',
'privacypage'       => 'Project:Xibaar ci say mbóot',
'sitesupport'       => 'Joxe ag ndimbal',
'sitesupport-url'   => 'Project:Joxe ag ndimbal',

'badaccess'        => 'Njuumte ci ndigël gi',
'badaccess-group0' => 'Amoo ay sañ-sañ yu doy ngir man a def li nga bëgg a def.',
'badaccess-group1' => 'jëf ji ngay jéem a def ñi bokk ci mbooloo mii $1 rek ñoo ko man.',
'badaccess-group2' => 'Jëf ji ngay jéem a def, jëfandikukatu mbooloo yii $1 rek ñoo ko man.',
'badaccess-groups' => 'Jëf ji ngay jéem a def, jëfandikukatu mbooloo yii $1 rek ñoo ko man.',

'versionrequired'     => 'Laaj na $1 sumbum MediaWiki',
'versionrequiredtext' => 'Laaj na $1 sumbum MediaWiki ngir man a jëfandikoo wii xët. Xoolal [[Special:Version|fii]]',

'ok'                      => 'waaw',
'retrievedfrom'           => 'Ci « $1 » lañ ko jële',
'youhavenewmessages'      => 'Am nga $1 ($2).',
'newmessageslink'         => 'Bataaxal yu bees',
'newmessagesdifflink'     => 'Coppite gu mujj',
'youhavenewmessagesmulti' => 'Am nga ay bataaxal yu bees ci $1',
'editsection'             => 'Soppi',
'editold'                 => 'Soppi',
'editsectionhint'         => 'Soppi bii xaaj : $1',
'toc'                     => 'Tëraliin',
'showtoc'                 => 'Wone',
'hidetoc'                 => 'Nëbb',
'thisisdeleted'           => 'Da ngaa bëgg a wone walla loppanti $1 ?',
'viewdeleted'             => 'Xool $1 ?',
'restorelink'             => '{{PLURAL:$1|1 coppite lañ far |$1 ciy coppite lañ far}}',
'feedlinks'               => 'Wal',
'feed-invalid'            => 'Gii xeetu wal baaxul.',
'site-rss-feed'           => 'Walu RSS gu $1',
'site-atom-feed'          => 'Walu Atom gu $1',
'page-rss-feed'           => 'Walu RSS gu "$1"',
'page-atom-feed'          => 'Walu Atom gu "$1"',
'red-link-title'          => '$1 (xët wi amagul)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Jukki',
'nstab-user'      => 'Xëtu jëfandikukat',
'nstab-special'   => 'Jagleel',
'nstab-project'   => 'Xëtu sémb',
'nstab-image'     => 'Dencukaay',
'nstab-mediawiki' => 'Bataaxal',
'nstab-template'  => 'Royuwaay',
'nstab-help'      => 'Ndimbal',
'nstab-category'  => 'Wàll',

# Main script and global functions
'nosuchaction'      => 'Jëf ji xameesu ko',
'nosuchactiontext'  => 'Jëf ji ñu biral ci bii URL wiki bi xammeewu ko.',
'nosuchspecialpage' => 'Xëtu jagleel wu amul',
'nospecialpagetext' => 'Da nga laaj aw xëtu jagleel wu wiki bi xamul. Ab limu xëti jagleel yépp, ma nees na koo gis ci [[{{ns:special}}:Specialpages]]',

# General errors
'error'                => 'Njuumte',
'databaseerror'        => 'Njuumtey dattub njoxe bi',
'dberrortext'          => 'Njuumtey mbindin ci laaj bi nga yóonne dattub njobe bi. Man na nekk it ab njuumte ci tëriin wi. Laaj bi ñu mujje yónne ci dattub njoxe bi moo doonoon:
<blockquote><tt>$1</tt></blockquote>
bàyyikoo ci bii solo « <tt>$2</tt> ». MySQL moo yónnewaat bii njuumte « <tt>$3 : $4</tt> ».',
'dberrortextcl'        => 'Ab laaj ca dattub njoxe ba jur na ab njuumtey mbindin. Laaj bi ñu mujje yónne dattub njoxe bi moo doonoon : « $1 » bàyyikoo ci bii solo « $2 ». MySQL delloo na njuumte li « $3 : $4 ».',
'noconnect'            => 'Jéggalu! ngir ay tolof-tolofi xarala, fi mu ne nii duggu gi jàppandiwul. <br />
$1',
'nodb'                 => 'Falug dattub njoxe bii di $1 antuwul',
'cachederror'          => 'Xët wii ab duppitu ndencit li la rekk, kon xéj-na beesul.',
'laggedslavemode'      => 'Moytul, wii xët man naa bañ a man dékku coppite yi ñu mujjee def',
'readonly'             => 'Dattub njoxe li dañ kaa caabi',
'enterlockreason'      => 'Biralal ngirtey tëj gi ak diir bi mu war a amee',
'readonlytext'         => 'Ci jamono jii yokk yeek coppitey xët yi jàppandiwuñ ndax dattub njoxe bi dañ kaa caabi, xéj-na dañoo nekk ciy liggéey, su ñu noppee rekk dattub njoxe bi baaxaat.
Yorkat bi def caabi ji, joxe na yii lay :$1',
'missingarticle'       => 'Dattub njoxe bi manuta fexe ba gis mbindi xët wi tuddu « $1 », donte xët wi am na.

Li koy man a waral mooy, méngale ñaari sumbi jaar-jaar walla lëkkalekaay buy jëme ciw xët wu faru.
Su dul loolu, kon ab njuumte lay doon ci tëriinub MediaWiki bi.

Di sakku ci yaw nga jébbal li xew ab yorkat, te nga jox ko màkkaanuw xët wi mu ñeel.',
'readonly_lag'         => 'Dattub njoxe bi daa caabiwu ngir may ñaareeli joxekat yi dap joxekat bu njëkk bi.',
'internalerror'        => 'Njuumte gu biir',
'internalerror_info'   => 'Njuumte gu biir : $1',
'filecopyerror'        => 'Duppig dencukaay bii di « $1 » jëm « $2 » antuwul.',
'filerenameerror'      => 'Tuddewaatug « $1 » niki « $2 » antuwul.',
'filedeleteerror'      => 'Farug dencukaay bii di « $1 » antuwul.',
'directorycreateerror' => 'Sosug wayndare bii di « $1 » antuwul.',
'filenotfound'         => 'Gisug dencukaay bii di « $1 » antuwul.',
'fileexistserror'      => 'Mbind mi ci wii wayndare « $1 » antuwul : dencukaay bi am na ba noppi',
'badarticleerror'      => 'Gii jëf defuwul ci wii xët.',
'cannotdelete'         => 'Farug xët walla dencukaay bi nga joxoñ antuwul. (xayna far gi am na keneen ku ko def ba noppi.)',
'badtitle'             => 'Koj bu baaxul',
'badtitletext'         => 'Kojug xët wi nga laaj baaxul, amul dara walla  day di kojjug diggantelàkk walla diggantesémb yu seen lonkoo baaxul. Xayna it dafa am benn walla ay araf yu ñu manuta jëfandikoo cib koj.',
'perfdisabled'         => 'Jéggalu! bii jëfukaay dañ kaa doxadilandi ndaxte day yeexal lool dattub njoxe bi, ba tax kenn manatul a jëfandikoo bi wiki.',
'perfcached'           => 'Lii ab duppitu ndencitu sumb mi la, kon xéj-na beesul.',
'perfcachedts'         => 'Njoxe yii di toftal ab duppitu ndencitu dattub njoxe bi la, te yeesalam gu mujj mi ngi am ci: $1',
'querypage-no-updates' => 'Yeesal yu xët wii dañ leen a doxadil fi mu ne nii. Xibaar yi ne fii ci suuf beesuñu.',
'wrong_wfQuery_params' => 'Njuumte ci xibaar yi ci wfQuery()<br />
Solo : $1<br />
Laaj : $2',
'viewsource'           => 'Xool gongikuwaayam',
'viewsourcefor'        => 'ngir $1',
'actionthrottled'      => 'Jëf ju ñu digal',
'actionthrottledtext'  => 'Ngir xeex spam yi, jëf ji nga namm a def dañ kaa digal ci yoon yoo ko man ci benn diir bu gatt. Te mel na ne romb nga boobu dig. Jéemaatal fii aki simili.',
'protectedpagetext'    => 'Wii xêt dañ kaa aar ngir bañ ag coppiteem.',
'viewsourcetext'       => 'Man ngaa xool te duppi li nekk ci bii jukki ngir man cee liggéey :',
'sqlhidden'            => '(Laaju SQL nëbbu na)',
'cascadeprotected'     => 'Xët wii dañ kaa aar ndaxte daa ëmbu ci {{PLURAL:$1|xët wi toftal|xët yi toftal}}, di yu ñu aar :
$2',
'namespaceprotected'   => "Amoo sañ-sañu soppi xët yi ne ci bii barabu tur « '''$1''' ».",
'customcssjsprotected' => 'Amoo sañ-sañu soppi wii xët ndaxte daa ëmb ay tànneefi yeneeni jëfandikukat.',
'ns-specialprotected'  => 'Xët yi ne ci bii barabu tur « {{ns:special}} » kenn maneesu leen a soppi.',
'titleprotected'       => "Koj bii [[User:$1|$1]] moo ko aar ngir bañ sosteefam.
Ngirte li mu joxe mooy ne « ''$2'' ».",

# Login and logout pages
'logouttitle'                => 'Génnu',
'logouttext'                 => "'''Fi mu nekk nii génn nga.'''<br />
Man ngaa wéy di jëfandikoo {{SITENAME}} ci anam buñ la dul xamme walla duggeewaat ak wenn tur wi walla ak weneen.",
'welcomecreation'            => '== Dalal-jamm, $1 ! ==

Sag mbindu sotti na. Bul fatte soppi say tànneef ni nga ko bëggee ci {{SITENAME}}.',
'loginpagetitle'             => 'Duggu',
'yourname'                   => 'Sa turu jëfandikukat',
'yourpassword'               => 'Sa baatujàll',
'yourpasswordagain'          => 'Bindaatal sa baatujàll',
'remembermypassword'         => 'Fattaliku sama baatujàll ci bii nosukaay',
'yourdomainname'             => 'Sa barab',
'externaldberror'            => 'Njuumte juddu na ci dattub njoxe bi, walla day ni rekk amuloo sañ-sañu yeesal sa sàqum biti.',
'loginproblem'               => '<b>Jafe-jafey xammeeku.</b><br />Jéemaatal!',
'login'                      => 'xammeeku',
'loginprompt'                => 'Faaw nga doxal cookie yi ngir man a duggu ci {{SITENAME}}.',
'userlogin'                  => 'Bindu/Duggu',
'logout'                     => 'Génnu',
'userlogout'                 => 'Génnu',
'notloggedin'                => 'Duggoo de',
'nologin'                    => 'Binduwoo ? $1.',
'nologinlink'                => 'Sos am sàq',
'createaccount'              => 'Bindu',
'gotaccount'                 => 'Bindu nga ba noppi? $1.',
'gotaccountlink'             => 'Xammeeku',
'createaccountmail'          => 'Jaare ko ci m-bataaxal',
'badretype'                  => 'Baatujàll yi nga bind yemuñu.',
'userexists'                 => 'Turu jëfandikukat bi nga bind am na boroom ba noppi. Tànnal beneen.',
'youremail'                  => 'Sa màkkaanub m-bataaxal :',
'username'                   => 'Turu jëfandikukat :',
'uid'                        => 'Limu Jëfandikukat :',
'yourrealname'               => 'Sa tur dëgg*',
'yourlanguage'               => 'Làkk :',
'yournick'                   => 'Xaatim ngir say waxtaan :',
'badsig'                     => 'Xaatim gu ñumm gi baaxul; saytul sa yoonub HTML.',
'badsiglength'               => 'Sa xaatim daa gudd lool: guddaay bi ëpp mooy $1 araf.',
'email'                      => 'Màkkaanub m-bataaxal',
'prefs-help-realname'        => 'Sa tur dëgg day lu jaasadi: soo ko ci bëgge duggal it dañ koy jëfandikoo rek ngir moomalela say cërute (li ngay indi).',
'loginerror'                 => 'Njuumte ci xammeeku gi',
'prefs-help-email'           => 'Sa màkkaanub m-bataaxal day lu jaasadi: day tax rek ñu man laa yónne ab bataaxal jaare ko ci sa xëtu jëfandikukat walla yónne la baatujàll bu bees soo ko fattee, te du tax sa màkkaan gisuwu.',
'prefs-help-email-required'  => 'Laaj na ab màkkaanub m-bataaxal',
'nocookiesnew'               => 'Sàqum jëfandikukat mi sosu na, waaye duggu gi antuwul. {{SITENAME}} day jëfandikoo ay cookie ngir duggu gi, waaye da nga leen doxadil. Doxal leen ci sa joowukaay te duggaat ak sa tur ak sa baatujàll bi nga sos.',
'nocookieslogin'             => '{{SITENAME}} day jëfandikoo ay cookie ngir dugg gi, te yaw say cookies dañoo doxadi. Doxal leen ci joowukaay te jéem a duggaat.',
'noname'                     => 'Bindoo turu jëfandikukat bi baax.',
'loginsuccesstitle'          => 'Sag xammeeku jàll na',
'loginsuccess'               => 'Léegi nag duggu nga ci {{SITENAME}} yaay « $1 ».',
'nosuchuser'                 => 'Amul benn jëfandikukat bu tuddu « $1 ». Xoolaatal bu baax mbindiin mi walla nga sos meneen sàqum jëfandikukat.',
'nosuchusershort'            => 'Amul benn jëfandikukat bu tuddu « <nowiki>$1</nowiki> ». Xoolaatal mbidiin mi.',
'nouserspecified'            => 'Laaj na nga tànn ab turu jëfandikukat',
'wrongpassword'              => 'Bii baatujàll baaxul. Jéemaatal.',
'wrongpasswordempty'         => 'Duggaloo ab baatujàll, jéemaatal.',
'passwordtooshort'           => 'Sa baatujàll dafa gatt. War naa am $1 araf lumu néew néew te itam wute ag sa turu jëfandikukat.',
'mailmypassword'             => 'Yónnee ma ab baatujàll bu bees',
'passwordremindertitle'      => 'Sa baatujàll bu bees ci {{SITENAME}}',
'passwordremindertext'       => 'Kenn(xayna yaw la) ku am bii màkkaanu IP $1 moo laaj ngir ñu yónne ko ab baatujàll bu bees ngir duggam ci {{SITENAME}} ($4).
Baatujàll bu jëfandikukat bii di « $2 » léegi mooy « $3 ».
Di la digal rekk nga duggu te soppi baatujàll bi ci ni mu gën a gaawee.

Soo doonul ki biral bii laaj, walla fattaliku nga sa baatujàll bu njëkk ba, te nammatoo kaa soppi, man ngaa tankamlu bii bataaxal te wéy di jëfandikoo baatujàll bu yàgg ba.',
'noemail'                    => 'Bii jëfandikukat « $1 » amufi benn màkkaanub m-bataaxal.',
'passwordsent'               => 'Ab baatujàll bu bees yónnee nañ ko ci màkkaanub m-bataaxal bu jëfandikukat bii di « $1 ». Jéemal a duggaat soo ko jotee.',
'blocked-mailpassword'       => 'Ngir faggandiku ci yaq gi, ku ñu téye sa màkkaanu IP ba doo man a soppi dara, doo man a yónneelu baatujàll bu bees.',
'eauthentsent'               => 'Yónnee nañ la ab m-bataaxalub dëggal ci màkkaanub m-bataaxal bi nga joxe. Balaa ñuy yónnee beneen m-bataaxal ci bii màkkaan, fawwu nga topp tektal yiñ la jox ngir dëggal ni yaa moom bii màkkaan.',
'throttled-mailpassword'     => 'Ab m-bataaxal bu lay fattali sa baatujàll yónnee nañ la ko am na $1 waxtu. Ngir moytu ay say-sayee, benn m-bataaxalu fattali rek lañ lay yónnee ci diiru $1 waxtu.',
'mailerror'                  => 'Njuumte ci yónneeb m-bataaxal bi : $1',
'acct_creation_throttle_hit' => 'Jéggalu, bindu nga $1 yoon. manoo binduwaat',
'emailauthenticated'         => 'Ci $1 nga dëggal sa màkkaanu m-bataaxal.',
'emailnotauthenticated'      => 'Dëggalagoo sa m-bataaxal. Duñ la man a yónne benn m-bataaxal bu aju ci yii ci suuf.',
'noemailprefs'               => 'Joxeel ab m-bataaxal ngir doxal yii solo',
'emailconfirmlink'           => 'Dëggalal sa m-bataaxal',
'invalidemailaddress'        => 'Daytalub m-bataaxal bi baaxul. Duggalal beneen walla nga bàyyi tool bi ne këmm',
'accountcreated'             => 'léegi bindu nga.',
'accountcreatedtext'         => 'Mbindug jëfandikukat bii di $1 jàll na',
'createaccount-title'        => 'Sosum sàq ci {{SITENAME}}',
'createaccount-text'         => 'Kenn ($1) ku sos am sàq ci {{SITENAME}} te tuddu $2 ($4).
Baatujàll bu « $2 » mooy « $3 ». Li gën mooy nga duggu ci teel te soppi baatujàll bi.

Jéelaleel bataaxal bii su fekkee ci njuumte nga sosee mii sàq.',
'loginlanguagelabel'         => 'Làkk : $1',

# Password reset dialog
'resetpass'               => 'Neenal baatujàll bi',
'resetpass_announce'      => 'Da nga duggu ak ab baatujàll bu saxul-dakk, buñ la yónne cib bataaxal. Ngir jeexal mbindu mi, faaw nga roof ab baatujàll bu bees fii:',
'resetpass_header'        => 'Neenalug baatujàll',
'resetpass_submit'        => 'Soppil baatujàll bi te duggu',
'resetpass_success'       => 'Coppiteeg baatujàll bi antu na : Yaa ngi duggu...',
'resetpass_bad_temporary' => 'Baatujàll bu diiru bi baaxul. Xéj-na ni nga soppee sa baatujàll bi moo baax, walla nga laaj baatujàll bu bees.',
'resetpass_forbidden'     => 'Baatujàll bi manoo kaa soppi ci {{SITENAME}}',

# Edit page toolbar
'bold_sample'     => 'Duufal mbind mi',
'bold_tip'        => 'Duufal mbind mi',
'italic_sample'   => 'Wengal mbind mi',
'italic_tip'      => 'Wengal mbind mi',
'link_sample'     => 'Koju lëkkalekaay bi',
'link_tip'        => 'Lëkkalekaay yu biir',
'extlink_sample'  => 'http://www.example.com koju lëkkalekaay bi',
'extlink_tip'     => 'Lëkkalekaay yu biti (bul fattee jiital http://)',
'headline_sample' => 'Ron-koj',
'headline_tip'    => 'Ron-koj 2° dayoo',
'nowiki_sample'   => 'Duggalal fii mbind mi ñu daytalul',
'nowiki_tip'      => 'Jéelaleel mbindinu wiki',
'image_tip'       => 'Roof ab nataal',
'media_tip'       => 'Lëkkalekaay buy jëme cib dencukaay',
'sig_tip'         => 'Xaatimee waxtu wi',
'hr_tip'          => 'Rëbb wu tëdd (bul ci ëppal)',

# Edit pages
'summary'                   => 'Koj&nbsp;',
'subject'                   => 'Tëriit/koj',
'minoredit'                 => 'Coppite yu néewal',
'watchthis'                 => 'Topp xët wii',
'savearticle'               => 'Duggal coppite yi',
'preview'                   => 'Wonendi',
'showpreview'               => 'Wonendi',
'showlivepreview'           => 'Xool bu gaaw',
'showdiff'                  => 'Gis li ma soppi',
'anoneditwarning'           => "'''Moytul :''' Duggoo. Sa màkkaanub IP di nañ ko duggal ci jaar-jaaru xët wii.",
'missingsummary'            => "'''Moytul :''' Defoo ab tënk ci coppite yi nga amal. Soo cuqaate ci «Duggal coppite yi», say coppite di nañ duggu te duñ am koj, maanaam duñ xam loo soppi.",
'missingcommenttext'        => 'Di la sakku nga duggal ab tënk ci-suuf, jërëjëf.',
'missingcommentheader'      => "'''Fattali :''' Joxoo ab koj say coppite. Soo cuqaate ci «duggal coppite yi», di nañ leen duggal te duñ am koj.",
'summary-preview'           => 'Wonendi koj gi',
'subject-preview'           => 'Wonendi gu tëriit/koj',
'blockedtitle'              => 'Bii jëfandikukat dañ kaa téye',
'blockedtext'               => '<big>\'\'\'Sa sàqum jëfandikukat walla sa màkkaanu IP dañ leen téye .\'\'\'</big>

Ki def téye gi mooy $ te lii mooy ngirte li : \'\'$2\'\'.

Man ngaa jokkoo ak $1 walla kenn ci [[{{MediaWiki:Grouppage-sysop}}|yorekat yi]] ngir ngeen ma cee waxtaan.

Te nga jàpp ne jumtukaay bii di "yónne bataaxal bii jëfandikukat" du dox su fekke duggaluloo ab m-bataaxal ci say [[Special:Preferences|tànneef]].
Sa màkkaanu IP mooy $3, xammeekaayu téye gi moy #$5.
Di la sakku nga duggal leen fépp fuñ la leen laajee
* Ndorteelu déye gi : $8
* Jeexantalu téye gi : $6
* Sàq mi ñu téye : $7.',
'autoblockedtext'           => 'Bii màkkaanu IP dañ kaa téye ndaxte da nga koo bokk ak beneen jëfandikukat, te moom it $1 moo ko téye $1.
Te lii mooy ngirte yi mu joxe :

:\'\'$2\'\'

* Ndoorteelu téye gi: $8
* Njeexintalu téye gi : $6

Man ngaa jookkook $1 walla ak kenn ci [[{{MediaWiki:Grouppage-sysop}}|yorekat yi]] ngir waxtaan ci téye gi.

Su fekkee joxe nga ab màkkaanu m-bataaxal ci say [[Special:Preferences|tànneef]] te terewuñula nga jëfandikoo ko, man ngaa jëfandikoo jumtukaay bii di "yónne ab m-bataaxal bii jëfandikukat" ngir jookkook ab yorekat.

Sa màkkaanu IP mooy $3 xammeekaayu téye gi mooy #$5. Di la sakku nga joxe leen fuñ la leen laajee.',
'blockednoreason'           => 'Joxewul benn ngirte',
'blockedoriginalsource'     => "Yoonu gongikuwaay wu wii xët '''$1''' moo ne nii ci suuf:",
'blockededitsource'         => "Ëmbitu '''say coppite''' yi nga def fii '''$1''' mooy lii ci suuf:",
'whitelistedittitle'        => 'Laaj na nga bindu ngir man a soppi ëmbit li',
'whitelistedittext'         => 'Faaw nga doon $1 ngir am sañ-sañu soppi ëmbit li.',
'whitelistreadtitle'        => 'Laaj na nga bindu ngir man a jàng ëmbit li',
'whitelistreadtext'         => 'Ngir man a jàng ëmbit li, laaj na nga [[Special:Userlogin|duggu]].',
'whitelistacctitle'         => 'Amoo sañ-sañu bindu.',
'whitelistacctext'          => 'Ngir nga man a sos yeneeni sàq ci bii wiki, laaj na nga [[Special:Userlogin|duggu]] te it xaar ba am sañ-sañ yi mu laaj.',
'confirmedittitle'          => 'Laaj na nga dëggal sa m-bataaxal ngir man a soppi xët yi',
'confirmedittext'           => 'Ngir man a soppi dara faaw nga dëggal sa m-bataaxal. Ngir kocc-koccal walla dëggal sa màkkaan demal ci say [[Special:Preferences|tànneef]].',
'nosuchsectiontitle'        => 'Xaaj bi amul',
'nosuchsectiontext'         => 'Da nga doon jéema soppi ab xaaj bu amul. Segam bii xaaj $1 amul, say coppite duñ leen denc.',
'loginreqtitle'             => 'Laaj na nga bindu',
'loginreqlink'              => 'Duggu',
'loginreqpagetext'          => 'Faaw nga $1 ngir gis yeneen xët yi.',
'accmailtitle'              => 'Baatujàll bi yónne nañ ko.',
'accmailtext'               => 'Baatujàll bu « $1 » yónne nañ ko ci bii màkkaan $2.',
'newarticle'                => '(Bees)',
'newarticletext'            => "Da ngaa topp ab lëkkalekaay buy jëme ci aw xët wu amagul. ngir sos xët wi léegi, duggalal sa mbind ci boyot bii ci suuf (man ngaa yër [[{{MediaWiki:Helppage}}|xëtu ndimbal wi]] ngir yeneeni xamle). Su fekkee njuumtee la fi indi cuqal ci '''dellu''' bu sa joowukaay.",
'anontalkpagetext'          => "---- ''Yaa ngi ci xëtu waxtaanuwaayu ab jëfandikukat bu kenn-xamul, bu bindoogul walla du jëfandikoo sàqam. Kon ngir xamme ko faaw nga jëfandikoo màkkaanub IP wan. Te màkkaanub IP jëfandikukat yu bari man nañ ka bokk. Su fekkee jëfandikukat bu kenn-xamul nga, te nga gis ne dañ laa féetale ay sànni-kaddu yoo moomul, man ngaa [[Special:Userlogin|bindu walla duggu]] ngi benn jaxase bañatee am ëllëg .''",
'noarticletext'             => 'Fi mu ne ni amul benn mbind ci xët wii; man ngaa [[Special:Search/{{PAGENAME}}|tambli ab seet ci koju xët wii]] walla [{{fullurl:{{FULLPAGENAME}}|action=edit}} soppi xët wii].',
'userpage-userdoesnotexist' => 'Bii sàqum jëfandikukat « $1 » du bu ku bindu. Seetal bu baax ndax da ngaa namma sos walla soppi wii xët.',
'clearyourcache'            => "'''Karmat :''' Soo dence xët wi ba noppi, faaw nga bës ci si sa arafukaay yii di toftal, te nga bàyyi xel ci joowukaay bi ngay jëfandikoo : '''Mozilla / Konqueror / Firefox :''' ''Shift-Ctrl-R'', '''Internet Explorer / Opera :''' ''Ctrl-F5'', '''Safari :''' ''Cmd-R''.",
'usercssjsyoucanpreview'    => "'''Xelal :''' di la digël nga cuq ci «Wonendi» ngir gis say xobi CSS walla JavaScript yu bees laata nga leen di denc.",
'usercsspreview'            => "'''Bul fatte ne lii wonendib sa CSS rekk la; dencagoo say coppite!'''",
'userjspreview'             => "'''Bul fatte ne lii ab wonendib sa yoonu javaScript rekk la; dencagoo say coppite!'''",
'userinvalidcssjstitle'     => "'''Moytul :''' amul wenn melin wu tuddu « $1 ». Bul fatte ne xët yiy jeexee .css ak .js seeni koj ay araf yu tuut ñoo ciy tegu/.<br />ci misaal, {{ns:user}}:Foo/'''m'''onobook.css moo baax, waaye bii du baax {{ns:user}}:Foo/'''M'''onobook.css .",
'updated'                   => '(bees na)',
'note'                      => '<strong>Karmat :</strong>',
'previewnote'               => 'Lii ab wonendi rekk la; coppite yi ci xët wi dencagoo leen!',
'previewconflict'           => "Wonendi bi mengóo na ak mbind yi ne ci boyotu coppite bi te nii lay mel soo cuqe ci 'Denc xët wi'.",
'session_fail_preview'      => '<strong>Jéegalu! manu noo denc say coppite ngir ñakkub ay njoxe ñeel sag duggu. Di la ñaan nga jéemaat. Su tolof-tolof bi wéyee, Jéemal a génn te duggaat. </strong>',
'session_fail_preview_html' => "<strong>Jéegalu ! manu noo denc say coppite ngir ab ñakkub ay njoxe ñeel sag duggu.</strong>

''Segam ci bii dal dañ fee doxal HTML bu ñumm, ngir ay ngirtey kaaraange, wonendi gi du gisuwu.''

<strong>Su tolof-tolof bi wéyee, man nga jéem a génn te duggaat .</strong>",
'token_suffix_mismatch'     => '<strong>Votre édition n’a été acceptée car votre navigateur a mélangé les caractères de ponctuation dans l’identifiant d’édition. L’édition a été rejetée afin d’empêcher la corruption du texte de l’article. Ce problème se produit lorsque vous utilisez un proxy anonyme à problème.</strong>',
'editing'                   => 'Coppiteg $1',
'editingsection'            => 'Coppiteg $1 (xaaj)',
'editingcomment'            => 'Coppiteg $1 (sanni-kàddu)',
'editconflict'              => 'jàppanteb coppite ci: $1',
'explainconflict'           => "Am na beneen jëfandikukat bu soppi xët wi, mu gën a bees, ci bi ngay soog a door say coppite.
Mbind yi ne ci boyotu coppite bi ci kaw, ñooy yi teew nii ci dattub njoxe bi, ni ko beneen jëfandikukat bi soppee.
Yaw nag say coppite ñoo nekk ci boyotu coppite bi ci suuf.
Soo nammee denc say coppite, faaw nga duggal leen ci boyot bi ci kaw.
Soo cuqe ci 'Denc xët wi', mbind yi ne ci boyot bi ci kaw rekk ñooy dencu .",
'yourtext'                  => 'Sa mbind',
'storedversion'             => 'Sumb bi dencu',
'nonunicodebrowser'         => '<strong>Attention : Votre navigateur ne supporte pas l’unicode. Une solution temporaire a été trouvée pour vous permettre de modifier en tout sûreté un article : les caractères non-ASCII apparaîtront dans votre boîte de modification en tant que codes hexadécimaux. Vous devriez utiliser un navigateur plus récent.</strong>',
'editingold'                => '<strong>Moytul: yaa ngi soppi am sumb mu yàgg mu xët wii. Soo leen dence, bépp coppite buñ defoon laataa mii sumb, di nañ leen ñakk.</strong>',
'yourdiff'                  => 'Wuute',
'copyrightwarning'          => 'Bépp cëru ci {{SITENAME}} dañ leen di jàppe niki ay siiwal yoo def te teg leen ci $2 (xoolal $1 ngir yeneeni xamle).
Soo bëggul keneen jël say mbind soppi leen, tas leen teg ci, bu leen fi duggal.<br />
Te it na wóor ne li nga fiy duggal yaa leen moom, yaa leen bind, walla fa nga leen jële gongikuwaay bu ubbeeku la, lu kenn moomul.
<strong>BUL FI DUGGAL LIGGÉEYI KENEEN YU AQI KI-SOS AAR TE AMOO CI BENN NDIGËL!</strong>',
'copyrightwarning2'         => 'Karmat: Bépp cëru ci {{SITENAME}} yeneen jëfandikukat yi man nañ leen a soppi walla far leen.
Soo bëggul keneen jël say mbind soppi leen, tas leen teg ci, bu leen fi duggal.<br />
Te it na wóor ne li nga fiy duggal yaa leen moom, yaa leen bind, walla fa nga leen jële gongikuwaay bu ubbeeku la, lu kenn moomul (xoolal $1 ngir yeneeni xamle).
<strong>BUL FI DUGGAL LIGGÉEYI KENEEN YU AQI KI-SOS AAR TE AMOO CI BENN NDIGËL!</strong>',
'longpagewarning'           => "'''Muytul: guddaayu xët wi da fa romb $1 kio ;
yenn joowukaay yi, man nañoo wone ay tolof-tolof ci bu ñuy soppi xët yi romb dayoob 32 kio. Li doon gën mooy nga séddatle ko ci ay xaaj yu bari.'''",
'longpageerror'             => '<strong>NJUUMTE : mbind mi nga yónne guddee na $1 kio, kon romb na dig bi di $2 kio. Mbind mi maneesu kaa denc.</strong>',
'readonlywarning'           => "'''Moytul: dattub njoxe bi dañ kaa caabi ngir ay liggéey,
kon doo man a denc say coppite fi mu nekk nii. Man ngaa duppi mbind mi taf ko cib tëriin bu ñuy binde te taaxirlu ñu ubbi dattub njoxe bi.'''",
'protectedpagewarning'      => "'''Moytul : wii xët dañ kaa aar.
Jëfandikukat yi nekk yorkat rekk a ko man a soppi.'''",
'semiprotectedpagewarning'  => "'''Karmat :''' wii xët dañ kaa aar ba nga xam ne ñi bindu rekk a ko man a soppi.",
'cascadeprotectedwarning'   => '<strong>MOYTUL : Xët wii dañ kaa aar ba nga xam ne [[{{MediaWiki:Grouppage-sysop}}|yorkat yi]] rek ñoo koy man a soppi. Kaaraange googu dañ kaa def ndaxte xët wii dañ kaa dugal ci biir {{PLURAL:$1|aw xët wu ñu aar|ay xët yu ñu aar}}.</strong>',
'titleprotectedwarning'     => '<strong>MOYTUL: wii xët dañ kaa aar ci anam boo xam ne yenn jëfandikukat yi rekk a ko man a sos.</strong>',
'templatesused'             => 'Royuwaay yi nekk ci wii xët :',
'templatesusedpreview'      => 'Royuwaay yi nekk ci gii wonendi :',
'templatesusedsection'      => 'Royuwaay yi ne ci bii xaaj:',
'template-protected'        => '(aar)',
'template-semiprotected'    => '(aaru-diggu)',
'hiddencategories'          => '{{PLURAL:$1|wàll bu nëbbu bu|wàll yu nëbbu yu }} xët wii bokk :',
'nocreatetitle'             => 'Digalu sosteefu xët',
'nocreatetext'              => 'Jëfandikukat yi bindu rekk a man a sosi xët ci {{SITENAME}}. Man nga dellu ginnaaw walla soppi aw xët wu am ba noppi, [[Special:Userlogin|duggu walla sos am sàq]].',
'nocreate-loggedin'         => 'Amuloo sañ-sañ yu doy ngir man a sosi xët yu bees ci {{SITENAME}}.',
'permissionserrors'         => 'Njuumte ci sañ-sañ yi',
'permissionserrorstext'     => 'Amuloo sañ-sañu àggali jëf ji nga tambali, ngax {{PLURAL:$1|lii toftal|yii toftal}} :',
'recreate-deleted-warn'     => "'''Moytul: yaa ngi nekk di sosaat aw xët wu ñu faroon.'''

Wóorluwul bu baax ndax sosaat xët wi di na doon li gën. Xoolal yéenekaayu far gi ci suuf.",

# "Undo" feature
'undo-success' => 'Gii coppite man nga kaa neenal. Xoolal méngale gi ne ci suuf ngir wóorlu ne ni ëmbit li mel na ni nga ko bëgge, te nga denc xët wi ngir jeexal.',
'undo-failure' => 'Neenalug coppite gi defuwul: man naa jur ab jàppante ci coppite yi ci diggante bi',
'undo-summary' => 'Neenalug coppite $1 yu [[Special:Contributions/$2|$2]]',

# Account creation failure
'cantcreateaccounttitle' => 'sag mbindu Manu la nekk .',
'cantcreateaccount-text' => "Sosum sàq mu bàyyikoo ci bii màkkaanu IP ('''$1''') dañ kaa téye [[User:$3|$3]].

Ngirtey téye gi $3 joxe, mooy ne: ''$2''.",

# History pages
'viewpagelogs'        => 'Xool yéenekaayu xët wii',
'nohistory'           => 'Xët wii amulub jaar-jaar.',
'revnotfound'         => 'Sumb mi gisuñ ko',
'revnotfoundtext'     => 'Sumbum xët wi ngay laaj gisuñ ko. Saytul URL bi nga jëfandikoo ngir jot xët wii.',
'currentrev'          => 'Sumb mi teew',
'revisionasof'        => 'Sumb mu $1',
'revision-info'       => 'Sumb mu $1, jëfandikukat: $2',
'previousrevision'    => '← Sumb mi jiitu',
'nextrevision'        => 'Sumb mi toftal →',
'currentrevisionlink' => 'Sumb mi teew',
'cur'                 => 'xamle',
'next'                => 'tegu',
'last'                => 'mujj',
'page_first'          => 'jiitu',
'page_last'           => 'mujj',
'histlegend'          => 'Méngaley sumb: falal sumb yi nga bëgg a méngale te bës ci Ayca walla ci cuquwaay bi ci suuf.

(teew) = li mu wuuteek sumb mi teew, (jii) = li mu wuuteek sumb mi jiitu, <b>c</b> = coppite yu néewal.',
'deletedrev'          => '[far nañ ko]',
'histfirst'           => 'Cëru yi njëkk',
'histlast'            => 'Cëru yi mujj',
'historyempty'        => '(këmm)',

# Revision feed
'history-feed-title'          => 'Jaar-jaaru sumb yi',
'history-feed-description'    => 'Jaar-jaaru xët wi ci bii wiki',
'history-feed-item-nocomment' => '$1 ci $2', # user at time
'history-feed-empty'          => 'Xët wi nga laaj amul. Xej-na dañ koo dindi ci dal bi walla ñu tuddewaat ko. Man nga jéem a [[Special:Search|seet ci wiki bi]] ndax ay xët yu bees am nañ fi.',

# Revision deletion
'rev-deleted-comment'         => '(sanni-kàddu bi far nañ ko)',
'rev-deleted-user'            => '(turu jëfandikukat bi far nañ ko)',
'rev-deleted-event'           => '(duggit li far nañ ko)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks"> Sumb mu xët wii dañ kaa fare ci rënkuwaay yi ñépp man a gis. man nga saytu [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} jaar-jaaru farte yi] ngir yeneeni xibaar.</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks"> mii sumb mu xët wi dañ kaa fare ci rënkuwaay bi ñépp man a gis. Li nga doon yorkat moo tax nga man gis mbind mi. Saytul [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} jaar-jaaru farte yi] ngir yeneeni xibaar.</div>',
'rev-delundel'                => 'wone/nëbb',
'revisiondelete'              => 'Far/Lopppanti ay sumb',
'revdelete-nooldid-title'     => 'Waxoo ban sumb',
'revdelete-nooldid-text'      => 'Waxoo ci ban sumb ngay amal solo sii.',
'revdelete-selected'          => "{{PLURAL:$2|Sumb mi falu mu|Sumb yi falu yu}} '''$1''' :",
'logdelete-selected'          => "{{PLURAL:$2|Xew-xewu yéenekaay bi falu|xew-xewi yéenekaay yi falu}} ngir '''$1''' :",
'revdelete-text'              => 'Sumb yi nga far dañuy wéy di feeñ ci jaar-jaaru xët wi, waaye mbind yi ñu ëmb ñépp duñ leen man a gis .

Yeneen yorkati {{SITENAME}} itam di nañ man a gis ëmbit yu laqu yi te loppanti leen, walla xanaa rek kay dañ fee def ay digal yu leen koy tere man a def.',
'revdelete-legend'            => 'Taxawal ay digal ci sumb yi ñu far',
'revdelete-hide-text'         => 'Nëbb mbindum sumb mi',
'revdelete-hide-name'         => 'Nëbb jëf ji ak njeexitam',
'revdelete-hide-comment'      => 'Nëbb sanni-kàddub coppite gi',
'revdelete-hide-user'         => 'Nëbb tur walla màkkaanu IP bu soppikat bi',
'revdelete-hide-restricted'   => 'jëfel digal yu yorkat yi ak yeneen jëfandikukat yi.',
'revdelete-suppress'          => 'Nëbb xibaar yi yorkat yi tamit.',
'revdelete-hide-image'        => 'Nëbb ëmbitu dencukaay bi',
'revdelete-unsuppress'        => 'Far digal yi ci sumb yi ñu loppanti',
'revdelete-log'               => 'Sanni-kàddu ngir yéenekaay bi :',
'revdelete-submit'            => 'Def ko ci sumb mi falu',
'revdelete-logentry'          => 'Gisinu sumb mi soppiku na ngir [[$1]]',
'logdelete-logentry'          => 'Gisub xew-xew bii [[$1]] dañ kaa soppi',
'logdelete-logaction'         => 'Soppi nga doxaliin bi di $2 ngir {{plural:$1|ab xew-xew bu|ay xew-xew yu}} ñeel [[$3]]',
'revdelete-success'           => "'''Coppiteg gisinug sumb mi, baax na.'''",
'logdelete-success'           => "'''Gisub xew-xew bi soppiku na bu baax.'''",

# History merging
'mergehistory'                     => 'Booleb jaar-jaar yu aw xët',
'mergehistory-header'              => 'Wii xët day tax nga man a boole sumb yépp yi ne ci jaar-jaaruw xët (di ko wax it xëtu gongikuwaay) ak jaar-jaaru weneen xët wu mujj.
wóorluwul ne coppite gi du yaq jaar-jaaru xët wi.',
'mergehistory-from'                => 'Xëtu gongikuwaay :',
'mergehistory-into'                => 'Xëtu jëmuwaay :',
'mergehistory-list'                => 'Jaar-jaar yi boolewu',
'mergehistory-go'                  => 'Wone coppite yi boolewu',
'mergehistory-submit'              => 'Boole jagal yi',
'mergehistory-fail'                => 'Booleb jaar-jaar yi antuwul. Falaatal xët wi ak taariix yi',
'mergehistory-no-source'           => 'Xëtu gongikuwaay bii $1 amul.',
'mergehistory-no-destination'      => 'Xëtu jëmuwaay bii $1 amul.',
'mergehistory-invalid-source'      => 'Xëtu gongikuwaay bi daa war a am koj bu baax.',
'mergehistory-invalid-destination' => 'Xëtu jëmuwaay bi daa war a am koj bu baax.',

# Merge log
'mergelog'         => 'Yéenekaayu boole yi',
'revertmerge'      => 'Neenal boole yi',
'mergelogpagetext' => 'Lii ci suuf ab lim la ci boole yu mujj yu jaar-jaaru aw xët ak weneen .',

# Diffs
'history-title' => 'Jaar-jaaru sumbi « $1 »',
'lineno'        => 'Rëdd $1 :',
'editundo'      => 'neenal',

# Search results
'searchresults'         => 'Ngértey ceet gi',
'searchresulttext'      => 'Ngir yeneeni xibaar ci ceet gi ci {{SITENAME}}, xoolal [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'Yaa ngi seet « [[:$1]] »',
'searchsubtitleinvalid' => 'Yaa ngi seet « $1 »',
'noexactmatch'          => "'''Amul wenn xët wu tudd « $1 » wu am.''' man ngaa [[:$1|sakk xët wi]].",
'noexactmatch-nocreate' => "'''Amul wenn xët wu tuddu« $1 ».'''",
'titlematches'          => 'Koju xët yi ñoo yam',
'notitlematches'        => 'Amul benn koju xët wu yam ak ceet gi',
'textmatches'           => 'Mbindu jukki yi ñoo yam.',
'notextmatches'         => 'Amul benn mbindu jukki bu yam ak ceet gi.',
'prevn'                 => '$1 yi jiitu',
'nextn'                 => '$1 yi toftal',
'viewprevnext'          => 'Xool ($1) ($2) ($3).',
'showingresults'        => 'Woneg <b>$1</b> {{plural:$1|ngérte|ciy ngérte}} doore ko ci #<b>$2</b>.',
'showingresultsnum'     => 'Woneg <b>$3</b> {{plural:$3|ngérte|ciy ngérte}} doore ko ci #<b>$2</b>.',
'nonefound'             => '<strong>Karmat</strong> : liy waral ñakkug ay ngérte yenn saa yi, mooy jëfandikoo ab baatu ceet bu gàtt, niki « am » walla « jëm », walla jëfandikoo baati ceet yu bari (xët yi ëmb baat yi nga bind yépp, rekk ñooy feeñ).',
'powersearch'           => 'Seet',
'powersearch-legend'    => 'Seet gu xóot',
'powersearchtext'       => 'Seet ci barabi tur yi :<br />
$1<br />
$2 boole ci xëti yoonalaat yi<br /> Seet $3 $9',
'searchdisabled'        => 'Ceet gi ci {{SITENAME}} doxul. Ci négandiku doxal gi, man nga seet ci Google. Jàppal ne, xéj-na ëmbiti {{SITENAME}} gi ci bii seetukaay yeesaluñ leen.',

# Preferences page
'preferences'              => 'Tànneef',
'mypreferences'            => 'Samay tànneef',
'prefs-edits'              => 'Limu coppite yi:',
'prefsnologin'             => 'Duggoo',
'prefsnologintext'         => 'Laaj na nga [[Special:Userlogin|duggu]] ngir soppi say tànneef.',
'prefsreset'               => 'Tànneef yi loppanti nañ leen.',
'qbsettings'               => 'Banqaasu jumtukaay',
'qbsettings-none'          => 'Kenn',
'qbsettings-fixedleft'     => 'Cammooñ',
'qbsettings-fixedright'    => 'Ndayjoor',
'qbsettings-floatingleft'  => 'ci cammooñ',
'qbsettings-floatingright' => 'Ci ndayjoor',
'changepassword'           => 'Coppiteg baatujàll bi',
'skin'                     => 'Melokaan',
'math'                     => 'Xayma',
'dateformat'               => 'Dayoob taariix bi',
'datedefault'              => 'Benn tànneef',
'datetime'                 => 'Taariix ak waxtu',
'math_failure'             => 'Njuumte ci xayma',
'math_unknown_error'       => 'Njuumte li xamuñ ko',
'math_unknown_function'    => 'Solo si xamuñ ko',
'math_lexing_error'        => 'Njuumteg mbindin',
'prefs-watchlist'          => 'Limu toppte',
'prefs-watchlist-days'     => 'Limu bes yi nga koy ba ci sa limu toppte :',
'saveprefs'                => 'Denc tànneef yi',
'resetprefs'               => 'Loppanti tànneef yi',
'oldpassword'              => 'Baatujàll bu yàgg :',
'newpassword'              => 'Baatujàll bu bees :',
'retypenew'                => 'Bindaatal baatujàll bu bees bi :',
'textboxsize'              => 'Boyotu coppite',
'searchresultshead'        => 'Seet',
'recentchangesdays'        => 'Limu bes yi nga koy wone ci coppite yu mujj yi :',
'recentchangescount'       => 'Limu coppite yi ngay wone ci coppite yu mujj yi :',
'savedprefs'               => 'Tànneey yi duggal nañ leen.',
'allowemail'               => 'ndigëlël yeneeni jëfëndikookat mën laa yòonnee bataaxal',

# User rights
'userrights-lookup-user'     => 'Yorinu yelleefu jëfëndikookat',
'userrights-user-editname'   => 'Duggal am turu jëfëndikookat :',
'editusergroup'              => 'Coppiteg mbooloo Jëfëndikookat yi',
'editinguser'                => "Coppiteg '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'   => 'Soppi mbooloo yu jëfëndikookat bi',
'saveusergroups'             => 'Duggal mbooloo jëjëndikookat yi',
'userrights-groupsmember'    => 'Way-bokk gu:',
'userrights-groupsavailable' => 'Mbooloo yi jappandi:',
'userrights-reason'          => 'Ngirtey coppite yi :',

# Groups
'group'       => 'Mbooloo :',
'group-sysop' => 'Yorkat',
'group-all'   => 'Yépp',

'group-sysop-member' => 'Yorkat',

'grouppage-sysop' => '{{ns:project}}:Yorkat',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|coppite|ciy coppite}}',
'recentchanges'                     => 'Coppite yu mujj',
'recentchangestext'                 => 'Toppal ci wii xët coppite yu mujj ci {{SITENAME}}.',
'recentchanges-feed-description'    => 'Toppal coppite yu mujj gu wiki gii.',
'rcshowhideminor'                   => '$1 Coppite yu néew',
'rcshowhideliu'                     => '$1 jëfëndikookat gu bindu',
'rcshowhideanons'                   => '$1 indig IP',
'rcshowhidemine'                    => '$1 Li ma indiwoon',
'hide'                              => 'Nëbb',
'show'                              => 'Wone',
'number_of_watching_users_pageview' => '[$1 jëfëndikookat tegu]',
'rc_categories'                     => 'Yemug wàll yi (xaajale ak « | »)',
'rc_categories_any'                 => 'Yëpp',

# Recent changes linked
'recentchangeslinked'          => 'Toppteg lëkkalekaay',
'recentchangeslinked-noresult' => 'Benn coppite amul ci xët yi mu lëkkalool ci diir bi ñu tann.',
'recentchangeslinked-summary'  => "Mii xët moo lay won coppite yu mujj ci xët yi ñu lëkkale. Say xëtu limu toppte ñoo '''xëm'''.",

# Upload
'upload'          => 'Jëli ab file',
'uploadbtn'       => 'Jëli file bi',
'uploadnologin'   => 'Duggoo',
'uploadlog'       => 'Jaar-jaaru jëli yi',
'filename'        => 'Turu file bi',
'badfilename'     => 'Nataal gi tuddewaat nañ ko « $1 ».',
'uploadwarning'   => 'Moytul !',
'savefile'        => 'Duggal file bi',
'watchthisupload' => 'Topp file bii',

# Special:Imagelist
'imagelist'      => 'Limu nataal yi',
'imagelist_user' => 'Jëfëndikookat',

# Image description page
'imagelinks'   => 'Xët yi am bii nataal',
'linkstoimage' => 'Xët yii ci suuf am nañ ci seen biir bii nataal :',

# List redirects
'listredirects' => 'Limu jubluwaat yi',

# Unused templates
'unusedtemplates'     => 'Royuwaay yi ñu jëfëndikoowul',
'unusedtemplatestext' => 'Mii xët day limal xët yëpp yi tudd « Royuwaay » yu ñu duggalul ci benn xët. Bul fattee seet baxam amul yeneen lëkkalekaay yu lay jëmale ci royuwaay yi balaa nga leen di dindi.',
'unusedtemplateswlh'  => 'yeneeni lëkkalekaay',

# Random page
'randompage' => 'Aw xët ci mbetteel',

# Statistics
'userstats' => 'Limbarem jëfëndikookat',

'brokenredirectstext'    => "Yoonalaat yii dañuy jëmee ci'y xët yu amul :",
'brokenredirects-edit'   => '(Soppi)',
'brokenredirects-delete' => '(dindi)',

'withoutinterwiki'        => 'Xët yi amul lëkkalekaay diggantey-làkk',
'withoutinterwiki-header' => 'Xët yii amu ñu ay lëkkalekaay jëm yeneeni làkk:',

'fewestrevisions' => 'Jukki yi gën a néewi coppite',

# Miscellaneous special pages
'ncategories'             => '$1 {{PLURAL:$1|wàll|ciy wàll}}',
'nlinks'                  => '$1 {{PLURAL:$1|lëkkalekaay|ciy lëkkalekaay}}',
'nmembers'                => '$1 {{PLURAL:$1|xët|ciy xët}} ci biir',
'specialpage-empty'       => 'Xët mii amul dara',
'uncategorizedpages'      => 'Xët yi amul wall',
'uncategorizedcategories' => 'Wàll yi amul wàll',
'uncategorizedimages'     => 'Nataal yu amul wall',
'uncategorizedtemplates'  => 'Royuwaay yi amul Wàll',
'unusedcategories'        => 'Wàll yi ñu jëfëndikoowul',
'wantedcategories'        => 'Wàll yi ñu gën a laaj',
'wantedpages'             => 'Xët yi ñu gën a laaj',
'mostlinked'              => 'Xët yi ñu gën a lëkkale',
'mostlinkedcategories'    => 'Wàll yi ñu gënë jëfëndikoo',
'mostlinkedtemplates'     => 'Royuwaay yi ñu gën a jëfëndikoo',
'mostcategories'          => 'Jukki yi ëpp yiy jëfëndikooy wàll',
'mostimages'              => 'Nataal yi ñu gën a jëfëndikoo',
'mostrevisions'           => 'Jukki yi ñu gën a soppi',
'listusers'               => 'Limu way bokk yi',
'specialpages'            => 'Xët yu solowu',
'newpages'                => 'Xët yu bees',
'newpages-username'       => 'Jëfëndikookat :',
'ancientpages'            => 'Jukki yi gënë néew ay coppite ci lu mujj',
'move'                    => 'Tuddewaat',
'movethispage'            => 'Tuddewaat xët wi',
'unusedcategoriestext'    => 'Wàll yii toftal, nekk nañu wante amuñul benn jukki walla wàll ci seen biir.',

# Book sources
'booksources-go' => 'Ayca',

# Special:Log
'specialloguserlabel'  => 'Jëfëndikookat :',
'speciallogtitlelabel' => 'Koj :',
'log'                  => 'Yéenekaay',
'all-logs-page'        => 'Yéenekaay yëpp',
'log-search-legend'    => 'Seet ci yéenekaay yi',
'log-search-submit'    => 'waaw',
'logempty'             => 'Dara nekkul ci jaar-jaaru xët mii.',

# Special:Allpages
'allpages'         => 'Xët yëpp',
'alphaindexline'   => '$1 ba $2',
'nextpage'         => 'Xët wi tegu ($1)',
'allpagesfrom'     => 'Wonel xët yi tambalee ci :',
'allarticles'      => 'Jukki yëpp',
'allinnamespace'   => 'Xët yëpp(li ñu bàyyil tur $1)',
'allpagesprev'     => 'Jiitu',
'allpagesnext'     => 'Tegu',
'allpagessubmit'   => 'Baaxal',
'allpagesprefix'   => 'Wone xët yi tambalee :',
'allpagesbadtitle' => 'Koj gi nga bindal xët mii jaaduwul. xayna dafa am ay araf yu ñu manula jëfëndikoo ci koj yi.',

# Special:Listusers
'listusers-submit' => 'Wone',

# E-mail user
'emailuser'     => 'Yònnee ab bataaxal jëfëndikookat bii',
'emailpage'     => 'Yònnee ab bataaxal jëfëndikookat bii',
'emailmessage'  => 'Bataaxal&nbsp;',
'emailsend'     => 'Yònnee',
'emailsent'     => 'Bataaxal yi ñu yònnee',
'emailsenttext' => 'Sa bataaxal yònnee nañ ko.',

# Watchlist
'watchlist'            => 'Limu toppte',
'mywatchlist'          => 'Limu toppte',
'watchlistfor'         => "(ngir jëfëndikookat '''$1''')",
'nowatchlist'          => 'Sa limu toppte amul benn jukki.',
'watchlistanontext'    => 'Ngir mana gis walla soppi jëfkayu sa limu toppte, faw nga  $1.',
'watchnologin'         => 'Duggoo de',
'watchnologintext'     => 'Yaa wara nekk [[Special:Userlogin|duggal]] ngir soppi lim gi.',
'addedwatch'           => 'Yokk ci sa limu toppte',
'removedwatch'         => 'Jëlee ci sa limu toppte',
'watch'                => 'Topp',
'watchthispage'        => 'Topp xët wii',
'unwatch'              => 'Bul toppati',
'unwatchthispage'      => 'Bul toppati',
'watchnochange'        => 'Lenn ci xët yi ngay topp soppikuwul ci diir bii',
'watchlist-details'    => 'Topp nga <b>$1</b> {{PLURAL:$1|xët|ciy xët}}, soo waññiwaalewul xëtu waxtaanukaay yi.',
'wlheader-showupdated' => '* Xët yi ñu soppiwoon ca sa duggu bu mujj ñoom la ñu fesal ñu <b>xëm</b>',
'watchmethod-recent'   => 'saytug coppite yu mujj yu xët yi ngay topp',
'watchmethod-list'     => 'saytug xët yi ñuy topp ngir ay coppite yu mujj',
'watchlistcontains'    => "Sa limu toppte am na '''$1''' {{PLURAL:$1|xët|xët}}.",
'wlnote'               => 'Fii ci suuf {{PLURAL:$1| ngay gis coppite yu mujj yi|ngay gis $1 coppite yu mujj}} ci {{PLURAL:$2|waxtu gu mujj gi|<b>$2</b> waxtu yu mujj}}.',
'wlshowlast'           => 'wone $1 waxtu yu mujj, $2 bess yu mujj, walla $3.',
'watchlist-show-own'   => 'Wone samay coppite',
'watchlist-hide-own'   => 'Nëbb samay coppite',
'watchlist-show-minor' => 'Wone coppite yu tuut yi',
'watchlist-hide-minor' => 'Nëbb coppite yu tuut yi',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Topp...',

'changed' => 'soppi',

# Delete/protect/revert
'confirm'     => 'Dëggal',
'deletionlog' => 'Yéenekaay',

# Undelete
'viewdeletedpage' => 'Jaar-jaaru xët wi ñu dindi',

# Namespace form on various pages
'namespace'      => 'Barabu tur :',
'blanknamespace' => '(njëkk)',

# Contributions
'contributions' => 'Li jëfëndikookat bii indi',
'mycontris'     => 'Samay cërute',
'nocontribs'    => 'Amul benn coppite bu melokaanoo nii bu ñu gis.',
'month'         => 'Tambalee ci weeru (ak yi jiitu) :',
'year'          => 'Tambalee ci attum (ak yi jiitu) :',

# What links here
'whatlinkshere'       => 'Xët yi mu lëkkalool',
'linklistsub'         => '(Limuy lëkkalekaay)',
'linkshere'           => 'Xët yii ci suuf am nañ ab lëkkalekaay buy jëm <b>[[:$1]]</b> :',
'whatlinkshere-prev'  => '{{PLURAL:$1|wi jiitu|$1 yi jiitu}}',
'whatlinkshere-next'  => '{{PLURAL:$1|wi tegu|$1 yi tegu}}',
'whatlinkshere-links' => '← lëkkalekaay',

# Block/unblock
'anononlyblock'            => 'Jëfëndikookat yu binduwul rek',
'blocklink'                => 'Teye',
'blocklogpage'             => 'Jaar-jaaru teye yi',
'block-log-flags-anononly' => 'jëfëndikookat gu kenn xamul rek',
'block-log-flags-nocreate' => 'Terenañ sa sakum sàq',
'block-log-flags-noemail'  => 'tere nañ sag yònneeg bataaxal',

# Move page
'move-page-legend' => 'Tuddewaat aw xët',
'movearticle'      => 'Tuddewaatal jukki bi',
'movenologintext'  => 'Ngir man a tuddewaat aw xët, da ngaa war a [[Special:Userlogin|dugg]] ni jëfëndikookat bu bindu te saw sàq war naa am yaggaa bi mu laaj.',
'newtitle'         => 'Koj bu bees',
'move-watch'       => 'Topp xët wii',
'articleexists'    => 'Am na ba noppi ab jukki gu am gii koj, walla koj gi nga tann baaxul. tannal bennen.',
'movedto'          => 'Turam bu bees',
'movetalk'         => 'Tuddewaat tamit xëtu waxtaanukaay wi mu andal',
'1movedto2'        => 'tuddewaat ko [[$1]] en [[$2]]',
'1movedto2_redir'  => 'yòonalaat ko [[$1]] mu jëm [[$2]]',
'movelogpage'      => 'Jaar-jaaru tuddewaat yi',
'movelogpagetext'  => 'Lii mooy limu xët yi ñu mujje tuddewaat.',
'movereason'       => 'Ngirtey tuddewaat bi',
'delete_and_move'  => 'Dindi te tuddewaat',

# Export
'export-addcattext' => 'Yokkal xëti Wàll gi :',
'export-addcat'     => 'Yokk',

# Namespace 8 related
'allmessagesname'     => 'Turu tool bi',
'allmessagescurrent'  => 'Bataaxal bi fi nekk',
'allmessagestext'     => "Lii mo'y limu bataaxal yëpp yi am ci biir MediaWiki",
'allmessagesmodified' => 'Wone coppite yi rek',

# Thumbnails
'thumbnail-more' => 'Ngandal',

# Tooltip help for the actions
'tooltip-pt-userpage'           => 'Xëtu jëfëndikookat',
'tooltip-pt-mytalk'             => 'Sama xëtu waxtaanukaay',
'tooltip-pt-preferences'        => 'Samay tànneef',
'tooltip-pt-watchlist'          => 'Limu xët yi ngay topp',
'tooltip-pt-mycontris'          => 'Limu samay cërute',
'tooltip-pt-login'              => 'Woo nan la ngir nga xammeku, waaye doonul lu manuta ñakk.',
'tooltip-pt-anonlogin'          => 'woo nan la ngir nga xammeku, waaye doonul lu manuta ñakk.',
'tooltip-pt-logout'             => 'Gennu',
'tooltip-ca-talk'               => 'Waxtaan yi ñeel xët wii',
'tooltip-ca-viewsource'         => 'Xët wii dañ kaa aar. Waaye man ngaa xool ndefam.',
'tooltip-ca-protect'            => 'Aar xët wi',
'tooltip-ca-undelete'           => 'Tabaxaat xët wi',
'tooltip-ca-move'               => 'Tuddewaatal xët wii',
'tooltip-ca-watch'              => 'Yokk xët wii ci sa limu toppte',
'tooltip-ca-unwatch'            => 'Jëlee xët wii ci sa limu toppte',
'tooltip-search'                => 'Seet ci gii wiki',
'tooltip-p-logo'                => 'Xët wu njëkk',
'tooltip-n-mainpage'            => 'Nemmeekul xët wu njëkk wi',
'tooltip-n-portal'              => 'Maanaam ci naal bi',
'tooltip-n-currentevents'       => 'Gis ay xibaar ci xew-xew yu teew yi',
'tooltip-n-recentchanges'       => 'Limu coppite yu mujj ci wiki gi',
'tooltip-n-randompage'          => 'Wone aw xët ci mbetteel',
'tooltip-n-help'                => 'Tërug ndimbal gi',
'tooltip-n-sitesupport'         => 'Jappaleel naal bi',
'tooltip-t-whatlinkshere'       => 'limu xët yi ñu lëkkaleg bii',
'tooltip-t-recentchangeslinked' => 'Limu coppite yu mujj yu xët yi ñu lëkkale ak mii',
'tooltip-feed-rss'              => 'Walug RSS ngir wii xët',
'tooltip-t-contributions'       => 'xool limu cërute gu jëfëndikookat bii',
'tooltip-t-emailuser'           => 'Yònnee ab bataaxal jëfëndikookat bii',
'tooltip-t-specialpages'        => 'Limu xët yu solowu yépp',
'tooltip-ca-nstab-main'         => 'Xool jukki bi',
'tooltip-ca-nstab-user'         => 'Xool xëtu jëfëndikookat bi',
'tooltip-ca-nstab-special'      => 'Lii am xët mu solowu la, mënoo koo soppi.',
'tooltip-ca-nstab-project'      => 'Xool xëtu naal bi',
'tooltip-ca-nstab-image'        => 'Xool xëtu nataal bi',
'tooltip-ca-nstab-template'     => 'Xool royuwaay gi',
'tooltip-ca-nstab-help'         => 'Xool xëtu ndimbal gi',
'tooltip-ca-nstab-category'     => 'Xool xëtu wàll gi',
'tooltip-minoredit'             => 'Melal samay coppite ñuy yu tuut',
'tooltip-save'                  => 'Duggal say coppite',
'tooltip-preview'               => 'wonendil say coppite balaa nga leen di duggal',
'tooltip-diff'                  => 'Day tax a gis coppite yi nga def, fesal lenn',
'tooltip-watch'                 => 'Yokk xët wii ci sa limu toppte',
'tooltip-recreate'              => 'Sosaat xët wi donte dañ kaa dindiwoo',

# Attribution
'anonymous' => 'Jëfëndikookat bu binduwul gu {{SITENAME}}',
'siteuser'  => 'Jëfëndikookat $1 bu {{SITENAME}}',

# Math options
'mw_math_html' => 'HTML su manee ne, lu ko moy PNG',

# Browsing diffs
'nextdiff' => 'Wuute ngi ci tegu →',

# Media information
'file-info'      => 'Réyaayu file bi : $1, type MIME : $2',
'file-info-size' => '($1 × $2 pixels, réyaayu file bi : $3, type MIME : $4)',
'show-big-image' => 'Ngandalal nataal gii',

# Special:Newimages
'ilsubmit' => 'Seet',
'bydate'   => 'ci diir',

# EXIF tags
'exif-usercomment' => 'Kadduy jëfëndikookat bi',

'exif-componentsconfiguration-0' => 'Amul',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'Yëpp',
'watchlistall2'    => 'yépp',
'namespacesall'    => 'Lépp',
'monthsall'        => 'Lépp',

# Trackbacks
'trackbackremove' => '([$1 Dindi])',

# Delete conflict
'confirmrecreate' => "Jëfëndikookat bii [[User:$1|$1]] ([[User talk:$1|Waxtaan]]) moo dindi xët wii, nga xam ne tambaliwoon nga koo defar, ngir ngirte lii :
: ''$2''
Dëgëlël ni bëgg ngaa sakkaat xët wii.",

# AJAX search
'articletitles' => 'Jukki yu tambalee « $1 »',

# Auto-summaries
'autoredircomment' => 'Jubluwaat fii [[$1]]',
'autosumm-new'     => 'Xët wu bees : $1',

# Watchlist editor
'watchlistedit-numitems'       => 'Sa xëtu toppte am na {{PLURAL:$1|aw xët|$1 ciy xët}}, soo ci gennee xëtu waxtaanukaay yi',
'watchlistedit-noitems'        => 'Sa limu toppte amul benn xët.',
'watchlistedit-normal-title'   => 'Coppiteg xëtu toppte gi',
'watchlistedit-normal-legend'  => 'Dindi ay xët yi limu toppte gi',
'watchlistedit-normal-explain' => 'xët yu sa limu toppte ñooy gisu fii ci suuf. Ngir dindi am xët (ak xëtu waxtaanukaayam) ci lim gi, kligal ci néeg moomu ci wetam te nga klig ci suuf. Man nga tamit  [[Special:Watchlist/raw|soppi ko]] walla [[Special:Watchlist/clear|setal lepp]].',
'watchlistedit-normal-submit'  => 'Dindi xët yi nga tann',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Dindi nañ am xët|$1 ciy xët dindi nañ leen}} ci sa limu toppte :',
'watchlistedit-raw-title'      => 'Coppiteg limu toppte gi',
'watchlistedit-raw-legend'     => 'Coppiteg limu toppte gi',
'watchlistedit-raw-explain'    => 'Limu xët yi nekk ci sa limu toppte moo nekk ci suuf, xëtu waxtaan yi nekku ñu ci(ñoo  ciy duggal seen bopp). Man ngaa soppi lim gi: yokk ci xët yi nga bëgg a topp, am xët ci rëdd mu ne, ak dindi xët yi nga bëggtul a topp. Soo noppee, kligal ci suuf ngir yeesal lim gi. Man nga tamit jëfëndikoo  [[Special:Watchlist/edit|Soppikaay gu mak gi]].',
'watchlistedit-raw-titles'     => 'Xët :',
'watchlistedit-raw-submit'     => 'Yeesal lim gi',
'watchlistedit-raw-done'       => 'Sa limu toppte yeesal nañ ko.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Yokk nañ ci aw xët|$1 ciy xët lañ fi yokk}} :',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Dindi nañ am xët|$1 ciy xët dindi nañ leen}} :',

# Watchlist editing tools
'watchlisttools-view' => 'Limu toppte',
'watchlisttools-edit' => 'Xool te soppi limu toppte gi',
'watchlisttools-raw'  => 'Soppi lim gi',

);

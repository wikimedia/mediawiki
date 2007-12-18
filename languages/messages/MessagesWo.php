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
'tog-hideminor'               => 'Nëbb coppite yu néewal yi mujj',
'tog-extendwatchlist'         => 'Jëfandikul limu toppte gi ñu gënal',
'tog-usenewrc'                => 'Jëfandikul coppite yu mujj yi ñu gënal (JavaScript)',
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
'tog-watchdeletion'           => 'Yokk ci sama limu toppte xët yi may dindi',
'tog-minordefault'            => 'jàppe samay coppite ni yu néewal saa su ne',
'tog-previewontop'            => 'Tegal wonendi ngi ci kaw balaa boyotu coppite gi',
'tog-previewonfirst'          => 'wone wonendi gi su dee soppi gu njëkk la',
'tog-enotifwatchlistpages'    => 'Yónne ma ab bataaxal su ab xët bu ne ci sama limu toppte soppikoo',
'tog-enotifusertalkpages'     => 'Yònnee ma ab bataaxal su ay coppite amee ci sama xëtu waxtaanukaay',
'tog-enotifminoredits'        => 'Yónne ma ab bataaxal donte coppite yu néew la ñu',
'tog-enotifrevealaddr'        => 'Wone sama makkaan gu mbëjfeppal ci bataaxali yëgle yi',
'tog-shownumberswatching'     => "Wone limu jëfandikukat yi'y topp wii xët",
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

# Bits of text used by many pages
'categories'      => 'Wàll',
'pagecategories'  => '{{PLURAL:$1|Wàll |Wàll }}',
'category_header' => 'Xët yi ci wàll gi « $1 »',
'subcategories'   => 'Ron-wàll',

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
'qbbrowse'       => 'Yàmbalaŋ',
'qbedit'         => 'Soppi',
'qbpageoptions'  => 'Xëtuw tànneef',
'qbpageinfo'     => 'Xëtuw xibaar',
'qbmyoptions'    => 'Samay tànneef',
'qbspecialpages' => 'Xët yu solowu',
'moredotdotdot'  => 'Ak yeneen...',
'mypage'         => 'Samaw xët',
'mytalk'         => 'Xëtu waxtaanuwaay',
'anontalk'       => 'Waxtaan ak bii IP',
'navigation'     => 'Joowiin',

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
'printableversion'  => 'Seexam gu móoluwu',
'permalink'         => 'Lëkkalekaay yu fi nekkandi',
'print'             => 'Móol',
'edit'              => 'Soppi',
'editthispage'      => 'Soppi xët wii',
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
'specialpage'       => 'Xët wu solowu',
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
'aboutsite'         => 'Maanaam ci {{SITENAME}}',
'aboutpage'         => 'Project:Maanaam ci',
'bugreports'        => 'Ngértey njuumte yi',
'bugreportspage'    => 'Project:Ngértey njuumte',
'copyright'         => 'Ndef gi jàppandi na ci $1.',
'currentevents'     => 'Luy xew',
'currentevents-url' => 'Project:Luy xew',
'disclaimers'       => 'Ay aartu',
'disclaimerpage'    => 'Project:Aartu yu daj',
'edithelp'          => 'Ndimbal',
'edithelppage'      => 'Help:Nooy soppee aw xët',
'helppage'          => 'Help:Ndimbal',
'mainpage'          => 'Xët wu njëkk',
'portal'            => 'Askan',
'portal-url'        => 'Project:Xët wu njëkk',
'sitesupport'       => 'Joxe ag ndimbal',
'sitesupport-url'   => 'Project:Joxe ag ndimbal',

'badaccess'        => 'Njuumte ci ndigël gi',
'badaccess-group0' => 'Amoo ay sañ-sañ yu doy ngir man a def li nga bëgg a def.',
'badaccess-group1' => 'jëf ji ngay jéem a def ñi bokk ci mbooloo mii $1 rek ñoo ko man.',
'badaccess-group2' => 'Jëf ji ngay jéem a def, jëfandikukatu mbooloo yii $1 rek ñoo ko man.',
'badaccess-groups' => 'Jëf ji ngay jéem a def, jëfandikukatu mbooloo yii $1 rek ñoo ko man.',

'ok'                      => 'waaw',
'retrievedfrom'           => 'Ci « $1 » lañ ko jële',
'youhavenewmessages'      => 'Am nga $1 ($2).',
'newmessageslink'         => 'Bataaxal yu bees',
'newmessagesdifflink'     => 'Coppite gu mujj',
'youhavenewmessagesmulti' => 'Am nga ay bataaxal yu bees ci $1',
'editsection'             => 'Soppi',
'editold'                 => 'Soppi',
'editsectionhint'         => 'Soppi bii xaaj : $1',
'toc'                     => 'Tëralin',
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

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Jukki',
'nstab-user'      => 'Xëtu jëfandikukat',
'nstab-special'   => 'Solowu',
'nstab-project'   => 'Manaam ci',
'nstab-image'     => 'Dencukaay',
'nstab-mediawiki' => 'Bataaxal',
'nstab-template'  => 'Royuwaay',
'nstab-help'      => 'Ndimbal',
'nstab-category'  => 'Wàll',

# Main script and global functions
'nosuchaction'      => 'Jëf ji xameesu ko',
'nosuchactiontext'  => 'Jëf ji ñu biral ci bii URL wiki bi xammeewu ko.',
'nosuchspecialpage' => 'Xët wu solowu wu amul',
'nospecialpagetext' => 'Da nga laaj aw xët wu solowu wu wiki gi xamul. Ab limu xët yu solowu yépp ma nees na koo gis ci [[{{ns:special}}:Specialpages]]',

# General errors
'error'                => 'Njuumte',
'noconnect'            => 'Jéggalu! ngir ay tolof-tolofi xarala, fi mu ne nii duggu gi jàppandiwul. <br />
$1',
'laggedslavemode'      => 'Moytul, wii xët man naa bañ a man dékku coppite yi ñu mujjee def',
'enterlockreason'      => 'Biralal ngirtey tëj gi ak diir bi mu war a amee',
'readonlytext'         => 'Les ajouts et mises à jour sur la base de données sont actuellement bloqués, probablement pour permettre la maintenance de la base, après quoi, tout rentrera dans l’ordre.
L’administrateur ayant verrouillé la base de données a donné l’explication suivante :$1',
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
'badtitle'             => 'Bopp bu baaxul',
'badtitletext'         => 'Boppu xët wi nga laaj baaxul, amul dara walla  day di boppu diggantey-làkk walla diggantey-sémb yu seen lëkkaloo baaxul. Xayna it dafa am benn walla ay araf yu ñu manuta jëfandikoo ci bopp.',
'viewsource'           => 'Xool gongikuwaayam',
'viewsourcefor'        => 'ngir $1',
'actionthrottled'      => 'Jêf ju digal',
'actionthrottledtext'  => 'Ngir xeex spam yi, jëf ji nga namm a def dañ kaa digal ci yoon yoo ko man ci benn diir bu gatt. Te mel na ne romb nga boobu dig. Jéemaatal fii aki simili.',
'protectedpagetext'    => 'Wii xêt dañ kaa aar ngir bañ ag coppiteem.',
'viewsourcetext'       => 'Man ngaa xool te duppi li nekk ci bii jukki ngir man cee liggéey :',

# Login and logout pages
'logouttitle'                => 'Gennu',
'logouttext'                 => "'''Fi mu nekk nii genn nga.'''<br />
Man ngaa wëy di jëfëndikoo {{SITENAME}} ci anam guñ la dul xamme walla duggeewaat ak menn tur wi walla ak menee.",
'welcomecreation'            => '== Dalal-jamm, $1 ! ==

Sag mbindu sotti na. Bul fatte soppi say tànneef ni nga ko bëggee ci {{SITENAME}}.',
'loginpagetitle'             => 'Duggu',
'yourname'                   => 'Sa turu jëfëndikookat',
'yourpassword'               => 'Sa baatu duggu',
'yourpasswordagain'          => 'Duggalaatal sa baatu duggu',
'remembermypassword'         => 'Fattaliku sama baatu duggu(cookie)',
'loginproblem'               => '<b>Jafe-jafey xammeku.</b><br />Jeemaatal!',
'login'                      => 'xammeeku',
'loginprompt'                => 'Faw nga doxal cookie yi ngir ma na dugg ci {{SITENAME}}.',
'userlogin'                  => 'Bindu/Duggu',
'logout'                     => 'Gennu',
'userlogout'                 => 'Gennu',
'nologin'                    => 'Binduwoo ? $1.',
'nologinlink'                => 'Sos am sàq',
'createaccount'              => 'Bindu',
'gotaccount'                 => 'Bindu nga ba noppi? $1.',
'gotaccountlink'             => 'Xammeku',
'badretype'                  => 'Baatu duggu yi nga bind Yemuñu.',
'userexists'                 => 'Turu jëfëndikookat bi nga bind am na boroom ba noppi. Tannal beneen.',
'youremail'                  => 'Sa e-mail:',
'username'                   => 'Tutu jëfëndikookat :',
'yourrealname'               => 'Sa tur dëgg*',
'yournick'                   => 'Xaatim ngir say waxtaan :',
'badsiglength'               => 'Sa xaatim daa gudd lool: guddaay bi ëpp mooy $1 araf.',
'loginerror'                 => 'Njuumte ci xammeeku gi',
'nocookiesnew'               => 'Sàqum jëfëndikookat mi sosu na, waaye duggoo. {{SITENAME}} day jëfëndikoo ay cookie ngir duggu bi waaye da nga leen doxadil. Doxal leen te duggaat ak sa tur ak sa baatu duggu',
'nocookieslogin'             => '{{SITENAME}} day jëfëndikoo ay cookie ngir dugg gi te yawe say cookies da ñoo doxadi. Doxal leen te jeema duggaat.',
'noname'                     => 'Bindoo turu jëfëndikookat bi baax.',
'loginsuccesstitle'          => 'Sag xammeeku jàll na',
'loginsuccess'               => 'Leegi nak dugg nga ci {{SITENAME}} yaay « $1 ».',
'wrongpassword'              => 'Bii baatu duggu baaxul. Jéemaatal.',
'wrongpasswordempty'         => 'Duggaloo ab baatu duggu, jéemaatal.',
'passwordtooshort'           => 'Sa baatu duggu dafa gatt. war naa am $1 araf lumu néew néew te itam wute ag sa turu jëfëndikookat.',
'mailmypassword'             => 'Yònnee ma ab baatu duggu bu bees',
'passwordremindertitle'      => 'Sa baatu duggu bu bees ci {{SITENAME}}',
'passwordsent'               => 'Ab baatu duggu bu bees yòonnee nañ ko ci bii e-mailu jëfëndikookat « $1 ». Jèemala duggaat soo ko jotee.',
'throttled-mailpassword'     => "Ab bataaxal bu la'y fattali sa baatu duggu yònnee nañ la ko ci diiru $1 waxtu yu mujju. Ngir moytu ay say-sayee, benn bataaxalu fattali rek lañ lay yònnee ci diiru $1 waxtu.",
'acct_creation_throttle_hit' => 'Jeggalu, bindu nga $1 yoon. manoo binduwaat',
'accountcreated'             => 'léegi bindu nga.',
'accountcreatedtext'         => 'Mbindug jëfëndikookat $1 jall na',
'loginlanguagelabel'         => 'Làkk : $1',

# Edit page toolbar
'link_sample' => 'Koju lëkkalekaay bi',
'link_tip'    => 'Lëkkalekaay yu biir',
'extlink_tip' => 'Lëkkalekaay yu biti (bul fattee jiital http://)',

# Edit pages
'summary'                 => 'Koj&nbsp;',
'minoredit'               => 'Coppite yu tuut',
'watchthis'               => 'Topp xët wii',
'savearticle'             => 'Duggal coppite yi',
'preview'                 => 'Wanendi',
'showpreview'             => 'Wonendi',
'showdiff'                => 'Wone coppite yi',
'anoneditwarning'         => "'''Moytul :''' xammekuwoo. sa IP di nañ ko duggal ci jaar-jaaru xët mii.",
'missingcommenttext'      => 'Merci d’insérer un résumé ci-dessous.',
'missingcommentheader'    => "'''Rappel :''' Vous n’avez pas fourni de sujet/titre à ce commentaire. Si vous cliquez à nouveau sur « Sauvegarder », votre édition sera enregistrée sans commentaire.",
'summary-preview'         => 'Wonendi koj gi',
'blockededitsource'       => "Ndefug '''say coppite''' yi nga def fii '''$1''' mooy lii ci suuf:",
'whitelistedittitle'      => 'Laaj na nga bindi ngir mana soppi ndef gi',
'whitelistedittext'       => 'Da ngay  wara doon $1 ngir am sañ-sañu soppi ngef gi.',
'whitelistreadtitle'      => 'Laaj na nga bindu ngir mana jàng ndef gi',
'whitelistreadtext'       => 'Da nga wara [[Special:Userlogin|duggu]] ngir jàng ndef gi.',
'whitelistacctitle'       => 'Amoo sañ-sañu bindu.',
'loginreqtitle'           => 'Laaj na nga bindu',
'loginreqlink'            => 'Duggu',
'loginreqpagetext'        => 'Faw nga $1 ngir gis yeneen xët yi.',
'accmailtitle'            => 'Baatu duggu yònnee nañ ko.',
'accmailtext'             => 'Baatu duggu gu « $1 » yònnee nañ ko fii $2.',
'newarticle'              => '(Bees)',
'newarticletext'          => "Da ngaa topp ab lëkkalekaay buy jëme ci aw xët wu amagul. ngir sos wii xët, duggalal sa mbind ci boyot bii ci suuf (man ngaa yër [[{{MediaWiki:Helppage}}|xëtu ndimbal wi]] ngir yeneeni xibaar). Su fekkee njuumtee la fi indi bësal ci '''dellu''' bu sa tukkikat.",
'anontalkpagetext'        => "---- ''Vous êtes sur la page de discussion d’un utilisateur anonyme qui n’a pas encore créé de compte ou qui ne l’utilise pas. Pour cette raison, nous devons utiliser son adresse IP pour l’identifier. Une adresse IP peut être partagée par plusieurs utilisateurs. Si vous êtes un utilisateur anonyme et si vous constatez que des commentaires qui ne vous concernent pas vous ont été adressés, vous pouvez [[Special:Userlogin|créer un compte ou vous connecter]] afin d’éviter toute confusion future avec d’autres contributeurs anonymes.''",
'noarticletext'           => 'Fi mu ne ni amul benn mbind ci xët wii; man ngaa [[{{ns:special}}:Search/{{PAGENAME}}|tambli ab seet ci koju xët wii]] walla [{{fullurl:{{FULLPAGENAME}}|action=edit}} soppi xët wii].',
'token_suffix_mismatch'   => '<strong>Votre édition n’a été acceptée car votre navigateur a mélangé les caractères de ponctuation dans l’identifiant d’édition. L’édition a été rejetée afin d’empêcher la corruption du texte de l’article. Ce problème se produit lorsque vous utilisez un proxy anonyme à problème.</strong>',
'editing'                 => 'Coppiteg $1',
'editinguser'             => 'Coppiteg $1',
'yourtext'                => 'Sa mbind',
'nonunicodebrowser'       => '<strong>Attention : Votre navigateur ne supporte pas l’unicode. Une solution temporaire a été trouvée pour vous permettre de modifier en tout sûreté un article : les caractères non-ASCII apparaîtront dans votre boîte de modification en tant que codes hexadécimaux. Vous devriez utiliser un navigateur plus récent.</strong>',
'editingold'              => '<strong>Attention : vous êtes en train de modifier une version obsolète de cette page. Si vous sauvegardez, toutes les modifications effectuées depuis cette version seront perdues.</strong>',
'yourdiff'                => 'Wuute',
'cascadeprotectedwarning' => '<strong>MOYTUL : Xët mii dañ kaa aar ba nga xamne [[{{MediaWiki:Grouppage-sysop}}|yorkat yi]] rek ñoo koy mana soppi. Kaaraange googu dañ kaa def ndaxte xët mii dañ kaa boole ci {{PLURAL:$1|am xët mu ñu aar|ay xët yu ñu aar}} ak « kaaraange cig toppante » te dañ kaa taal.</strong>',
'templatesused'           => 'Royuwaay yi nekk ci mii xët :',
'templatesusedpreview'    => 'Royuwaay yi nekk ci gii wonendi :',
'template-protected'      => '(aar)',

# Account creation failure
'cantcreateaccounttitle' => 'sag mbindu Mënu la nekk .',

# History pages
'viewpagelogs' => 'Xool yéenekaayu xët mii',
'nohistory'    => 'Xët wii amulub jaar-jaar.',
'cur'          => 'Xibaar',
'next'         => 'tegu',
'histlast'     => 'Li ñu mujje indi',

# Search results
'noexactmatch' => "'''Amul wenn xët wu tudd « $1 » wu am.''' man ngaa [[:$1|sakk xët wi]].",
'nextn'        => '$1 yi tegu',
'viewprevnext' => 'Xool ($1) ($2) ($3).',

# Preferences page
'mypreferences'        => 'Samay tànneef',
'changepassword'       => 'Coppiteeg baatu duggu bi',
'skin'                 => 'Apparence',
'prefs-watchlist'      => 'Limu toppte',
'prefs-watchlist-days' => 'Limu bes yi nga koy ba ci sa limu toppte :',
'saveprefs'            => 'Duggal tànneef yi',
'oldpassword'          => 'Baatu duggu bu yagg :',
'newpassword'          => 'Baatu duggu bu bees :',
'retypenew'            => 'Dëggalal baatu dugg bu bees bi :',
'searchresultshead'    => 'Seet',
'recentchangesdays'    => 'Limu bes yi nga koy wone ci coppite yu mujj yi :',
'recentchangescount'   => 'Limu coppite yi ngay wone ci coppite yu mujj yi :',
'savedprefs'           => 'Tànneey yi duggal nañ leen.',
'allowemail'           => 'ndigëlël yeneeni jëfëndikookat mën laa yòonnee bataaxal',

# User rights
'userrights-lookup-user'     => 'Yorinu yelleefu jëfëndikookat',
'userrights-user-editname'   => 'Duggal am turu jëfëndikookat :',
'editusergroup'              => 'Coppiteg mbooloo Jëfëndikookat yi',
'userrights-editusergroup'   => 'Soppi mbooloo yu jëfëndikookat bi',
'saveusergroups'             => 'Duggal mbooloo jëjëndikookat yi',
'userrights-groupsmember'    => 'Way-bokk gu:',
'userrights-groupsavailable' => 'Mbooloo yi jappandi:',
'userrights-groupshelp'      => 'Tannal mbooloo yi nga bëgg a jëlee walla yokk ab jëfëndikookat. Mbooloo yi nga tannul duñu soppiku. Man nga tannadi am mbooloo ak CTRL + klig cammooñ.',
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
'recentchangestext'                 => 'Toppal ci mii xët coppite yu mujj ci {{SITENAME}}.',
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

# Image list
'imagelist'      => 'Limu nataal yi',
'ilsubmit'       => 'Seet',
'byname'         => 'ci tur',
'bydate'         => 'ci diir',
'bysize'         => 'ci ngandaay',
'imagelinks'     => 'Xët yi am bii nataal',
'linkstoimage'   => 'Xët yii ci suuf am nañ ci seen biir bii nataal :',
'imagelist_user' => 'Jëfëndikookat',

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
'allpages'                => 'Xët yëpp',
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

'categoriespagetext' => 'Wàll yii ñoo am ci biir wiki gi.',
'alphaindexline'     => '$1 ba $2',

# Special:Log
'specialloguserlabel'  => 'Jëfëndikookat :',
'speciallogtitlelabel' => 'Koj :',
'log'                  => 'Yéenekaay',
'all-logs-page'        => 'Yéenekaay yëpp',
'log-search-legend'    => 'Seet ci yéenekaay yi',
'log-search-submit'    => 'waaw',
'logempty'             => 'Dara nekkul ci jaar-jaaru xët mii.',

# Special:Allpages
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

# Displayed when you click the "watch" button and it's in the process of watching
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
'mycontris'     => 'Li ma indiwoon',
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
'movepage'        => 'Tuddewaat aw xët',
'movearticle'     => 'Tuddewaatal jukki bi',
'movenologintext' => 'Ngir man a tuddewaat aw xët, da ngaa war a [[Special:Userlogin|dugg]] ni jëfëndikookat bu bindu te saw sàq war naa am yaggaa bi mu laaj.',
'newtitle'        => 'Koj bu bees',
'move-watch'      => 'Topp xët wii',
'articleexists'   => 'Am na ba noppi ab jukki gu am gii koj, walla koj gi nga tann baaxul. tannal bennen.',
'movedto'         => 'Turam bu bees',
'movetalk'        => 'Tuddewaat tamit xëtu waxtaanukaay wi mu andal',
'1movedto2'       => 'tuddewaat ko [[$1]] en [[$2]]',
'1movedto2_redir' => 'yòonalaat ko [[$1]] mu jëm [[$2]]',
'movelogpage'     => 'Jaar-jaaru tuddewaat yi',
'movelogpagetext' => 'Lii mooy limu xët yi ñu mujje tuddewaat.',
'movereason'      => 'Ngirtey tuddewaat bi',
'delete_and_move' => 'Dindi te tuddewaat',

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
'and'       => 'ak',

# Math options
'mw_math_html' => 'HTML su manee ne, lu ko moy PNG',

# Browsing diffs
'nextdiff' => 'Wuute ngi ci tegu →',

# Media information
'file-info'      => 'Réyaayu file bi : $1, type MIME : $2',
'file-info-size' => '($1 × $2 pixels, réyaayu file bi : $3, type MIME : $4)',
'show-big-image' => 'Ngandalal nataal gii',

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

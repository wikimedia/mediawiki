<?php
/** Tachelhit (Tašlḥiyt)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Dalinanir
 * @author Zanatos
 */

$fallback = 'ar';

$messages = array(
# User preference toggles
'tog-underline'              => 'krrj du izdayn:',
'tog-highlightbroken'        => 'sbaynd izdayn li khsrnin   <a href="" class="new">zod ghika</a> (nghd: zod ghika <a href="" class="internal">?</a>)',
'tog-justify'                => 'skr lɛrd n-stor ɣ togzimin aygiddi',
'tog-hidepatrolled'          => 'Hide patrolled edits in recent changes',
'tog-numberheadings'         => 'nmra n nsmiat wahdot',
'tog-showtoolbar'            => 'sbaynd tizikrt n tbddil(JavaScript)',
'tog-rememberpassword'       => '↓ Askti nu ukcum ɣ Urdinaturad (Iɣ kullu tggut $1 {{PLURAL:$1|Ass|Ass}})',
'tog-watchcreations'         => 'Zaydn tasniwin lli skrɣ i umuɣ n tilli ssuġiɣ.',
'tog-watchdefault'           => 'Zaydn tasniwin lli tżrigɣ i umuɣ n tilli tsaggaɣ',
'tog-watchmoves'             => 'Zayd tisniwin lli smattayɣ i tilli tsggaɣ.',
'tog-watchdeletion'          => 'Zaydn tasniwin lli kkesɣ i tilli tsaggaɣ',
'tog-minordefault'           => 'Rcm kullu iẓṛign li fssusni sɣiklli gan.',
'tog-nocache'                => 'ador itsjjal lmtasaffih tawriqt ad',
'tog-enotifwatchlistpages'   => 'sifd yi tabrat  igh ibdl kra yat twriqt ghomdfor inu',
'tog-enotifusertalkpages'    => 'sifd yi tabrat  igh tbdl tawriqt ohokko-no',
'tog-enotifminoredits'       => 'sifd yi tabrat  i ibdln mziynin',
'tog-watchlisthideown'       => 'hbo ghayli bdlgh gh omdfor inu',
'tog-watchlisthidebots'      => 'hba ghayli bdln robotat gh omdfor inu',
'tog-watchlisthideminor'     => 'hbo ibdln mziynin gh omdfor inu',
'tog-watchlisthideliu'       => 'hbo ibdln n wili tsjlnin gh omdfr ino',
'tog-watchlisthideanons'     => 'hbo ibdl n wili orisjilnin gh omdfor ino',
'tog-watchlisthidepatrolled' => 'hbo ibdln lityozranin gh omdfor ino',
'tog-ccmeonemails'           => 'sifd yi noskha n tibratin liyid safdn wiyad',
'tog-showhiddencats'         => 'sbaynd tsnifat ihbanin',
'tog-norollbackdiff'         => 'hiyd lfarq baad lqiyam bstirjaa',

'underline-always'  => 'dima',
'underline-never'   => 'ḥtta manak',
'underline-default' => 'ala hssad regalhe n lmotasaffih',

# Font style option in Special:Preferences
'editfont-style'     => 'lkht n lmintaqa nthrir',
'editfont-default'   => 'ala hssab reglage n lmotasaffih',
'editfont-monospace' => 'kht ard tabt',
'editfont-sansserif' => 'lkht bla zwayd',
'editfont-serif'     => 'lkht szwayd',

# Dates
'sunday'        => 'assamass',
'monday'        => 'aynass',
'tuesday'       => 'assinas',
'wednesday'     => 'akrass',
'thursday'      => 'akouass',
'friday'        => 'assimuass',
'saturday'      => 'assidias',
'sun'           => 'assamass',
'mon'           => 'aynass',
'tue'           => 'assinas',
'wed'           => 'akrass',
'thu'           => 'akouass',
'fri'           => 'assimuass',
'sat'           => 'assidias',
'january'       => 'yennayer',
'february'      => 'xubrayr',
'march'         => 'Mars',
'april'         => 'ibrir',
'may_long'      => 'mayyuh',
'june'          => 'yunyu',
'july'          => 'yulyu',
'august'        => 'ɣusht',
'september'     => 'shutanbir',
'october'       => 'kṭuber',
'november'      => 'Nuwanber',
'december'      => 'Dujanbir',
'january-gen'   => 'Innayr',
'february-gen'  => 'Brayr',
'march-gen'     => 'Mars',
'april-gen'     => 'Ibrir',
'may-gen'       => 'Mayyu',
'june-gen'      => 'yunyu',
'july-gen'      => 'yulyu',
'august-gen'    => 'ɣuct',
'september-gen' => 'Cutanbir',
'october-gen'   => 'kṭubr',
'november-gen'  => 'Nuwanbir',
'december-gen'  => 'Dujanbir',
'jan'           => 'yennayer',
'feb'           => 'brayr',
'mar'           => 'Mars',
'apr'           => 'Ibrir',
'may'           => 'Mayyuh',
'jun'           => 'yunyu',
'jul'           => 'yulyu',
'aug'           => 'ɣuct',
'sep'           => 'cutanbir',
'oct'           => 'kṭuber',
'nov'           => 'Nuwanber',
'dec'           => 'Dujanbir',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|amggrd|imggrad}}',
'category_header'        => 'tiwriqin ɣ-omggrd "$1"',
'subcategories'          => 'imggrad-mzin',
'category-media-header'  => 'tiwriqin ɣ-omggrd "$1"',
'hidden-categories'      => '{{PLURAL:$1|taggayt igdln|taggayin gdlnin}}',
'category-subcat-count'  => 'Taggayt ad gis {{PLURAL:$2|ddu taggayt|$2 ddu taggayin, lli ɣ tlla {{PLURAL:$1|ɣta|ɣti $1}}}} γu flla nna.',
'category-article-count' => '↓ Taggayt ad gis {{PLURAL:$2|tasna d itfrn|$2 tisniwin, lliɣ llant {{PLURAL:$1|ɣta|ɣti $1}} n uflla yan}}.',
'listingcontinuesabbrev' => 'Attfar',

'about'         => 'F',
'article'       => 'Mayllan ɣ tasna',
'newwindow'     => 'Murzemt ɣ tasatmt tamaynut',
'cancel'        => 'ḥiyyd',
'moredotdotdot' => 'Uggar...',
'mypage'        => 'Tasnat inu',
'mytalk'        => 'Amsgdal inu',
'anontalk'      => 'Amsgdal i w-ansa yad',
'navigation'    => 'Tunigin',
'and'           => '&#32; d',

# Cologne Blue skin
'qbfind'         => 'Af',
'qbbrowse'       => 'Cabba',
'qbedit'         => 'Sbadl',
'qbpageoptions'  => 'Tasnat ad',
'qbpageinfo'     => 'Context',
'qbmyoptions'    => 'Tisnatin inu',
'qbspecialpages' => 'Tisnatin timzlay',
'faq'            => 'Isqsitn li bdda tsutulnin',
'faqpage'        => 'Project: Isqqsit li bdda',

# Vector skin
'vector-action-addsection' => 'Zayd amli',
'vector-action-delete'     => 'Ḥiyd',
'vector-action-move'       => 'Smmatti',
'vector-action-protect'    => 'Ḥbu',
'vector-action-undelete'   => 'Rard may mayḥiydn',
'vector-action-unprotect'  => 'Ḥiyd aḥbu',
'vector-view-create'       => 'Skert',
'vector-view-edit'         => 'Ara',
'vector-view-history'      => 'Mel amzruy',
'vector-view-view'         => 'ɣr',
'vector-view-viewsource'   => 'Ẓr asagm',
'actions'                  => 'Imskarn',
'namespaces'               => 'Ismawn n tɣula',
'variants'                 => 'lmotaghayirat',

'errorpagetitle'    => 'Laffut',
'returnto'          => 'Urri s $1.',
'tagline'           => 'Ž {{SITENAME}}',
'help'              => 'Asaws',
'search'            => 'Acnubc',
'searchbutton'      => 'Cabba',
'go'                => 'Balak',
'searcharticle'     => 'Ftu',
'history'           => 'Amzruy n tasna',
'history_short'     => 'Amzruy',
'updatedmarker'     => 'Tuybddal z tizrink li iğuran',
'info_short'        => 'Inɣmisn',
'printableversion'  => 'Tasna nu sugz',
'permalink'         => 'Azday Bdda illan',
'print'             => 'Siggz',
'edit'              => 'Ẓreg (bddel)',
'create'            => 'Skr',
'editthispage'      => 'Ara tasna yad',
'create-this-page'  => 'Sker tasna yad',
'delete'            => 'Ḥiyd',
'deletethispage'    => 'Ḥiyd tasna yad',
'undelete_short'    => 'Yurrid {{PLURAL:$1|yan umbddel|$1 imbddeln}}',
'protect'           => 'Ḥbu',
'protect_change'    => 'Abddel',
'protectthispage'   => 'Ḥbu tasna yad',
'unprotect'         => 'Kksas aḥbu',
'unprotectthispage' => 'Kks aḥbu i tasnatad',
'newpage'           => 'tawriqt tamaynut',
'talkpage'          => 'Sgdl f tasna yad',
'talkpagelinktext'  => 'Amsgdal',
'specialpage'       => 'Tasna izlin',
'personaltools'     => 'Imasn inu',
'postcomment'       => 'Ayyaw amaynu',
'articlepage'       => 'Mel mayllan ɣ tasna',
'talk'              => 'Amsgdal',
'views'             => 'Ẓr.. (Mel)',
'toolbox'           => 'Tanaka n imasn',
'userpage'          => 'Ẓr n tasna n umsqdac',
'projectpage'       => 'Ẓr tasna n tuwwuri',
'imagepage'         => 'Ẓr tasna n-usddaw',
'mediawikipage'     => 'Ẓr tasna n tabrat',
'templatepage'      => 'Ẓr tasna n Tamudemt',
'viewhelppage'      => 'Ẓr tasna n-aws',
'categorypage'      => 'Ẓr tasna n taggayt',
'viewtalkpage'      => 'Ẓr amsgdal',
'otherlanguages'    => 'S tutlayin yaḍnin',
'redirectedfrom'    => '(Tmmuttid z $1)',
'redirectpagesub'   => 'Tasna n-usmmattay',
'lastmodifiedat'    => 'Imbddeln imggura n tasna yad z $1, s $2.',
'viewcount'         => 'Tmmurzm tasna yad {{PLURAL:$1|yat twalt|$1 mnnawt twal}}.',
'protectedpage'     => 'Tasnayat iqn ugdal nes.',
'jumpto'            => 'Ftu s:',
'jumptonavigation'  => 'Tunigen',
'jumptosearch'      => 'Acnubc',
'view-pool-error'   => 'Surf, iqddacn žayn ɣilad. mnnaw midn yaḍnin ay siggiln tasna yad. Qqel imik fad addaɣ talst at tarmt at lkmt tasna yad

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'F {{SITENAME}}',
'aboutpage'            => "Project:f'",
'copyright'            => 'Mayllan gis illa ɣ ddu $1.',
'copyrightpage'        => '{{ns:project}}:Izrfan n umgay',
'currentevents'        => 'Immussutn n ɣila',
'currentevents-url'    => 'Project:Immussutn n ɣilad',
'disclaimers'          => 'Ur darssuq',
'disclaimerpage'       => 'Project: Ur illa maddar illa ssuq',
'edithelp'             => 'Aws ɣ tirra',
'edithelppage'         => 'Help:Imaratn',
'helppage'             => 'Help:Mayllan',
'mainpage'             => 'Tasana tamzwarut',
'mainpage-description' => 'Tasna tamzwarut',
'policy-url'           => 'Project:Tasrtit',
'portal'               => 'Ağur n w-amun',
'portal-url'           => 'Project:Ağur n w-amun',
'privacy'              => 'Tasrtit n imzlayn',
'privacypage'          => 'Project:Tasirtit ni imzlayn',

'badaccess'        => 'Anezri (uras tufit)',
'badaccess-group0' => 'Ur ak ittuyskar at sbadelt ma trit',
'badaccess-groups' => 'Ɣaylli trit at tskrt ɣid ittuyzlay ɣir imsxdamn ɣ tamsmunt{{PLURAL:$2|tamsmunt|yat ɣ timsmuna}}: $1.',

'versionrequired'     => 'Txxṣṣa $1 n MediaWiki',
'versionrequiredtext' => 'Ixxṣṣa w-ayyaw $1 n MediaWiki bac at tskrert tasna yad.
Ẓr [[Special:Version|ayyaw tasna]].',

'ok'                      => 'Waxxa',
'pagetitle'               => '(MediaWiki)$1 - {{SITENAME}}',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'retrievedfrom'           => 'Yurrid z "$1"',
'youhavenewmessages'      => 'Illa dark $1 ($2).',
'newmessageslink'         => 'Tibratin timaynutin',
'newmessagesdifflink'     => 'Imbddeln imĝura',
'youhavenewmessagesmulti' => 'Dark tibratin timaynutin ɣ $1',
'editsection'             => 'Ẓreg (bddel)',
'editsection-brackets'    => '[$1]',
'editold'                 => 'Ẓreg (bddel)',
'viewsourceold'           => 'Mel aɣbalu',
'editlink'                => 'Ẓreg (bddel)',
'viewsourcelink'          => 'Mel aɣbalu',
'editsectionhint'         => 'Ẓreg ayyaw: $1',
'toc'                     => 'Mayllan',
'showtoc'                 => 'Mel',
'hidetoc'                 => 'ḥbu',
'thisisdeleted'           => 'Mel niɣd rard $1?',
'viewdeleted'             => 'Mel $1?',
'feedlinks'               => 'tlqim:',
'site-rss-feed'           => "$1 lqm n' RSS",
'site-atom-feed'          => "$1 lqm n' atom",
'page-rss-feed'           => '"$1" tlqim RSS',
'page-atom-feed'          => '$1 azday atom',
'red-link-title'          => '$1 (tasna yad ur tlli)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Tasnat',
'nstab-user'      => 'Tasnat u-msxdam',
'nstab-media'     => 'Tasnat Ntuzumt',
'nstab-special'   => 'Tasna tamzlit',
'nstab-project'   => 'Tasna n tuwuri',
'nstab-image'     => 'Asdaw',
'nstab-mediawiki' => 'Tabrat',
'nstab-template'  => 'Talɣa',
'nstab-help'      => 'Tasna n-aws',
'nstab-category'  => 'Taggayt',

# Main script and global functions
'nosuchaction'      => 'Ur illa mat iskrn',
'nosuchactiontext'  => 'Mytuskarn ɣu tansa yad ur tti tgi.

Irwas is turit tansa  skra mani yaḍnin, ulla azday ur igi amya.

Tzdar attili tamukrist ɣ {{SITENAME}}.',
'nosuchspecialpage' => 'Urtlla tasna su w-ussaɣad',
'nospecialpagetext' => '<strong>Trit yat tasna tamzlit ur illan.</strong>

Tifilit n tasnayin gaddanin ratn taft ɣid [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'               => 'Laffut',
'databaseerror'       => 'Laffut ɣ database',
'dberrortext'         => 'Tlla laffut ɣikli s tskert database.
Ulla mayad kis kra ntmukrist.
May igguran ittu isigal ɣ mayad igat.
<blockquote><tt>$1</tt></blockquote>
S ussiglad "<tt>$2</tt>".
laffut d yurrin ɣ database "<tt>$3: $4</tt>".',
'laggedslavemode'     => 'Ḥan tasnayad ur gis graygan ambddel amaynu.',
'readonly'            => 'Tqqn tabase',
'missing-article'     => 'lqaa\'ida n lbayanat ortofa nass ad gh tawriqt  liss ikhssa asti taf limism "$1" $2.

ghikad artitsbib  igh itabaa lfrq aqdim nghd tarikh artawi skra nsfha ityohyadn.

ighor iga lhal ghika ati ran taft kra lkhata gh lbarnamaj.

ini mayad ikra [[Special:ListUsers/sysop|lmodir]] tfktas ladriss ntwriqt an.',
'missingarticle-rev'  => '(lmorajaaa#: $1)',
'missingarticle-diff' => '(lfarq: $1, $2)',
'internalerror'       => 'khata ghogns',
'internalerror_info'  => 'khata ghogns :$1',
'fileappenderrorread' => 'orimkin anghr "$1"',
'fileappenderror'     => 'orimkn anzayd "$1" s "$2".',
'filecopyerror'       => 'orimkin ankopi "$1" s "$2".',
'filerenameerror'     => '↓ ur as tufit ad tsmmut "$1" s "$2".',
'filenotfound'        => 'orimkn anaf "$1"',
'fileexistserror'     => 'orimkn anara "$1" lmilf ad illa yad',
'formerror'           => "khata': orimkn anskr lform ad",
'badtitle'            => 'onwan orifolki',
'badtitletext'        => '↓ Azwl n tasna lli trit ur igadda, ixwa, niɣd iga aswl n gr tutlayt niḍ ngr tuwwurins ur izdimzyan. Ẓr urgis tgit kra nu uskkil niɣd mnnaw lli gis ur llanin',
'viewsource'          => 'Mel iɣbula',
'viewsourcefor'       => 'l $1',

# Virus scanner
'virus-unknownscanner' => 'antivirus oritwsan',

# Login and logout pages
'welcomecreation'         => '== Brrkat,  $1! ==
lcont nek  tuyskar .
 Adur tut atbaddlt [[Special:Preferences|{{SITENAME}} issusmen]]',
'yourname'                => 'smiyt o-msxdam:',
'yourpassword'            => 'awal iḥdan:',
'yourpasswordagain'       => 'Зawd ara awal iḥdan:',
'remembermypassword'      => 'Askti nu ukcum ɣ Urdinaturad (Iɣ kullu tggut $1 {{PLURAL:$1|Ass|Ass}})',
'yourdomainname'          => 'Taɣult nek',
'externaldberror'         => 'Imma tlla ɣin kra lafut ɣu ukcumnk ulla urak ittuyskar at tsbddelt lkontnk nbrra.',
'login'                   => 'Kcm ɣid',
'nav-login-createaccount' => 'kcm / murzm Amidan',
'loginprompt'             => 'You must have cookies enabled to log in to {{SITENAME}}.',
'userlogin'               => 'kchem / qiyd amskhdam amaynu',
'userloginnocreate'       => 'Kcm ɣid',
'logout'                  => 'Fuɣ',
'userlogout'              => 'Fuɣ',
'notloggedin'             => 'Ur tmlit mat git',
'nologin'                 => 'Ur trzemt amidan (lkunt) nek? $1..',
'nologinlink'             => 'Murzm amidan nek (lkunt)..',
'createaccount'           => 'Murzm amidan nek (lkunt)..',
'gotaccount'              => 'Is nit dark amidan(lkunt)? $1.',
'gotaccountlink'          => 'Kcm',
'createaccountmail'       => 'S tirawt taliktunant',
'createaccountreason'     => 'Maɣ:',
'badretype'               => 'Tasarut lin tgit ur dis tucka.',
'userexists'              => 'Asaɣ nu umsqdac li tskcmt illa yad',
'loginerror'              => 'Gar akccum',
'createaccounterror'      => '$1 ur as tufit at kcmt',
'loginsuccesstitle'       => 'tkchmt mzyan',
'mailmypassword'          => 'sifd yi awal ihdan yadni',
'loginlanguagelabel'      => 'tutlayt: $1',

# JavaScript password checks
'password-strength-bad'        => 'ortslih',
'password-strength-mediocre'   => 'trmi',
'password-strength-acceptable' => 'mqbola',
'password-strength-good'       => 'tfolki',
'password-retype'              => 'Зawd ara awal iḥdan:',
'password-retype-mismatch'     => 'iwaliwn hdanin ordochkin',

# Password reset dialog
'resetpass'               => 'bdl awal ihdan',
'oldpassword'             => 'awal ihdan aqbor',
'newpassword'             => 'awal ihdan amayno:',
'retypenew'               => 'Зawd ara awal iḥdan:',
'resetpass-submit-cancel' => 'ḥiyyd',

# Edit page toolbar
'bold_sample'     => 'ⴰⵟⵕⵉⵚ ⵉⴹⵏⵉⵏ',
'bold_tip'        => 'Aţŗş aťťuz',
'italic_sample'   => 'Aţŗiş iknan',
'italic_tip'      => 'Aţŗiş italik',
'link_sample'     => 'Azwl n uzday',
'link_tip'        => 'Azday uwgens',
'extlink_sample'  => 'http://www.example.com azwl n uzday',
'extlink_tip'     => 'Azday n berra (isktayn http://prefix)',
'headline_sample' => 'Aţŗiş n uswer amuqran',
'headline_tip'    => 'ⵜⴰⵏⵙⴰ ⵙ ⵓⵙⵡⵉⵔ ⵡⵉⵙⵙⵉⵏ',
'math_sample'     => 'ⵙⴽⵛⵎ ⵜⴰⵍⵖⴰ ⵖⵉⴷ (skcm talɣat ɣid)',
'math_tip'        => 'ⵜⴰⵍⵖⴰⵜ ⵜⵓⵙⵏⴰⴽⵜ (talɣat tusnakt)',
'nowiki_sample'   => 'Kcm s uţŗiş li ur igddan ɣid',
'nowiki_tip'      => 'Zri Taseddast nwiki',
'image_tip'       => 'ⴰⵙⴷⴰⵡ ⵏ ⵉⵍⵍⵏ (asdaw n illan)',
'media_tip'       => 'ⴰⵣⴷⴰⵢ ⵏ ⵓⵙⴷⴰⵡ',
'sig_tip'         => 'ⴰⴽⵔⵔⴰⵊ ⵏⴽ ⵖⵉⴷ ⵙ ⵓⵙⴰⴽⵓⵜ ⴷ ⴰⴽⵓⴷ',
'hr_tip'          => 'ⵉⵣⵔⵉⵔⵉ ⵉⵖⵣⵉⴼⵏ (izriri iɣzzifn)',

# Edit pages
'summary'                          => 'Tadusi',
'subject'                          => 'Subject/tansa',
'minoredit'                        => 'Imbddel ad fssusn',
'watchthis'                        => 'Sagg tasna yad',
'savearticle'                      => 'Ḥbu tasna',
'preview'                          => '↓ Ammal',
'showpreview'                      => 'Iẓṛi amzwaru',
'showlivepreview'                  => 'ard direct',
'showdiff'                         => 'Mel imbddeln',
'anoneditwarning'                  => "Balak ''' ur tkcmt ''' rad ibayn IP nk ɣ umzrut n tasna yad, ur sul  iḥba tamagit nk",
'summary-preview'                  => 'lmoayan n lmolkhass:',
'blockedtitle'                     => 'lmostkhdim ad itbloka',
'blockednoreason'                  => 'ta yan sabab oritfki',
'loginreqtitle'                    => '↓ Labd ad tkclt zwar',
'loginreqlink'                     => '↓ Kcm ɣid',
'accmailtitle'                     => 'awal ihdan hatin yochayakn',
'newarticle'                       => '↓ (Amaynu)',
'newarticletext'                   => "↓ Tfrt yan uzday s yat tasna lli ur ta jju illan [{{fullurl:Special:Log|type=delete&page={{FULLPAGENAMEE}}}} ttuykkas].
Iɣ rast daɣ tskrt skcm atṛiṣ nk ɣ tanaka  yad (Tẓḍaṛt an taggt γi [[{{MediaWiki:Helppage}}|tasna u usaws]] iɣ trit inɣmisn yaḍn).
Ivd tlkmt {{GENDER:||e|(e)}} ɣis bla trit, klikki f tajrrayt n '''urrir''' n iminig nk (navigateur).",
'noarticletext'                    => 'ɣilad ur illa walu may ityuran  f tasnatad ad, tzdart at [[Special:Search/{{PAGENAME}}|search for this page title]] in other pages,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} search the related logs],
ulla cabba  [{{fullurl:{{FULLPAGENAME}}|action=edit}} edit this page]</span>.',
'noarticletext-nopermission'       => 'Ur illa may itt yuran ɣ tasna tad.
Ẓr [[Special:Search/{{PAGENAME}}|search for this page title]] ɣ tisnatin yaḍnin,
ulla <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}}search the related logs]</span>.',
'updated'                          => '(mohdata)',
'note'                             => "'''molahada:'''",
'previewnote'                      => "↓ '''Ad ur ttut aṭṛiṣ ad iga ɣir amzwaru urta illa ɣ ifalan !'''",
'editing'                          => 'taẓṛgt $1',
'editingsection'                   => '↓ Ẓrig $1 (tagzumt)',
'yourtext'                         => 'nss nek',
'storedversion'                    => 'noskha ityawsjaln',
'yourdiff'                         => 'lforoq',
'copyrightwarning'                 => "ikhssak atst izd kolchi tikkin noun ɣ {{SITENAME}} llan ɣdo $2 (zr $1 iɣ trit ztsnt uggar).
iɣ ortrit ayg ɣayli torit ḥor artisbadal wnna ka-iran, attid ortgt ɣid.<br />
ikhssak ola kiyi ador tnqilt ɣtamani yadni.
'''ador tgat ɣid ɣayli origan ḥor iɣzark orilli lidn nbab-ns!'''",
'templatesused'                    => '↓ {{PLURAL:$1|Tamuḍimt lli nsxdm|Timuḍimin}} ɣ tasna yad:',
'templatesusedpreview'             => '↓ {{PLURAL:$1|Tamuḍimt llis nskar |Timuḍam lli sa nskar }} ɣ iẓriyad amzwaru :',
'template-protected'               => 'Agdal',
'template-semiprotected'           => 'Azin-ugdal',
'hiddencategories'                 => '↓ {{PLURAL:$1|Taggayt iḥban|Taggayin ḥbanin}} lli ɣtlla tasba yad :',
'permissionserrorstext-withaction' => '↓ Urak ittuyskar  {{IGGUT:||e|(e)}} s $2, bac {{PLURAL:$1|s wacku yad|iwackutn ad}} :',
'log-fulllog'                      => 'sbaynd sijil ikmln',

# History pages
'viewpagelogs'           => '↓ Ẓr timhlin lli ittuskarn ɣ tasna yad',
'currentrev-asof'        => 'Amseggar amǧuru  n $1',
'revisionasof'           => 'Askttay yaḍn f $1',
'previousrevision'       => 'Iẓṛi daɣ aqbur',
'nextrevision'           => '↓ Amẓr amaynu',
'currentrevisionlink'    => 'Amcggr amggaṛu',
'cur'                    => 'Ɣilad',
'next'                   => 'Imal (wad yuckan)',
'last'                   => 'Amzwaru',
'page_first'             => 'walli izwarn',
'page_last'              => 'walli igran',
'histlegend'             => '↓ Isisfiw amzyan : ({{MediaWiki:Cur}}) = urtga zund  lqm (la version) n ɣila, ({{MediaWiki:Last}}) = urd tmcacka d lqm lli izrin, <b>m</b> = ambddl idrusn',
'history-fieldset-title' => '↓ Sigel ɣ umzruy',
'histfirst'              => 'Amzwaru',
'histlast'               => 'Amggaru',
'historyempty'           => '(orgiss walo)',

# Revision feed
'history-feed-item-nocomment' => '$1 ar $2',

# Revision deletion
'rev-delundel'               => 'Mel/ĥbu',
'rev-showdeleted'            => 'Mel',
'revdelete-show-file-submit' => 'yah',
'revdelete-radio-set'        => 'yah',
'revdelete-radio-unset'      => 'oho',
'revdel-restore'             => 'sbadl tannayt',
'revdelete-content'          => 'Mayllan',
'revdelete-uname'            => 'Assaɣ nu-msxdan',
'revdelete-hid'              => 'ador tsbaynt $1',
'revdelete-unhid'            => 'sbaynd $1',

# Revision move
'revmove-submit'         => 'smati lmorajat s tawriqt ad',
'revmove-reasonfield'    => 'Maɣ:',
'revmove-titlefield'     => 'sfha lhadaf:',
'revmove-badparam-title' => 'reglage orfolki',

# History merging
'mergehistory-box'  => 'dmj morajaat ntwriqin ad',
'mergehistory-from' => 'sfha lmasdar',
'mergehistory-into' => 'sfha lhadaf',
'mergehistory-list' => 'tarikh n damj',

# Merge log
'revertmerge' => 'ḥiyyd tazdayt',

# Diffs
'history-title'           => 'Asakud n umcggr n « $1 »',
'difference'              => 'laḥna gr tamzwarut d tamǧarut',
'lineno'                  => 'Izriri $1:',
'compareselectedversions' => 'Snahya gr ilqmn lli tuystaynin',
'editundo'                => 'Urri',
'diff-multi'              => '({{PLURAL:$1|Gr yan usurri|$1 gr isuritn}} ura tuyfsar)',

# Search results
'searchresults'                    => 'Ma akkan icnubcn',
'searchresults-title'              => 'Mad akkan icnubcn f "$1"',
'searchresulttext'                 => 'Inɣmisn yaḍnin f {{SITENAME}},  ẓr  [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Ar tsiggilt f \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|tisniwin li kullu ttiswirnin s "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|tisniwin li kullu ttiswirnin s "$1"]])',
'searchsubtitleinvalid'            => "Tsiggelt f '''$1'''",
'toomanymatches'                   => 'Illa bzzaf maygan zund maya. sbadl taguri yad skra yaḍn',
'titlematches'                     => 'Assaɣ n tasna iga zund',
'notitlematches'                   => 'Ur ityuffa kra ntansa zund ɣwad',
'textmatches'                      => 'Aṭṛiṣ n tasna iga zund',
'notextmatches'                    => 'Ur ittyufa kra nu uṭṛiṣ igan zund ɣwad',
'prevn'                            => 'Tamzwarut {{PLURAL:$1|$1}}',
'nextn'                            => 'Tallid yuckan {{PLURAL:$1|$1}}',
'viewprevnext'                     => 'Mel ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Istayn ucnubc',
'searchhelp-url'                   => 'Help:Mayllan',
'searchprofile-articles'           => 'Mayllan ɣ tasna',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'kullu',
'searchprofile-advanced'           => 'motaqqadim',
'searchprofile-articles-tooltip'   => 'qlb gh $1',
'searchprofile-project-tooltip'    => 'qlb gh $1',
'searchprofile-images-tooltip'     => 'qlb gh tswira',
'searchprofile-everything-tooltip' => 'Cabba ɣ kullu may ityran ɣid (d ḥtta ɣ tisna nu umsgdal)',
'searchprofile-advanced-tooltip'   => 'Cabba ɣ igmmaḍn li tuyzlaynin',
'search-result-size'               => '$1 ({{PLURAL:$2|1 taguri|$2 tiguriwin}})',
'search-result-score'              => 'Tazdayt: $1%',
'search-redirect'                  => '(Asmmati $1)',
'search-section'                   => 'Ayyaw $1',
'search-suggest'                   => 'Is trit att nnit: $1',
'search-interwiki-caption'         => 'Tiwuriwin taytmatin',
'search-interwiki-default'         => '$1 imyakkatn',
'search-interwiki-more'            => '(Uggar)',
'search-mwsuggest-enabled'         => 'D mara ittuyskar',
'search-mwsuggest-disabled'        => 'Ur illa marayttuskar',
'search-relatedarticle'            => 'Tzdi',
'mwsuggest-disable'                => 'Asbid AJAX n maryttuynnan ayttuyskar',
'searcheverything-enable'          => 'Cabba ɣ graygat agmmaḍ',
'searchrelated'                    => 'Tuyzday',
'searchall'                        => 'Kullu',
'showingresults'                   => "Ẓr azddar  {{PLURAL:$1|'''1''' May tuykfan|'''$1''' Mad kfan}} Bdu s #'''$2'''",
'showingresultsnum'                => "Ẓr azddar (ifsr ɣ uzddar) {{PLURAL:$3|'''1''' may kfa|'''$3''' mad kfan}} Bdu s #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|May kfa '''$1''' ar '''$3'''|Mad kfan '''$1 - $2''' ar '''$3'''}} i '''$4'''",
'nonefound'                        => "'''Arra''': Icnubbu ar tilin ɣir tiɣulin tuyzlaynin. Iɣ trit at cabbat ɣ kullu may tyuran d ḥtta tisnatin nu umsgdal s ''all:'', bdu acnubc  nek s kullu ma ɣɣid imun, ulla s assaɣ n tɣult li trit.",
'search-nonefound'                 => 'Ur ittuykfa walu maygan zund ɣayli trit',
'powersearch'                      => 'Amsigl imzwarn',
'powersearch-legend'               => 'Amsigl imzwarn',
'powersearch-ns'                   => 'Icnubbucn ɣ tɣulin',
'powersearch-redir'                => 'Afsr n ismmatayn (Tifilit n ismmatayn)',
'powersearch-field'                => 'Acnubc ɣ',
'powersearch-togglelabel'          => 'Sti',
'powersearch-toggleall'            => 'Kullu',
'powersearch-togglenone'           => 'Walu',
'search-external'                  => 'Acnubc b brra',
'searchdisabled'                   => '{{SITENAME}} Acnubc ibid.
Tzdar at cabbat ɣilad ɣ Google.
Izdar ad urtili ɣ isbidn n mayllan ɣ {{SITENAME}} .',

# Quickbar
'qbsettings'               => 'Tafeggagt izrbn',
'qbsettings-none'          => 'Ur iḥudda',
'qbsettings-fixedleft'     => 'Aẓẓugz azlmaḍ',
'qbsettings-fixedright'    => 'Azzugz afasi',
'qbsettings-floatingleft'  => 'Yaywul su uzlmad',
'qbsettings-floatingright' => 'Yaywul su ufasi',

# Preferences page
'preferences'               => 'Timssusmin',
'mypreferences'             => 'Timssusmin',
'prefs-edits'               => 'Uṭṭun n n imbddeln',
'prefsnologin'              => 'Ur tmlit mat git',
'changepassword'            => 'bdl awal ihdan',
'prefs-skin'                => 'odm',
'skin-preview'              => 'Ammal',
'prefs-math'                => 'mat',
'datedefault'               => 'Timssusmin',
'prefs-datetime'            => 'waqt d tarikh',
'prefs-personal'            => 'milf n umsxdam',
'prefs-rc'                  => 'Imbddeln imggura',
'prefs-watchlist'           => 'lista n tabiaa',
'prefs-watchlist-days'      => 'osfan liratzrt gh lista n umdfur',
'prefs-watchlist-days-max'  => 'lmaximum 7 osfan',
'prefs-watchlist-token'     => 'tasarut n list n omdfor',
'prefs-misc'                => 'motafarriqat',
'prefs-resetpass'           => 'bdl awal ihdan',
'prefs-email'               => 'lkhiyarat n Email',
'prefs-rendering'           => 'adm',
'saveprefs'                 => 'sjjl',
'resetprefs'                => 'hiyd tghyirat li orsjilnin',
'restoreprefs'              => 'sglbd kollo regalega',
'prefs-editing'             => 'tahrir',
'prefs-edit-boxsize'        => 'hajm nafida n thrir',
'rows'                      => 'sfof:',
'columns'                   => 'aamida:',
'searchresultshead'         => 'Cabba',
'resultsperpage'            => 'adad nataij gh sfha:',
'contextlines'              => 'stour gh natija',
'contextchars'              => 'lhrof gh natija',
'stub-threshold'            => 'wasla n  <a href="#" class="stub">do amzdoy</a> itforma (bytes):',
'stub-threshold-disabled'   => 'moattal',
'recentchangesdays'         => 'adad liyam lmroda gh ahdat tghyirat',
'localtime'                 => '↓Tizi n ugmaḍ ad:',
'servertime'                => '↓ Asaru n Tizi',
'guesstimezone'             => 'skchm twqit gh lmotasaffih',
'timezoneregion-africa'     => 'Africa',
'timezoneregion-america'    => 'America',
'timezoneregion-antarctica' => 'Antarctica',
'timezoneregion-arctic'     => 'Arctic',
'timezoneregion-asia'       => 'Asia',
'timezoneregion-atlantic'   => 'Atlantic Ocean',
'timezoneregion-australia'  => 'Australia',
'timezoneregion-europe'     => 'Europa',
'timezoneregion-indian'     => 'Indian Ocean',
'timezoneregion-pacific'    => 'Pacific Ocean',
'allowemail'                => 'artamz limail dar isxdamn yadni',
'prefs-searchoptions'       => 'Istayn ucnubc',
'prefs-namespaces'          => 'Ismawn n tɣula',
'defaultns'                 => 'ghd sigl gh nitaqat ad',
'default'                   => 'iftiradi',
'prefs-files'               => 'Asdaw',
'prefs-custom-css'          => 'khss CSS',
'prefs-custom-js'           => 'khss JavaScipt',
'username'                  => 'smiyt o-msxdam:',
'uid'                       => 'raqm omskhdam:',
'prefs-registration'        => 'waqt n tsjil:',
'yourrealname'              => 'smiyt nk lmqol',
'yourlanguage'              => 'tutlayt:',
'yournick'                  => 'sinyator',
'yourgender'                => 'ljins',
'gender-unknown'            => 'ghayr mohdad',
'gender-male'               => 'dkr',
'gender-female'             => 'lont',
'email'                     => 'email',
'prefs-signature'           => 'sinyator',
'prefs-dateformat'          => 'sight n loqt',

# Groups
'group-sysop' => 'Anedbalen n unagraw',

'grouppage-sysop' => '{{ns:project}}: Inedbalen',

# Rights
'right-revisionmove' => '↓ Smmati imẓran daɣ',

# User rights log
'rightslog' => '↓ Anɣmas n imbddlnn izrfan n umsqdac',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => '↓ Ẓrig tasna yad.',

# Recent changes
'nchanges'                          => '↓ $1 imbddln {{PLURAL:$1||s}}',
'recentchanges'                     => 'Imbddeln imggura',
'recentchanges-legend'              => 'Tixtiɣitin (options) n imbddl imaynutn',
'recentchanges-feed-description'    => '↓ Tfr imbddln imggura n wiki yad ɣ usuddm',
'rcnote'                            => '↓ Γid {{PLURAL:$1|ambddl amggaru lli ittuysgarn| $1 Imbddln imggura lli ittuyskarn}} ɣ {{PLURAL:$2|was amggaru| <b>$2</b> Ussan imggura}} ar $5 n $4.',
'rclistfrom'                        => '↓ Mel imbdeltn imaynutn z $1',
'rcshowhideminor'                   => '$1 iẓṛign fssusnin',
'rcshowhidebots'                    => '$1 butn',
'rcshowhideliu'                     => '$1 midn li ttuyqqiyadnin',
'rcshowhideanons'                   => '$1 midn ur ttuyssan nin',
'rcshowhidemine'                    => '$1 iẓṛign inu',
'rclinks'                           => '↓ Ml id  $1 n imbddltn immgura li ittuyskarn n id $2 ussan ad gguranin<br />$3.',
'diff'                              => 'Gar',
'hist'                              => '↓ Amzruy',
'hide'                              => 'Ḥbu',
'show'                              => 'Mel',
'minoreditletter'                   => '↓ m',
'newpageletter'                     => 'A',
'boteditletter'                     => 'q',
'unpatrolledletter'                 => '!',
'sectionlink'                       => '→',
'number_of_watching_users_pageview' => '[$1 iżŗi {{PLURAL:$1|amsqdac|imsqdacn}}]',
'rc_categories_any'                 => 'wanna',
'rc-change-size'                    => '$1',
'newsectionsummary'                 => '/* $1 */ ayaw amaynu',
'rc-enhanced-expand'                => 'Ml ifruriyn (ira JavaScript)',
'rc-enhanced-hide'                  => 'Ĥbu ifruriyn',

# Recent changes linked
'recentchangeslinked'          => 'Imbddel zun ɣwid',
'recentchangeslinked-feed'     => 'Imbddeln zund ɣwid',
'recentchangeslinked-toolbox'  => 'Imbddeln zund ɣwid',
'recentchangeslinked-title'    => 'Imbddeln li izdin "$1"',
'recentchangeslinked-backlink' => '← $1',
'recentchangeslinked-noresult' => 'Ur illi may budeln ɣ tisniwin li dar izdayn s ɣid',
'recentchangeslinked-summary'  => 'Ɣid umuɣ iymbddeln li ittyskarnin tigira yad ɣ tisniwin li ittuyzdayn d kra n tasna (ulla i igmamn n kra taggayt ittuyzlayn). Tisniwin  ɣ [[Special:Watchlist|Umuɣ n tisniwin li ttsaggat]].',
'recentchangeslinked-page'     => 'Assaɣ n tasna',
'recentchangeslinked-to'       => 'Afficher les changements vers les pages liées au lieu de la page donnée
Mel imbddeln z tisniwin li ittuyzdayni bla tasna li trit.',

# Upload
'upload'                   => 'Srbu asddaw',
'uploadbtn'                => 'Srbu asddaw',
'reuploaddesc'             => 'Sbidd asrbu d turrit',
'upload-tryagain'          => '↓ Ṣafḍ Anglam n ufaylu li ibudln',
'uploadnologin'            => 'Ur tmlit mat git',
'uploadnologintext'        => 'Mel zwar mat git [[Special:UserLogin|Mel mat git]] iɣ trit ad tsrbut isddawn.',
'upload_directory_missing' => '↓ Akaram n w-affay ($1) ur ittyufa d urt iskr uqadac web (serveur)',
'uploadlogpage'            => '↓ Anɣmis n isrbuṭn',
'uploadedimage'            => '↓ Issrba "[[$1]]"',

# File description page
'filehist'                  => 'Amzry n usdaw',
'filehist-help'             => 'Adr i asakud/tizi bac attżrt manik as izwar usddaw ɣ tizi yad',
'filehist-current'          => 'Ɣilad',
'filehist-datetime'         => 'Asakud/Tizi',
'filehist-thumb'            => 'Awlaf imżżin',
'filehist-thumbtext'        => 'Mżżi n lqim ɣ tizi $1',
'filehist-user'             => 'Amsqdac',
'filehist-dimensions'       => 'Dimensions',
'filehist-comment'          => 'Aɣfawal',
'imagelinks'                => 'Izdayn n usdaw',
'linkstoimage'              => 'Tasna yad {{PLURAL:$1|izdayn n tasna|$1 azday n tasniwin}} s usdaw:',
'sharedupload'              => 'Asdawad z $1 tẓḍart at tsxdmt gr iswirn yaḍnin',
'uploadnewversion-linktext' => 'Srbud tunɣilt tamaynut n usdaw ad',

# Statistics
'statistics' => '↓ Tisnaddanin',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byt|byt}}',
'ncategories'             => '$1 {{PLURAL:$1|taggayt|taggayin}}',
'nlinks'                  => '$1 {{PLURAL:$1|azday|izdayn}}',
'nmembers'                => '$1 {{PLURAL:$1|agmam|igmamn}}',
'nrevisions'              => '$1 {{PLURAL:$1|asgadda|isgaddatn}}',
'nviews'                  => '$1 {{PLURAL:$1|assag|issagn}}',
'specialpage-empty'       => 'Ur illa mayttukfan i asaggu yad',
'lonelypages'             => 'Tasnatiwin tigigilin',
'lonelypagestext'         => 'Tisnawinad ur ur tuyzdaynt z ulla lant ɣ tisniwin yaḍnin ɣ {{SITENAME}}.',
'uncategorizedpages'      => 'Tisnawinad ur llant ɣ graygan taggayt',
'uncategorizedcategories' => 'Taggayin ur ittuyzlayn ɣ kraygan taggayt',
'prefixindex'             => '↓ Tisniwin lli izwarn s ...',
'newpages'                => '↓ Tisniwin timaynutin',
'move'                    => 'Smmatti',
'movethispage'            => 'Smmatti tasna yad',
'unusedcategoriestext'    => 'Taggayin ad llant waxxa gis nt ur tlli kra n tasna wala kra n taggayin yaḍnin',
'notargettitle'           => 'F walu',
'nopagetext'              => 'Tasna li trit ur tlli',
'pager-newer-n'           => '{{PLURAL:$1|amaynu 1|amaynu $1}}',
'pager-older-n'           => '{{PLURAL:$1|aqbur 1|aqbur $1}}',
'suppress'                => 'Iẓriyattuyn',

# Book sources
'booksources'               => 'Iɣbula n udlis',
'booksources-search-legend' => 'Acnubc s iɣbula n idlisn',
'booksources-isbn'          => 'ISBN:',
'booksources-go'            => 'Ftu',

# Special:Log
'specialloguserlabel'  => 'Amsqdac',
'speciallogtitlelabel' => 'Azwl',
'log'                  => 'Immussutn ittyuran',
'all-logs-page'        => 'Immussutn ittyuran immurzmn i kullu..',
'log-title-wildcard'   => 'Cabba s iswln li ttizwirnin s uṭṛiṣ ad',

# Special:AllPages
'allpages'          => 'Tisniwin kullu tnt',
'alphaindexline'    => '$1 ar $2',
'nextpage'          => 'Tasna li rad yack ($1)',
'prevpage'          => 'Tasna li izrin $1',
'allpagesfrom'      => 'Mel tisniwin li ittizwirn z',
'allpagesto'        => 'Mel tasniwin li ttgurunin s',
'allarticles'       => 'Tasniwin kullu tnt',
'allinnamespace'    => 'Tasniwin kullu tnt ɣ ($1 assaɣadɣar)',
'allnotinnamespace' => 'Tasniwin kullu tnt ur llant ɣ ($1 assaɣadɣar)',
'allpagesprev'      => 'Amzwaru (walli izwarn)',
'allpagesnext'      => 'Imal (wad yuckan)',
'allpagessubmit'    => 'Ftu',
'allpagesprefix'    => 'Mel tasniwin li ttizwirnin s',

# Special:Categories
'categories' => 'imggrad',

# Special:LinkSearch
'linksearch' => 'Izdayn n brra',

# Special:Log/newusers
'newuserlogpage'          => '↓ Aɣmis n willi mmurzmn imiḍan amsqdac',
'newuserlog-create-entry' => '↓ Amḍan amaynu n umsqdac',

# Special:ListGroupRights
'listgrouprights-members' => '↓ Umuɣ n  midn',

# E-mail user
'emailuser' => '↓ Azn tabrat umsqdac ad',

# Watchlist
'watchlist'         => '↓ Umuɣ n imtfrn',
'mywatchlist'       => 'Umuɣ inu lli tsaggaɣ',
'addedwatch'        => '↓ Zayd tin i umuɣ n umtfr',
'addedwatchtext'    => '↓ tasna « [[:$1]] » tllan ɣ [[Special:Watchlist|umuɣ n umtfr]]. Imbdln lli dyuckan d tasna lli dis iṭṭuzn rad asn nskr agmmaḍ nsn. Tasna radd ttbayan s "uḍnay" ɣ [[Special:RecentChanges|Umuɣ n imbddeln imaynutn]]',
'removedwatch'      => '↓ Kkist s umuɣ n umtfr',
'removedwatchtext'  => '↓ Tasna "[[:$1]]" ḥra ttuykkas z [[Special:Watchlist|your watchlist]].',
'watch'             => 'zaydtin i tochwafin-niw',
'watchthispage'     => '↓ Ṭfr tasna yad',
'unwatch'           => 'Ur rast tsaggaɣ',
'watchlist-details' => '↓ Umuɣ nk n imttfura ar  ittawi $1 tasna {{PLURAL:$1||s}}, bla dis tsmunt tisniwin n imdiwiln.',
'wlshowlast'        => '↓ Ml ikudan imggura $1 , ussan imggura $2 niɣd $3',
'watchlist-options' => 'Tixtiṛiyin n umuɣ lli ntfar',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ar itt sagga',
'unwatching' => 'Ur at sul ntsagga',

# Delete
'deletepage'            => 'Amḥiyd n tasna',
'confirmdeletetext'     => 'Ḥan tbidt f attkkist tasna yad kullu d kullu amzruy nes.
illa fllak ad ni tẓrt is trit ast tkkist d is tssnt marad igguṛu iɣt tkkist d is iffaɣ mayad i [[{{MediaWiki:Policy-url}}|tasrtit]].',
'actioncomplete'        => 'tigawt tummidt',
'deletedtext'           => '"<nowiki>$1</nowiki>"  ttuykkas.
Ẓṛ $2 inɣmas imggura n ma ittuykkasn',
'deletedarticle'        => 'Kkiss "[[$1]]"',
'dellogpage'            => 'Qqiyd akkas ad',
'deletecomment'         => '! Maɣ:',
'deleteotherreason'     => 'Wayyaḍ/ maf ittuykkas yaḍn',
'deletereasonotherlist' => 'Maf ittuykkas yaḍn',

# Rollback
'rollbacklink' => 'Rard',

# Protect
'protectlogpage'              => '↓ Iɣmisn n ugdal',
'protectedarticle'            => '↓ ay gdl  "[[$1]]"',
'modifiedarticleprotection'   => '↓ isbudl taskfalt n ugdal n « [[$1]] »',
'protectcomment'              => 'Maɣ:',
'protectexpiry'               => '↓ Tizi nu uzri n umzruy:',
'protect_expiry_invalid'      => '↓ Tizi n uzri n umzruy urtti tga.',
'protect_expiry_old'          => '↓ Tizi n uzri n umzruy n zrit.',
'protect-text'                => "↓ Tzḍaṛt ad tẓṛt niɣ tbudlt taskflt n ugdal (protection) n tasna '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "↓ Ur tẓdart wala ittuyskarak ad tbadlt tiskfal n ugdal n tisniwin.
Ha riglaj n ɣila lli f tlla tasna '''$1''' :",
'protect-cascadeon'           => '↓ Tasna yad tgddl (protégé) t llan ɣ {{PLURAL:$1|Tasna llid yuckan, talli igddln| Tillid yuckan, lli igddln}} s tamatart ad « Agdl s imuzzar ». Tzḍart ad tsbadlt iswirn n ugdlns bla irza mayad aǧdl s imuzzar',
'protect-default'             => 'Immurzm i kullu imsxdamn',
'protect-fallback'            => '↓ Tra "$1" ajja (permission)',
'protect-level-autoconfirmed' => '↓ Sbid tqqnt f imsqdacn imaynutn lli ur ittuyssanin',
'protect-level-sysop'         => '↓ Imɣarn ṣafi.',
'protect-summary-cascade'     => '↓ Agdal n imuzzar',
'protect-expiring'            => '↓ tzri $1 (UTC)',
'protect-cascade'             => '↓ gdlnt wala tisniwin llin illan ɣ tasna yad (Agdal s imuzzar)',
'protect-cantedit'            => '↓ Ur as tufit ad sbadlt tiskfal n ugdal n tasna yad acku urak ittuyskar',
'restriction-type'            => '↓ Turagt',
'restriction-level'           => '↓ Restriction level:',

# Undelete
'undeletelink'     => 'mel/rard',
'undeletedarticle' => '↓ Isurrid  "[[$1]]"',

# Namespace form on various pages
'namespace'      => 'Taɣult',
'invert'         => 'amglb n ustay',
'blanknamespace' => '(Amuqran)',

# Contributions
'contributions'       => 'Tiwuriwin n umsaws',
'contributions-title' => '↓ Umuɣ n tiwuriwin n umsqdac $1',
'mycontris'           => 'Tiwuriwin inu',
'contribsub2'         => '↓ I $1 ($2)',
'uctop'               => '↓ (tamgarut)',
'month'               => 'Z usggas (d urbur):',
'year'                => 'Z usggas (d urbur):',

'sp-contributions-newbies'             => '↓ Ad ur tmlt abla tiwuriwin n wiyyaḍ',
'sp-contributions-newbies-sub'         => '↓ Z imiḍan (comptes) imaynutn',
'sp-contributions-newbies-title'       => '↓ Tiwuriwin n umqdac z imḍan imaynutn',
'sp-contributions-blocklog'            => '↓ Tinɣmas n willi ttuyqqanin (blocage)',
'sp-contributions-deleted'             => '↓ Tiwuriwin lli ittuykkasnin',
'sp-contributions-logs'                => '↓ Iɣmisn',
'sp-contributions-talk'                => '↓ Sgdl (discuter)',
'sp-contributions-userrights'          => '↓ Sgiddi izrfan',
'sp-contributions-blocked-notice'      => '↓ Amsqdac ad ittuysbddad. Maf ittuysbddad illa ɣ uɣmmis n n willi n sbid. Mayad ɣ trit ad tsnt maɣ',
'sp-contributions-blocked-notice-anon' => '↓ Tansa yad IP ttuysbddad. Maf ittuysbddad illa ɣ uɣmmis n n willi n sbid. Mayad ɣ trit ad tsnt maɣ',
'sp-contributions-search'              => '↓ Cnubc f tiwuriwin',
'sp-contributions-username'            => '↓ Tansa IP niɣ assaɣ nu umsqdac:',
'sp-contributions-toponly'             => '↓ Ad urtmlt adla mat ittuyẓran tigira yad',
'sp-contributions-submit'              => '↓ Cabba (Sigl)',
'sp-contributions-explain'             => '↓',

# What links here
'whatlinkshere'            => 'May izdayn ɣid',
'whatlinkshere-title'      => 'Tisniwin li izdayn d "$1"',
'whatlinkshere-page'       => 'Tasna:',
'whatlinkshere-backlink'   => '← $1',
'linkshere'                => "Tasnawinad ar slkamnt i '''[[:$1]]''':",
'nolinkshere'              => "Ur llant tasniwin li izdin d '''[[:$1]]'''.",
'nolinkshere-ns'           => "Ur tlla kra n tasna izdin d  '''[[:$1]]''' ɣ tɣult l-ittuystayn.",
'isredirect'               => 'Tasna immutin',
'istemplate'               => 'Illa gis',
'isimage'                  => 'Azday awlaf',
'whatlinkshere-prev'       => '{{PLURAL:$1|amzwaru|amzwaru $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|wali d yuckan|wali d yuckan $1}}',
'whatlinkshere-links'      => '← izdayn',
'whatlinkshere-hideredirs' => '$1 Ismmattayn',
'whatlinkshere-hidetrans'  => '$1 mayllan gis',
'whatlinkshere-hidelinks'  => '$1 izdayn',
'whatlinkshere-hideimages' => '$1 izdayn awlaf',
'whatlinkshere-filters'    => 'Istayn',

# Block/unblock
'blockip'                  => '↓ Qn f umsqdac',
'ipboptions'               => '↓ 2 ikudn:2 hours,1 as:1 day,3 ussan:3 days,1 imalas:1 week,2 imalasn:2 weeks,1 ayur:1 month,3 irn:3 months,6 irn:6 months,1 asggas:1 year,tusut ur iswuttan:infinite',
'ipbotheroption'           => '↓ wayya',
'ipbhidename'              => '↓ ḥbu assaɣ n umsqdac ɣ imbdln d umuɣn',
'ipbwatchuser'             => '↓ Tfr tisniwin d imsgdaln n umqdac',
'ipballowusertalk'         => '↓ Yufa umsqdac ad ad isbddl tasna ns n umsgdal ɣ tizi lliɣas ttuyqqan tins',
'ipblocklist'              => '↓ Tansa IP d imsqdacn ttuẓnin',
'blocklink'                => 'Adur tajt',
'unblocklink'              => 'kkis agdal',
'change-blocklink'         => 'Sbadl agdal',
'contribslink'             => 'tikkin',
'blocklogpage'             => '↓ aɣmmis n may ittuyqqanin',
'blocklog-showlog'         => '↓ Amsqdac ikkattin ittuyqqan. anɣmis n willi ttuyqqanin  ɣid:',
'blocklog-showsuppresslog' => '↓ Amsqdac ikkattin ittuyqqan d iḥba. Anɣmis n willi ttuyqqanin  ɣid:',
'blocklogentry'            => '↓ tqn [[$1]] s tizi izrin n $2 $3',
'unblocklogentry'          => '↓ immurzm $1',
'block-log-flags-nocreate' => '↓ Ammurzm n umiḍan urak ittuyskar',

# Move page
'movepagetext'             => "↓ Swwur s tifrkkitad bac ad sbadlt uzwl tasna yad , s usmmattay n umzru ns s uzwl amaynu . Assaɣ Aqbur rad ig ɣil yan usmmattay n tasna s uzwl (titre) amynu . Tâḍart ad s tgt immattayn n ɣil f was fwas utumatik s dar uswl amaynu.  Iɣ tstit bac ad tskrt . han ad ur ttut ad tẓrt kullu  [[Special:DoubleRedirects|double redirection]] ou [[Special:BrokenRedirects|redirection cassée]]. Illa fllak ad ur ttut masd izdayn rad tmattayn s sin igmmaḍn ur igan yan.

Smmem masd tasna ur rad tmmatti iɣ tlla kra n yat yaḍn lli ilan asw zund nttat . Abla ɣ dars amzruy ɣ ur illa umay,  nɣd yan usmmattay ifssusn. 

''' Han !'''
Maya Iẓḍar ad iglb zzu uzddar ar aflla tasna yad lli bdda n nttagga. Illa fllak ad urtskr mara yigriẓ midn d kiyyin lli iswurn ɣ tasna yad. issin mara tskr urta titskrt..",
'movepagetalktext'         => "↓ Tasna n umsgdal (imdiwiln) lli izdin d ɣta iɣ tlla, rad as ibadl w-assaɣ utumatik  '''abla iɣ :'''
* tsmmuttim tasna s yan ugmmaḍ wassaɣ, niɣd
* tasna n umsgdal( imdiwiln) tlla s wassaɣ ad amaynu, niɣd
* iɣ tkrjm tasatmt ad n uzddar

Γ Tiklayad illa flla tun ad tsbadlm assaɣ niɣt tsmun mayad s ufus ɣ yat, iɣ tram",
'movearticle'              => 'Smmatti tasna niɣ as tsbudlt assaɣ',
'newtitle'                 => '↓ dar w-assaɣ amaynu:',
'move-watch'               => '↓ Tfr tisniwin timaynutin d timẓlay',
'movepagebtn'              => '↓ Smmatti tasna niɣ as tsbudlt assaɣ',
'pagemovedsub'             => '↓ tmmutti bla tamukrist',
'movepage-moved'           => '↓ \'\'\'"$1" tmmutti s "$2"\'\'\'',
'articleexists'            => '↓ Tlla yad tasna illan assaɣ zund ɣwa niɣd assaɣ llid tiwid urt iga. Sti assaɣ yaḍn tarmt.',
'talkexists'               => '↓ Tasna tmmutti mzyan, mac tasna n umsgdal (imdiwiln) ur tmmutti acku tlla f wassaɣ ad amaynu.Illa fllak aggisnt tskrt yat s ufus nk.',
'movedto'                  => '↓ Tmmuti s',
'movetalk'                 => '↓ Sbadl assaɣ tasna n imdiwiln lli izdin d ɣi.',
'1movedto2'                => '↓ Ad tmmatti z [[$1]] s [[$2]]',
'1movedto2_redir'          => '↓ ad tmmatti [[$1]] s [[$2]] trẓ asurriti ns',
'move-redirect-suppressed' => '↓ asuritti n ittuykkasn',
'movelogpage'              => '↓ Iɣmisn n ismmattrayn',
'movelogpagetext'          => '↓ Γid umuɣ n tisniwin lli sbadlnin assaɣ d tilli mmuttini.',
'movesubpage'              => '↓ Ddu-tasna {{PLURAL:$1||s}}',
'movereason'               => 'Maɣ:',
'revertmove'               => 'Rard',

# Export
'export' => 'assufɣ n tasniwin',

# Thumbnails
'thumbnail-more' => 'Simɣur',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Tasna n umsqdac',
'tooltip-pt-mytalk'               => 'Tasnat umsgdal inu',
'tooltip-pt-anontalk'             => 'Amsgdal f imbddeln n tansa n IP yad',
'tooltip-pt-preferences'          => 'Timssusmin inu',
'tooltip-pt-watchlist'            => 'Tifilit n tisnatin li itsaggan imdddeln li gisnt ittyskarn..',
'tooltip-pt-mycontris'            => 'Tabdart n ismmadn inu',
'tooltip-pt-login'                => 'Yufak at qiyt akcum nek, mach ur fllak ibziz .',
'tooltip-pt-anonlogin'            => 'Ifulki at tqiyt akcum nek, mac ur fllak iga bziz',
'tooltip-pt-logout'               => 'Affuɣ',
'tooltip-ca-talk'                 => 'Assays f mayllan ɣ tasnat ad',
'tooltip-ca-edit'                 => 'Tzḍaṛt  at tsbadelt tasna yad. Ifulki iɣt zwar turmt ɣ tasna w-arm',
'tooltip-ca-addsection'           => 'Bdu ayyaw amaynu.',
'tooltip-ca-viewsource'           => 'Tasnatad tuyḥba. mac dẓdart at tẓrt aɣbalu nes.',
'tooltip-ca-history'              => 'Tunɣilt tamzwarut n tasna yad',
'tooltip-ca-protect'              => 'Ḥbu tasna yad',
'tooltip-ca-unprotect'            => 'Kkis aḥbu n tasna yad',
'tooltip-ca-delete'               => 'Kkis tasna yad',
'tooltip-ca-undelete'             => 'Rard imbddeln imzwura li ittyskarnin ɣ tasna yad',
'tooltip-ca-move'                 => 'Smmati tasna yad',
'tooltip-ca-watch'                => 'Smd tasna yad itilli tsaggat.',
'tooltip-ca-unwatch'              => 'Kkis tasna yad z ɣ tilli tsaggat',
'tooltip-search'                  => 'siggl ɣ {{SITENAME}}',
'tooltip-search-go'               => 'Ftu s tasna s w-assaɣ znd ɣ-wad  iɣ tlla',
'tooltip-search-fulltext'         => 'Cnubc aṭṛiṣad ɣ tisnatin',
'tooltip-p-logo'                  => 'Tasnat tamuqrant',
'tooltip-n-mainpage'              => 'Kkid tasna tamzwarut',
'tooltip-n-mainpage-description'  => 'Kid tasna tamuqrant',
'tooltip-n-portal'                => "f' usenfar, matzdart atitskrt, maniɣrattaft ɣayli trit",
'tooltip-n-currentevents'         => 'Tiɣri izrbn i kullu maɣid immusn',
'tooltip-n-recentchanges'         => 'Umuɣ n imbddlen imaynuten ɣ l-wiki',
'tooltip-n-randompage'            => 'Srbu yat tasna ɣik nna ka tga',
'tooltip-n-help'                  => 'Adɣar n w-aws',
'tooltip-t-whatlinkshere'         => 'Umuɣ n kullu tisnatin n Wiki lid ilkkmn ɣid',
'tooltip-t-recentchangeslinked'   => 'Imbddln imaynutn n tisnatin li ittylkamn s tasna yad',
'tooltip-feed-rss'                => 'Usuddm (Flux) n tasna yad',
'tooltip-feed-atom'               => 'Usuddm Atum n tasna yad',
'tooltip-t-contributions'         => 'Ẓr umuɣ n tiwuriwin n umsqdac ad',
'tooltip-t-emailuser'             => 'Ṣafd tabrat umsqdac ad',
'tooltip-t-upload'                => 'sɣlid ifaylutn',
'tooltip-t-specialpages'          => 'Umuɣ n tisniwin timẓlayin',
'tooltip-t-print'                 => 'Lqim uziggz n tasna yad',
'tooltip-t-permalink'             => 'Azday bdda i lqim n tasna yad',
'tooltip-ca-nstab-main'           => 'Ẓr mayllan ɣ tasna',
'tooltip-ca-nstab-user'           => 'Ẓr tasna n useqdac',
'tooltip-ca-nstab-media'          => 'Iẓri n tasna n midya',
'tooltip-ca-nstab-special'        => 'Tasna yad tuyẓlay, uras tufit ast ẓregt(tbddelt) nttat nit',
'tooltip-ca-nstab-project'        => 'Żr tasna n twwuri',
'tooltip-ca-nstab-image'          => 'Źr tasna n usdaw',
'tooltip-ca-nstab-mediawiki'      => 'Żr tabrat nu-nagraw.',
'tooltip-ca-nstab-template'       => 'Żr tamudemt',
'tooltip-ca-nstab-help'           => 'Źr tasna nu-saws',
'tooltip-ca-nstab-category'       => 'Źr tasna nu-stay',
'tooltip-minoredit'               => 'Kerj ażřigad mas ifssus',
'tooltip-save'                    => 'Ḥbu imbddel nek',
'tooltip-preview'                 => 'Mel(fsr) imbddeln nek, urat tḥibit matskert',
'tooltip-diff'                    => 'Mel (fsr) imbddeln li tskert u-ṭṛiṣ',
'tooltip-compareselectedversions' => 'Ẓr inaḥyatn gr sin lqimat li ttuystaynin ɣ tasna yad.',
'tooltip-watch'                   => 'Smdn tasna yad i tilli tsggat.',
'tooltip-recreate'                => 'Als askr n tasna yad waxxa ttuwḥiyyad',
'tooltip-upload'                  => 'Izwir siɣ tullt.',
'tooltip-rollback'                => '"Rard" s yan klik ażrig (iżrign) s ɣiklli sttin kkan tiklit li igguran',
'tooltip-undo'                    => '↓ "Sglb" ḥiyd ambdl ad t mmurẓmt tasatmt n umbdl ɣ umuḍ tiẓri tamzwarut.',

# Browsing diffs
'previousdiff' => 'Imbddln imzwura',
'nextdiff'     => '↓ Ambdl d ittfrn  →',

# Media information
'file-info-size'       => '($1 × $2 piksil, asdaw tugut: $3, MIME anaw: $4)',
'file-nohires'         => '↓<small>Ur tlli tabudut tamqrant.</small>',
'svg-long-desc'        => '↓ (Asdaw SVG, Tabadut n $1 × $2 ifrdan, Tiddi : $3)',
'show-big-image'       => 'balak',
'show-big-image-thumb' => '<small>Size of this preview: $1 × $2 pixels</small>',

# Bad image list
'bad_image_list' => 'zud ghikad :

ghir lhwayj n lista (stour libdounin s *) karaytyo7asab',

# Metadata
'metadata'          => 'isfka n mita',
'metadata-help'     => 'Asdaw ad llan gis inɣmisn yaḍnin lli tfl lkamira tuṭunit niɣd aṣfḍ n uxddam lliɣ ay sgadda asdaw ad',
'metadata-expand'   => 'Ml ifruriyn lluzzanin',
'metadata-collapse' => 'Aḥbu n ifruriyn lluzzanin',
'metadata-fields'   => 'Igran EXIF n isfkan nmita lin illan ɣ tabratad ran ilin ɣ tawlaf n tasna iɣ mzzin tiflut n isfka n mita
Wiyyaḍ raggis ḥbun s ɣiklli sttin kkan gantn
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

'exif-exposureprogram-1' => 'w-ofoss',

'exif-subjectdistance-value' => '$1 metro',

'exif-meteringmode-0'   => 'orityawssan',
'exif-meteringmode-1'   => 'moyen',
'exif-meteringmode-2'   => 'moyen igiddi gh tozzomt',
'exif-meteringmode-3'   => 'tanqqit',
'exif-meteringmode-4'   => 'MultiSpot',
'exif-meteringmode-5'   => 'agaw',
'exif-meteringmode-6'   => 'ghar imik giss',
'exif-meteringmode-255' => 'wayya',

'exif-lightsource-0'  => 'orityawssan',
'exif-lightsource-1'  => 'dow n wass',
'exif-lightsource-2'  => 'Fluorescent',
'exif-lightsource-3'  => 'dow ijhddn',
'exif-lightsource-4'  => 'lflash',
'exif-lightsource-9'  => 'ljow ifolkin',
'exif-lightsource-10' => 'tagot',
'exif-lightsource-11' => 'asklo',

'exif-sensingmethod-2' => 'amfay n lon n tozmi ghyat tosa',
'exif-sensingmethod-3' => 'amfay n lon n tozmi ghsnat tosatin',

'exif-gaincontrol-0' => 'walo',

'exif-contrast-0' => 'normal',
'exif-contrast-1' => 'irtb',
'exif-contrast-2' => 'iqor',

'exif-saturation-0' => 'normal',
'exif-saturation-1' => 'imik ntmlli',
'exif-saturation-2' => 'kigan ntmlli',

'exif-sharpness-0' => 'normal',
'exif-sharpness-1' => 'irtb',
'exif-sharpness-2' => 'iqor',

'exif-subjectdistancerange-0' => 'orityawssan',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'tannayt iqrbn',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'dairat lard chamaliya',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-n' => 'Knots',

# External editor support
'edit-externally'      => '↓ Bddl asdaw ad s wasnas abrrani',
'edit-externally-help' => '(Ẓṛ [http://www.mediawiki.org/wiki/Manual:External_editors/fr les instructions d’installation] bac ad taf uggar n inɣmisn)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'Kullu',
'imagelistall'     => 'Kullu',
'watchlistall2'    => '↓ kraygat (kullu)',
'namespacesall'    => 'kullu',
'monthsall'        => '↓ kullu',
'limitall'         => 'Kullu',

# Trackbacks
'trackbackremove' => '([$1 mhi])',
'trackbacklink'   => 'Trackback',

# Delete conflict
'recreate' => 'awd skr',

# action=purge
'confirm_purge_button' => 'Waxxa',

# Multipage image navigation
'imgmultigo' => 'ballak !',

# Table pager
'ascending_abbrev'         => 'aryaqliw',
'descending_abbrev'        => 'aritgiiz',
'table_pager_next'         => 'tawriqt tamaynut',
'table_pager_prev'         => 'tawriqt izrin',
'table_pager_first'        => 'tawriqt tamzwarut',
'table_pager_last'         => 'tawriqt tamgrut',
'table_pager_limit_submit' => 'ballak',
'table_pager_empty'        => 'ornofa amya',

# Watchlist editor
'watchlistedit-normal-submit' => 'hiyd lanawin',
'watchlistedit-raw-titles'    => 'Azwl',

# Watchlist editing tools
'watchlisttools-view' => '↓ Umuɣ n imtfrn',
'watchlisttools-edit' => '↓ Ẓr tẓṛgt umuɣ lli tuytfarn',
'watchlisttools-raw'  => '↓ Ẓṛig umuɣ n tisniwin',

# Special:Version
'version'                       => 'noskha',
'version-specialpages'          => 'Tisnatin timzlay',
'version-parserhooks'           => 'khatatif lmohallil',
'version-variables'             => 'lmotaghayirat',
'version-other'                 => 'wayya',
'version-mediahandlers'         => 'motahakkimat lmedia',
'version-hooks'                 => 'lkhtatif',
'version-extension-functions'   => 'lkhdaym n limtidad',
'version-parser-extensiontags'  => 'imarkiwn n limtidad n lmohalil',
'version-parser-function-hooks' => 'lkhtatif ndala',
'version-poweredby-others'      => 'wiyyad',
'version-software-product'      => 'lmntoj',
'version-software-version'      => 'noskha',

# Special:FilePath
'filepath-page'   => 'Asdaw:',
'filepath-submit' => 'Ftu',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'smiyt n-wasdaw:',
'fileduplicatesearch-submit'   => 'Sigl',

# Special:SpecialPages
'specialpages'                 => 'tiwriqin tesbtarin',
'specialpages-group-other'     => 'tiwriqin khassa yadnin',
'specialpages-group-login'     => 'kchm/sjl',
'specialpages-group-changes'   => 'tghyirat granin d sijilat',
'specialpages-group-media'     => 'taqarir n lmedia d upload',
'specialpages-group-users'     => 'imskhdamn d salahiyat',
'specialpages-group-highuse'   => 'tiwriqim li bahra skhdamn midn',
'specialpages-group-pages'     => 'lista n twriqin',
'specialpages-group-pagetools' => 'tawriqt n ladawat',
'specialpages-group-wiki'      => 'wiki ladawat dlmalomat',
'specialpages-group-redirects' => 'sfhat tahwil gant khassa',
'specialpages-group-spam'      => 'ladawat n spam',

# Special:BlankPage
'blankpage' => 'tawriqt orgiss walo',

# Special:Tags
'tag-filter'           => '[[Special:Imarkiwn|amarkiy]] astay:',
'tag-filter-submit'    => 'Istayn',
'tags-title'           => 'imarkiwn',
'tags-hitcount-header' => 'tghyiran markanin',
'tags-edit'            => 'bddl',

# Special:ComparePages
'comparepages'     => 'qarnn tiwriqin',
'compare-selector' => 'qarn lmorajaa ntwriqin',
'compare-page1'    => 'tawriqt 1',
'compare-page2'    => 'tawriqt 2',
'compare-rev1'     => 'morajaa 1',
'compare-rev2'     => 'morajaa 2',
'compare-submit'   => 'qarn',

# HTML forms
'htmlform-submit'              => 'sifd',
'htmlform-reset'               => 'sglbd tghyirat',
'htmlform-selectorother-other' => 'wayya',

);

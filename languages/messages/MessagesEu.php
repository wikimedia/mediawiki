<?php
/** Basque (Euskara)
 *
 * @addtogroup Language
 */

$skinNames = array(
	'standard'     => 'Lehenetsia',
	'nostalgia'    => 'Nostalgia',
	'cologneblue'  => 'Cologne Blue',
);
$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Aparteko',
	NS_MAIN           => '',
	NS_TALK           => 'Eztabaida',
	NS_USER           => 'Lankide',
	NS_USER_TALK      => 'Lankide_eztabaida',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_eztabaida',
	NS_IMAGE          => 'Irudi',
	NS_IMAGE_TALK     => 'Irudi_eztabaida',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_eztabaida',
	NS_TEMPLATE       => 'Txantiloi',
	NS_TEMPLATE_TALK  => 'Txantiloi_eztabaida',
	NS_HELP           => 'Laguntza',
	NS_HELP_TALK      => 'Laguntza_eztabaida',
	NS_CATEGORY       => 'Kategoria',
	NS_CATEGORY_TALK  => 'Kategoria_eztabaida',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Loturak azpimarratu:',
'tog-highlightbroken'         => 'Lotura hautsiak <a href="" class="new">horrela</a> erakutsi (bestela, honela<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Paragrafoak justifikatu',
'tog-hideminor'               => 'Azken aldaketetan aldaketa txikiak ezkutatu',
'tog-extendwatchlist'         => 'Jarraipen zerrenda zabaldu aldaketa guztiak ikusteko',
'tog-usenewrc'                => 'Hobetutako azken aldaketak (JavaScript)',
'tog-numberheadings'          => 'Goiburukoak automatikoki zenbakitu',
'tog-showtoolbar'             => 'Aldaketen tresna-barra erakutsi (JavaScript)',
'tog-editondblclick'          => 'Klik bikoitzaren bitartez orrialdeak aldatu (JavaScript)',
'tog-editsection'             => 'Atalak [aldatu] loturen bitartez aldatzeko aukera gaitu',
'tog-editsectiononrightclick' => 'Atalen izenburuetan klik eginez atala<br />aldatzea gaitu (JavaScript)',
'tog-showtoc'                 => 'Edukien taula erakutsi (3 goiburukotik gorako orrialdeentzako)',
'tog-rememberpassword'        => 'Nire saioa ordenagailu honetan gogoratu',
'tog-editwidth'               => 'Zabalera osoko aldaketa koadroa',
'tog-watchcreations'          => 'Sortzen ditudan orrialdeak jarraipen zerrendara gehitu',
'tog-watchdefault'            => 'Aldatzen ditudan orrialdeak jarraipen zerrendara gehitu',
'tog-minordefault'            => 'Lehenetsi bezala aldaketa txiki bezala markatu guztiak',
'tog-previewontop'            => 'Aurrebista aldaketa koadroaren aurretik erakutsi',
'tog-previewonfirst'          => 'Lehen aldaketan aurrebista erakutsi',
'tog-nocache'                 => 'Orrialdeen katxea ezgaitu',
'tog-enotifwatchlistpages'    => 'Jarraitzen ari naizen orrialde baten aldaketak daudenean e-posta jaso',
'tog-enotifusertalkpages'     => 'Nire eztabaida orrialdea aldatzen denean e-posta jaso',
'tog-enotifminoredits'        => 'Aldaketa txikiak direnean ere e-posta jaso',
'tog-enotifrevealaddr'        => 'Jakinarazpen mezuetan nire e-posta helbidea erakutsi',
'tog-shownumberswatching'     => 'Jarraitzen duen erabiltzaile kopurua erakutsi',
'tog-fancysig'                => 'Lotura automatikorik gabeko sinadura',
'tog-externaleditor'          => 'Lehenetsi bezala kanpoko editore bat erabili',
'tog-externaldiff'            => 'Lehenetsi bezala kanpoko diff erreminta erabili',
'tog-showjumplinks'           => '"Hona jo" irisgarritasun loturak gaitu',
'tog-uselivepreview'          => 'Zuzeneko aurrebista erakutsi (JavaScript) (Proba fasean)',
'tog-forceeditsummary'        => 'Aldaketaren laburpena zuri uzterakoan ohartarazi',
'tog-watchlisthideown'        => 'Jarraipen zerrendan nire aldaketak ezkutatu',
'tog-watchlisthidebots'       => 'Jarraipen zerrendan bot-en aldaketak ezkutatu',

'underline-always'  => 'Beti',
'underline-never'   => 'Inoiz ez',
'underline-default' => 'Nabigatzailearen lehenetsitako balioa',

'skinpreview' => '(Aurrebista)',

# Dates
'sunday'        => 'Igandea',
'monday'        => 'Astelehena',
'tuesday'       => 'Asteartea',
'wednesday'     => 'Asteazkena',
'thursday'      => 'Osteguna',
'friday'        => 'Ostirala',
'saturday'      => 'Larunbata',
'sun'           => 'Iga',
'mon'           => 'Asl',
'tue'           => 'Asr',
'wed'           => 'Asz',
'thu'           => 'Osg',
'fri'           => 'Osr',
'sat'           => 'Lar',
'january'       => 'Urtarrila',
'february'      => 'Otsaila',
'march'         => 'Martxoa',
'april'         => 'Apirila',
'may_long'      => 'Maiatza',
'june'          => 'Ekaina',
'july'          => 'Uztaila',
'august'        => 'Abuztua',
'september'     => 'Iraila',
'october'       => 'Urria',
'november'      => 'Azaroa',
'december'      => 'Abendua',
'january-gen'   => 'Urtarril',
'february-gen'  => 'Otsail',
'march-gen'     => 'Martxo',
'april-gen'     => 'Apiril',
'may-gen'       => 'Maiatz',
'june-gen'      => 'Ekain',
'july-gen'      => 'Uztail',
'august-gen'    => 'Abuztu',
'september-gen' => 'Irail',
'october-gen'   => 'Urri',
'november-gen'  => 'Azaro',
'december-gen'  => 'Abendu',
'jan'           => 'Urt',
'feb'           => 'Ots',
'mar'           => 'Mar',
'apr'           => 'Api',
'may'           => 'Mai',
'jun'           => 'Eka',
'jul'           => 'Uzt',
'aug'           => 'Abu',
'sep'           => 'Ira',
'oct'           => 'Urr',
'nov'           => 'Aza',
'dec'           => 'Abe',

# Bits of text used by many pages
'categories'      => '{{PLURAL:$1|Kategoria|Kategoriak}}',
'category_header' => '"$1" kategoriako artikuluak',
'subcategories'   => 'Azpikategoriak',

'linkprefix'        => '/^(.*?)([a-zA-Z\x80-\xff]+)$/sD',
'mainpagetext'      => "<big>'''MediaWiki arrakastaz instalatu da.'''</big>",
'mainpagedocfooter' => 'Ikus [http://meta.wikimedia.org/wiki/Help:Contents Erabiltzaile Gida] wiki softwarea erabiltzen hasteko informazio gehiagorako.

== Nola hasi ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings Konfigurazio balioen zerrenda]
* [http://www.mediawiki.org/wiki/Help:FAQ MediaWiki FAQ (Maiz egindako galderak)]
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWikiren argitalpenen posta zerrenda]',

'about'          => 'Honi Buruz',
'article'        => 'Artikulua',
'newwindow'      => '(leiho berrian irekitzen da)',
'cancel'         => 'Bertan behera utzi',
'qbfind'         => 'Aurkitu',
'qbbrowse'       => 'Arakatu',
'qbedit'         => 'Aldatu',
'qbpageoptions'  => 'Orrialde hau',
'qbpageinfo'     => 'Testuingurua',
'qbmyoptions'    => 'Nire orrialdeak',
'qbspecialpages' => 'Aparteko orrialdeak',
'moredotdotdot'  => 'Gehiago...',
'mypage'         => 'Nire orrialdea',
'mytalk'         => 'Nire eztabaida',
'anontalk'       => 'IP honen eztabaida',
'navigation'     => 'Nabigazioa',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Errorea',
'returnto'          => '$1(e)ra itzuli.',
'tagline'           => '{{SITENAME}}(e)tik',
'help'              => 'Laguntza',
'search'            => 'Bilatu',
'searchbutton'      => 'Bilatu',
'go'                => 'Joan',
'searcharticle'     => 'Joan',
'history'           => 'Orrialdearen historia',
'history_short'     => 'Historia',
'updatedmarker'     => 'nire azkeneko bisitaz geroztik eguneratuta',
'info_short'        => 'Informazioa',
'printableversion'  => 'Inprimatzeko bertsioa',
'permalink'         => 'Lotura finkoa',
'print'             => 'Inprimatu',
'edit'              => 'Aldatu',
'editthispage'      => 'Orrialde hau aldatu',
'delete'            => 'Ezabatu',
'deletethispage'    => 'Orrialde hau ezabatu',
'undelete_short'    => 'Berreskuratu {{PLURAL:$1|aldaketa bat|$1 aldaketa}}',
'protect'           => 'Babestu',
'protectthispage'   => 'Orrialde hau babestu',
'unprotect'         => 'Babesa kendu',
'unprotectthispage' => 'Orrialde honi babesa kendu',
'newpage'           => 'Orrialde berria',
'talkpage'          => 'Orrialde honi buruz eztabaidatu',
'specialpage'       => 'Aparteko orrialdea',
'personaltools'     => 'Tresna pertsonalak',
'postcomment'       => 'Azalpen bat bidali',
'articlepage'       => 'Artikulua ikusi',
'talk'              => 'Eztabaida',
'views'             => 'Bistaratzeak',
'toolbox'           => 'Tresna taula',
'userpage'          => 'Lankide orrialdea ikusi',
'projectpage'       => 'Proiektuaren orrialdea ikusi',
'imagepage'         => 'Irudiaren orrialdea ikusi',
'mediawikipage'     => 'Mezu orrialdea ikusi',
'templatepage'      => 'Txantiloi orrialdea ikusi',
'viewhelppage'      => 'Laguntza orrialdea ikusi',
'categorypage'      => 'Kategoria orrialdea ikusi',
'viewtalkpage'      => 'Eztabaida ikusi',
'otherlanguages'    => 'Beste hizkuntzetan',
'redirectedfrom'    => '($1(e)tik birzuzenduta)',
'redirectpagesub'   => 'Birzuzenketa orria',
'lastmodifiedat'    => 'Orrialdearen azken aldaketa: $2, $1.', # $1 date, $2 time
'viewcount'         => 'Orrialde hau {{plural:$1|behin|$1 aldiz}} bisitatu da.',
'protectedpage'     => 'Babestutako orrialdea',
'jumpto'            => 'Hona jo:',
'jumptonavigation'  => 'nabigazioa',
'jumptosearch'      => 'bilatu',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}}(e)ri buruz',
'aboutpage'         => 'Project:Honi_buruz',
'bugreports'        => 'Programazio-erroreen jakinarazpenak',
'bugreportspage'    => 'Project:Programazio-erroreen jakinarazpenak',
'copyright'         => 'Eduki guztia $1(r)en babespean dago.',
'copyrightpagename' => '{{SITENAME}} copyright',
'copyrightpage'     => 'Project:Eskubideak',
'currentevents'     => 'Albisteak',
'currentevents-url' => 'Albisteak',
'disclaimers'       => 'Mugaketak',
'disclaimerpage'    => 'Project:Erantzukizunen mugaketa orokorra',
'edithelp'          => 'Aldaketak egiteko laguntza',
'edithelppage'      => 'Help:Aldaketak egiteko laguntza',
'faq'               => 'Maiz egindako galderak',
'faqpage'           => 'Project:Maiz egindako galderak',
'helppage'          => 'Help:Contents',
'mainpage'          => 'Azala',
'policy-url'        => 'Project:Politikak',
'portal'            => 'Wikipediako txokoa',
'portal-url'        => 'Project:Txokoa',
'privacy'           => 'Pribatutasun politika',
'privacypage'       => 'Project:Pribatutsan politika',
'sitesupport'       => 'Dohaintzak',
'sitesupport-url'   => 'Project:Gune laguntza',

'badaccess'        => 'Baimen errorea',
'badaccess-group0' => 'Ez daukazu ekintza hori burutzeko baimenik.',
'badaccess-group1' => 'Ekintza hori $1 taldeko erabiltzaileei mugatuta dago.',
'badaccess-group2' => 'Ekintza hori $1 taldeetako bateko erabiltzaileei mugatuta dago.',
'badaccess-groups' => 'Ekintza hori $1 taldeetako batetako erabiltzaileei mugatuta dago.',

'versionrequired'     => 'MediaWikiren $1 bertsioa beharrezkoa da',
'versionrequiredtext' => 'MediaWikiren $1 bertsioa beharrezkoa da orrialde hau erabiltzeko. Ikus [[Special:Version]]',

'ok'                  => 'Ados',
'pagetitle'           => '$1 - {{SITENAME}}',
'retrievedfrom'       => '"$1"(e)tik jasota',
'youhavenewmessages'  => '$1 dauzkazu ($2).',
'newmessageslink'     => 'Mezu berriak',
'newmessagesdifflink' => 'azken aldaketa ikusi',
'editsection'         => 'aldatu',
'editold'             => 'aldatu',
'editsectionhint'     => 'Atala aldatu: $1',
'toc'                 => 'Edukiak',
'showtoc'             => 'erakutsi',
'hidetoc'             => 'ezkutatu',
'thisisdeleted'       => '$1 ikusi edo leheneratu?',
'viewdeleted'         => '$1 ikusi?',
'restorelink'         => '{{PLURAL:$1|ezabatutako aldaketa bat|ezabatutako $1 aldaketa}}',
'feedlinks'           => 'Jarioa:',
'feed-invalid'        => 'Baliogabeko harpidetza jario mota.',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Artikulua',
'nstab-user'      => 'Erabiltzaile orrialdea',
'nstab-media'     => 'Media orrialdea',
'nstab-special'   => 'Apartekoa',
'nstab-project'   => 'Proiektu orrialdea',
'nstab-image'     => 'Fitxategia',
'nstab-mediawiki' => 'Mezua',
'nstab-template'  => 'Txantiloia',
'nstab-help'      => 'Laguntza orrialdea',
'nstab-category'  => 'Kategoria',

# Main script and global functions
'nosuchaction'      => 'Ekintza hori ez da existitizen',
'nosuchactiontext'  => 'URL bidez zehaztutako ekintza ez du wikiak ezagutzen',
'nosuchspecialpage' => 'Ez da aparteko orrialde hori existitzen',
'nospecialpagetext' => 'Baliogabeko aparteko orrialde bat eskatu duzu; existitzen direnen zerrenda ikus dezakezu  [[{{ns:special}}:Specialpages]] orrialdean.',

# General errors
'error'                => 'Errorea',
'databaseerror'        => 'Datu-base errorea',
'dberrortext'          => 'Datu-basean kontsulta egiterakoan sintaxi errore bat gertatu da. Baliteke softwareak bug bat izatea. Datu-basean egindako azken kontsulta:
<blockquote><tt>$1</tt></blockquote>
Funtzio honekin: "<tt>$2</tt>".
MySQLk emandako errore informazioa: "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Datu-basean kontsulta egiterakoan sintaxi errore bat gertatu da. Datu-basean egindako azken kontsulta:
"$1"
Funtzio honekin: "$2".
MySQLk emandako errore informazioa: "$3: $4"',
'noconnect'            => 'Sentitzen dugu! Wikian arazo teknikoak direla-eta, ezin izan da datu-basera konektatu. <br />
$1',
'nodb'                 => 'Ezin izan da $1 datu-basea hautatu',
'cachederror'          => 'Honako hau eskatutako orrialdearen katxeko kopia da, litekeena da eguneratuta ez azaltzea.',
'laggedslavemode'      => 'Oharra: Baliteke orrialde honetan azken aldaketak ez erakustea.',
'readonly'             => 'Datu-basea blokeatuta dago',
'enterlockreason'      => 'Zehaztu blokeatzeko arrazoia, noiz kenduko den jakinaraziz',
'readonlytext'         => 'Datu-basea artikulu berriak sortu edo aldaketak ez egiteko blokeatuta dago, seguruenik mantenu lanak direla-eta. Blokeo hori kentzerakoan beti bezala egongo da berriz.

Blokeatu duen administratzaileak azalpen hau eman du: $1',
'missingarticle'       => 'Datu-baseak ez du aurkitu beharko lukeen orrialde baten testua aurkitu, "$1" izena duena.

Arrazoia iraungitako diff bat edo ezabatutako orrialde baten historia lotura jarraitzea izan liteke.

Hau ez bada zure egoera, baliteke softwarean bug bat aurkitu izana.
Mesedez, administratzaileari jakinarazi, URLa bidaliz.',
'readonly_lag'         => 'Datu-basea automatikoki blokeatu da menpeko zerbitzariak nagusiarekin sinkronizatu bitartean',
'internalerror'        => 'Barne errorea',
'filecopyerror'        => 'Ezin izan da "$1" fitxategia "$2"(e)ra kopiatu.',
'filerenameerror'      => 'Ezin izan zaio "$1" fitxategiari "$2" izen berria eman.',
'filedeleteerror'      => 'Ezin izan da "$1" fitxategia ezabatu.',
'filenotfound'         => 'Ezin izan da "$1" fitxategia aurkitu.',
'unexpected'           => 'Espero ez zen balioa: "$1"="$2".',
'formerror'            => 'Errorea: ezin izan da formularioa bidali',
'badarticleerror'      => 'Ezin da ekintza hau orrialde honetan burutu.',
'cannotdelete'         => 'Ezin izan da zehaztutako orrialde edo fitxategia ezabatu. (Baliteke beste norbaitek ezabatu izana.)',
'badtitle'             => 'Izenburu ezegokia',
'badtitletext'         => 'Eskatutako orrialde izenburua ez da baliozkoa, hutsik dago, edo gaizki lotutako hizkuntzen arteko lotura da. Baliteke izenburuetan erabili ezin den karaktereren bat izatea.',
'perfdisabled'         => 'Sentitzen dugu! Ezaugarri hau denbora batez ezgaituta dago, wikian arazoak sortzen baititu, datu-basea motelduz.',
'perfcached'           => 'Hurrengo datuak katxean gordeta daude eta litekeena da guztiz eguneratuta ez egotea:',
'perfcachedts'         => 'Hurrengo datuak katxean daude, $1 eguneratu zen azkenekoz.',
'wrong_wfQuery_params' => 'Baliogabeko parametroak eman zaizkio wfQuery() funtzioari<br />
Funtzioa: $1<br />
Kontsulta: $2',
'viewsource'           => 'Kodea ikusi',
'viewsourcefor'        => '$1',
'protectedinterface'   => 'TOrrialde honek softwarearentzako interfaze testua gordetzen du eta blokeatuta dago bandalismoak saihesteko.',
'editinginterface'     => "'''Oharra:''' Softwarearentzako interfaze testua duen orrialde bat aldatzen ari zara. Orrialde honetako aldaketek erabiltzaile guztiei eragingo die.",
'sqlhidden'            => '(ezkutuko SQL kontsulta)',

# Login and logout pages
'logouttitle'                => 'Saioa ixtea',
'logouttext'                 => '<strong>Saioa itxi egin duzu.</strong><br />
Erabiltzaile anonimo bezala jarraitu dezakezu {{SITENAME}} erabiltzen, edo saioa has dezakezu berriz erabiltzaile berdinarekin edo ezberdin batekin. Kontuan izan orrialde batzuk saioa hasita bazenu bezala ikus ditzakezula nabigatzailearen katxea garbitu arte.',
'welcomecreation'            => '== Ongi etorri, $1! ==

Zure kontua sortu egin da. Ez ahaztu {{SITENAME}}(e)ko hobespenak aldatzea.',
'loginpagetitle'             => 'Saio hasiera',
'yourname'                   => 'Erabiltzaile izena',
'yourpassword'               => 'Pasahitza',
'yourpasswordagain'          => 'Pasahitza berriz',
'remembermypassword'         => 'Nire saioa ordenagailu honetan gogoratu',
'yourdomainname'             => 'Zure domeinua',
'externaldberror'            => 'There was either an external authentication database error or you are not allowed to update your external account.',
'loginproblem'               => '<b>Arazoa gertatu da saioa hasterakoan.</b><br />Saiatu berriz!',
'alreadyloggedin'            => '<strong>$1, saioa hasi duzu jada!</strong><br />',
'login'                      => 'Saioa hasi',
'loginprompt'                => 'Cookieak gaituta izatea beharrezkoa da {{SITENAME}}(e)n saioa hasteko.',
'userlogin'                  => 'Saioa hasi / kontua sortu',
'logout'                     => 'Saioa itxi',
'userlogout'                 => 'Saioa itxi',
'notloggedin'                => 'Saioa hasi gabe',
'nologin'                    => 'Ez duzu erabiltzaile konturik? $1.',
'nologinlink'                => 'Kontua sortu',
'createaccount'              => 'Kontua sortu',
'gotaccount'                 => 'Baduzu erabiltzaile kontua? $1.',
'gotaccountlink'             => 'Saioa hasi',
'createaccountmail'          => 'e-postaz',
'badretype'                  => 'Idatzitako pasahitzak ez dira berdinak.',
'userexists'                 => 'Aukeratutako erabiltzaile izena hartuta dago. Mesedez, ezberdin bat aukeratu.',
'youremail'                  => 'E-posta *:',
'username'                   => 'Erabiltzaile izena:',
'uid'                        => 'Erabiltzaile IDa:',
'yourrealname'               => 'Benetako izena *:',
'yourlanguage'               => 'Hizkuntza:',
'yourvariant'                => 'Aldaera',
'yournick'                   => 'Erabiltzaile izena:',
'badsig'                     => 'Baliogabeko sinadura; egiaztatu HTML etiketak.',
'email'                      => 'E-posta',
'prefs-help-realname'        => '* Benetako izena (aukerakoa): zehaztea erabakiz gero, zure lanarentzako atribuzio bezala balioko du.',
'loginerror'                 => 'Errorea saioa hastean',
'prefs-help-email'           => '* E-posta (aukerakoa): Beste lankideak zurekin harremanetan jartzeko, zure nortasuna ezagutzera eman gabe.',
'nocookiesnew'               => 'Erabiltzaile kontua sortu da, baina ez da saioa hasi. {{SITENAME}}(e)k cookieak erabiltzen ditu saioekin eta ezgaituta dauzkazu. Gaitu itzazu mesedez, eta ondoren saiatu saioa hasten zure erabiltzaile izen eta pasahitz berriak erabiliz.',
'nocookieslogin'             => '{{SITENAME}}(e)k cookieak erabiltzen ditu saioekin eta ezgaituta dauzkazu. Gaitu itzazu mesedez, eta saiatu berriz.',
'noname'                     => 'Ez duzu baliozko erabiltzaile izen bat zehaztu.',
'loginsuccesstitle'          => 'Saio hasiera egina',
'loginsuccess'               => "'''Saioa hasi duzu {{SITENAME}}(e)n \"\$1\" izenarekin.'''",
'nosuchuser'                 => 'Ez dago "$1" izena duen lankiderik. Mesedez, egiaztatu ondo idatzi duzun edo kontu berria sor ezazu.',
'nosuchusershort'            => 'Ez dago "$1" izena duen erabiltzailerik. Egiaztatu ongi idatzi duzula.',
'nouserspecified'            => 'Erabiltzaile izena zehaztu beharra daukazu.',
'wrongpassword'              => 'Pasahitza ez da zuzena. Saiatu berriz.',
'wrongpasswordempty'         => 'Pasahitza hutsik dago. Saiatu berriz.',
'mailmypassword'             => 'Pasahitza e-postaz bidali',
'passwordremindertitle'      => 'Pasahitzaren gogorarazpena {{SITENAME}}(e)tik',
'passwordremindertext'       => 'Norbaitek (zuk ziurrenik, $1 IP helbidetik)
{{SITENAME}}(e)ko pasahitza zuri bidaltzea eskatu du ($4).
"$2" erabiltzailearen pasahitza "$3" da orain.
Saioa hasi eta pasahitza aldatu beharko zenuke orain.

Eskaera hau beste norbaitek egin badu edo jada pasahitza gogoratu baduzu eta ez baduzu aldatu nahi, mezu honi kasurik ez egin eta jarraitu zuri pasahitz zaharra erabiltzen.',
'noemail'                    => 'Ez dago "$1" erabiltzailearen e-posta helbiderik gordeta.',
'passwordsent'               => 'Pasahitz berria bidali da "$1" erabiltzailearen e-posta helbidera.
Mesedez, saioa hasi jasotakoan.',
'eauthentsent'               => 'Egiaztapen mezu bat bidali da zehaztutako e-posta helbidera.
Helbide horretara beste edozein mezu bidali aurretik, bertan azaltzen diren argibideak jarraitu behar dituzu, e-posta hori zurea dela egiaztatzeko.',
'mailerror'                  => 'Errorea mezua bidaltzerakoan: $1',
'acct_creation_throttle_hit' => 'Sentitzen dugu, $1 erabiltzaile kontu sortu dituzu dagoeneko. Ezin duzu gehiago sortu.',
'emailauthenticated'         => 'Zure e-posta helbidea egiaztatu zeneko data: $1.',
'emailnotauthenticated'      => 'Zure posta helbidea egiaztatu gabe dago. Ez da mezurik bidaliko hurrengo ezaugarrientzako.',
'noemailprefs'               => 'Zehaztu e-posta helbide bat ezaugarri hauek erabili ahal izateko.',
'emailconfirmlink'           => 'Egiaztatu zure e-posta helbidea',
'invalidemailaddress'        => 'Ezin da e-posta helbide hori ontzat eman baliogabeko formatua duela dirudielako. Mesedez, formatu egokia duen helbide bat zehaztu, edo hutsik utzi.',
'accountcreated'             => 'Kontua sortuta',
'accountcreatedtext'         => '$1 erabiltzaile kontua sortu egin da.',

# Edit page toolbar
'bold_sample'     => 'Testu beltza',
'bold_tip'        => 'Testu beltza',
'italic_sample'   => 'Testu etzana',
'italic_tip'      => 'Testu etzana',
'link_sample'     => 'Loturaren izenburua',
'link_tip'        => 'Barne lotura',
'extlink_sample'  => 'http://www.adibidea.com loturaren izenburua',
'extlink_tip'     => 'Kanpo lotura (gogoratu http:// aurrizkia)',
'headline_sample' => 'Goiburuko testua',
'headline_tip'    => '2. mailako goiburukoa',
'math_sample'     => 'Formula hemen idatzi',
'math_tip'        => 'Formula matematikoa (LaTeX)',
'nowiki_sample'   => 'Formatu gabeko testua idatzi hemen',
'nowiki_tip'      => 'Ez egin jaramonik wiki formatuari',
'image_sample'    => 'Adibidea.jpg',
'image_tip'       => 'Txertatutako irudia',
'media_sample'    => 'Adibidea.ogg',
'media_tip'       => 'Media fitxategi lotura',
'sig_tip'         => 'Zure sinadura data eta orduarekin',
'hr_tip'          => 'Lerro horizontala (gutxitan erabili)',

# Edit pages
'summary'                   => 'Laburpena',
'subject'                   => 'Izenburua',
'minoredit'                 => 'Hau aldaketa txikia da',
'watchthis'                 => 'Orrialde hau jarraitu',
'savearticle'               => 'Orrialdea gorde',
'preview'                   => 'Aurrebista',
'showpreview'               => 'Aurrebista erakutsi',
'showlivepreview'           => 'Zuzeneko aurrebista',
'showdiff'                  => 'Aldaketak erakutsi',
'anoneditwarning'           => "'''Oharra:''' Ez duzu saioa hasi. Zure IP helbidea orrialde honetako historian gordeko da.",
'missingsummary'            => "'''Gogorarazpena:''' Ez duzu aldaketa laburpen bat zehaztu. Berriz ere gordetzeko aukeratzen baduzu, laburpen mezurik gordeko da.",
'missingcommenttext'        => 'Mesedez, iruzkin bat idatzi jarraian.',
'blockedtitle'              => 'Erabiltzailea blokeatuta dago',
'blockedtext'               => 'Zure erabiltzaile izena edo IP helbidea $1(e)k blokeatu du. Emandako arrazoia honako hau da: \'\'$2\'\' $1 edo Wikipediako beste [[{{MediaWiki:grouppage-sysop}}|administratzaile]] batekin harremanetan jarri beharko zinateke zure blokeoa eztabaidatzeko. Kontuan izan ezingo duzula "Erabiltzaile honi e-posta bidali" aukera erabili zure [[Special:Preferences|Hobespenetan]] baliozko e-posta helbide bat definitu ezean. Zure IP helbidea $3 da. Mesedez, edozein kontsulta egiterakoan, helbide hori aipatu.',
'blockedoriginalsource'     => "Jarraian ikus daiteke '''$1'''(r)en kodea:",
'blockededitsource'         => "Jarraian ikus daitezke '''$1'''(e)n egin dituzun aldaketak:",
'whitelistedittitle'        => 'Saioa hastea beharrezkoa da aldaketak egiteko',
'whitelistedittext'         => '$1 behar duzu orrialdeak aldatu ahal izateko..',
'whitelistreadtitle'        => 'Saioa hastea beharrezkoa da irakurtzeko',
'whitelistreadtext'         => '[[Special:Userlogin|Saioa hasi]] behar duzu orrialdeak irakurtzeko.',
'whitelistacctitle'         => 'Ez daukazu kontu berri bat sortzeko baimenik',
'whitelistacctext'          => 'Wiki honetan kontu berriak sortu ahal izateko [[Special:Userlogin|saioa hasi]] eta baimena izatea beharrezko da.',
'confirmedittitle'          => 'E-posta egiaztatzea beharrezkoa da aldaketak egiteko',
'confirmedittext'           => 'Orrialdeetan aldaketak egin aurretik zure e-posta helbidea egiaztatu beharra daukazu. Mesedez, zehaztu eta egiaztatu zure e-posta helbidea [[Special:Preferences|hobespenetan]].',
'loginreqtitle'             => 'Saioa hastea beharrezkoa',
'loginreqlink'              => 'saioa hasi',
'loginreqpagetext'          => 'Beste orrialde batzuk ikusteko $1 beharra daukazu..',
'accmailtitle'              => 'Pasahitza bidali da.',
'accmailtext'               => '"$1"(r)en pasahitza $2(e)ra bidali da.',
'newarticle'                => '(Berria)',
'newarticletext'            => "Orrialde hau ez da existitzen oraindik. Orrialde sortu nahi baduzu, beheko koadroan idazten hasi zaitezke (ikus [[{{MediaWiki:helppage}}|laguntza orrialdea]] informazio gehiagorako). Hona nahi gabe etorri bazara, nabigatzaileko '''atzera''' botoian klik egin.",
'anontalkpagetext'          => "----''Honako hau konturik sortu ez edo erabiltzen ez duen erabiltzaile anonimo baten eztabaida orria da. Bere IP helbidea erabili beharko da beraz identifikatzeko. Erabiltzaile batek baino gehiagok IP bera erabil dezakete ordea. Erabiltzaile anonimoa bazara eta zurekin zerikusirik ez duten mezuak jasotzen badituzu, mesedez [[Special:Userlogin|Izena eman edo saioa hasi]] etorkizunean horrelakoak gerta ez daitezen.''",
'noarticletext'             => 'Oraindik ez dago testurik orrialde honetan; beste orrialde batzuetan [[{{ns:special}}:Search/{{PAGENAME}}|bilatu dezakezu izenburu hau]] edo [{{fullurl:{{FULLPAGENAME}}|action=edit}} berau aldatu ere egin dezakezu].',
'clearyourcache'            => "'''Oharra:''' Gorde ondoren zure nabigatzailearen katxea ekidin beharko duzu aldaketak ikusteko. '''Mozilla / Firefox / Safari:''' ''Shift'' tekla sakatu birkargatzeko momentuan, edo ''Ctrl-Shift-R'' sakatu (''Cmd-Shift-R'' Apple Mac baten); '''IE:''' ''Ctrl'' tekla sakatu birkargatzeko momentuan, edo ''Ctrl-F5'' sakatu; '''Konqueror:''': Birkargatzeko klik egin, edo F5 sakatu, besterik ez; '''Opera''' erabiltzaileek ''Tresnak-Hobespenak'' atalera jo eta katxea garbitzeko aukera hautatu.",
'usercssjsyoucanpreview'    => "<strong>Laguntza:</strong> Zure CSS/JS berria gorde aurretik probatzeko 'Aurrebista erakutsi' botoia erabili.",
'usercsspreview'            => "'''Ez ahaztu zure CSS kodea aurreikusten zabiltzala, oraindik ez dela gorde!'''",
'userjspreview'             => "'''Gogoratu zure JavaScript kodea probatu/aurreikusten zabiltzala, oraindik ez da gorde!'''",
'userinvalidcssjstitle'     => "'''Oharra:''' Ez da \"\$1\" itxura existitzen. Kontuan izan .css eta .js fitxategi pertsonalizatuen izenak letra xehez idatzi behar direla; adibidez, Lankide:Adibide/monobook.css, eta ez Lankide:Adibide/Monobook.css.",
'updated'                   => '(Eguneratua)',
'note'                      => '<strong>Oharra:</strong>',
'previewnote'               => '<strong>Gogoratu hau aurreikusketa bat dela, beraz gorde egin beharko duzu!</strong>',
'previewconflict'           => 'Aurreikuspenak aldaketen koadroan idatzitako testua erakusten du, gorde ondoren agertuko den bezala.',
'session_fail_preview'      => '<strong>Sentitzen dugu! Ezin izan da zure aldaketa prozesatu, saioko datu batzuen galera dela-eta. Mesedez, saiatu berriz. Arazoak jarraitzen badu, saiatu saioa amaitu eta berriz hasten.</strong>',
'session_fail_preview_html' => "<strong>Sentitzen dugu! Ezin izan dugu zure aldaketa burutu, saio datu galera bat medio.</strong>

''Wiki honek HTML kodea onartzen duenez, aurreikuspena ezgaituta dago JavaScript erasoak saihestu asmoz.''

<strong>Aldaketa saiakera hau zuzena baldin bada, saiatu berriro mesedez. Arazoak jarraitzen badu, saiatu saioa itxi eta berriz hasten.</strong>",
'importing'                 => '$1 inportatzen',
'editing'                   => '$1 aldatzen',
'editinguser'               => '<b>$1</b> erabiltzailea aldatzen',
'editingsection'            => '$1 aldatzen (atala)',
'editingcomment'            => '$1 aldatzen (iruzkina)',
'editconflict'              => 'Aldaketa gatazka: $1',
'explainconflict'           => 'Zu orrialdea aldatzen hasi ondoren beste norbaitek ere aldaketak egin ditu. Goiko testu koadroan ikus daiteke orrialdeak uneotan duen edukia. Zure aldaketak beheko testu koadroan ikus daitezke. Zure testua dagoenarekin elkartu beharko duzu. Orrialdea gordetzeko erabakitzen duzun unean goiko koadroko edukia <b>bakarrik</b> gordeko da.<br />',
'yourtext'                  => 'Zure testua',
'storedversion'             => 'Gordetako bertsioa',
'nonunicodebrowser'         => '<strong>OHARRA: Zure nabigatzailea ez dator Unicode arauarekin bat. Artikuluak modu seguruan aldatu ahal izateko beste sistema bat gaitu da: ASCII ez diren karaktereak kode hamaseitar bezala agertuko dira aldaketa koadroan.</strong>',
'editingold'                => '<strong>KONTUZ: Artikulu honen bertsio zahar bat aldatzen ari zara. Gorde egiten baduzu, azkenengo aldaketa baino lehenagoko aldakuntzak, ezabatuak izango dira.</strong>',
'yourdiff'                  => 'Ezberdintasunak',
'copyrightwarning'          => 'Kontuan izan {{SITENAME}}(e)n egindako ekarpen guztiak $2 baldintzapean argitaratzen direla (ikus $1 informazio gehiagorako). Zure testua banatzeko baldintza hauekin ados ez bazaude, ez ezazu bidali.<br />
Era berean, bidaltzen ari zaren edukia zuk zeuk idatzitakoa dela edo jabetza publikoko edo baliabide aske batetik kopiatu duzula zin egin ari zara.
<strong>EZ BIDALI BAIMENIK GABEKO COPYRIGHTDUN EDUKIRIK!</strong>',
'copyrightwarning2'         => 'Mesedez, kontuan izan {{SITENAME}}(e)n egindako ekarpen guztiak besteek aldatu edo ezabatu ditzaketela. Ez baduzu besteek aldaketak egitea nahi, ez ezazu bidali.<br />
Era berean, bidaltzen ari zaren edukia zuk zeuk idatzitakoa dela edo jabetza publikoko edo baliabide aske batetik kopiatu duzula zin egin ari zara (ikus $1 informazio gehiagorako).
<strong>EZ BIDALI BAIMENIK GABEKO COPYRIGHTDUN EDUKIRIK!</strong>',
'longpagewarning'           => '<strong>OHARRA: Orrialde honek $1 kilobyteko tamaina du; nabigatzaile batzuek arazoak izan litzakete 32kb-tik gorako testuekin. Mesedez, saiatu orrialdea atal txikiagoetan banatzen.</strong>',
'longpageerror'             => '<strong>ERROREA: Bidali duzun testuak $1 kilobyteko luzera du, eta $2 kilobyteko maximoa baino luzeagoa da. Ezin da gorde.</strong>',
'readonlywarning'           => '<strong>OHARRA: Datu-basea blokeatu egin da mantenu lanak burutzeko, beraz ezingo dituzu orain zure aldaketak gorde. Testua fitxategi baten kopiatu dezakezu, eta beranduago erabiltzeko gorde.</strong>',
'protectedpagewarning'      => '<strong>OHARRA:  Orri hau blokeaturik dago, administratzaileek soilik eraldatu dezakete. Ikusi [[Project:Babestutako orria|Babestutako Orria]].</strong>',
'semiprotectedpagewarning'  => '<strong>Oharra: Orrialde hau erregistratutako erabiltzaileek bakarrik aldatzeko babestuta dago.</strong>',
'templatesused'             => 'Orrialde honetan erabiltzen diren txantiloiak:',
'edittools'                 => '<!-- Hemen jarritako testua aldaketa eta igoera formularioen azpian agertuko da. -->',
'nocreatetitle'             => 'Orrialdeak sortzea mugatuta',
'nocreatetext'              => 'Gune honek orrialde berriak sortzeko gaitasuna mugatu du. Atzera egin dezakezu existitzen den orrialde bat aldatzeko, edo [[Special:Userlogin|saio hasi edo kontua sortu]].',

# Account creation failure
'cantcreateaccounttitle' => 'Ezin izan da kontua sortu',
'cantcreateaccounttext'  => 'IP helbide honetatik (<b>$1</b>) izena emateko aukera blokeatu egin da. Baliteke zauden eskolan edo Interneteko Zerbitzu Hornitzailean gertatuko bandalismoren batengatik gertatzea hau.',

# History pages
'revhistory'          => 'Berrikuspenen historiala',
'viewpagelogs'        => 'Orrialde honen erregistroak ikusi',
'nohistory'           => 'Orrialde honek ez dauka aldaketa historiarik.',
'revnotfound'         => 'Ezin izan da berrikuspena aurkitu',
'revnotfoundtext'     => 'Ezin izan da eskatzen ari zaren orrialdearen berrikuspen zaharra aurkitu. Mesedez, egiaztatu orrialde honetara iristeko erabili duzun URLa.',
'loadhist'            => 'Orrialdearen historia kargatzen',
'currentrev'          => 'Oraingo berrikuspena',
'revisionasof'        => '$1(e)ko berrikuspena',
'previousrevision'    => '←Berrikuspen zaharragoa',
'nextrevision'        => 'Berrikuspen berriagoa→',
'currentrevisionlink' => 'Oraingo berrikuspena ikusi',
'cur'                 => 'orain',
'next'                => 'hurrengoa',
'last'                => 'azkena',
'orig'                => 'orij',
'histlegend'          => 'Diff hautapena: hautatu alderatu nahi dituzun bi bertsioak eta beheko botoian klik egin.<br />
Legenda: (orain) = oraingo bertsioarekiko ezberdintasuna,
(azkena) = aurreko bertsioarekiko ezberdintasuna, t = aldaketa txikia.',
'deletedrev'          => '[ezabatuta]',
'histfirst'           => 'Lehena',
'histlast'            => 'Azkena',

# Revision feed
'history-feed-title'          => 'Berrikuspenen historia',
'history-feed-description'    => 'Wikiko orrialde honen berrikuspenen historia',
'history-feed-item-nocomment' => 'nork: $1 noiz: $2', # user at time
'history-feed-empty'          => 'Eskatutako orrialdea ez da existitzen. Baliteke wikitik ezabatu edo izenez aldatu izana. Saiatu [[Special:Search|wikian zerikusia duten orrialdeak bilatzen]].',

# Revision deletion
'rev-deleted-comment'         => '(iruzkina ezabatu da)',
'rev-deleted-user'            => '(erabiltzailea ezabatu da)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Orrialdearen berrikuspen hau artxibo publikoetatik kendu da. Xehetasunak [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} ezabaketa erregistroan] ikus daitezke.
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Orrialdearen berrikuspen hau artxibo publikoetatik kendu da. Guneko administratzaile bezala ikusteko aukera daukazu ordea; xehetasunak [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} ezabaketa erregistroan] ikus ditzakezu.
</div>',
'rev-delundel'                => 'erakutsi/ezkutatu',
'revisiondelete'              => 'Berrikuspenak ezabatu/leheneratu',
'revdelete-nooldid-title'     => 'Helburu berrikuspenik ez',
'revdelete-nooldid-text'      => 'Ez d(it)uzu eragiketa hau burutzeko helburu berrikuspena(k) zehaztu.',
'revdelete-selected'          => '[[:$1]](r)en hautatutako berrikuspena:',
'revdelete-text'              => 'Ezabatutako berrikuspenek orrialdearen historian agertzen jarraituko dute, baina bere edukiak ez dira publikoki eskuratu ahal izango.

Wiki honetako beste administratzaileek ezkutuko eduki hau ikusteko aukera izango dute, eta baita leheneratzeko ere, gunearen arduradunek beste mugapenen bat ezartzen ez badute behintzat.',
'revdelete-legend'            => 'Berrikuspen mugapenak ezarri:',
'revdelete-hide-text'         => 'Berrikuspenaren testua ezkutatu',
'revdelete-hide-comment'      => 'Aldaketaren iruzkina ezkutatu',
'revdelete-hide-user'         => 'Egilearen erabiltzaile izena/IPa ezkutatu',
'revdelete-hide-restricted'   => 'Mugapen hauek administratzaileei zein besteei aplikatu',
'revdelete-log'               => 'Erregistroaren iruzkina:',
'revdelete-submit'            => 'Hautatutako berrikuspenari aplikatu',
'revdelete-logentry'          => '[[$1]](r)entzako berriskupen ikusgaitasuna aldatu da',

# Diffs
'difference'                => '(Bertsioen arteko ezberdintasunak)',
'loadingrev'                => 'diff-entzako berrikuspena eskuratzen',
'lineno'                    => '$1. lerroa:',
'editcurrent'               => 'Orrialdearen oraingo bertsioa aldatu',
'selectnewerversionfordiff' => 'Hautatu bertsio berriago bat konparaketa egiteko',
'selectolderversionfordiff' => 'Hautatu bertsio zaharrago bat konparaketa egiteko',
'compareselectedversions'   => 'Hautatutako bertsioak alderatu',

# Search results
'searchresults'         => 'Bilaketaren emaitzak',
'searchresulttext'      => '{{SITENAME}}(e)n bilaketak egiteko informazio gehiagorako, ikus [[Project:Bilaketa|{{SITENAME}}(e)n bilatzen]].',
'searchsubtitle'        => "'''[[:$1]]''' bilatu duzu",
'searchsubtitleinvalid' => "'''$1''' bilatu duzu",
'badquery'              => 'Gaizki osatutako bilaketa katea',
'badquerytext'          => 'Ezin izan dugu zure kontsulta burutu. Baliteke hau hiru letra baino laburragoa den hitz bat bilatzen saiatzeagatik izatea, eta hori ezin da egin. Litekeena da ere adierazpena gaizki idatzi izana, adibidez "euskal euskal herria". Saiatu beste kontsulta batekin mesedez.',
'matchtotals'           => '"$1" bilaketak $2 orrialde izenburu eta $3 orrialderen testu aurkitu ditu.',
'noexactmatch'          => "'''Ez dago \"\$1\" izenburua duen orrialderik.''' [[:\$1|Orrialde hau]] sortu dezakezu.",
'titlematches'          => 'Emaitzak artikuluen izenburuetan',
'notitlematches'        => 'Ez dago bat datorren orrialde izenbururik',
'textmatches'           => 'Emaitza orrialde testuetan',
'notextmatches'         => 'Ez dago bat datorren orrialde testurik',
'prevn'                 => 'aurreko $1ak',
'nextn'                 => 'hurrengo $1ak',
'viewprevnext'          => 'Ikusi ($1) ($2) ($3).',
'showingresults'        => 'Jarraian <b>$1</b> emaitz ikus daitezke, <b>$2</b>.etik hasita.',
'showingresultsnum'     => 'Hasieran #<b>$2</b> duten <b>$3</b> emaitza erakusten dira jarraian.',
'nonefound'             => "'''Oharra''': Arrakastarik gabeko bilaketen arrazoi nagusietako bat \"dute\" eta \"da\" bezalako hitz arruntak bilatzea izan ohi da, edo baita bilaketan hitz gehiegi zehazteagatik ere (emaitzetan hitz guztiak dituzten emaitzak baino ez dira azalduko).",
'powersearch'           => 'Bilatu',
'powersearchtext'       => 'Izen-tarte hauetan bilatu:<br />$1<br />$2 Birzuzenketen zerrenda<br />$3 $9 bilatu',
'searchdisabled'        => '{{SITENAME}}(e)n ezgaituta dago bilaketa. Dena dela, Google erabiliz ere egin dezakezu bilaketa. Kontuan izan bertan dituzten {{SITENAME}}(e)ko emaitzak zaharkituta egon daitezkeela.',
'blanknamespace'        => '(Nagusia)',

# Preferences page
'preferences'             => 'Hobespenak',
'mypreferences'           => 'Nire hobespenak',
'prefsnologin'            => 'Saioa hasi gabe',
'prefsnologintext'        => '[[Special:Userlogin|Saioa hasi behar duzu]] zure hobespenak ezartzeko.',
'prefsreset'              => 'Hobespenak hasieratu egin dira.',
'qbsettings'              => 'Laster-barra',
'qbsettings-none'         => 'Ezein ere',
'qbsettings-fixedleft'    => 'Eskuinean',
'qbsettings-fixedright'   => 'Ezkerrean',
'qbsettings-floatingleft' => 'Ezkerrean mugikor',
'changepassword'          => 'Pasahitza aldatu',
'skin'                    => 'Itxura',
'math'                    => 'Math',
'dateformat'              => 'Data formatua',
'datedefault'             => 'Hobespenik ez',
'datetime'                => 'Data eta ordua',
'math_failure'            => 'Interpretazio errorea',
'math_unknown_error'      => 'errore ezezaguna',
'math_unknown_function'   => 'funtzio ezezaguna',
'math_lexing_error'       => 'errore lexikoa',
'math_syntax_error'       => 'sintaxi errorea',
'math_image_error'        => 'PNG bilakatze errorea; egiaztatu latex, dvips, gs eta convert ongi instalatuta daudela',
'math_bad_tmpdir'         => 'Ezin da math direktorio tenporala sortu edo bertan idatzi',
'math_bad_output'         => 'Ezin da math direktorioa sortu edo bertan idatzi',
'math_notexvc'            => 'texvc exekutagarria falta da; mesedez, ikus math/README konfiguratzeko.',
'prefs-personal'          => 'Erabiltzaile profila',
'prefs-rc'                => 'Azken aldaketak',
'prefs-watchlist'         => 'Jarraipen zerrenda',
'prefs-watchlist-days'    => 'Jarraipen zerrendan erakutsi beharreko egun kopurua:',
'prefs-watchlist-edits'   => 'Jarraipen zerrendan erakutsi beharreko aldaketa kopurua:',
'prefs-misc'              => 'Denetarik',
'saveprefs'               => 'Gorde',
'resetprefs'              => 'Hasieratu',
'oldpassword'             => 'Pasahitz zaharra:',
'newpassword'             => 'Pasahitz berria:',
'retypenew'               => 'Pasahitz berria berriz idatzi:',
'textboxsize'             => 'Aldatzen',
'rows'                    => 'Lerroak:',
'columns'                 => 'Zutabeak:',
'searchresultshead'       => 'Bilaketa',
'resultsperpage'          => 'Emaitza orrialdeko:',
'contextlines'            => 'Lerro emaitzako:',
'contextchars'            => 'Lerro bakoitzeko karaktere kopurua:',
'recentchangescount'      => 'Aldaketa berrietako izenburu kopurua:',
'savedprefs'              => 'Zure hobespenak gorde egin dira.',
'timezonelegend'          => 'Ordu zona',
'timezonetext'            => 'Zure ordu lokala eta zerbitzariaren orduaren (UTC) arteko ezberdintasuna.',
'localtime'               => 'Ordu lokala',
'timezoneoffset'          => 'Ezberdintasuna¹',
'servertime'              => 'Zerbitzariko ordua',
'guesstimezone'           => 'Nabigatzailetik jaso',
'allowemail'              => 'Beste erabiltzaileengandik e-posta mezuak jasotzea gaitu',
'defaultns'               => 'Izen-tarte hauetan bilatu lehenetsitzat:',
'default'                 => 'lehenetsia',
'files'                   => 'Fitxategiak',

# User rights
'userrights-lookup-user'     => 'Erabiltzaile taldeak kudeatu',
'userrights-user-editname'   => 'Erabiltzaile izena idatzi:',
'editusergroup'              => 'Erabiltzaile taldeak editatu',
'userrights-editusergroup'   => 'Erabiltzaile taldeak editatu',
'saveusergroups'             => 'Erabiltzaile taldeak gorde',
'userrights-groupsmember'    => 'Partaide da hemen:',
'userrights-groupsavailable' => 'Existitzen diren taldeak:',
'userrights-groupshelp'      => 'Hautatu erabiltzaileari gehitu edo kendu nahi dizkiozun taldeak. Deshautatutako taldeak ez dira aldatuko. Talde bat deshautatu dezakezu CTRL + Ezker Klika eginez',

# Groups
'group'            => 'Taldea:',
'group-bot'        => 'Bot-ak',
'group-sysop'      => 'Administratzaileak',
'group-bureaucrat' => 'Burokratak',
'group-all'        => '(guztiak)',

'group-bot-member'        => 'Bot',
'group-sysop-member'      => 'Administratzaile',
'group-bureaucrat-member' => 'Burokrata',

'grouppage-bot'        => '{{ns:project}}:Bot-ak',
'grouppage-sysop'      => '{{ns:project}}:Administratzaileak',
'grouppage-bureaucrat' => '{{ns:project}}:Burokratak',

# User rights log
'rightslog'      => 'Erabiltzaile eskubideen erregistroa',
'rightslogtext'  => 'Erabiltzaile eskubideetan izandako aldaketen erregistroa da hau.',
'rightslogentry' => '$1(r)en partaidetza aldatu da $2(e)tik $3(e)ra',
'rightsnone'     => '(bat ere ez)',

# Recent changes
'recentchanges'                     => 'Aldaketa berriak',
'recentchangestext'                 => 'Orrialde honetan wiki honetan egindako azken aldaketak erakusten dira.',
'rcnote'                            => 'Jarraian azken <strong>$2</strong> egunetako azken <strong>$1</strong> aldaketak erakusten dira, $3 eguneratuta.',
'rcnotefrom'                        => 'Jarraian azaltzen diren aldaketak data honetatik aurrerakoak dira: <b>$2</b> (gehienez <b>$1</b> erakusten dira).',
'rclistfrom'                        => 'Erakutsi $1 ondorengo aldaketa berriak',
'rcshowhideminor'                   => '$1 aldaketa txikiak',
'rcshowhidebots'                    => '$1 bot-ak',
'rcshowhideliu'                     => '$1 erabiltzaile erregistratuak',
'rcshowhideanons'                   => '$1 anonimoak',
'rcshowhidepatr'                    => '$1 patruilatutako aldaketak',
'rcshowhidemine'                    => '$1 nire aldaketak',
'rclinks'                           => 'Erakutsi azken $1 aldaketak $2 egunetan.<br>$3',
'diff'                              => 'ezb',
'hist'                              => 'hist',
'hide'                              => 'Ezkutatu',
'show'                              => 'Erakutsi',
'minoreditletter'                   => 't',
'newpageletter'                     => 'B',
'boteditletter'                     => 'b',
'sectionlink'                       => '→',
'number_of_watching_users_pageview' => '[$1 jarraitzaile]',
'rc_categories'                     => 'Kategorietara mugatu ("|" karaktereaz banandu)',
'rc_categories_any'                 => 'Edozein',

# Recent changes linked
'recentchangeslinked' => 'Lotutako orrialdeen aldaketak',

# Upload
'upload'                      => 'Fitxategia igo',
'uploadbtn'                   => 'Fitxategia igo',
'reupload'                    => 'Berriz igo',
'reuploaddesc'                => 'Igotzeko formulariora itzuli.',
'uploadnologin'               => 'Saioa hasi gabe',
'uploadnologintext'           => 'Fitxategiak igotzeko [[Special:Userlogin|saioa hasi]] behar duzu.',
'upload_directory_read_only'  => 'Web zerbitzariak ez dauka igoera direktorioan ($1) idazteko baimenik.',
'uploaderror'                 => 'Errorea igotzerakoan',
'uploadtext'                  => "Fitxategiak igotzeko beheko formularioa erabil dezakezu. Aurretik igotako irudiak ikusi edo bilatzeko [[Special:Imagelist|igotako fitxategien zerrendara]] jo. Igoerak eta ezabatutakoak [[Special:Log/upload|igoera erregistroan]] zerrendatzen dira.

Orrialde baten irudi bat txertatzeko, erabili kode hauetako bat:
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Fitxategia.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Fitxategia.png|testu alternatiboa]]</nowiki>''' edo
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fitxategia.ogg]]</nowiki>''' irudia zuzenean erabiltzeko.",
'uploadlog'                   => 'igoera erregistroa',
'uploadlogpage'               => 'Igoera erregistroa',
'uploadlogpagetext'           => 'Jarraian azken igoeren zerrenda ikus daiteke.',
'filename'                    => 'Fitxategi izena',
'filedesc'                    => 'Laburpena',
'fileuploadsummary'           => 'Laburpena:',
'filestatus'                  => 'Copyright egoera',
'filesource'                  => 'Iturria',
'uploadedfiles'               => 'Igotako fitxategiak',
'ignorewarning'               => 'Oharra ezikusi eta fitxategia gorde.',
'ignorewarnings'              => 'Edozein ohar ezikusi.',
'minlength'                   => 'Fitxategi izenak hiru karaktere izan behar ditu gutxienez.',
'illegalfilename'             => '"$1" fitxategiaren izenak orrialdeen izenburuetan erabili ezin diren karaktereak ditu. Mesedez, fitxategiari izena aldatu eta saiatu berriz igotzen.',
'badfilename'                 => 'Irudiaren izena aldatu da: "$1".',
'largefileserver'             => 'Fitxategi hau zerbitzariak baimentzen duena baino handiagoa da.',
'emptyfile'                   => 'Badirudi igotzen ari zaren fitxategia hutsik dagoela. Mesedez, egiaztatu fitxategi hori dela igo nahi duzuna.',
'fileexists'                  => 'Badago izen hori daukan fitxategi bat; mesedez, ikusi existitzen den $1 fitxategia aldatu nahi duzun egiaztatzeko.',
'fileexists-forbidden'        => 'Badago izen hori daukan fitxategi bat; mesedez, atzera itzuli eta igo fitxategia izen ezberdin batekin. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Badago izen hori daukan fitxategi bat elkarbanatutako fitxategi-biltegian; mesedez, atzera itzuli eta igo fitxategia izen ezberdin batekin. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Igoera arrakastatsua',
'fileuploaded'                => '$1 fitxategia igo da. Mesedez, jarraitu hurrengo lotura hau: jo $2 deskribapen orrialdera eta fitxategiaren inguruko informazioa osatu, hala nola bere jatorria, noiz sortu den eta nork, eta fitxategiaren inguruan jakin beharko litzatekeen beste edozein datu. Hau irudi bat bada, honela txertatu dezakezu: <tt><nowiki>[[Image:$1|thumb|Deskribapena]]</nowiki></tt>',
'uploadwarning'               => 'Igotzeko oharra',
'savefile'                    => 'Fitxategia gorde',
'uploadedimage'               => '"[[$1]]" igo da',
'uploaddisabled'              => 'Igoerak ezgaituta daude',
'uploaddisabledtext'          => 'Fitxategiak igotzea ezgaituta dago wiki honetan.',
'uploadscripted'              => 'Fitxategi honek web zerbitzariak modu ezegokian interpretatu lezakeen HTML edo script kodea dauka.',
'uploadcorrupt'               => 'Fitxategiak akatsak ditu edo luzapena ezegokia da. Mesedez, egiaztatu fitxategia ondo dagoela eta berriz igo.',
'uploadvirus'                 => 'Fitxategiak birusa dauka! Xehetasunak: $1',
'sourcefilename'              => 'Iturri-fitxategiaren izena',
'destfilename'                => 'Helburu fitxategi izena',
'watchthisupload'             => 'Orrialde hau jarraitu',
'filewasdeleted'              => 'Izen hau duen fitxategi bat igo eta ezabatu da jada. $1 aztertu beharko zenuke berriz igo aurretik.',

'license'            => 'Lizentzia',
'nolicense'          => 'Hautatu gabe',
'upload_source_url'  => ' (baliozko URL publikoa)',
'upload_source_file' => ' (zure ordenagailuko fitxategi bat)',

# Image list
'imagelist'                 => 'Fitxategien zerrenda',
'imagelisttext'             => "Jarraian duzu $2(e)z ordenatutako {{plural:$1|fitxategi baten|'''$1''' fitxategiren}} zerrenda.",
'imagelistforuser'          => 'Honek $1(e)k igotako irudiak bakarrik erakusten ditu.',
'getimagelist'              => 'fitxategi zerrenda jasotzen',
'ilsubmit'                  => 'Bilatu',
'showlast'                  => 'Erakutsi azken $1 fitxategiak $2 ordenatuta.',
'byname'                    => 'izenaren arabera',
'bydate'                    => 'dataren arabera',
'bysize'                    => 'tamainaren arabera',
'imgdelete'                 => 'ezb',
'imgdesc'                   => 'desk',
'imgfile'                   => 'fitxategia',
'imglegend'                 => 'Legenda: (desk) = fitxategiaren deskribapena erakutsi/aldatu.',
'imghistory'                => 'Fitxategiaren historia',
'revertimg'                 => 'des',
'deleteimg'                 => 'ezb',
'deleteimgcompletely'       => 'Fitxategi honen bertsio guztiak ezabatu',
'imghistlegend'             => 'Legenda: (orain) = oraingo fitxategia, (ezab) = ezabatu bertsio zahar hau, (ber) = bertsio zahar honetara itzuli. <br /><i>Dataren gainean klik egin egun hartan igotako fitxategia ikusteko</i>.',
'imagelinks'                => 'Loturak',
'linkstoimage'              => 'Hurrengo orrialdeek dute fitxategi honetarako lotura:',
'nolinkstoimage'            => 'Ez dago fitxategi honetara lotura egiten duen orrialderik.',
'sharedupload'              => 'Fitxategi hau elkarbanatutako igoera bat da eta beste proiektuek ere erabil dezakete.',
'shareduploadwiki'          => 'Informazio gehiagorako $1 ikusi mesedez.',
'shareduploadwiki-linktext' => 'fitxategiaren deskribapen orrialdea',
'noimage'                   => 'Ez dago fitxategirik izen honekin, $1 dezakezu nahi baduzu.',
'noimage-linktext'          => 'igo egin',
'uploadnewversion-linktext' => 'Fitxategi honen bertsio berri bat igo',
'imagelist_date'            => 'Data',
'imagelist_name'            => 'Izena',
'imagelist_user'            => 'Erabiltzailea',
'imagelist_size'            => 'Tamaina (byte)',
'imagelist_description'     => 'Deskribapena',
'imagelist_search_for'      => 'Irudiaren izenagatik bilatu:',

# MIME search
'mimesearch' => 'MIME bilaketa',
'mimetype'   => 'MIME mota:',
'download'   => 'deskargatu',

# Unwatched pages
'unwatchedpages' => 'Jarraitu gabeko orrialdeak',

# List redirects
'listredirects' => 'Birzuzenketen zerrenda',

# Unused templates
'unusedtemplates'     => 'Erabili gabeko txantiloiak',
'unusedtemplatestext' => 'Orrialde honetan beste edozein orrialdetan erabiltzen ez diren txantiloi izen-tarteko orrialdeak zerrendatzen dira. Ez ahaztu txantiloietara egon daitezkeen loturak egiaztatzeaz ezabatu aurretik.',
'unusedtemplateswlh'  => 'beste loturak',

# Random redirect
'randomredirect' => 'Ausazko birzuzenketa',

# Statistics
'statistics'             => 'Estatistikak',
'sitestats'              => '{{SITENAME}}(e)ko estatistikak',
'userstats'              => 'Erabiltzaile estatistikak',
'sitestatstext'          => "Datu-basean guztira '''\$1''' orrialde daude.
Kopuru horretan \"eztabaida\" orrialdeak, {{SITENAME}}(r)i buruzko orrialdeak, zirriborroak, birzuzenketak eta eduki orrialde bezala kontsideratu ezin diren beste batzuk ere kontuan hartzen dira. Horiek baztertuz, '''\$2''' orrialde daude ziurrenik edukia daukatenak. 

'''\$8''' fitxategi igo dira.

Guztira '''\$3''' orrialde irakurketa egon dira, eta '''\$4''' orrialde aldaketa wikia abian jarri zenez geroztik.
Horren arabera, '''\$5''' aldaketa egin dira orrialde bakoitzeko bataz beste, eta aldaketa bakoitzeko '''\$6''' irakurketa egin dira.

[http://meta.wikimedia.org/wiki/Help:Job_queue Atazen zerrendaren] luzera '''\$7'''(e)koa da.",
'userstatstext'          => "'''$1''' erabiltzaile daude izen emanda, horietatik '''$2''' (edo '''$4%''') $5 direlarik.",
'statistics-mostpopular' => 'Orrialde bisitatuenak',

'disambiguations'     => 'Argipen orrialdeak',
'disambiguationspage' => 'Template:argipen',

'doubleredirects'     => 'Birzuzenketa bikoitzak',
'doubleredirectstext' => 'Lerro bakoitzean lehen eta bigarren birzuzenketetarako loturak ikus daitezke, eta baita edukia daukan edo eduki beharko lukeen orrialderako lotura ere. Lehen birzuzenketak azken honetara zuzendu beharko luke.',

'brokenredirects'     => 'Hautsitako birzuzenketak',
'brokenredirectstext' => 'Jarraian zerrendatutako birzuzenketak existitzen ez diren orrialdeetara zuzenduta daude:',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|byte 1|$1 byte}}',
'ncategories'             => '{{PLURAL:$1|kategoria 1|$1 kategoria}}',
'nlinks'                  => '{{PLURAL:$1|lotura 1|$1 lotura}}',
'nmembers'                => '{{PLURAL:$1|partaide 1|$1 partaide}}',
'nrevisions'              => '{{PLURAL:$1|berrikuspen 1|$1 berrikuspen}}',
'nviews'                  => '{{PLURAL:$1|ikusketa 1|$1 ikusketa}}',
'lonelypages'             => 'Orrialde umezurtzak',
'lonelypagestext'         => 'Jarraian zerrendatutako orrialdeek ez daukate wikiko beste orrialdeetatik loturarik.',
'uncategorizedpages'      => 'Kategorizatu gabeko orrialdeak',
'uncategorizedcategories' => 'Kategorizatu gabeko kategoriak',
'uncategorizedimages'     => 'Kategorizatu gabeko irudiak',
'unusedcategories'        => 'Erabili gabeko kategoriak',
'unusedimages'            => 'Erabili gabeko fitxategiak',
'popularpages'            => 'Orrialde bisitatuenak',
'wantedcategories'        => 'Eskatutako kategoriak',
'wantedpages'             => 'Eskatutako orrialdeak',
'mostlinked'              => 'Gehien lotutako orrialdeak',
'mostlinkedcategories'    => 'Gehien lotutako kategoriak',
'mostcategories'          => 'Sailkapenean kategoria gehien dituzten orrialdeak',
'mostimages'              => 'Gehien lotutako irudiak',
'mostrevisions'           => 'Berrikuspen gehien dituzten orrialdeak',
'allpages'                => 'Orrialde guztiak',
'prefixindex'             => 'Aurrizkien aurkibidea',
'randompage'              => 'Ausazko orrialdea',
'shortpages'              => 'Orrialde laburrak',
'longpages'               => 'Orrialde luzeak',
'deadendpages'            => 'Orrialde itsuak',
'deadendpagestext'        => 'Jarraian zerrendatutako orrialdeek ez daukate wikiko beste edozein orrialdetarako loturarik.',
'listusers'               => 'Erabiltzaileen zerrenda',
'specialpages'            => 'Aparteko orrialdeak',
'spheading'               => 'Erabiltzaile guztientzako aparteko orrialdeak',
'restrictedpheading'      => 'Mugatutako aparteko orrialdeak',
'rclsub'                  => '("$1"(e)tik lotutako orrialdeetara)',
'newpages'                => 'Orrialde berriak',
'newpages-username'       => 'Erabiltzaile izena:',
'ancientpages'            => 'Orrialde zaharrenak',
'intl'                    => 'Hizkuntzen arteko loturak',
'move'                    => 'Move',
'movethispage'            => 'Orrialde hau mugitu',
'unusedimagestext'        => '<p>Mesedez, kontuan izan beste webgune batzutatik URL zuzena erabiliz lotura izan dezaketela irudira, eta kasu horretan ez lirateke hemengo zerrendetan azalduko.</p>',
'unusedcategoriestext'    => 'Hurrengo kategoria orrialde guztiak datu-basean existitzen dira, baina ez du inongo orrialde edo kategoriak erabiltzen.',

# Book sources
'booksources'      => 'Iturri liburuak',
'booksources-text' => 'Jarraian liburu berri eta erabiliak saltzen dituzten guneetarako loturen zerrenda bat ikus dezakezu, bilatzen ari zaren liburu horientzako informazio gehigarria aurkitzeko lagungarria izan daitekeena:',

'categoriespagetext' => 'Hurrengo kategoriak daude wiki honetan:',
'data'               => 'Datuak',
'userrights'         => 'Erabiltzaile baimenen kudeaketa',
'groups'             => 'Erabiltzaile taldeak',
'alphaindexline'     => '$1(e)tik $2(e)raino',
'version'            => 'Bertsioa',

# Special:Log
'specialloguserlabel'  => 'Lankidea:',
'speciallogtitlelabel' => 'Izenburua:',
'log'                  => 'Erregistroak',
'alllogstext'          => 'Igoera, ezabaketa, babes, blokeaketa eta administratzaile erregistroen erakusketa. Zerrenda mugatu dezakezu erregistro mota, erabiltzaile izena edo eragindako orrialdea aukeratuz.',
'logempty'             => 'Ez dago emaitzarik erregistroan.',

# Special:Allpages
'nextpage'          => 'Hurrengo orrialdea ($1)',
'allpagesfrom'      => 'Honela hasten diren orrialdeak erakutsi:',
'allarticles'       => 'Artikulu guztiak',
'allinnamespace'    => 'Orrialde guztiak ($1 izen-tartea)',
'allnotinnamespace' => 'Orrialde guztiak ($1 izen-tartean ez daudenak)',
'allpagesprev'      => 'Aurrekoa',
'allpagesnext'      => 'Hurrengoa',
'allpagessubmit'    => 'Joan',
'allpagesprefix'    => 'Aurrizki hau duten orrialdeak bistaratu:',
'allpagesbadtitle'  => 'Orrialdearen izena baliogabekoa da edo interwiki edo hizkuntzen arteko aurrizkia dauka. Izenburuetan erabili ezin daitezkeen karaktere bat edo gehiago izan ditzake.',

# Special:Listusers
'listusersfrom' => 'Hemendik aurrerako erabiltzaileak bistaratu:',

# E-mail user
'mailnologin'     => 'Bidalketa helbiderik ez',
'mailnologintext' => 'Beste erabiltzaileei e-posta mezuak bidaltzeko [[Special:Userlogin|saioa hasi]] eta baliozko e-posta helbidea behar duzu izan zure [[Special:Preferences|hobespenetan]].',
'emailuser'       => 'Erabiltzaile honi e-posta bidali',
'emailpage'       => 'Erabiltzaileari e-posta bidali',
'emailpagetext'   => 'Erabiltzaile honek baliozko e-posta helbide bat ezarri badu bere hobespenetan, beheko formularioa erabiliz mezu bat bidal dakioke. Hobespenetan daukazun e-posta helbidea azalduko da mezuaren bidaltzaile bezala eta beraz erantzun ahal izango dizu.',
'usermailererror' => 'Mail objektuak errore hau itzuli du:',
'defemailsubject' => 'E-posta {{SITENAME}}(e)tik',
'noemailtitle'    => 'Posta helbiderik ez',
'noemailtext'     => 'Erabiltzaile honek ez du baliozko posta helbiderik zehaztu edo beste erabiltzaileengandik mezurik ez jasotzea aukeratu du.',
'emailfrom'       => 'Nork',
'emailto'         => 'Nori',
'emailsubject'    => 'Gaia',
'emailmessage'    => 'Mezua',
'emailsend'       => 'Mezua',
'emailsent'       => 'Mezua bidali egin da',
'emailsenttext'   => 'Zure e-posta mezua bidali egin da.',

# Watchlist
'watchlist'            => 'Nire jarraipen zerrenda',
'mywatchlist'          => 'Nire jarraipen zerrenda',
'watchlistfor'         => "('''$1''')",
'nowatchlist'          => 'Zure jarraipen zerrenda hutsik dago.',
'watchlistanontext'    => 'Mesedez $1 zure jarraipen zerrendako orrialdeak ikusi eta aldatu ahal izateko.',
'watchlistcount'       => "'''$1 elementu dituzu zure jarraipen zerrendan, eztabaida orrialdeak barne.'''",
'clearwatchlist'       => 'Jarraipen zerrenda garbitu',
'watchlistcleartext'   => 'Ziur zaude ezabatu nahi dituzula?',
'watchlistclearbutton' => 'Jarraipen zerrenda garbitu',
'watchlistcleardone'   => 'Zure jarraipen zerrenda garbitu egin da. $1 elementu ezabatu dira.',
'watchnologin'         => 'Saioa hasi gabe',
'watchnologintext'     => '[[Special:Userlogin|Saioa hasi]] behar duzu zure jarraipen zerrenda aldatzeko.',
'addedwatch'           => 'Jarraipen zerrendan gehitu da',
'addedwatchtext'       => "\"\$1\" orrialdea zure [[Special:Watchlist|jarraipen edo zelatatuen zerrendara]] erantsi da. Orrialde honen hurrengo aldaketak zerrenda horretan ageriko dira aurrerantzean, eta gainera [[Special:Recentchanges|aldaketa berrien zerrendan]] beltzez ageriko da, erraztasunez antzeman ahal izateko.

Jarraipen zerrendatik artikulua kentzeko, artikuluan ''ez jarraitu''ri eman.",
'removedwatch'         => 'Jarraipen zerrendatik ezabatuta',
'removedwatchtext'     => '"[[:$1]]" orrialdea zure jarraipen zerrendatik kendu da.',
'watch'                => 'Jarraitu',
'watchthispage'        => 'Orrialde hau jarraitu',
'unwatch'              => 'Ez jarraitu',
'unwatchthispage'      => 'Jarraitzeari utzi',
'notanarticle'         => 'Ez da eduki orrialdea',
'watchnochange'        => 'Hautatutako denbora tartean ez da aldaketarik izan zure jarraipen zerrendako orrialdeetan.',
'watchdetails'         => '* $1 orrialde jarraitzen, eztabaida orrialdeak kontuan hartu gabe
* [[Special:Watchlist/edit|Jarraipen zerrenda osoa erakutsi eta editatu]]
* [[Special:Watchlist/clear|Orrialde guztiak kendu]]',
'wlheader-enotif'      => '* Posta bidezko ohartarazpena gaituta dago.',
'wlheader-showupdated' => "* Bisitatu zenituen azken alditik aldaketak izan dituzten orrialdeak '''beltzez''' nabarmenduta daude",
'watchmethod-recent'   => 'Aldaketa berriak aztertzen jarraipen zerrendako orrialdeen bila',
'watchmethod-list'     => 'jarraipen zerrendako orrialdeak aldaketa berrien bila aztertzen',
'removechecked'        => 'Hautatutakoak jarraipen zerrendatik ezabatu',
'watchlistcontains'    => 'Zure jarraipen zerrendak $1 orrialde ditu.',
'watcheditlist'        => "Hona hemen jarraitzen ari zaren orrialdeen zerrenda alfabetikoa. Zerrendatik kendu nahi dituzun orrialdeak hautatu eta 'hautatutakoak ezabatu' botoian klik egin (eduki orrialde bat kentzeak bere eztabaida orrialdea kentzea ere suposatzen du, eta alderantziz).",
'removingchecked'      => 'Jarraipen zerrendatik eskatutakoak ezabatzen...',
'couldntremove'        => "Ezin izan da '$1' ezabatu...",
'iteminvalidname'      => "Arazoa '$1' elementuarekin, baliogabeko izena...",
'wlnote'               => "Jarraian ikus daitezke azken '''$2''' egunetako azken $1 aldaketak.",
'wlshowlast'           => 'Erakutsi azken $1 orduak $2 egunak $3',
'wlsaved'              => 'Honako hau zure jarraipen zerrendaren gordetako bertsio bat da.',
'wldone'               => 'Egina.',

'enotif_mailer'      => '{{SITENAME}}(e)ko Oharpen Postaria',
'enotif_reset'       => 'Orrialde guztiak bisitatu bezala markatu',
'enotif_newpagetext' => 'Honako hau orrialde berria da.',
'changed'            => 'aldatu',
'created'            => 'sortu',
'enotif_subject'     => '{{SITENAME}}(e)ko $PAGETITLE orrialdea $PAGEEDITOR(e)k $CHANGEDORCREATED du',
'enotif_lastvisited' => 'Jo $1 orrialdera zure azken bisitaz geroztik izandako aldaketa guztiak ikusteko.',
'enotif_body'        => 'Kaixo $WATCHINGUSERNAME,

{{SITENAME}}(e)ko $PAGETITLE orrialdea $CHANGEDORCREATED egin du $PAGEEDITOR(e)k une honetan: $PAGEEDITDATE, ikus $PAGETITLE_URL azken bertsiorako.

$NEWPAGE

Egilearen laburpena: $PAGESUMMARY $PAGEMINOREDIT

Egilearekin harremanetan jarri:
e-posta: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Ez dira oharpen gehiago bidaliko orrialde hau berriz bisitatzen ez baduzu. Horrez gain, orrialdeen oharpen konfigurazioa leheneratu dezakezu jarraipen zerrendatik.

             {{SITENAME}}(e)ko oharpen sistema

--
Zure jarraipen zerrendako konfigurazioa aldatzeko, ikus
{{fullurl:{{ns:special}}:Watchlist/edit}}

Laguntza:
{{fullurl:{{MediaWiki:helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Orrialdea ezabatu',
'confirm'                     => 'Baieztatu',
'excontent'                   => "edukia honakoa zen: '$1'",
'excontentauthor'             => "edukia honakoa zen: '$1' (parte hartu duen lankide bakarra: '$2')",
'exbeforeblank'               => "hustu aurreko edukiera: '$1'",
'exblank'                     => 'orrialdea hutsik zegoen',
'confirmdelete'               => 'Ezabaketa baieztatu',
'deletesub'                   => '("$1" ezabatzen)',
'historywarning'              => 'Kontuz! Ezabatuko duzun orrialdeak honako historia du:',
'confirmdeletetext'           => 'Orrialde edo irudi bat eta beste historia guztia datu-basetik ezabatzear zaude. Mesedez, egiaztatu hori egin nahi duzula, ondorioak zeintzuk diren badakizula, eta [[{{MediaWiki:policy-url}}|politikak]] errespetatuz egingo duzula.',
'actioncomplete'              => 'Ekintza burutu da',
'deletedtext'                 => '"$1" ezabatu egin da. Ikus $2 azken ezabaketen erregistroa ikusteko.',
'deletedarticle'              => '"[[$1]]" ezabatu da',
'dellogpage'                  => 'Ezabaketa erregistroa',
'dellogpagetext'              => 'Behean ikus daiteke azken ezabaketen zerrenda.',
'deletionlog'                 => 'ezabaketa erregistroa',
'reverted'                    => 'Lehenagoko berrikuspen batera itzuli da',
'deletecomment'               => 'Ezabatzeko arrazoia',
'imagereverted'               => 'Lehenagoko bertsiora leheneratu egin da.',
'rollback'                    => 'Aldaketak desegin',
'rollback_short'              => 'Desegin',
'rollbacklink'                => 'desegin',
'rollbackfailed'              => 'Desegiteak huts egin dud',
'cantrollback'                => 'Ezin da aldaketa desegin; erabiltzaile bakarrak hartu du parte.',
'alreadyrolled'               => 'Ezin da [[User:$2|$2]](e)k ([[User talk:$2|Eztabaida]]) [[$1]](e)n egindako azken aldaketa desegin; beste norbaitek editatu du edo jada desegin du. Azken aldaketa [[User:$3|$3]](e)k ([[User talk:$3|Eztabaida]]) egin du.',
'editcomment'                 => 'Aldaketaren iruzkina: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => '$2ren aldaketak ezabatu dira, $1ren azken bertsiora itzuliz.',
'sessionfailure'              => 'Badirudi saioarekin arazoren bat dagoela; bandalismoak saihesteko ekintza hau ezeztatu egin da. Mesedez, nabigatzaileko "atzera" botoian klik egin, hona ekarri zaituen orrialde hori berriz kargatu, eta saiatu berriz.',
'protectlogpage'              => 'Babes erregistroa',
'protectlogtext'              => 'Orrialdeen blokeo eta desblokeo zerrenda azaltzen da jarraian.',
'protectedarticle'            => '"[[$1]]" babestu da"',
'unprotectedarticle'          => '"[[$1]]"(r)i babesa kendu zaio',
'protectsub'                  => '("$1" babesten)',
'confirmprotect'              => 'Babesa baieztatu',
'protectcomment'              => 'Babesteko arrazoia',
'unprotectsub'                => '(babesa kentzen: "$1")',
'protect-unchain'             => 'Mugitzeko blokeoa kendu',
'protect-text'                => '<strong>$1</strong> orrialdearen babes maila ikusi eta aldatu egin beharko zenuke.',
'protect-default'             => '(lehenetsia)',
'protect-level-autoconfirmed' => 'Erregistratu gabeko erabiltzaileak blokeatu',
'protect-level-sysop'         => 'Administratzaileak bakarrik',

# Restrictions (nouns)
'restriction-edit' => 'Aldatu',
'restriction-move' => 'Mugitu',

# Undelete
'undelete'                 => 'Ezabatutako orrialdeak ikusi',
'undeletepage'             => 'Ezabatutako orrialdeak ikusi eta leheneratu',
'viewdeletedpage'          => 'Ezabatutako orrialdeak ikusi',
'undeletepagetext'         => 'Jarraian zerrendatzen diren orrialdeak ezabatu egin dira baina oraindik artxiboa gordeta daude eta leheneratu egin daitezke. Artxiboa noizean behin hustu egin liteke.',
'undeleteextrahelp'        => "Orrialde osoa leheneratzeko, koadrotxo guztiak hautatu gabe utzi eta '''''Leheneratu'''''n klik egin. Aukeratutako leheneratze bat burutzeko, leheneratu nahi dituzun berrikuspenen koadrotxoak markatu eta '''''Leheneratu''''' klik egin. '''''Hasieratu'''''n klik eginez gero koadrotxo guztiak eta iruzkin koadroa hustu egingo dira.",
'undeleterevisions'        => '$1 berrikuspen gordeta',
'undeletehistory'          => 'Orrialdea leheneratzen baduzu, berrikuspena guztiak leheneratuko dira historian. Ezabatu ondoren izen berdina duen orrialde berri bat sortzen bada leheneratutako berrikuspenak azalduko dira historian, eta oraingo berrikuspena ez da automatikoki ordezkatuko.',
'undeletehistorynoadmin'   => 'Artikulua ezabatu egin da. Ezabatzeko azalpena beheko laburpenean erakusten da, ezabatu aurretik parte hartu zuten erabiltzaileen xehetasunekin batera. Ezabatutako berrikuspenen oraingo testua administratzaileek bakarrik ikus dezakete.',
'undeletebtn'              => 'Leheneratu',
'undeletereset'            => 'Hasieratu',
'undeletecomment'          => 'Iruzkina:',
'undeletedarticle'         => '"[[$1]]" leheneratu da',
'undeletedrevisions'       => '$1 berrikuspen leheneratu dira',
'undeletedrevisions-files' => '$1 berrikuspen eta $2 fitxategi leheneratu dira',
'undeletedfiles'           => '$1 fitxategi leheneratu dira',
'cannotundelete'           => 'Errorea birsortzerakoan; baliteke beste norbaitek lehenago birsortu izana.',
'undeletedpage'            => "<big>'''$1 leheneratu egin da'''</big>

[[Special:Log/delete|Ezabaketa erregistrora]] jo azken ezabaketa eta leheneraketak ikusteko.",

# Namespace form on various pages
'namespace' => 'Izen-tartea:',
'invert'    => 'Hautapena alderanztu',

# Contributions
'contributions' => 'Lankidearen ekarpenak',
'mycontris'     => 'Nire ekarpenak',
'contribsub2'   => '$1 ($2)',
'nocontribs'    => 'Ez da ezaugarri horiekin bat datorren aldaketarik aurkitu.',
'ucnote'        => 'Behean agertzen dira erabiltzaile honen azken <b>$1</b> aldaketak azken <b>$2</b> egunetan.',
'uclinks'       => 'Azken $1 aldaketak ikusi; azken $2 egunak ikusi.',
'uctop'         => ' (Azken aldaketa)',

'sp-contributions-newest'      => 'Berriena',
'sp-contributions-oldest'      => 'Zaharrena',
'sp-contributions-newer'       => '$1 berriago',
'sp-contributions-older'       => '$1 zaharrago',
'sp-contributions-newbies-sub' => 'Hasiberrientzako',

'sp-newimages-showfrom' => 'Irudi berriak erakutsi $1(e)tik hasita',

# What links here
'whatlinkshere'        => 'Honekin lotzen diren orrialdeak',
'whatlinkshere-barrow' => '&lt;',
'notargettitle'        => 'Helburu orrialderik ez',
'notargettext'         => 'Ez duzu eragiketa hau burutzeko helburu orrialde edo erabiltzaile bat zehaztu.',
'linklistsub'          => '(Loturen zerrenda)',
'linkshere'            => "Hurrengoek dute '''[[:$1]]''' orrialderako lotura:",
'nolinkshere'          => "Ez dago '''[[:$1]]''' lotura duen orrialderik.",
'isredirect'           => 'berbideraketa orrialdea',
'istemplate'           => 'erabilpena',

# Block/unblock
'blockip'                     => 'Erabiltzailea blokeatu',
'blockiptext'                 => 'IP helbide edo erabiltzaile izen bati idazketa baimenak kentzeko beheko formularioa erabil dezakezu. Ekintza hau bandalismoa saihesteko baino ez da burutu behar, eta beti ere [[{{MediaWiki:policy-url}}|politikak]] errespetatuz. Blokeoaren arrazoi bat ere zehaztu ezazu (adibidez, orrialde batzuk zehaztuz).',
'ipaddress'                   => 'IP Helbidea',
'ipadressorusername'          => 'IP Helbidea edo erabiltzaile izena',
'ipbexpiry'                   => 'Iraungipena',
'ipbreason'                   => 'Arrazoia',
'ipbanononly'                 => 'Erabiltzaile anonimoak bakarrik blokeatu',
'ipbcreateaccount'            => 'Kontua sortzea debekatu',
'ipbsubmit'                   => 'Erabiltzaile hau blokeatu',
'ipbother'                    => 'Beste denbora-tarte bat',
'ipboptions'                  => '15 minutu:15 minutes,30 minutu:30 minutes,ordu 1:1 hour,2 ordu:2 hours,egun bat:1 day,3 egun:3 days,aste 1:1 week,2 aste:2 weeks,hilabete 1:1 month,betirako:infinite',
'ipbotheroption'              => 'beste bat',
'badipaddress'                => 'Baliogabeko IP helbidea',
'blockipsuccesssub'           => 'Blokeoa burutu da',
'blockipsuccesstext'          => '[[{{ns:Special}}:Contributions/$1|$1]] erabiltzaileari blokeoa ezarri zaio. Ikus [[{{ns:Special}}:Ipblocklist|IP blokeoen zerrenda]] blokeoak aztertzeko.',
'unblockip'                   => 'Erabiltzailea desblokeatu',
'unblockiptext'               => 'Erabili beheko formularioa lehenago blokeatutako IP helbide edo erabiltzaile baten idazketa baimenak leheneratzeko.',
'ipusubmit'                   => 'Helbide hau desblokeatu',
'unblocked'                   => '[[User:$1|$1]] desblokeatu egin da',
'ipblocklist'                 => 'Blokeatutako IP helbide eta erabiltzaileen zerrenda',
'blocklistline'               => '$1, $2(e)k $3 blokeatu du (iraungipena: $4)',
'infiniteblock'               => 'infinitu',
'expiringblock'               => 'iraungipen data: $1',
'anononlyblock'               => 'anon. soilik',
'createaccountblock'          => 'kontua sortzea blokeatuta',
'blocklink'                   => 'blokeatu',
'unblocklink'                 => 'blokeoa kendu',
'contribslink'                => 'ekarpenak',
'autoblocker'                 => '"[[User:$1|$1]]"(e)k berriki erabili duen IP helbidea duzulako autoblokeatu zaizu. $1(e)k emandako arrazoia zera da: "\'\'\'$2\'\'\'"',
'blocklogpage'                => 'Blokeo erregistroa',
'blocklogentry'               => '"[[User:$1|$1]]" $2(e)ko iraungipenarekin blokeatu da.',
'blocklogtext'                => 'Erabiltzaileen blokeoen ezarpen eta ezabaketen erregistroa da hau. Ez dira automatikoki blokeatutako IP helbideak zerrendatzen. Ikus [[Special:Ipblocklist|IP blokeoen zerrenda]] aktibo dauden blokeoak aztertzeko.',
'unblocklogentry'             => '$1 desblokeatu da',
'range_block_disabled'        => 'Administratzaileak IP eremuak blokeatzeko gaitasuna ezgaituta dago.',
'ipb_expiry_invalid'          => 'Baliogabeko iraungipen denbora',
'ipb_already_blocked'         => '"$1" badago blokeatuta',
'ip_range_invalid'            => 'Baliogabeko IP eremua.',
'proxyblocker'                => 'Proxy blokeatzailea',
'ipb_cant_unblock'            => 'Errorea: Ez da $1 IDa duen blokeoa aurkitu. Baliteke blokeoa jada kenduta egotea.',
'proxyblockreason'            => 'Zure IP helbidea blokeatu egin da proxy ireki baten zaudelako. Mesedez, zure Interneteko Zerbitzu Hornitzailearekin harremanetan jar zaitez segurtasun arazo honetaz ohartarazteko.',
'proxyblocksuccess'           => 'Egina.',
'sorbs'                       => 'SORBS DNSBL',
'sorbsreason'                 => 'Zure IP helbidea proxy ireki bezala zerrendatuta dago [http://www.sorbs.net SORBS]eko DNSBLan.',
'sorbs_create_account_reason' => 'Zure IP helbidea proxy ireki bezala zerrendatuta dago [http://www.sorbs.net SORBS]eko DNSBLan. Ezin duzu kontua sortu.',

# Developer tools
'lockdb'              => 'Datu-basea blokeatu',
'unlockdb'            => 'Datu-basea desblokeatu',
'lockdbtext'          => 'Datu-basea blokeatzeak edozein erabiltzailek orrialdeak aldatzea, hobespenak aldatzea, jarraipen zerrendan aldaketak egitea, eta datu-basean edozein aldaketa behar duen edozein ekintza galaraziko du. Mesedez, baieztatu zure asmoa hori dela, eta blokeoa kenduko duzula mantenua burutu ondoren.',
'unlockdbtext'        => 'Datu-basea desblokeatzerakoan erabiltzaile guztiek orrialdeak aldatu, beraien hobespenak ezarri, jarraipen zerrendan aldaketak egin eta beste eragiketa batzuk burutzeko gaitasuna leheneratuko du. Mesedez, baieztatu egin nahi duzuna hori dela.',
'lockconfirm'         => 'Bai, datu-basea blokeatu nahi dut',
'unlockconfirm'       => 'Bai, datu-basea desblokeatu nahi dut',
'lockbtn'             => 'Datu-basea blokeatu',
'unlockbtn'           => 'Datu-basea desblokeatu',
'locknoconfirm'       => 'Ez duzu baieztapen kutxa hautatu.',
'lockdbsuccesssub'    => 'Datu-basea blokeatu egin da',
'unlockdbsuccesssub'  => 'Datu-basearen blokeoa kendu da',
'lockdbsuccesstext'   => 'Datu-basea blokeatu egin da. <br />Ez ahaztu mantenu lanak burutu ondoren [[Special:Unlockdb|blokeoa kentzeaz]].',
'unlockdbsuccesstext' => 'Datu-basea desblokeatu egin da.',
'lockfilenotwritable' => 'Ezin da datu-baseko blokeo fitxategian idatzi. Datu-basea blokeatu edo desblokeatzeko, zerbitzariak idazteko aukera izan beharra dauka.',
'databasenotlocked'   => 'Datu-basea ez dago blokeatuta.',

# Move page
'movepage'                => 'Orrialdea mugitu',
'movepagetext'            => 'Hurrengo pausoak jarraituz, artikulu edo orrialde baten izena aldatu daiteke. Izenburu zaharra, automatikoki izenburu berriari birzuzenduko zaio. 
Gogora ezazu, orrialdearen izena ez dela aldatuko, nahi duzun izena dagoeneko sortuta badago Wikipedian; birzuzenketa bat edo historiarik gabeko orrialde bat ez bada.

<b>KONTUZ!</b>
Artikulu oso erabilia edo asko aldatzen denaren izenburua aldatzera bazoaz, mesedez, lehenbizi artikuluaren eztabaidan adierazi ezazu beste lankideen iritziak jasotzeko.',
'movepagetalktext'        => "Dagokion eztabaida orrialdea berarekin batera mugitu da, honako kasu hauetan '''ezik:'''
* Hutsik ez dagoen eztabaida orrialde bat existitzen bada izen berrian.
* Beheko koadroa hautatzen ez baduzu.

Kasu horietan orrialdea eskuz mugitu edo bestearekin bateratu beharko duzu.",
'movearticle'             => 'Orrialdea mugitu',
'movenologin'             => 'Saioa hasi gabe',
'movenologintext'         => 'Orrialde bat mugitzeko erregistratutako erabiltzailea izan behar duzu eta [[Special:Userlogin|saioa hasi]].',
'newtitle'                => 'Izenburu berria',
'movepagebtn'             => 'Orrialde mugitu',
'pagemovedsub'            => 'Mugimendua eginda',
'pagemovedtext'           => '"$1" izenburua "$2"(r)en truke aldatu da.',
'articleexists'           => 'Izen hori duen artikulu bat badago edo hautatutako izena ez da baliozkoa. Mesedez, beste izen bat aukeratu.',
'talkexists'              => "'''Orrialde hau arazorik gabe mugitu da, baina eztabaida orrialde ezin izan da mugitu izenburu berriarekin jada bat existitzen delako. Mesedez, eskuz batu itzazu biak.'''",
'movedto'                 => 'hona mugitu da:',
'movetalk'                => 'Eztabaida orrialdea ere mugitu, ahal bada.',
'talkpagemoved'           => 'Artikulu honen eztabaida ere mugitu egin da.',
'talkpagenotmoved'        => 'Artikulu honen eztabaida <strong>ez</strong> da mugitu.',
'1movedto2'               => '$1 izenburua $2(r)engatik aldatu da',
'1movedto2_redir'         => '$1 izenburua $2(r)engatik aldatu da birzuzenketaren gainetik',
'movelogpage'             => 'Mugimendu erregistroa',
'movelogpagetext'         => 'Mugitutako orrialdeen zerrenda bat azaltzen da jarraian.',
'movereason'              => 'Arrazoia',
'revertmove'              => 'desegin',
'delete_and_move'         => 'Ezabatu eta mugitu',
'delete_and_move_text'    => '== Ezabatzeko beharra ==

"[[$1]]" helburua existitzen da. Lekua egiteko ezabatu nahi al duzu?',
'delete_and_move_confirm' => 'Bai, orrialdea ezabatu',
'delete_and_move_reason'  => 'Lekua egiteko ezabatu da',
'selfmove'                => 'Helburu izenburua berdina da; ezin da orrialde bat bere gainera mugitu.',
'immobile_namespace'      => 'Hasierako edo amaierako izenburua Aparteko motakoa da; ezin da izen-tarte horretatik eta horretara ezer mugitu.',

# Export
'export'          => 'Orrialdeak esportatu',
'exporttext'      => 'Orrialde bat edo batzuen testua eta historia esportatu dezakezu XML fitxategi batzuetan. Ondoren, MediaWiki erabiltzen duen beste wiki baten jarri dezakezu Special:Import orrialdea erabiliz.

Orrialdeak esportatzeko zehaztu hauen izenburuak beheko koadroan, izenburu bat lerroko, eta aukeratu zein bertsio esportatu nahi dituzun.

Horrez gain, lotura zuzena ere erabil dezakezu; adibidez, [[{{ns:Special}}:Export/{{int:mainpage}}]] {{int:mainpage}} orrialdearentzako.',
'exportcuronly'   => 'Oraingo berrikuspena bakarrik hartu, ez historia guztia',
'exportnohistory' => "----
'''Oharra:''' Formulario honen bitartez orrialdeen historia osoak esportatzeko aukera ezgaitu egin da, errendimendua dela-eta.",
'export-submit'   => 'Esportatu',

# Namespace 8 related
'allmessages'               => 'Sistemako mezu guztiak',
'allmessagesname'           => 'Izena',
'allmessagesdefault'        => 'Testu lehenetsia',
'allmessagescurrent'        => 'Oraingo testua',
'allmessagestext'           => 'MediaWikin erabiltzen diren mezu guztien zerrenda:',
'allmessagesnotsupportedUI' => "Aukeratuta duzun hizkuntza ('''$1''') ez du Special:Allmessages orrialdeak onartzen gune honetan.",
'allmessagesnotsupportedDB' => "Ezin da '''Special:Allmessages''' erabili '''\$wgUseDatabaseMessages''' ezgaituta dagoelako.",
'allmessagesfilter'         => 'Mezu izenaren iragazkia:',
'allmessagesmodified'       => 'Aldatutakoak bakarrik erakutsi',

# Thumbnails
'thumbnail-more'  => 'Handitu',
'missingimage'    => '<b>Irudia falta da</b><br /><i>$1</i>',
'filemissing'     => 'Fitxategia falta da',
'thumbnail_error' => 'Errorea irudi txikia sortzerakoan: $1',

# Special:Import
'import'                     => 'Orrialdeak inportatu',
'importinterwiki'            => 'Wikien arteko inportazioa',
'import-interwiki-text'      => 'Aukeratu inportatzeko wiki eta orrialde izenburu bat. Berrikuspenen datak eta egileak gorde egingo dira. Inportazio ekintza guzti hauek [[Special:Log/import|inportazio erregistroan]] gordetzen dira.',
'import-interwiki-history'   => 'Orrialde honen historiako bertsio guztiak kopiatu',
'import-interwiki-submit'    => 'Inportatu',
'import-interwiki-namespace' => 'Izen-tarte honetako orrialdeak transferitu:',
'importtext'                 => 'Mesedez, jatorrizko wikitik orrialdea esportatzeko Special:Export tresna erabil ezazu, zure diskoan gorde eta jarraian hona igo.',
'importstart'                => 'Orrialdeak inportatzen...',
'import-revision-count'      => '{{PLURAL:$1|berrikuspen 1|$1 berrikuspen}}',
'importnopages'              => 'Ez dago orrialderik inportatzeko.',
'importfailed'               => 'Inportazioak huts egin du: $1',
'importunknownsource'        => 'Inportazio iturri mota ezezaguna',
'importcantopen'             => 'Ezin izan da inportazio fitxategia ireki',
'importbadinterwiki'         => 'Interwiki lotura ezegokia',
'importnotext'               => 'Hutsik dago edo testurik gabe',
'importsuccess'              => 'Inportazioa burutu da!',
'importhistoryconflict'      => 'Gatazka sortzen ari den berrikuspen historia dago (baliteke orrialdea lehenago inportatu izana)',
'importnosources'            => 'Ez dago wikien arteko inportazio iturririk eta historialak zuzenean igotzea ezgaituta dago.',
'importnofile'               => 'Ez da inportazio fitxategirik igo.',
'importuploaderror'          => 'Inportazio fitxategiaren igoerak huts egin du; baliteke fitxategiaren tamaina baimendutakoa baino handiagoa izatea.',

# Import log
'importlogpage'                    => 'Inportazio erregistroa',
'importlogpagetext'                => 'Beste wiki batzutatik historial eta guzti egindako orrialdeen inportazio administratiboak.',
'import-logentry-upload'           => '[[$1]] igoera bitartez inportatu da',
'import-logentry-upload-detail'    => '$1 berrikuspen',
'import-logentry-interwiki'        => '$1 wiki artean mugitu da',
'import-logentry-interwiki-detail' => '$1 berrikuspen $2(e)tik',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Nire lankide orrialdea',
'tooltip-pt-anonuserpage'         => 'Zure IParen lankide orrialdea',
'tooltip-pt-mytalk'               => 'Nire eztabaida orrialdea',
'tooltip-pt-anontalk'             => 'Zure IParen eztabaida',
'tooltip-pt-preferences'          => 'Nire hobespenak',
'tooltip-pt-watchlist'            => 'Jarraitzen dituzun orrialdeen zerrenda.',
'tooltip-pt-mycontris'            => 'Nire ekarpenak',
'tooltip-pt-login'                => 'Izen ematera gonbidatzen zaitugu.',
'tooltip-pt-anonlogin'            => 'Izen ematera gonbidatzen zaitugu.',
'tooltip-pt-logout'               => 'Saioa itxi',
'tooltip-ca-talk'                 => 'Artikuluari buruzko eztabaida',
'tooltip-ca-edit'                 => 'Artikulu hau aldatu dezakezu. Mesedez, aurrebista botoia erabil ezazu gorde baino lehen.',
'tooltip-ca-addsection'           => 'Eztabaida honetan iruzkin bat erantsi ezazu.',
'tooltip-ca-viewsource'           => 'Artikulu hau babesturik dago. Bere kodea soilik ikus dezakezu.',
'tooltip-ca-history'              => 'Artikulu honen aurreko bertsioak.',
'tooltip-ca-protect'              => 'Artikulu hau babestu',
'tooltip-ca-delete'               => 'Artikulu hau ezabatu',
'tooltip-ca-undelete'             => 'Ezabatu baino lehenago egindako aldaketak berrezarri.',
'tooltip-ca-move'                 => 'Orrialde hau mugitu',
'tooltip-ca-watch'                => 'Orrialde hau jarraipen zerrendan gehitu',
'tooltip-ca-unwatch'              => 'Orrialde hau jarraipen zerrendatik kendu',
'tooltip-search'                  => 'Wiki honetan bilatu',
'tooltip-p-logo'                  => 'Azala',
'tooltip-n-mainpage'              => 'Azala bisitatu',
'tooltip-n-portal'                => 'Proiektuaren inguruan, zer egin dezakezu, non aurkitu nahi duzuna',
'tooltip-n-currentevents'         => 'Oraingo gertaeren inguruko informazio gehigarria',
'tooltip-n-recentchanges'         => 'Wikiko azken aldaketen zerrenda.',
'tooltip-n-randompage'            => 'Ausazko orrialde bat kargatu',
'tooltip-n-help'                  => 'Aurkitzeko lekua.',
'tooltip-n-sitesupport'           => 'Lagun iezaguzu',
'tooltip-t-whatlinkshere'         => 'Hona lotzen duten wiki orrialde guztien zerrenda',
'tooltip-t-recentchangeslinked'   => 'Orrialde honetatik lotutako orrialdeen azken aldaketak',
'tooltip-feed-rss'                => 'Orrialde honen RSS jarioa',
'tooltip-feed-atom'               => 'Orrialde honen atom jarioa',
'tooltip-t-contributions'         => 'Lankide honen ekarpen zerrenda ikusi',
'tooltip-t-emailuser'             => 'Lankide honi e-posta mezua bidali',
'tooltip-t-upload'                => 'Irudiak edo media fitxategiak igo',
'tooltip-t-specialpages'          => 'Aparteko orrialde guztien zerrenda',
'tooltip-ca-nstab-main'           => 'Eduki orrialdea ikusi',
'tooltip-ca-nstab-user'           => 'Lankide orrialdea ikusi',
'tooltip-ca-nstab-media'          => 'Media orrialdea ikusi',
'tooltip-ca-nstab-special'        => 'Hau aparteko orrialde bat da, ezin duzu orrialdea aldatu.',
'tooltip-ca-nstab-project'        => 'Proiektuaren orrialdea ikusi',
'tooltip-ca-nstab-image'          => 'Irudiaren orrialdea ikusi',
'tooltip-ca-nstab-mediawiki'      => 'Sistemaren mezua ikusi',
'tooltip-ca-nstab-template'       => 'Txantiloia ikusi',
'tooltip-ca-nstab-help'           => 'Laguntza orrialdea ikusi',
'tooltip-ca-nstab-category'       => 'Kategoria orrialdea ikusi',
'tooltip-minoredit'               => 'Aldaketa txiki bezala markatu hau',
'tooltip-save'                    => 'Zure aldaketak gorde',
'tooltip-preview'                 => 'Zure aldaketak aurreikusi, mesedez gorde aurretik erabili!',
'tooltip-diff'                    => 'Testuari egindako aldaketak erakutsi.',
'tooltip-compareselectedversions' => 'Orrialde honen bi hautatutako bertsioen arteko ezberdintasunak ikusi.',
'tooltip-watch'                   => 'Orrialde hau zure segimendu zerrendan gehitu',
'tooltip-recreate'                => 'Orrialdea birsortu ezabatu egin den arren',

# Stylesheets
'common.css'   => '/** Hemen idatzitako CSS kodeak itxura guztietan izango du eragina */',
'monobook.css' => '/* Hemen idatzitako CSS kodeak Monobook itxuran bakarrik izango du eragina */',

# Scripts
'monobook.js' => '/* Deprecated; use [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Dublin Core RDF metadatuak ezgaitu egin dira zerbitzari honetarako.',
'nocreativecommons' => 'Creative Commons RDF metadatuak ezgaitu egin dira zerbitzari honetarako.',
'notacceptable'     => 'Wiki zerbitzariak ezin ditu datuak zure bezeroak irakur ditzakeen formatu batean eskaini.',

# Attribution
'anonymous'        => '{{SITENAME}}(e)ko lankide anonimoak',
'siteuser'         => '{{SITENAME}}(e)ko $1 erabiltzailea',
'lastmodifiedatby' => 'Orrialdearen azken aldaketa: $2, $1. Nork: $3.', # $1 date, $2 time, $3 user
'and'              => 'eta',
'othercontribs'    => '$1(r)en lanean oinarrituta.',
'others'           => 'besteak',
'siteusers'        => '{{SITENAME}}(e)ko $1 erabiltzailea(k)',
'creditspage'      => 'Orrialdearen kredituak',
'nocredits'        => 'Ez dago krediturik eskuragarri orrialde honentzako.',

# Spam protection
'spamprotectiontitle'    => 'Spam-arengandik babesteko iragazkia',
'spamprotectiontext'     => 'Gorde nahi duzun orrialdea spam iragazkiak blokeatu du. Baliteke kanpo lotura batek sortzea arazo hori.',
'spamprotectionmatch'    => 'Gure spam iragazkiak testu hau antzeman du: $1',
'subcategorycount'       => '{{PLURAL:$1|Azpikategoria bat dago|$1 azpikategoria daude}} kategoria honetan.',
'categoryarticlecount'   => 'Kategoria honetan {{PLURAL:$1|artikulu bakarra dago|$1 artikulu daude}}.',
'listingcontinuesabbrev' => ' jarr.',
'spambot_username'       => 'MediaWikiren spam garbiketa',
'spam_reverting'         => '$1(e)rako loturarik ez daukan azken bertsiora itzultzen',
'spam_blanking'          => 'Berrikuspen guztiek $1(e)rako lotura zeukaten, husten',

# Info page
'infosubtitle'   => 'Orrialdearen informazioa',
'numedits'       => 'Aldaketa kopurua (artikulua): $1',
'numtalkedits'   => 'Aldaketa kopurua (eztabaida orrialdea): $1',
'numwatchers'    => 'Jarraitzaile kopurua: $1',
'numauthors'     => 'Egile ezberdinen kopurua (artikulua): $1',
'numtalkauthors' => 'Egile ezberdinen kopurua (eztabaida orrialdea): $1',

# Math options
'mw_math_png'    => 'Beti PNG irudiak sortu',
'mw_math_simple' => 'Oso sinplea bada HTML, eta bestela PNG',
'mw_math_html'   => 'Posible bada HTML, eta bestela PNG',
'mw_math_source' => 'TeX bezala utzi (testu bidezko nabigatzaileentzako)',
'mw_math_modern' => 'Nabigatzaile berrientzako gomendatuta',
'mw_math_mathml' => 'MathML posible bada (proba fasean)',

# Patrolling
'markaspatrolleddiff'        => 'Patruilatu bezala markatu',
'markaspatrolledtext'        => 'Artikulu hau patruilatu bezala markatu',
'markedaspatrolled'          => 'Patruilatu bezala markatu da',
'markedaspatrolledtext'      => 'Hautatutako berrikuspena patruilatu bezala markatu da.',
'rcpatroldisabled'           => 'Aldaketa berrien patruilaketa ezgaituta dago',
'rcpatroldisabledtext'       => 'Aldaketa berrien patruilaketa ezaugarria ezgaituta dago orain.',
'markedaspatrollederror'     => 'Ezin da patruilatu bezala markatu',
'markedaspatrollederrortext' => 'Patruilatu bezala markatzeko berrikuspen bat hautatu beharra daukazu.',

# Image deletion
'deletedrevision' => '$1 berrikuspen zaharra ezabatu da.',

# Browsing diffs
'previousdiff' => '← Aurreko ezberdintasuna',
'nextdiff'     => 'Hurrengo ezberdintasuna →',

# Media information
'mediawarning' => "'''Oharra''': Fitxategi honek kode mingarria izan lezake; zure sisteman exekutatzea arriskutsua izan liteke.<hr />",
'imagemaxsize' => 'Irudiak deskribapen-orrialdetan hurrengo tamainara txikitu:',
'thumbsize'    => 'Irudi txikiaren tamaina:',

'newimages'    => 'Fitxategi berrien galeria',
'showhidebots' => '($1 bot-ak)',
'noimages'     => 'Ez dago ezer ikusteko.',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-cn' => 'cn',
'variantname-zh-tw' => 'tw',
'variantname-zh-hk' => 'hk',
'variantname-zh-sg' => 'sg',
'variantname-zh'    => 'zh',

# Variants for Serbian language
'variantname-sr-ec' => 'sr-ec',
'variantname-sr-el' => 'sr-el',
'variantname-sr-jc' => 'sr-jc',
'variantname-sr-jl' => 'sr-jl',
'variantname-sr'    => 'sr',

# Variants for Kazakh language
'variantname-kk-tr' => 'kk-tr',
'variantname-kk-kz' => 'kk-kz',
'variantname-kk-cn' => 'kk-cn',
'variantname-kk'    => 'kk',

'passwordtooshort' => 'Zure pasahitza laburregia da. $1 karaktere izan behar ditu gutxienez.',

# Metadata
'metadata'          => 'Metadatuak',
'metadata-help'     => 'Fitxategi honek informazio gehigarri dauka, ziurrenik kamera digital edo eskanerrak egiterako momentuan gehitutakoa. Hori dela-eta, jatorrizko fitxategi hori aldatu egin bada, baliteke xehetasun batzuek errealitatearekin bat ez egitea.',
'metadata-expand'   => 'Xehetasunak erakutsi',
'metadata-collapse' => 'Xehetasunak ezkutatu',
'metadata-fields'   => 'Mezu honetan zerrendatutako EXIF metadatu eremuak irudiaren orrialdean erakutsiko dira. Gainontzekoak ezkutatu egindako dira lehenetsi bezala.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# External editor support
'edit-externally'      => 'Fitxategi hau editatu kanpo-aplikazio bat erabiliz',
'edit-externally-help' => 'Ikus [http://meta.wikimedia.org/wiki/Help:External_editors konfiguraziorako argibideak] informazio gehiagorako.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'guztiak',
'imagelistall'     => 'guztiak',
'watchlistall1'    => 'guztiak',
'watchlistall2'    => 'guztiak',
'namespacesall'    => 'guztiak',

# E-mail address confirmation
'confirmemail'            => 'E-posta helbidea egiaztatu',
'confirmemail_noemail'    => 'Ez daukazu e-posta helbiderik zehaztuta zure [[Special:Preferences|hobespenetan]].',
'confirmemail_text'       => 'Wiki honetan zure e-posta helbidea egiaztatzea beharrezkoa da e-postarekin zerikusia duten ezaugarriak erabili aurretik. Beheko botoia jo zure helbidera egiaztapen mezu bat bidaltzeko. Mezuan kode bat duen lotura bat joango da atxikita; lotura hori zure nabigatzailean ireki ezazu e-posta helbidea egiaztatzeko.',
'confirmemail_send'       => 'Egiaztapen kodea e-postaz bidali',
'confirmemail_sent'       => 'Egiaztapen mezua bidali da.',
'confirmemail_sendfailed' => 'Ezin izan da egiaztapen mezua bidali. Ziurtatu e-posta helbidean baliogabeko karaktererik ez dagoela. Zerbitzariaren mezua: $1',
'confirmemail_invalid'    => 'Baliogabeko egiaztapen kodea. Baliteke kodea iraungi izana.',
'confirmemail_needlogin'  => '$1 behar duzu zure e-posta helbidea egiaztatzeko.',
'confirmemail_success'    => 'Zure e-posta helbidea egiaztatu da. Saioa hasi eta ekarpenak egin ditzakezu orain.',
'confirmemail_loggedin'   => 'Zure e-posta helbidea egiaztatu da.',
'confirmemail_error'      => 'Akatsen bat gertatu da egiaztapena burutzerakoan.',
'confirmemail_subject'    => 'E-posta helbide egiaztapena {{SITENAME}}(e)n',
'confirmemail_body'       => 'Norbaitek, ziurrenik zuk ($1 IP helbidetik), "$2" kontua erregistratu du {{SITENAME}}(e)n e-posta helbide honekin.

Izen hori zuri dagokizula eta {{SITENAME}}(e)n zure e-posta egiaztatzeko, hurrengo lotura hau zure nabigatzailean ireki behar duzu:

$3

Zu *ez* bazara, ez jo lotura horretara. Egiaztapen kode hau $4 iraungiko da.',

# Inputbox extension, may be useful in other contexts as well
'tryexact'       => 'Izenburu zehatza bilatu',
'searchfulltext' => 'Testu osoa bilatu',
'createarticle'  => 'Artikulua sortu',

# Scary transclusion
'scarytranscludedisabled' => '[Interwikien transklusioa ezgaituta dago]',
'scarytranscludefailed'   => '[Arazoa $1 txantiloia eskuratzerakoan; barkatu]',
'scarytranscludetoolong'  => '[URLa luzeegia da; barkatu]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks"> Artikulu honen aipuak:<br /> $1 </div>',
'trackbackremove'   => ' ([$1 Ezabatu])',
'trackbacklink'     => 'Aipua',
'trackbackdeleteok' => 'Aipua ezabatu egin da.',

# Delete conflict
'deletedwhileediting' => 'Oharra: Zu aldaketak egiten hasi ondoren ezabatu egin da orrialde hau!',
'confirmrecreate'     => "[[User:$1|$1]] erabiltzaileak ([[User talk:$1|eztabaida]]) orrialde hau ezabatu zu aldatzen hasi eta gero. Hona arrazoia: : ''$2'' Mesedez, baieztatu orrialde hau berriz sortu nahi duzula.",
'recreate'            => 'Birsortu',

'unit-pixel' => 'px',

# HTML dump
'redirectingto' => '[[$1]] orrialdera berbideratzen...',

# action=purge
'confirm_purge'        => 'Orrialde honen katxea ezabatu?

$1',
'confirm_purge_button' => 'Ados',

'youhavenewmessagesmulti' => 'Mezu berriak dituzu $1(e)n',

'searchcontaining' => "''$1'' barne duten orrialdeen bilaketa.",
'searchnamed'      => "''$1'' izenburua duten artikuluen bilaketa.",
'articletitles'    => "''$1''(r)ekin hasten diren artikuluak",
'hideresults'      => 'Emaitzak ezkutatu',

# DISPLAYTITLE
'displaytitle' => '(Orrialde honetara lotzen da [[$1]] bezala)',

'loginlanguagelabel' => 'Hizkuntza: $1',

# Multipage image navigation
'imgmultipageprev' => '&larr; aurreko orrialdea',
'imgmultipagenext' => 'hurrengo orrialdea &rarr;',
'imgmultigo'       => 'Joan!',
'imgmultigotopre'  => 'Orrialdera jo',

# Table pager
'ascending_abbrev'         => 'gor',
'descending_abbrev'        => 'behe',
'table_pager_next'         => 'Hurrengo orrialdea',
'table_pager_prev'         => 'Aurreko orrialdea',
'table_pager_first'        => 'Lehen orrialdea',
'table_pager_last'         => 'Azken orrialdea',
'table_pager_limit'        => 'Orrialdeko $1 elementu erakutsi',
'table_pager_limit_submit' => 'Joan',
'table_pager_empty'        => 'Emaitzik ez',

# Auto-summaries
'autoredircomment' => '[[$1]] orrialdera birzuzentzentzen', # This should be changed to the new naming convention, but existed beforehand

);

?>

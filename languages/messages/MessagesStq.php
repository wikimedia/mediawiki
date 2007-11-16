<?php
/** Seeltersk (Seeltersk)
 *
 * @addtogroup Language
 *
 * @author Maartenvdbent
 * @author Nike
 * @author SPQRobin
 */

$fallback = 'de';

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Spezial',
	NS_MAIN           => '',
	NS_TALK           => 'Diskussion',
	NS_USER           => 'Benutser',
	NS_USER_TALK      => 'Benutser_Diskussion',
	# NS_PROJECT set by \$wgMetaNamespace
	NS_PROJECT_TALK   => '$1_Diskussion',
	NS_IMAGE          => 'Bielde',
	NS_IMAGE_TALK     => 'Bielde_Diskussion',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_Diskussion',
	NS_TEMPLATE       => 'Foarloage',
	NS_TEMPLATE_TALK  => 'Foarloage_Diskussion',
	NS_HELP           => 'Hälpe',
	NS_HELP_TALK      => 'Hälpe_Diskussion',
	NS_CATEGORY       => 'Kategorie',
	NS_CATEGORY_TALK  => 'Kategorie_Diskussion',
);

$messages = array(
# Dates
'sunday'       => 'Sundai',
'monday'       => 'Moundai',
'tuesday'      => 'Täisdai',
'wednesday'    => 'Midwiek',
'thursday'     => 'Tuunsdai',
'friday'       => 'Fräindai',
'saturday'     => 'Snäiwende',
'sun'          => 'Sun',
'mon'          => 'Mou',
'tue'          => 'Täi',
'wed'          => 'Mid',
'thu'          => 'Tuu',
'fri'          => 'Frä',
'sat'          => 'Snä',
'january'      => 'Januoar',
'february'     => 'Feebermound',
'march'        => 'Meerte',
'april'        => 'April',
'may_long'     => 'Moai',
'august'       => 'August',
'january-gen'  => 'Januoar',
'february-gen' => 'Feebermound',
'march-gen'    => 'Meerte',
'april-gen'    => 'April',
'august-gen'   => 'August',
'jan'          => 'Jan',
'feb'          => 'Fee',
'mar'          => 'Mee',
'apr'          => 'Apr',
'may'          => 'Moa',
'aug'          => 'Aug',

# Bits of text used by many pages
'categories'      => 'Kategorien',
'category_header' => 'Artikkel in de Kategorie "$1"',

'mainpagetext'      => 'Ju Wiki Software wuude mäd Ärfoulch installierd!',
'mainpagedocfooter' => 'Sjuch ju [http://meta.wikimedia.org/wiki/MediaWiki_localization Dokumentation tou de Anpaasenge fon dän Benutseruurfläche] un dät [http://meta.wikimedia.org/wiki/Help:Contents Benutserhondbouk] foar Hälpe tou ju Benutsenge un Konfiguration.',

'article'       => 'Inhoold Siede',
'newwindow'     => '(eepent in näi Finster)',
'cancel'        => 'Oubreeke',
'qbedit'        => 'Beoarbaidje',
'moredotdotdot' => 'Moor …',
'mypage'        => 'Oaine Siede',
'mytalk'        => 'Oaine Diskussion',
'anontalk'      => 'Diskussionssiede foar dissen IP',
'navigation'    => 'Navigation',

'errorpagetitle'   => 'Failer',
'tagline'          => 'Fon {{SITENAME}}',
'help'             => 'Hälpe',
'search'           => 'Säike',
'searchbutton'     => 'Säike',
'history'          => 'Versione',
'history_short'    => 'Geschichte',
'info_short'       => 'Information',
'edit'             => 'Siede beoarbaidje',
'editthispage'     => 'Siede beoarbaidje',
'delete'           => 'Läskje',
'deletethispage'   => 'Disse Siede läskje',
'newpage'          => 'Näie Siede',
'talkpagelinktext' => 'Diskussion',
'talk'             => 'Diskussion',
'lastmodifiedat'   => 'Disse Siede wuude toulääst annerd uum $2, $1.', # $1 date, $2 time
'jumpto'           => 'Wikselje tou:',
'jumptosearch'     => 'Säike',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'bugreports'        => 'Kontakt Wikipedia',
'copyright'         => 'Inhoold is ferföichboar unner de $1.',
'currentevents'     => 'Aktuälle Geböärnisse',
'currentevents-url' => 'Aktuälle Geböärnisse',
'disclaimers'       => 'Begriepskläärenge',
'disclaimerpage'    => 'Project:Siede tou Begriepskläärenge',
'edithelp'          => 'Beoarbaidengshälpe',
'edithelppage'      => 'Help:Beoarbaidengshälpe',
'faq'               => 'Oafte stoalde Froagen',
'faqpage'           => 'Project:FAQ',
'helppage'          => '{{ns:project}}:Hälpe',
'mainpage'          => 'Haudsiede',

'newmessageslink'     => 'näie Ättergjuchte',
'newmessagesdifflink' => 'Unnerscheed tou ju foarlääste Version',
'editsection'         => 'Beoarbaidje',
'editold'             => 'Beoarbaidje',
'hidetoc'             => 'ferbierge',
'site-rss-feed'       => '$1 RSS-Feed',
'site-atom-feed'      => '$1 Atom-Feed',

# General errors
'error'              => 'Failer',
'dberrortext'        => 'Dät roat n Syntaxfailer in dän Doatenboankoufroage. Ju lääste Doatenboankoufroage lutte:
<blockquote><tt>$1</tt></blockquote> uut de Funktion "<tt>$2</tt>". MySQL mäldede dän Failer "<tt>$3: $4</tt>".',
'noconnect'          => 'Spietelk kuude neen Ferbiendenge tou ju Doatenboank apbaud wäide. Die Doatenboankserver häd foulgjende Failere mälded: <i>$1</i>. Fersäik dät jädden noch moal of besäik uus Haudsiede.',
'nodb'               => 'Kuude Doatenboank $1 nit beloangje',
'cachederror'        => 'Dät Foulgjende is ne Kopie uut de Cache un is fielicht ferallerd.',
'laggedslavemode'    => 'Woarschauenge: Ju anwiesde Siede kon unner Umstande do jungste Beoarbaidengen noch nit be-ienhoolde.',
'enterlockreason'    => 'Reeke jädden n Gruund ien, wieruum ju Doatenboank speerd wäide schuul un ne Ouschätsenge uur ju Duur fon ju Speerenge',
'internalerror'      => 'Interne Failer',
'internalerror_info' => 'Interne Failer: $1',
'filecopyerror'      => 'Kuude Doatäi "$1" nit ätter "$2" kopierje.',
'filerenameerror'    => 'Kuude Doatäi "$1" nit ätter "$2" uumenaame.',
'filedeleteerror'    => 'Kuude Doatäi "$1" nit läskje.',
'filenotfound'       => 'Kuude Doatäi "$1" nit fiende.',
'formerror'          => '<b style="color: #cc0000;">Failer: Do Iengoawen konne nit feroarbaided wäide.</b>',
'badarticleerror'    => 'Disse Honnelenge kon ap disse Siede nit moaked wäide.',
'cannotdelete'       => 'Kon spezifizierde Siede of Artikkel nit läskje. Fielicht is ju al läsked wuuden.',
'badtitle'           => 'Uungultige Tittel.',
'badtitletext'       => 'Die anfräigede Tittel waas uungultich, loos, of n uungultige Sproaklink fon n uur Wiki.',

# Login and logout pages
'loginpagetitle'             => 'Benutser-Anmäldenge',
'loginproblem'               => "'''Dät roate n Problem mäd ju Anmäldenge.'''<br /> Fersäik dät jädden nochmoal!",
'login'                      => 'Anmäldje',
'loginprompt'                => 'Uum sik bie {{SITENAME}} anmäldje tou konnen, mouten Cookies aktivierd weese.',
'logout'                     => 'Oumäldje',
'nologin'                    => 'Noch neen Benutserkonto? $1.',
'nologinlink'                => 'Hier laist du n Konto an.',
'createaccount'              => 'Benutserkonto anlääse',
'gotaccount'                 => 'Du hääst al n Konto? $1.',
'gotaccountlink'             => 'Hier gungt dät ätter dän Login',
'createaccountmail'          => 'Uur Email',
'badretype'                  => 'Do bee Paaswoude stimme nit uureen.',
'badsig'                     => 'Signatursyntax is uungultich; HTML uurpröiwje.',
'loginerror'                 => 'Failer bie ju Anmäldenge',
'nocookiesnew'               => 'Dien Benutsertougong wuude kloor moaked, man du bäst nit anmälded. {{SITENAME}} benutset Cookies toun Anmäldjen fon do Benutsere. Du hääst in dien Browser-Ienstaalengen Cookies deaktivierd. Uum dien näie Benutsertougong tou bruuken, läit jädden dien Browser Cookies foar {{SITENAME}} annieme un mäldje die dan mäd dien juust iengjuchten Benutsernoome un Paaswoud an.',
'nocookieslogin'             => '{{SITENAME}} benutset Cookies toun Anmäldjen fon dän Benutser. Du hääst in dien Browser-Ienstaalengen Cookies deaktivierd, jädden aktivierje do un fersäik et fonnäien.',
'loginsuccesstitle'          => 'Anmäldenge mäd Ärfoulch',
'loginsuccess'               => "'''Du bäst nu as \"\$1\" bie {{SITENAME}} anmälded.'''",
'mailmypassword'             => 'Paaswoud ferjeeten?',
'noemail'                    => 'Benutser "$1" häd neen Email-Adrässe anroat of häd ju E-Mail-Funktion deaktivierd.',
'eauthentsent'               => 'Ne Bestäätigengs-Email wuude an ju anroate Adrässe fersoand. Aleer n Email fon uur
Benutsere uur ju Wikipedia-Mailfunktion ämpfangd wäide kon, mout ju Adrässe un hiere
wuddelke Touheeregaid tou dit Benutserkonto eerste bestäätiged wäide. Befoulgje jädden do
Waiwiese in ju Bestätigengs-E-Mail.',
'mailerror'                  => 'Failer bie dät Seenden fon dän Email: $1',
'acct_creation_throttle_hit' => 'Du hääst al $1 Benutserkonten anlaid. Du koast fääre neen moor anlääse.',
'emailauthenticated'         => 'Jou Email-Adrässe wuude bestäätiged: $1.',
'emailnotauthenticated'      => 'Jou Email-Adrässe wuude <strong>noch nit bestäätiged</strong>. Deeruum is bit nu neen E-
Mail-Fersoand un Ämpfang foar do foulgjende Funktionen muugelk.',
'noemailprefs'               => '<strong>Du hääst neen Email-Adrässe anroat</strong>, do foulgjende Funktione sunt deeruum apstuuns nit muugelk.',
'emailconfirmlink'           => 'Bestäätigje Jou Email-Adrässe',
'invalidemailaddress'        => 'Ju Email-Adresse wuude nit akzeptierd deeruum dät ju n ungultich Formoat (eventuäl ungultige Teekene) tou hääben schient. Reek jädden ne korrekte Adrässe ien of moakje dät Fäild loos.',

# Edit page toolbar
'bold_sample'    => 'Fatte Text',
'bold_tip'       => 'Fatte Text',
'italic_sample'  => 'Kursive Text',
'italic_tip'     => 'Kursive Text',
'link_tip'       => 'Interne Link',
'extlink_sample' => 'http://www.Biespil.de Link-Text',
'extlink_tip'    => 'Externen Link (http:// beoachtje)',
'headline_tip'   => 'Ieuwene 2 Uurschrift',
'math_sample'    => 'Formel hier ienföigje',
'math_tip'       => 'Mathematiske Formel (LaTeX)',
'image_sample'   => 'Biespil.jpg',
'image_tip'      => 'Bielde-Ferwies',
'media_sample'   => 'Biespil.ogg',
'media_tip'      => 'Mediendoatäi-Ferwies',
'hr_tip'         => 'Horizontoale Lienje (spoarsoam ferweende)',

# Edit pages
'blockedtitle'     => 'Benutser is blokkierd',
'blockedtext'      => "Dien Benutsernoome of dien IP-Adrässe wuude fon $1 speerd. As Gruund wuude anroat ''$2''.

Ju Duur fon ju Speerenge fint sik in $4. Deer IP-Adrässen bie fuul Providere dynamisk ferroat wäide, kon sun Speerenge oafte uk Uunscheeldige träffe, t.B. wan die bie dän Ienwoal ju IP-Adrässe fon wäl touwiesd wuude, die eer in Wikipedia Uunfuuch anstoald häd. Fals ju speerde IP-Adrässe n Proxy fon AOL is, koast du as AOL-Benutser ju Speerenge uumgunge, truch n uur [[Browser]] tou ferweenden as dän AOL-Browser. Wan du ju Meenenge bäst, dät ju Speerenge uungjuchtfäidich waas, weende die dan jädden mäd Angoawe fon ju IP-Adrässe ($3) of dän Benutsernoome, dän Speergruund un ne Beschrieuwenge fon dien Beoarbaidengen uur Email an (email-address) Uum ju Oarbaidsbeläästenge foar do Fräiwillige, do sik uum sukke Fälle kummerje, gering tou hoolden, weende die deerum jädden bloot bie laangere Speerengen an disse Adrässe. Speerengen weegen Vandalismus fon dän Benutser, die eer ju beträffende IP-Adrässe bruukt häd, schuulen ätter kuute Tied ouloope.",
'loginreqtitle'    => 'Anmäldenge ärfoarderelk',
'loginreqlink'     => 'anmäldje',
'loginreqpagetext' => 'Du moast die $1, uum uur Sieden betrachtje tou konnen.',
'accmailtitle'     => 'Paaswoud wuude fersoand.',
'accmailtext'      => 'Dät Paaswoud fon "$1" wuude an $2 soand.',
'newarticle'       => '(Näi)',
'newarticletext'   => 'Hier dän Text fon dän näie Artikkel iendreege. Jädden bloot in ganse Satse schrieuwe un neen truch dät Uurheebergjucht schutsede Texte fon uur Ljuude kopierje.',
'anontalkpagetext' => "----''Dit is ju Diskussionssiede fon n uunbekoanden Benutser, die sik nit anmälded häd. Wail naan Noome deer is, wäd ju nuumeriske [[IP-Adrässe]] tou Identifizierenge ferwoand. Man oafte wäd sunne Adrässe fon moorere Benutsere ferwoand. Wan du n uunbekoanden Benutser bääst un du toankst dät du Kommentare krichst do nit foar die meend sunt, dan koast du ap bääste dien [[Special:Userlogin|anmäldje]], uum sukke Fertuusengen tou fermieden.''",
'noarticletext'    => '(Dissen Artikkel änthalt apstuuns neen Text)',
'clearyourcache'   => "'''Bemäärkenge:''' Ätter dät Fäästlääsen kon dät nöödich weese, dän Browser-Cache loostoumoakjen, uum do Annerengen sjo tou konnen.",
'editing'          => 'Beoarbaidjen fon $1',
'editinguser'      => 'Beoarbaidje fon Benutser <b>$1</b>',
'editingsection'   => 'Beoarbaidje fon $1 (Apsats)',
'editingcomment'   => 'Beoarbaidjen fon $1 (Kommentoar)',
'editconflict'     => 'Beoarbaidengs-Konflikt: "$1"',
'explainconflict'  => "Uurswäl häd dissen Artikkel annerd, ätterdät du anfangd bäst, him tou beoarbaidjen. Dät buppere Textfäild änthaalt dän aktuälle Artikkel. Dät unnere Textfäild änthaalt dien Annerengen. Föige jädden dien Annerengen in dät buppere Textfäild ien.<br/> '''Bloot''' die Inhoold fon dät buppere Textfäild wäd spiekerd, wan du ap \"Spiekerje\" klikst!",
'editingold'       => '<strong>OACHTENGE: Jie beoarbaidje ne oolde Version fon disse Artikkel. Wan Jie spiekerje, wäide alle näiere Versione uurschrieuwen.</strong>',
'copyrightwarning' => 'Aal Biedraage tou dän {{SITENAME}} wäide betrachted as stoundend unner ju $2 (sjuch fääre: "$1"). Fals Jie nit moaten dät Jou Oarbaid hier fon uur Ljuude ferannerd un fersprat wäd, dan drukke Jie nit ap "Spiekerje".<br />
Iek fersicherje hiermäd, dät iek dän Biedraach sälwen ferfoated hääbe blw. dät hie neen froamd Gjucht ferlätset un willigje ien, him unner dän GNU-Lizenz für freie Dokumentation tou fereepentlikjen.',
'longpagewarning'  => '<strong>WOARSCHAUENGE: Disse Siede is $1kb groot; eenige Browsere kuuden Probleme hääbe, Sieden tou beoarbaidjen, do der gratter as 32kb sunt. Uurlääse Jou jädden, of ne Oudeelenge fon do Sieden in litjere Ousnitte muugelk is.</strong>',
'edittools'        => '<!-- Text hier stoant unner Beoarbaidengsfäildere un Hoochleedefäildere. -->',
'nocreatetitle'    => 'Dät Moakjen fon näie Sieden is begränsed',
'nocreatetext'     => '{{SITENAME}} häd testwiese dät Moakjen fon näie Sieden begränsed. Du koast oawers al bestoundene Sieden beoarbaidje of die [[Special:Userlogin|anmäldje]].',

# History pages
'nohistory'           => 'Dät rakt neen fröiere Versione fon dissen Artikkel.',
'loadhist'            => 'Leede Lieste mäd fröiere Versione',
'nextrevision'        => 'Naistjungere Version →',
'currentrevisionlink' => 'Aktuälle Version ounsjo',
'cur'                 => 'Aktuäl',
'next'                => 'Naiste',
'last'                => 'Foarige',
'histlegend'          => "Diff  Uutwoal: Do Boxen fon do wonskede Versionen markierje un 'Enter' drukke ap dän Button unner klikke.<br />
Legende: (Aktuäl) = Unnerscheed tou ju aktuälle Version, 
(Lääste) = Unnerscheed tou ju foarige Version, L = Litje Annerenge",
'deletedrev'          => '[läsked]',
'histfirst'           => 'Ooldste',
'histlast'            => 'Näiste',

# Diffs
'difference'              => '(Unnerschied twiske Versionen)',
'loadingrev'              => 'Leede Versione tou Unnerscheedenge',
'lineno'                  => 'Riege $1:',
'editcurrent'             => 'Ju aktuälle Version fon disse Artikkel beoarbaidje',
'compareselectedversions' => 'Wäälde Versione ferglieke',

# Search results
'nextn'       => 'naiste $1',
'powersearch' => 'Säike',

# Preferences page
'changepassword'        => 'Paaswoud annerje',
'math'                  => 'TeX',
'dateformat'            => 'Doatumsformoat',
'datedefault'           => 'Neen Preferenz',
'datetime'              => 'Doatum un Tied',
'math_failure'          => 'Parser-Failer',
'math_unknown_error'    => 'Uunbekoande Failer',
'math_unknown_function' => 'Uunbekoande Funktion',
'math_lexing_error'     => "'Lexing'-Failer",
'math_syntax_error'     => 'Syntaxfailer',
'math_image_error'      => 'ju PNG-Konvertierenge sluuch fail',
'math_bad_tmpdir'       => 'Kon dät Temporärferteeknis foar mathematiske Formeln nit anlääse of beschrieuwe.',
'math_bad_output'       => 'Kon dät Sielferteeknis foar mathematiske Formeln nit anlääse of beschrieuwe.',
'math_notexvc'          => 'Dät texvc-Program kon nit fuunen wäide. Beoachte jädden math/README.',
'newpassword'           => 'Näi Paaswoud:',
'contextlines'          => 'Teekene pro Träffer:',
'contextchars'          => 'Teekene pro Riege:',
'localtime'             => 'Tied bie Jou:',
'guesstimezone'         => 'Ienföigje uut dän Browser',
'allowemail'            => 'Emails fon uur Benutsere kriegen',
'defaultns'             => 'In disse Noomensruume schäl standoardmäitich soacht wäide:',
'files'                 => 'Doatäie',

# User rights
'editusergroup' => 'Beoarbaidede Benutsergjuchte',

# Groups
'group'            => 'Gruppe:',
'group-bureaucrat' => 'Bürokraten',

'group-bureaucrat-member' => 'Bürokrat',

# Recent changes
'nchanges'        => '$1 {{PLURAL:$1|Annerenge|Annerengen}}',
'recentchanges'   => 'Lääste Annerengen',
'diff'            => 'Unnerschied',
'hist'            => 'Versione',
'hide'            => 'ferbierge',
'minoreditletter' => 'L',
'newpageletter'   => 'N',
'rc-change-size'  => '$1 {{PLURAL:$1|Byte|Bytes}}',

# Upload
'upload'                      => 'Hoochleede',
'filename'                    => 'Doatäinoome',
'filedesc'                    => 'Beschrieuwenge, Wälle',
'fileuploadsummary'           => 'Beschrieuwenge/Wälle:',
'filesource'                  => 'Wälle',
'ignorewarning'               => 'Woarschauenge ignorierje un Doatäi daach spiekerje.',
'ignorewarnings'              => 'Woarschauengen ignorierje',
'illegalfilename'             => 'Die Doatäinoome "$1" änthaalt ap minste een nit toulät Teeken. Benaam jädden ju Doatäi uum un fersäik, hier fon näien hoochtouleeden.',
'badfilename'                 => 'Die Datäi-Noome is automatisk annerd tou "$1".',
'large-file'                  => 'Jädden neen Bielde uur $1 hoochleede; disse Doatäi is $2 groot.',
'largefileserver'             => 'Disse Doatäi is tou groot, deer die Server so konfigurierd is, dät Doatäien bloot bit tou ne bestimde Grööte apzeptierd wäide.',
'emptyfile'                   => 'Ju hoochleedene Doatäi is loos. Die Gruund kon n Typfailer in dän Doatäinoome weese. Kontrollierje jädden, of du ju Doatäi wuddelk hoochleede wolt.',
'fileexists'                  => "Ne Doatäi mäd dissen Noome bestoant al. Wan du ap 'Doatäi spiekerje' klikst, wäd ju Doatäi
uurschrieuwen. Unner $1 koast du die bewisje, of du dät wuddelk wolt.",
'fileexists-forbidden'        => 'Mäd dissen Noome bestoant al ne Doatäi. Gung jädden tourääch un leede dien Doatäi unner n uur Noome hooch. [[Bielde:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Mäd dissen Noome bestoant al ne Doatäi ap Wikipedia Commons. Gung jädden tourääch un leede dien Doatäi unner n uur Noome hooch. [[Bielde:$1|thumb|center|$1]]',

'nolicense' => 'naan Foaruutwoal',

# Image list
'imagelist'             => 'Bieldelieste',
'imagelisttext'         => 'Hier is ne Lieste fon $1 Bielden, sortierd $2.',
'getimagelist'          => 'Leede Bieldelieste',
'ilsubmit'              => 'Säik',
'byname'                => 'ätter Noome',
'bydate'                => 'ätter Doatum',
'bysize'                => 'ätter Grööte',
'imgdelete'             => 'Läskje',
'imagelinks'            => 'Bieldeferwiese',
'linkstoimage'          => 'Do foulgjende Artikkele benutsje disse Bielde: <br /><small>(Moonige Sieden wäide eventuell
moorfooldich liested, konnen in säildene Falle oawers uk miste. Dät kumt fon oolde Failere in
dän Software häär, man schoadet fääre niks.)</small>',
'nolinkstoimage'        => 'Naan Artikkel benutset disse Bielde.',
'noimage-linktext'      => 'hoochleede',
'imagelist_description' => 'Beschrieuwenge',

# File reversion
'filerevert-comment' => 'Kommentoar:',

# MIME search
'mimesearch' => 'Säike ätter MIME-Typ',
'download'   => 'Deelleede',

# List redirects
'listredirects' => 'Lieste fon Fäärelaitengs-Sieden',

# Random page
'randompage' => 'Toufällige Artikkel',

'disambiguations'     => 'Begriepskläärengssieden',
'disambiguationspage' => 'Template:Begriepskläärenge',

'doubleredirects'     => 'Dubbelde Fääre-Laitengen',
'doubleredirectstext' => '<b>Oachtenge:</b> Disse Lieste kon "falske Positive" änthoolde. Dät is dan dän Fal, wan aan
Redirect buute dän Redirect-Ferwies noch wiedere Text mäd uur Ferwiesen änthaalt. Doo
Lääste schällen dan wächhoald wäide.',

'brokenredirects'     => 'Ferkierde Truchferwiese',
'brokenredirectstext' => 'Disse Truchferwiese laitje tou nit existierjende Artikkel:',

# Miscellaneous special pages
'ncategories'          => '$1 {{PLURAL:$1|Kategorie|Kategorien}}',
'nlinks'               => '{{PLURAL:$1|1 Ferwiese|$1 Ferwiese}}',
'lonelypages'          => 'Ferwaisde Sieden',
'mostlinked'           => 'Maast ferlinkede Sieden',
'mostlinkedcategories' => 'Maast benutsede Kategorien',
'mostcategories'       => 'Maast kategorisierde Artikkele',
'mostimages'           => 'Maast benutsede Bielden',
'mostrevisions'        => 'Artikkel mäd do maaste Versione',
'allpages'             => 'Aal Artikkele',
'longpages'            => 'Loange Artikkel',
'deadendpages'         => 'Siede sunner Ferwiese',
'listusers'            => 'Benutser-Lieste',
'specialpages'         => 'Sunnersieden',
'newpages'             => 'Näie Artikkel',
'move'                 => 'ferschäuwen',
'movethispage'         => 'Artikel ferschuuwe',

'categoriespagetext' => 'Do foulgjende Kategorien existierje in de Wiki.',
'data'               => 'Failer in dän Doatenboank',
'groups'             => 'Benutsergruppen',

# Special:Log
'log'         => 'Logbouke',
'alllogstext' => 'Dit is ne kombinierde Anwiesenge fon aal Logs fon {{SITENAME}}.',
'logempty'    => 'Neen paasende Iendraage.',

# Special:Allpages
'nextpage'          => 'Naiste Siede ($1)',
'allpagesfrom'      => 'Sieden wiese fon:',
'allarticles'       => 'Aal do Artikkele',
'allinnamespace'    => 'Aal Sieden in $1 Noomenruum',
'allnotinnamespace' => 'Aal Sieden, bute in dän $1 Noomenruum',
'allpagesprev'      => 'Foargungende',
'allpagesnext'      => 'Naiste',
'allpagessubmit'    => 'Anweende',

# E-mail user
'mailnologin'     => 'Du bäst nit anmälded.',
'mailnologintext' => 'Du moast [[{{ns:special}}:Userlogin|anmälded weese]] un sälwen ne [[{{ns:special}}:Confirmemail|gultige]] E-Mail-Adrässe anroat hääbe, uum uur Benutsere ne E-Mail tou seenden.',
'emailuser'       => 'Seende E-Mail an disse Benutser',
'emailpage'       => 'E-mail an Benutser',
'emailpagetext'   => 'Wan disse Benutser ne gultige Email-Adrässe anroat häd, konnen Jie him mäd dän unnerstoundene Formuloar ne E-mail seende. As Ouseender wäd ju E-mail-Adrässe uut Jou Ienstaalengen iendrain, deermäd die Benutser Jou oantwoudje kon.',
'noemailtitle'    => 'Neen Email-Adrässe',
'noemailtext'     => 'Disse Benutser häd neen gultige Email-Adrässe anroat of moate neen E-Mail fon uur Benutsere ämpfange.',
'emailfrom'       => 'Fon',
'emailto'         => 'An',
'emailsubject'    => 'Beträf',
'emailmessage'    => 'Ättergjucht',
'emailsend'       => 'Seende',
'emailsent'       => 'Begjucht fersoand',
'emailsenttext'   => 'Jou Begjucht is soand wuuden.',

# Watchlist
'watchlist'       => 'Beooboachtengslieste',
'mywatchlist'     => 'Beooboachtengslieste',
'addedwatch'      => 'An Foulgelieste touföiged.',
'addedwatchtext'  => "Die Artikkel \"[[:\$1]]\" wuude an dien [[Special:Watchlist|Foulgelieste]] touföiged.
Leetere Annerengen an dissen Artikkel un ju touheerende Diskussionssiede wäide deer liested
un die Artikkel wäd in ju [[Special:Recentchanges|fon do lääste Annerengen]] in '''Fatschrift''' anroat. 

Wan du die Artikkel wier fon ju Foulgelieste ou hoalje moatest, klik ap ju Siede ap \"Ferjeet disse Siede\".",
'iteminvalidname' => "Problem mäd dän Iendraach '$1', ungultige Noome...",

'enotif_mailer'                => '{{SITENAME}} tält Bescheed uur Email',
'enotif_reset'                 => 'Markier aal besoachte Sieden',
'enotif_newpagetext'           => 'Dit is ne näie Siede.',
'enotif_impersonal_salutation' => '{{SITENAME}} Benutser',
'changed'                      => 'annerd',
'created'                      => 'näi anlaid',
'enotif_subject'               => '{{SITENAME}} Siede $PAGETITLE wuude $CHANGEDORCREATED fon $PAGEEDITOR',
'enotif_lastdiff'              => '$1 wiest alle Annerengen mäd aan Glap.',
'enotif_body'                  => 'Ljoowe $WATCHINGUSERNAME, 

ju {{SITENAME}} Siede $PAGETITLE wuude fon $PAGEEDITOR an dän $PAGEEDITDATE $CHANGEDORCREATED, ju aktuälle Version is: $PAGETITLE_URL 

$NEWPAGE 

Touhoopefoatenge fon dän Editor: $PAGESUMMARY $PAGEMINOREDIT 

Kontakt toun Editor: 
Mail $PAGEEDITOR_EMAIL 
Wiki $PAGEEDITOR_WIKI 

Deer wäide soloange neen wiedere Mails toun Bescheed soand, bit Jie ju Siede wier besäike. Ap Jou Beooboachtengssiede konnen Jie aal Markere toun Bescheed touhoope touräächsätte. 

Jou früntelke {{SITENAME}} Becheedtälsystem 

--- 
Jou Beooboachtengslieste 
{{SERVER}}{{localurl:Special:Watchlist/edit}} 

Hälpe tou ju Benutsenge rakt 
{{SERVER}}{{localurl:WikiHelpdesk}}',

# Delete/protect/revert
'deletepage'        => 'Siede läskje',
'confirm'           => 'Bestäätigje',
'excontent'         => "Oolde Inhoold: '$1'",
'excontentauthor'   => "Inhoold waas: '$1' (eensige Benutser: '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'     => "Inhoold foar dät Loosmoakjen fon de Siede: '$1'",
'exblank'           => 'Siede waas loos',
'confirmdelete'     => 'Läskenge bestäätigje',
'deletesub'         => '(Läskje "$1")',
'historywarning'    => 'WOARSCHAUENGE: Ju Siede, ju du läskje moatest, häd ne Versionsgeschichte: &nbsp;',
'confirmdeletetext' => 'Jie sunt deerbie, n Artikkel of ne Bielde un aal allere Versione foar altied uut dän Doatenboank tou läskjen. Bitte bestäätigje Jie Jou Apsicht, dät tou dwoon, dät Jie Jou do Konsekwänsen bewust sunt, un dät Jie in Uureenstämmenge mäd uus [[{{MediaWiki:policy-url}}]] honnelje.',
'deletedtext'       => '"$1" wuude läsked. 
In $2 fiende Jie ne Lieste fon do lääste Läskengen.',
'deletedarticle'    => '"$1" wuude läsked',
'dellogpage'        => 'Läsk-Logbouk',
'dellogpagetext'    => 'Hier is ne Lieste fon do lääste Läskengen.',
'deletionlog'       => 'Läsk-Logbouk',
'deletecomment'     => 'Gruund foar ju Läskenge',
'cantrollback'      => 'Disse Annerenge kon nit touräächstoald wäide; deer et naan fröieren Autor rakt.',
'editcomment'       => 'Ju Annerengskommentoar waas: "<i>$1</i>".', # only shown if there is an edit comment

# Namespace form on various pages
'namespace' => 'Noomensruum:',
'invert'    => 'Uutwoal uumekiere',

# Contributions
'contributions' => 'Benutserbiedraage',
'mycontris'     => 'Oaine Biedraage',
'nocontribs'    => 'Deer wuuden neen Annerengen foar disse Kriterien fuunen.',

# What links here
'linkshere'   => "Do foulgjende Sieden ferwiese hierhäär:  '''[[:$1]]''': <br /><small>(Moonige Sieden wäide eventuell moorfooldich liested, konnen in säildene Falle oawers uk miste. Dät kumt fon oolde Failere in dän Software häär, man schoadet fääre niks.)</small>",
'nolinkshere' => "Naan Artikkel ferwiest hierhäär: '''[[:$1]]'''.",
'isredirect'  => 'Fäärelaitengs-Siede',
'istemplate'  => 'Foarloagenienbiendenge',

# Block/unblock
'blockip'            => 'Blokkierje Benutser',
'ipaddress'          => 'IP-Adrässe:',
'ipbexpiry'          => 'Oulooptied (Speerduur):',
'ipbreason'          => 'Begruundenge:',
'ipbsubmit'          => 'Adrässe blokkierje',
'ipbother'           => 'Uur Duur (ängelsk):',
'ipboptions'         => '1 Uure:1 hour,2 Uuren:2 hours,6 Uuren:6 hours,1 Dai:1 day,3 Deege:3 days,1 Wiek:1 week,2 Wieke:2 weeks,1 Mound:1 month,3 Mounde:3 months,1 Jier:1 year,Uunbestimd:indefinite',
'ipbotheroption'     => 'Uur Duur',
'badipaddress'       => 'Dissen Benutser bestoant nit, d.h. die Noome is falsk',
'blockipsuccesssub'  => 'Blokkoade geloangen',
'blockipsuccesstext' => 'Ju IP-Adrässe [[Special:Contributions/$1|$1]] wuude blokkierd.
<br />[[Special:Ipblocklist|Lieste fon Blokkoaden]].',
'ipusubmit'          => 'Disse Adrässe fräireeke',
'ipblocklist'        => 'Lieste fon blokkierde IP-Adrässen',
'blocklistline'      => '$1, $2 blokkierde $3 ($4)',
'blocklink'          => 'blokkierje',
'contribslink'       => 'Biedraage',
'autoblocker'        => 'Du wierst blokkierd, deer du eene IP-Adrässe mäd "[[User:$1|$1]]" benutsjen dääst. Foar ju Blokkierenge fon dän Benutser waas as Gruund anroat: "$2".',
'blocklogpage'       => 'Benutserblokkoaden-Logbouk',
'blocklogentry'      => '[[$1]] blokkierd foar n Tiedruum fon: $2 $3',
'blocklogtext'       => 'Dit is n Logbouk fon Speerengen un Äntspeerengen fon Benutsere. Ju Sunnersiede fiert aal aktuäl speerde Benutsere ap, iensluutend automatisk blokkierde IP-Adrässe.',
'ipb_expiry_invalid' => 'Ju anroate Oulooptied is nit gultich.',
'ip_range_invalid'   => 'Uungultige IP-Adräsberäk.',

# Developer tools
'lockdb'            => 'Doatenboank speere',
'lockdbtext'        => 'Mäd dät Speeren fon de Doatenboank wäide aal Annerengen an Benutserienstaalengen, Beooboachtengsliesten, Artikkele usw. ferhinnerd. Betäätigje jädden dien Apsicht, ju Doatenboank tou speeren.',
'lockconfirm'       => 'Jee, iek moate ju Doatenboank speere.',
'lockbtn'           => 'Doatenboank speere',
'locknoconfirm'     => 'Du hääst dät Bestäätigengsfäild nit markierd.',
'lockdbsuccesssub'  => 'Doatenboank wuude mäd Ärfoulch speerd',
'lockdbsuccesstext' => 'Ju {{SITENAME}}-Doatenboank wuude speerd.<br />Reek jädden [[Special:Unlockdb|ju Doatenboank wier fräi]], so gau ju Woarschauenge ousleeten is.',

# Move page
'movepage'                => 'Siede ferschäuwen',
'movepagetext'            => 'Mäd dissen Formular koast du ne Siede touhoope mäd aal Versione tou n uur Noome ferschuuwe. Foar dän oolde Noome wäd ne Fäärelaitenge tou dän Näie iengjucht. Ferwiese ap dän oolde Noome wäide nit annerd.',
'movepagetalktext'        => "Ju touheerige Diskussionssiede wäd, sofier deer, mee ferschäuwen, '''of dät moast weese''' 
* du ferschufst ju Siede in n uur [[Wikipedia:Noomensruum|Noomensruum]] 
* deer bestoant al n Diskussionssiede mäd dän näie Noome 
* du wäälst ju unnerstoundene Option ou. 

In disse Falle moast du ju Siede, wan wonsked, fon Hounde ferschuuwe. Jädden dän '''näie''' Tittel unner '''Siel''' iendreege, deerunner ju Uumnaamenge jädden '''begründje'''.",
'movenologin'             => 'Du bäst nit anmälded',
'movenologintext'         => 'Du moast n registrierden Benutser un  [[Special:Userlogin|angemeldet]] weese, uum ne Siede ferschuuwe tou konnen.',
'newtitle'                => 'Tou dän näie Tittel:',
'movepagebtn'             => 'Siede ferschäuwen',
'articleexists'           => 'Dät rakt al n Siede mäd disse Noome, of uurs is die Noome dän du anroat hääst, nit toulät.
Fersäik jädden n uur Noome.',
'movedto'                 => 'ferschäuwen ätter',
'movetalk'                => 'Ju Diskussionssiede mee ferschuuwe, wan muugelk.',
'movelogpage'             => 'Ferschäuwengs-Logbouk',
'movelogpagetext'         => 'Dit is ne Lieste fon aal ferschäuwene Sieden.',
'movereason'              => 'Kuute Begründenge:',
'delete_and_move'         => 'Läskje un ferschuuwe',
'delete_and_move_text'    => '==Sielartikkel is al deer, läskje?== 

Die Artikkel "[[$1]]" existiert al. Moatest du him foar ju Ferschuuwenge läskje?',
'delete_and_move_confirm' => 'Jee, Sielartikkel foar ju Ferschuuwenge läskje',
'delete_and_move_reason'  => 'Läsked uum Plats tou moakjen foar Ferschuuwenge',
'immobile_namespace'      => 'Die wonskede Siedentittel is aan besunneren; ju Siede kon nit in dissen (uur) Noomensruum ferschäuwen wäide.',

# Export
'export'          => 'Sieden exportierje',
'exporttext'      => 'Du koast dän Täkst un ju Beoarbaidengshistorie fon ne bestimde Siede of fon n Uutwoal fon Sieden ättter XML exportierje.',
'exportcuronly'   => 'Bloot ju aktuälle Version fon de Siede exportierje',
'exportnohistory' => "--- 
'''Waiwiesenge:''' Die Export fon komplette Versionsgeschichten is uut Performancegruunden bit ap fääre nit muugelk. Ne Deelleedenge fon Versiongeschichten as Dump is oawers muugelk unner [http://download.wikimedia.org/ download.wikimedia.org] — ''Wikimedia-Serveradministratoren''.",

# Namespace 8 related
'allmessages'        => 'Aal Ättergjuchte',
'allmessagesname'    => 'Noome',
'allmessagesdefault' => 'Standardtext',
'allmessagescurrent' => 'Disse Text',
'allmessagestext'    => 'Dit is ne Lieste fon aal System-Ättergjuchte do in dän MediaWiki-Noomenruum tou Ferföigenge stounde.',

# Thumbnails
'filemissing' => 'Doatäi failt',

# Special:Import
'import'                => 'Sieden importierje',
'importtext'            => 'Exportiere ju Siede fon dän Wälwiki middels [[{{ns:special}}:Export]] un leede ju Doatäi dan uur disse Siede wier hooch.',
'importfailed'          => 'Import failsloain: $1',
'importnotext'          => 'Loos of neen Text',
'importsuccess'         => 'Import fuller Ärfoulch!',
'importhistoryconflict' => 'Deer bestounde al allere Versionen, do mäd disse kollidierje. Muugelkerwiese wuude ju Siede al eer importierd.',
'importnosources'       => 'Foar dän Transwiki Import sunt neen Wällen definierd un dät direkte Hoochleeden fon Versione is blokkierd.',
'importnofile'          => 'Deer is neen Importdoatäi hoochleeden wuuden.',
'importuploaderror'     => 'Dät Hoochleeden fon ju Importdoatäi glipte (sluuch fail). Fielicht is ju Doatäi gratter as toulät.',

# Metadata
'nodublincore'      => 'Dublin-Core-RDF-Metadoaten sunt foar dissen Server deaktivierd.',
'nocreativecommons' => 'Creative-Commons-RDF-Metadoaten sunt foar dissen Server deaktivierd.',

# Attribution
'lastmodifiedatby' => 'Disse Siede wuude toulääst annerd uum $2, $1 fon $3.', # $1 date, $2 time, $3 user
'creditspage'      => 'Siedenstatistik',
'nocredits'        => 'Foar disse Siede sunt neen Informationen deer.',

# Spam protection
'categoryarticlecount' => 'Tou disse Kategorie heere $1 Artikkele.',
'category-media-count' => 'Tou disse Kategorie heere $1 Artikkele.',

# Info page
'infosubtitle' => 'Siedeninformation',

# Math options
'mw_math_png'    => 'Altied as PNG deerstaale',
'mw_math_simple' => 'Eenfache TeX as HTML deerstaale, uurs PNG',
'mw_math_html'   => 'Wan muugelk as HTML deerstaale, uurs PNG',
'mw_math_source' => 'As TeX beläite (foar Textbrowsere)',
'mw_math_modern' => 'Antouräiden foar moderne Browsere',
'mw_math_mathml' => 'MathML',

# Patrolling
'markaspatrolleddiff'   => 'As pröiwed markierje',
'markaspatrolledtext'   => 'Dissen Artikkel as pröiwed markierje',
'markedaspatrolled'     => 'As pröiwed markierd',
'markedaspatrolledtext' => 'Ju uutwoalde Artikkelannerenge wuude as pröiwed markierd.',

# Patrol log
'patrol-log-diff' => 'Version $1',

# Image deletion
'deletedrevision' => 'Oolde Version $1 läsked',

# Browsing diffs
'nextdiff' => 'Toun naisten Versionsunnerscheed →',

# Media information
'mediawarning' => "'''Warnung:''' Disse Oard fon Doatäi kon n schoadelken Programcode änthoolde. Truch dät Deelleeden of Eepenjen fon dissen Doatäi kon dän Computer Schoade toubroacht wäide. Al dät Anklikken fon dän Link kon deertou fiere, dät die Browser ju Doatäi eepen moaket un uunbekoande Programcode tou Uutfierenge kumt. Do Bedrieuwere fon ju Wikipedia uurnieme neen Feroantwoudenge foar dän Inhoold fon disse Doatäi! Schuul disse Doatäi wuddelk schoadelke Programcode änthoolde, schuul n Administrator informierd wäide.<hr />",

# Special:Newimages
'newimages' => 'Näie Bielde',
'noimages'  => 'neen Bielden fuunen.',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'hours-abbrev' => 'U',

# Metadata
'metadata'          => 'Metadoaten',
'metadata-help'     => 'Disse Doatäi änthaalt wiedere Informatione, do in de Räägel von dän Digitoalkamera of dän ferwoanden Scanner stammen dwo. Truch ätterdraine Beoarbaidenge fon ju Originoaldoatäi konnen eenige Details annerd wuuden weese.',
'metadata-expand'   => 'Wiedere Details ienbländje',
'metadata-collapse' => 'Details uutbländje',
'metadata-fields'   => 'Do foulgjende Fäildere fon do EXIF-Metadoaten in disse Media Wiki-Ättergjucht wäide ap Bieldbeschrieuwengssieden anwiesd; wiedere standdoardmäitich "ienklapte" Details konnen anwiesd wäide. 
* make 
* model 
* fnumber 
* datetimeoriginal 
* exposuretime 
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Bratte',
'exif-imagelength'                 => 'Laangte',
'exif-bitspersample'               => 'Bits pro Faawenkomponente',
'exif-compression'                 => 'Oard fon ju Kompression',
'exif-orientation'                 => 'Kamera-Uutgjuchtenge',
'exif-planarconfiguration'         => 'Doatenuutgjuchtenge',
'exif-yresolution'                 => 'Vertikoale Aplöösenge',
'exif-resolutionunit'              => 'Mäite-Eenhaid fon ju Aplöösenge',
'exif-stripoffsets'                => 'Bieldedoatenfersät',
'exif-rowsperstrip'                => 'Antaal Riegen pro Striepe',
'exif-jpeginterchangeformat'       => 'Offset tou JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Gratte fon do JPEG SOI-Doaten in Bytes',
'exif-transferfunction'            => 'Uurdreegengsfunktion',
'exif-datetime'                    => 'Spiekertiedpunkt',
'exif-imagedescription'            => 'Bieldetittel',
'exif-make'                        => 'Häärstaaler',
'exif-model'                       => 'Modäl',
'exif-artist'                      => 'Photograph',
'exif-copyright'                   => 'Uurheebergjuchte',
'exif-exifversion'                 => 'Exif-Version',
'exif-flashpixversion'             => 'unnerstöände Flashpix-Version',
'exif-colorspace'                  => 'Faawenruum',
'exif-componentsconfiguration'     => 'Betjuudenge fon älke Komponente',
'exif-compressedbitsperpixel'      => 'Komprimierde Bits pro Pixel',
'exif-pixelydimension'             => 'Gultige Bieldebratte',
'exif-pixelxdimension'             => 'Gultige Bieldehöchte',
'exif-makernote'                   => 'Häärstaalernotiz',
'exif-usercomment'                 => 'Benutserkommentoare',
'exif-relatedsoundfile'            => 'Touheerige Toondoatäi',
'exif-datetimeoriginal'            => 'Ärfoatengstiedpunkt',
'exif-datetimedigitized'           => 'Digitalisierengstiedpunkt',
'exif-subsectime'                  => 'Spiekertiedpunkt',
'exif-subsectimeoriginal'          => 'Ärfoatengstiedpunkt',
'exif-subsectimedigitized'         => 'Digitoalisierengstiedpunkt',
'exif-exposuretime'                => 'Beljoachtengsduur',
'exif-exposuretime-format'         => '$1 Sekunden ($2)',
'exif-fnumber'                     => 'Blände',
'exif-exposureprogram'             => 'Beljuchtengsprogram',
'exif-spectralsensitivity'         => 'Beljoachtengstiedwäid',
'exif-isospeedratings'             => 'Film- of Sensorämpfiendelkaid (ISO)',
'exif-oecf'                        => 'Optoelektroniske Uumreekenengsfaktor',
'exif-aperturevalue'               => 'Bländenwäid',
'exif-brightnessvalue'             => 'Ljoachtegaidswäid',
'exif-exposurebiasvalue'           => 'Beljuchtengsfoargoawe',
'exif-maxaperturevalue'            => 'Grootste Blände',
'exif-subjectdistance'             => 'Fierte',
'exif-meteringmode'                => 'Meetferfoaren',
'exif-lightsource'                 => 'Luchtwälle',
'exif-flash'                       => 'Blits (Loai!)',
'exif-flashenergy'                 => 'Blitsstäärke',
'exif-focalplanexresolution'       => 'Sensoraplöösenge horizontoal',
'exif-focalplaneyresolution'       => 'Sensoraplöösenge vertikoal',
'exif-focalplaneresolutionunit'    => 'Eenhaid fon Sensoraplöösenge',
'exif-subjectlocation'             => 'Motivstandploats',
'exif-exposureindex'               => 'Beljuchtengsindex',
'exif-sensingmethod'               => 'Meetmethode',
'exif-filesource'                  => 'Wälle fon ju Doatäi',
'exif-scenetype'                   => 'Scenetyp',
'exif-cfapattern'                  => 'CFA-Muster',
'exif-customrendered'              => 'Benutserdefinierde Bieldeferoarbaidenge',
'exif-exposuremode'                => 'Beljuchtengsmodus',
'exif-whitebalance'                => 'Wiet-Ougliek',
'exif-digitalzoomratio'            => 'Digitoalzoom',
'exif-focallengthin35mmfilm'       => 'Baadenwiete',
'exif-scenecapturetype'            => 'Apnoame-Oard',
'exif-gaincontrol'                 => 'Ferstäärkenge',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Säädigenge',
'exif-sharpness'                   => 'Schäärpegaid',
'exif-devicesettingdescription'    => 'Reewen-Ienstaalenge',
'exif-subjectdistancerange'        => 'Motivfierte',
'exif-imageuniqueid'               => 'Bielde-ID',
'exif-gpsaltituderef'              => 'Beluukengshöchte',
'exif-gpsaltitude'                 => 'Höchte',
'exif-gpsdestlatituderef'          => 'Referenz foar ju Bratte',
'exif-gpsdestlatitude'             => 'Bratte',
'exif-gpsdestlongituderef'         => 'Referenz foar ju Laangte',
'exif-gpsdestlongitude'            => 'Laangte',
'exif-gpsdestbearingref'           => 'Referenz foar Motivgjuchte',
'exif-gpsdestbearing'              => 'Motivgjuchte',
'exif-gpsdestdistanceref'          => 'Referenz foar Motivfierte',
'exif-gpsdestdistance'             => 'Motivfierte',
'exif-gpsareainformation'          => 'Noome fon dät GPS-Gestrich',
'exif-gpsdatestamp'                => 'GPS-Doatum',

# EXIF attributes
'exif-compression-1' => 'Uunkomprimierd',

'exif-orientation-1' => 'Normoal', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Horizontoal uumewoand', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Uum 180° uumewoand', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Vertikoal uumewoand', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Juun dän Klokkenwiesersin uum 90° troald un vertikoal uumewoand', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Uum 90° in Klokkenwiesersin troald', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Uum 90° in Klokkenwiesersin troald un vertikoal uumewoand', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Uum 90° juun dän Klokkenwiesersin troald', # 0th row: left; 0th column: bottom

'exif-componentsconfiguration-0' => 'Bestoant nit',

'exif-exposureprogram-0' => 'Uunbekoand',
'exif-exposureprogram-1' => 'Manuäl',
'exif-exposureprogram-2' => 'Standoardprogram',
'exif-exposureprogram-3' => 'Tiedautomatik',
'exif-exposureprogram-4' => 'Bländenautomatik',
'exif-exposureprogram-5' => 'Kreativprogram mäd Befoarluukenge fon ne hooge Schäärpendjupte',
'exif-exposureprogram-6' => 'Aktion-Program mäd Befoarluukenge fon ne kute Beljoachtengstied',
'exif-exposureprogram-7' => 'Portrait-Program',
'exif-exposureprogram-8' => 'Londskupsapnoamen',

'exif-subjectdistance-value' => '$1 Meters',

'exif-meteringmode-0'   => 'Uunbekoand',
'exif-meteringmode-1'   => 'in n Truchsleek',
'exif-meteringmode-2'   => 'Middezentrierd',
'exif-meteringmode-3'   => 'Punktmeetenge',
'exif-meteringmode-4'   => 'Moorfachpunktmeetenge',
'exif-meteringmode-5'   => 'Muster',
'exif-meteringmode-6'   => 'Bieldedeel',
'exif-meteringmode-255' => 'Uur',

'exif-lightsource-0'   => 'Uunbekoand',
'exif-lightsource-1'   => 'Deegeslucht',
'exif-lightsource-3'   => 'Gloilaampe',
'exif-lightsource-4'   => 'Blits (Loai)',
'exif-lightsource-9'   => 'Fluch Weeder',
'exif-lightsource-10'  => 'beleekene Luft',
'exif-lightsource-11'  => 'Schaad',
'exif-lightsource-17'  => 'Standoardlucht A',
'exif-lightsource-18'  => 'Standoardlucht B',
'exif-lightsource-19'  => 'Standoardlucht C',
'exif-lightsource-255' => 'Uur Luchtwälle',

'exif-focalplaneresolutionunit-2' => 'Tuume',

'exif-sensingmethod-1' => 'Uundefinierd',
'exif-sensingmethod-2' => 'Een-Chip-Faawesensor',
'exif-sensingmethod-3' => 'Twoo-Chip-Faawesensor',
'exif-sensingmethod-4' => 'Trjoo-Chip-Faawesensor',

'exif-scenetype-1' => 'Normoal',

'exif-customrendered-0' => 'Standoard',
'exif-customrendered-1' => 'Benutserdefinierd',

'exif-whitebalance-0' => 'Automatisk',
'exif-whitebalance-1' => 'Manuäl',

'exif-scenecapturetype-0' => 'Standoard',
'exif-scenecapturetype-1' => 'Londskup',
'exif-scenecapturetype-2' => 'Portrait',
'exif-scenecapturetype-3' => 'Noachtszene',

'exif-gaincontrol-0' => 'Neen',

'exif-contrast-0' => 'Normoal',
'exif-contrast-1' => 'Swäk',
'exif-contrast-2' => 'Stäärk',

'exif-saturation-0' => 'Normoal',
'exif-saturation-1' => 'Min Säädigenge',
'exif-saturation-2' => 'Hooge Säädigenge',

'exif-sharpness-0' => 'Normoal',
'exif-sharpness-1' => 'Schäärpegaid min',
'exif-sharpness-2' => 'Schäärpegaid stäärk',

'exif-subjectdistancerange-0' => 'Uunbekoand',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Nai',
'exif-subjectdistancerange-3' => 'Fier',

# External editor support
'edit-externally'      => 'Disse Doatäi mäd n extern Program beoarbaidje',
'edit-externally-help' => 'Sjuch [http://meta.wikimedia.org/wiki/Hilfe:Externe_Editoren Installations-Anweisungen] foar
wiedere Informatione.',

# 'all' in various places, this might be different for inflected languages
'imagelistall'  => 'aal',
'namespacesall' => 'aal',

# Delete conflict
'deletedwhileediting' => 'Oachtenge: Disse Siede wuude al läsked ätter dät du anfangd hiedest, hier tou beoarbaidjen!
Wan du disse Siede spiekerst, wäd ju deeruum näi anlaid.',

# action=purge
'confirm_purge' => 'Dän Cache fon disse Siede loosmoakje?

$1',

# AJAX search
'hideresults' => 'ferbierge',

);

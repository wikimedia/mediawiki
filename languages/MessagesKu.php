<?php
/** Kurdish (Kurdî / كوردي)
  *
  * @package MediaWiki
  * @subpackage Language
  */

$namespaceNames = array(
	NS_MEDIA            => 'Medya',
	NS_SPECIAL          => 'Taybet',
	NS_MAIN             => '',
	NS_TALK             => 'Nîqaş',
	NS_USER             => 'Bikarhêner',
	NS_USER_TALK        => 'Bikarhêner_nîqaş',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_nîqaş',
	NS_IMAGE            => 'Wêne',
	NS_IMAGE_TALK       => 'Wêne_nîqaş',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_nîqaş',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_nîqaş',
	NS_HELP             => 'Alîkarî',
	NS_HELP_TALK        => 'Alîkarî_nîqaş',
	NS_CATEGORY         => 'Kategorî',
	NS_CATEGORY_TALK    => 'Kategorî_nîqaş'
);

$messages = array(
'skinpreview'           => '(Pêşdîtin)',
'sunday'                => 'yekşem',
'monday'                => 'duşem',
'tuesday'               => 'Sêşem',
'wednesday'             => 'Çarşem',
'thursday'              => 'Pêncşem',
'friday'                => 'În',
'saturday'              => 'şemî',
'january'               => 'Rêbendan',
'february'              => 'reşemî',
'march'                 => 'adar',
'april'                 => 'avrêl',
'may_long'              => 'gulan',
'june'                  => 'pûşper',
'july'                  => 'Tîrmeh',
'august'                => 'tebax',
'september'             => 'rezber',
'october'               => 'kewçêr',
'november'              => 'sermawez',
'december'              => 'Berfanbar',
'jan'                   => 'rêb',
'feb'                   => 'reş',
'mar'                   => 'adr',
'apr'                   => 'avr',
'may'                   => 'gul',
'jun'                   => 'pşr',
'jul'                   => 'tîr',
'aug'                   => 'teb',
'sep'                   => 'rez',
'oct'                   => 'kew',
'nov'                   => 'ser',
'dec'                   => 'ber',
'categories'            => '{{PLURAL:$1|Kategorî|Kategorî}}',
'category_header'       => 'Gotarên di kategoriya "$1" de',
'subcategories'         => 'Binkategorî',
'mainpage'              => 'Destpêk',
'portal'                => 'Portala komê',
'portal-url'            => 'Project:Portala komê',
'about'                 => 'Der barê',
'aboutsite'             => 'Der barê {{SITENAME}}',
'aboutpage'             => '{{SITENAME}}:Der barê',
'article'               => 'Gotar',
'help'                  => 'Alîkarî',
'helppage'              => 'Help:Alîkarî',
'bugreports'            => 'Raporên çewtiyan',
'bugreportspage'        => 'Project:Raporên çewtiyan',
'sitesupport'           => 'Ji bo Weqfa Wikimedia Beş',
'faq'                   => 'Pirs û Bersîv (FAQ)',
'faqpage'               => 'Project:Pirs û Bersîv',
'edithelp'              => 'Alîkarî ji bo guherandin',
'edithelppage'          => 'Help:Rûpeleke çawa biguherînim',
'cancel'                => 'Betal',
'qbfind'                => 'Bibîne',
'qbbrowse'              => 'Bigere',
'qbedit'                => 'Biguherîne',
'qbpageoptions'         => 'Ev rûpel',
'qbmyoptions'           => 'Rûpelên min',
'qbspecialpages'        => 'Rûpelên taybet',
'moredotdotdot'         => 'Zêde...',
'mypage'                => 'Rûpela min',
'mytalk'                => 'Rûpela guftûgo ya min',
'anontalk'              => 'Guftûgo ji bo vê IPê',
'navigation'            => 'Navîgasyon',
'currentevents'         => 'Bûyerên rojane',
'currentevents-url'     => 'Bûyerên rojane',
'disclaimers'           => 'Ferexetname',
'errorpagetitle'        => 'Çewtî (Error)',
'returnto'              => 'Bizivire $1.',
'tagline'               => 'Ji {{SITENAME}}',
'search'                => 'Lêbigere',
'searchbutton'          => 'Lêbigere',
'go'                    => 'Gotar',
'history'               => 'Dîroka rûpelê',
'history_short'         => 'Dîrok',
'info_short'            => 'Zanyarî',
'printableversion'      => 'Versiyon ji bo çapkirinê',
'print'                 => 'Çap',
'edit'                  => 'Biguherîne',
'editthispage'          => 'Vê rûpelê biguherîne',
'delete'                => 'Jê bibe',
'deletethispage'        => 'Vê rûpelê jê bibe',
'protect'               => 'Biparêze',
'protectthispage'       => 'Vê rûpelê biparêze',
'unprotect'             => 'Parastinê rake',
'unprotectthispage'     => 'Parastina vê rûpelê rake',
'newpage'               => 'Rûpela nû',
'talkpage'              => 'Vê rûpelê guftûgo bike',
'specialpage'           => 'Rûpela taybet',
'personaltools'         => 'Amûrên şexsî',
'postcomment'           => 'Şîroveyekê bişîne',
'articlepage'           => 'Li naveroka rûpelê binêre',
'talk'                  => 'Guftûgo',
'views'                 => 'Dîtin',
'toolbox'               => 'Qutiya amûran',
'userpage'              => 'Rûpelê vê/vî bikarhênerê/î temaşe bike',
'viewtalkpage'          => 'Guftûgoyê temaşe bike',
'otherlanguages'        => 'Zimanên din',
'redirectedfrom'        => '(Hat ragihandin ji $1)',
'lastmodifiedat'          => 'Ev rûpel carî dawî di $2, $1 de hat guherandin.',
'viewcount'             => 'Ev rûpel $1 car hat xwestin.',
'copyright'             => 'Ji bo naverokê $1 derbas dibe.',
'protectedpage'         => 'Rûpela parastî',
'badaccess'             => 'Eror li bi dest Hînan',
'ok'                    => 'Temam',
'retrievedfrom'         => 'Ji "$1" hatiye standin.',
'youhavenewmessages'    => '$1 yên te hene ($2).',
'newmessageslink'       => 'Nameyên nû',
'newmessagesdifflink'   => 'Ciyawazî ji revîzyona berê re',
'editsection'           => 'biguherîne',
'editold'               => 'biguherîne',
'toc'                   => 'Tabloya Naverokê',
'showtoc'               => 'nîşan bide',
'hidetoc'               => 'veşêre',
'nstab-main'            => 'Gotar',
'nstab-user'            => 'Bikarhêner',
'nstab-media'           => 'Medya',
'nstab-special'         => 'Taybet',
'nstab-image'           => 'Wêne',
'nstab-mediawiki'       => 'Mesaj',
'nstab-template'        => 'Şablon',
'nstab-help'            => 'Alîkarî',
'nstab-category'        => 'Kategorî',
'nosuchaction'          => 'Çalakiyek bi vê rengê tune',
'nosuchspecialpage'     => 'Rûpeleke taybet bi vê rengê tune',
'error'                 => 'Çewtî (Error)',
'noconnect'             => 'Bibexşîne! Çend pirsgrêkên teknîkî heye, girêdan ji pêşkêşvanê (suxrekirê, server) re niha ne gengaz e. <br />
$1',
'enterlockreason'       => 'Hoyek ji bo bestin binav bike, herweha zemaneke mezende kirî ji bo helgirtina bestinê!',
'filerenameerror'       => 'Navê faylê "$1" nebû "$2".',
'filenotfound'          => 'Dosya bi navê "$1" nehat dîtin.',
'badarticleerror'       => 'Ev çalakî di vê rûpelê de nabe.',
'badtitle'              => 'Sernivîsa nebaş',
'perfcached'            => 'The following data is cached and may not be completely up to date:',
'viewsource'            => 'Çavkanî',
'protectedtext'         => 'Ew rûpel hat qefl kirin û nayê guherandin. Ew jî sedemên xwe heye.
Binihêre:
[[Project:Rûpela parastî]].

Hûn dikarin çavkaniya wê rûpelê bibînin û kopî bikin. Heke hûn dixwazin tiştekî zêde bikin, ji kerema xwe di rûpela guftugoyê binivîsin.',
'logouttitle'           => 'Derketina bikarhêner',
'logouttext'            => '<strong>Tu niha derketî (logged out).</strong><br />
Tu dikarî {{SITENAME}} niha weke bikarhênerekî nediyarkirî bikarbînî, yan jî tu dikarî dîsa bi vî navê xwe yan navekî din wek bikarhêner têkevî. Bila di bîra te de be ku gengaz e hin rûpel mîna ku tu hîn bi navê xwe qeyd kiriyî werin nîşandan, heta ku tu nîşanên çavlêgerandina (browser) xwe jênebî.',
'welcomecreation'       => '<h2>Bi xêr hatî, $1!</h2><p>Hesaba te hat afirandin. Tu dikarî niha tercîhên xwe eyar bikî.',
'loginpagetitle'        => 'Qeyda bikarhêner (User login)',
'yourname'              => 'Navê te wek bikarhêner (user name)',
'yourpassword'          => 'Şîfreya te (password)',
'yourpasswordagain'     => 'Şîfreya xwe careke din binîvîse',
'remembermypassword'    => 'Şifreya min di her rûniştdemê de bîne bîra xwe.',
'loginproblem'          => '<b>Di qeyda te (login) de pirsgirêkek derket.</b><br />Careke din biceribîne!',
'alreadyloggedin'       => '<strong>Bikarhêner $1, tu jixwe têketî!</strong><br />',
'login'                 => 'Têkeve (login)',
'loginprompt'           => '<b>Eger tu xwe nû qeyd bikî, nav û şîfreya xwe hilbijêre.</b> Ji bo xwe qeyd kirinê di {{SITENAME}} de divê ku \'\'cookies\'\' gengaz be.',
'userlogin'             => 'Têkeve an hesabeke nû çêke',
'logout'                => 'Derkeve (log out)',
'userlogout'            => 'Derkeve',
'notloggedin'           => 'Xwe qeyd nekir (not logged in)',
'nologin'               => 'Tu hêj ne endamî? $1.',
'nologinlink'           => 'Bibe endam',
'createaccount'         => 'Hesabê nû çêke',
'gotaccount'            => 'Hesabê te heye? $1.',
'gotaccountlink'        => 'Têkeve (login)',
'createaccountmail'     => 'bi e-name',
'badretype'             => 'Herdu şîfreyên ku te nivîsîn hevûdin nagirin.',
'youremail'             => 'E-maila te*',
'username'              => 'Navê bikarhêner:',
'yourrealname'          => 'Navê te yê rastî*',
'yourlanguage'          => 'Ziman:',
'yournick'              => 'Leqeba te (ji bo îmza)',
'loginerror'            => 'Çewtî (Login error)',
'prefs-help-email'      => '* E-mail (optional): Enables others to contact you through your user or user_talk page without the need of revealing your identity.',
'nocookieslogin'        => 'Ji bo qeydkirina bikarhêneran {{SITENAME}} "cookies" bi kar tîne. Te fonksiyona "cookies" girt. Ji kerema xwe "cookies" gengaz bike û careke din biceribîne.',
'noname'                => 'Navê ku te nivîsand derbas nabe.',
'loginsuccesstitle'     => 'Têketin serkeftî!',
'loginsuccess'          => 'Tu niha di {{SITENAME}} de qeydkirî yî wek "$1".',
'nosuchuser'            => 'Bikarhênera/ê bi navê "$1" tune. Navê rast binivîse an bi vê formê <b>hesabeke nû çêke</b>. (Ji bo hevalên nû "Têkeve" çênabe!)',
'wrongpassword'         => 'Şifreya ku te nivîsand şaş e. Ji kerema xwe careke din biceribîne.',
'mailmypassword'        => 'Şîfreyeke nû bi e-mail ji min re bişîne',
'noemail'               => 'Navnîşana bikarhênerê/î "$1" nehat tomar kirine.',
'passwordsent'          => 'Ji navnîşana e-mail ku ji bo "$1" hat tomarkirin şîfreyekê nû hat şandin. Vê bistîne û dîsa têkeve.',
'acct_creation_throttle_hit'=> 'Biborîne! Te hesab $1 vekirine. Tu êdî nikarî hesabên din vekî.',
'bold_sample'           => 'Nivîsa estûr',
'bold_tip'              => 'Nivîsa estûr',
'italic_sample'         => 'Nivîsa xwar (îtalîk)',
'italic_tip'            => 'Nivîsa xwar (îtalîk)',
'link_sample'           => 'Navê lînkê',
'link_tip'              => 'Lînka hundir',
'extlink_sample'        => 'http://www.minak.com navê lînkê',
'extlink_tip'           => 'Lînka derve (http:// di destpêkê de ji bîr neke)',
'headline_sample'       => 'Nivîsara sernameyê',
'headline_tip'          => 'Sername asta 2',
'nowiki_sample'         => 'Nivîs ku nebe formatkirin',
'image_sample'          => 'Mînak.jpg',
'image_tip'             => 'Wêne li hundirê gotarê',
'media_sample'          => 'Mînak.ogg',
'sig_tip'               => 'Îmze û demxeya wext ya te',
'hr_tip'                => 'Rastexêza berwarî (kêm bi kar bîne)',
'summary'               => 'Kurte û çavkanî (Te çi kir?)',
'subject'               => 'Mijar/sernivîs',
'minoredit'             => 'Ev guheraniyekê biçûk e',
'watchthis'             => 'Vê gotarê bişopîne',
'savearticle'           => 'Rûpelê tomar bike',
'preview'               => 'Pêşdîtin',
'showpreview'           => 'Pêşdîtin',
'showdiff'              => 'Guherandinê nîşan bide',
'anoneditwarning'       => 'Tu ne têketî. Navnîşana IP ya te wê di dîroka guherandina vê rûpelê de bê tomar kirin.',
'blockedtitle'          => 'Bikarhêner hat asteng kirin',
'blockedtext'           => 'Navê bikarhêner an jî navnîşana IP ya te ji aliyê $1 hat asteng kirin. Sedema vê ev  e:<br />\'\'$2\'\'<br />Tu dikarî bi $1 an yek ji [[Project:Koordînator|koordînatorên din]] re ser vê blokê guftûgo bikî. 

Têbînî: Tu nikarî fonksiyona "Ji vê bikarhêner re E-mail bişîne" bi kar bîne eger te navnîşana email a xwe di "[[Special:Preferences|Tercîhên min]]" de nenivîsand.

Navnîşana te ya IP $3 ye. Ji kerema xwe eger pirsên te hebe vê navnîşanê bibêje.',
'whitelistedittext'     => 'Ji bo guherandina rûpelan, $1 pêwîst e.',
'loginreqtitle'         => 'Têketin pêwîst e',
'loginreqlink'          => 'login',
'accmailtitle'          => 'Şîfre hat şandin.',
'accmailtext'           => 'Şîfreya \'$1\' hat şandin ji $2 re.',
'newarticle'            => '(Nû)',
'newarticletext'        => '<div style="font-size:small;color:#003333;border-width:1px;border-style:solid;border-color:#aaaaaa;padding:3px">
Ev rûpel hîn tune. Eger tu bixwazî vê rûpelê çêkî, dest bi nivîsandinê bike û piştre qeyd bike. \'\'\'Wêrek be\'\'\', biceribîne!<br />
Ji bo alîkarî binêre: [[Help:Alîkarî|Alîkarî]].<br />
Eger tu bi şaştî hatî, bizivire rûpela berê.
</div>',
'anontalkpagetext'      => '----
\'\'Ev rûpela guftûgo ye ji bo bikarhênerên nediyarkirî ku hîn hesabekî xwe çênekirine an jî bikarnaînin. Ji ber vê yekê divê em wan bi [[IP address|navnîşana IP]] ya hejmarî nîşan bikin. Navnîşaneke IP dikare ji aliyê gelek kesan ve were bikaranîn. Heger tu bikarhênerekî nediyarkirî bî û bawerdikî ku nirxandinên bê peywend di der barê te de hatine kirin ji kerema xwe re [[Special:Userlogin|hesabekî xwe veke an jî têkeve]] da ku tu xwe ji tevlîheviyên bi bikarhênerên din re biparêzî.\'\'',
'noarticletext'         => 'Ev rûpel niha vala ye, tu dikarî
[[Special:Search/{{PAGENAME}}|Di nav gotarên din de li "{{PAGENAME}}" bigere]] an
[{{fullurl:{{FULLPAGENAME}}|action=edit}} vê rûpelê biguherînî].',
'updated'               => '(Hat taze kirin)',
'note'                  => '<strong>Not:</strong>',
'previewnote'           => 'Ji bîr neke ku ev bi tenê çavdêriyek e, ev rûpel hîn nehat qeyd kirin!',
'editing'               => 'Biguherîne: "$1"',
'editingsection'        => 'Tê guherandin: $1 (beş)',
'editingcomment'        => '$1 (şîrove) tê guherandin.',
'editconflict'          => 'Têkçûna guherandinan: $1',
'explainconflict'       => 'Ji dema te dest bi guherandinê kir heta niha kesekê/î din ev rûpel guherand.

Jor guhartoya heyî tê dîtîn. Guherandinên te jêr tên nîşan dan. Divê tû wan bikî yek. Heke niha tomar bikî, <b>bi tene</b> nivîsara qutiya jor wê bê tomarkirin. <p>',
'yourtext'              => 'Nivîsara te',
'storedversion'         => 'Versiyona qeydkirî',
'editingold'            => '<strong>HÎŞYAR: Tu ser revîsyoneke kevn a vê rûpelê dixebitî.
Eger tu qeyd bikî, hemû guhertinên ji vê revîzyonê piştre winda dibin.
</strong>',
'yourdiff'              => 'Ciyawazî',
'copyrightwarning'      => 'Dîqat bike: Hemû tevkariyên {{SITENAME}} di bin $2 de tên belav kirin (ji bo hûragahiyan li $1 binêre). Eger tu nexwazî ku nivîsên te bê dilrehmî bên guherandin û li gora keyfa herkesî bên belavkirin, li vir neweşîne.<br />
Tu soz didî ku te ev bi xwe nivîsand an jî ji çavkaniyekê azad an geliyane \'\'(public domain)\'\' girt.
<strong>BERHEMÊN MAFÊN WAN PARASTÎ (©) BÊ DESTÛR NEWEŞÎNE!</strong>',
'longpagewarning'       => 'HIŞYAR: Drêjahiya vê rûpelê $1 kB (kilobayt) e, ev pir e. Dibe ku çend \'\'browser\'\'
baş nikarin rûpelên ku ji 32 kB drêjtir in biguherînin. Eger tu vê rûpelê beş beş bikî gelo ne çêtir e?',
'protectedpagewarning'  => 'ŞIYARÎ:  Ev rûpel hat qefl kirin. Bi tenê bikarhênerên ku xwediyên mafan "sysop" ne dikarin vê rûpelê biguherînin.<br />
Be sure you are following the
[[Project:Protected page guidelines|protected page guidelines]].',
'revhistory'            => 'Dîroka revîzyonan',
'nohistory'             => 'Ew rûpel dîroka guherandinê tune.',
'revnotfound'           => 'Revîzyon nehat dîtin',
'currentrev'            => 'Revîzyona niha',
'revisionasof'          => 'Revîzyon a $1',
'previousrevision'      => '←Rêvîzyona kevintir',
'nextrevision'          => 'Revîzyona nûtir→',
'currentrevisionlink'   => 'Revîzyona niha nîşan bide',
'cur'                   => 'ferq',
'next'                  => 'pêş',
'last'                  => 'berê',
'orig'                  => 'orîj',
'histlegend'            => 'Legend: (ferq) = cudayî nav vê û versiyon a niha,
(berê) = cudayî nav vê û yê berê vê, B = guhêrka biçûk',
'histfirst'             => 'Kevintirîn',
'histlast'              => 'Nûtirîn',
'rev-delundel'          => 'nîşan bide/veşêre',
'difference'            => '(Ciyawaziya nav revîzyonan)',
'lineno'                => 'Dêrra $1:',
'compareselectedversions'=> 'Guhartoyan Helsengêne',
'searchresults'         => 'Encamên lêgerînê',
'searchresulttext'      => 'Ji bo zêdetir agahî der barê lêgerînê di {{SITENAME}} de, binêre [[Project:Searching|Searching {{SITENAME}}]].',
'searchsubtitle'           => 'Ji bo query "[[:$1]]"',
'searchsubtitleinvalid'           => 'Ji bo query "$1"',
'titlematches'          => 'Dîtinên di sernivîsên gotaran de',
'notitlematches'        => 'Di nav sernivîsan de nehat dîtin.',
'textmatches'           => 'Dîtinên di nivîsara rûpelan de',
'notextmatches'         => 'Di nivîsarê de nehat dîtin.',
'prevn'                 => '$1 paş',
'nextn'                 => '$1 pêş',
'viewprevnext'          => '($1) ($2) ($3).',
'showingresults'        => '<b>$1</b> encam, bi #<b>$2</b> dest pê dike.',
'showingresultsnum'     => '<b>$3</b> encam, bi #<b>$2</b> dest pê dike.',
'powersearch'           => 'Lêbigere',
'powersearchtext'       => 'Lêgerîn di nav cihên navan de:<br />
$1<br />
$2 Ragihandinan nîşan bide   Lêbigere: $3 $9',
'searchdisabled'        => '<p>Tu dikarî li {{SITENAME}} bi Google an Yahoo! bigere. Têbînî: Dibe ku encamen lêgerîne ne yên herî nû ne.
</p>',
'blanknamespace'        => '(Serekî)',
'preferences'           => 'Tercîhên min',
'prefsnologin'          => 'Xwe qeyd nekir',
'changepassword'        => 'Şîfre biguherîne',
'skin'                  => 'Pêste',
'datetime'              => 'Dem û rêkewt',
'prefs-personal'        => 'Agahiyên bikarhênerê/î',
'prefs-rc'              => 'Guherandinên dawî',
'prefs-misc'            => 'Eyaren cuda',
'saveprefs'             => 'Tercîhan qeyd bike',
'oldpassword'           => 'Şîfreya kevn',
'newpassword'           => 'Şîfreya nû',
'retypenew'             => 'Şîfreya nû careke din binîvîse',
'rows'                  => 'Rêz',
'columns'               => 'stûn',
'searchresultshead'     => 'Eyarên encamên lêgerinê',
'savedprefs'            => 'Tercîhên te qeyd kirî ne.',
'default'               => 'asayî',
'files'                 => 'Dosya',
'changes'               => 'guherandin',
'recentchanges'         => 'Guherandinên dawî',
'recentchangestext'     => '<!-- please translate: -->Track the most recent changes to the wiki on this page.',
'rcnote'                => 'Jêr <strong>$1</strong> guherandinên dawî di <strong>$2</strong> rojên dawî de, ji $3 şûnde tên nîşan dan.',
'rclistfrom'            => 'an jî guherandinên ji $1 şûnda nîşan bide.',
'rclinks'               => '$1 guherandinên di $2 rojên dawî de nîşan bide<br />$3',
'diff'                  => 'ciyawazî',
'hist'                  => 'dîrok',
'hide'                  => 'veşêre',
'show'                  => 'nîşan bide',
'minoreditletter'       => 'B',
'newpageletter'         => 'Nû',
'upload'                => 'Wêneyê barbike',
'uploadbtn'             => 'Dosyayê barbike',
'uploadnologin'         => 'Xwe qeyd nekir',
'uploadnologintext'     => 'Ji bo barkirina wêneyan divê ku tu [[Special:Userlogin|têkevî]].',
'uploadtext'            => 'Berê tu wêneyên nû bar bikî, ji bo dîtin an vedîtina wêneyên ku ji xwe hene binêre: [[Special:Imagelist|lîsteya wêneyên barkirî]]. Herwisa wêneyên ku hatine barkirin an jî jê birin li vir dikarî bibînî: [[Special:Log/upload|reşahiya barkiriyan]]. 

Yek ji lînkên jêr ji bo bikarhînana wêne an faylê di gotarê de bikar bihîne:

* \'\'\'<nowiki>[[{{ns:Image}}:File.jpg]]</nowiki>\'\'\'
* \'\'\'<nowiki>[[{{ns:Image}}:File.png|alt text]]</nowiki>\'\'\'
anjî ji bo faylên dengî
* \'\'\'<nowiki>[[{{ns:Media}}:File.ogg]]</nowiki>\'\'\'',
'filename'              => 'Navê dosyayê',
'filedesc'              => 'Kurte',
'fileuploadsummary'     => 'Kurte:',
'filesource'            => 'Çavkanî',
'copyrightpage'         => 'Project:Mafên nivîsanê',
'copyrightpagename'     => 'Mafên nivîsanê',
'uploadedfiles'         => 'Dosyayên bar kirî',
'ignorewarning'         => 'Hişyarê qebûl neke û dosyayê qeyd bike.',
'ignorewarnings'        => 'goh nede hîşyaran!',
'minlength'             => 'Navê wêne bi lanî kêm dive ji 3 tîpan pêtir be.',
'badfilename'           => 'Navê wêneyê hat guherandin û bû "$1".',
'badfiletype'           => 'Formata ".$1" naye tawsiye kirin. (Ji bo wêne .png û .jpg tên tawsiye kirin.)',
'largefile'             => 'Pêşniyara me ewe ku wêneyan ji $1 bayt mezintir nebe, ew wêne $2 bayt e.',
'successfulupload'      => 'Barkirin serkeftî',
'fileuploaded'          => 'Barkirina dosyaya bi navê $1 serkeftî.
Ji kerema xwe, biçe: $2 û agahî li der barê dosyayê binivîse (ji ku derê hat girtin, kîngê hat çêkirin, kê çêkir û hwd.)

Heke ev dosya wêneyek be, bi vî rengî bi kar bîne:
<br />
<tt><nowiki>[[{{ns:Image}}:$1|thumb|Binnivîs]]</nowiki></tt>',
'uploadwarning'         => 'Hişyara barkirinê',
'savefile'              => 'Dosyayê tomar bike',
'uploadedimage'         => '"$1" barkirî',
'destfilename'          => 'Navê faylê xwastî',
'imagelist'             => 'Listeya wêneyan',
'imagelisttext'         => 'Below is a list of $1 files sorted $2.',
'ilsubmit'              => 'Lêbigere',
'showlast'              => '$1 wêneyên dawî bi rêz kirî $2 nîşan bide.',
'byname'                => 'li gor navê',
'bydate'                => 'li gor dîrokê',
'bysize'                => 'li gor mezinayiyê',
'imghistory'            => 'Dîroka wêneyê',
'deleteimg'             => 'jêbibe',
'imagelinks'            => 'Lînkên wêneyê',
'linkstoimage'          => 'Di van rûpelên de lînkek ji vê wêneyê re heye:',
'nolinkstoimage'        => 'Rûpeleke ku ji vê wêneyê re lînk dike tune.',
'download'              => 'dabezandin',
'statistics'            => 'Statîstîk',
'sitestats'             => 'Statîstîkên sîteyê',
'userstats'             => 'Statistîkên bikarhêneran',
'sitestatstext'         => 'Di \'\'database\'\' de \'\'\'$1\'\'\' rûpel hene.
Tê de rûpelên guftûgoyê, rûpelên der barê {{SITENAME}}, rûpelên pir kurt (stub), rûpelên ragihandinê (redirect) û rûpelên din ku qey ne gotar in hene.
Derve wan, \'\'\'$2\'\'\' rûpel hene ku qey \'\'\'gotarên rewa\'\'\' ne. 

\'\'\'$8\'\'\' dosya hatin barkirin.

Ji afirandina Wîkiyê heta roja îro \'\'\'$3\'\'\' carî rûpel hatin mezekirin û \'\'\'$4\'\'\' carî rûpel hatin guherandin
since the wiki was setup.
Ji ber wê di nîvî de her rûpel \'\'\'$5\'\'\' carî hatiye guherandin, û nîspeta dîtun û guherandinan \'\'\'$6\'\'\' e.

Dirêjahiya [http://meta.wikimedia.org/wiki/Help:Job_queue job queue] \'\'\'$7\'\'\' e.',
'userstatstext'         => '\'\'\'$1\'\'\' bikarhênerên qeydkirî hene. Ji wan \'\'\'$2\'\'\' (an \'\'\'$4\'\'\') administrator/koordînator in. (Binêre $3).',
'disambiguations'       => 'Rûpelên cudakirinê',
'brokenredirects'       => 'Ragihandinên jê bûye',
'nbytes'                => '$1 bayt',
'nlinks'                => '$1 lînk',
'nmembers'              => '$1 {{PLURAL:$1|endam|endam}}',
'nviews'                => '$1 {{PLURAL:$1|dîtin|dîtin}}',
'lonelypages'           => 'Rûpelên sêwî',
'uncategorizedpages'    => 'Rûpelên bê kategorî',
'uncategorizedcategories'=> 'Kategoriyên bê kategorî',
'unusedcategories'      => 'Kategoriyên ku nayên bi kar anîn',
'unusedimages'          => 'Wêneyên ku nayên bi kar anîn',
'popularpages'          => 'Rûpelên populer',
'wantedcategories'      => 'Kategoriyên tên xwestin',
'wantedpages'           => 'Rûpelên ku tên xwestin',
'allpages'              => 'Hemû rûpel',
'randompage'            => 'Rûpelek bi helkeft',
'shortpages'            => 'Rûpelên kurt',
'longpages'             => 'Rûpelên dirêj',
'deadendpages'          => 'Rûpelên bê dergeh',
'listusers'             => 'Lîsteya bikarhêneran',
'specialpages'          => 'Rûpelên taybet',
'spheading'             => 'Rûpelên taybet ji bo hemû bikarhêneran',
'recentchangeslinked'   => 'Guherandinên peywend',
'newpages'              => 'Rûpelên nû',
'ancientpages'          => 'Gotarên kevintirîn',
'move'                  => 'Navê rûpelê biguherîne',
'movethispage'          => 'Vê rûpelê bigerîne',
'booksources'           => 'Çavkaniyên pirtûkan',
'categoriespagetext'    => 'Di vê wîkiyê de ev kategorî hene:',
'booksourcetext'        => 'Li jêr lîsteyek ji linkan bo sîteyên din ku pertûkên nû û destî dû difrotin hatiye û belkî zanyarî yên pêtir jî derbarî wan pertûkên ku tu dixwazî hebin.',
'alphaindexline'        => '$1 heta $2',
'version'               => 'Guherto',
'log'                   => 'Reşahiyan',
'nextpage'              => 'Rûpela pêşî ($1)',
'allpagesfrom'          => 'Pêşdîtina rûpelan bi dest pê kirin ji',
'allarticles'           => 'Hemû gotar',
'allinnamespace'        => 'Hemû rûpelan ($1 boşahî a nav)',
'allpagesprev'          => 'Pêş',
'allpagesnext'          => 'Paş',
'allpagessubmit'        => 'Biçe',
'allpagesprefix'        => 'Nîşan bide rûpelên bi pêşgira:',
'mailnologin'           => 'Navnîşan neşîne',
'emailuser'             => 'Ji vê/î bikarhênerê/î re e-name bişîne',
'emailpage'             => 'E-name bikarhêner',
'defemailsubject'       => '{{SITENAME}} e-name',
'noemailtitle'          => 'Navnîşana e-name tune',
'emailfrom'             => 'Ji',
'emailto'               => 'Bo',
'emailsubject'          => 'Mijar',
'emailmessage'          => 'Name',
'emailsend'             => 'Bişîne',
'emailsent'             => 'E-name hat şandin',
'emailsenttext'         => 'E-nameya te hat şandin.',
'watchlist'             => 'Lîsteya min ya şopandinê',
'watchlistfor'          => '(ji bo \'\'\'$1\'\'\')',
'watchnologin'          => 'Xwe qeyd nekir',
'addedwatch'            => 'Hat îlawekirinî listeya şopandinê',
'addedwatchtext'        => 'Rûpela "$1" çû ser [[Special:Watchlist|lîsteya te ya şopandinê]].
Li dahatû de her guhartoyek li wê rûpelê û rûpela guftûgo ya wê were kirin li vir dêt nîşan dan,
 
Li rûpela [[Special:Recentchanges|Guherandinên dawî]] jî ji bo hasan dîtina wê, ew rûpel bi \'\'\'Nivîsa estûr\'\'\' dê nîşan dayîn.


<p>Her dem tu bixwazî ew rûpel li nav lîsteya te ya şopandinê derbikî, li ser wê rûpelê, klîk bike "êdî neşopîne".</p>',
'removedwatchtext'      => 'The page "$1" has been removed from your watchlist.',
'watch'                 => 'Bişopîne',
'watchthispage'         => 'Vê rûpelê bişopîne',
'unwatch'               => 'Êdî neşopîne',
'notanarticle'          => 'Ne gotar e',
'watchdetails'          => '* $1 rûpel tên şopandin, rûpelên guftûgoyê nayên jimartin
* [[Special:Watchlist/edit|Lîsteya şopandinê bibîne û temam bike]]
* [[Special:Watchlist/clear|Hemû rûpelan derxe]]',
'watchlistcontains'     => 'Di lîsteya şopandina te de $1 rûpel hene.',
'couldntremove'         => '\'$1\' naye jêbirin...',
'wlhideshowown'         => '$1 my edits.',
'wlhideshowbots'        => '$1 bot edits.',
'wldone'                => 'Çêbû.',
'enotif_newpagetext'    => 'Ev rûpeleke nû ye.',
'changed'               => 'guhart',
'created'               => 'afirandî',
'deletepage'            => 'Rûpelê jê bibe',
'confirm'               => 'Pesend bike',
'excontent'             => 'Naveroka berê: \'$1\'',
'excontentauthor'       => 'Nawerokê wê rûpelê ew bû: \'$1\' (û tenya bikarhêner \'$2\' bû)',
'exbeforeblank'         => 'Nawerok berî betal kirinê ew bû: \'$1\'',
'exblank'               => 'rûpel vala bû',
'confirmdelete'         => 'Teyîda jêbirinê',
'deletesub'             => '("$1" tê jêbirin)',
'historywarning'        => 'Hîşyar: Ew rûpel ku tu dixwazî jê bibî dîrokek heye:',
'actioncomplete'        => 'Çalakî temam',
'deletedtext'           => '"$1" hat jêbirin. Ji bo qeyda rûpelên ku di dema nêzîk de hatin jêbirin binêre $2.',
'deletedarticle'        => '"$1" hat jêbirin',
'dellogpage'            => 'Reşahiya_jêbirin',
'dellogpagetext'        => 'Li jêr lîsteyek ji jêbirinên dawî heye.',
'deletionlog'           => 'reşahiya jêbirin',
'deletecomment'         => 'Sedema jêbirinê',
'rollback_short'        => 'Bizivirîne pêş',
'rollbacklink'          => 'bizivirîne pêş',
'cantrollback'          => 'Guharto naye vegerandin; bikarhêrê dawî, \'\'\'tenya\'\'\' nivîskarê wê rûpelê ye.',
'alreadyrolled'         => 'Guherandina dawiya [[$1]]
bi [[User:$2|$2]] ([[User talk:$2|guftûgo]]) venizivre; keseke din wê rûpelê zivrandiye an guherandiye.

Guhartoya dawî bi [[User:$3|$3]] ([[User talk:$3|guftûgo]]).',
'revertpage'            => 'Guherandina $2 hat betal kirin, vegerand guhartoya dawî ya $1',
'protectlogpage'        => 'Reşahiya _parastiyan',
'protectedarticle'      => 'parastî [[$1]]',
'confirmprotecttext'    => 'Tu bi rastî dixwazî wê rûpelê biparêzî?',
'confirmprotect'        => 'Parastinê teyîd bike',
'protectcomment'        => 'Sedema parastinê',
'unprotectcomment'      => 'Sedem ji bo rakirina parastinê',
'restriction-edit'      => 'Biguherîne',
'restriction-move'      => 'Nav biguherîne',
'undeletebtn'           => 'Restore!',
'namespace'             => 'Boşahiya nav:',
'invert'                => 'Hilbijardinê pêçewane bike',
'contributions'         => 'Beşdariyên vê bikarhêner',
'mycontris'             => 'Beşdariyên min',
'contribsub'            => 'Ji bo $1',
'uclinks'               => '$1 guherandinên dawî; $2 rojên dawî',
'uctop'                 => ' (ser)',
'newbies'               => 'ecemî',
'sp-contributions-newest'=> 'Nûtirîn',
'sp-contributions-oldest'=> 'Kevintirîn',
'sp-contributions-newer'=> '$1 yên nûtir',
'sp-contributions-older'=> '$1 yên kevintir',
'whatlinkshere'         => 'Lînkên ji vê rûpelê re',
'notargettitle'         => 'Hedef tune',
'linklistsub'           => '(Listeya lînkan)',
'linkshere'             => 'Di van rûpelên de lînkek ji vê re heye:',
'nolinkshere'           => 'Ji hîç rûpel ji vê re lînk tune.',
'isredirect'            => 'rûpela ragihandinê',
'blockip'               => 'Bikarhêner asteng bike',
'ipbreason'             => 'Sedem',
'ipbsubmit'             => 'Vê bikarhêner asteng bike',
'badipaddress'          => 'Bikarhêner bi vî navî tune',
'blockipsuccesssub'     => 'Blok serkeftî',
'blockipsuccesstext'    => '"$1" hat asteng kirin.
<br />Bibîne [[Special:Ipblocklist|Lîsteya IP\'yan hatî asteng kirin]] ji bo lîsteya blokan.',
'blocklistline'         => '$1, $2 $3 asteng kir ($4)',
'blocklink'             => 'asteng bike',
'unblocklink'           => 'betala astengê',
'contribslink'          => 'Beşdarî',
'autoblocker'           => 'Otomatîk hat bestin jiberku IP-ya we û ya "[[User:$1|$1]]" yek in. Sedem: "\'\'\'$2\'\'\'"',
'blocklogpage'          => 'Reşahiya_asteng kiriyan',
'blocklogentry'         => '"[[$1]]" ji bo dema $2 hatiye asteng kirin',
'unblocklogentry'       => 'astenga "$1" hat betal kirin',
'proxyblocksuccess'     => 'Çêbû.',
'makesysopname'         => 'Navê bikarhêner:',
'rightsnone'            => '(tune)',
'movepage'              => 'Vê rûpelê bigerîne',
'movepagetalktext'      => 'Rûpela \'\'\'guftûgoyê giredayî ji vê rûpelê re wê bê gerandin jî. 
\'\'\'Îstisna:\'\'\'
*You are moving the page across namespaces,
*A non-empty talk page already exists under the new name, or
*You uncheck the box below.

In those cases, you will have to move or merge the page manually if desired.',
'movearticle'           => 'Rûpelê bigerîne',
'movenologin'           => 'Xwe qeyd nekir',
'movenologintext'       => 'Tu dive bikarhênereke qeydkirî bî û [[Special:Userlogin|werî nav sîstemê]]
da bikarî navê wê rûpelê biguherînî.',
'newtitle'              => 'Sernivîsa nû',
'movepagebtn'           => 'Vê rûpelê bigerîne',
'pagemovedsub'          => 'Gerandin serkeftî',
'pagemovedtext'         => 'Rûpela "[[$1]]" çû cihê "[[$2]]".',
'articleexists'         => 'Rûpela bi vî navî heye, an navê ku te hilbijart derbas nabe. Navekî din hilbijêre.',
'movedto'               => 'bû',
'movetalk'              => 'Rûpela \'\'\'guftûgo\'\'\' ya wê jî bigerîne, eger gengaz be.',
'1movedto2'             => '$1 çû cihê $2',
'1movedto2_redir'       => '$1 çû cihê $2 ser redirect',
'movelogpage'           => 'Reşahiya nav guherandin',
'movelogpagetext'       => 'Li jêr lîsteyek ji rûpelan ku navê wan hatiye guherandin heye.',
'movereason'            => 'Sedem',
'allmessages'           => 'Hemû mesajên sîstemê',
'allmessagesname'       => 'Nav',
'allmessagescurrent'    => 'Texta niha',
'allmessagestext'       => 'Ev lîsteya hemû mesajên di namespace a MediaWiki: de ye.',
'allmessagesnotsupportedUI'=> 'Your current interface language <b>$1</b> is not supported by Special:Allmessages at this site.',
'allmessagesnotsupportedDB'=> '\'\'\'Special:Allmessages\'\'\' cannot be used because \'\'\'$wgUseDatabaseMessages\'\'\' is switched off.',
'thumbnail-more'        => 'Mezin bike',
'importnotext'          => 'Vala an nivîs tune',
'tooltip-diff'          => 'Show which changes you made to the text. [alt-d]',
'tooltip-compareselectedversions'=> 'Cudatiyên guhartoyên hilbijartî yên vê rûpelê bibîne. [alt-v]',
'Monobook.css'          => '*.rtl 
 {
  dir:rtl;
  text-align:right;
  font-family: "Tahoma", "Unikurd Web", "Arial Unicode MS", "DejaVu Sans", "Lateef", "Scheherazade", "ae_Rasheeq", sans-serif, sans; 
 }

 /*Make the site more suitable for Soranî users */
 h1 {font-family: "Tahoma", "Arial Unicode MS", sans-serif, sans, "Unikurd Web", "Scheherazade";}
 h2 {font-family: "Tahoma", "Arial Unicode MS", sans-serif, sans, "Unikurd Web", "Scheherazade";}
 h3 {font-family: "Tahoma", "Arial Unicode MS", sans-serif, sans, "Unikurd Web", "Scheherazade";}
 body {font-family: "Tahoma", "Arial Unicode MS", sans-serif, sans, "Unikurd Web", "Scheherazade";}
 textarea {font-family: Lucida Console, Tahoma;}
 pre {font-family: Lucida Console, Tahoma;}

 /* extra buttons for edit dialog (from bg:)*/
 #my-buttons {
   padding: 0.5em;
 }
 #my-buttons a {
   color: black;
   background-color: #ccddee;
   font-weight: bold;
   font-size: 0.9em;
   text-decoration: none;
   border: thin #006699 outset;
   padding: 0 0.1em 0em 0.1em; 
 }
 #my-buttons a:hover, #my-buttons a:active {
   background-color: #bbccdd;
   border-style: inset;
 }
 .messagebox {
   border: 1px solid #aaaaaa;
   background-color: #f9f9f9;
   width: 80%;
   margin: 0 auto 1em auto;
   padding: 0.5em;
   text-align: justify;
 }
 .messagebox.merge {
   border: 1px solid #cf9fff;
   background-color: #f5edf5;
   text-align: center;
 }
 .messagebox.cleanup {
   border: 1px solid #9f9fff;
   background-color: #efefff;
   text-align: center;
 }
 .messagebox.standard-talk {
   border: 1px solid #c0c090;
   background-color: #f8eaba;
 }',
'anonymous'             => 'Bikarhênera/ê nediyarkirî ya/yê {{SITENAME}}',
'siteuser'              => 'Bikarhênera/ê $1 a/ê {{SITENAME}}',
'and'                   => 'û',
'others'                => 'ên din',
'siteusers'             => 'Bikarhênerên $1 yên {{SITENAME}}',
'subcategorycount'      => 'Di vê kategoriyê de $1 binkategorî hene.',
'categoryarticlecount'  => 'Di vê kategoriyê de $1 gotar hene.',
'listingcontinuesabbrev'=> ' dewam',
'Monobook.js'           => '/* tooltips and access keys */
 var ta = new Object();
 ta[\'pt-userpage\'] = new Array(\'.\',\'Rûpela min a şexsî\');
 ta[\'pt-anonuserpage\'] = new Array(\'.\',\'The user page for the ip you\'re editing as\');
 ta[\'pt-mytalk\'] = new Array(\'n\',\'Rûpela guftûgo ya min\');
 ta[\'pt-anontalk\'] = new Array(\'n\',\'Discussion about edits from this ip address\');
 ta[\'pt-preferences\'] = new Array(\'\',\',Tercîhên min\');
 ta[\'pt-watchlist\'] = new Array(\'l\',\'The list of pages you\'re monitoring for changes.\');
 ta[\'pt-mycontris\'] = new Array(\'y\',\'Lîsteya tevkariyên min\');
 ta[\'pt-login\'] = new Array(\'o\',\'You are encouraged to log in, it is not mandatory however.\');
 ta[\'pt-anonlogin\'] = new Array(\'o\',\'You are encouraged to log in, it is not mandatory however.\');
 ta[\'pt-logout\'] = new Array(\'o\',\'Derkeve (Log out)\');
 ta[\'ca-talk\'] = new Array(\'t\',\'guftûgo û şîrove ser vê rûpelê\');
 ta[\'ca-edit\'] = new Array(\'e\',\'Vê rûpelê biguherîne! Berê qeydkirinê bişkoka "Pêşdîtin" bi kar bîne.\');
 ta[\'ca-addsection\'] = new Array(\'+\',\'Beşekê zêde bike.\');
 ta[\'ca-viewsource\'] = new Array(\'e\',\'This page is protected. You can view its source.\');
 ta[\'ca-history\'] = new Array(\'h\',\'Versyonên berê yên vê rûpelê.\');
 ta[\'ca-protect\'] = new Array(\'=\',\'Vê rûplê biparêze\');
 ta[\'ca-delete\'] = new Array(\'d\',\'Vê rûpelê jê bibe\');
 ta[\'ca-undelete\'] = new Array(\'d\',\'Restore the edits done to this page before it was deleted\');
 ta[\'ca-move\'] = new Array(\'m\',\'Navekî nû bide vê rûpelê\');
 ta[\'ca-nomove\'] = new Array(\'\',\'You don\'t have the permissions to move this page\');
 ta[\'ca-watch\'] = new Array(\'w\',\'Add this page to your watchlist\');
 ta[\'ca-unwatch\'] = new Array(\'w\',\'Remove this page from your watchlist\');
 ta[\'search\'] = new Array(\'f\',\'Li vê wikiyê bigêre\');
 ta[\'p-logo\'] = new Array(\'\',\'Destpêk\');
 ta[\'n-mainpage\'] = new Array(\'z\',\'Biçe Destpêkê\');
 ta[\'n-portal\'] = new Array(\'\',\'About the project, what you can do, where to find things\');
 ta[\'n-currentevents\'] = new Array(\'\',\'Find background information on current events\');
 ta[\'n-recentchanges\'] = new Array(\'r\',\'The list of recent changes in the wiki.\');
 ta[\'n-randompage\'] = new Array(\'x\',\'Load a random page\');
 ta[\'n-help\'] = new Array(\'\',\'Bersivên ji bo pirsên te.\');
 ta[\'n-sitesupport\'] = new Array(\'\',\'Support us\');
 ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Lîsteya hemû rûpelên ku ji vê re grêdidin.\');
 ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Recent changes in pages linking to this page\');
 ta[\'feed-rss\'] = new Array(\'\',\'RSS feed for this page\');
 ta[\'feed-atom\'] = new Array(\'\',\'Atom feed for this page\');
 ta[\'t-contributions\'] = new Array(\'\',\'View the list of contributions of this user\');
 ta[\'t-emailuser\'] = new Array(\'\',\'Jê re name bişîne\');
 ta[\'t-upload\'] = new Array(\'u\',\'Upload images or media files\');
 ta[\'t-specialpages\'] = new Array(\'q\',\'List of all special pages\');
 ta[\'ca-nstab-main\'] = new Array(\'c\',\'View the content page\');
 ta[\'ca-nstab-user\'] = new Array(\'c\',\'Rûpela bikarhênerê/î temaşe bike\');
 ta[\'ca-nstab-media\'] = new Array(\'c\',\'View the media page\');
 ta[\'ca-nstab-special\'] = new Array(\'\',\'This is a special page, you can\'t edit the page itself.\');
 ta[\'ca-nstab-project\'] = new Array(\'a\',\'View the project page\');
 ta[\'ca-nstab-image\'] = new Array(\'c\',\'View the image page\');
 ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'View the system message\');
 ta[\'ca-nstab-template\'] = new Array(\'c\',\'View the template\');
 ta[\'ca-nstab-help\'] = new Array(\'c\',\'View the help page\');
 ta[\'ca-nstab-category\'] = new Array(\'c\',\'View the category page\');

/*RTL and LTR*/
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
 
 hookEvent(\'load\', bidiSwitchSetup);',
'previousdiff'          => '← Ciyawaziya pêştir',
'nextdiff'              => 'Ciyawaziya paştir →',
'thumbsize'             => 'Thumbnail size :',
'showbigimage'          => 'Versyona mezin bibîne an daxe ($1x$2, $3 KB).',
'newimages'             => 'Pêşangeha wêneyên nû',
'specialloguserlabel'   => 'Bikarhêner:',
'recentchangesall'      => 'hemû',
'imagelistall'          => 'hemû',
'watchlistall1'         => 'hemû',
'watchlistall2'         => 'hemû',
'namespacesall'         => 'Hemû',
'createarticle'         => 'Gotarê biafirîne',
'scarytranscludefailed' => '[Anîna şablona $1 biserneket; biborîne]',
'deletedwhileediting'   => 'Hîşyar: Piştî te guherandinê dest pê kir ew rûpel hat jêbirin!',
'confirm_purge_button'  => 'Temam',
'loginlanguagelabel'    => 'Ziman: $1',
);
?>

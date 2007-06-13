<?php
/** Kurdish (Kurdî / كوردي)
  *
  * @addtogroup Language
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
# User preference toggles
'tog-watchlisthideminor' => 'Xeyrandinên biçûk pêşneke',

'underline-always' => 'Tim',
'underline-never'  => 'Ne carekê',

'skinpreview' => '(Pêşdîtin)',

# Dates
'sunday'       => 'yekşem',
'monday'       => 'duşem',
'tuesday'      => 'Sêşem',
'wednesday'    => 'Çarşem',
'thursday'     => 'Pêncşem',
'friday'       => 'În',
'saturday'     => 'şemî',
'wed'          => 'Çarş',
'january'      => 'Rêbendan',
'february'     => 'reşemî',
'march'        => 'adar',
'april'        => 'avrêl',
'may_long'     => 'gulan',
'june'         => 'pûşper',
'july'         => 'Tîrmeh',
'august'       => 'tebax',
'september'    => 'rezber',
'october'      => 'kewçêr',
'november'     => 'sermawez',
'december'     => 'Berfanbar',
'december-gen' => 'Berfanbar',
'jan'          => 'rêb',
'feb'          => 'reş',
'mar'          => 'adr',
'apr'          => 'avr',
'may'          => 'gul',
'jun'          => 'pşr',
'jul'          => 'tîr',
'aug'          => 'teb',
'sep'          => 'rez',
'oct'          => 'kew',
'nov'          => 'ser',
'dec'          => 'ber',

# Bits of text used by many pages
'categories'      => 'Kategorî',
'pagecategories'  => '$1 Kategorîyan',
'category_header' => 'Gotarên di kategoriya "$1" de',
'subcategories'   => 'Binekategorî',

'about'          => 'Der barê',
'article'        => 'Gotar',
'cancel'         => 'Betal',
'qbfind'         => 'Bibîne',
'qbbrowse'       => 'Bigere',
'qbedit'         => 'Biguherîne',
'qbpageoptions'  => 'Ev rûpel',
'qbmyoptions'    => 'Rûpelên min',
'qbspecialpages' => 'Rûpelên taybet',
'moredotdotdot'  => 'Zêde...',
'mypage'         => 'Rûpela min',
'mytalk'         => 'Rûpela guftûgo ya min',
'anontalk'       => 'Guftûgo ji bo vê IPê',
'navigation'     => 'Navîgasyon',

'errorpagetitle'    => 'Çewtî (Error)',
'returnto'          => 'Bizivire $1.',
'tagline'           => 'Ji {{SITENAME}}',
'help'              => 'Alîkarî',
'search'            => 'Lêbigere',
'searchbutton'      => 'Lêbigere',
'go'                => 'Gotar',
'searcharticle'     => 'Gotar',
'history'           => 'Dîroka rûpelê',
'history_short'     => 'Dîrok / Nivîskar',
'info_short'        => 'Zanyarî',
'printableversion'  => 'Versiyon ji bo çapkirinê',
'print'             => 'Çap',
'edit'              => 'Biguherîne',
'editthispage'      => 'Vê rûpelê biguherîne',
'delete'            => 'Jê bibe',
'deletethispage'    => 'Vê rûpelê jê bibe',
'protect'           => 'Biparêze',
'protectthispage'   => 'Vê rûpelê biparêze',
'unprotect'         => 'Parastinê rake',
'unprotectthispage' => 'Parastina vê rûpelê rake',
'newpage'           => 'Rûpela nû',
'talkpage'          => 'Vê rûpelê guftûgo bike',
'specialpage'       => 'Rûpela taybet',
'personaltools'     => 'Amûrên şexsî',
'postcomment'       => 'Şîroveyekê bişîne',
'articlepage'       => 'Li naveroka rûpelê binêre',
'talk'              => 'Guftûgo (nîqaş)',
'views'             => 'Dîtin',
'toolbox'           => 'Qutiya amûran',
'userpage'          => 'Rûpelê vê/vî bikarhênerê/î temaşe bike',
'templatepage'      => 'Rûpelê şablonê seke',
'viewhelppage'      => 'Rûpelê alîkarîyê seke',
'viewtalkpage'      => 'Guftûgoyê temaşe bike',
'otherlanguages'    => 'Zimanên din',
'redirectedfrom'    => '(Hat ragihandin ji $1)',
'lastmodifiedat'    => 'Ev rûpel carî dawî di $2, $1 de hat guherandin.', # $1 date, $2 time
'viewcount'         => 'Ev rûpel $1 car hat xwestin.',
'protectedpage'     => 'Rûpela parastî',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Der barê {{SITENAME}}',
'aboutpage'         => '{{SITENAME}}:Der barê',
'bugreports'        => 'Raporên çewtiyan',
'bugreportspage'    => '{{ns:project}}:Raporên çewtiyan',
'copyright'         => 'Ji bo naverokê $1 derbas dibe.',
'copyrightpagename' => 'Mafên nivîsanê',
'copyrightpage'     => '{{ns:project}}:Mafên nivîsanê',
'currentevents'     => 'Bûyerên rojane',
'currentevents-url' => 'Bûyerên rojane',
'disclaimers'       => 'Ferexetname',
'edithelp'          => 'Alîkarî ji bo guherandin',
'edithelppage'      => '{{ns:project}}: Rûpelekê çawa biguherînim',
'faq'               => 'Pirs û Bersîv (FAQ)',
'faqpage'           => '{{SITENAME}}: Pirs û Bersîv',
'helppage'          => '{{SITENAME}}: Alîkarî',
'mainpage'          => 'Destpêk',
'portal'            => 'Portala komê',
'portal-url'        => '{{ns:project}}:Portala komê',
'sitesupport'       => 'Ji bo Weqfa Wikimedia Beş',

'badaccess' => 'Eror li bi dest Hînan',

'versionrequired'     => 'Verzîyonê $1 ji MediaWiki pêwîste',
'versionrequiredtext' => 'Verzîyonê $1 ji MediaWiki pêwîste ji bo bikaranîna vê rûpelê. Li [[{{ns:special}}:version|versyon]] seke.',

'ok'                  => 'Temam',
'retrievedfrom'       => 'Ji "$1" hatiye standin.',
'youhavenewmessages'  => '$1 yên te hene ($2).',
'newmessageslink'     => 'Nameyên nû',
'newmessagesdifflink' => 'Ciyawazî ji revîzyona berê re',
'editsection'         => 'biguherîne',
'editold'             => 'biguherîne',
'toc'                 => 'Tabloya Naverokê',
'showtoc'             => 'nîşan bide',
'hidetoc'             => 'veşêre',
'viewdeleted'         => 'Li $1 seke?',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Gotar',
'nstab-user'      => 'Bikarhêner',
'nstab-media'     => 'Medya',
'nstab-special'   => 'Taybet',
'nstab-project'   => 'Rûpela projeyê',
'nstab-image'     => 'Wêne',
'nstab-mediawiki' => 'Peyam',
'nstab-template'  => 'Şablon',
'nstab-help'      => 'Alîkarî',
'nstab-category'  => 'Kategorî',

# Main script and global functions
'nosuchaction'      => 'Çalakiyek bi vê rengê tune',
'nosuchspecialpage' => 'Rûpeleke taybet bi vê rengê tune',

# General errors
'error'           => 'Çewtî (Error)',
'noconnect'       => 'Bibexşîne! Çend pirsgrêkên teknîkî heye, girêdan ji pêşkêşvanê (suxrekirê, server) re niha ne gengaz e.<br />$1',
'enterlockreason' => 'Hoyek ji bo bestin binav bike, herweha zemaneke mezende kirî ji bo helgirtina bestinê!',
'filerenameerror' => 'Navê faylê "$1" nebû "$2".',
'filenotfound'    => 'Dosya bi navê "$1" nehat dîtin.',
'badarticleerror' => 'Ev çalakî di vê rûpelê de nabe.',
'badtitle'        => 'Sernivîsa nebaş',
'viewsource'      => 'Çavkanî',
'viewsourcefor'   => 'ji $1 ra',

# Login and logout pages
'logouttitle'                => 'Derketina bikarhêner',
'logouttext'                 => 'Tu niha derketî (logged out).

Tu dikarî di {{SITENAME}} da niha wekî bikarhênerekî nedîyarkirî bikarbînê, yan jî tu dikarî dîsa bi vî navê xwe yan navekî din wek bikarhêner têkevî {{SITENAME}}. Bila di bîra te de be ku gengaz e hin rûpel mîna ku tu hîn bi navê xwe qeyd kirîyî werin nîşandan, heta ku tu nîşanên çavlêgerandina (browser) xwe jênebî.',
'welcomecreation'            => '<h2>Bi xêr hatî, $1!</h2><p>Hesaba te hat afirandin. Tu dikarî niha tercîhên xwe eyar bikî.',
'loginpagetitle'             => 'Qeyda bikarhêner (User login)',
'yourname'                   => 'Navê te wek bikarhêner (user name)',
'yourpassword'               => 'Şîfreya te (password)',
'yourpasswordagain'          => 'Şîfreya xwe careke din binîvîse',
'remembermypassword'         => 'Şifreya min di her rûniştdemê de bîne bîra xwe.',
'yourdomainname'             => 'Domaînê te',
'loginproblem'               => '<b>Di qeyda te (login) de pirsgirêkek derket.</b><br />Careke din biceribîne!',
'alreadyloggedin'            => '<font color=red><b>Bikarhêner $1, tu jixwe têketî!</b></font><br />',
'login'                      => 'Têkeve (login)',
'loginprompt'                => "<b>Eger tu xwe nû qeyd bikî, nav û şîfreya xwe hilbijêre.</b> Ji bo xwe qeyd kirinê di {{SITENAME}} de divê ku ''cookies'' gengaz be.",
'userlogin'                  => 'Têkeve an hesabeke nû çêke',
'logout'                     => 'Derkeve (log out)',
'userlogout'                 => 'Derkeve',
'notloggedin'                => 'Xwe qeyd nekir (not logged in)',
'nologin'                    => 'Tu hêj ne endamî? $1.',
'nologinlink'                => 'Bibe endam',
'createaccount'              => 'Hesabê nû çêke',
'gotaccount'                 => 'Hesabê te heye? $1.',
'gotaccountlink'             => 'Têkeve (login)',
'createaccountmail'          => 'bi e-name',
'badretype'                  => 'Herdu şîfreyên ku te nivîsîn hevûdin nagirin.',
'youremail'                  => 'E-maila te*',
'username'                   => 'Navê bikarhêner:',
'yourrealname'               => 'Navê te yê rastî*',
'yourlanguage'               => 'Ziman',
'yournick'                   => 'Leqeba te (ji bo îmza)',
'loginerror'                 => 'Çewtî (Login error)',
'nocookieslogin'             => 'Ji bo qeydkirina bikarhêneran {{SITENAME}} "cookies" bikartîne. Te fonksîyona "cookies" girtîye. Xêra xwe kerema xwe "cookies" gengaz bike û careke din biceribîne.',
'noname'                     => 'Navê ku te nivîsand derbas nabe.',
'loginsuccesstitle'          => 'Têketin serkeftî!',
'loginsuccess'               => 'Tu niha di {{SITENAME}} de qeydkirî yî wek "$1".',
'nosuchuser'                 => 'Bikarhênera/ê bi navê "$1" tune. Navê rast binivîse an bi vê formê <b>hesabeke nû çêke</b>. (Ji bo hevalên nû "Têkeve" çênabe!)',
'wrongpassword'              => 'Şifreya ku te nivîsand şaşe. Ji kerema xwe careke din biceribîne.',
'wrongpasswordempty'         => 'Cîhê şîfreya te vala ye. Carekê din binivisîne.',
'mailmypassword'             => 'Şîfreyeke nû bi e-mail ji min re bişîne',
'noemail'                    => 'Navnîşana bikarhênerê/î "$1" nehat tomar kirine.',
'passwordsent'               => 'Ji navnîşana e-mail ku ji bo "$1" hat tomarkirin şîfreyekê nû hat şandin. Vê bistîne û dîsa têkeve.',
'acct_creation_throttle_hit' => 'Biborîne! Te hesab $1 vekirine. Tu êdî nikarî hesabên din vekî.',

# Edit page toolbar
'bold_sample'     => 'Nivîsa estûr',
'bold_tip'        => 'Nivîsa estûr',
'italic_sample'   => 'Nivîsa xwar (îtalîk)',
'italic_tip'      => 'Nivîsa xwar (îtalîk)',
'link_sample'     => 'Navê lînkê',
'link_tip'        => 'Lînka hundir',
'extlink_sample'  => 'http://www.minak.com navê lînkê',
'extlink_tip'     => 'Lînka derve (http:// di destpêkê de ji bîr neke)',
'headline_sample' => 'Nivîsara sernameyê',
'headline_tip'    => 'Sername asta 2',
'nowiki_sample'   => 'Nivîs ku nebe formatkirin',
'image_sample'    => 'Mînak.jpg',
'image_tip'       => 'Wêne li hundirê gotarê',
'media_sample'    => 'Mînak.ogg',
'sig_tip'         => 'Îmze û demxeya wext ya te',
'hr_tip'          => 'Rastexêza berwarî (kêm bi kar bîne)',

# Edit pages
'summary'                => 'Kurte û çavkanî (Te çi kir?)',
'subject'                => 'Mijar/sernivîs',
'minoredit'              => 'Ev guheraniyekê biçûk e',
'watchthis'              => 'Vê gotarê bişopîne',
'savearticle'            => 'Rûpelê tomar bike',
'preview'                => 'Pêşdîtin',
'showpreview'            => 'Pêşdîtin',
'showdiff'               => 'Guherandinê nîşan bide',
'anoneditwarning'        => 'Tu ne têketî. Navnîşana IP ya te wê di dîroka guherandina vê rûpelê de bê tomar kirin.',
'subject-preview'        => 'Pêşdîtinê sernivîsê',
'blockedtitle'           => 'Bikarhêner hat asteng kirin',
'blockedtext'            => 'Navê bikarhêner an jî navnîşana IP ya te ji aliyê $1 hat asteng kirin. Sedema vê ev  e:<br />\'\'$2\'\'<p>Tu dikarî bi $1 an yek ji [[{{SITENAME}}:Koordînator|koordînatorên din]] re ser vê blokê guftûgo bikî. 

Têbînî: Tu nikarî fonksiyona "Ji vê bikarhêner re E-mail bişîne" bi kar bîne eger te navnîşana email a xwe di "[[{{ns:special}}:Preferences|Tercîhên min]]" de nenivîsand.

Navnîşana te ya IP $3 ye. Ji kerema xwe eger pirsên te hebe vê navnîşanê bibêje.',
'whitelistedittitle'     => 'Ji bo guherandinê vê gotarê tu gireke xwe qeydbikê.',
'whitelistedittext'      => 'Ji bo guherandinê vê gotarê tu gireke xwe [[{{ns:special}}:Userlogin|li vir]] qeydbikê.',
'whitelistreadtitle'     => 'Ji xandinê vê gotarê tu gireke xwe qeydbikê',
'whitelistreadtext'      => 'Ji bo xandinê vê gotarê tu gireke xwe [[{{ns:special}}:Userlogin|li vir]] qedybikê.',
'whitelistacctitle'      => 'Tu nikanê xwe qeydbikê.',
'whitelistacctext'       => 'To be allowed to create accounts in this wiki you have to [[{{ns:special}}:Userlogin|log]] in and have the appropriate permissions.',
'loginreqtitle'          => 'Têketin pêwîst e',
'accmailtitle'           => 'Şîfre hat şandin.',
'accmailtext'            => "Şîfreya '$1' hat şandin ji $2 re.",
'newarticle'             => '(Nû)',
'newarticletext'         => "<div style=\"font-size:small;color:#003333;border-width:1px;border-style:solid;border-color:#aaaaaa;padding:3px\">
Ev rûpel hîn tune. Eger tu bixwazî vê rûpelê çêkî, dest bi nivîsandinê bike û piştre qeyd bike.
'''Wêrek be''', biceribîne!<br /> Ji bo alîkarî binêre: [[Help:Alîkarî|Alîkarî]].<br /> Eger tu bi şaştî hatî, bizivire rûpela berê. </div>",
'anontalkpagetext'       => "----
''Ev rûpela guftûgo ye ji bo bikarhênerên nediyarkirî ku hîn hesabekî xwe çênekirine an jî bikarnaînin.
Ji ber vê yekê divê em wan bi navnîşana IP ya hejmarî nîşan bikin. Navnîşaneke IP dikare ji aliyê gelek kesan ve were bikaranîn.
Heger tu bikarhênerekî nediyarkirî bî û bawerdikî ku nirxandinên bê peywend di der barê te de hatine kirin ji kerema xwe re
[[{{ns:special}}:Userlogin|hesabekî xwe veke an jî têkeve Ensîklopediya Azad]] da ku tu xwe ji tevlîheviyên bi bikarhênerên din re biparêzî.''",
'noarticletext'          => 'Ev rûpel niha vala ye, tu dikarî [[Special:Search/{{PAGENAME}}|Di nav gotarên din de li "{{PAGENAME}}" bigere]] an
[{{fullurl:{{FULLPAGENAME}}|action=edit}} vê rûpelê biguherînî].',
'usercssjsyoucanpreview' => "<strong>Tîp:</strong> 'Pêşdîtin' bikarwîne ji bo tu bibînê çawa CSS/JS'ê te yê nuh e berî tomarkirinê.",
'usercsspreview'         => "'''Zanibe ku tu bes CSS'ê xwe pêşdibînê, ew ne hatîye tomarkirin!'''",
'updated'                => '(Hat taze kirin)',
'note'                   => '<strong>Not:</strong>',
'previewnote'            => 'Ji bîr neke ku ev bi tenê çavdêriyek e, ev rûpel hîn nehat qeyd kirin!',
'editing'                => 'Biguherîne: "$1"',
'editinguser'            => 'Biguherîne: "$1"',
'editingsection'         => 'Tê guherandin: $1 (beş)',
'editingcomment'         => '$1 (şîrove) tê guherandin.',
'editconflict'           => 'Têkçûna guherandinan: $1',
'explainconflict'        => 'Ji dema te dest bi guherandinê kir heta niha kesekê/î din ev rûpel guherand.

Jor guhartoya heyî tê dîtîn. Guherandinên te jêr tên nîşan dan. Divê tû wan bikî yek. Heke niha tomar bikî, <b>bi tene</b> nivîsara qutiya jor wê bê tomarkirin. <p>',
'yourtext'               => 'Nivîsara te',
'storedversion'          => 'Versiyona qeydkirî',
'editingold'             => '<strong>HÎŞYAR: Tu ser revîsyoneke kevn a vê rûpelê dixebitî.
Eger tu qeyd bikî, hemû guhertinên ji vê revîzyonê piştre winda dibin.
</strong>',
'yourdiff'               => 'Ciyawazî',
'copyrightwarning'       => "Dîqat bike: Hemû tevkariyên {{SITENAME}} di bin $2 de tên belav kirin (ji bo hûragahiyan li $1 binêre).
Eger tu nexwazî ku nivîsên te bê dilrehmî bên guherandin û li gora keyfa herkesî bên belavkirin, li vir neweşîne.<br />
Tu soz didî ku te ev bi xwe nivîsand an jî ji çavkaniyekê azad an geliyane ''(public domain)'' girt. <strong>BERHEMÊN MAFÊN WAN PARASTÎ (©) BÊ DESTÛR NEWEŞÎNE!</strong>",
'longpagewarning'        => "HIŞYAR: Drêjahiya vê rûpelê $1 kB (kilobayt) e, ev pir e. Dibe ku çend ''browser''
baş nikarin rûpelên ku ji 32 kB drêjtir in biguherînin. Eger tu vê rûpelê beş beş bikî gelo ne çêtir e?",
'protectedpagewarning'   => "'''ŞIYARÎ''': Ev rûpela tê parastin. Bi tenê bikarhênerên ku xwediyên mafên \"koordînator\" (sysop) ne dikarin vê rûpelê biguherînin.<br />

Zanibe tu bi qebûlkirinên rûpelên astengkirinê kardikê!",
'templatesused'          => 'Şablon di van rûpelan da tê bikaranîn',
'templatesusedpreview'   => 'Şablon yê di vê pêşdîtinê da tên bikaranîn:',
'templatesusedsection'   => 'Şablon yê di vê perçê da tên bikaranîn:',
'template-protected'     => '(tê parastin)',
'template-semiprotected' => '(nîv-parastî)',

# "Undo" feature
'undo-summary' => 'Rêvîsyona $1 yê [[{{ns:special}}:Contributions/$2|$2]] ([[User talk:$2|guftûgo]]) şondakir',

# History pages
'revhistory'          => 'Dîroka revîzyonan',
'viewpagelogs'        => 'Înformasîyonan li ser vê rûpelê seke',
'nohistory'           => 'Ew rûpel dîroka guherandinê tune.',
'revnotfound'         => 'Revîzyon nehat dîtin',
'currentrev'          => 'Revîzyona niha',
'revisionasof'        => 'Revîzyon a $1',
'previousrevision'    => '←Rêvîzyona kevintir',
'nextrevision'        => 'Revîzyona nûtir→',
'currentrevisionlink' => 'Revîzyona niha nîşan bide',
'cur'                 => 'ferq',
'next'                => 'pêş',
'last'                => 'berê',
'orig'                => 'orîj',
'histlegend'          => 'Legend: (ferq) = cudayî nav vê û versiyon a niha,
(berê) = cudayî nav vê û yê berê vê, B = guhêrka biçûk',
'histfirst'           => 'Kevintirîn',
'histlast'            => 'Nûtirîn',

# Revision feed
'history-feed-empty' => "The requested page doesn't exist.
It may have been deleted from the wiki, or renamed.
Try [[{{ns:special}}:Search|searching on the wiki]] for relevant new pages.",

# Revision deletion
'rev-delundel' => 'nîşan bide/veşêre',

# Diffs
'difference'              => '(Ciyawaziya nav revîzyonan)',
'lineno'                  => 'Dêrra $1:',
'compareselectedversions' => 'Guhartoyan Helsengêne',
'editundo'                => 'Betalbike',

# Search results
'searchresults'         => 'Encamên lêgerînê',
'searchresulttext'      => 'Ji bo zêdetir agahî der barê lêgerînê di {{SITENAME}} da, binêre $1.',
'searchsubtitle'        => 'Ji bo query "[[:$1]]"',
'searchsubtitleinvalid' => 'Ji bo query "$1"',
'noexactmatch'          => "'''Rûpeleke bi navê \"\$1\" tune.''' Tu dikarî [[:\$1|vê rûpelê biafirînî]]",
'titlematches'          => 'Dîtinên di sernivîsên gotaran de',
'notitlematches'        => 'Di nav sernivîsan de nehat dîtin.',
'textmatches'           => 'Dîtinên di nivîsara rûpelan de',
'notextmatches'         => 'Di nivîsarê de nehat dîtin.',
'prevn'                 => '$1 paş',
'nextn'                 => '$1 pêş',
'viewprevnext'          => '($1) ($2) ($3).',
'showingresults'        => '<b>$1</b> encam, bi #<b>$2</b> dest pê dike.',
'showingresultsnum'     => '<b>$3</b> encam, bi #<b>$2</b> dest pê dike.',
'powersearch'           => 'Lê bigere',
'powersearchtext'       => 'Lêgerîn di nav cihên navan de:<br />
$1<br />
$2 Ragihandinan nîşan bide &amp;nbsp; Lêbigere: $3 $9',
'searchdisabled'        => '<p>Tu dikarî li {{SITENAME}} bi Google an Yahoo! bigere. Têbînî: Dibe ku encamen lêgerîne ne yên herî nû ne.
</p>',
'blanknamespace'        => '(Serekî)',

# Preferences page
'preferences'       => 'Tercîhên min',
'prefsnologin'      => 'Xwe qeyd nekir',
'changepassword'    => 'Şîfre biguherîne',
'skin'              => 'Pêste',
'datetime'          => 'Dem û rêkewt',
'prefs-personal'    => 'Agahiyên bikarhênerê/î',
'prefs-rc'          => 'Guherandinên dawî',
'prefs-misc'        => 'Eyaren cuda',
'saveprefs'         => 'Tercîhan qeyd bike',
'oldpassword'       => 'Şîfreya kevn',
'newpassword'       => 'Şîfreya nû',
'retypenew'         => 'Şîfreya nû careke din binîvîse',
'textboxsize'       => 'Guheranin',
'rows'              => 'Rêz',
'columns'           => 'sitûn',
'searchresultshead' => 'Eyarên encamên lêgerinê',
'savedprefs'        => 'Tercîhên te qeyd kirî ne.',
'default'           => 'asayî',
'files'             => 'Dosya',

# User rights
'userrights-lookup-user'   => 'Îdarekirina grûpan',
'userrights-user-editname' => 'Navî bikarhênerê têke:',
'userrights-groupsmember'  => 'Endamê:',

# Groups
'group-sysop' => 'Koordînatoran',

'group-sysop-member' => 'Koordînator',

# User rights log
'rightsnone' => '(tune)',

# Recent changes
'nchanges'        => '$1 guherandin',
'recentchanges'   => 'Guherandinên dawî',
'rcnote'          => "Jêr {{PLURAL:$1|'''1''' xeyrandin|'''$1''' xeyrandinên dawî}} tên wêşandin yê {{PLURAL:$2|rojê dawî|'''$2''' rojên dawî}}. Ji $3.",
'rclistfrom'      => 'an jî guherandinên ji $1 şûnda nîşan bide.',
'rclinks'         => '$1 guherandinên di $2 rojên dawî de nîşan bide<br />$3',
'diff'            => 'ciyawazî',
'hist'            => 'dîrok',
'hide'            => 'veşêre',
'show'            => 'nîşan bide',
'minoreditletter' => 'B',
'newpageletter'   => 'Nû',

# Recent changes linked
'recentchangeslinked' => 'Guherandinên peywend',

# Upload
'upload'               => 'Wêneyekî barbike',
'uploadbtn'            => 'Wêneyê (ya tiştekî din ya mêdya) barbike',
'reupload'             => 'Dîsa barbike',
'uploadnologin'        => 'Xwe qeyd nekir',
'uploadnologintext'    => 'Ji bo barkirina wêneyan divê ku tu [[{{ns:special}}:Userlogin|têkevî]].',
'uploadtext'           => "Berê tu wêneyên nû bar bikî, ji bo dîtin an vedîtina wêneyên ku ji xwe hene binêre: [[Special:Imagelist|lîsteya wêneyên barkirî]].
Herwisa wêneyên ku hatine barkirin an jî jê birin li vir dikarî bibînî: [[Special:Log/upload|reşahiya barkiriyan]].
Yek ji lînkên jêr ji bo bikarhînana wêne an faylê di gotarê de bikar bihîne:
* '''<nowiki>[[</nowiki>{{ns:Image}}:File.jpg]]'''
* '''<nowiki>[[</nowiki>{{ns:Image}}:File.png|alt text]]''' anjî ji bo faylên dengî
* '''<nowiki>[[</nowiki>{{ns:Media}}:File.ogg]]'''",
'filename'             => 'Navê dosyayê',
'filedesc'             => 'Kurte',
'fileuploadsummary'    => 'Kurte:',
'filesource'           => 'Çavkanî',
'uploadedfiles'        => 'Dosyayên bar kirî',
'ignorewarning'        => 'Hişyarê qebûl neke û dosyayê qeyd bike.',
'ignorewarnings'       => 'Guh nede hîşyaran',
'minlength'            => 'Navê wêne bi lanî kêm dive ji 3 tîpan pêtir be.',
'badfilename'          => 'Navê vî wêneyî hat guherandin û bû "$1".',
'fileexists-forbidden' => 'Medyayek bi vê navî heye; xêra xwe şonda here û vê medyayê bi navekî din barbike.
[[Image:$1|thumb|center|$1]]',
'successfulupload'     => 'Barkirin serkeftî',
'fileuploaded'         => 'Barkirina dosyaya bi navê $1 serkeftî.
Ji kerema xwe, biçe: $2 û agahî li der barê dosyayê binivîse (ji ku derê hat girtin, kîngê hat çêkirin, kê çêkir û hwd.)

Heke ev dosya wêneyek be, bi vî rengî bi kar bîne:
<br />
<tt><nowiki>[[</nowiki>{{ns:Image}}:$1|thumb|Binnivîs]]</tt>',
'uploadwarning'        => 'Hişyara barkirinê',
'savefile'             => 'Dosyayê tomar bike',
'uploadedimage'        => '"$1" barkirî',
'sourcefilename'       => 'Navî wêneyê (ya tiştekî din ya mêdya)',
'destfilename'         => 'Navî wêneyê (ya tiştekî din ya mêdya) yê xastî',
'watchthisupload'      => 'Vê rûpelê bişopîne',

# Image list
'imagelist'        => 'Listeya wêneyan',
'imagelisttext'    => 'Below is a list of $1 files sorted $2.',
'ilsubmit'         => 'Lêbigere',
'showlast'         => '$1 wêneyên dawî bi rêz kirî $2 nîşan bide.',
'byname'           => 'li gor navê',
'bydate'           => 'li gor dîrokê',
'bysize'           => 'li gor mezinayiyê',
'imghistory'       => 'Dîroka vî wêneyî',
'deleteimg'        => 'jêbibe',
'imagelinks'       => 'Lînkên vî wêneyî',
'linkstoimage'     => 'Di van rûpelan de lînkek ji vî wêneyî re heye:',
'nolinkstoimage'   => 'Rûpelekî ku ji vî wêneyî re girêdankê çêdike nîne.',
'noimage'          => 'Medyayek bi vê navî tune, lê tu kanî $1',
'noimage-linktext' => 'wê barbike',

# MIME search
'download' => 'dabezandin',

# Unwatched pages
'unwatchedpages' => 'Gotar ê ne tên şopandin',

# Unused templates
'unusedtemplateswlh' => 'lînkên din',

# Statistics
'statistics'    => 'Statîstîk',
'sitestats'     => 'Statîstîkên rûpelê',
'userstats'     => 'Statistîkên bikarhêneran',
'sitestatstext' => "Di ''database'' de <b>$1</b> rûpel hene. Tê de rûpelên guftûgoyê, rûpelên der barê {{SITENAME}}, rûpelên pir kurt, rûpelên ragihandinê (redirect) û rûpelên din ku qey ne gotar in hene.

Derve wan, '''$2''' rûpel hene ku qey '''gotarên rewa''' ne. 

Ji 28'ê çileya 2004 heta roja îro <b>$4</b> carî rûpel hatin guherandin û wergî '''$5''' guherandin her rûpel.

Dirêjbûnê „[[m:Help:Job queue|Job queue]]“: '''$7'''.

----

Fonksiyonên ku hîn rast naxebitin:
*<b>$3</b> rûpel tê şopandin
*<b>$5</b> xeyrandinên her rûpelê
*<b>$6</b> pêşdîtin her rûpelê.",
'userstatstext' => '<b>$1</b> bikarhênerên qeydkirî hene. Ji wan <b>$2</b> administrator/koordînator in. (Binêre $3).
<br /><br />
Ji bo statîstîkên din ser rûpela Destpêkê biçe: <b>Statîstîk</b>',

'disambiguations' => 'Rûpelên cudakirinê',

'brokenredirects' => 'Ragihandinên jê bûye',

# Miscellaneous special pages
'nbytes'                  => '$1 bayt',
'nlinks'                  => '$1 lînk',
'nmembers'                => '$1 endam',
'nviews'                  => '$1 dîtin',
'lonelypages'             => 'Rûpelên sêwî',
'uncategorizedpages'      => 'Rûpelên bê kategorî',
'uncategorizedcategories' => 'Kategoriyên bê kategorî',
'unusedcategories'        => 'Kategoriyên ku nayên bi kar anîn',
'unusedimages'            => 'Wêneyên ku nayên bi kar anîn',
'popularpages'            => 'Rûpelên populer',
'wantedcategories'        => 'Kategorîyên tên xwestin',
'wantedpages'             => 'Rûpelên ku tên xwestin',
'mostcategories'          => 'Gotar bi pir kategorîyan',
'allpages'                => 'Hemû rûpel',
'randompage'              => 'Rûpelek bi helkeft',
'shortpages'              => 'Rûpelên kurt',
'longpages'               => 'Rûpelên dirêj',
'deadendpages'            => 'Rûpelên bê dergeh',
'listusers'               => 'Lîsteya bikarhêneran',
'specialpages'            => 'Rûpelên taybet',
'spheading'               => 'Rûpelên taybet ji bo hemû bikarhêneran',
'newpages'                => 'Rûpelên nû',
'ancientpages'            => 'Gotarên kevintirîn',
'move'                    => 'Navê rûpelê biguherîne',
'movethispage'            => 'Vê rûpelê bigerîne',

# Book sources
'booksources' => 'Çavkaniyên pirtûkan',

'categoriespagetext' => 'Di vê wîkiyê de ev kategorî hene:',
'groups'             => 'Grûpen bikarhêneran',
'alphaindexline'     => '$1 heta $2',
'version'            => 'Verzîyon',

# Special:Log
'specialloguserlabel' => 'Bikarhêner:',
'log'                 => 'Reşahiyan',
'logempty'            => 'Tişt di vir da tune.',

# Special:Allpages
'nextpage'       => 'Rûpela pêşî ($1)',
'allpagesfrom'   => 'Pêşdîtina rûpelan bi dest pê kirin ji',
'allarticles'    => 'Hemû gotar',
'allinnamespace' => 'Hemû rûpelan ($1 boşahî a nav)',
'allpagesprev'   => 'Pêş',
'allpagesnext'   => 'Paş',
'allpagessubmit' => 'Biçe',
'allpagesprefix' => 'Nîşan bide rûpelên bi pêşgira:',

# E-mail user
'mailnologin'     => 'Navnîşan neşîne',
'emailuser'       => 'Ji vê/î bikarhênerê/î re e-name bişîne',
'emailpage'       => 'E-name bikarhêner',
'defemailsubject' => '{{SITENAME}} e-name',
'noemailtitle'    => 'Navnîşana e-name tune',
'emailfrom'       => 'Ji',
'emailto'         => 'Bo',
'emailsubject'    => 'Mijar',
'emailmessage'    => 'Name',
'emailsend'       => 'Bişîne',
'emailsent'       => 'E-name hat şandin',
'emailsenttext'   => 'E-nameya te hat şandin.',

# Watchlist
'watchlist'            => 'Lîsteya min ya şopandinê',
'mywatchlist'          => 'Lîsteya min ya şopandinê',
'watchlistfor'         => '(ji bo $1)',
'watchlistanontext'    => 'Ji bo sekirinê ya xeyrandinê lîsteya te ya şopandinê tu gireke xwe $1.',
'watchlistcount'       => "'''$1 qeydkirin li lîsteya te ya şopandinê bi guftûgoran jî hene.'''",
'watchlistcleartext'   => 'Di rast dixazê wan betalbikê?',
'watchlistclearbutton' => 'Lîsteya te ya şopandinê betalbike',
'watchlistcleardone'   => 'Lîsteya te ya şopandinê hate betalkirin. $1 gotar çûn.',
'watchnologin'         => 'Te xwe qeyd nekirîye.',
'watchnologintext'     => 'Ji bo xeyrandinê lîsteya te ya şopandinê tu gireke xwe [[{{ns:special}}:Userlogin|qedy kiribe]].',
'addedwatch'           => 'Hat îlawekirinî listeya şopandinê',
'addedwatchtext'       => "Rûpela \"\$1\" çû ser [[{{ns:special}}:Watchlist|lîsteya te ya şopandinê]].
Li dahatû de her guhartoyek li wê rûpelê û rûpela guftûgo ya wê were kirin li vir dêt nîşan dan,
 
Li rûpela [[{{ns:special}}:Recentchanges|Guherandinên dawî]] jî ji bo hasan dîtina wê, ew rûpel bi '''Nivîsa estûr''' dê nîşan dayîn.


<p>Her dem tu bixwazî ew rûpel li nav lîsteya te ya şopandinê derbikî, li ser wê rûpelê, klîk bike \"êdî neşopîne\".",
'removedwatch'         => 'Ji lîsteya şopandinê hate jêbirin',
'removedwatchtext'     => 'Rûpela "$1" ji lîsteya te ya şopandinê hate jêbirin.',
'watch'                => 'Bişopîne',
'watchthispage'        => 'Vê rûpelê bişopîne',
'unwatch'              => 'Êdî neşopîne',
'notanarticle'         => 'Ne gotar e',
'watchnochange'        => 'Ne rûpelek, yê tu dişopînê, hate xeyrandin di vê wextê da, yê tu dixazê bibînê.',
'watchdetails'         => '* $1 rûpel tên şopandin, rûpelên guftûgoyê netên jimartin
* [[{{ns:special}}:Watchlist/edit|Lîsteya şopandinê bibîne û bixeyrîne]]
* [[{{ns:special}}:Watchlist/clear|Giştik rûpel derxe]]',
'wlheader-enotif'      => '* E-mail-şandin çêbû.',
'wlheader-showupdated' => "* Ew rûpel yê hatin xeyrandin jilkî te li wan sekir di '''nivîsa estûr''' tên pêşandin.",
'watchlistcontains'    => 'Di lîsteya şopandina te de $1 rûpel hene.',
'watcheditlist'        => "Li vir lîstekî alfabêtî ji rûpelên te yê tu dişopînê ye. Li çargoşa xwe eger tu dixazê wan gotaran ji lîstê xwe bibê û paşê li 'Gotar bi şixtekî jêbibe' xwe.",
'removingchecked'      => 'Gotarên bi xeta tên jêbirin ji lîsteya te ya şopandinê',
'couldntremove'        => "'$1' naye jêbirin...",
'wlnote'               => 'Nuha $1 xeyrandinên dawî yê <b>$2</b> seetên dawî tên.',
'wlshowlast'           => 'Xeyrandînên berî $1 seetan, $2 rojan, ya $3 (di rojên sîyî paşî)',
'wlsaved'              => 'Ev rûpela verzîonekî qeydkirî ji lîsteya te ya şopandinê ye.',
'watchlist-hide-bots'  => "Guherandinên Bot'an veşêre",
'watchlist-show-own'   => 'Guherandinên min pêşke',
'watchlist-hide-own'   => 'Guherandinên min veşêre',
'watchlist-show-minor' => 'Guherandinên biçûk pêşke',
'watchlist-hide-minor' => 'Guherandinên biçûk veşêre',
'wldone'               => 'Çêbû.',

'enotif_newpagetext' => 'Ev rûpeleke nû ye.',
'changed'            => 'guhart',
'created'            => 'afirandî',

# Delete/protect/revert
'deletepage'           => 'Rûpelê jê bibe',
'confirm'              => 'Pesend bike',
'excontent'            => 'Naveroka berê: $1',
'excontentauthor'      => "Nawerokê wê rûpelê ew bû: '$1' (û tenya bikarhêner '$2' bû)",
'exbeforeblank'        => "Nawerok berî betal kirinê ew bû: '$1'",
'exblank'              => 'rûpel vala bû',
'confirmdelete'        => 'Teyîda jêbirinê',
'deletesub'            => '("$1" tê jêbirin)',
'historywarning'       => 'Hîşyar: Ew rûpel ku tu dixwazî jê bibî dîrokek heye:',
'actioncomplete'       => 'Çalakî temam',
'deletedtext'          => '"$1" hat jêbirin. Ji bo qeyda rûpelên ku di dema nêzîk de hatin jêbirin binêre $2.',
'deletedarticle'       => '"$1" hat jêbirin',
'dellogpage'           => 'Reşahiya_jêbirin',
'dellogpagetext'       => 'Li jêr lîsteyek ji jêbirinên dawî heye.',
'deletionlog'          => 'reşahiya jêbirin',
'deletecomment'        => 'Sedema jêbirinê',
'rollback_short'       => 'Bizivirîne pêş',
'rollbacklink'         => 'bizivirîne pêş',
'cantrollback'         => "Guharto naye vegerandin; bikarhêrê dawî, '''tenya''' nivîskarê wê rûpelê ye.",
'alreadyrolled'        => 'Guherandina dawiya [[$1]]
bi [[User:$2|$2]] ([[User talk:$2|Guftûgo]]) venizivre; keseke din wê rûpelê zivrandiye an guherandiye.

Guhartoya dawî bi [[User:$3|$3]] ([[User talk:$3|Gufûgo]]).',
'revertpage'           => 'Guherandina $2 hat betal kirin, vegerand guhartoya dawî ya $1',
'protectlogpage'       => 'Reşahiya _parastiyan',
'protectlogtext'       => 'Below is a list of page locks and unlocks. See the [[{{ns:special}}:Protectedpages|protected pages list]] for the list of currently operational page protections.',
'protectedarticle'     => 'parastî [[$1]]',
'unprotectedarticle'   => '"[[$1]]" niha vê parastin e',
'confirmprotect'       => 'Parastinê teyîd bike',
'protectcomment'       => 'Sedema parastinê',
'unprotectsub'         => 'Parastinê "$1" rake',

# Restrictions (nouns)
'restriction-edit' => 'Biguherîne',
'restriction-move' => 'Nav biguherîne',

# Undelete
'undelete'               => 'Li rûpelên jêbirî seke',
'viewdeletedpage'        => 'Rûpelên vemirandî seke',
'undeletebtn'            => 'Dîsa çêke!',
'undeletedarticle'       => '"[[$1]]" dîsa çêkir',
'undelete-search-box'    => 'Rûpelên jêbirî lêbigere',
'undelete-search-prefix' => 'Rûpela pêşe min ke ê bi vê destpêdîkin:',
'undelete-search-submit' => 'Lêbigere',

# Namespace form on various pages
'namespace' => 'Boşahiya nav:',
'invert'    => 'Hilbijardinê pêçewane bike',

# Contributions
'contributions' => 'Beşdariyên vê bikarhêner',
'mycontris'     => 'Beşdariyên min',
'contribsub2'   => 'Ji bo $1 ($2)',
'uclinks'       => '$1 guherandinên dawî; $2 rojên dawî',
'uctop'         => ' (ser)',

'sp-contributions-newest' => 'Nûtirîn',
'sp-contributions-oldest' => 'Kevintirîn',
'sp-contributions-newer'  => '$1 yên nûtir',
'sp-contributions-older'  => '$1 yên kevintir',

# What links here
'whatlinkshere' => 'Lînk yê tên ser vê rûpelê',
'notargettitle' => 'Hedef tune',
'linklistsub'   => '(Listeya lînkan)',
'linkshere'     => "Ev rûpel tên ser vê rûpelê '''„[[:$1]]“''':",
'nolinkshere'   => "Ne ji rûpelekê lînk tên ser '''„[[:$1]]“'''.",
'isredirect'    => 'rûpela ragihandinê',

# Block/unblock
'blockip'            => 'Bikarhêner asteng bike',
'blockiptext'        => 'Ji bo astengkirina nivîsandinê ji navnîşaneke IP an bi navekî bikarhêner, vê formê bikarbîne.
Ev bes gireke were bikaranîn ji bo vandalîzmê biskinîne (bi vê [[{{ns:project}}:Policy|qebûlkirinê]]). 

Sedemekê binivîse!',
'ipbreason'          => 'Sedem',
'ipbreasonotherlist' => 'Sedemekî din',
'ipbreason-dropdown' => '*Sedemên astengkirinê (bi tevayî)
** vandalîzm
** înformasyonên şaş kir gotarekê
** rûpelê vala kir
** bes lînkan dikir rûpelan
** kovan dikir gotaran
** heqaretkirin
** pir accounts dikaranîn
** navekî pîs',
'ipbsubmit'          => 'Vê bikarhêner asteng bike',
'ipbother'           => 'demekî din',
'ipboptions'         => '1 seet:1 hour,2 seet:2 hours,6 seet:6 hours,1 roj:1 day,3 roj:3 days,1 hefte:1 week,2 hefte:2 weeks,1 mihe:1 month,3 mihe:3 months,1 sal:1 year,ji her demê ra:infinite',
'ipbotheroption'     => 'yên din',
'ipbotherreason'     => 'Sedemekî din',
'badipaddress'       => 'Bikarhêner bi vî navî tune',
'blockipsuccesssub'  => 'Blok serkeftî',
'blockipsuccesstext' => '"$1" hat asteng kirin.
<br />
Bibîne [[{{ns:special}}:Ipblocklist|Lîsteya IP\'yan hatî asteng kirin]] ji bo lîsteya blokan.',
'blocklistline'      => '$1, $2 $3 asteng kir ($4)',
'blocklink'          => 'asteng bike',
'unblocklink'        => 'betala astengê',
'contribslink'       => 'Beşdarî',
'autoblocker'        => 'Otomatîk hat bestin jiberku IP-ya we û ya "[[User:$1|$1]]" yek in. Sedem: "\'\'\'$2\'\'\'"',
'blocklogpage'       => 'Reşahiya_asteng kiriyan',
'blocklogentry'      => '"[[$1]]" $3 astengkir ji bo dema $2.',
'unblocklogentry'    => 'astenga "$1" hat betal kirin',
'proxyblocksuccess'  => 'Çêbû.',

# Move page
'movepage'         => 'Vê rûpelê bigerîne',
'movepagetalktext' => "Rûpela '''guftûgoyê''' vê rûpelê wê were, eger hebe, gerandin. Lê ev tişta nameşe, eger

*berê guftûgoyek bi wê navê hebe ya
*tu tiştekî jêr hilbijêrê.

Eger ev mişkla çêbû, tu gireke vê rûpelê bi xwe bigerînê.

Xêra xwe navî nuh û sedemê navgerandinê binivisîne.",
'movearticle'      => 'Rûpelê bigerîne',
'movenologin'      => 'Xwe qeyd nekir',
'movenologintext'  => 'Tu dive bikarhênereke qeydkirî bî û [[{{ns:special}}:Userlogin|werî nav sîstemê]]
da bikarî navê wê rûpelê biguherînî.',
'newtitle'         => 'Sernivîsa nû',
'movepagebtn'      => 'Vê rûpelê bigerîne',
'pagemovedsub'     => 'Gerandin serkeftî',
'pagemovedtext'    => 'Rûpela "[[$1]]" çû cihê "[[$2]]".',
'articleexists'    => 'Rûpela bi vî navî heye, an navê ku te hilbijart derbas nabe. Navekî din hilbijêre.',
'movedto'          => 'bû',
'movetalk'         => "Rûpela '''guftûgo''' ya wê jî bigerîne, eger gengaz be.",
'1movedto2'        => '$1 çû cihê $2',
'1movedto2_redir'  => '$1 çû cihê $2 ser redirect',
'movelogpage'      => 'Reşahiya nav guherandin',
'movelogpagetext'  => 'Li jêr lîsteyek ji rûpelan ku navê wan hatiye guherandin heye.',
'movereason'       => 'Sedem',

# Namespace 8 related
'allmessages'               => 'Hemû mesajên sîstemê',
'allmessagesname'           => 'Nav',
'allmessagescurrent'        => 'Texta niha',
'allmessagestext'           => 'Ev lîsteya hemû mesajên di namespace a MediaWiki: de ye.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' nikane were bikaranîn çunkî '''\$wgUseDatabaseMessages''' hatîye temirandin.",

# Thumbnails
'thumbnail-more' => 'Mezin bike',

# Special:Import
'importnotext' => 'Vala an nivîs tune',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Rûpela min a şexsî',
'tooltip-pt-anonuserpage'         => 'The user page for the ip you',
'tooltip-pt-mytalk'               => 'Rûpela guftûgo ya min',
'tooltip-pt-preferences'          => ',Tercîhên min',
'tooltip-pt-watchlist'            => 'The list of pages you',
'tooltip-pt-mycontris'            => 'Lîsteya tevkariyên min',
'tooltip-pt-logout'               => 'Derkeve (Log out)',
'tooltip-ca-talk'                 => 'guftûgo û şîrove ser vê rûpelê',
'tooltip-ca-edit'                 => 'Vê rûpelê biguherîne! Berê qeydkirinê bişkoka "Pêşdîtin',
'tooltip-ca-addsection'           => 'Beşekê zêde bike.',
'tooltip-ca-history'              => 'Versyonên berê yên vê rûpelê.',
'tooltip-ca-protect'              => 'Vê rûplê biparêze',
'tooltip-ca-delete'               => 'Vê rûpelê jê bibe',
'tooltip-ca-move'                 => 'Navekî nû bide vê rûpelê',
'tooltip-search'                  => 'Li vê wikiyê bigêre',
'tooltip-p-logo'                  => 'Destpêk',
'tooltip-n-mainpage'              => 'Biçe Destpêkê',
'tooltip-n-help'                  => 'Bersivên ji bo pirsên te.',
'tooltip-t-whatlinkshere'         => 'Lîsteya hemû rûpelên ku ji vê re grêdidin.',
'tooltip-t-recentchangeslinked'   => 'Recent changes in pages linking to this page',
'tooltip-t-emailuser'             => 'Jê re name bişîne',
'tooltip-ca-nstab-user'           => 'Rûpela bikarhênerê/î temaşe bike',
'tooltip-ca-nstab-special'        => 'This is a special page, you can',
'tooltip-compareselectedversions' => 'Cudatiyên guhartoyên hilbijartî yên vê rûpelê bibîne. [alt-v]',

# Stylesheets
'monobook.css' => '*.rtl 
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
 pre {font-family: Lucida Console, Tahoma;}',

# Attribution
'anonymous' => 'Bikarhênera/ê nediyarkirî ya/yê {{SITENAME}}',
'siteuser'  => 'Bikarhênera $1 ê {{SITENAME}}',
'and'       => 'û',
'others'    => 'ên din',
'siteusers' => 'Bikarhênerên $1 yên {{SITENAME}}',

# Spam protection
'subcategorycount'       => 'Di vê kategoriyê de $1 binkategorî hene.',
'categoryarticlecount'   => 'Di vê kategoriyê de $1 gotar hene.',
'listingcontinuesabbrev' => ' dewam',

# Browsing diffs
'previousdiff' => '← Ciyawaziya pêştir',
'nextdiff'     => 'Ciyawaziya paştir →',

'newimages' => 'Pêşangeha wêneyên nû',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'hemû',
'imagelistall'     => 'hemû',
'watchlistall1'    => 'giştik',
'watchlistall2'    => 'giştik',
'namespacesall'    => 'Hemû',

# E-mail address confirmation
'confirmemail_noemail' => 'Te e-mail-adressê xwe di [[{{ns:special}}:Preferences|tercihên xwe da]] nenivîsandîye.',
'confirmemail_body'    => 'Kesek, dibê tu, bi IP adressê $1, xwe li {{SITENAME}} bi vê navnîşana e-name tomar kir ("$2") .

Eger ev rast qeydkirinê te ye û di dixwazî bikaranîna e-nama ji te ra çêbibe li {{SITENAME}}, li vê lînkê bitikîne:

$3

Lê eger ev *ne* tu bû, li lînkê netikîne. Ev e-nameya di rojê $4 da netê qebûlkirin.',

# Inputbox extension, may be useful in other contexts as well
'createarticle' => 'Gotarê biafirîne',

# Scary transclusion
'scarytranscludefailed' => '[Anîna şablona $1 biserneket; biborîne]',

# Delete conflict
'deletedwhileediting' => 'Hîşyar: Piştî te guherandinê xwe dest pê kir ev rûpela hate jêbirin!',

# action=purge
'confirm_purge'        => 'Bîra vê rûpelê jêbîbe ?

$1',
'confirm_purge_button' => 'Temam',

'youhavenewmessagesmulti' => 'Nameyên nih li $1 ji te ra hene.',

'loginlanguagelabel' => 'Ziman: $1',

# Auto-summaries
'autosumm-blank'   => 'Rûpel hate vala kirin',
'autoredircomment' => 'Redirect berve [[$1]]', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => 'Rûpela nû: $1',

# Live preview
'livepreview-loading' => 'Tê…',
'livepreview-ready'   => 'Tê… Çêbû!',

);

?>

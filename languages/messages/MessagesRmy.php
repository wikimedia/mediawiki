<?php
/**
 * Vlax Romany (Romani)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 * @author Niklas Laxström
 */

/**
 * Use Romanian as default instead of English
 */
$fallback = 'ro';

$namespaceNames = array(
	NS_MEDIA          => 'Mediya',
	NS_SPECIAL        => 'Uzalutno',
	NS_MAIN           => '',
	NS_TALK           => 'Vakyarimata',
	NS_USER           => 'Jeno',
	NS_USER_TALK      => 'Jeno_vakyarimata',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '{{grammar:genitive-pl|$1}}_vakyarimata',
	NS_IMAGE          => 'Chitro',
	NS_IMAGE_TALK     => 'Chitro_vakyarimata',
	NS_MEDIAWIKI      => 'MediyaViki',
	NS_MEDIAWIKI_TALK => 'MediyaViki_vakyarimata',
	NS_TEMPLATE       => 'Sikavno',
	NS_TEMPLATE_TALK  => 'Sikavno_vakyarimata',
	NS_HELP           => 'Zhutipen',
	NS_HELP_TALK      => 'Zhutipen_vakyarimata',
	NS_CATEGORY       => 'Shopni',
	NS_CATEGORY_TALK  => 'Shopni_vakyarimata'
);

$messages = array(
'subcategories'         => 'Telekategoriye',
'mainpage'              => 'Sherutni patrin',
'portal'                => 'Maladipnasko than',
'portal-url'            => 'Project:Maladipnasko than',
'about'                 => 'Andar',
'aboutsite'             => 'Andar {{SITENAME}}',
'aboutpage'             => 'Project:Andar',
'article'               => 'Lekh',
'help'                  => 'Zhutipen',
'helppage'              => 'Project:Źutipen',
'sitesupport'           => 'Denimata',
'edithelp'              => 'Editisaripnasko zhutipen',
'newwindow'             => '(inklel aver filiyastra)',
'edithelppage'          => 'Project:Sar te editisares ek patrin',
'cancel'                => 'Mekh la',
'qbedit'                => 'Editisar',
'qbpageinfo'            => 'Patrinyake janglimata',
'qbspecialpages'        => 'Uzalutne patrya',
'mypage'                => 'Miri patrin',
'mytalk'                => 'Mire vakyarimata',
'navigation'            => 'Phirimos',
'errorpagetitle'        => 'Dosh',
'returnto'              => 'Ja palpale kai $1.',
'search'                => 'Rod',
'searchbutton'          => 'Rod',
'go'                    => 'Ja',
'searcharticle'                    => 'Ja',
'history'               => 'Puraneder versiye',
'history_short'         => 'Puranipen',
'printableversion'      => 'Printisaripnaski versiya',
'permalink'             => 'Savaxtutno phandipen',
'print'                 => 'Printisaripen',
'edit'                  => 'Editisar i patrin',
'editthispage'          => 'Editisar i patrin',
'deletethispage'        => 'Khos i patrin',
'newpage'               => 'Nevi patrin',
'specialpage'           => 'Uzalutni patrin',
'articlepage'           => 'Dikh o lekh',
'talk'                  => 'Vakyarimata',
'toolbox'               => 'Labnengo moxton',
'userpage'              => 'Dikh i jeneski patrin',
'viewtalkpage'          => 'Dikh i diskucia',
'otherlanguages'        => 'Avre ćhibande',
'lastmodifiedat'          => 'O palutno paruvipen $2, $1.',
'jumpto'                => 'Ja kai:',
'retrievedfrom'         => 'Lino katar "$1"',
'editsection'           => 'editisar',
'editsectionhint'       => 'Editisar o kotor: $1',
'toc'                   => 'Ander',
'showtoc'               => 'dikh',
'hidetoc'               => 'garav',
'nstab-main'            => 'Lekh',
'nstab-user'            => 'Jeneski patrin',
'nstab-media'           => 'Mediya patrin',
'nstab-special'         => 'Uzalutno',
'nstab-image'           => 'Chitro',
'nstab-template'        => 'Sikavno',
'nstab-help'            => 'Zhutipen',
'nstab-category'        => 'Kategoriya',
'wrong_wfQuery_params'  => 'Doshalo gin le parametrengo ko wfQuery()<br />I function: $1<br />Query: $2',
'viewsource'            => 'Dikh i sursa',
'loginpagetitle'        => 'Jenesko prinjaripen',
'yourname'              => 'Tiro anav',
'yourpassword'          => 'O nakhavipnasko lav',
'yourpasswordagain'     => 'O nakhavipnasko lav de nevo',
'loginproblem'          => '<b>Sas ek problem le tire prinjaripnaski</b><br />Ker les de nevo!',
'login'                 => 'Prinjaripen',
'userlogin'             => 'Prinjaripen / Ker ek akount',
'userlogout'            => 'De avri',
'nologinlink'           => 'Ker ek akount',
'createaccount'         => 'Ker ek nevo akount',
'youremail'             => 'Emailesko adress (kana kames)*',
'yourrealname'          => 'Tiro chacho anav*',
'yourlanguage'          => 'Ćhib:',
'yournick'              => 'I xarni versyunya, le semnaturenge',
'loginerror'            => 'Prinjaripnaski dosh',
'wrongpassword'         => 'O nakhavipnasko lav so thovdyan si doshalo. Mangas tuke te zumaves vi ekvar.',
'mailmypassword'        => 'Bićhal ma o nakhavipnasko lav e-mail-estar!',
'passwordremindertext'  => 'Varekon (shai te aves tu, katar i adresa $1)
manglyas ek nevo nakahvipnasko lav katar {{SITENAME}}.
O nakhavipnasko lav le jenesko "$2" akana si "$3".
Mishto si te jas kai {{SITENAME}} thai te paruves tiro lav sigo.',
'acct_creation_throttle_hit'=> 'Fal ame nasul, akana si tut $1 akounturya. Nashti te keres aver.',
'accountcreated'        => 'Akount kerdo',
'image_sample'          => 'Misal.jpg',
'summary'               => 'Xarno xalyaripen',
'minoredit'             => 'Kadava si ek tikno editisarimos',
'watchthis'             => 'Dikh kadaya patrin',
'savearticle'           => 'Uxtav i patrin',
'showpreview'           => 'Dikh sar avelas i patrin',
'showdiff'              => 'Dikh le paruvimata',
'whitelistedittitle'    => 'Trebul o [[Special:Userlogin|autentifikaripen]] kashte editisares',
'whitelistedittext'     => 'Trebul te [[Special:Userlogin|autentifikisares]] kashte editisares artikolurya.',
'whitelistreadtitle'    => 'Trebul o autentifikaripen kashte drabares',
'whitelistreadtext'     => 'Trebul te [[Special:Userlogin|autentifikisares]] kashte drabares artikolurya.',
'whitelistacctitle'     => 'Chi shai (nai tuke xakaya) te keres konturya',
'accmailtitle'          => 'O nakhavipnasko lav bićhaldo.',
'accmailtext'           => 'O nakhavipnasko lav andar \'$1\' bićhaldo ko $2.',
'newarticle'            => '(Nevo)',
'newarticletext'        => 'Avilyan kai ek patrin so na si.
Te keres la, shai te shirdes (astares) te lekhaves ando telutno moxton (dikh [[Project:Źutipen|zhutipnaski patrin]] te janes buteder).
Kana avilyan kathe doshatar, ja palpale.',
'noarticletext'         => 'Andi \'\'\'{{SITENAME}}\'\'\' nai ji akana ek lekh kadale anavesa.
* Te shirdes (astares) te keres o lekh, ker klik  \'\'\'[{{fullurl:{{FULLPAGENAME}}|action=edit}} kathe]\'\'\'.',
'editing'               => 'Editisaripen $1',
'editinguser'               => 'Editisaripen $1',
'yourtext'              => 'Tiro teksto',
'storedversion'         => 'Akanutni versiya',
'yourdiff'              => 'Ververimata',
'revhistory'            => 'puranipen le versiyango',
'revnotfoundtext'       => 'I puraneder versiya la patrinyaki so tu manglyan na arakhel pes. Mangas tuke te palemdikhes o phandipen so labyardyan kana avilyan kathe.',
'loadhist'              => 'Ladavav o puranipen le versiyango',
'previousrevision'      => '← Purano paruvipen',
'nextrevision'          => 'Nevi paruvipen →',
'cur'                   => 'akanutni',
'last'                  => 'purani',
'histlegend'            => 'Xalyaripen: (akanutni) = ververimata mamui i akanutni versiya,
(purani) = ververimata mamui i puraneder versiya, T = tikno editisaripen',
'deletedrev'            => '[khoslo]',
'histfirst'             => 'O mai purano',
'histlast'              => 'O mai nevo',
'compareselectedversions'=> 'Dikh ververimata mashkar alosarde versiye',
'prevn'                 => 'mai neve $1',
'nextn'                 => 'mai purane $1',
'viewprevnext'          => 'Dikh ($1) ($2) ($3).',
'showingresults'        => 'Tele si <b>$1</b> rezultaturya shirdindoi le ginestar <b>$2</b>.',
'showingresultsnum'     => 'Tele si <b>$3</b> rezultaturya shirdindoi le ginestar <b>$2</b>.',
'powersearch'           => 'Rod',
'preferences'           => 'Kamimata',
'changepassword'        => 'Paruv o nakhavipnasko lav',
'skin'                  => 'Dikhimos',
'prefs-rc'              => 'Neve paruvimata',
'localtime'             => 'Thanutno vaxt',
'timezoneoffset'        => 'Ververipen',
'changes'               => 'paruvimata',
'recentchanges'         => 'Neve paruvimata',
'recentchangestext'     => 'Andi kadaya patrin shai te dikhes le neve paruvimata andi romani {{SITENAME}}.

[[Project:Mishto avilyan|Mishto avilyan ki {{SITENAME}}]]! Shai te dikhes vi le [[lekh]]a so xalyaren sar jal i {{SITENAME}}: [[{{ns:Project}}:Butvarutne pućhimata|butvarutne pućhimata]], [[Project:Forovipen (politika)|forovipen (politika) la {{SITENAME}}ko]] thai o [[Project:Birigyardo jalipen|birigyardo jalipen]].
But importanto si te na bićhales butya brakhle (arakhle) katar le [[Project:Autorenge xakaya (chachimata)|autorenge xakaya (chachimata)]]. Si te na kerel khonik kadya kashte na avel problemurya ando kado proyekto.',
'rcnote'                => 'Tele si le palutne <strong>$1</strong> paruvimata andar le palutne <strong>$2</strong> divesa.',
'rclistfrom'            => 'Dikh le paruvimata ji kai $1',
'rclinks'               => 'Dikh le palutne $1 paruvimata andar le palutne $2 divesa.<br />$3',
'diff'                  => 'ververipen',
'hist'                  => 'puranipen',
'hide'                  => 'garav',
'show'                  => 'dikh',
'minoreditletter'       => 't',
'upload'                => 'Bićhal file',
'uploadbtn'             => 'Bićhal file',
'filedesc'              => 'Xarno xalyaripen',
'copyrightpage'         => 'Project:Autorenge xakaya (chachimata)',
'badfilename'           => 'O chitrosko anav sas paruvdo; o nevo anav si "$1".',
'imagelist'             => 'Patrinipen le chitrengo',
'imagelistforuser'      => 'Kathe si numa le chitre ladavde katar $1.',
'ilsubmit'              => 'Rod',
'imgdelete'             => 'khos',
'imghistory'            => 'Chitrosko puranipen',
'deleteimg'             => 'khosav',
'deleteimgcompletely'   => 'khosav',
'imagelinks'            => 'Chitroske phandimata',
'unusedtemplates'       => 'Bilabyarde sikavne',
'unusedtemplateswlh'    => 'aver phandimata',
'statistics'            => 'Beshimata',
'sitestats'             => 'Site-ske beshimata',
'userstatstext'         => 'Si <b>$1</b> jene rejistrime (lekhavde).
Mashkar lende <b>$2</b> si administratorurya (dikh $3).',
'wantedpages'           => 'Kamle pajine',
'allpages'              => 'Savore patrya',
'shortpages'            => 'Xarne patrya',
'deadendpages'          => 'Biphandimatenge patrya',
'listusers'             => 'Jenengo patrinipen',
'specialpages'          => 'Uzalutne patrya',
'spheading'             => 'Uzalutne patrya',
'recentchangeslinked'   => 'Pashvipnaske paruvimata',
'rclsub'                => '(le patrinyanca phandle katar "$1")',
'newpages'              => 'Neve patrya',
'ancientpages'          => 'E puraneder lekha',
'intl'                  => 'Phandimata mashkar ćhiba',
'move'                  => 'Ingerdipen',
'nextpage'              => 'Anglutni patrin ($1)',
'allarticles'           => 'Sa le artikolurya',
'allpagessubmit'        => 'Ja',
'emailuser'             => 'Bićhal e-mail kodoleske',
'emailfrom'             => 'Katar',
'emailto'               => 'Karing',
'emailsend'             => 'Bićhal',
'watchlist'             => 'Dikhipnaske lekha',
'addedwatch'            => 'Thovdi ando patrinipen le patrinyange so arakhav len',
'addedwatchtext'        => 'I patrin "[[:$1]]" sas thovdi andi tiri lista [[Special:Watchlist|le artikolengi so dikhes len]].
Le neve paruvimata andar kadale patrya thai andar lenge vakyarimatenge patrya thona kathe, vi dikhena pen le <b>thule semnurenca</b> andi patrin le [[Special:Recentchanges|neve paruvimatenge]].

Kana kamesa te khoses kadaya patrin andar tiri lista le patryange so arakhes len ker click kai "Na mai arakh" (opre, kana i patrin dikhel pes).',
'removedwatchtext'      => 'I patrin "[[:$1]]" sas khosli katar o patrinipen le dikhipnaske lekhenca (artikolurya).',
'watch'                 => 'Dikh la',
'unwatch'               => 'Na mai dikh',
'unwatchthispage'       => 'Na mai dikh',
'wlnote'                => 'Tele si le palutne $1 paruvimata ande palutne <b>$2</b> ore.',
'wlsaved'               => 'Kadaya si i uxtavni versiunya la tiri listyaki le dikhAceasta este o versiune salvată a listei tale de pagini urmărite.',
'enotif_newpagetext'    => 'Kadaya si ek nevi patrin.',
'deletepage'            => 'Khos i patrin',
'excontent'             => 'o ander sas: \'$1\'',
'excontentauthor'       => 'o ander sas: \'$1\' (thai o korkoro butyarno sas \'$2\')',
'exblank'               => 'i patrin sas chuchi',
'deletesub'             => '(Khosav "$1")',
'historywarning'        => 'Dikh! La patrya so kames to khoses la si la puranipen:',
'actioncomplete'        => 'Agorisardi buti',
'deletedtext'           => '"$1" sas khosli.
Dikh ando $2 ek patrinipen le palutne butyange khosle.',
'deletedarticle'        => '"$1" sas khosli.',
'rollback_short'        => 'Palemavilipen',
'rollbacklink'          => 'palemavilipen',
'rollbackfailed'        => 'O palemavilipen nashtisardyas te kerel pes.',
'contributions'         => 'Jeneske butya',
'mycontris'             => 'Mire butya',
'contribsub'            => 'Katar $1',
'uctop'                 => ' (opre)',
'sp-contributions-newest'=> 'O mai nevo',
'sp-contributions-oldest'=> 'O mai purano',
'sp-contributions-newer'=> 'Mai neve $1',
'sp-contributions-older'=> 'Mai purane $1',
'whatlinkshere'         => 'So phandel pes kathe',
'nolinkshere'           => 'Ni ek patrin phandel pes (avel) kathe.',
'contribslink'          => 'butya',
'rights'                => 'Chachimata (xakaya):',
'movearticle'           => 'Inger i patrin',
'pagemovedsub'          => 'I patrin sas bićhaldi.',
'pagemovedtext'         => 'I patrin "[[$1]]" sas bićhaldi karing "[[$2]]".',
'movedto'               => 'ingerdi kai',
'talkpagemoved'         => 'Ingerdi vi i phandli vakyarimatengi patrin.',
'talkpagenotmoved'      => 'I phandli vakyarimatengi patrin <strong>nai</strong> ingerdi.',
'1movedto2'             => '[[$1]] bichhaldo kai [[$2]]',
'allmessages'           => 'Toate mesajele',
'allmessagesname'       => 'Anav',
'lastmodifiedatby'        => 'Kadaya patrin sas paruvdi agoreste $2, $1 katar $3.',
'and'                   => 'thai',
'others'                => 'aver',
'Monobook.js'           => '/* tooltips and access keys */
var ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Miri labyarneski pajina\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'Miri labyarneski pajina ki akanutni IP adress\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'Miri diskuciyaki pajina\');
ta[\'pt-anontalk\'] = new Array(\'n\',\'Diskucie le editisarimatenge ki akanutni IP adress\');
ta[\'pt-preferences\'] = new Array(\'\',\'Sar kamav te dikhel pes miri pajina\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'I lista le pajinenge so dikhav lendar (monitorizav).\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'Le mire editisarimata\');
ta[\'pt-login\'] = new Array(\'o\',\'Mishto si te identifikares tut, pale na si musai.\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'Mishto si te identifikares tut, pale na si musai.\');
ta[\'pt-logout\'] = new Array(\'\',\'Kathe aćhaves i sesiyunya\');
ta[\'ca-talk\'] = new Array(\'t\',\'Diskuciya le artikoleske\');
ta[\'ca-edit\'] = new Array(\'e\',\'Shai te editisares kadaya pajina. Mangas te paledikhes o teksto anglal te uxtaves les.\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Kathe shai te thos ek komentaryo ki kadaya diskuciya.\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'Kadaya pajina si brakhli. Shai numa te dikhes o source-code.\');
ta[\'ca-history\'] = new Array(\'h\',\'Purane versiune le dokumenteske.\');
ta[\'ca-protect\'] = new Array(\'=\',\'Brakh kadava dokumento.\');
ta[\'ca-delete\'] = new Array(\'d\',\'Khos kadava dokumento.\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Palemthav le editisarimata kerdine le kadale dokumenteske sar sas anglal lesko khosipen.\');
ta[\'ca-move\'] = new Array(\'m\',\'Trade kadava dokumento.\');
ta[\'ca-nomove\'] = new Array(\'\',\'Nai tuke shayutnipen te trades kadava dokumento.\');
ta[\'ca-watch\'] = new Array(\'w\',\'Thav kadava dokumento andi monitorizaripnaski lista.\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Khos kadava dokumento andar i monitorizaripnaski lista.\');
ta[\'search\'] = new Array(\'f\',\'Rod andi kadaya Wiki\');
ta[\'p-logo\'] = new Array(\'\',\'I sherutni pajina\');
ta[\'n-mainpage\'] = new Array(\'z\',\'Dikh i sherutni pajina\');
ta[\'n-portal\'] = new Array(\'\',\'O proyekto, so shai te keres, kai arakhes solucie.\');
ta[\'n-currentevents\'] = new Array(\'\',\'Arakh janglimata le akanutne evenimenturenge\');
ta[\'n-recentchanges\'] = new Array(\'r\',\'I lista le neve paruvimatenge kerdini andi kadaya wiki.\');
ta[\'n-randompage\'] = new Array(\'x\',\'Ja ki ek aleatori pajina\');
ta[\'n-help\'] = new Array(\'\',\'O than kai arakhes zhutipen.\');
ta[\'n-sitesupport\'] = new Array(\'\',\'Zhutisar amen\');
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'I lista sa le wiki pajinenge so aven (si phande) vi kathe\');
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Neve paruvimata andi kadaya pajina\');
ta[\'feed-rss\'] = new Array(\'\',\'Kathe te pravares o RSS flukso le kadale pajinyako\');
ta[\'feed-atom\'] = new Array(\'\',\'Kathe te pravares o Atom flukso le kadale pajinyako\');
ta[\'t-contributions\'] = new Array(\'\',\'Dikh i lista le editisarimatenge le kadale labyaresko\');
ta[\'t-emailuser\'] = new Array(\'\',\'Bićhal ek emailo le kadale labyareske\');
ta[\'t-upload\'] = new Array(\'u\',\'Bićhal imajine vai media files\');
ta[\'t-specialpages\'] = new Array(\'q\',\'I lista sa le spechiale pajinengi\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Dikh o artikolo\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Dikh i labyarengi pajina\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Dikh i pajina media\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'Kadaya si ek spechiali pajina, nashti te editisares la.\');
ta[\'ca-nstab-project\'] = new Array(\'a\',\'Dikh i pajina le proyekteski\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Dikh i imajinyaki pajina\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Dikh o mesajo le sistemesko\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Dikh o formato\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Dikh i zhutipnaski pajina\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Dikh i kategoriya\');',
'deletedrevision'       => 'Khoslo o purano paruvipen $1.',
'previousdiff'          => '← Purano ververipen',
'nextdiff'              => 'Anglutno paruvipen →',
'showhidebots'          => '($1 boturya)',
'recentchangesall'      => 'sa',
'imagelistall'          => 'savore',
'watchlistall1'         => 'savore',
'watchlistall2'         => 'savore',
'namespacesall'         => 'savore',
'deletedwhileediting'   => 'Dikh: Kadaya patrin sas khosli de kana shirdyas (astardyas) te editisares la!',
);
?>

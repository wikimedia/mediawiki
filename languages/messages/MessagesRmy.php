<?php
/**
 * Vlax Romany (Romani)
 *
 * @addtogroup Language
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
# Bits of text used by many pages
'subcategories' => 'Telekategoriye',

'about'          => 'Andar',
'article'        => 'Lekh',
'newwindow'      => '(inklel aver filiyastra)',
'cancel'         => 'Mekh la',
'qbedit'         => 'Editisar',
'qbpageinfo'     => 'Patrinyake janglimata',
'qbspecialpages' => 'Uzalutne patrya',
'mypage'         => 'Miri patrin',
'mytalk'         => 'Mire vakyarimata',
'navigation'     => 'Phirimos',

'errorpagetitle'   => 'Dosh',
'returnto'         => 'Ja palpale kai $1.',
'help'             => 'Zhutipen',
'search'           => 'Rod',
'searchbutton'     => 'Rod',
'go'               => 'Ja',
'searcharticle'    => 'Ja',
'history'          => 'Puraneder versiye',
'history_short'    => 'Puranipen',
'printableversion' => 'Printisaripnaski versiya',
'permalink'        => 'Savaxtutno phandipen',
'print'            => 'Printisaripen',
'edit'             => 'Editisar i patrin',
'editthispage'     => 'Editisar i patrin',
'deletethispage'   => 'Khos i patrin',
'newpage'          => 'Nevi patrin',
'specialpage'      => 'Uzalutni patrin',
'articlepage'      => 'Dikh o lekh',
'talk'             => 'Vakyarimata',
'toolbox'          => 'Labnengo moxton',
'userpage'         => 'Dikh i jeneski patrin',
'viewtalkpage'     => 'Dikh i diskucia',
'otherlanguages'   => 'Avre ćhibande',
'lastmodifiedat'   => 'O palutno paruvipen $2, $1.', # $1 date, $2 time
'jumpto'           => 'Ja kai:',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'     => 'Andar {{SITENAME}}',
'aboutpage'     => 'Project:Andar',
'copyrightpage' => 'Project:Autorenge xakaya (chachimata)',
'edithelp'      => 'Editisaripnasko zhutipen',
'edithelppage'  => 'Project:Sar te editisares ek patrin',
'helppage'      => 'Project:Źutipen',
'mainpage'      => 'Sherutni patrin',
'portal'        => 'Maladipnasko than',
'portal-url'    => 'Project:Maladipnasko than',
'sitesupport'   => 'Denimata',

'retrievedfrom'   => 'Lino katar "$1"',
'editsection'     => 'editisar',
'editsectionhint' => 'Editisar o kotor: $1',
'toc'             => 'Ander',
'showtoc'         => 'dikh',
'hidetoc'         => 'garav',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'     => 'Lekh',
'nstab-user'     => 'Jeneski patrin',
'nstab-media'    => 'Mediya patrin',
'nstab-special'  => 'Uzalutno',
'nstab-image'    => 'Chitro',
'nstab-template' => 'Sikavno',
'nstab-help'     => 'Zhutipen',
'nstab-category' => 'Kategoriya',

# General errors
'wrong_wfQuery_params' => 'Doshalo gin le parametrengo ko wfQuery()<br />I function: $1<br />Query: $2',
'viewsource'           => 'Dikh i sursa',

# Login and logout pages
'loginpagetitle'             => 'Jenesko prinjaripen',
'yourname'                   => 'Tiro anav',
'yourpassword'               => 'O nakhavipnasko lav',
'yourpasswordagain'          => 'O nakhavipnasko lav de nevo',
'loginproblem'               => '<b>Sas ek problem le tire prinjaripnaski</b><br />Ker les de nevo!',
'login'                      => 'Prinjaripen',
'userlogin'                  => 'Prinjaripen / Ker ek akount',
'userlogout'                 => 'De avri',
'nologinlink'                => 'Ker ek akount',
'createaccount'              => 'Ker ek nevo akount',
'youremail'                  => 'Emailesko adress (kana kames)*',
'yourrealname'               => 'Tiro chacho anav*',
'yourlanguage'               => 'Ćhib:',
'yournick'                   => 'I xarni versyunya, le semnaturenge',
'loginerror'                 => 'Prinjaripnaski dosh',
'wrongpassword'              => 'O nakhavipnasko lav so thovdyan si doshalo. Mangas tuke te zumaves vi ekvar.',
'mailmypassword'             => 'Bićhal ma o nakhavipnasko lav e-mail-estar!',
'passwordremindertext'       => 'Varekon (shai te aves tu, katar i adresa $1)
manglyas ek nevo nakahvipnasko lav katar {{SITENAME}}.
O nakhavipnasko lav le jenesko "$2" akana si "$3".
Mishto si te jas kai {{SITENAME}} thai te paruves tiro lav sigo.',
'acct_creation_throttle_hit' => 'Fal ame nasul, akana si tut $1 akounturya. Nashti te keres aver.',
'accountcreated'             => 'Akount kerdo',

# Edit page toolbar
'image_sample' => 'Misal.jpg',

# Edit pages
'summary'            => 'Xarno xalyaripen',
'minoredit'          => 'Kadava si ek tikno editisarimos',
'watchthis'          => 'Dikh kadaya patrin',
'savearticle'        => 'Uxtav i patrin',
'showpreview'        => 'Dikh sar avelas i patrin',
'showdiff'           => 'Dikh le paruvimata',
'whitelistedittitle' => 'Trebul o [[Special:Userlogin|autentifikaripen]] kashte editisares',
'whitelistedittext'  => 'Trebul te [[Special:Userlogin|autentifikisares]] kashte editisares artikolurya.',
'whitelistreadtitle' => 'Trebul o autentifikaripen kashte drabares',
'whitelistreadtext'  => 'Trebul te [[Special:Userlogin|autentifikisares]] kashte drabares artikolurya.',
'whitelistacctitle'  => 'Chi shai (nai tuke xakaya) te keres konturya',
'accmailtitle'       => 'O nakhavipnasko lav bićhaldo.',
'accmailtext'        => "O nakhavipnasko lav andar '$1' bićhaldo ko $2.",
'newarticle'         => '(Nevo)',
'newarticletext'     => 'Avilyan kai ek patrin so na si.
Te keres la, shai te shirdes (astares) te lekhaves ando telutno moxton (dikh [[{{MediaWiki:helppage}}|zhutipnaski patrin]] te janes buteder).
Kana avilyan kathe doshatar, ja palpale.',
'noarticletext'      => "Andi '''{{SITENAME}}''' nai ji akana ek lekh kadale anavesa.
* Te shirdes (astares) te keres o lekh, ker klik  '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} kathe]'''.",
'editing'            => 'Editisaripen $1',
'editinguser'        => 'Editisaripen $1',
'yourtext'           => 'Tiro teksto',
'storedversion'      => 'Akanutni versiya',
'yourdiff'           => 'Ververimata',

# History pages
'revhistory'       => 'puranipen le versiyango',
'revnotfoundtext'  => 'I puraneder versiya la patrinyaki so tu manglyan na arakhel pes. Mangas tuke te palemdikhes o phandipen so labyardyan kana avilyan kathe.',
'loadhist'         => 'Ladavav o puranipen le versiyango',
'previousrevision' => '← Purano paruvipen',
'nextrevision'     => 'Nevi paruvipen →',
'cur'              => 'akanutni',
'last'             => 'purani',
'histlegend'       => 'Xalyaripen: (akanutni) = ververimata mamui i akanutni versiya,
(purani) = ververimata mamui i puraneder versiya, T = tikno editisaripen',
'deletedrev'       => '[khoslo]',
'histfirst'        => 'O mai purano',
'histlast'         => 'O mai nevo',

# Diffs
'compareselectedversions' => 'Dikh ververimata mashkar alosarde versiye',

# Search results
'prevn'             => 'mai neve $1',
'nextn'             => 'mai purane $1',
'viewprevnext'      => 'Dikh ($1) ($2) ($3).',
'showingresults'    => 'Tele si <b>$1</b> rezultaturya shirdindoi le ginestar <b>$2</b>.',
'showingresultsnum' => 'Tele si <b>$3</b> rezultaturya shirdindoi le ginestar <b>$2</b>.',
'powersearch'       => 'Rod',

# Preferences page
'preferences'    => 'Kamimata',
'changepassword' => 'Paruv o nakhavipnasko lav',
'skin'           => 'Dikhimos',
'prefs-rc'       => 'Neve paruvimata',
'localtime'      => 'Thanutno vaxt',
'timezoneoffset' => 'Ververipen',

# Recent changes
'changes'           => 'paruvimata',
'recentchanges'     => 'Neve paruvimata',
'recentchangestext' => 'Andi kadaya patrin shai te dikhes le neve paruvimata andi romani {{SITENAME}}.',
'rcnote'            => 'Tele si le palutne <strong>$1</strong> paruvimata andar le palutne <strong>$2</strong> divesa.',
'rclistfrom'        => 'Dikh le paruvimata ji kai $1',
'rclinks'           => 'Dikh le palutne $1 paruvimata andar le palutne $2 divesa.<br />$3',
'diff'              => 'ververipen',
'hist'              => 'puranipen',
'hide'              => 'garav',
'show'              => 'dikh',
'minoreditletter'   => 't',

# Upload
'upload'      => 'Bićhal file',
'uploadbtn'   => 'Bićhal file',
'filedesc'    => 'Xarno xalyaripen',
'badfilename' => 'O chitrosko anav sas paruvdo; o nevo anav si "$1".',

# Image list
'imagelist'           => 'Patrinipen le chitrengo',
'imagelistforuser'    => 'Kathe si numa le chitre ladavde katar $1.',
'ilsubmit'            => 'Rod',
'imgdelete'           => 'khos',
'imghistory'          => 'Chitrosko puranipen',
'deleteimg'           => 'khosav',
'deleteimgcompletely' => 'khosav',
'imagelinks'          => 'Chitroske phandimata',

# Unused templates
'unusedtemplates'    => 'Bilabyarde sikavne',
'unusedtemplateswlh' => 'aver phandimata',

# Statistics
'statistics'    => 'Beshimata',
'sitestats'     => 'Site-ske beshimata',
'userstatstext' => 'Si <b>$1</b> jene rejistrime (lekhavde).
Mashkar lende <b>$2</b> si administratorurya (dikh $3).',

# Miscellaneous special pages
'wantedpages'         => 'Kamle pajine',
'allpages'            => 'Savore patrya',
'shortpages'          => 'Xarne patrya',
'deadendpages'        => 'Biphandimatenge patrya',
'listusers'           => 'Jenengo patrinipen',
'specialpages'        => 'Uzalutne patrya',
'spheading'           => 'Uzalutne patrya',
'recentchangeslinked' => 'Pashvipnaske paruvimata',
'rclsub'              => '(le patrinyanca phandle katar "$1")',
'newpages'            => 'Neve patrya',
'ancientpages'        => 'E puraneder lekha',
'intl'                => 'Phandimata mashkar ćhiba',
'move'                => 'Ingerdipen',

# Special:Allpages
'nextpage'       => 'Anglutni patrin ($1)',
'allarticles'    => 'Sa le artikolurya',
'allpagessubmit' => 'Ja',

# E-mail user
'emailuser' => 'Bićhal e-mail kodoleske',
'emailfrom' => 'Katar',
'emailto'   => 'Karing',
'emailsend' => 'Bićhal',

# Watchlist
'watchlist'        => 'Dikhipnaske lekha',
'my-watchlist'        => 'Dikhipnaske lekha',
'addedwatch'       => 'Thovdi ando patrinipen le patrinyange so arakhav len',
'addedwatchtext'   => 'I patrin "[[:$1]]" sas thovdi andi tiri lista [[Special:Watchlist|le artikolengi so dikhes len]].
Le neve paruvimata andar kadale patrya thai andar lenge vakyarimatenge patrya thona kathe, vi dikhena pen le <b>thule semnurenca</b> andi patrin le [[Special:Recentchanges|neve paruvimatenge]].

Kana kamesa te khoses kadaya patrin andar tiri lista le patryange so arakhes len ker click kai "Na mai arakh" (opre, kana i patrin dikhel pes).',
'removedwatchtext' => 'I patrin "[[:$1]]" sas khosli katar o patrinipen le dikhipnaske lekhenca (artikolurya).',
'watch'            => 'Dikh la',
'unwatch'          => 'Na mai dikh',
'unwatchthispage'  => 'Na mai dikh',
'wlnote'           => 'Tele si le palutne $1 paruvimata ande palutne <b>$2</b> ore.',
'wlsaved'          => 'Kadaya si i uxtavni versiunya la tiri listyaki le dikhAceasta este o versiune salvată a listei tale de pagini urmărite.',

'enotif_newpagetext' => 'Kadaya si ek nevi patrin.',

# Delete/protect/revert
'deletepage'      => 'Khos i patrin',
'excontent'       => "o ander sas: '$1'",
'excontentauthor' => "o ander sas: '$1' (thai o korkoro butyarno sas '$2')",
'exblank'         => 'i patrin sas chuchi',
'deletesub'       => '(Khosav "$1")',
'historywarning'  => 'Dikh! La patrya so kames to khoses la si la puranipen:',
'actioncomplete'  => 'Agorisardi buti',
'deletedtext'     => '"$1" sas khosli.
Dikh ando $2 ek patrinipen le palutne butyange khosle.',
'deletedarticle'  => '"$1" sas khosli.',
'rollback_short'  => 'Palemavilipen',
'rollbacklink'    => 'palemavilipen',
'rollbackfailed'  => 'O palemavilipen nashtisardyas te kerel pes.',

# Contributions
'contributions' => 'Jeneske butya',
'mycontris'     => 'Mire butya',
'contribsub'    => 'Katar $1',
'uctop'         => ' (opre)',

'sp-contributions-newest' => 'O mai nevo',
'sp-contributions-oldest' => 'O mai purano',
'sp-contributions-newer'  => 'Mai neve $1',
'sp-contributions-older'  => 'Mai purane $1',

# What links here
'whatlinkshere' => 'So phandel pes kathe',
'nolinkshere'   => 'Ni ek patrin phandel pes (avel) kathe.',

# Block/unblock
'contribslink' => 'butya',

# Move page
'movearticle'      => 'Inger i patrin',
'pagemovedsub'     => 'I patrin sas bićhaldi.',
'pagemovedtext'    => 'I patrin "[[$1]]" sas bićhaldi karing "[[$2]]".',
'movedto'          => 'ingerdi kai',
'talkpagemoved'    => 'Ingerdi vi i phandli vakyarimatengi patrin.',
'talkpagenotmoved' => 'I phandli vakyarimatengi patrin <strong>nai</strong> ingerdi.',
'1movedto2'        => '[[$1]] bichhaldo kai [[$2]]',

# Namespace 8 related
'allmessages'     => 'Toate mesajele',
'allmessagesname' => 'Anav',

# Tooltip help for the actions
'tooltip-pt-userpage'           => 'Miri labyarneski pajina',
'tooltip-pt-anonuserpage'       => 'Miri labyarneski pajina ki akanutni IP adress',
'tooltip-pt-mytalk'             => 'Miri diskuciyaki pajina',
'tooltip-pt-anontalk'           => 'Diskucie le editisarimatenge ki akanutni IP adress',
'tooltip-pt-preferences'        => 'Sar kamav te dikhel pes miri pajina',
'tooltip-pt-watchlist'          => 'I lista le pajinenge so dikhav lendar (monitorizav).',
'tooltip-pt-mycontris'          => 'Le mire editisarimata',
'tooltip-pt-login'              => 'Mishto si te identifikares tut, pale na si musai.',
'tooltip-pt-anonlogin'          => 'Mishto si te identifikares tut, pale na si musai.',
'tooltip-pt-logout'             => 'Kathe aćhaves i sesiyunya',
'tooltip-ca-talk'               => 'Diskuciya le artikoleske',
'tooltip-ca-edit'               => 'Shai te editisares kadaya pajina. Mangas te paledikhes o teksto anglal te uxtaves les.',
'tooltip-ca-addsection'         => 'Kathe shai te thos ek komentaryo ki kadaya diskuciya.',
'tooltip-ca-viewsource'         => 'Kadaya pajina si brakhli. Shai numa te dikhes o source-code.',
'tooltip-ca-history'            => 'Purane versiune le dokumenteske.',
'tooltip-ca-protect'            => 'Brakh kadava dokumento.',
'tooltip-ca-delete'             => 'Khos kadava dokumento.',
'tooltip-ca-undelete'           => 'Palemthav le editisarimata kerdine le kadale dokumenteske sar sas anglal lesko khosipen.',
'tooltip-ca-move'               => 'Trade kadava dokumento.',
'tooltip-ca-watch'              => 'Thav kadava dokumento andi monitorizaripnaski lista.',
'tooltip-ca-unwatch'            => 'Khos kadava dokumento andar i monitorizaripnaski lista.',
'tooltip-search'                => 'Rod andi kadaya Wiki',
'tooltip-p-logo'                => 'I sherutni pajina',
'tooltip-n-mainpage'            => 'Dikh i sherutni pajina',
'tooltip-n-portal'              => 'O proyekto, so shai te keres, kai arakhes solucie.',
'tooltip-n-currentevents'       => 'Arakh janglimata le akanutne evenimenturenge',
'tooltip-n-recentchanges'       => 'I lista le neve paruvimatenge kerdini andi kadaya wiki.',
'tooltip-n-randompage'          => 'Ja ki ek aleatori pajina',
'tooltip-n-help'                => 'O than kai arakhes zhutipen.',
'tooltip-n-sitesupport'         => 'Zhutisar amen',
'tooltip-t-whatlinkshere'       => 'I lista sa le wiki pajinenge so aven (si phande) vi kathe',
'tooltip-t-recentchangeslinked' => 'Neve paruvimata andi kadaya pajina',
'tooltip-feed-rss'              => 'Kathe te pravares o RSS flukso le kadale pajinyako',
'tooltip-feed-atom'             => 'Kathe te pravares o Atom flukso le kadale pajinyako',
'tooltip-t-contributions'       => 'Dikh i lista le editisarimatenge le kadale labyaresko',
'tooltip-t-emailuser'           => 'Bićhal ek emailo le kadale labyareske',
'tooltip-t-upload'              => 'Bićhal imajine vai media files',
'tooltip-t-specialpages'        => 'I lista sa le spechiale pajinengi',
'tooltip-ca-nstab-main'         => 'Dikh o artikolo',
'tooltip-ca-nstab-user'         => 'Dikh i labyarengi pajina',
'tooltip-ca-nstab-media'        => 'Dikh i pajina media',
'tooltip-ca-nstab-special'      => 'Kadaya si ek spechiali pajina, nashti te editisares la.',
'tooltip-ca-nstab-project'      => 'Dikh i pajina le proyekteski',
'tooltip-ca-nstab-image'        => 'Dikh i imajinyaki pajina',
'tooltip-ca-nstab-mediawiki'    => 'Dikh o mesajo le sistemesko',
'tooltip-ca-nstab-template'     => 'Dikh o formato',
'tooltip-ca-nstab-help'         => 'Dikh i zhutipnaski pajina',
'tooltip-ca-nstab-category'     => 'Dikh i kategoriya',

# Attribution
'lastmodifiedatby' => 'Kadaya patrin sas paruvdi agoreste $2, $1 katar $3.', # $1 date, $2 time, $3 user
'and'              => 'thai',
'others'           => 'aver',

# Image deletion
'deletedrevision' => 'Khoslo o purano paruvipen $1.',

# Browsing diffs
'previousdiff' => '← Purano ververipen',
'nextdiff'     => 'Anglutno paruvipen →',

'showhidebots' => '($1 boturya)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'sa',
'imagelistall'     => 'savore',
'watchlistall1'    => 'savore',
'watchlistall2'    => 'savore',
'namespacesall'    => 'savore',

# Delete conflict
'deletedwhileediting' => 'Dikh: Kadaya patrin sas khosli de kana shirdyas (astardyas) te editisares la!',

);

?>

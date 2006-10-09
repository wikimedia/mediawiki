<?php
/** Basque (Euskara)
 *
 * @package MediaWiki
 * @subpackage Language
 */


$quickbarSettings = array(
	'Ezein ere', 'Eskuinean', 'Ezkerrean', 'Ezkerrean mugikor'
);

$skinNames = array(
	'standard'     => 'Lehenetsia',
	'nostalgia'    => 'Nostalgia',
	'cologneblue'  => 'Cologne Blue',
	'smarty'       => 'Paddington',
	'montparnasse' => 'Montparnasse'
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
'1movedto2' => '$1 izenburua $2-en truke aldatu da.',
'about' => 'buruz',
'aboutpage' => '{{ns:project}}:{{SITENAME}}ri_buruz', // TODO: grammar
'accesskey-diff' => 'd',
'allmessages' => 'Mezu_guztiak',
'allpages' => 'Orri guztiak',
'alphaindexline' => '$1 -tik $2 -raino',
'alreadyloggedin' => '<strong>Lankide $1, barruan zaude!</strong><br />',
'ancientpages' => 'Orri zaharrak',
'bold_sample' => 'Lodia',
'bold_tip' => 'Lodia',
'cancel' => 'Bertan behera utzi',
'categories' => 'Kategoriak',
'category_header' => '"$1" kategoriako artikuluak',
'categoryarticlecount' => 'Kategoria honetan $1 artikulu daude.',
'contributions' => 'Lankidearen ekarpenak',
/*'copyrightwarning' => 'Mesedez, egin kontu {{SITENAME}}-ri egindako ekarpen guztiak GNU Dokumentazio aske Lizentziaren barnean egin dela suposatzen dela (begira $1). Ez ezazu sakatu bidaltzeko botoia zure idatzia, baimenik gabe eta zure nahiaren aurka hedatzen ikustea nahi ez baduzu. Zu ere, idatzia zure kabuz idatzi duzula, edo publikora zabaldutako leku batetik ateratzen ari zarela agintzen ari zara. <strong>EZ EZAZU COPYRIGHT BATEN MENPEAN DAGOEN LANA BAIMENIK GABE ERABILI!</strong>', // TODO: grammar*/
'cur' => 'azk',
'currentevents' => 'Gaurkotasun',
'currentrev' => 'Azken eguneratzea',
'deadendpages' => 'Artikulu itsuak',
'deletethispage' => 'Orria ezabatu',
'edithelp' => 'Editatzeko laguntza',
'edithelppage' => '{{ns:project}}:Editatzeko laguntza',
'editing' => '"$1" editatzen',
'editinguser' => '"$1" editatzen',
'editsection' => 'editatu',
'editold' => 'editatu',
'editthispage' => 'Orria editatu',
'go' => 'Joan',
'searcharticle' => 'Joan',
'headline_sample' => 'Goiburuko',
'headline_tip' => '2. mailako goiburukoa',
'help' => 'Laguntza',
'helppage' => '{{ns:project}}:Laguntza',
'hide' => 'ezkutatu',
'hidetoc' => 'ezkutatu',
'histlegend' => 'Legenda: betsionen artean desberdintasunak: (azk) = azkena, (aur) = aurrekoa;
t = edikateka txikiak',
'history' => 'Orriaren historia',
'history_short' => 'Historia',
'hr_tip' => 'Lerro horizontal (neurritasunaz)',
'ilsubmit' => 'Bilatu',
'image_sample' => 'Adibide.png',
'imagelist' => 'Irudien zerrenda',
'italic_sample' => 'Etzana',
'italic_tip' => 'Etzana',
'last' => 'aur',
'lastmodifiedat' => 'Orriaren azken eguneratzea: $2, $1.',
'loadhist' => 'Orriaren historia kargatzen',
'login' => 'Izena eman/Saio-hasiera',
'loginpagetitle' => 'Saio hasiera',
'loginproblem' => '<b>Arazoren bat egon da zure saio-hasieran.</b><br />¡Saiatu berriro!',
'logout' => 'Saio-bukaera',
'logouttext' => 'Zure saioa amaitu duzu.
Izena eman gabe {{SITENAME}} erabiltzen jarraitu ahal duzu, edo izen berdin edo bestearekin beste saioa hasi ahal duzu.<br />Orri batzuk saioa mantentzen duzuela adierazi dezakete arakatzailearen katxea garbitu arte.',
'logouttitle' => 'Saio amaiera',
'lonelypages' => 'Orri umezurtzak',
'longpages' => 'Orri luzeak',
'mainpage' => 'Azala',
'minoredit' => 'Edizio txikia',
'minoreditletter' => 't',
'moredotdotdot' => 'Gehiago...',
'movearticle' => 'Oraingo izenburua',
'movepage' => 'Orriaren izenburua aldatu',
'movepagebtn' => 'Orriaren izenburua aldatu',
'movethispage' => 'Izenburua aldatu',
'mycontris' => 'Nire ekarpenak',
'newarticletext' => 'Orri hau ez dago datu-basean; artikulua hastea nahi baduzu, testu lehioan idatzi dezakezu (Mesedez, zure lehen bisita bada, irakurri lehen [[{{ns:project}}:Laguntza|Laguntza orria]]).
Honaino nahigabe helduz gero, zure arakatzaileko \'\'\'atzera\'\'\' botoia sakatu.',
'newpage' => 'Orri berria',
'newpageletter' => 'B',
'newpages' => 'Orri berriak',
'newtitle' => 'Izenburu berria',
'nextn' => 'hurrengo $1ak',
'nlinks' => '$1 esteka',
'noname' => 'Lankide izena ez duzu eman.',
'nowatchlist' => 'Zure segimendu zerrenda hutsik dago.',
'nstab-category' => 'Kategoria',
'nstab-help' => 'Laguntza',
'nstab-image' => 'Irudia',
'nstab-main' => 'Artikulua',
'nstab-mediawiki' => 'Oharra',
'nstab-special' => 'Berezia',
'nstab-template' => 'Txantiloia',
'otherlanguages' => 'Beste hizkuntzak',
'pagemovedtext' => '"$1"-ren izenburua "$2"-en truke aldatu da.',
'passwordremindertext' => 'Norbaitek (zu seguruenik, IP $1 helbidetik) {{SITENAME}}n saio berria hasteko pasahitza bidaltzea eskatu du.
"$2" lankidearen pasahitza orain "$3" da.
Mesedez, hasi saioa eta pasahitz hau berri baten truke aldatu.', // TODO: grammar
'passwordremindertitle' => '{{SITENAME}}ren pasahitz oroigarria', // TODO: grammar
'passwordsent' => 'Pasahitz oroigarria "$1"-ren helbide elektronikora bidali dugu.
Mesedez hasi saioa pasahitza hartu bezain laster.',
'popularpages' => 'Orri bisitatuenak',
'preferences' => 'Hobespenak',
'prefs-misc' => 'Nahaztea',
'preview' => 'Aurrebista',
'prevn' => 'aurreko $1ak',
'printableversion' => 'Inprimatzeko bertsio',
'randompage' => 'Ausazko orria',
'rclinks' => 'Erakutsi azken $1 aldaketak $2 egunetan.<br />$3',
'rclistfrom' => 'Erakutsi $1tik aldaketa berriak',
'rcnote' => 'Azken <strong>$1</strong> aldaketak <strong>$2</strong> egunetan erakusten.',
'recentchanges' => 'Aldaketa berriak',
'recentchangeslinked' => 'Lotutako orrien aldaketak',
'remembermypassword' => 'Gogoratu pasahitza saio tartean (cookie gorde).',
'savearticle' => 'Orria gorde',
'search' => 'Bilatu',
'searchbutton' => 'Bilatu',
'searchresults' => 'Bilaketaren emaitza',
'shortpages' => 'Artikulu laburrak',
'show' => 'erakutsi',
'showpreview' => 'Aurrebista erakutsi',
'showtoc' => 'erakutsi',
'sitestats' => 'Gunearen estatistikak',
'sitestatstext' => 'Datu-basean guztira <b>$1</b> orri daude; eztabaidatzeko, wikipedari buruzko orriak, \'\'redirect\'\'-k eta artikulu laburrak barne hartzen.
Horiek baztertzen, <b>$2</b> artikulu dira datu-basean.<p>
There have been a total of <b>$3</b> page views, and <b>$4</b> page edits
since the software was upgraded (July 20, 2002).
That comes to <b>$5</b> average edits per page, and <b>$6</b> views per edit.',
'sitesupport' => 'Emariak',
'specialpages' => 'Orri bereziak',
'statistics' => 'Estatistikak',
'summary' => 'Laburpen',
'talk' => 'Eztabaida',
'talkpage' => 'Eztabaida orri honen gainean',
'toc' => 'Aurkibidea',
'toolbox' => 'Lanabesak',
'undelete' => 'Orria ezabatuta berreskuratu',
'unusedimages' => 'Irudi umezurtzak',
'userexists' => 'Beste lankide erabiltzen ari den izena eman duzu. Mesedez, beste izen aukeratu.',
'userlogin' => 'Izena eman edo saio berria hasi',
'userstats' => 'Lankideen estatistikak',
'userstatstext' => '<b>$1</b> lankideek izena eman dute.
<b>$2</b> administratzaileak dira (ikusi $3).',
'viewprevnext' => 'Erakutsi ($1) ($2) ($3).',
'viewtalkpage' => 'Eztabaida erakutsi',
'wantedpages' => 'Orri eskatutakoenak',
'watchlist' => 'Segimendu zerrenda',
'watchlistcontains' => 'Zure segimendu zerrenda $1 orri ditu.',
'watchthis' => 'Artikulua zelatatu',
'watchthispage' => 'Orria zelatatu',
'welcomecreation' => '<h2>Ongi etorri, $1!</h2><p>Zure kontua sotu duzu.
Ez ahaztu zure hobespenak pertsonalizatu.',
'whatlinkshere' => 'Honekin lotzen diren orriak',
'projectpage' => 'Erakutsi Meta-orria',
'wrongpassword' => 'Pasahitza ez da zuzena. Saiatu berriro.',
'yourdiff' => 'Desberdintasunak',
'youremail' => 'Zure helbide elektronikoa (e-mail)*',
'yourname' => 'Zure erabiltzaile-izena',
'yournick' => 'Zure gaitzizena (sinatzeko)',
'yourpassword' => 'Zure pasahitza',
'yourpasswordagain' => 'Idatzi berriro pasahitza',
'yourrealname' => 'Zure benetako izena*',
'yourtext' => 'Zure testua',

);


?>

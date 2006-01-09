<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once("LanguageUtf8.php");

/* private */ $wgNamespaceNamesSq = array(
	NS_MEDIA          => "Media",
	NS_SPECIAL        => "Speciale",
	NS_MAIN           => "",
	NS_TALK           => "Diskutim",
	NS_USER           => "Përdoruesi",
	NS_USER_TALK      => "Përdoruesi_diskutim",
	NS_PROJECT        => $wgMetaNamespace,
	NS_PROJECT_TALK   => $wgMetaNamespace . "_diskutim",
	NS_IMAGE          => "Figura",
	NS_IMAGE_TALK     => "Figura_diskutim",
	NS_MEDIAWIKI      => "MediaWiki",
	NS_MEDIAWIKI_TALK => "MediaWiki_diskutim",
	NS_TEMPLATE       => "Stampa",
	NS_TEMPLATE_TALK  => "Stampa_diskutim",
	NS_HELP           => 'Ndihmë',
	NS_HELP_TALK      => 'Ndihmë_diskutim'
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsSq = array(
	"Asgjë", "Lidhur majtas", "Lidhur djathtas", "Fluturo majtas"
);

/* private */ $wgSkinNamesSq = array(
	'standard' => "Standarte",
	'nostalgia' => "Nostalgjike",
	'cologneblue' => "Kolonjë Blu"
) + $wgSkinNamesEn;


/* private */ $wgDateFormatsSq = array(
#	"Pa preferencë",
);

$wgAllMessagesSq = array(
'1movedto2' => '$1 u zhvendos tek $2',
'1movedto2_redir' => '$1 u zhvendos tek $2 nëpërmjet përcjellimit',
'about' => 'Rreth',
'aboutpage' => '{{ns:project}}:Rreth',
'aboutsite' => 'Rreth {{SITENAME}}-s',
'accmailtext' => 'Fjalëkalimi për \'$1\' u dërgua tek $2.',
'accmailtitle' => 'Fjalëkalimi u dërgua.',
'acct_creation_throttle_hit' => 'Më vjen keq, por keni hapur $1 llogari dhe nuk mund të hapni më shumë.',
'actioncomplete' => 'Veprim i mbaruar',
'addedwatch' => 'U shtua tek lista mbikqyrëse',
'addedwatchtext' => 'Faqja "$1" është shtuar [[Speciale:Watchlist|listës mbikqyrëse]] tuaj. Ndryshimet e ardhshme të kësaj faqeje dhe faqes së diskutimit të saj do të jepen më poshtë, dhe emri i faqes do të duket i \'\'\'trashë\'\'\' në [[Speciale:Recentchanges|listën e ndryshimeve së fundmi]] për t\'i dalluar më kollaj.

Në qoftë se dëshironi të hiqni një faqe nga lista mbikqyrëse më vonë, shtypni "ç\'mbikqyre" në tabelën e sipërme.',
'addgroup' => 'Shtoni një grup',
'administrators' => '{{ns:project}}:Administruesit',
'allarticles' => 'Të gjithë artikujt',
'allinnamespace' => 'Të gjitha faqet (hapësira $1)',
'alllogstext' => 'Kjo faqe tregon një pamje të përmbledhur të rregjistrave të ngarkimeve, grisjeve, mbrojtjeve, bllokimeve, dhe të veprimeve administrative. Mundeni të kufizoni informactionin sipas tipit të rregjistrit, emrit të përdoruesit, si dhe faqes në çështje.',
'allmessages' => 'Të gjitha mesazhet e sistemit',
'allmessagescurrent' => 'Teksti i tanishëshm',
'allmessagesdefault' => 'Teksti i parazgjedhur',
'allmessagesname' => 'Emri',
'allmessagesnotsupportedUI' => 'Ndërfaqja gjuhësore e juaj, <b>$1</b>, nuk mbulohet nga Special:AllMessages në këto faqe.',
'allmessagestext' => 'Kjo është një listë e të gjitha faqeve në hapësirën MediaWiki:',
'allnonarticles' => 'Të gjitha faqet që s\'janë artikuj',
'allnotinnamespace' => 'Të gjitha faqet (jo në hapësirën $1)',
'allpages' => 'Të gjitha faqet',
'allpagesfrom' => 'Trego faqet duke filluar nga:',
'allpagesnext' => 'Më pas',
'allpagesprev' => 'Më para',
'allpagessubmit' => 'Shko',
'alphaindexline' => '$1 deri në $2',
'already_bureaucrat' => 'Ky përdorues është bërë burokrat më parë',
'already_sysop' => 'Ky përdorues është bërë administrues më parë',
'alreadyloggedin' => '<strong>Përdorues $1, keni hyrë brënda më parë!</strong><br />',
'alreadyrolled' => 'Nuk munda ta rithej redaktimin e fundit e [[$1]] nga [[Përdoruesi:$2|$2]] ([[Përdoruesi diskutim:$2|Diskutim]]); dikush tjetër e ka redaktuar ose rikthyer këtë faqe.

Redaktimi i fundit është bërë nga [[Përdoruesi:$3|$3]] ([[Përdoruesi diskutim:$3|Diskutim]]).',
'ancientpages' => 'Artikuj më të vjetër',
'and' => 'dhe',
'anontalk' => 'Diskutimet për këtë IP',
'anontalkpagetext' => '---- \'\'Kjo është një faqe diskutimi për një përdorues anonim i cili nuk ka hapur akoma një llogari ose nuk e përdor atë. Prandaj, më duhet të përdor numrin e adresës [[IP adresë|IP]] për ta identifikuar. Kjo adresë mund të përdoret nga disa njerëz. Në qoftë se jeni një përdorues anonim dhe mendoni se komente kot janë drejtuar ndaj jush, ju lutem [[Speciale:Userlogin|krijoni një llogari ose hyni brënda]] për të mos u ngatarruar me përdorues të tjerë anonim.\'\'',
'anonymous' => 'Përdorues anonim të {{SITENAME}}',
'apr' => 'Pri',
'april' => 'Prill',
'article' => 'Artikulli',
'articleexists' => 'Një faqe me atë titull ekziston, ose titulli që zgjodhët nuk është i saktë. Ju lutem zgjidhni një tjetër.',
'articlepage' => 'Shikoni artikullin',
'aug' => 'Gus',
'august' => 'Gusht',
'autoblocker' => 'I bllokuar automatikisht sepse përdor të njëjtën IP adresë si "$1". Arsye "$2".',
'badaccess' => 'Gabim leje',
'badarticleerror' => 'Ky veprim nuk mund të bëhet në këtë faqe.',
'badfilename' => 'Emri i skedarit është ndërruar në "$1".',
'badfiletype' => '".$1" nuk rekomandohet si tip skedari.',
'badipaddress' => 'Nuk ka asnjë përdorues me atë emër',
'badquery' => 'Pyetje kërkese e formuluar gabim',
'badquerytext' => 'Nuk munda t\'i përgjigjem pyetjes tuaj. Kjo ka mundësi të ketë ndodhur ngaqë provuat të kërkoni për një
fjalë me më pak se tre gërma, gjë që s\'mund të behet akoma. Ka mundësi që edhe të keni shtypur keq pyetjen, për
shëmbull "peshku dhe dhe halat". Provoni një pyetje tjetër.',
'badretype' => 'Fjalëkalimet nuk janë njësoj.',
'badtitle' => 'Titull i pasaktë',
'badtitletext' => 'Titulli i faqes që kërkuat nuk ishte i saktë, ishte bosh, ose ishte një lidhje gabim me një titull wiki internacional.',
'blanknamespace' => '(Kryesore)',
'blockedtext' => 'Emri juaj ose adresa e IP-së është bllokuar nga $1.
Arsyeja e dhënë është kjo:<br />\'\'$2\'\'<p>Mund të kontaktoni $1 ose një nga
[[{{ns:project}}:Administruesit|administruesit]] e tjerë për të diskutuar bllokimin.

Vini re se nuk mund të përdorni "dërgoji email këtij përdoruesi" n.q.s. nuk keni një adresë të saktë
të rregjistruar në [[Speciale:Preferences|parapëlqimet e përdoruesit]].

Adresa e IP-së që keni është $3. Jepni këtë adresë në çdo ankesë.

==Shënim për përdoruesit e AOL-it==
Për shkak të vandalizmeve të një përdoruesit të AOL-it, {{SITENAME}} shpesh bllokon AOL ndërmjetse. Për fat të keq, një ndërmjetse shërbyese mund të jetë duke u përdorur nga një numër i madh njerëzish, prandaj shpesh disa përdorues të pafajshëm të AOL-it bllokohen. Ju kërkoj ndjesë për çdo problem që ka ndodhur.

Në qoftë se kjo ju ndodh, ju lutem njoftoni një administrues duke përdorur një adresë AOL-i. Gjithashtu dërgoni edhe adresën e IP-së të dhënë mësipër.',
'blockedtitle' => 'Përdoruesi është bllokuar',
'blockip' => 'Blloko përdoruesin',
'blockipsuccesssub' => 'Bllokimi u bë me sukses',
'blockipsuccesstext' => '"$1" është bllokuar. <br />Shikoni [[Speciale:Ipblocklist|IP blloko listë]] për bllokimet.',
'blockiptext' => 'Përdorni formularin e mëposhtëm për të hequr lejen e shkrimit për një përdorues ose IP-ë specifike.
Kjo duhet bërë vetëm në raste vandalizmi, dhe në përputhje me [[{{ns:project}}:Rregullat|rregullat e {{SITENAME}}-s]].
Plotësoni arsyen specifike më poshtë (p.sh., tregoni faqet specifike që u vandalizuan).

Afati është sipas standardit GNU (http://www.gnu.org/software/tar/manual/html_chapter/tar_7.html), p.sh. "1 hour", "2 days", "next Wednesday", "1 January 2017", ose për ndryshe "indefinite" ose "infinite".',
'blocklink' => 'blloko',
'blocklistline' => '$1, $2 bllokoi $3 ($4)',
'blocklogentry' => 'bllokoi "$1"',
'blocklogpage' => 'Rregjistri bllokimeve',
'blocklogtext' => 'Ky është një rregjistër bllokimesh dhe ç\'bllokimesh të përdoruesve. IP-të e bllokuara automatikisht nuk janë të dhëna. Shikoni dhe [[Speciale:Ipblocklist|listën e IP-ve të bllokuara]] për një listë të bllokimeve të tanishme.',
'bold_sample' => 'Tekst i trashë',
'bold_tip' => 'Tekst i trashë',
'booksources' => 'Burime librash',
'booksourcetext' => 'Më poshtë është një listë me lidhje tek hapësira të tjera që shesin libra të rinj dhe të përdorur, dhe mund të kenë më shumë informacion për librat që po kerkon. {{SITENAME}} nuk ka mardhënie me asnjë nga këto biznese, dhe
kjo listë nuk duhet të shikohet si një rreklamë.',
'brokenredirects' => 'Përcjellime të prishura',
'brokenredirectstext' => 'Përcjellimet që vijojnë lidhen tek një artikull që s\'ekziston.',
'bugreports' => 'Përshtypjet',
'bugreportspage' => '{{ns:project}}:Përshtypjet',
'bureaucratlog' => 'Rregjistri burokratik',
'bureaucratlogentry' => ' i dha $1: titullin $2',
'bydate' => 'datës',
'byname' => 'emrit',
'bysize' => 'madhësisë',
'cachederror' => 'Kjo është një kopje e faqes së kërkuar dhe mund të jetë e vjetër.',
'cancel' => 'Harroji',
'cannotdelete' => 'Nuk munda të gris këtë faqe ose figurë të dhënë. (Ka mundësi të jetë grisur nga dikush tjeter.)',
'cantrollback' => 'Nuk munda ta kthejë redaktimin; redaktori i fundit është i vetmi autor i këtij artikulli.',
'categories' => 'Kategori faqesh',
'categoriespagetext' => 'Ndodhen këto kategori:',
'category' => 'kategori',
'category_header' => 'Artikuj në kategorinë "$1"',
'categoryarticlecount' => 'Ndodhen $1 artikuj nën këtë kategori.',
'categoryarticlecount1' => 'Ndodhet $1 artikull nën këtë kategori.',
'changepassword' => 'Ndërroni fjalëkalimin',
'changes' => 'ndryshime',
'clearyourcache' => '\'\'\'Shënim:\'\'\' Mbasi të ruani parapëlqimet ose mbasi të kryeni ndryshime, duhet të pastroni \'\'cache\'\'-në e shfletuesit tuaj për të parë ndryshimet: \'\'\'Mozilla/Safari/Konqueror\'\'\' shtypni \'\'Ctrl+Shift+Reload\'\' (ose \'\'ctrl+shift+r\'\'), \'\'\'IE\'\'\' \'\'Ctrl+f5\'\', \'\'\'Opera\'\'\': \'\'F5\'\'...',
'columns' => 'Kolona',
'compareselectedversions' => 'Krahasoni versionet e zgjedhura',
'confirm' => 'Konfirmoni',
'confirmdelete' => 'Konfirmoni grisjen',
'confirmdeletetext' => 'Jeni duke grisur \'\'\'përfundimisht\'\'\' një faqe ose një skedar me tër historinë e tij nga rregjistri. Ju lutem konfirmoni që keni ndër mënd ta bëni këtë gjë, që i kuptoni se cilat janë pasojat, dhe që po veproni në përputhje me [[{{ns:project}}:Rregullat]].',
'confirmprotect' => 'Konfirmoni mbrojtjen',
'confirmprotecttext' => 'Dëshironi të mbroni këtë faqe?',
'confirmrecreate' => 'Përdoruesi [[User:$1|$1]] ([[User talk:$1|diskutime]]) grisi këtë artikull mbasi ju filluat ta redaktoni për arsyen:
: \'\'$2\'\'
Ju lutem konfirmoni nëse dëshironi me të vertetë ta ri-krijoni këtë artikull.',
'confirmunprotect' => 'Konfirmoni lirimin',
'confirmunprotecttext' => 'Dëshironi të lironi këtë faqe?',
'contextchars' => 'Sa gërma të tregohen për çdo rresht',
'contextlines' => 'Sa rreshta të tregohen për përputhje',
'contribslink' => 'kontribute',
'contribsub' => 'Për $1',
'contributions' => 'Redaktimet e përdoruesit',
'copyright' => 'Përmbajtja është në disponim nëpërmjet liçensës $1.',
'copyrightpage' => '{{ns:project}}:Të drejta autori',
'copyrightpagename' => '{{SITENAME}} Të drejta autori',
'copyrightwarning' => 'Ju lutem vini re që të gjitha kontributet tek {{SITENAME}} janë të konsideruara të dhëna nën liçensën GNU Free Documentation License (shikoni $1 për detaje). Në qoftë se nuk dëshironi që kontributet tuaja të redaktohen pa mëshirë dhe të jepen kudo, atëherë mos i jepni këtu.

Gjithashtu, po premtoni që i keni shkruar vetë këto, ose i keni kopjuar nga një faqe nën "public domain" ose nën GFDL.

\'\'\'MOS JEPNI PUNIME QE JANE NEN COPYRIGHT PA PASUR LEJE!\'\'\'',
'couldntremove' => 'S\'mundi të heq arikullin \'$1\'...',
'createaccount' => 'Hap një llogari',
'createaccountmail' => 'me email',
'cur' => 'tani',
'currentevents' => 'Ngjarjet e tanishme',
'currentevents-url' => 'Ngjarjet e tanishme',
'currentrev' => 'Versioni i tanishëm',
'currentrevisionlink' => 'shikoni versionin e tanishëm',
'databaseerror' => 'Gabim rregjistri',
'dateformat' => 'Pamja e datës',
'dberrortext' => 'Ka ndodhur një gabim me pyetjen e rregjistrit. Kjo mund të ndodhi n.q.s. pyetja nuk është e vlehshme (shikoni $5),
ose mund të jetë një yçkël e softuerit. Pyetja e fundit që i keni bërë rregjistrit ishte:
<blockquote><tt>$1</tt></blockquote>
nga funksioni "<tt>$2</tt>".
MySQL kthehu gabimin "<tt>$3: $4</tt>".',
'dberrortextcl' => 'Ka ndodhur një gabim me formatin e pyetjes së rregjistrit. Pyetja e fundit qe i keni bërë rregjistrit ishte:
"$1"
nga funksioni "$2".
MySQL kthehu gabimin "$3: $4".',
'deadendpages' => 'Faqe pa rrugë-dalje',
'debug' => 'Raporto yçkla',
'dec' => 'Dhj',
'december' => 'Dhjetor',
'default' => 'parazgjedhje',
'defaultns' => 'Kërko automatikisht vetëm në këto hapësira:',
'delete' => 'grise',
'deletecomment' => 'Arsyeja për grisjen',
'deletedarticle' => 'grisi "$1"',
'deletedrevision' => 'Gris versionin e vjetër $1.',
'deletedtext' => '"$1" është grisur nga rregjistri. Shikoni $2 për një rekord të grisjeve së fundmi.',
'deleteimg' => 'gris',
'deleteimgcompletely' => 'gris',
'deletepage' => 'Grise faqen',
'deletesub' => '(Duke grisur "$1")',
'deletethispage' => 'Grise faqen',
'deletionlog' => 'grisje rekordesh',
'dellogpage' => 'Rregjistri grisjeve',
'dellogpagetext' => 'Më poshtë është një listë e grisjeve më të fundit.
Të gjitha kohët janë sipas orës së shërbyesit (UTC).
<ul>
</ul>',
'destfilename' => 'Emri mbas dhënies',
'developertext' => 'Veprimi që kërkuat mund bëhet vetëm nga një përdorues me titullin "zhvillues". Shikoni $1.',
'developertitle' => 'Nevojitet titulli "zhvillues"',
'diff' => 'ndrysh',
'difference' => '(Ndryshime midis versioneve)',
'disambiguations' => 'Faqe qartësuese',
'disambiguationspage' => '{{ns:project}}:Lidhje_tek_faqe_qartësuese',
'disambiguationstext' => 'Artikujt që vijojnë lidhen tek një <i>faqe qartësuese</i>. Ato duhet të lidhen tek tema e përshtatshme

<br />Një faqe trajtohet si qartësuese lidhet nga $1.<br />Lidhje nga hapësira të tjera <i>nuk</i> jepen këtu.',
'disclaimerpage' => '{{ns:project}}:Shpërnjohjet_e_përgjithshme',
'disclaimers' => 'Shpërnjohjet',
'doubleredirects' => 'Përcjellime dopjo',
'doubleredirectstext' => '<b>Kujdes:</b> Kjo listë mund të ketë lidhje gabim. D.m.th. ka tekst dhe lidhje mbas #REDIRECT-it të parë.

<br />
Çdo rresht ka lidhje tek përcjellimi i parë dhe i dytë, gjithashtu ka edhe rreshtin e parë të tekstit të përcjellimit të dytë, duke dhënë dhe artikullin e "vërtetë", me të cilin përcjellimi i parë duhet të lidhet.',
'edit' => 'Redaktoni',
'editcomment' => 'Komenti i redaktimit ishte: "<i>$1</i>".',
'editconflict' => 'Konflikt redaktimi: $1',
'editcurrent' => 'Redaktoni versionin e tanishëm të kësaj faqeje',
'editgroup' => 'Grupi Redaktimeve',
'edithelp' => 'Ndihmë për redaktim',
'edithelppage' => '{{ns:project}}:Si_redaktohet_një_faqe',
'editing' => 'Duke redaktuar $1',
'editingcomment' => 'Duke redaktuar $1 (koment)',
'editingold' => '<strong>KUJDES: Po redaktoni një version të vjetër të kësaj faqeje. Në qoftë se e ruani, çdo ndryshim i bërë deri tani do të humbet.</strong>',
'editingsection' => 'Duke redaktuar $1 (seksion)',
'editsection' => 'redaktoni',
'editthispage' => 'Redaktoni faqen',
'editusergroup' => 'Redaktoni Grupet e Përdoruesve',
'emailforlost' => '* Futja e email-it nuk është e detyrueshme. Por lejon përdorues të tjerë tu kontaktojnë nëpërmjet faqes pa u treguar adresën, gjithashtu kjo adresë është e dobishme n.q.s. harroni fjalëkalimin',
'emailfrom' => 'Nga',
'emailmessage' => 'Mesazh',
'emailpage' => 'Dërgo e-mail përdoruesve',
'emailpagetext' => 'Në qoftë se ky përdorues ka dhënë një adresë të saktë në parapëlqimet, formulari më poshtë do t\'i dërgojë një mesazh.

Adresa e email-it që keni dhënë në parapëlqimet do të duket si pjesa "From" e adresës së mesazhit, kështuqë marrësi do të ketë mundësi tu përgjigjet.',
'emailsend' => 'Dërgo',
'emailsent' => 'Email-i u dërgua',
'emailsenttext' => 'Mesazhi e-mail është dërguar.',
'emailsubject' => 'Subjekt',
'emailto' => 'Për',
'emailuser' => 'Dërgoji e-mail këtij përdoruesi',
'emptyfile' => 'Skedari që keni dhënë është bosh ose mbi madhësinë e lejushme. Kjo gjë mund të ndodhi nëse shtypni emrin gabim, prandaj kontrolloni nëse dëshironi të jepni skedarin me këtë emër.',
'enterlockreason' => 'Fusni një arsye për bllokimin, gjithashtu fusni edhe kohën se kur pritet të ç\'bllokohet',
'error' => 'Gabim',
'errorpagetitle' => 'Gabim',
'exbeforeblank' => 'përmbajtja përpara boshatisjes ishte: \'$1\'',
'exblank' => 'faqja është bosh',
'excontent' => 'përmbajtja ishte: \'$1\'',
'excontentauthor' => 'përmbajtja ishte: \'$1\' (dhe i vetmi redaktor ishte \'$2\')',
'explainconflict' => 'Dikush tjetër ka ndryshuar këtë faqe kur ju po e redaktonit. Kutija e redaktimit mësipër tregon tekstin e faqes siç ekziston tani. Nryshimet tuaja janë treguar poshtë kutisë së redaktimit. Ju duhet të përputhni ndryshimet tuaja me tekstin ekzistues. <b>Vetëm</b> teksti në kutinë e sipërme të redaktimit do të ruhet kur të shtypni "Ruaje faqen".
<p>',
'export' => 'Eksportoni faqe',
'exportcuronly' => 'Përfshi vetëm versionin e fundit, jo të gjithë historinë',
'exporttext' => 'Mund të eksportoni tekstin dhe historinë e redaktimit e një faqeje ose disa faqesh të mbështjesha në XML; kjo mund të importohet në një wiki tjetër që përdor softuerin MediaWiki (tani për tani, ky opsion nuk është përfshirë tek {{SITENAME}}).

Për të eksportuar faqe, thjesht shtypni një emër për çdo rresht, ose krijoni lidhje të tipit <nowiki>[[Speciale:Export/Faqja Kryesore]]</nowiki> si [[Speciale:Export/Faqja Kryesore]].',
'extlink_sample' => 'http://www.shëmbull.al Titulli i lidhjes',
'extlink_tip' => 'Lidhje e jashtme (most harro prefiksin http://)',
'faq' => 'Pyetje e Përgjigje',
'faqpage' => '{{ns:project}}:Pyetje_e_Përgjigje',
'feb' => 'Shk',
'february' => 'Shkurt',
'feedlinks' => 'Ushqyes:',
'filecopyerror' => 'Nuk munda të kopjojë skedarin "$1" tek "$2".',
'filedeleteerror' => 'Nuk munda të gris skedarin "$1".',
'filedesc' => 'Përmbledhje',
'fileexists' => 'Ekziston një skedar me atë emër, ju lutem kontrolloni $1 në qoftë se nuk jeni të sigurt nëse dëshironi ta zëvendësoni.',
'filemissing' => 'Mungon skedari',
'filename' => 'Skedaremër',
'filenotfound' => 'Nuk munda të gjejë skedarin "$1".',
'filerenameerror' => 'Nuk munda të ndërrojë emrin e skedarit "$1" në "$2".',
'files' => 'Skedarë',
'filesource' => 'Burimi',
'filestatus' => 'Gjëndja e të drejtave të autorit',
'fileuploaded' => 'Skedari "$1" u mor me sukses. Ju lutem ndiqni këtë lidhje : ($2) për të shkuar tek faqja e përshkrimit dhe për të futur
informacion për skedarin, si p.sh. ku e gjetët, kur u bë, kush e bëri, dhe çdo gjë tjetër që na duhet të dimë për të.',
'fileuploadsummary' => 'Përshkrimi:',
'formerror' => 'Gabim: nuk munda të dërgoj formularin',
'friday' => 'E Premte',
'getimagelist' => 'duke ngarkuar të gjithë listën e figurave',
'go' => 'Shko',
'googlesearch' => '',
'groups-editgroup-name' => 'Grupi:',
'guesstimezone' => 'Gjeje nga shfletuesi',
'headline_sample' => 'Titull shëmbull',
'headline_tip' => 'Titull i nivelit 2',
'help' => 'Ndihmë',
'helppage' => '{{ns:project}}:Ndihmë',
'hide' => 'fshih',
'hidetoc' => 'fshih',
'histfirst' => 'Së pari',
'histlast' => 'Së fundmi',
'histlegend' => 'Legjenda: (tani) = ndryshimet me versionin e tanishëm,
(fund) = ndryshimet me versionin e parardhshëm, V = redaktim i vogël',
'history' => 'Historiku i faqes',
'history_short' => 'Historiku',
'historywarning' => 'Kujdes: Faqja që jeni bërë gati për të grisur ka histori:',
'hr_tip' => 'vijë horizontale (përdoreni rallë)',
'illegalfilename' => 'Skedari "$1" përmban gërma që nuk lejohen tek titujt e faqeve. Ju lutem ndërrojani emrin dhe provoni ta ngarkoni përsëri.',
'ilsubmit' => 'Kërko',
'image_sample' => 'Shëmbull.jpg',
'image_tip' => 'Vendos një figurë',
'imagelinks' => 'Lidhje skedarësh',
'imagelist' => 'Lista e figurave',
'imagelistall' => 'të gjitha',
'imagelisttext' => 'Më poshtë është një listë e $1 figurave të renditura sipas $2.',
'imagemaxsize' => 'Kufizo pamjen e figurave në faqet përshkruese në rezolucionin:',
'imagepage' => 'Shikoni faqen e figurës',
'imagereverted' => 'Kthimi tek një version i sukseshëm.',
'imgdelete' => 'gris',
'imgdesc' => 'për',
'imghistlegend' => 'Legjendë: (tani) = ky është skedari i tanishëm, (gris) = grise
këtë version të vjetër, (ktheje) = ktheje në këtë version të vjetër.
<br /><i>Shtyp datën për të parë skedarin e dhënë në atë ditë</i>.',
'imghistory' => 'Historia e skedarit',
'imglegend' => 'Legjendë: (për) = trego/redakto përshkrimin e skedarit.',
'import' => 'Importoni faqe',
'importfailed' => 'Importimi dështoi: $1',
'importhistoryconflict' => 'Ekzistojnë versione historiku në konflikt (kjo faqe mund të jetë importuar më parë)',
'importnosources' => 'Nuk ka asnjë burim importi të përcaktuar dhe ngarkimet historike të drejtpërdrejta janë ndaluar.',
'importnotext' => 'Bosh ose pa tekst',
'importsuccess' => 'Importim i sukseshëm!',
'importtext' => 'Ju lutem eksportoni këtë skedar nga burimi wiki duke përdorur mjetin Special:Export, ruajeni në dikun tuaj dhe ngarkojeni këtu.',
'info_short' => 'Informacion',
'infosubtitle' => 'Informacion për faqen',
'internalerror' => 'Gabim i brëndshëm',
'intl' => 'Gjuhë-lidhje',
'invert' => 'Anasjelltas zgjedhjes',
'ip_range_invalid' => 'Shtrirje IP gabim.',
'ipaddress' => 'IP Adresë/përdorues',
'ipb_expiry_invalid' => 'Afati i kohës është gabim.',
'ipbexpiry' => 'Afati',
'ipblocklist' => 'Lista e përdoruesve dhe e IP adresave të bllokuara',
'ipbreason' => 'Arsye',
'ipbsubmit' => 'Blloko këtë përdorues',
'ipusubmit' => 'Ç\'blloko këtë adresë',
'ipusuccess' => '"$1" u ç\'bllokua',
'isredirect' => 'faqe përcjellëse',
'italic_sample' => 'Tekst i pjerrët',
'italic_tip' => 'Tekst i pjerrët',
'iteminvalidname' => 'Problem me artikullin \'$1\', titull jo i saktë...',
'january' => 'Janar',
'jul' => 'Kor',
'july' => 'Korrik',
'jun' => 'Qer',
'june' => 'Qershor',
'laggedslavemode' => 'Kujdes: Kjo faqe mund të mos jetë përtërirë nga shërbyesi kryesorë dhe mund të ketë informacion të vjetër',
'largefile' => 'Rekomandohet që skedarët të most kalojnë 100KB në madhësi.',
'last' => 'fund',
'lastmodified' => 'Kjo faqe është ndryshuar për herë te fundit më $1.',
'lastmodifiedby' => 'Kjo faqe është redaktuar së fundit më $1 nga $2.',
'license' => 'Liçensimi',
'lineno' => 'Rreshti $1:',
'link_sample' => 'Titulli i lidhjes',
'link_tip' => 'Lidhje e brëndshme',
'linklistsub' => '(Listë lidhjesh)',
'linkshere' => 'Faqet e mëposhtëme lidhen këtu:',
'linkstoimage' => 'Këto faqe lidhen tek ky skedar:',
'listform' => 'listë',
'listingcontinuesabbrev' => ' vazh.',
'listusers' => 'Lista e përdoruesve',
'loadhist' => 'Duke ngarkuar historinë e faqes',
'loadingrev' => 'duke ngarkuar versionin për ndryshimin',
'localtime' => 'Tregimi i orës lokale',
'lockbtn' => 'Blloko rregjistrin',
'lockconfirm' => 'Po, dëshiroj me të vërtetë të bllokoj rregjistrin.',
'lockdb' => 'Blloko rregjistrin',
'lockdbsuccesssub' => 'Rregjistri u bllokua me sukses',
'lockdbsuccesstext' => 'Rregjistri i {{SITENAME}} është bllokuar.
<br />Kujtohu ta ç\'bllokosh mbasi të kesh mbaruar mirëmbajtjen.',
'lockdbtext' => 'Bllokimi i rregjistrit do të ndërpresi mundësinë e përdoruesve për të redaktuar faqet, për të ndryshuar parapëlqimet, për të ndryshuar listat mbikqyrëse të tyre, dhe për gjëra të tjera për të cilat nevojiten shkrime në rregjistër.
Ju lutem konfirmoni që dëshironi me të vërtetë të kryeni këtë veprim, dhe se do të ç\'bllokoni rregjistrin
kur të mbaroni së kryeri mirëmbajtjen.',
'locknoconfirm' => 'Nuk vendose kryqin tek kutia konfirmuese.',
'log' => 'Rregjistra',
'login' => 'Hyrje',
'loginend' => '<!-- bosh tani per tani -->',
'loginerror' => 'Gabim hyrje',
'loginpagetitle' => 'Hyrje përdoruesi',
'loginproblem' => '<b>Kishte një problem me hyrjen tuaj.</b><br />Provojeni përsëri!',
'loginprompt' => 'Duhet të pranoni "biskota" për të hyrë brënda në {{SITENAME}}.',
'loginreqtitle' => 'Detyrohet hyrja',
'loginsuccess' => 'Keni hyrë brënda në {{SITENAME}} si "$1".',
'loginsuccesstitle' => 'Hyrje me sukses',
'logout' => 'Dalje',
'logouttext' => 'Keni dalë jashtë. Mund të vazhdoni të përdorni {{SITENAME}}-n anonimisht, ose mund të hyni brënda përsëri.',
'logouttitle' => 'Përdoruesi doli',
'lonelypages' => 'Faqe të palidhura',
'longpages' => 'Artikuj të gjatë',
'longpagewarning' => 'KUJDES: Kjo faqe është $1 kilobytes e gjatë; disa
shfletues mund të kenë probleme për të redaktuar faqe që afrohen ose janë akoma më shumë se 32kb.
Konsideroni ta ndani faqen në disa seksione më të vogla.',
'mailerror' => 'Gabim duke dërguar postën: $1',
'mailmypassword' => 'Më dërgo një fjalëkalim të ri tek adresa ime',
'mailnologin' => 'S\'ka adresë dërgimi',
'mailnologintext' => 'Duhet të keni [[{{ns:special}}:Userlogin|hyrë brënda]] dhe të keni një adresë të saktë në [[{{ns:special}}:Preferences|parapëlqimet]] për tu dërguar e-mail përdoruesve të tjerë.',
'mainpage' => 'Faqja Kryesore',
'mainpagetext' => 'Wiki software u instalua me sukses.',
'maintenance' => 'Faqja mirëmbajtëse',
'maintenancebacklink' => 'Mbrapsh tek faqja mirëmbajtëse',
'maintnancepagetext' => 'Kjo faqe ka disa mjete to dobishme për mirëmbajtjen e përditshme. Disa nga këto funksione e përdorin shumë rregjistrin, kështuqë mos e freskoni faqen mbas çdo ndryshimi ;-)',
'makesysop' => 'Jepini një përdoruesit titullin administrues',
'makesysopfail' => '<b>Përdoruesi \'$1\' nuk mund të bëhej administrues. (Kontrolloni nëse emrin e keni shtypur saktësisht)</b>',
'makesysopname' => 'Emri i përdoruesit:',
'makesysopok' => '<b>Përdoruesi \'$1\' u bë administrues</b>',
'makesysopsubmit' => 'Jepini privilegjin',
'makesysoptext' => 'Ky formular përdoret për tu dhënë titullin [[{{ns:project}}:Administruesit|administrues]] një përdoruesi të thjeshtë. Kini kujdes, mbasi të jetë dhënë, vetëm një \'\'zhvillues\'\' mund t\'ia heqi këtë titull një administruesi.',
'makesysoptitle' => 'Jepini privilegjin e titullit administrues',
'march' => 'Mars',
'matchtotals' => 'Pyetja "$1" u përpuq $2 tituj faqesh
dhe teksti i $3 artikujve te pasardhshëm.',
'math' => 'Tregimi i matematikës',
'math_bad_output' => 'Nuk munda të shkruaj ose të krijoj prodhimin matematik në dosjen',
'math_bad_tmpdir' => 'Nuk munda të shkruaj ose krijoj dosjen e përkohshme për matematikë',
'math_failure' => 'Nuk e kuptoj',
'math_image_error' => 'Konversioni PNG dështoi; kontrolloni për ndonjë gabim instalimi të latex-it, dvips-it, gs-it, dhe convert-it.',
'math_lexing_error' => 'gabim leximi',
'math_notexvc' => 'Mungon zbatuesi texvc; ju lutem shikoni math/README për konfigurimin.',
'math_sample' => 'Vendos formulen ketu',
'math_syntax_error' => 'gabim sintakse',
'math_tip' => 'Formulë matematike (LaTeX)',
'math_unknown_error' => 'gabim i panjohur',
'math_unknown_function' => 'funksion i panjohur ',
'may' => 'Maj',
'may_long' => 'Maj',
'media_sample' => 'Shëmbull.ogg',
'media_tip' => 'Lidhje skedari medie',
'metadata_page' => '{{ns:project}}:Metadata',
'mimesearch' => 'Kërkime MIME',
'mimetype' => 'Lloji MIME:',
'minlength' => 'Emrat e skedarëve duhet të kenë të paktën tre gërma.',
'minoredit' => 'Ky është një redaktim i vogël',
'minoreditletter' => 'v',
'mispeelings' => 'Faqe me gabime gramatikore',
'mispeelingspage' => 'Lista e gabimeve më të shpeshta të shkrimit',
'mispeelingstext' => 'Faqet që vijojnë kanë një gabim shkrimi që ndodh shpesh, të cilat jepen në $1. Shkrimi i vërtetë mund të jetë dhënë (si kështu).',
'missingarticle' => 'Rregjistri nuk e gjeti tekstin e faqes që duhet të kishte gjetur, të quajtur "$1".

<p>Kjo ndodh zakonisht kur ndjek një ndryshim ose lidhje historie tek një
faqe që është grisur.

<p>Në qoftë se ky nuk është rasti, atëherë mund të keni gjetur një yçkël në softuerin.
Tregojani këtë përmbledhje një administruesi, duke shënuar edhe URL-in.',
'missingimage' => '<b>Mungon figura</b><br /><i>$1</i>',
'missinglanguagelinks' => 'Mungojnë gjuhë-lidhjet',
'missinglanguagelinksbutton' => 'Gjej gjuhë-lidhjet që mungojnë për',
'missinglanguagelinkstext' => 'Këto artikuj <i>nuk</i> lidhen tek faqja korresponduese në $1. Përcjellime dhe nën-faqet <i>nuk</i> janë treguar.',
'monday' => 'E Hënë',
'moredotdotdot' => 'Më shumë...',
'mostlinked' => 'Faqet më të lidhura',
'move' => 'Zhvendose',
'movearticle' => 'Zhvendose faqen',
'movedto' => 'zhvendosur tek',
'movelogpage' => 'Rregjistri zhvendosjeve',
'movelogpagetext' => 'Më poshtë është një listë e faqeve të zhvendosura',
'movenologin' => 'Nuk keni hyrë brënda',
'movenologintext' => 'Duhet të keni hapur një llogari dhe të keni <a href="/wiki/Speciale:Userlogin">hyrë brënda</a> për të zhvendosur një faqe.',
'movepage' => 'Zhvendose faqen',
'movepagebtn' => 'Zhvendose faqen',
'movepagetalktext' => 'Faqja a bashkangjitur e diskutimit, n.q.s. ekziston, do të zhvendoset automatikisht \'\'\'përveçse\'\'\' kur:
*Zhvendosni një faqe midis hapësirave të ndryshme,
*Një faqe diskutimi jo-boshe ekziston nën titullin e ri, ose
*Nuk zgjidhni kutinë më poshtë.

Në ato raste, duhet ta zhvendosni ose përpuqni faqen vetë n.q.s. dëshironi.',
'movepagetext' => 'Duke përdorur formularin e mëposhtëm do të ndërroni titullin e një faqeje, duke zhvendosur gjithë historinë përkatëse tek titulli i ri. Titulli i vjetër do të bëhet një faqe përcjellëse tek titulli i ri. Lidhjet tek faqja e vjetër nuk do të ndryshohen; duhet të kontrolloni [[Speciale:Maintenance|mirëmbajtjen]] për përcjellime të dyfishta ose të prishura.
Keni përgjegjësinë për tu siguruar që lidhjet të vazhdojnë të jenë të sakta.

Vini re se kjo faqe \'\'\'nuk\'\'\' do të zhvendoset n.q.s. ekziston një faqe me titullin e ri, përveçse kur ajo të jetë bosh ose një përcjellim dhe të mos ketë një histori të vjetër. Kjo do të thotë se mund ta zhvendosni një faqe prapë tek emri
i vjetër n.q.s. keni bërë një gabim, dhe s\'mund ta prishësh një faqe që ekziston.

<b>KUJDES!</b>
Ky mund të jetë një ndryshim i madh dhe gjëra të papritura mund të ndodhin për një faqe të shumë-frekuentuar; ju lutem, kini kujdes dhe mendohuni mirë para se të përdorni këtë funksion.',
'movetalk' => 'Zhvendos edhe faqen e diskutimeve, në qoftë se është e mundur.',
'movethispage' => 'Zhvendose faqen',
'mw_math_html' => 'HTML në qoftë se është e mundur ose ndryshe PNG',
'mw_math_mathml' => 'MathML',
'mw_math_modern' => 'E rekomanduar për shfletuesit modern',
'mw_math_png' => 'Gjithmonë PNG',
'mw_math_simple' => 'HTML në qoftë se është e thjeshtë ose ndryshe PNG',
'mw_math_source' => 'Lëre si TeX (për shfletuesit tekst)',
'mycontris' => 'Redaktimet e mia',
'mypage' => 'Faqja ime',
'mytalk' => 'Diskutimet e mia',
'namespace' => 'Hapësira:',
'navigation' => 'Shfleto',
'nchanges' => '$1 ndryshime',
'newarticle' => '(I Ri)',
'newarticletext' => '<div style="border: 1px solid #ccc; padding: 7px;">{{SITENAME}} nuk ka akoma një \'\'{{NAMESPACE}} faqe\'\' të quajtur \'\'\'{{PAGENAME}}\'\'\'. Shtypni \'\'\'redaktoni\'\'\' më sipër ose [[Speciale:Search/{{PAGENAME}}|bëni një kërkim për {{PAGENAME}}]]
</div>',
'newbies' => 'të njomtët',
'newimages' => 'Galeria e figurave të reja',
'newmessageslink' => 'mesazhe të reja',
'newpage' => 'Faqe e re',
'newpageletter' => 'R',
'newpages' => 'Artikuj të rinj',
'newpassword' => 'Fjalëkalimi i ri',
'newtitle' => 'Tek titulli i ri',
'newusersonly' => ' (përdoruesit e rinj vetëm)',
'newwindow' => '(hapet në një dritare të re)',
'next' => 'mbas',
'nextdiff' => 'Ndryshimi më pas →',
'nextn' => '$1 më pas',
'nextpage' => 'Faqja e mëpasme ($1)',
'nextrevision' => 'Version më i ri →',
'nlinks' => '$1 lidhje',
'noarticletext' => '(Tani për tani, nuk ka tekst në këtë faqe)',
'noconnect' => 'Ju kërkoj ndjesë! Difekt teknik, rifilloj së shpejti.',
'nocontribs' => 'Nuk ka asnjë ndryshim që përputhet me këto kritere.',
'nocookieslogin' => '{{SITENAME}} përdor "biskota" për të futur brënda përdoruesit. Prandaj, duhet të pranoni biskota dhe të provoni përsëri.',
'nocookiesnew' => 'Llogaria e përdoruesit u hap, por nuk keni hyrë brënda akoma. {{SITENAME}} përdor "biskota" për të futur brënda përdoruesit. Prandaj, duhet të pranoni biskota dhe të provoni përsëri me emrin dhe fjalëkalimin tuaj.',
'nodb' => 'Nuk mund të zgjidhja rregjistrin $1',
'noemail' => 'Rregjistri nuk ka adresë për përdoruesin "$1".',
'noemailtext' => 'Ky përdorues s\'ka dhënë një adresë të saktë,
ose ka vendosur të mos pranojë mesazhe email-i nga përdorues të tjerë.',
'noemailtitle' => 'S\'ka adresë email-i',
'nogomatch' => '<span style="font-size: 135%; font-weight: bold; margin-left: .6em">Faqja me atë titull nuk ështe krijuar akoma</span>

<span style="display: block; margin: 1.5em 2em">
Mund të [[$1|filloni një artikull]] me këtë titull.

<span style="display:block; font-size: 89%; margin-left:.2em">Ju lutem kërkoni {{SITENAME}}-n përpara se të krijoni një artikull të ri se mund të jetë nën një titull tjetër.</span>
</span>',
'nohistory' => 'Nuk ka histori redaktimesh për këtë faqe.',
'noimages' => 'S\'ka gjë për të parë.',
'nolicense' => 'Asnjë nuk është zgjedhur',
'nolinkshere' => 'Asnjë faqe nuk lidhet këtu.',
'nolinkstoimage' => 'Asnjë faqe nuk lidhet tek ky skedar.',
'noname' => 'Nuk keni dhënë një emër të saktë.',
'nonefound' => '<strong>Shënim</strong>: kërkimet pa përfundime ndodhin kur kërkoni për fjalë që rastisen shpesh si "ke" and "nga", të cilat nuk janë të futura në rregjistër, ose duke dhënë më shumë se një fjalë (vetëm faqet që i kanë të gjitha ato fjalë do të tregohen si përfundime).',
'nonunicodebrowser' => '<strong>KUJDES: Shfletuesi juaj nuk përdor dot unikode, ju lutem ndryshoni shfletues para se të redaktoni artikuj.</strong>',
'nospecialpagetext' => 'Keni kërkuar një faqe speciale që nuk njihet nga wiki software.',
'nosuchaction' => 'Nuk ekziston ky veprim',
'nosuchactiontext' => 'Veprimi i caktuar nga URL nuk
njihet nga wiki software',
'nosuchspecialpage' => 'Nuk ekziston kjo faqe',
'nosuchuser' => 'Nuk ka ndonjë përdorues me emrin "$1". Kontrolloni gërmat, ose përdorni formularin e mëposhtëm për të hapur një llogari të re.',
'nosuchusershort' => 'Nuk ka asnjë përdorues me emrin "$1".',
'notanarticle' => 'Nuk është artikull',
'notargettext' => 'Nuk keni dhënë asnjë artikull ose përdorues mbi të cilin të përdor këtë funksion.',
'notargettitle' => 'Asnjë artikull',
'note' => '<strong>Shënim:</strong> ',
'notextmatches' => 'Nuk ka asnjë tekst faqeje që përputhet',
'notitlematches' => 'Nuk ka asnjë titull faqeje që përputhet',
'notloggedin' => 'Nuk keni hyrë brënda',
'nov' => 'Nën',
'november' => 'Nëntor',
'nowatchlist' => 'Nuk keni asnjë faqe në listën mbikqyrëse.',
'nowiki_sample' => 'Vendos tekst që nuk duhet të formatohet',
'nowiki_tip' => 'Mos përdor format wiki',
'nstab-category' => 'Kategori',
'nstab-help' => 'Ndihmë',
'nstab-image' => 'Figura',
'nstab-main' => 'Artikulli',
'nstab-mediawiki' => 'Mesazhi',
'nstab-special' => 'Speciale',
'nstab-template' => 'Stampa',
'nstab-user' => 'Përdoruesi',
'nstab-wp' => 'Projekt-faqe',
'nviews' => '$1 shikime',
'oct' => 'Tet',
'october' => 'Tetor',
'ok' => 'Shkoni',
'oldpassword' => 'Fjalëkalimi i vjetër',
'orig' => 'parë',
'orphans' => 'Faqe të palidhura',
'otherlanguages' => 'Në gjuhë të tjera',
'others' => 'të tjerë',
'pagemovedsub' => 'Zhvendosja doli me sukses',
'pagemovedtext' => 'Faqja "[[$1]]" u zhvendos tek "[[$2]]".',
'passwordremindertext' => 'Dikush (ndoshta ju, nga IP adresa $1) kërkojë që të dërgoj një fjalëkalim hyrje të ri për {{SITENAME}}-n. Fjalëkalimi për përdoruesin "$2" tani është "$3". Duhet të hyni përsëri dhe të ndërroni fjalëkalimin tuaj menjëherë. Në qoftë se nuk e përdorni këtë fjalëkalim të ri, atëherë do të vazhdojë të përdoret ai i vjetri.',
'passwordremindertitle' => 'Kujtim për fjalëkalimin nga {{SITENAME}}',
'passwordsent' => 'Një fjalëkalim i ri është dërguar tek adresa e rregjistruar për "$1".
Hyni përsëri mbasi ta kesh marrë.',
'perfdisabled' => 'Ju kërkoj ndjesë! Ky veprim është bllokuar përkohsisht sepse e ngadalëson rregjistrin aq shumë sa nuk e përdor dot njeri tjetër.',
'perfdisabledsub' => 'Kjo është nje kopje e ruajtur nga $1:',
'permalink' => 'Lidhje e përhershme',
'personaltools' => 'Mjete vetjake',
'popularpages' => 'Artikuj te frekuentuar shpesh',
'portal' => 'Wikiportal',
'portal-url' => 'Project:Wikiportal',
'postcomment' => 'Bëni koment',
'powersearch' => 'Kërko',
'powersearchtext' => 'Kërko në hapësirën:<br />
$1<br />
$2 Lidhje përcjellëse &nbsp; Kërko për $3 $9',
'preferences' => 'Parapëlqimet',
'prefixindex' => 'Treguesi i parashtesave',
'prefs-misc' => 'Të ndryshme',
'prefs-personal' => 'Të dhëna përdoruesi',
'prefs-rc' => 'Rreth ndryshimeve së fundmi dhe cungjeve',
'prefsnologin' => 'Nuk keni hyrë brënda',
'prefsnologintext' => 'Duhet të keni <a href="/wiki/Speciale:Userlogin">hyrë brënda</a> për të ndryshuar parapëlqimet e përdoruesit.',
'prefsreset' => 'Parapëlqimet janë rikthyer siç ishin.',
'preview' => 'Parapamje',
'previewconflict' => 'Kjo parapamje reflekton tekstin sipër kutisë së redaktimit siç do të duket kur të kryeni ndryshimin.',
'previewnote' => 'Kini kujdes se kjo është vetëm një parapamje, nuk është ruajtur akoma!',
'previousdiff' => '← Ndryshimi më para',
'previousrevision' => '← Version më i vjetër',
'prevn' => '$1 më para',
'printableversion' => 'Versioni i shtypshëm',
'printsubtitle' => '(Nga {{SERVER}})',
'protect' => 'Mbroje',
'protectcomment' => 'Arsyeja për mbrojtjen',
'protectedarticle' => 'mbrojti [[$1]]',
'protectedpage' => 'Faqe e mbrojtur',
'protectedpagewarning' => 'KUJDES: Kjo faqe është bllokuar kështuqë vetëm përdorues me titullin sysop mund ta redaktojnë. Ndiqni rregullat e dhëna tek [[{{ns:project}}:Rregullat për faqe të bllokuara|faqet e bllokuara]].',
'protectedtext' => 'Kjo faqe është e mbrojtur që të mos redaktohet; mund të ketë
disa arsye përse kjo është bërë, ju lutem shikoni
[[{{ns:project}}:Faqe e mbrojtur]].

Mund të shikoni dhe kopjoni tekstin e kësaj faqeje:',
'protectlogpage' => 'Rregjistri mbrojtjeve',
'protectlogtext' => 'Më poshtë është një listë e "mbrojtjeve/lirimeve" të faqeve. Shikoni [[{{ns:project}}:Faqe e mbrojtur]] për më shumë informacion.',
'protectmoveonly' => 'Mbroje vetëm nga zhvendosjet',
'protectpage' => 'Mbroje faqen',
'protectsub' => '(Duke mbrojtur "$1")',
'protectthispage' => 'Mbroje faqen',
'proxyblocker' => 'Bllokuesi i ndërmjetëseve',
'proxyblockreason' => 'IP adresa juaj është bllokuar sepse është një ndërmjetëse e hapur. Ju lutem lidhuni me kompaninë e shërbimeve të Internetit që përdorni dhe i informoni për këtë problem sigurije.',
'proxyblocksuccess' => 'Mbaruar.',
'qbbrowse' => 'Shfletoni',
'qbedit' => 'Redaktoni',
'qbfind' => 'Kërko',
'qbmyoptions' => 'Opsionet e mia',
'qbpageinfo' => 'Informacion mbi faqen',
'qbpageoptions' => 'Opsionet e faqes',
'qbsettings' => 'Vendime të shpejta',
'qbspecialpages' => 'Faqe speciale',
'randompage' => 'Artikull i rastit',
'range_block_disabled' => 'Mundësia e administruesve për të bllokuar me shtrirje është çaktivizuar.',
'rchide' => 'në $4 formë; $1 redaktime të vogla; $2 hapësira të dyta; $3 redaktime të shumta.',
'rclinks' => 'Trego $1 ndryshime gjatë $2 ditëve<br />$3',
'rclistfrom' => 'Trego ndryshime së fundmi duke filluar nga $1',
'rcliu' => '; $1 redaktime nga përdorues të rregjistruar',
'rcloaderr' => 'Duke ngarkuar ndryshime së fundmi',
'rclsub' => '(për faqet e lidhura nga "$1")',
'rcnote' => 'Më poshtë janë <strong>$1</strong> ndryshime së fundmi gjatë <strong>$2</strong> ditëve.',
'rcnotefrom' => 'Më poshtë janë ndryshime së fundmi nga <b>$2</b> (treguar deri në <b>$1</b>).',
'readonly' => 'Rregjistri i bllokuar',
'readonlytext' => 'Rregjistri i {{SITENAME}}-s është i bllokuar dhe nuk lejon redaktime dhe
artikuj të rinj. Ka mundësi të jetë bllokuar për mirëmbajtje,
dhe do të kthehet në gjëndje normale mbas mirëmbajtjes. Mirëmbajtësi i cili e bllokoi dha këtë arsye:
<p>$1',
'readonlywarning' => 'KUJDES: Rregjistri është bllokuar për mirëmbajtje,
kështuqë nuk do keni mundësi të ruani redaktimet e tuaja tani. Mund të kopjoni dhe ruani tekstin në një skedar për më vonë.',
'recentchanges' => 'Ndryshime së fundmi',
'recentchangesall' => 'të gjitha',
'recentchangescount' => 'Numri i titujve në ndryshime së fundmi',
'recentchangeslinked' => 'Ndryshimet fqinje',
'redirectedfrom' => '(Përcjellë nga $1)',
'remembermypassword' => 'Mbaj mënd fjalëkalimin tim për tërë vizitat e ardhshme.',
'removechecked' => 'Hiq të zgjedhurat',
'removedwatch' => 'U hoq nga lista mibkqyrëse',
'removedwatchtext' => 'Faqja "$1" është hequr nga lista mbikqyrëse e juaj.',
'removingchecked' => 'Duke hequr artikujt e zgjedhur nga lista mbikqyrëse...',
'resetprefs' => 'Rikthe parapëlqimet',
'restorelink' => '$1 redaktimet e grisura',
'restorelink1' => 'një redaktim të grisur',
'restrictedpheading' => 'Faqe speciale të kufizuara',
'resultsperpage' => 'Sa përputhje të tregohen për faqe',
'retrievedfrom' => 'Marrë nga "$1"',
'returnto' => 'Kthehu tek $1.',
'retypenew' => 'Rishtypni fjalëkalimin e ri',
'reupload' => 'Jepeni përsëri',
'reuploaddesc' => 'Kthehu tek formulari i dhënies.',
'reverted' => 'Kthehu tek një version i vjetër',
'revertimg' => 'ktheje',
'revertmove' => 'ktheje',
'revertpage' => 'Kthyer tek redaktimi i fundit nga $1',
'revhistory' => 'Historia e redaktimeve',
'revisionasof' => 'Versioni i $1',
'revnotfound' => 'Versioni nuk u gjet',
'revnotfoundtext' => 'Versioni i vjetër i faqes së kërkuar nuk mund të gjehej.Ju lutem kontrolloni URL-in që përdorët për të ardhur tek kjo faqe.',
'rights' => 'Privilegje:',
'rollback' => 'Riktheji mbrapsh redaktimet',
'rollback_short' => 'Riktheje',
'rollbackfailed' => 'Rikthimi dështoi',
'rollbacklink' => 'riktheje',
'rows' => 'Rreshta',
'saturday' => 'E Shtunë',
'savearticle' => 'Kryej ndryshimet',
'savedprefs' => 'Parapëlqimet tuaja janë ruajtur.',
'savefile' => 'Ruaj skedarin',
'saveprefs' => 'Ruaj parapëlqimet',
'search' => 'Kërko',
'searchdisabled' => '<p>Kërkimi me tekst të plotë është bllokuar tani për tani ngaqë shërbyesi është shumë i ngarkuar; shpresojmë ta nxjerrim prapë në gjendje normale pas disa punimeve. Deri atëherë mund të përdorni Google-in për kërkime:</p>',

'searchquery' => 'Për pyetjen "<a href="/wiki/$1">$1</a>" <a href="/wiki/Special:Allpages/$1">[Treguesi]</a>',
'searchresults' => 'Përfundimet e kërkimit',
'searchresultshead' => 'Rreth kërkimit',
'searchresulttext' => '<!--   -->',
'selectnewerversionfordiff' => 'Zgjidhni një version më të ri për krahasim',
'selectolderversionfordiff' => 'Zgjidhni një version më të vjetër për krahasim',
'selflinks' => 'Faqe që lidhen tek vetëvetja',
'selflinkstext' => 'Faqet që vijojnë kanë një lidhje tek vetëvetja, gjë që s\'duhet të ndodhi.',
'sep' => 'Sht',
'september' => 'Shtator',
'servertime' => 'Ora e shërbyesit tani është',
'set_rights_fail' => '<b>Nuk mund të vendoseshin privilegjet për përdoruesin "$1". (Vendosët emrin e saktë?)</b>',
'set_user_rights' => 'Vendosni privilegjet e përdoruesve',
'setbureaucratflag' => 'Jepi titullin burokrat',
'shortpages' => 'Artikuj të shkurtër',
'show' => 'trego',
'showbigimage' => 'Shkarkoni versionin me rezolucion më të lartë ($1x$2, $3 KB)',
'showdiff' => 'Trego ndryshimet',
'showhideminor' => '$1 redaktimet e vogla | $2 robotët | $3 përdoruesit e rregjistruar',
'showingresults' => 'Duke treguar më poshtë <b>$1</b> përfundime dhe duke filluar me #<b>$2</b>.',
'showingresultsnum' => 'Duke treguar më poshtë <b>$3</b> përfundime dhe duke filluar me #<b>$2</b>.',
'showlast' => 'Trego $1 figurat e fundit të renditura sipas $2.',
'showpreview' => 'Trego parapamjen',
'showtoc' => 'trego',
'sig_tip' => 'Firma juaj me gjithë kohë',
'sitestats' => 'Statistikat e faqeve',
'sitesupport' => 'Dhurime',
'skin' => 'Pamja',
'sourcefilename' => 'Emri i skedarit',
'spamprotectionmatch' => 'Teksti në vijim është cilësuar i padëshiruar nga softueri: $1',
'spamprotectiontext' => 'Faqja që dëshironit të ruani është bllokuar nga filtri i teksteve të padëshiruara. Ka mundësi që kjo të ketë ndodhur për shkak të ndonjë lidhjeje të jashtme.',
'spamprotectiontitle' => 'Mbrojtje ndaj teksteve të padëshiruara',
'speciallogtitlelabel' => 'Titulli:',
'specialloguserlabel' => 'Përdoruesi:',
'specialpage' => 'Faqe speciale',
'specialpages' => 'Faqe speciale',
'spheading' => 'Faqe speciale për të gjithë përdoruesit',
'statistics' => 'Statistika',
'storedversion' => 'Versioni i ruajtur',
'stubthreshold' => 'Kufiri për tregimin e cungjeve',
'subcategories' => 'Nën-kategori',
'subcategorycount' => 'Gjënden $1 nën-kategori në këtë kategori.',
'subcategorycount1' => 'Ndodhen $1 nen-kategori nën këtë kategori.',
'subject' => 'Subjekt/Titull',
'subjectpage' => 'Shikoni subjektin',
'successfulupload' => 'Dhënie e sukseshme',
'summary' => 'Përmbledhje',
'sunday' => 'E Djelë',
'sysoptext' => 'Veprimi që kërkuat mund të bëhet vetëm nga një përdorues me titullin "administrues". Shikoni $1.',
'sysoptitle' => 'Nevojitet titulli "administrues"',
'tableform' => 'tabelë',
'talk' => 'Diskutimet',
'talkexists' => 'Faqja për vete u zhvendos, ndërsa faqja e diskutimit nuk u zhvendos sepse një e tillë ekziston tek titulli i ri. Ju lutem, përpuqini vetë.',
'talkpage' => 'Diskutoni faqen',
'talkpagemoved' => 'Faqja e diskutimeve korrespondente u zhvendos gjithashtu.',
'talkpagenotmoved' => 'Faqja e diskutimeve korrespondente <strong>nuk</strong> u zhvendos.',
'templatesused' => 'Stampa të përdorura në këtë faqe:',
'textboxsize' => 'Rreth redaktimit',
'textmatches' => 'Tekst faqesh që përputhet',
'thisisdeleted' => 'Shikoni ose restauroni $1?',
'thumbnail-more' => 'Zmadho',
'thumbsize' => 'Madhësia fotove përmbledhëse:',
'thursday' => 'E Enjte',
'timezonelegend' => 'Zona kohore',
'timezoneoffset' => 'Ndryshimi',
'timezonetext' => 'Fusni numrin e orëve prej të cilave ndryshon ora lokale nga ajo e shërbyesit (UTC).',
'titlematches' => 'Tituj faqesh që përputhen',
'toc' => 'Tabela e përmbajtjeve',
'tog-editondblclick' => 'Redakto faqet me dopjo-shtypje (JavaScript)',
'tog-editsection' => 'Lejo redaktimin e seksioneve me [redakto] lidhje',
'tog-editsectiononrightclick' => 'Lejo redaktimin e seksioneve me djathtas-shtypje<br /> mbi emrin e seksionit (JavaScript)',
'tog-editwidth' => 'Kutija e redaktimit ka gjerësi te plotë',
'tog-externaldiff' => 'Përdor program të jashtëm për të treguar ndryshimet',
'tog-externaleditor' => 'Përdor program të jashtëm për redaktime',
'tog-fancysig' => 'Mos e përpuno firmën për formatim',
'tog-hideminor' => 'Fshih redaktimet e vogla në ndryshimet e fundit',
'tog-highlightbroken' => 'Trego lidhjet e faqeve bosh <a href="" class="new">kështu </a> (ndryshe: kështu<a href="" class="internal">?</a>).',
'tog-justify' => 'Rregullim i kryeradhës',
'tog-minordefault' => 'Shëno të gjitha redaktimet si të vogla automatikisht',
'tog-nocache' => 'Mos ruaj kopje te faqeve',
'tog-numberheadings' => 'Numëro automatikish mbishkrimet',
'tog-previewonfirst' => 'Trego parapamje në redaktim të parë',
'tog-previewontop' => 'Trego parapamjen përpara kutisë redaktuese, jo mbas saj',
'tog-rememberpassword' => 'Mbaj mënd fjalëkalimin për vizitën e ardhshme',
'tog-showtoc' => 'Trego tabelën e përmbajtjeve<br />(për faqet me më shume se 3 tituj)',
'tog-showtoolbar' => 'Show edit toolbar',
'tog-underline' => 'Nënvizo lidhjet',
'tog-usenewrc' => 'Ndryshimet e fundit me formatin e ri (jo për të gjithë shfletuesit)',
'tog-watchdefault' => 'Shto faqet që redakton tek lista mbikqyrëse',
'toolbox' => 'Mjete',
'trackback' => '; $4$5 : [$2 $1]',
'trackbackexcerpt' => '; $4$5 : [$2 $1]: <nowiki>$3</nowiki>',
'tuesday' => 'E Martë',
'uclinks' => 'Shikoni $1 redaktimet e fundit; shikoni $2 ditët e fundit.',
'ucnote' => 'Më poshtë janë redaktimet më të fundit të <b>$1</b> gjatë <b>$2</b> ditëve.',
'uctop' => ' (sipër)',
'unblockip' => 'Ç\'blloko përdoruesin',
'unblockiptext' => 'Përdor formularin e më poshtëm për t\'i ridhënë leje shkrimi
një përdoruesi ose IP adreseje të bllokuar.',
'unblocklink' => 'ç\'blloko',
'unblocklogentry' => 'ç\'bllokoi "$1"',
'uncategorizedcategories' => 'Kategori të pakategorizuara',
'uncategorizedpages' => 'Faqe të pakategorizuara',
'undelete' => 'Restauroni faqet e grisura',
'undelete_short' => 'Restauroni',
'undeletearticle' => 'Restauro artikullin e grisur',
'undeletebtn' => 'Restauro!',
'undeletedarticle' => 'u restaurua "$1"',
'undeletedtext' => 'Faqja [[:$1|$1]] është restauruar me sukses. Shikoni [[Speciale:Log/delete|rregjistrin e grisjeve]] për një listë të grisjeve dhe restaurimeve të fundit.',
'undeletehistory' => 'N.q.s. restauroni një faqe, të gjitha versionet do të restaurohen në histori. N.q.s. një faqe e re me të njëjtin titull është krijuar që nga grisja, versionet e restauruara do të duken më përpara në histori, dhe versioni i faqes së fundit nuk do të shkëmbehet automatikisht.',
'undeletepage' => 'Shikoni ose restauroni faqet e grisura',
'undeletepagetext' => 'Më poshtë janë faqet që janë grisur por që gjënden akoma në arkiv dhe
mund të restaurohen. Arkivi boshatiset periodikisht.',
'undeleterevision' => 'U gris versioni i $1',
'undeleterevisions' => '$1 versione u futën në arkiv',
'unexpected' => 'Vlerë e papritur: "$1"="$2".',
'unlockbtn' => 'Ç\'blloko rregjistrin',
'unlockconfirm' => 'Po, dëshiroj me të vërtetë të ç\'bllokoj rregjistrin',
'unlockdb' => 'Ç\'blloko rregjistrin',
'unlockdbsuccesssub' => 'Rregjistri u ç\'bllokua me sukses',
'unlockdbsuccesstext' => 'Rregjistri i {{SITENAME}} është ç\'bllokuar.',
'unlockdbtext' => 'Ç\'bllokimi i rregjistrit do të lejojë mundësinë e të gjithë përdoruesve për të redaktuar faqe, për të ndryshuar parapëlqimet e tyre, për të ndryshuar listat mbikqyrëse të tyre, dhe gjëra të tjera për të cilat nevojiten shkrime në rregjistër. Ju lutem konfirmoni që dëshironi me të vërtetë të kryeni këtë veprim.',
'unprotect' => 'Liroje',
'unprotectcomment' => 'Arsyeja për lirimin',
'unprotectedarticle' => 'lirojë [[$1]]',
'unprotectsub' => '(Duke liruar "$1")',
'unprotectthispage' => 'Liroje faqen',
'unusedcategories' => 'Kategori të papërdorura',
'unusedimages' => 'Figura të papërdorura',
'unusedimagestext' => '<p>Ju lutem, vini re se hapësira të tjera si p.sh ato që kanë të bëjnë me gjuhë të ndryshme mund të lidhin
një figurë me një URL në mënyrë direkte, kështuqë ka mundësi që këto figura të rreshtohen këtu megjithëse janë në përdorim.',
'unwatch' => 'Ç\'mbikqyre',
'unwatchthispage' => 'Mos e mbikqyr',
'updated' => '(E ndryshuar)',
'upload' => 'Jepni skedar',
'uploadbtn' => 'Jepni skedar',
'uploaddisabled' => 'Ndjesë, dhëniet janë bllokuar në këtë shërbyes dhe nuk është gabimi juaj.',
'uploadedfiles' => 'Jepni skedarë',
'uploadedimage' => 'dha "[[$1]]"',
'uploaderror' => 'Gabim dhënie',
'uploadlink' => 'Jepni skedar',
'uploadlog' => 'rregjistër dhënie',
'uploadlogpage' => 'Rregjistri ngarkimeve',
'uploadlogpagetext' => 'Më poshtë është një listë e skedarëve më të rinj që janë dhënë.
Të gjitha orët janë me orën e shërbyesit (UTC).
<ul>
</ul>',
'uploadnologin' => 'Nuk keni hyrë brënda',
'uploadnologintext' => 'Duhet të keni [[{{ns:special}}:Userlogin|hyrë brënda]] për të dhënë skedarë.',
'uploadtext' => '\'\'\'NDALO!\'\'\' Përpara se të jepni këtu, lexoni dhe ndiqni [[{{ns:project}}:Rregullat e përdorimit të figurave]] të {{SITENAME}}-s. Mos jepni skedarë për të cilët autori (ose ju) nuk ka dhënë të drejtë për përdorim nën një liçensë të lirë (si psh GNU Free Documentation License ose Creative Commons).

Për të parë ose për të kërkuar figurat e dhëna më parë,
shkoni tek [[Speciale:Imagelist|lista e figurave të dhëna]].
Dhëniet dhe grisjet janë të rregjistruara në [[Speciale:Log|faqen e rregjistrave]].

Përdorni formularin e më poshtëm për të dhënë skedarë të figurave të reja për tu përdorur në illustrimet e artikujve. Për shumicën e shfletuesve, do të shihni një "Browse..." buton, i cili do të hapi dialogun standart të skedarëve të operating system që përdorni.

Për të vendosur një figurë në një artikull, përdorni lidhjen sipas formës \'\'\'<nowiki>[[figura:skedar.jpg]]</nowiki>\'\'\' ose \'\'\'<nowiki>[[figura:skedar.png|tekst përshkrues]]</nowiki>\'\'\'
ose \'\'\'<nowiki>[[media:skedar.ogg]]</nowiki> të tjerë.

Përdorni stampa tek përshkrimi për të cilësuar liçensën e duhur, psh \'\'\'<nowiki>{{GFDL}} {{PD}}</nowiki>\'\'\'',
'uploadwarning' => 'Kujdes dhënie',
'usenewcategorypage' => '1

Vendosni "0" për gërmën e parë për të çaktivizuar planimetrinë e re të faqeve kategori.',
'user_rights_set' => '<b>Privilegjet për përdoruesin "$1" u freskuan</b>',
'usercssjsyoucanpreview' => '<strong>Këshillë:</strong> Përdorni butonin \'Trego parapamjen\' për të provuar ndryshimet tuaja të faqeve css/js përpara se të kryeni ndryshimet.',
'userexists' => 'Emri që përdorët është në përdorim. Zgjidhni një emër tjetër.',
'userlogin' => 'Hyrje',
'userlogout' => 'Dalje',
'usermailererror' => 'Objekti postal ktheu gabimin:',
'userpage' => 'Shikoni faqen',
'userrights-user-editname' => 'Enter a username:',
'userstats' => 'Statistikat e përdoruesve',
'version' => 'Versioni',
'viewcount' => 'Kjo faqe është parë $1 herë.',
'viewprevnext' => 'Shikoni ($1) ($2) ($3).',
'viewsource' => 'Shikoni tekstin',
'viewtalkpage' => 'Shikoni diskutimet',
'wantedpages' => 'Artikuj më të dëshiruar',
'watch' => 'Mbikqyre',
'watchdetails' => '*\'\'\'$1\'\'\' faqe nën mbikqyrje duke mos numëruar faqet e diskutimit
*\'\'\'$2\'\'\' faqe brënda përkufizimit janë redaktuar
<!--*$3...-->
<center>\'\'\'[$4 Trego dhe redakto tërë listën]\'\'\'</center>',
'watcheditlist' => 'Këtu jepet një listë e alfabetizuar e faqeve nën mbikqyrje. Zgjidhni kutinë e sejcilës faqe që dëshironi të heqni nga lista dhe shtypni butonin \'Hiq të zgjedhurat\' në fund të ekranit.',
'watchlist' => 'Lista mbikqyrëse',
'watchlistall1' => 'të gjitha',
'watchlistall2' => 'të gjitha',
'watchlistcontains' => 'Lista mbikqyrëse e juaj ka $1 faqe.',
'watchlistsub' => '(për përdoruesin "$1")',
'watchmethod-list' => 'duke parë faqet nën mbikqyrje për ndryshime së fundmi',
'watchmethod-recent' => 'duke parë ndryshime së fundmi për faqe nën mbikqyrje',
'watchnochange' => 'Asnjë nga artikujt nën mbikqyrje nuk është redaktuar gjatë kohës së dhënë.',
'watchnologin' => 'Nuk keni hyrë brënda',
'watchnologintext' => 'Duhet të keni [[{{ns:special}}:Userlogin|hyrë brënda]] për të ndryshuar listën mbikqyrëse.',
'watchthis' => 'Mbikqyr këtë faqe',
'watchthispage' => 'Mbikqyre këtë faqe',
'wednesday' => 'E Mërkurë',
'welcomecreation' => '<h2>Mirësevini, $1!</h2><p>Llogaria juaj është hapur. Mos harroni të ndryshoni parapëlqimet e {{SITENAME}}-s.',
'whatlinkshere' => 'Lidhjet këtu',
'whitelistacctext' => 'Duhet të [[Speciale:Userlogin|hyni brënda]] dhe të keni të drejta të posaçme pasi tu lejohet të hapni llogari në Wiki.
i',
'whitelistacctitle' => 'Nuk ju lejohet të hapni një llogari',
'whitelistedittext' => 'Duhet të [[Speciale:Userlogin|hyni brënda]] për të redaktuar artikuj.',
'whitelistedittitle' => 'Duhet të hyni brënda për të redaktuar',
'whitelistreadtext' => 'Duhet të [[Speciale:Userlogin|hyni brënda]] për të lexuar artikuj.',
'whitelistreadtitle' => 'Duhet të hyni brënda për të lexuar',
'wlhide' => 'Fshih',
'wlhideshowown' => '$1 redaktimet e mia.',
'wlnote' => 'Më poshtë janë $1 ndryshimet e <b>$2</b> orëve së fundmi.',
'wlsaved' => 'Kjo është një kopje e ruajtur e listës mbikqyrëse tuaj.',
'wlshowlast' => 'Trego $1 orët $2 ditët $3',
'wrong_wfQuery_params' => 'Parametra gabim tek wfQuery()<br />
Funksioni: $1<br />
Pyetja: $2',
'wrongpassword' => 'Fjalëkalimi që futët nuk është i saktë. Provojeni përsëri!',
'yourdiff' => 'Ndryshimet',
'youremail' => 'Adresa e email-it*',
'yourlanguage' => 'Ndërfaqja gjuhësore',
'yourname' => 'Fusni emrin tuaj',
'yournick' => 'Nofka juaj (për firmosje)',
'yourpassword' => 'Fusni fjalëkalimin tuaj',
'yourpasswordagain' => 'Fusni fjalëkalimin përsëri',
'yourrealname' => 'Emri juaj i vërtetë*',
'yourtext' => 'Teksti juaj',
);
class LanguageSq extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesSq;
		return $wgNamespaceNamesSq;
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesSq;
		foreach ( $wgNamespaceNamesSq as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		# Compatbility with alt names:
		if( 0 == strcasecmp( "Perdoruesi", $text ) ) return 2;
		if( 0 == strcasecmp( "Perdoruesi_diskutim", $text ) ) return 3;
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsSq;
		return $wgQuickbarSettingsSq;
	}

	function getSkinNames() {
		global $wgSkinNamesSq;
		return $wgSkinNamesSq;
	}

	function getDateFormats() {
		global $wgDateFormatsSq;
		return $wgDateFormatsSq;
	}

	# localised date and time
	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = substr( $ts, 0, 4 ) . " " .
			$this->getMonthName( substr( $ts, 4, 2 ) ) . " ".
			(0 + substr( $ts, 6, 2 ));
		return $d;
	}

	function time( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
		return $t;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . " " . $this->time( $ts, $adj );
	}

	function getMessage( $key ) {
		global $wgAllMessagesSq;
		if(array_key_exists($key, $wgAllMessagesSq))
			return $wgAllMessagesSq[$key];
		else
			return parent::getMessage($key);
	}

	function formatNum( $number ) {
		global $wgTranslateNumerals;
		return $wgTranslateNumerals ? strtr($number, '.,', ',.' ) : $number;
	}

}

?>

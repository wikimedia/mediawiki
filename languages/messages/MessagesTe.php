<?php
/** Telugu (తెలుగు)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Arjunaraoc
 * @author Ashokjayanti
 * @author Chaduvari
 * @author Jprmvnvijay5
 * @author Kaganer
 * @author Kiranmayee
 * @author Malkum
 * @author Meno25
 * @author Mpradeep
 * @author Praveen Illa
 * @author Ravichandra
 * @author Sunil Mohan
 * @author The Evil IP address
 * @author Urhixidur
 * @author Veeven
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author לערי ריינהארט
 * @author రహ్మానుద్దీన్
 * @author రాకేశ్వర
 * @author వైజాసత్య
 */

$namespaceNames = [
	NS_MEDIA            => 'మీడియా',
	NS_SPECIAL          => 'ప్రత్యేక',
	NS_TALK             => 'చర్చ',
	NS_USER             => 'వాడుకరి',
	NS_USER_TALK        => 'వాడుకరి_చర్చ',
	NS_PROJECT_TALK     => '$1_చర్చ',
	NS_FILE             => 'దస్త్రం',
	NS_FILE_TALK        => 'దస్త్రంపై_చర్చ',
	NS_MEDIAWIKI        => 'మీడియావికీ',
	NS_MEDIAWIKI_TALK   => 'మీడియావికీ_చర్చ',
	NS_TEMPLATE         => 'మూస',
	NS_TEMPLATE_TALK    => 'మూస_చర్చ',
	NS_HELP             => 'సహాయం',
	NS_HELP_TALK        => 'సహాయం_చర్చ',
	NS_CATEGORY         => 'వర్గం',
	NS_CATEGORY_TALK    => 'వర్గం_చర్చ',
];

$namespaceAliases = [
	'సభ్యులు' => NS_USER,
	'సభ్యులపై_చర్చ' => NS_USER_TALK,
	'సభ్యుడు' => NS_USER, # set for T13615
	'సభ్యునిపై_చర్చ' => NS_USER_TALK,
	'బొమ్మ' => NS_FILE,
	'బొమ్మపై_చర్చ' => NS_FILE_TALK,
	'ఫైలు' => NS_FILE,
	'ఫైలుపై_చర్చ' => NS_FILE_TALK,
	'సహాయము' => NS_HELP,
	'సహాయము_చర్చ' => NS_HELP_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'చురుగ్గావున్నవాడుకరులు' ],
	'Allmessages'               => [ 'అన్నిసందేశాలు' ],
	'AllMyUploads'              => [ 'నాయెక్కింపులన్నీ' ],
	'Allpages'                  => [ 'అన్నిపేజీలు' ],
	'Ancientpages'              => [ 'పురాతనపేజీలు' ],
	'Badtitle'                  => [ 'చెడుశీర్షిక' ],
	'Blankpage'                 => [ 'ఖాళీపేజి' ],
	'Block'                     => [ 'అడ్డగించు', 'ఐపినిఅడ్డగించు', 'వాడుకరినిఅడ్డగించు' ],
	'Booksources'               => [ 'పుస్తకమూలాలు' ],
	'BrokenRedirects'           => [ 'తెగిపోయినదారిమార్పులు' ],
	'Categories'                => [ 'వర్గాలు' ],
	'ChangeEmail'               => [ 'ఈమెయిలుమార్పు' ],
	'ChangePassword'            => [ 'సంకేతపదముమార్చు' ],
	'ComparePages'              => [ 'పేజీలనుపోల్చు' ],
	'Confirmemail'              => [ 'ఈమెయిలుధ్రువపరచు' ],
	'Contributions'             => [ 'చేర్పులు' ],
	'CreateAccount'             => [ 'ఖాతాసృష్టించు' ],
	'Deadendpages'              => [ 'అగాధపేజీలు' ],
	'DeletedContributions'      => [ 'తొలగించినచేర్పులు' ],
	'Diff'                      => [ 'తేడా' ],
	'DoubleRedirects'           => [ 'జమిలిదారిమార్పు' ],
	'Emailuser'                 => [ 'వాడుకరికిఈమెయిలుచెయ్యి' ],
	'Export'                    => [ 'ఎగుమతి' ],
	'Fewestrevisions'           => [ 'అతితక్కువకూర్పులు' ],
	'Import'                    => [ 'దిగుమతి' ],
	'Listfiles'                 => [ 'ఫైళ్లజాబితా', 'బొమ్మలజాబితా' ],
	'Listgrouprights'           => [ 'గుంపుహక్కులజాబితా', 'వాడుకరులగుంపుహక్కులు' ],
	'Listusers'                 => [ 'వాడుకరులజాబితా' ],
	'Log'                       => [ 'చిట్టా', 'చిట్టాలు' ],
	'Lonelypages'               => [ 'ఒంటరిపేజీలు', 'అనాధపేజీలు' ],
	'Longpages'                 => [ 'పొడుగుపేజీలు' ],
	'MergeHistory'              => [ 'చరిత్రనువిలీనంచేయి' ],
	'Mostcategories'            => [ 'ఎక్కువవర్గములు' ],
	'Mostrevisions'             => [ 'ఎక్కువకూర్పులు' ],
	'Movepage'                  => [ 'వ్యాసమునుతరలించు' ],
	'Mycontributions'           => [ 'నా_మార్పులు-చేర్పులు' ],
	'Mypage'                    => [ 'నాపేజీ' ],
	'Mytalk'                    => [ 'నాచర్చ' ],
	'Newimages'                 => [ 'కొత్తఫైళ్లు', 'కొత్తబొమ్మలు' ],
	'Newpages'                  => [ 'కొత్తపేజీలు' ],
	'Preferences'               => [ 'అభిరుచులు' ],
	'Protectedpages'            => [ 'సంరక్షితపేజీలు' ],
	'Randompage'                => [ 'యాదృచ్చికపేజీ' ],
	'Randomredirect'            => [ 'యాదుచ్చికదారిమార్పు' ],
	'Recentchanges'             => [ 'ఇటీవలిమార్పులు' ],
	'Recentchangeslinked'       => [ 'చివరిమార్పులలింకులు', 'సంబంధితమార్పులు' ],
	'Revisiondelete'            => [ 'కూర్పుతొలగించు' ],
	'Search'                    => [ 'అన్వేషణ' ],
	'Shortpages'                => [ 'చిన్నపేజీలు' ],
	'Specialpages'              => [ 'ప్రత్యేకపేజీలు' ],
	'Statistics'                => [ 'గణాంకాలు' ],
	'Uncategorizedcategories'   => [ 'వర్గీకరించనివర్గములు' ],
	'Uncategorizedimages'       => [ 'వర్గీకరించనిఫైళ్లు', 'వర్గీకరించనిబొమ్మలు' ],
	'Uncategorizedpages'        => [ 'వర్గీకరించనిపేజీలు' ],
	'Uncategorizedtemplates'    => [ 'వర్గీకరించనిమూసలు' ],
	'Unusedcategories'          => [ 'వాడనివర్గములు' ],
	'Unusedimages'              => [ 'వాడనిఫైళ్లు', 'వాడనిబొమ్మలు' ],
	'Unusedtemplates'           => [ 'వాడనిమూసలు' ],
	'Unwatchedpages'            => [ 'వీక్షించనిపేజీలు' ],
	'Upload'                    => [ 'ఎక్కింపు' ],
	'Userlogin'                 => [ 'వాడుకరిప్రవేశం' ],
	'Userlogout'                => [ 'వాడుకరినిష్క్రమణ' ],
	'Userrights'                => [ 'వాడుకరిహక్కులు' ],
	'Version'                   => [ 'కూర్పు' ],
	'Wantedcategories'          => [ 'కోరినవర్గాలు' ],
	'Wantedfiles'               => [ 'అవసరమైనఫైళ్లు' ],
	'Wantedpages'               => [ 'అవసరమైనపేజీలు', 'విరిగిపోయినలింకులు' ],
	'Wantedtemplates'           => [ 'అవసరమైననమూనాలు' ],
	'Watchlist'                 => [ 'వీక్షణజాబితా' ],
	'Whatlinkshere'             => [ 'ఇక్కడికిలింకున్నపేజీలు' ],
	'Withoutinterwiki'          => [ 'అంతరవికీలేకుండా' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#దారిమార్పు', '#REDIRECT' ],
	'notoc'                     => [ '0', '__విషయసూచికవద్దు__', '__NOTOC__' ],
	'toc'                       => [ '0', '__విషయసూచిక__', '__TOC__' ],
	'pagename'                  => [ '1', 'పేజీపేరు', 'PAGENAME' ],
	'img_right'                 => [ '1', 'కుడి', 'right' ],
	'img_left'                  => [ '1', 'ఎడమ', 'left' ],
	'special'                   => [ '0', 'ప్రత్యేక', 'special' ],
];

$linkTrail = "/^([\u{0C01}-\u{0C6F}]+)(.*)$/sDu";

$digitGroupingPattern = "##,##,###";

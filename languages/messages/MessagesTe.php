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

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'సభ్యులు' => NS_USER,
	'సభ్యులపై_చర్చ' => NS_USER_TALK,
	'సభ్యుడు' => NS_USER, # set for bug 11615
	'సభ్యునిపై_చర్చ' => NS_USER_TALK,
	'బొమ్మ' => NS_FILE,
	'బొమ్మపై_చర్చ' => NS_FILE_TALK,
	'ఫైలు' => NS_FILE,
	'ఫైలుపై_చర్చ' => NS_FILE_TALK,
	'సహాయము' => NS_HELP,
	'సహాయము_చర్చ' => NS_HELP_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'చురుగ్గావున్నవాడుకరులు' ),
	'Allmessages'               => array( 'అన్నిసందేశాలు' ),
	'AllMyUploads'              => array( 'నాయెక్కింపులన్నీ' ),
	'Allpages'                  => array( 'అన్నిపేజీలు' ),
	'Ancientpages'              => array( 'పురాతనపేజీలు' ),
	'Badtitle'                  => array( 'చెడుశీర్షిక' ),
	'Blankpage'                 => array( 'ఖాళీపేజి' ),
	'Block'                     => array( 'అడ్డగించు', 'ఐపినిఅడ్డగించు', 'వాడుకరినిఅడ్డగించు' ),
	'Booksources'               => array( 'పుస్తకమూలాలు' ),
	'BrokenRedirects'           => array( 'తెగిపోయినదారిమార్పులు' ),
	'Categories'                => array( 'వర్గాలు' ),
	'ChangeEmail'               => array( 'ఈమెయిలుమార్పు' ),
	'ChangePassword'            => array( 'సంకేతపదముమార్చు' ),
	'ComparePages'              => array( 'పేజీలనుపోల్చు' ),
	'Confirmemail'              => array( 'ఈమెయిలుధ్రువపరచు' ),
	'Contributions'             => array( 'చేర్పులు' ),
	'CreateAccount'             => array( 'ఖాతాసృష్టించు' ),
	'Deadendpages'              => array( 'అగాధపేజీలు' ),
	'DeletedContributions'      => array( 'తొలగించినచేర్పులు' ),
	'Diff'                      => array( 'తేడా' ),
	'DoubleRedirects'           => array( 'జమిలిదారిమార్పు' ),
	'Emailuser'                 => array( 'వాడుకరికిఈమెయిలుచెయ్యి' ),
	'Export'                    => array( 'ఎగుమతి' ),
	'Fewestrevisions'           => array( 'అతితక్కువకూర్పులు' ),
	'Import'                    => array( 'దిగుమతి' ),
	'Listfiles'                 => array( 'ఫైళ్లజాబితా', 'బొమ్మలజాబితా' ),
	'Listgrouprights'           => array( 'గుంపుహక్కులజాబితా', 'వాడుకరులగుంపుహక్కులు' ),
	'Listusers'                 => array( 'వాడుకరులజాబితా' ),
	'Log'                       => array( 'చిట్టా', 'చిట్టాలు' ),
	'Lonelypages'               => array( 'ఒంటరిపేజీలు', 'అనాధపేజీలు' ),
	'Longpages'                 => array( 'పొడుగుపేజీలు' ),
	'MergeHistory'              => array( 'చరిత్రనువిలీనంచేయి' ),
	'Mostcategories'            => array( 'ఎక్కువవర్గములు' ),
	'Mostrevisions'             => array( 'ఎక్కువకూర్పులు' ),
	'Movepage'                  => array( 'వ్యాసమునుతరలించు' ),
	'Mycontributions'           => array( 'నా_మార్పులు-చేర్పులు' ),
	'Mypage'                    => array( 'నాపేజీ' ),
	'Mytalk'                    => array( 'నాచర్చ' ),
	'Newimages'                 => array( 'కొత్తఫైళ్లు', 'కొత్తబొమ్మలు' ),
	'Newpages'                  => array( 'కొత్తపేజీలు' ),
	'Preferences'               => array( 'అభిరుచులు' ),
	'Protectedpages'            => array( 'సంరక్షితపేజీలు' ),
	'Randompage'                => array( 'యాదృచ్చికపేజీ' ),
	'Randomredirect'            => array( 'యాదుచ్చికదారిమార్పు' ),
	'Recentchanges'             => array( 'ఇటీవలిమార్పులు' ),
	'Recentchangeslinked'       => array( 'చివరిమార్పులలింకులు', 'సంబంధితమార్పులు' ),
	'Revisiondelete'            => array( 'కూర్పుతొలగించు' ),
	'Search'                    => array( 'అన్వేషణ' ),
	'Shortpages'                => array( 'చిన్నపేజీలు' ),
	'Specialpages'              => array( 'ప్రత్యేకపేజీలు' ),
	'Statistics'                => array( 'గణాంకాలు' ),
	'Uncategorizedcategories'   => array( 'వర్గీకరించనివర్గములు' ),
	'Uncategorizedimages'       => array( 'వర్గీకరించనిఫైళ్లు', 'వర్గీకరించనిబొమ్మలు' ),
	'Uncategorizedpages'        => array( 'వర్గీకరించనిపేజీలు' ),
	'Uncategorizedtemplates'    => array( 'వర్గీకరించనిమూసలు' ),
	'Unusedcategories'          => array( 'వాడనివర్గములు' ),
	'Unusedimages'              => array( 'వాడనిఫైళ్లు', 'వాడనిబొమ్మలు' ),
	'Unusedtemplates'           => array( 'వాడనిమూసలు' ),
	'Unwatchedpages'            => array( 'వీక్షించనిపేజీలు' ),
	'Upload'                    => array( 'ఎక్కింపు' ),
	'Userlogin'                 => array( 'వాడుకరిప్రవేశం' ),
	'Userlogout'                => array( 'వాడుకరినిష్క్రమణ' ),
	'Userrights'                => array( 'వాడుకరిహక్కులు' ),
	'Version'                   => array( 'కూర్పు' ),
	'Wantedcategories'          => array( 'కోరినవర్గాలు' ),
	'Wantedfiles'               => array( 'అవసరమైనఫైళ్లు' ),
	'Wantedpages'               => array( 'అవసరమైనపేజీలు', 'విరిగిపోయినలింకులు' ),
	'Wantedtemplates'           => array( 'అవసరమైననమూనాలు' ),
	'Watchlist'                 => array( 'వీక్షణజాబితా' ),
	'Whatlinkshere'             => array( 'ఇక్కడికిలింకున్నపేజీలు' ),
	'Withoutinterwiki'          => array( 'అంతరవికీలేకుండా' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#దారిమార్పు', '#REDIRECT' ),
	'notoc'                     => array( '0', '__విషయసూచికవద్దు__', '__NOTOC__' ),
	'toc'                       => array( '0', '__విషయసూచిక__', '__TOC__' ),
	'pagename'                  => array( '1', 'పేజీపేరు', 'PAGENAME' ),
	'img_right'                 => array( '1', 'కుడి', 'right' ),
	'img_left'                  => array( '1', 'ఎడమ', 'left' ),
	'special'                   => array( '0', 'ప్రత్యేక', 'special' ),
);

$linkTrail = "/^([\xE0\xB0\x81-\xE0\xB1\xAF]+)(.*)$/sDu";

$digitGroupingPattern = "##,##,###";


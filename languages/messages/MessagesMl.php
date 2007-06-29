<?php
/** Malayalam (മലയാളം)
  *
  * @addtogroup Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  * @edited by:
  *		Sadik Khalid <sadik.khalid@gmail.com>,
  *		Manjith Joseph <manjithkaini@gmail.com>,
  *		Praveen Prakash <me.praveen@gmail.com>,
  *		Shiju Alex <shijualexonline@gmail.com>, 
  */

$digitTransformTable = array(
	'0' => '൦', # &#x0d66;
	'1' => '൧', # &#x0d67;
	'2' => '൨', # &#x0d68;
	'3' => '൩', # &#x0d69;
	'4' => '൪', # &#x0d6a;
	'5' => '൫', # &#x0d6b;
	'6' => '൬', # &#x0d6c;
	'7' => '൭', # &#x0d6d;
	'8' => '൮', # &#x0d6e;
	'9' => '൯', # &#x0d6f;
);

$namespaceNames = array(
	NS_MEDIA => 'മീഡിയ',
	NS_SPECIAL => 'പ്രത്യേകം',
	NS_MAIN => '',
	NS_TALK => 'സംവാദം',
	NS_USER => 'ഉപയോക്താവ്',
	NS_USER_TALK => 'ഉപയോക്താവിന്റെ_സംവാദം',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK => '$1_സംവാദം',
	NS_IMAGE => 'ചിത്രം',
	NS_IMAGE_TALK => 'ചിത്രത്തിന്റെ_സംവാദം',
	NS_MEDIAWIKI => 'മീഡിയവിക്കി',
	NS_MEDIAWIKI_TALK => 'മീഡിയവിക്കി_സംവാദം',
	NS_TEMPLATE => 'ഫലകം',
	NS_TEMPLATE_TALK => 'ഫലകത്തിന്റെ_സംവാദം',
	NS_CATEGORY => 'വിഭാഗം',
	NS_CATEGORY_TALK => 'വിഭാഗത്തിന്റെ_സംവാദം',
	NS_HELP => 'സഹായം',
	NS_HELP_TALK => 'സഹായത്തിന്റെ_സംവാദം',
);



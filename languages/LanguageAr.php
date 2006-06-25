<?php
/** Arabic (العربية)
  *
  * @package MediaWiki
  * @subpackage Language
  */

/** This is an UTF-8 language  */
require_once('LanguageUtf8.php');

/* private */ $wgNamespaceNamesAr = array(
	NS_MEDIA            => 'ملف',
	NS_SPECIAL          => 'خاص',
	NS_MAIN             => '',
	NS_TALK             => 'نقاش',
	NS_USER             => 'مستخدم',
	NS_USER_TALK        => 'نقاش_المستخدم',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => 'نقاش' . '_' . $wgMetaNamespace,
	NS_IMAGE            => 'صورة',
	NS_IMAGE_TALK       => 'نقاش_الصورة',
	NS_MEDIAWIKI        => 'ميدياويكي',
	NS_MEDIAWIKI_TALK   => 'نقاش_ميدياويكي',
	NS_TEMPLATE         => 'قالب',
	NS_TEMPLATE_TALK    => 'نقاش_قالب',
	NS_HELP             => 'مساعدة',
	NS_HELP_TALK        => 'نقاش_المساعدة',
	NS_CATEGORY         => 'تصنيف',
	NS_CATEGORY_TALK    => 'نقاش_التصنيف'
) + $wgNamespaceNamesEn;


/* private */ $wgMagicWordsAr = array(
#   ID                                 CASE  SYNONYMS
	MAG_REDIRECT             => array( 0,    '#REDIRECT'    ,   '#تحويل'                  ),
	MAG_NOTOC                => array( 0,    '__NOTOC__'   ,   '__لافهرس__'               ),
	MAG_FORCETOC             => array( 0,    '__FORCETOC__'    ,   '__لصق_فهرس__'        ),
	MAG_TOC                  => array( 0,    '__TOC__'     ,   '__فهرس__'                ),
	MAG_NOEDITSECTION        => array( 0,    '__NOEDITSECTION__' ,   '__لاتحريرقسم__'      ),
	MAG_START                => array( 0,    '__START__'   ,   '__ابدأ__'                ),
	MAG_CURRENTMONTH         => array( 1,    'CURRENTMONTH'     ,    'شهر' , 'شهر_حالي'   ),
	MAG_CURRENTMONTHNAME     => array( 1,    'CURRENTMONTHNAME'    ,   'اسم_شهر', 'اسم_شهر_حالي'),
#	MAG_CURRENTMONTHNAMEGEN  => array( 1,    'CURRENTMONTHNAMEGEN'    ),
#	MAG_CURRENTMONTHABBREV   => array( 1,    'CURRENTMONTHABBREV'     ),
	MAG_CURRENTDAY           => array( 1,    'CURRENTDAY'    ,          'يوم'            ),
#	MAG_CURRENTDAY2          => array( 1,    'CURRENTDAY2'            ),
	MAG_CURRENTDAYNAME       => array( 1,    'CURRENTDAYNAME'   ,     'اسم_يوم'          ),
	MAG_CURRENTYEAR          => array( 1,    'CURRENTYEAR'    ,    'عام'                 ),
	MAG_CURRENTTIME          => array( 1,    'CURRENTTIME'    ,   'وقت'                  ),
	MAG_NUMBEROFARTICLES     => array( 1,    'NUMBEROFARTICLES'  ,'عددالمقالات' , 'عدد_المقالات'),
	MAG_NUMBEROFFILES        => array( 1,    'NUMBEROFFILES'  , 'عددالملفات' , 'عدد_الملفات'),
	MAG_PAGENAME             => array( 1,    'PAGENAME'       ,       'اسم_صفحة'         ),
	MAG_PAGENAMEE            => array( 1,    'PAGENAMEE'      ,         'عنوان_صفحة'     ),
	MAG_NAMESPACE            => array( 1,    'NAMESPACE'       ,      'نطاق'             ),
	MAG_NAMESPACEE           => array( 1,    'NAMESPACEE'     , 'عنوان_نطاق'             ),
	MAG_FULLPAGENAME         => array( 1,    'FULLPAGENAME', 'اسم_كامل'                  ),
	MAG_FULLPAGENAMEE        => array( 1,    'FULLPAGENAMEE'  , 'عنوان_كامل'             ),
	MAG_MSG                  => array( 0,    'MSG:'         ,          'رسالة:'          ),
	MAG_SUBST                => array( 0,    'SUBST:'      ,     'نسخ:'  , 'نسخ_قالب:'   ),
	MAG_MSGNW                => array( 0,    'MSGNW:'     ,  'مصدر:' , 'مصدر_قالب:'      ),
	MAG_END                  => array( 0,    '__END__'       ,  '__نهاية__', '__إنهاء__'   ),
	MAG_IMG_THUMBNAIL        => array( 1,    'thumbnail', 'thumb'   ,          'تصغير'    ),
	MAG_IMG_MANUALTHUMB      => array( 1,    'thumbnail=$1', 'thumb=$1'  ,'تصغير=$1'      ),
	MAG_IMG_RIGHT            => array( 1,    'right'       ,       'يمين'                  ),
	MAG_IMG_LEFT             => array( 1,    'left'           ,    'يسار'                ),
	MAG_IMG_NONE             => array( 1,    'none'         ,        'بدون'              ),
	MAG_IMG_WIDTH            => array( 1,    '$1px'  ,    '$1بك'                         ),
	MAG_IMG_CENTER           => array( 1,    'center', 'centre'   ,           'وسط'      ),
	MAG_IMG_FRAMED           => array( 1,    'framed', 'enframed', 'frame' , 'إطار', 'اطار'),
	MAG_INT                  => array( 0,    'INT:'        ,        'محتوى:'              ),
	MAG_SITENAME             => array( 1,    'SITENAME'    ,          'اسم_الموقع'        ),
	MAG_NS                   => array( 0,    'NS:'            ,              'نط:'       ),
	MAG_LOCALURL             => array( 0,    'LOCALURL:'      ,       'عنوان:'           ),
#	MAG_LOCALURLE            => array( 0,    'LOCALURLE:'             ),
	MAG_SERVER               => array( 0,    'SERVER'          ,   'العنوان'             ),
	MAG_SERVERNAME           => array( 0,    'SERVERNAME'      ,   'اسم_عنوان'           ),
	MAG_SCRIPTPATH           => array( 0,    'SCRIPTPATH'       ,      'مسار'            ),
#	MAG_GRAMMAR              => array( 0,    'GRAMMAR:'               ),
	MAG_NOTITLECONVERT       => array( 0,    '__NOTITLECONVERT__', '__NOTC__',  'لاتحويل_عنوان'),
	MAG_NOCONTENTCONVERT     => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__', 'لاتحويل_محتوى' ),
	MAG_CURRENTWEEK          => array( 1,    'CURRENTWEEK'    ,     'أسبوع'              ),
	MAG_CURRENTDOW           => array( 1,    'CURRENTDOW'      ,     'رقم_يوم'           ),
	MAG_REVISIONID           => array( 1,    'REVISIONID'        ,     'نسخة'            ),
#	MAG_PLURAL               => array( 0,    'PLURAL:'                ),
	MAG_FULLURL              => array( 0,    'FULLURL:', 'عنوان_كامل:'                   ),
#	MAG_FULLURLE             => array( 0,    'FULLURLE:'              ),
#	MAG_LCFIRST              => array( 0,    'LCFIRST:'               ),
#	MAG_UCFIRST              => array( 0,    'UCFIRST:'               ),
#	MAG_LC                   => array( 0,    'LC:'                    ),
#	MAG_UC                   => array( 0,    'UC:'                    ),
#	MAG_RAW                  => array( 0,    'RAW:'                   ),
);


if (!$wgCachedMessageArrays) {
	require_once('MessagesAr.php');
}

class LanguageAr extends LanguageUtf8 {
	function digitTransformTable() {
		return array(
			'0' => '٠',
			'1' => '١',
			'2' => '٢',
			'3' => '٣',
			'4' => '٤',
			'5' => '٥',
			'6' => '٦',
			'7' => '٧',
			'8' => '٨',
			'9' => '٩',
			'.' => '٫', // wrong table?
			',' => '٬'
		);
	}

	function getNamespaces() {
		global $wgNamespaceNamesAr;
		return $wgNamespaceNamesAr;
	}

	function getMonthAbbreviation( $key ) {
		/* No abbreviations in Arabic */
		return $this->getMonthName( $key );
	}

	function isRTL() {
		return true;
	}

	function linkPrefixExtension() {
		return true;
	}

	function getDefaultUserOptions() {
		$opt = parent::getDefaultUserOptions();

		# Swap sidebar to right side by default
		$opt['quickbar'] = 2;

		# Underlines seriously harm legibility. Force off:
		$opt['underline'] = 0;
		return $opt ;
	}

	function fallback8bitEncoding() {
		return 'windows-1256';
	}

	function getMagicWords()  {
		global $wgMagicWordsAr;
		return $wgMagicWordsAr;
	}

	function getMessage( $key ) {
		global $wgAllMessagesAr;
		if( isset( $wgAllMessagesAr[$key] ) ) {
			return $wgAllMessagesAr[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

}
?>

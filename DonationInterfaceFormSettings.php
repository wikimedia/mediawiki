<?php
/**
 * Some setup vars to make our lives a little easier. 
 * These are unset at the end of the file. 
 */
$forms_whitelist = array();
$form_dirs = array(
	'default' => $wgDonationInterfaceHtmlFormDir,
	'gc' => $wgGlobalCollectGatewayHtmlFormDir,
//	'pfp' => $wgPayflowProGatewayHtmlFormDir,
);

/**********
 * Amazon *
 **********/
$forms_whitelist['amazon'] = array(
	'gateway' => 'amazon',
	'countries' => array( '+' => 'US',),
	'currencies' => array( '+' => 'USD',),
	'payment_methods' => array('amazon' => 'ALL'),
	'redirect',
);

/****************************
 * Bank Transfer - Two-Step *
 ****************************/

$forms_whitelist['bt'] = array(
	'file' => $form_dirs['gc'] . '/bt/bt.html',
	'gateway' => 'globalcollect',
	'countries' => array(
	//	'+' => 'ALL',
		'-' => array('CA','US'),
	),
	'currencies' => array(
		'+' => array('AED', 'BGN', 'BHD', 'CLP', 'CZK', 'DKK', 'EEK', 'EGP', 'EUR', 'HRK',
					 'HUF', 'IDR', 'JPY', 'LBP', 'MXN', 'MYR', 'NOK', 'NZD', 'PEN', 'PLN',
					 'QAR', 'RON', 'RUB', 'SEK', 'THB', 'TRY', 'TWD', 'USD', 'ZAR'),
	),
	'payment_methods' => array('bt' => 'ALL')
);

$forms_whitelist['bt-CA'] = array(
	'file' => $form_dirs['gc'] . '/bt/bt-CA.html',
	'gateway' => 'globalcollect',
	'countries' => array(
		'+' => 'CA',
	),
	'payment_methods' => array('bt' => 'ALL')
);

$forms_whitelist['bt-US'] = array(
	'file' => $form_dirs['gc'] . '/bt/bt-US.html',
	'gateway' => 'globalcollect',
	'countries' => array(
		'+' => 'US',
	),
	'payment_methods' => array('bt' => 'ALL')
);


/****************
 * Direct Debit *
 ****************/
/*
$forms_whitelist['dd-ES'] = array(
	'file' => $form_dirs['gc'] . '/dd/dd-ES.html',
	'gateway' => 'globalcollect',
	'countries' => array(
		'+' => 'ES',
	),
	'payment_methods' => array('dd' => 'ALL')
); */


/*********************
 * Electronic Wallet *
 *********************/

$forms_whitelist['ew-webmoney'] = array(
	'file' => $form_dirs['gc'] . '/ew/ew-webmoney.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('ew' => 'ew_webmoney'),
	'countries' => array( '+' => array( 'FI', 'RU' ), ),
	'currencies' => array( '+' => array( 'EUR', 'RUB' ), ),
);

$forms_whitelist['ew-yandex'] = array(
	'file' => $form_dirs['gc'] . '/ew/ew-yandex.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('ew' => 'ew_yandex'),
	'countries' => array( '+' => array( 'RU', ), ),
	'currencies' => array( '+' => array( 'RUB', ), ),
);


/*******************************
 * RealTime Banking - Two Step *
 *******************************/

$forms_whitelist['rtbt-sofo'] = array(
	'file' => $form_dirs['gc'] . '/rtbt/rtbt-sofo.html',
	'gateway' => 'globalcollect',
	'countries' => array(
		'+' => array( 'AT', 'BE' ),
		'-' => 'GB'
	),
	'currencies' => array( '+' => 'EUR' ),
	'payment_methods' => array('rtbt' => 'rtbt_sofortuberweisung'),
);

$forms_whitelist['rtbt-sofo-GB'] = array(
	'file' => $form_dirs['gc'] . '/rtbt/rtbt-sofo-GB.html',
	'gateway' => 'globalcollect',
	'countries' => array( '+' => 'GB' ),
	'currencies' => array( '+' => 'GBP' ),
	'payment_methods' => array('rtbt' => 'rtbt_sofortuberweisung')
);

$forms_whitelist['rtbt-ideal'] = array(
	'file' => $form_dirs['gc'] . '/rtbt/rtbt-ideal.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('rtbt' => 'rtbt_ideal'),
	'countries' => array( '+' => 'NL' ),
	'currencies' => array( '+' => 'EUR' ),
);

$forms_whitelist['rtbt-enets'] = array(
	'file' => $form_dirs['gc'] . '/rtbt/rtbt-enets.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('rtbt' => 'rtbt_enets'),
	'countries' => array( '+' => 'SI' ),
	'currencies' => array( '+' => 'SGD' ),
);

/*
$forms_whitelist['rtbt-eps'] = array(
	'file' => $form_dirs['gc'] . '/rtbt/rtbt-eps.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('rtbt' => 'rtbt_eps')
);
*/

$forms_whitelist['rtbt-ideal-noadd'] = array(
	'file' => $form_dirs['gc'] . '/rtbt/rtbt-ideal-noadd.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('rtbt' => 'rtbt_ideal')
);


/********
 * BPAY *
 ********/

$forms_whitelist['obt-bpay'] = array(
	'file' => $form_dirs['gc'] . '/obt/obt-bpay.html',
	'gateway' => 'globalcollect',
	'countries' => array( '+' => 'AU'),
	'currencies' => array( '+' => 'AUD'),
	'payment_methods' => array('obt' => 'bpay')
);

/*****************************
 * Credit Card - Single Step *
 *****************************/
/*
$forms_whitelist['webitects_2_3step'] = array(
	'file' => $form_dirs['gc'] . '/webitects_2_3step.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc' ))
);

$forms_whitelist['webitects_2_3step-CA'] = array(
	'file' => $form_dirs['gc'] . '/webitects_2_3step-CA.html',
	'gateway' => 'globalcollect',
	'countries' => array( '+' => 'CA' ),
	'payment_methods' => array('cc' => array( 'visa', 'mc' ))
);

$forms_whitelist['webitects_2_3stepB-US'] = array(
	'file' => $form_dirs['gc'] . '/webitects_2_3stepB-US.html',
	'gateway' => 'globalcollect',
	'countries' => array( '+' => 'US' ),
	'payment_methods' => array('cc' => array( 'visa', 'mc' ))
); 
*/


/**************************
 * Credit Card - Two Step *
 **************************/

$forms_whitelist['webitects2nd'] = array(
	'file' => $form_dirs['gc'] . '/webitects2nd.html',
	'gateway' => 'globalcollect',
//	'payment_methods' => array('cc' => array( 'visa', 'mc' ))
);

$forms_whitelist['webitects2nd-US'] = array(
	'file' => $form_dirs['gc'] . '/webitects2nd-US.html',
	'gateway' => 'globalcollect',
//	'payment_methods' => array('cc' => array( 'visa', 'mc' ))
);

$forms_whitelist['webitects2nd_green-US'] = array(
	'file' => $form_dirs['gc'] . '/webitects2nd_green-US.html',
	'gateway' => 'globalcollect',
//	'payment_methods' => array('cc' => array( 'visa', 'mc' ))
);

$forms_whitelist['webitects2nd-amex'] = array(
	'file' => $form_dirs['gc'] . '/webitects2nd-amex.html',
	'gateway' => 'globalcollect',
//	'payment_methods' => array('cc' => array( 'visa', 'mc', 'amex' ))
);


/**********************
 * Credit Card - Misc *
 **********************/

$forms_whitelist['cc-vmad'] = array(
	'file' => $form_dirs['gc'] . '/cc/cc-vmad.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'amex', 'discover' )),
	'countries' => array(
		'+' => array( 'US', ),
	),
);

$forms_whitelist['cc-vmaj'] = array(
	'file' => $form_dirs['gc'] . '/cc/cc-vmaj.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'amex', 'jcb' )),
	'countries' => array(
		'+' => array( 'AD', 'AT', 'AU', 'BE', 'BH', 'DE', 'EC', 'ES', 'FI', 'FR', 'GB',
					  'GF', 'GR', 'HK', 'IE', 'IT', 'JP', 'KR', 'LU', 'MY', 'NL', 'PR',
					  'PT', 'SG', 'SI', 'SK', 'TH', 'TW', ),
	),
);

$forms_whitelist['cc-vmd'] = array(
	'file' => $form_dirs['gc'] . '/cc/cc-vmd.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'discover' )),
	'countries' => array(
		// Array merge with cc-vmad as fallback in case 'a' goes down
		'+' => array_merge(
			$forms_whitelist['cc-vmad']['countries']['+'],
			array() // as of right now, nothing specific here
		),
	),
);

$forms_whitelist['cc-vmj'] = array(
	'file' => $form_dirs['gc'] . '/cc/cc-vmj.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'jcb' )),
	'countries' => array(
		// Array merge with cc-vmaj as fallback in case 'a' goes down
		'+' => array_merge(
			$forms_whitelist['cc-vmaj']['countries']['+'],
			array( 'BR', 'ID', 'PH', )
		),
	),
);

$forms_whitelist['cc-vma'] = array(
	'file' => $form_dirs['gc'] . '/cc/cc-vma.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'amex' )),
	'countries' => array(
		// Array merge with cc-vmaj as fallback in case 'j' goes down
		// Array merge with cc-vmad as fallback in case 'd' goes down
		'+' => array_merge(
			$forms_whitelist['cc-vmaj']['countries']['+'],
			$forms_whitelist['cc-vmad']['countries']['+'],
			array( 'AE', 'AL', 'AN', 'AR', 'BG', 'CA', 'CH', 'CN', 'CR', 'CY', 'CZ', 'DK',
				 'DZ', 'EE', 'EG', 'JO', 'KE', 'HR', 'HU', 'IL', 'KW', 'KZ', 'LB', 'LI',
				 'LK', 'LT', 'LV', 'MA', 'MT', 'NO', 'NZ', 'OM', 'PK', 'PL', 'QA', 'RO',
				 'RU', 'SA', 'SE', 'TN', 'TR', 'UA', )
		)
	),
);

$forms_whitelist['cc-vm'] = array(
	'file' => $form_dirs['gc'] . '/cc/cc-vm.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc' )),
	'countries' => array(
		// Array merge with cc-vmj as fallback in case 'j' goes down
		// Array merge with cc-vmd as fallback in case 'd' goes down
		'+' => array_merge(
			$forms_whitelist['cc-vmj']['countries']['+'],
			$forms_whitelist['cc-vmd']['countries']['+'],
			array( 'AG', 'AM', 'AO', 'AS', 'AW', 'AZ', 'BA', 'BB', 'BD', 'BF', 'BI', 'BJ',
				 'BM', 'BN', 'BO', 'BS', 'BW', 'BY', 'BZ', 'CF', 'CG', 'CI', 'CK', 'CL',
				 'CM', 'CO', 'CV', 'DJ', 'DM', 'DO', 'ER', 'ET', 'FJ', 'FM', 'FO', 'GA',
				 'GD', 'GE', 'GL', 'GM', 'GT', 'GU', 'HN', 'IN', 'IQ', 'IS', 'JM', 'KH',
				 'KI', 'KM', 'KN', 'KP', 'LC', 'LR', 'LY', 'MC', 'MD', 'ME', 'MG', 'MH',
				 'MK', 'ML', 'MO', 'MP', 'MR', 'MV', 'MW', 'MX', 'MZ', 'NA', 'NE', 'NG',
				 'NI', 'NR', 'PA', 'PE', 'PG', 'PS', 'PW', 'PY', 'RE', 'RS', 'SB', 'SC',
				 'SD', 'SM', 'SN', 'SR', 'TD', 'TG', 'TM', 'TO', 'TP', 'TT', 'TZ', 'UY',
				 'UZ', 'VA', 'VC', 'VE', 'VI', 'VN', 'VU', 'YE', 'ZA', 'ZM', 'ZW', )
		),
	),
);

$forms_whitelist['cc-a'] = array(
	'file' => $form_dirs['gc'] . '/cc/cc-a.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'amex' )),
	'countries' => array(
		'+' => array_merge(
			$forms_whitelist['cc-vma']['countries']['+'],
			array() // as of right now, nothing specific here
		)
	),
);

$forms_whitelist['cc'] = array(
	'file' => $form_dirs['gc'] . '/cc/cc.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => 'ALL')
);


/****************************
 * Name and Email-Only Test *
 ****************************/

$forms_whitelist['email-cc-vmaj'] = $forms_whitelist['cc-vmaj'];
$forms_whitelist['email-cc-vma'] = $forms_whitelist['cc-vma'];
$forms_whitelist['email-cc-vm'] = $forms_whitelist['cc-vm'];

/*************************
 * Recurring Credit Card *
 *************************/

$forms_whitelist['rcc-vmad'] = array(
	'file' => $form_dirs['gc'] . '/rcc/rcc-vmad.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'amex', 'discover' )),
	'recurring',
	'countries' => array(
		'+' => $forms_whitelist['cc-vmad']['countries']['+']
	)
);

$forms_whitelist['rcc-vmaj'] = array(
	'file' => $form_dirs['gc'] . '/rcc/rcc-vmaj.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'amex', 'jcb' )),
	'recurring',
	'countries' => array(
		'+' => $forms_whitelist['cc-vmaj']['countries']['+']
	)
);

$forms_whitelist['rcc-vmd'] = array(
	'file' => $form_dirs['gc'] . '/rcc/rcc-vmd.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'discover' )),
	'recurring',
	'countries' => array(
		'+' => $forms_whitelist['cc-vmd']['countries']['+']
	)
);

$forms_whitelist['rcc-vmj'] = array(
	'file' => $form_dirs['gc'] . '/rcc/rcc-vmj.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'jcb' )),
	'recurring',
	'countries' => array(
		'+' => $forms_whitelist['cc-vmj']['countries']['+']
	)
);

$forms_whitelist['rcc-vma'] = array(
	'file' => $form_dirs['gc'] . '/rcc/rcc-vma.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'amex' )),
	'recurring',
	'countries' => array(
		'+' => $forms_whitelist['cc-vma']['countries']['+']
	)
);

$forms_whitelist['rcc-vm'] = array(
	'file' => $form_dirs['gc'] . '/rcc/rcc-vm.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc' )),
	'recurring',
	'countries' => array(
		'+' => $forms_whitelist['cc-vm']['countries']['+']
	)
);

$forms_whitelist['rcc'] = array(
	'file' => $form_dirs['gc'] . '/rcc/rcc.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => 'ALL'),
	'recurring'
);


//Yes: We definitely want to blow away everything that didn't come from this file. 
$wgDonationInterfaceAllowedHtmlForms = $forms_whitelist;
$wgDonationInterfaceFormDirs = $form_dirs;

unset( $forms_whitelist );
unset( $form_dirs );
unset( $wgGlobalCollectGatewayAllowedHtmlForms );
unset( $wgPayflowProGatewayAllowedHtmlForms );
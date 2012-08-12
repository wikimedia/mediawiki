<?php

/** Mapping of (country, currency, payment method) to gateways and forms **/
//This is going away as soon as I can wire in what I've just done with the way we define forms.

$wgDonationInterfaceFormMap = array(
	'ES' => array(
		'EUR' => array(
			'cc' => array(
				'gateways' => array( 'GlobalCollect' ),
				'forms' => array( 'cc-vmaj', 'cc-vma', 'cc-vm' )
			),
			// TODO: PayPal
//			'dd' => array(
//				'gateways' => array( 'GlobalCollect' ),
//				'forms' => array( 'dd-ES', )
//			),
			'bt' => array(
				'gateways' => array( 'GlobalCollect' ),
				'forms' => array( 'bt' )
			),
		)
	),
	'default' => array(
		'USD' => array(
			'cc' => array(
				'gateways' => array( 'GlobalCollect' ),
				'forms' => array( 'cc-vmad', 'cc-vma', 'cc-vm' )
			)
		)
	)
);

/** Additional DonationInterface Forms **/


/**
 * Some setup vars to make our lives a little easier. 
 * These are unset at the end of the file. 
 */
$forms_whitelist = array();
$form_dirs = array(
	'default' => $wgDonationInterfaceHtmlFormDir,
	'gc' => $wgGlobalCollectGatewayHtmlFormDir,
	'pfp' => $wgPayflowProGatewayHtmlFormDir,
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
	//'currencies' => array('+' => 'ALL'),
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
	'payment_methods' => array('ew' => 'ew_webmoney')
);

$forms_whitelist['ew-yandex'] = array(
	'file' => $form_dirs['gc'] . '/ew/ew-yandex.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('ew' => 'ew_yandex')
);


/*******************************
 * RealTime Banking - Two Step *
 *******************************/

$forms_whitelist['rtbt-sofo'] = array(
	'file' => $form_dirs['gc'] . '/rtbt/rtbt-sofo.html',
	'gateway' => 'globalcollect',
	'countries' => array( '-' => 'GB' ),
	'payment_methods' => array('rtbt' => 'rtbt_sofortuberweisung')
);

$forms_whitelist['rtbt-sofo-GB'] = array(
	'file' => $form_dirs['gc'] . '/rtbt/rtbt-sofo-GB.html',
	'gateway' => 'globalcollect',
	'countries' => array( '+' => 'GB' ),
	'payment_methods' => array('rtbt' => 'rtbt_sofortuberweisung')
);

$forms_whitelist['rtbt-ideal'] = array(
	'file' => $form_dirs['gc'] . '/rtbt/rtbt-ideal.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('rtbt' => 'rtbt_ideal')
);

$forms_whitelist['rtbt-enets'] = array(
	'file' => $form_dirs['gc'] . '/rtbt/rtbt-enets.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('rtbt' => 'rtbt_enets')
);

$forms_whitelist['rtbt-eps'] = array(
	'file' => $form_dirs['gc'] . '/rtbt/rtbt-eps.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('rtbt' => 'rtbt_eps')
);

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


/*************************
 * Recurring Credit Card *
 *************************/

$forms_whitelist['rcc'] = array(
	'file' => $form_dirs['gc'] . '/rcc/rcc.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => 'ALL')
);

$forms_whitelist['rcc-vm'] = array(
	'file' => $form_dirs['gc'] . '/rcc/rcc-vm.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc' ))
);

$forms_whitelist['rcc-vma'] = array(
	'file' => $form_dirs['gc'] . '/rcc/rcc-vma.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'amex' ))
);

$forms_whitelist['rcc-vmad'] = array(
	'file' => $form_dirs['gc'] . '/rcc/rcc-vmad.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'amex', 'discover' ))
);

$forms_whitelist['rcc-vmaj'] = array(
	'file' => $form_dirs['gc'] . '/rcc/rcc-vmaj.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'amex', 'jcb' ))
);

$forms_whitelist['rcc-vmd'] = array(
	'file' => $form_dirs['gc'] . '/rcc/rcc-vmd.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'discover' ))
);

$forms_whitelist['rcc-vmj'] = array(
	'file' => $form_dirs['gc'] . '/rcc/rcc-vmj.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'jcb' ))
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
	'payment_methods' => array('cc' => array( 'visa', 'mc' ))
);

$forms_whitelist['webitects2nd-US'] = array(
	'file' => $form_dirs['gc'] . '/webitects2nd-US.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc' ))
);

$forms_whitelist['webitects2nd_green-US'] = array(
	'file' => $form_dirs['gc'] . '/webitects2nd_green-US.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc' ))
);

$forms_whitelist['webitects2nd-amex'] = array(
	'file' => $form_dirs['gc'] . '/webitects2nd-amex.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'amex' ))
);


/**********************
 * Credit Card - Misc *
 **********************/

$forms_whitelist['cc'] = array(
	'file' => $form_dirs['gc'] . '/cc/cc.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => 'ALL')
);

$forms_whitelist['cc-a'] = array(
	'file' => $form_dirs['gc'] . '/cc/cc-a.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'amex' ))
);

$forms_whitelist['cc-vm'] = array(
	'file' => $form_dirs['gc'] . '/cc/cc-vm.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc' ))
);

$forms_whitelist['cc-vma'] = array(
	'file' => $form_dirs['gc'] . '/cc/cc-vma.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'amex' ))
);

$forms_whitelist['cc-vmd'] = array(
	'file' => $form_dirs['gc'] . '/cc/cc-vmd.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'discover' ))
);

$forms_whitelist['cc-vmad'] = array(
	'file' => $form_dirs['gc'] . '/cc/cc-vmad.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'amex', 'discover' ))
);

$forms_whitelist['cc-vmaj'] = array(
	'file' => $form_dirs['gc'] . '/cc/cc-vmaj.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'amex', 'jcb' ))
);

$forms_whitelist['cc-vmj'] = array(
	'file' => $form_dirs['gc'] . '/cc/cc-vmj.html',
	'gateway' => 'globalcollect',
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'jcb' ))
);

//ffname aliases
$forms_whitelist['cc-US'] = $forms_whitelist['cc-vmad'];
$forms_whitelist['cc-CA'] = $forms_whitelist['cc-vm'];
$forms_whitelist['cc-damv'] = $forms_whitelist['cc-vmad'];


/****************************
 * Name and Email-Only Test *
 ****************************/

$forms_whitelist['email-cc-vmaj'] = array(
	'file' => $form_dirs['gc'] . '/cc-emailonly/cc-vmaj.html',
	'gateway' => 'globalcollect',
	'countries' => array( '-' => array( 'US', 'CA', 'GB') ),
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'amex', 'jcb' ))
);

$forms_whitelist['email-cc-vma'] = array(
	'file' => $form_dirs['gc'] . '/cc-emailonly/cc-vma.html',
	'gateway' => 'globalcollect',
	'countries' => array( '-' => array( 'US', 'CA', 'GB') ),
	'payment_methods' => array('cc' => array( 'visa', 'mc', 'amex' ))
);

$forms_whitelist['email-cc-vm'] = array(
	'file' => $form_dirs['gc'] . '/cc-emailonly/cc-vm.html',
	'gateway' => 'globalcollect',
	'countries' => array( '-' => array( 'US', 'CA', 'GB') ),
	'payment_methods' => array('cc' => array( 'visa', 'mc' ))
);


//Yes: We definitely want to blow away everything that didn't come from this file. 
$wgDonationInterfaceAllowedHtmlForms = $forms_whitelist;
$wgDonationInterfaceFormDirs = $form_dirs;

unset( $forms_whitelist );
unset( $form_dirs );
unset( $wgGlobalCollectGatewayAllowedHtmlForms );
unset( $wgPayflowProGatewayAllowedHtmlForms );
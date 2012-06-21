<?php
/** Additional DonationInterface Forms **/

/**
 * Include optional form classes here. 
 * All these exist, but are disabled by default. 
 * Uncomment to enable.
 */


/**
 * DonationInterface RapidHTML whitelist additions
 * These will apply to all enabled adapters 
 */

/** Sync up with the gateways again, in such a way that we get all the clobal changes without ditching what we already have.  **/
if ( !isset( $wgDonationDataAllowedHtmlForms )) $wgDonationDataAllowedHtmlForms = array();
$wgGlobalCollectGatewayAllowedHtmlForms = array_merge( $wgGlobalCollectGatewayAllowedHtmlForms, $wgDonationDataAllowedHtmlForms );
$wgPayflowProGatewayAllowedHtmlForms = array_merge( $wgPayflowProGatewayAllowedHtmlForms, $wgDonationDataAllowedHtmlForms );

/**
 * GlobalCollect RapidHTML whitelist additions
 */

// default
$wgGlobalCollectGatewayAllowedHtmlForms['default'] = $wgGlobalCollectGatewayHtmlFormDir . '/cc/cc-vm.html';

// Bank Xfer - Two Step
$wgGlobalCollectGatewayAllowedHtmlForms['bt'] = $wgGlobalCollectGatewayHtmlFormDir . '/bt/bt.html';
$wgGlobalCollectGatewayAllowedHtmlForms['bt-CA'] = $wgGlobalCollectGatewayHtmlFormDir . '/bt/bt-CA.html';
$wgGlobalCollectGatewayAllowedHtmlForms['bt-US'] = $wgGlobalCollectGatewayHtmlFormDir . '/bt/bt-US.html';

//Electronic Wallet - Webmoney
$wgGlobalCollectGatewayAllowedHtmlForms['ew-webmoney'] = $wgGlobalCollectGatewayHtmlFormDir .'/ew/ew-webmoney.html';
$wgGlobalCollectGatewayAllowedHtmlForms['ew-yandex'] = $wgGlobalCollectGatewayHtmlFormDir .'/ew/ew-yandex.html';

// RealTime Banking - Two Step
$wgGlobalCollectGatewayAllowedHtmlForms['rtbt-sofo'] = $wgGlobalCollectGatewayHtmlFormDir . '/rtbt/rtbt-sofo.html';
$wgGlobalCollectGatewayAllowedHtmlForms['rtbt-sofo-GB'] = $wgGlobalCollectGatewayHtmlFormDir . '/rtbt/rtbt-sofo-GB.html';
$wgGlobalCollectGatewayAllowedHtmlForms['rtbt-ideal'] = $wgGlobalCollectGatewayHtmlFormDir . '/rtbt/rtbt-ideal.html';
$wgGlobalCollectGatewayAllowedHtmlForms['rtbt-enets'] = $wgGlobalCollectGatewayHtmlFormDir . '/rtbt/rtbt-enets.html';
$wgGlobalCollectGatewayAllowedHtmlForms['rtbt-eps'] = $wgGlobalCollectGatewayHtmlFormDir . '/rtbt/rtbt-eps.html';
$wgGlobalCollectGatewayAllowedHtmlForms['rtbt-ideal-noadd'] = $wgGlobalCollectGatewayHtmlFormDir . '/rtbt/rtbt-ideal-noadd.html';

//BPAY
$wgGlobalCollectGatewayAllowedHtmlForms['obt-bpay'] = $wgGlobalCollectGatewayHtmlFormDir . '/obt/obt-bpay.html';

// RCC
$wgGlobalCollectGatewayAllowedHtmlForms['rcc'] = $wgGlobalCollectGatewayHtmlFormDir . '/rcc/rcc.html';
$wgGlobalCollectGatewayAllowedHtmlForms['rcc-vm'] = $wgGlobalCollectGatewayHtmlFormDir . '/rcc/rcc-vm.html';
$wgGlobalCollectGatewayAllowedHtmlForms['rcc-vma'] = $wgGlobalCollectGatewayHtmlFormDir . '/rcc/rcc-vma.html';
$wgGlobalCollectGatewayAllowedHtmlForms['rcc-vmad'] = $wgGlobalCollectGatewayHtmlFormDir . '/rcc/rcc-vmad.html';
$wgGlobalCollectGatewayAllowedHtmlForms['rcc-vmaj'] = $wgGlobalCollectGatewayHtmlFormDir . '/rcc/rcc-vmaj.html';
$wgGlobalCollectGatewayAllowedHtmlForms['rcc-vmd'] = $wgGlobalCollectGatewayHtmlFormDir . '/rcc/rcc-vmd.html';
$wgGlobalCollectGatewayAllowedHtmlForms['rcc-vmj'] = $wgGlobalCollectGatewayHtmlFormDir . '/rcc/rcc-vmj.html';

// Credit Card - Single Step
//$wgGlobalCollectGatewayAllowedHtmlForms['webitects_2_3step'] = $wgGlobalCollectGatewayHtmlFormDir . '/webitects_2_3step.html';
//$wgGlobalCollectGatewayAllowedHtmlForms['webitects_2_3step-CA'] = $wgGlobalCollectGatewayHtmlFormDir . '/webitects_2_3step-CA.html';
//$wgGlobalCollectGatewayAllowedHtmlForms['webitects_2_3stepB-US'] = $wgGlobalCollectGatewayHtmlFormDir . '/webitects_2_3stepB-US.html';

// Credit Card - Two Step
$wgGlobalCollectGatewayAllowedHtmlForms['webitects2nd'] = $wgGlobalCollectGatewayHtmlFormDir . '/webitects2nd.html';
$wgGlobalCollectGatewayAllowedHtmlForms['webitects2nd-US'] = $wgGlobalCollectGatewayHtmlFormDir . '/webitects2nd-US.html';
$wgGlobalCollectGatewayAllowedHtmlForms['webitects2nd_green-US'] = $wgGlobalCollectGatewayHtmlFormDir . '/webitects2nd_green-US.html';
$wgGlobalCollectGatewayAllowedHtmlForms['webitects2nd-amex'] = $wgGlobalCollectGatewayHtmlFormDir . '/webitects2nd-amex.html';

//Credit Card - misc.
$wgGlobalCollectGatewayAllowedHtmlForms['cc'] = $wgGlobalCollectGatewayHtmlFormDir .'/cc/cc.html';
$wgGlobalCollectGatewayAllowedHtmlForms['cc-a'] = $wgGlobalCollectGatewayHtmlFormDir .'/cc/cc-a.html';
$wgGlobalCollectGatewayAllowedHtmlForms['cc-vm'] = $wgGlobalCollectGatewayHtmlFormDir .'/cc/cc-vm.html';
$wgGlobalCollectGatewayAllowedHtmlForms['cc-vma'] = $wgGlobalCollectGatewayHtmlFormDir .'/cc/cc-vma.html';
$wgGlobalCollectGatewayAllowedHtmlForms['cc-vmd'] = $wgGlobalCollectGatewayHtmlFormDir .'/cc/cc-vmd.html';
$wgGlobalCollectGatewayAllowedHtmlForms['cc-vmad'] = $wgGlobalCollectGatewayHtmlFormDir .'/cc/cc-vmad.html';
$wgGlobalCollectGatewayAllowedHtmlForms['cc-vmaj'] = $wgGlobalCollectGatewayHtmlFormDir .'/cc/cc-vmaj.html';
$wgGlobalCollectGatewayAllowedHtmlForms['cc-vmj'] = $wgGlobalCollectGatewayHtmlFormDir .'/cc/cc-vmj.html';

$wgGlobalCollectGatewayAllowedHtmlForms['cc-damv'] = $wgGlobalCollectGatewayHtmlFormDir .'/cc/cc-vmad.html';

$wgGlobalCollectGatewayAllowedHtmlForms['cc-US'] = $wgGlobalCollectGatewayHtmlFormDir .'/cc/cc-vmad.html';
$wgGlobalCollectGatewayAllowedHtmlForms['cc-CA'] = $wgGlobalCollectGatewayHtmlFormDir .'/cc/cc-vm.html';


/**
 * PayflowPro RapidHTML whitelist additions
 */
//The following example is hard-coded as a default. Just a sample line. 
//$wgPayflowProGatewayAllowedHtmlForms['lightbox1'] = $wgPayflowProGatewayHtmlFormDir .'/lightbox1.html';
// Credit Card - Single Step
//$wgPayflowProGatewayAllowedHtmlForms['webitects_2_3step'] = $wgPayflowProGatewayHtmlFormDir .'/webitects_2_3step.html';
$wgPayflowProGatewayAllowedHtmlForms['webitects_2_3step-CA'] = $wgPayflowProGatewayHtmlFormDir .'/webitects_2_3step-CA.html';

// Credit Card - Two Step
$wgPayflowProGatewayAllowedHtmlForms['webitects_2_2step-US'] = $wgPayflowProGatewayHtmlFormDir .'/webitects_2_2step-US.html';
$wgPayflowProGatewayAllowedHtmlForms['webitects_2_2stepB-US'] = $wgPayflowProGatewayHtmlFormDir .'/webitects_2_2stepB-US.html';
$wgPayflowProGatewayAllowedHtmlForms['webitects2nd_green-US'] = $wgPayflowProGatewayHtmlFormDir . '/webitects2nd_green-US.html';

$wgPayflowProGatewayAllowedHtmlForms['TwoStepTwoColumnLetter3'] = $wgPayflowProGatewayHtmlFormDir . '/TwoStepTwoColumnLetter3.html';
$wgPayflowProGatewayAllowedHtmlForms['TwoStepTwoColumnLetter3-legal'] = $wgPayflowProGatewayHtmlFormDir . '/TwoStepTwoColumnLetter3-legal.html';
$wgPayflowProGatewayAllowedHtmlForms['TwoStepTwoColumnLetter3-nolabels'] = $wgPayflowProGatewayHtmlFormDir . '/TwoStepTwoColumnLetter3-nolabels.html';
$wgPayflowProGatewayAllowedHtmlForms['TwoStepTwoColumnLetter3-order'] = $wgPayflowProGatewayHtmlFormDir . '/TwoStepTwoColumnLetter3-order.html';
$wgPayflowProGatewayAllowedHtmlForms['TwoStepTwoColumnLetter3-noheader'] = $wgPayflowProGatewayHtmlFormDir . '/TwoStepTwoColumnLetter3-noheader.html';
$wgPayflowProGatewayAllowedHtmlForms['TwoStepTwoColumnLetter3-simpleamount'] = $wgPayflowProGatewayHtmlFormDir . '/TwoStepTwoColumnLetter3-simpleamount.html';
$wgPayflowProGatewayAllowedHtmlForms['TwoStepTwoColumnLetter3-smallbutton'] = $wgPayflowProGatewayHtmlFormDir . '/TwoStepTwoColumnLetter3-smallbutton.html';
$wgPayflowProGatewayAllowedHtmlForms['TwoStepTwoColumnLetter3-orig'] = $wgPayflowProGatewayHtmlFormDir . '/TwoStepTwoColumnLetter3-orig.html';
$wgPayflowProGatewayAllowedHtmlForms['lightbox1'] = $wgPayflowProGatewayHtmlFormDir .'/lightbox1.html';

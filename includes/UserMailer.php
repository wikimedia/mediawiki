<?php

# This function will perform a direct (authenticated) login to
# a SMTP Server to use for mail relaying if 'wgSMTP' specifies an
# array of parameters. It requires PEAR:Mail to do that.
# Otherwise it just uses the standard PHP 'mail' function.
function userMailer( $to, $from, $subject, $body )
{
	global $wgUser, $wgSMTP, $wgOutputEncoding, $wgErrorString;
	
  	$qto = wfQuotedPrintable( $to );
	
	if (is_array( $wgSMTP ))
	{
		require_once( "Mail.php" );
		
		$timestamp = time();
	
		$headers["From"] = $from;
/* removing to: field as it should be set by the send() function below
   UNTESTED - Hashar */
//		$headers["To"] = $qto;
		$headers["Subject"] = $subject;
		$headers["MIME-Version"] = "1.0";
		$headers["Content-type"] = "text/plain; charset={$wgOutputEncoding}";
		$headers["Content-transfer-encoding"] = "8bit";
		$headers["Message-ID"] = "<{$timestamp}" . $wgUser->getName() . "@" . $wgSMTP["IDHost"] . ">";
		$headers["X-Mailer"] = "MediaWiki interuser e-mailer";
	
		// Create the mail object using the Mail::factory method
		$mail_object =& Mail::factory("smtp", $wgSMTP);
	
		$mailResult =& $mail_object->send($to, $headers, $body);
		
		# Based on the result return an error string, 
		if ($mailResult === true)
			return "";
		else if (is_object($mailResult))
			return $mailResult->getMessage();
		else
			return "Mail object return unknown error.";
	}
	
	else
	{
		$headers =
			"MIME-Version: 1.0\n" .
			"Content-type: text/plain; charset={$wgOutputEncoding}\n" .
			"Content-transfer-encoding: 8bit\n" .
			"From: {$from}\n" .
			"X-Mailer: MediaWiki interuser e-mailer";

		$wgErrorString = "";
		set_error_handler( "mailErrorHandler" );
		mail( $to, $subject, $body, $headers );
		restore_error_handler();

		return $wgErrorString;
	}
}

function mailErrorHandler( $code, $string ) {
	global $wgErrorString;
	$wgErrorString = preg_replace( "/^mail\(\): /", "", $string );
}

?>

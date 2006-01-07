<?php
/* ProfilerSimpleUDP class, that sends out messages for 'udpprofile' daemon
   (the one from wikipedia/udpprofile CVS )
*/

require_once(dirname(__FILE__).'/Profiling.php');
require_once(dirname(__FILE__).'/ProfilerSimple.php');

class ProfilerSimpleUDP extends ProfilerSimple {
	function getFunctionReport() {
		global $wgUDPProfilerHost;
		global $wgUDPProfilerPort;
		global $wgDBname;

		$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		$plength=0;
		$packet="";
		foreach ($this->mCollated as $entry=>$pfdata) {
			$pfline=sprintf ("%s %s %d %f %f %f %f %s\n", $wgDBname,"-",$pfdata['count'],
				$pfdata['cpu'],$pfdata['cpu_sq'],$pfdata['real'],$pfdata['real_sq'],$entry);
			$length=strlen($pfline);
			/* printf("<!-- $pfline -->"); */
			if ($length+$plength>1400) {
				socket_sendto($sock,$packet,$plength,0,$wgUDPProfilerHost,$wgUDPProfilerPort);
				$packet="";
				$plength=0;
			}
			$packet.=$pfline;
			$plength+=$length;
		}
		socket_sendto($sock,$packet,$plength,0x100,$wgUDPProfilerHost,$wgUDPProfilerPort);
	}
}
?>

/*

Copyright 2008 Tor-Einar Jarnbjo

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.

*/

package org.omtk.vorbis {

	import flash.utils.getTimer;
	import flash.utils.ByteArray;
	import org.omtk.ogg.*;
	import org.omtk.util.BitByteArray;

	public class AudioPacket {
	
		private var modeNumber:int;
		private var mode:Mode;
		private var mapping:Mapping;
		private var n:int;
		
		private var blockFlag:Boolean;
		private var previousWindowFlag:Boolean;
		private var nextWindowFlag:Boolean;

		private var windowCenter:int;
		private var leftWindowStart:int;
		private var leftWindowEnd:int;
		private var leftN:int;
		private var rightWindowStart:int;
		private var rightWindowEnd:int;
		private var rightN:int;
		
		private var window:Vector.<Number>;
		private var freq0:Vector.<Number>;
		private var freq1:Vector.<Number>;
		private var pcm0:Vector.<Number>;
		private var pcm1:Vector.<Number>;
		
		private var channelFloors:Vector.<Floor>;
		private var noResidues:Vector.<Boolean>;		

		private var _lastPacket: Boolean;
		private var _lastGranulePosition: int;

		public function AudioPacket(vorbis:VorbisStream, packet: OggPacket, currentGranulePosition: int) {
		
			var source: BitByteArray = packet.data;
			_lastPacket = packet.lastPacket;
			_lastGranulePosition = packet.lastGranulePosition;
			
			var i:int;
			var j:int;
			var k:int;
	
			var sHeader:SetupHeader = vorbis.setupHeader;
			var iHeader:IdentificationHeader = vorbis.identificationHeader;
			var modes:Vector.<Mode> = sHeader.modes;
			var mappings:Vector.<Mapping> = sHeader.mappings;
			var residues:Vector.<Residue> = sHeader.residues;
			var channels:int = iHeader.channels;
	
			if (source.readUnsignedBitwiseInt(1) != 0) {
				throw new Error("Packet type mismatch when trying to create an audio packet.");
			}
	
			modeNumber = source.readUnsignedBitwiseInt(Util.ilog(modes.length - 1));
	
			mode = modes[modeNumber];
			
			if(mode == null) {
				throw new Error("Reference to invalid mode in audio packet.");
			}
	
			mapping = mappings[mode.mapping];
	
			var magnitudes:Vector.<int> = mapping.magnitudes;
			var angles:Vector.<int> = mapping.angles;
	
			blockFlag = mode.blockFlag;
	
			var blockSize0:int = iHeader.blockSize0;
			var blockSize1:int = iHeader.blockSize1;
	
			n = blockFlag ? blockSize1 : blockSize0;
	
			if (blockFlag) {
				previousWindowFlag = source.readBit();
				nextWindowFlag = source.readBit();
			}
	
			windowCenter = n / 2;
	
			if (blockFlag && !previousWindowFlag) {
				leftWindowStart = n / 4 - blockSize0 / 4;
				leftWindowEnd = n / 4 + blockSize0 / 4;
				leftN = blockSize0 / 2;
			} else {
				leftWindowStart = 0;
				leftWindowEnd = n / 2;
				leftN = windowCenter;
			}
	
			if (blockFlag && !nextWindowFlag) {
				rightWindowStart = n * 3 / 4 - blockSize0 / 4;
				rightWindowEnd = n * 3 / 4 + blockSize0 / 4;
				rightN = blockSize0 / 2;
			} else {
				rightWindowStart = windowCenter;
				rightWindowEnd = n;
				rightN = n / 2;
			}
	
			window = getComputedWindow(vorbis);
	
			channelFloors = new Vector.<Floor>(channels, true);
			noResidues = new Vector.<Boolean>(channels, true);
	
			freq0 = new Vector.<Number>(n, true);
			freq1 = new Vector.<Number>(n, true);
			pcm0 = new Vector.<Number>(n, true);
			pcm1 = new Vector.<Number>(n, true);

			var allFloorsEmpty: Boolean = true;
			var submapNumber: int;
			var floorNumber: int;
			var decodedFloor: Floor;
	
			for(i = 0; i < channels; i++) {
				submapNumber = mapping.mux[i];
				floorNumber = mapping.submapFloors[submapNumber];
				decodedFloor = sHeader.floors[floorNumber].decodeFloor(vorbis, source);
				channelFloors[i] = decodedFloor;
				noResidues[i] = decodedFloor == null;
				if (decodedFloor != null) {
					allFloorsEmpty = false;
				}
			}
	
			if(allFloorsEmpty) {
				return;
			}
			
			var mag: int;
			var ang: int;

			for(i = 0; i < magnitudes.length; i++) {
				mag = magnitudes[i];
				ang = angles[i];
				if (!noResidues[mag] || !noResidues[ang]) {
					noResidues[mag] = false;
					noResidues[ang] = false;
				}
			}
	
			var ch: int;
			var doNotDecodeFlags: Vector.<Boolean>;
			var residue:Residue;
			
			for(i = 0; i < mapping.submaps; i++) {
			
				doNotDecodeFlags = new Vector.<Boolean>();
				
				for(j = 0; j < channels; j++) {
					if(mapping.mux[j] == i) {
						doNotDecodeFlags.push(noResidues[j]);
					}
				}

				residue = residues[mapping.submapResidues[i]];
				
				residue.decodeResidue(vorbis, source, mode, ch, doNotDecodeFlags, freq0, freq1);
			}

			var a: Number;
			var m: Number;
	
			for(i = mapping.couplingSteps - 1; i >= 0; i--) {
			
				mag = magnitudes[i];
				ang = angles[i];
				
				for (j = 0; j < freq0.length; j++) {
					
					a = ang == 0 ? freq0[j] : freq1[j];
					m = mag == 0 ? freq0[j] : freq1[j];
					
					if(a > 0) {
						if(ang == 0) {
							freq0[j] = m > 0 ? m - a : m + a;
						}
						else {
							freq1[j] = m > 0 ? m - a : m + a;
						}
					}
					else {
						if(mag == 0) {
							freq0[j] = m > 0 ? m + a : m - a;
						}
						else {
							freq1[j] = m > 0 ? m + a : m - a;
						}
						
						if(ang == 0) {
							freq0[j] = m;
						}
						else {
							freq1[j] = m;
						}
					}
				}
			}
			
			if(channelFloors[0] != null) {
				Floor(channelFloors[0]).computeFloor(freq0);
			}
			
			if(channelFloors[1] != null) {
				Floor(channelFloors[1]).computeFloor(freq1);
			}

			// perform an inverse mdct to all channels
			var mdct: Mdct = blockFlag ? iHeader.mdct1 : iHeader.mdct0;
			mdct.imdct(freq0, window, pcm0);
			mdct.imdct(freq1, window, pcm1);

			if(_lastPacket) {
				if(leftWindowEnd - leftWindowStart > _lastGranulePosition - currentGranulePosition) {
					leftWindowEnd = leftWindowStart + _lastGranulePosition - currentGranulePosition
				}
				if(rightWindowStart - leftWindowStart > _lastGranulePosition - currentGranulePosition) {
					rightWindowStart = leftWindowStart + _lastGranulePosition - currentGranulePosition
				}
			}

		}	
	
		private function getComputedWindow(vorbis:VorbisStream):Vector.<Number> {
			
			var i:int;
			
			var ix:int = (blockFlag ? 4 : 0) + (previousWindowFlag ? 2 : 0)	+ (nextWindowFlag ? 1 : 0);
			var w:Vector.<Number> = vorbis.windows[ix];
			
			var x:Number;
			
			if (w == null) {
				w = new Vector.<Number>(n);
	
				for(i = 0; i < leftWindowStart; i++) {
					w[i] = 0;
				}
	
				for (i = 0; i < leftN; i++) {
					x = (i + .5) / leftN * Math.PI / 2.;
					x = Math.sin(x);
					x *= x;
					x *= Math.PI / 2.;
					x = Math.sin(x);
					w[i + leftWindowStart] = x;
				}
	
				for (i = leftWindowEnd; i < rightWindowStart; i++) {
					w[i] = 1;
				}
	
				for (i = 0; i < rightN; i++) {
					x = (rightN - i - .5) / rightN * Math.PI / 2.;
					x = Math.sin(x);
					x *= x;
					x *= Math.PI / 2.;
					x = Math.sin(x);
					w[i + rightWindowStart] = x;
				}
	
				for(i = rightN + rightWindowStart; i < n; i++) {
					w[i] = 0;
				}

				for(i = 0; i < w.length; i++) {
					w[i] *= 0.5;
				}
	
				vorbis.windows[ix] = w;
			}
			
			return w;
		}	
		
		
		public function readPcm(previousPacket:AudioPacket, target: ByteArray): int {
		
			var j:int;
			var j2:int;
			var ppcm0:Vector.<Number> = previousPacket.pcm0;
			var ppcm1:Vector.<Number> = previousPacket.pcm1;
			
			j2 = previousPacket.rightWindowStart;
			
			for(j = leftWindowStart; j < leftWindowEnd; j++) {
				target.writeFloat(pcm0[j] + ppcm0[j2]);
				target.writeFloat(pcm1[j] + ppcm1[j2++]);
			}

			for (j = leftWindowEnd; j < rightWindowStart; j++) {
				target.writeFloat(pcm0[j]);
				target.writeFloat(pcm1[j]);
			}

			return numberOfSamples;

		}

		public function get numberOfSamples():int {
			return rightWindowStart - leftWindowStart;
		}

		public function get lastPacket(): Boolean {
			return lastPacket;
		}
		
		public function get lastGranulePosition(): int {
			return lastGranulePosition;
		}
	
	}


}
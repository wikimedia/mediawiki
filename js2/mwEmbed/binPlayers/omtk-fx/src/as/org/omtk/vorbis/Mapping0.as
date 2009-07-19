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

	import flash.errors.IllegalOperationError;
	import org.omtk.util.BitByteArray;

	public class Mapping0 extends Mapping {
	
		private var _magnitudes:Vector.<int>;
		private var _angles:Vector.<int>;
		private var _mux:Vector.<int>;
		private var _submapFloors:Vector.<int>;
		private var _submapResidues:Vector.<int>;
	
		public function Mapping0(stream:VorbisStream, source:BitByteArray, header:SetupHeader) {

			var i:int;
			var j:int;
		
			var submaps:int = 1;
	
			if (source.readBit()) {
				submaps = source.readUnsignedBitwiseInt(4) + 1;
			}
	
			var channels:int = stream.identificationHeader.channels;
			var ilogChannels:int = Util.ilog(channels - 1);
	
			if (source.readBit()) {
				var couplingSteps:int = source.readUnsignedBitwiseInt(8) + 1;
				_magnitudes = new Vector.<int>(couplingSteps);
				_angles = new Vector.<int>(couplingSteps);
	
				for (i = 0; i < couplingSteps; i++) {
					magnitudes[i] = source.readUnsignedBitwiseInt(ilogChannels);
					angles[i] = source.readUnsignedBitwiseInt(ilogChannels);
					if (magnitudes[i] == angles[i] || magnitudes[i] >= channels
							|| angles[i] >= channels) {
						throw new Error("The channel magnitude and/or angle mismatch.");
					}
				}
			} else {
				_magnitudes = Vector.<int>([]);
				_angles = Vector.<int>([]);
			}
	
			if (source.readUnsignedBitwiseInt(2) != 0) {
				throw new Error("A reserved mapping field has an invalid value.");
			}
	
			_mux = new Vector.<int>(channels);
			if (submaps > 1) {
				for (i = 0; i < channels; i++) {
					_mux[i] = source.readUnsignedBitwiseInt(4);
					if (_mux[i] > submaps) {
						throw new Error("A mapping mux value is higher than the number of submaps");
					}
				}
			} else {
				for (i = 0; i < channels; i++) {
					_mux[i] = 0;
				}
			}
	
			_submapFloors = new Vector.<int>(submaps);
			_submapResidues = new Vector.<int>(submaps);
	
			var floorCount:int = header.floors.length;
			var residueCount:int = header.residues.length;
	
			for (i = 0; i < submaps; i++) {
				source.readUnsignedBitwiseInt(8); // discard time placeholder
				_submapFloors[i] = source.readUnsignedBitwiseInt(8);
				_submapResidues[i] = source.readUnsignedBitwiseInt(8);
	
				if (_submapFloors[i] > floorCount) {
					throw new Error("A mapping floor value is higher than the number of floors.");
				}
	
				if (_submapResidues[i] > residueCount) {
					throw new Error("A mapping residue value is higher than the number of residues.");
				}
			}
		}
	
		public override function get type():int {
			return 0;
		}
	
		public override function get couplingSteps():int {
			return _angles.length;
		}

		public override function get submaps():int {
			return _submapFloors.length;
		}

		public override function get angles():Vector.<int> {
			return _angles;
		}

		public override function get magnitudes():Vector.<int> {
			return _magnitudes;
		}

		public override function get mux():Vector.<int> {
			return _mux;
		}

		public override function get submapFloors():Vector.<int> {
			return _submapFloors;
		}

		public override function get submapResidues():Vector.<int> {
			return _submapResidues;
		}	
	}

}
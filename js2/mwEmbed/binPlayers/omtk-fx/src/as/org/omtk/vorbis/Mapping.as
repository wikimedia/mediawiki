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

	public class Mapping {
	
		public static function createInstance(stream:VorbisStream, source:BitByteArray, header:SetupHeader):Mapping {
		
			var type:int = source.readUnsignedBitwiseInt(16);
			switch (type) {
			case 0:
				return new Mapping0(stream, source, header);
			default:
				throw new Error("Mapping type " + type + " is not supported.");
			}
		
		}
	
		public function get type():int {
			throw new IllegalOperationError("not implemented");
		}
	
		public function get couplingSteps():int {
			throw new IllegalOperationError("not implemented");
		}

		public function get submaps():int {
			throw new IllegalOperationError("not implemented");
		}

		public function get angles():Vector.<int> {
			throw new IllegalOperationError("not implemented");
		}

		public function get magnitudes():Vector.<int> {
			throw new IllegalOperationError("not implemented");
		}

		public function get mux():Vector.<int> {
			throw new IllegalOperationError("not implemented");
		}

		public function get submapFloors():Vector.<int> {
			throw new IllegalOperationError("not implemented");
		}

		public function get submapResidues():Vector.<int> {
			throw new IllegalOperationError("not implemented");
		}

	
	}

}
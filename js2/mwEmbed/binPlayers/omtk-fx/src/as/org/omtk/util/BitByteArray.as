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

package org.omtk.util {

	import flash.utils.ByteArray;
	import flash.utils.Endian;
	import flash.errors.IllegalOperationError;
	
	public class BitByteArray extends ByteArray {
	
		private var currentByte:uint;
		private var bitIndex:int = 8;
	
		public function BitByteArray() {
			this.endian = Endian.LITTLE_ENDIAN;
		}

		public function readBit():Boolean {
			if (bitIndex > 7) {
				bitIndex = 0;
				currentByte = readUnsignedByte();
			}
			return (currentByte & (1 << (bitIndex++))) != 0;
		}
		
		public function readUnsignedBitwiseInt(bits:int):uint {
	
			var res:uint = 0;
			
			for (var i : int = 0; i < bits; i++) {
				if (bitIndex > 7) {
					bitIndex = 0;
					currentByte = readUnsignedByte();
				}
				if((currentByte & (1 << (bitIndex++))) != 0) {
					res |= (1 << i);
				}
			}
			
			return res;
		}	

		public function readUnsignedHuffmanInt(root:HuffmanNode):uint {

			while (!root.hasValue) {
				if (bitIndex > 7) {
					bitIndex = 0;
					currentByte = readUnsignedByte();
				}
				root = (currentByte & (1 << (bitIndex++))) != 0 ? root._o1 : root._o0;
			}
			
			return root._value;			
		}
	
	}
	

}
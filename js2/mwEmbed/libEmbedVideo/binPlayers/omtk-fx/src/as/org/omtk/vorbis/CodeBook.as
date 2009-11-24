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
	import org.omtk.util.*;

	public class CodeBook {

		private var entries:uint;
		private var entryLengths:Vector.<int>;
		private var valueVector:Vector.<Vector.<Number>>;

		private var codeBookLookupType:int = -1;

		public var huffmanRoot:HuffmanNode;
		public var dimensions:uint;

		public function CodeBook( source: BitByteArray ) {

			var i:int;
			var j:int;
		
			var syncPattern:uint = source.readUnsignedBitwiseInt(24);
			if(syncPattern !=0x564342) {
				throw new IllegalOperationError("Illegal codebook sync pattern: "+syncPattern);
			}
		
			dimensions = source.readUnsignedBitwiseInt(16);
			entries = source.readUnsignedBitwiseInt(24);
			
			entryLengths = new Vector.<int>(entries);
			
			var ordered:Boolean = source.readBit();
			
			if(ordered) {
				var cl:int = source.readUnsignedBitwiseInt(5)+1;
				for(i=0; i<entryLengths.length; ) {
					var num:int = source.readUnsignedBitwiseInt(Util.ilog(entryLengths.length-i));
					if(i+num>entryLengths.length) {
						throw new Error("The codebook entry length list is longer than the actual number of entry lengths.");
					}
					for(j=i; j<i+num; j++) {
						entryLengths[j] = cl;
					}
					//Arrays.fill(entryLengths, i, i+num, cl);
					cl++;
					i+=num;
				}
			}
			else {
				// !ordered
				var sparse:Boolean = source.readBit();
				
				if(sparse) {
					for(i=0; i<entryLengths.length; i++) {
						if(source.readBit()) {
							entryLengths[i]=source.readUnsignedBitwiseInt(5)+1;
						}
						else {
							entryLengths[i]=-1;
						}
					}
				}
				else {
					// !sparse
					//Alert.show("entryLengths.length: "+entryLengths.length, "CodeBook");
					for(i=0; i<entryLengths.length; i++) {
						entryLengths[i]=source.readUnsignedBitwiseInt(5)+1;
					}
				}
			
			}
			
			if (!createHuffmanTree(entryLengths)) {
				throw new Error("An exception was thrown when building the codebook Huffman tree.");
			}	

			codeBookLookupType = source.readUnsignedBitwiseInt(4);

			switch(codeBookLookupType) {
			case 0:
				// codebook has no scalar vectors to be calculated
				break;
			case 1:
			case 2:
				var codeBookMinimumValue:Number = Util.float32unpack(source.readUnsignedBitwiseInt(32));
				var codeBookDeltaValue:Number = Util.float32unpack(source.readUnsignedBitwiseInt(32));

				var codeBookValueBits:uint = source.readUnsignedBitwiseInt(4)+1;
				var codeBookSequenceP:Boolean = source.readBit();

				var codeBookLookupValues:uint = 0;

				if(codeBookLookupType==1) {
					codeBookLookupValues=Util.lookup1Values(entries, dimensions);
				}
				else {
					codeBookLookupValues=entries*dimensions;
				}

				var codeBookMultiplicands:Vector.<int> = new Vector.<int>(codeBookLookupValues);

				for(i=0; i < codeBookMultiplicands.length; i++) {
					codeBookMultiplicands[i]=source.readUnsignedBitwiseInt(codeBookValueBits);
				}

				valueVector = new Vector.<Vector.<Number>>(entries);

				if(codeBookLookupType==1) {
					for(i=0; i<entries; i++) {
						valueVector[i] = new Vector.<Number>(dimensions);
						var last:Number = 0.0;
						var indexDivisor:uint = 1;
						for(j=0; j<dimensions; j++) {
							var multiplicandOffset:int = (i/indexDivisor)%codeBookLookupValues;
							valueVector[i][j]=
								codeBookMultiplicands[multiplicandOffset]*codeBookDeltaValue+codeBookMinimumValue+last;
							if(codeBookSequenceP) {
								last = valueVector[i][j];
							}
							indexDivisor*=codeBookLookupValues;
						}
					}
				}
				else {
					throw new Error("Unsupported codebook lookup type: "+codeBookLookupType);
					/** @todo implement */
				}
				break;
			default:
				throw new Error("Unsupported codebook lookup type: "+codeBookLookupType);
			}
		}
	
		private function createHuffmanTree(entryLengths:Vector.<int>):Boolean {
		
			var i:int;
		
			huffmanRoot = new HuffmanNode();
			for(i=0; i<entryLengths.length; i++) {
				var el:int = entryLengths[i];
				if(el>0) {
					if(!huffmanRoot.setNewValue(el, i)) {
						return false;
					}
				}
			}
			return true;
   		}


		public function readVvAdd(a0:Vector.<Number>, a1:Vector.<Number>, left:Boolean, right:Boolean, source:BitByteArray, offset:int,	length:int):void {
	
			var i:int;
			var j:int;
	
			if (!left && !right) {
				return;
			}

			// 1 or 2 channels	
			var ch:int = 
				left && right ? 2 : 1;

			var lim:int;
			var ix:int;
			var ve:Vector.<Number>;
			
			if(left && right) {
				lim = (offset + length) / 2;
				var chptr:int = 0;
				for (i = offset / 2; i < lim;) {
					ix = source.readUnsignedHuffmanInt(huffmanRoot);
					ve = valueVector[ix];
					for (j = 0; j < dimensions; j++) {
						if(chptr == 0) {
							a0[i] += ve[j];
						}
						else {
							a1[i] += ve[j];
						}
						chptr++;
						if(chptr == 2) {
							chptr = 0;
							i++;
						}
					}
				}
			}
			else {
				var a : Vector.<Number> = left ? a0 : a1;
				lim = offset + length;
				for (i = offset; i < lim;) {
					ve = valueVector[source.readUnsignedHuffmanInt(huffmanRoot)];
					for (j = 0; j < dimensions; j++) {
						a[i] += ve[j];
						i++;
					}
				}
			}

		}
		
	}
	
}
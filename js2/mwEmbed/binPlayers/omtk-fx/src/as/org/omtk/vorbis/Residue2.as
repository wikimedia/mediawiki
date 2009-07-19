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
	import flash.utils.Dictionary;
	import org.omtk.util.BitByteArray;

	public class Residue2 extends Residue {
	
		public function Residue2(source:BitByteArray, header:SetupHeader) {
			super(source, header);
		}

		public override function decodeResidue(
			vorbis:VorbisStream, source:BitByteArray, 
			mode:Mode, ch:int,
			doNotDecodeFlags:Vector.<Boolean>, vectors0:Vector.<Number>, vectors1:Vector.<Number>):void {

			var i:int;
			var j:int;
			var k:int;
			var l:int;
			var s:int;
			var slim:int;

			var look:Look = getLook(vorbis, mode);
	
			var codeBook:CodeBook = vorbis.setupHeader.codeBooks[classBook];
	
			var classvalsPerCodeword:int = codeBook.dimensions;
			var nToRead:int = end - begin;
			var partitionsToRead:int = nToRead / partitionSize; // partvals
	
			var samplesPerPartition:int = partitionSize;
			var partitionsPerWord:int = look.phrasebook.dimensions;
	
			var partWords:int = (partitionsToRead + partitionsPerWord - 1) / partitionsPerWord;
	
			var offset:int;
			
			var left:Boolean = false;
			var right:Boolean = false;
			
			for (i = 0; i < doNotDecodeFlags.length; i++) {
				if (!doNotDecodeFlags[i]) {
					if(i==0) {
						left = true;
					}
					else if (i==1) {
						right = true;
					}
				}
			}
	
			var partword:Array = new Array(partWords);
			
			var pb:int = source.position;
			
			slim = look.stages;
			for (s = 0; s < slim; s++) {
				
				for (i = 0, l = 0; i < partitionsToRead; l++) {
				
					if (s == 0) {
						var temp:int = source.readUnsignedHuffmanInt(look.phrasebook.huffmanRoot);
						if (temp == -1) {
							throw new Error("Foo??");
						}
						partword[l] = look.decodemap[temp];
						if (partword[l] == null) {
							throw new Error("Foo??");
						}
					}
	
					for (k = 0; k < partitionsPerWord && i < partitionsToRead; k++, i++) {
						offset = begin + i * samplesPerPartition;

						if ((cascade[partword[l][k]] & (1 << s)) != 0) {
							var stagebook:CodeBook = 
								vorbis.setupHeader.codeBooks[look.partbooks[partword[l][k]][s]];
							if (stagebook != null) {
								stagebook.readVvAdd(vectors0, vectors1, left, right, source, offset, samplesPerPartition);
							}
						}
					}
				}
			}
		
		}
		
	}
}
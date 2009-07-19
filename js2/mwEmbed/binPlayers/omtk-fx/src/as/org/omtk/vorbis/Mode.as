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
	
	public class Mode
	{
		private var _blockFlag:Boolean;
		private var _windowType:uint;
		private var _transformType:uint;
		private var _mapping:uint;
		
		public function Mode(source:BitByteArray, header:SetupHeader) {

			_blockFlag=source.readBit();
			_windowType=source.readUnsignedBitwiseInt(16);
			_transformType=source.readUnsignedBitwiseInt(16);
			_mapping=source.readUnsignedBitwiseInt(8);
			
			if(_windowType!=0) {
				throw new Error("Window type = "+windowType+", != 0");
			}
			
			if(_transformType!=0) {
				throw new Error("Transform type = "+transformType+", != 0");
			}
			
			if(_mapping > header.mappings.length) {
				throw new Error("Mode mapping number is higher than total number of mappings.");
			}
		}
	
		public function get blockFlag():Boolean {
			return _blockFlag;
		}
		
		public function get windowType():uint {
			return _windowType;
		}
		
		public function get transformType():uint {
			return _transformType;
		}
		
		public function get mapping():uint {
			return _mapping;
		}
	
	}
	
}
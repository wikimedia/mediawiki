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

	public class HuffmanNode {
	
		private var _parent:HuffmanNode;
	
		public var _o0:HuffmanNode;
		public var _o1:HuffmanNode;
		
		private var _depth:int;
	
		public var _value:int;
		public var hasValue: Boolean;
		private var _full:Boolean = false;
	
		public function HuffmanNode(parent:HuffmanNode = null, value:int = -1) {
			_parent = parent;
			if(_parent != null) {
				_depth = _parent.depth+1;
			}
			_value = value;			
			_full = value >= 0;
			hasValue = value >= 0;
		}
	
		public function setNewValue(depth:int, value:uint):Boolean {
	      if (full) {
	         return false;
	      }
	      if (depth == 1) {
	         if (_o0 == null) {
	            _o0 = new HuffmanNode(this, value);
	            return true;
	         } else if (_o1 == null) {
	            _o1 = new HuffmanNode(this, value);
	            return true;
	         } else {
	            return false;
	         }
	      } else {
	         return o0.setNewValue(depth - 1, value)
	         	? true : o1.setNewValue(depth - 1, value);
	      }
	   }	
	
		public function get value():uint {
			return _value;
		}
	
		public function get o0():HuffmanNode {
			if(_o0 == null) {
				_o0 = new HuffmanNode(this);
			}
			return _o0;
		}

		public function get o1():HuffmanNode {
			if(_o1 == null) {
				_o1 = new HuffmanNode(this);
			}
			return _o1;
		}
	
		public function get depth():int {
			return _depth;
		}
		
		public function get full():Boolean {
			return _full ? true
      			: (_full = (_o0 != null && _o0.full && _o1 != null && _o1.full));
		}
		
	}
	
}
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

	public class Util {

   		public static function ilog(x:uint):uint {
			var res:int = 0;
			for(; x>0; x>>=1, res++);
			return res;
		}	
		
		public static function float32unpack(x:uint):Number {
			var mantissa:Number = x&0x1fffff;
			var e:Number = (x&0x7fe00000)>>21;
			if((x&0x80000000)!=0) {
				mantissa=-mantissa;
			}
			return mantissa*Math.pow(2.0, e-788.0);
		}

		public static function lookup1Values(a:int, b:int):uint {
			var res:uint = Math.pow(Math.E, Math.log(a)/b);
			return intPow(res+1, b)<=a?res+1:res;
		}

		public static function intPow(base:uint, e:uint):uint {
			var res:uint = 1;
			for(; e>0; e--, res*=base);
			return res;
		}

		public static function isBitSet(value:uint, bit:uint):Boolean {
			return (value&(1<<bit))!=0;
		}
		
		public static function icount(value:uint):uint {
			var res:uint = 0;
			while (value > 0) {
				res += value & 1;
				value >>= 1;
			}
			return res;
		}

		public static function lowNeighbour(v:Vector.<int>, x:int):int {

			var max:int = -1;
			var n:int = 0;
			var i:int;
			
			for (i = 0; i < v.length && i < x; i++) {
				if (v[i] > max && v[i] < v[x]) {
					max = v[i];
					n = i;
				}
			}
			return n;
		}

		public static function highNeighbour(v:Vector.<int>, x:int):int {
			
			var min:int = int.MAX_VALUE;
			var n:int = 0;
			var i:int;
			
			for (i = 0; i < v.length && i < x; i++) {
				if (v[i] < min && v[i] > v[x]) {
					min = v[i];
					n = i;
				}
			}
			return n;
		}

		public static function renderPoint(x0:int, x1:int, y0:int, y1:int, x:int):int {
			return y0 + int(((y1-y0) * (x - x0)) / (x1 - x0));
		}

		public static function renderLine(x0:int, y0:int, x1:int, y1:int, v:Vector.<Number>):void {
		
			var dy:int = y1 - y0;
			var adx:int = x1 - x0;
			var b:int = dy / adx;
			var sy:int = dy < 0 ? b - 1 : b + 1;
			var x:int = x0;
			var y:int = y0;
			var err:int = 0;
			var ady:int = (dy < 0 ? -dy : dy) - (b > 0 ? b * adx : -b * adx);
	
			v[x] *= Floor.DB_STATIC_TABLE[y];
			for (x = x0 + 1; x < x1; x++) {
				err += ady;
				if (err >= adx) {
					err -= adx;
					v[x] *= Floor.DB_STATIC_TABLE[y += sy];
				} else {
					v[x] *= Floor.DB_STATIC_TABLE[y += b];
				}
			}

		}

	}
	
}
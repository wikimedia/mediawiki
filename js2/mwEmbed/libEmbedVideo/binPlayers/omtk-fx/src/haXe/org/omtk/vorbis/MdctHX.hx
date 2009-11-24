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

package org.omtk.vorbis;

import flash.Vector;

class MdctHX {

	private static var cPI1_8:Float = 0.92387953251128675613;
	private static var cPI2_8:Float = 0.70710678118654752441;
	private static var cPI3_8:Float = 0.38268343236508977175;
	
	private var n:Int;
	private var log2n:Int;
	private var trig:Vector<Float>;
	private var bitrev:Vector<Int>;
	
	private var dtmp1:Float;
	private var dtmp2:Float;
	private var dtmp3:Float;
	private var dtmp4:Float;

	private var x:Vector<Float>;
	private var w:Vector<Float>;

	public function new(n:Int) { 
		this.n = n;
	
		var i:Int;
		var j:Int;
		
		bitrev = new Vector<Int>();
		trig = new Vector<Float>(Std.int(n+n/4), true);

		for(i in 0...Std.int(n+n/4)) {
			trig[i] = 0;
		}

		x = new Vector<Float>(Std.int(n/2), true);
		w = new Vector<Float>(Std.int(n/2), true);

		for(i in 0...Std.int(n/2)) {
			x[i] = 0;
			w[i] = 0;
		}
		
		var n2:Int = n >>> 1;
		log2n = Math.round(Math.log(n) / Math.log(2));

		var AE:Int = 0;
		var AO:Int = 1;
		var BE:Int = Std.int(AE + n / 2);
		var BO:Int = BE + 1;
		var CE:Int = Std.int(BE + n / 2);
		var CO:Int = CE + 1;
		
		for (i in 0...Std.int(n/4)) {
			trig[AE + i * 2] = Math.cos((Math.PI / n) * (4 * i));
			trig[AO + i * 2] = -Math.sin((Math.PI / n) * (4 * i));
			trig[BE + i * 2] = Math.cos((Math.PI / (2 * n)) * (2 * i + 1));
			trig[BO + i * 2] = Math.sin((Math.PI / (2 * n)) * (2 * i + 1));
		}

		for (i in 0...Std.int(n/8)) {
			trig[CE + i * 2] = Math.cos((Math.PI / n) * (4 * i + 2));
			trig[CO + i * 2] = -Math.sin((Math.PI / n) * (4 * i + 2));
		}
		
		var mask:Int = (1 << (log2n - 1)) - 1;
		var msb:Int = 1 << (log2n - 2);
		
		for (i in 0...Std.int(n/8)) {
			var acc:Int = 0;
			j = 0;
			while(msb>>>j!=0) {
				if (((msb >>> j) & i) != 0) {
					acc |= 1 << j;
				}
				j++;
			}
			bitrev[i * 2] = ((~acc) & mask);
			bitrev[i * 2 + 1] = acc;
		}
		
	} 

	public function imdct(frq:Vector<Float>, window:Vector<Float>, pcm:Vector<Float>):Void {
	
		var i:Int;

		var n2:Int;
		var n4:Int;
		var n8:Int;

		var inO:Int;
		var xO:Int;
		var A:Int;

		var temp1:Float;
		var temp2:Float;

		var B:Int;
		var o1:Int;
		var o2:Int;
		var o3:Int;
		var o4:Int;

		var xx:Int;
		var xxx:Vector<Float>;
		
		n2 = n >> 1;
		n4 = n >> 2;
		n8 = n >> 3;

		inO = -1;
		xO = 0;
		A = n2;
		
		temp1 = 0.0;
		temp2 = 0.0;
		
		for (i in 0...n8) {
			dtmp1 = frq[inO += 2];
			dtmp2 = frq[inO += 2];
			dtmp3 = trig[--A];
			dtmp4 = trig[--A];
			x[xO++] = -dtmp2 * dtmp3 - dtmp1 * dtmp4;
			x[xO++] = dtmp1 * dtmp3 - dtmp2 * dtmp4;
		}

		inO = n2;

		for(i in 0...n8) {
			dtmp1 = frq[inO -= 2];
			dtmp2 = frq[inO -= 2];
			dtmp3 = trig[--A];
			dtmp4 = trig[--A];
			x[xO++] = dtmp2 * dtmp3 + dtmp1 * dtmp4;
			x[xO++] = dtmp2 * dtmp4 - dtmp1 * dtmp3;
		}

		xxx = kernel(x, w, n, n2, n4, n8);
		xx = 0;
		
		B = n2;
		o1 = n4;
		o2 = o1 - 1;
		o3 = n4 + n2;
		o4 = o3 - 1;

		for (i in 0...n4) {
			dtmp1 = xxx[xx++];
			dtmp2 = xxx[xx++];
			dtmp3 = trig[B++];
			dtmp4 = trig[B++];

			temp1 = (dtmp1 * dtmp4 - dtmp2 * dtmp3);
			temp2 = -(dtmp1 * dtmp3 + dtmp2 * dtmp4);

			pcm[o1] = -temp1 * window[o1];
			pcm[o2] = temp1 * window[o2];
			pcm[o3] = temp2 * window[o3];
			pcm[o4] = temp2 * window[o4];

			o1++;
			o2--;
			o3++;
			o4--;
		}
		
	}

	private inline function kernel(x:Vector<Float>, w:Vector<Float>, n:Int, n2:Int, n4:Int, n8:Int):Vector<Float> {

		var i:Int;
		var r:Int;
		var s:Int;
		var rlim:Int;
		var slim:Int;
		
		var xA:Int = n4;
		var xB:Int = 0;
		var w1:Int = 0;
		var w2:Int = n4;
		var A:Int = n2;

		var x0:Float;
		var x1:Float;
		var wA:Float;
		var wB:Float;
		var wC:Float;
		var wD:Float;
		var k0:Int;
		var k1:Int;
		var t1:Int;
		var t2:Int;
		
		var wbase:Int;
		var temp:Vector<Float>;
		
		var wACE:Float;
		var wBCE:Float;
		var wACO:Float;
		var wBCO:Float;
			
		var AEv:Float;
		var AOv:Float;
			
		i=0;
		while(i < n4) {
			x0 = x[xA] - x[xB];
			
			w[w2 + i] = x[xA++] + x[xB++];

			x1 = x[xA] - x[xB];
			A -= 4;

			w[i++] = x0 * trig[A] + x1 * trig[A + 1];
			w[i] = x1 * trig[A] - x0 * trig[A + 1];

			w[w2 + i] = x[xA++] + x[xB++];
			i++;
		}

		for (i in 0...log2n-3) {
			k0 = n >>> (i + 2);
			k1 = 1 << (i + 3);
			wbase = n2 - 2;

			A = 0;

			rlim = k0 >>> 2;
			for (r in 0...rlim) {
			
				w1 = wbase;
				w2 = w1 - (k0 >> 1);
				AEv = trig[A];
				AOv = trig[A + 1];
				wbase -= 2;

				k0++;
				
				slim = 2 << i;
				for (s in 0...slim) {
					dtmp1 = w[w1];
					dtmp2 = w[w2];
					wB = dtmp1 - dtmp2;
					x[w1] = dtmp1 + dtmp2;
					dtmp1 = w[++w1];
					dtmp2 = w[++w2];
					wA = dtmp1 - dtmp2;
					x[w1] = dtmp1 + dtmp2;
					x[w2] = wA * AEv - wB * AOv;
					x[w2-1] = wB * AEv + wA * AOv;
					w1 -= k0;
					w2 -= k0;
				}
				k0--;
				A += k1;
			}

			temp = w;
			w = x;
			x = temp;
		}
		

		var C:Int = n;
		var bit:Int = 0;
		var xx1:Int = 0;
		var xx2:Int = n2 - 1;
		
		var wt1: Float;
		var wt2: Float;
		var wt12: Float;
		var wt21: Float;
		var trigV: Float;

		for (i in 0...n8) {
			t1 = bitrev[bit++];
			t2 = bitrev[bit++];
			
			wt1 = w[t1];
			wt2 = w[t2];
			wt12 = w[t1-1];
			wt21 = w[t2+1];

			wA = wt1 - wt21;
			wB = wt12 + wt2;
			wC = wt1 + wt21;
			wD = wt12 - wt2;
			
			trigV = trig[C];

			wACE = wA * trigV;
			wBCE = wB * trigV;
			
			trigV = trig[++C];
			
			wACO = wA * trigV;
			wBCO = wB * trigV;
			
			++C;

			x[xx1++] = (wC + wACO + wBCE);
			x[xx2--] = (-wD + wBCO - wACE);
			x[xx1++] = (wD + wBCO - wACE);
			x[xx2--] = (wC - wACO - wBCE);
		}

		return x;
	}	

	/*
	 * Dummy function required for the haXe compiler to build this to
	 * a .SWF file.
	 */
	public static function main() : Void {	
	}
	
}

/**
Copyright (c) 2013, Specialisterne.
http://specialisterne.com/dk/
All rights reserved.
Authors:
Jacob Christian Munch-Andersen

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this
   list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright notice,
   this list of conditions and the following disclaimer in the documentation
   and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
**/
// For information and latest version see: https://github.com/Jacob-Christian-Munch-Andersen/Easy-Deflate
(function(){

var zip={};
function UTF8encode(str){
	var out=[];
	var a;
	var c,c2;
	for(a=0;a<str.length;a++){
		c=str.charCodeAt(a);
		if(c<128){
			out.push(c);
		}
		else if(c<2048){
			out.push((c >> 6)+192);
			out.push((c & 63)+128);
		}
		else if(c<65536){
			if(c>=0xD800 && c<0xDC00){
				a++;
				if(a>=str.length){
					return null;
				}
				c2=str.charCodeAt(a);
				if(c2>=0xDC00 && c2<0xE000){
					c=65536+(c-0xD800)*1024+c2-0xDC00;
					out.push((c >> 18)+240);
					out.push(((c >> 12) & 63)+128);
					out.push(((c >> 6) & 63)+128);
					out.push((c & 63)+128);
				}
				else{
					return null;
				}
			}
			else if(c>=0xDC00 && c<0xE000){
				return null;
			}
			else{
				out.push((c >> 12)+224);
				out.push(((c >> 6) & 63)+128);
				out.push((c & 63)+128);
			}
		}
		else{
			return null;
		}
	}
	return new Uint8Array(out);
}
function UTF8decodeA(arrarr){
	var result="";
	var intermediate;
	var minvalue;
	var missing=0;
	var a,b;
	var arr;
	var c;
	var lower,upper;
	for(a=0;a<arrarr.length;a++){
		arr=arrarr[a];
		for(b=0;b<arr.length;b++){
			c=arr[b];
			if(missing){
				if(c>127 && c<192){
					intermediate=intermediate*64+c-128;
					missing--;
					if(!missing){
						if(intermediate>=minvalue){
							if(intermediate>=65536){
								if(intermediate>0x10FFFF){
									return null;
								}
								upper=(intermediate-65536)>>10;
								lower=intermediate%1024;
								result+=String.fromCharCode(upper+0xD800,lower+0xDC00);
							}
							else{
								result+=String.fromCharCode(intermediate);
							}
						}
						else{
							return null;
						}
					}
				}
				else{
					return null;
				}
			}
			else if(c<128){
				result+=String.fromCharCode(c);
			}
			else if(c>191 && c<248){
				if(c<224){
					intermediate=c-192;
					minvalue=128;
					missing=1;
				}
				else if(c<240){
					intermediate=c-224;
					minvalue=2048;
					missing=2;
				}
				else{
					intermediate=c-240;
					minvalue=65536;
					missing=3;
				}
			}
			else{
				return null;
			}
		}
	}
	if(missing){
		return null;
	}
	return result;
}
function deflate(str){
	var a,c;
	var readlen=50000;
	var resulta=[];
	var results="";
	var b,d;
	var zipper=new zip.Deflater(9);
	for(a=0;a<str.length;a+=readlen){
		d=UTF8encode(str.substr(a,readlen));
		if(d===null){ //This error may be due to a 4 byte charachter being split, retry with a string that is 1 longer to fix it.
			d=UTF8encode(str.substr(a,readlen+1));
			a+=1;
			if(d===null){
				return null;
			}
		}
		b=zipper.append(d);
		if(b.length!==0){
			resulta.push(b);
		}
	}
	b=zipper.flush();
	if(b.length!==0){
		resulta.push(b);
	}
	for(a=0;a<resulta.length;a++){
		for(c=0;c<resulta[a].length;c++){
			results+=String.fromCharCode(resulta[a][c]);
		}
	}
	return "rawdeflate,"+btoa(results);
}
function inflate(dfl){
	var unzipper=new zip.Inflater();
	var resulta=[];
	var dfls;
	var a,c;
	var b,d;
	if(dfl.slice(0,11)!="rawdeflate,"){
		return null;
	}
	try{
		dfls=atob(dfl.slice(11));
	}
	catch(e){
		return null;
	}
	try{
		for(a=0;a<dfls.length;a+=50000){
			b=new Uint8Array(Math.min(50000,dfls.length-a));
			for(c=0;c<b.length;c++){
				b[c]=dfls.charCodeAt(c+a);
			}
			d=unzipper.append(b);
			if(d.length){
				resulta.push(d);
			}
		}
		return UTF8decodeA(resulta);
	}
	catch(e){
		return null;
	}
}

window.EasyDeflate = {
	'zip': zip,
	'inflate': inflate,
	'deflate': deflate
};

})();
"use strict";const t=require("vue");function i(n,r){if(n()){t.warn(r);return}const c=t.watch(n,e=>{e&&(t.warn(r),c())})}module.exports=i;

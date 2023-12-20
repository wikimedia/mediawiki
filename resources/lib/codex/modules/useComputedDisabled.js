"use strict";const e=require("vue"),u=require("./constants.js");function r(s){const t=e.inject(u.DisabledKey,e.ref(!1));return e.computed(()=>t.value||s.value)}module.exports=r;

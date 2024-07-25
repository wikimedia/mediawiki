"use strict";const n=require("vue"),i=require("./useI18n.cjs");function c(e,r,t,u=[]){const s=i(r,t,u);return n.computed(()=>e.value||s.value)}exports.useI18nWithOverride=c;

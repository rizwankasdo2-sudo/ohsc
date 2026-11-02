// Run with: node tools/tests/cv-draft.test.js
'use strict';
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');
const source = fs.readFileSync(path.join(__dirname, '../assets/cv-builder.js'), 'utf8');
const key = 'ohscCvBuilderTabV1';
function storage(seed) {
    const values = new Map(Object.entries(seed || {}));
    return { getItem(k) { return values.has(k) ? values.get(k) : null; }, setItem(k,v) { values.set(k,String(v)); }, removeItem(k) { values.delete(k); } };
}
function boot(session, local) {
    const nodes = new Map();
    function node(id) {
        if (!nodes.has(id)) nodes.set(id, { hidden: true, style: {}, events: {}, textContent: '', innerHTML: '', addEventListener(n,fn) { this.events[n]=fn; }, querySelector() { return null; }, focus() {}, scrollIntoView() {} });
        return nodes.get(id);
    }
    const context = { document: { getElementById: node, querySelectorAll() { return []; } }, sessionStorage: session, localStorage: local, window: {}, setTimeout() {} };
    // Expose the closure to this test only; production has no debugging hooks.
    vm.runInNewContext(source.replace(/\}\)\(\);\s*$/, 'globalThis.api={state:function(){return state;},save:save,load:load,sheet:sheet};})();'), context);
    return { api: context.api, node };
}
const shared = storage({ ohscCvBuilder: JSON.stringify({personal:{name:'Previous browser user'}}) });
const tabStorage = storage();
let first = boot(tabStorage, shared);
assert.equal(first.api.state().personal.name, '', 'Old localStorage data must not auto-open');
assert.equal(first.node('cvLegacyNotice').hidden, false);
first.api.state().personal.name = 'Test Person A';
first.api.state().template = 'classic';
first.api.state().step = 3;
first.api.save();
assert.equal(JSON.parse(shared.getItem('ohscCvBuilder')).personal.name, 'Previous browser user', 'Saving must not write browser-wide personal data');
let reload = boot(tabStorage, shared);
assert.equal(reload.api.state().personal.name, 'Test Person A', 'Reload must restore this tab');
assert.equal(reload.api.state().step, 3);
assert.equal(reload.api.state().template, 'classic');
assert.equal(boot(storage(), shared).api.state().personal.name, '', 'A separate storage context must start blank');
reload.node('cvClearDraft').events.click();
assert.equal(reload.node('cvClearConfirm').hidden, false);
reload.node('cvCancelClear').events.click();
assert.equal(reload.node('cvClearConfirm').hidden, true);
assert.notEqual(tabStorage.getItem(key), null, 'Cancel must keep the draft');
reload.node('cvConfirmClear').events.click();
assert.equal(tabStorage.getItem(key), null);
assert.equal(shared.getItem('ohscCvBuilder'), null);
assert.equal(reload.api.state().personal.name, '');
assert.equal(reload.api.state().step, 1);
assert.equal(boot(tabStorage,shared).api.state().personal.name, '', 'Cleared data must not return on reload');
const expired = storage({[key]:JSON.stringify({version:1,savedAt:Date.now()-9*60*60*1000,data:{personal:{name:'Expired'}}})});
assert.equal(boot(expired,shared).api.state().personal.name,'');
assert.equal(expired.getItem(key),null);
assert.equal(boot(storage({[key]:'{broken json'}),shared).api.state().step,1);
const malformed = storage({[key]:JSON.stringify({version:1,savedAt:Date.now(),data:{step:999,template:'invalid',personal:{name:123,summary:[]},experience:'bad',education:[null],skills:{bad:true}}})});
const clean=boot(malformed,shared).api.state();
assert.equal(clean.step,5); assert.equal(clean.template,'modern'); assert.equal(clean.personal.name,''); assert.equal(clean.skills.length,0);
const denied={getItem(){throw Error('blocked')},setItem(){throw Error('blocked')},removeItem(){throw Error('blocked')}};
const blocked=boot(denied,denied);
assert.match(blocked.node('cvDraftStatus').textContent,/unavailable/);
blocked.api.save(); assert.match(blocked.node('cvDraftStatus').textContent,/could not be saved/);
const escaped=first.api.sheet({personal:{name:'<script>alert(1)</script>',title:'',email:'',phone:'',location:'',summary:''},experience:[],education:[],skills:[]},'classic');
assert(!escaped.includes('<script>')); assert(escaped.includes('&lt;script&gt;'));
console.log('PASS: tab reload, separate storage contexts, legacy isolation, confirmation/cancel, clear/reload, expiry, malformed/blocked storage, and escaped Classic preview.');

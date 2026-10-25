const {strict: assert} = require('node:assert');
const fs = require('node:fs');
const vm = require('node:vm');
const body = {rows: [], insertBefore(row, ref) { row.remove(); this.rows.splice(this.rows.indexOf(ref), 0, row); }, append(row) {row.remove(); this.rows.push(row);}, prepend(row) {row.remove(); this.rows.unshift(row);}};
const events = [];
const table = {id:'ctrl_test', dataset:{multitextName:'edit[7][rows]'}, tBodies:[body], dispatchEvent(e){events.push(e.type);}};
function makeRow(values) {
    const inputs = values.map((value, col) => ({value, dataset:{multitextColumn:String(col)}, invalid:true, removeAttribute(){this.invalid=false;}, focus(){}, select(){}}));
    return {inputs, parentElement:body, errors:[{remove(){}}],
        get previousElementSibling(){return body.rows[body.rows.indexOf(this)-1];},
        get nextElementSibling(){return body.rows[body.rows.indexOf(this)+1];},
        cloneNode(){return makeRow(inputs.map(i=>i.value));},
        querySelectorAll(selector){return selector === '.tl_error' ? this.errors : inputs;},
        querySelector(){return inputs[0];},
        remove(){const i=body.rows.indexOf(this);if(i>=0)body.rows.splice(i,1);},
        after(row){row.remove();body.rows.splice(body.rows.indexOf(this)+1,0,row);}
    };
}
let listener, registrations=0;
const context = {window:{}, document:{getElementById(){return table;},addEventListener(name, cb){listener=cb;registrations++;}}, Event:class{constructor(type){this.type=type;}}};
vm.createContext(context);
const code=fs.readFileSync(__dirname+'/../src/Resources/public/js/multitext.js','utf8');
vm.runInContext(code, context);vm.runInContext(code, context);
assert.equal(registrations,1,'Turbo/repeated loading must not duplicate listeners');
function click(row,action){const button={dataset:{multitextAction:action},closest(selector){return selector==='tr'?row:table;}};listener({target:{closest(){return button;}},preventDefault(){}});}
body.rows.push(makeRow(['A','B']),makeRow(['C','D']));
click(body.rows[0],'rnew');assert.deepEqual(body.rows[1].inputs.map(i=>i.value),['','']);
click(body.rows[0],'rcopy');assert.deepEqual(body.rows[1].inputs.map(i=>i.value),['A','B']);
assert.equal(body.rows[1].inputs[0].invalid,false);
click(body.rows[0],'rup');assert.equal(body.rows.at(-1).inputs[0].value,'A');
click(body.rows.at(-1),'rdown');assert.equal(body.rows[0].inputs[0].value,'A');
click(body.rows[0],'rdown');assert.equal(body.rows[1].inputs[0].value,'A');
click(body.rows[1],'rup');assert.equal(body.rows[0].inputs[0].value,'A');
while(body.rows.length>1) click(body.rows[0],'rdelete');
click(body.rows[0],'rdelete');assert.equal(body.rows.length,1);
assert.equal(body.rows[0].inputs[1].name,'edit[7][rows][0][1]');
assert.equal(body.rows[0].inputs[1].id,'ctrl_test_0_1');
assert.ok(events.includes('input') && events.includes('change'));
console.log('JavaScript tests passed: insert, copy, move/wrap, delete, reindex and repeated loading.');

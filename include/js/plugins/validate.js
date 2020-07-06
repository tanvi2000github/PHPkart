/************************************ JQUERY VALIDATE ****************************/
/**
 * jQuery Validation Plugin 1.9.0
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * http://docs.jquery.com/Plugins/Validation
 *
 * Copyright (c) 2006 - 2011 Jörn Zaefferer
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
(function(a){a.extend(a.fn,{validate:function(b){if(!this.length){b&&b.debug&&window.console&&console.warn("nothing selected, can't validate, returning nothing");return}var c=a.data(this[0],"validator");if(c){return c}this.attr("novalidate","novalidate");c=new a.validator(b,this[0]);a.data(this[0],"validator",c);if(c.settings.onsubmit){var d=this.find("input, button");d.filter(".cancel").click(function(){c.cancelSubmit=true});if(c.settings.submitHandler){d.filter(":submit").click(function(){c.submitButton=this})}this.submit(function(b){function d(){if(c.settings.submitHandler){if(c.submitButton){var b=a("<input type='hidden'/>").attr("name",c.submitButton.name).val(c.submitButton.value).appendTo(c.currentForm)}c.settings.submitHandler.call(c,c.currentForm);if(c.submitButton){b.remove()}return false}return true}if(c.settings.debug)b.preventDefault();if(c.cancelSubmit){c.cancelSubmit=false;return d()}if(c.form()){if(c.pendingRequest){c.formSubmitted=true;return false}return d()}else{c.focusInvalid();return false}})}return c},valid:function(){if(a(this[0]).is("form")){return this.validate().form()}else{var b=true;var c=a(this[0].form).validate();this.each(function(){b&=c.element(this)});return b}},removeAttrs:function(b){var c={},d=this;a.each(b.split(/\s/),function(a,b){c[b]=d.attr(b);d.removeAttr(b)});return c},rules:function(b,c){var d=this[0];if(b){var e=a.data(d.form,"validator").settings;var f=e.rules;var g=a.validator.staticRules(d);switch(b){case"add":a.extend(g,a.validator.normalizeRule(c));f[d.name]=g;if(c.messages)e.messages[d.name]=a.extend(e.messages[d.name],c.messages);break;case"remove":if(!c){delete f[d.name];return g}var h={};a.each(c.split(/\s/),function(a,b){h[b]=g[b];delete g[b]});return h}}var i=a.validator.normalizeRules(a.extend({},a.validator.metadataRules(d),a.validator.classRules(d),a.validator.attributeRules(d),a.validator.staticRules(d)),d);if(i.required){var j=i.required;delete i.required;i=a.extend({required:j},i)}return i}});a.extend(a.expr[":"],{blank:function(b){return!a.trim(""+b.value)},filled:function(b){return!!a.trim(""+b.value)},unchecked:function(a){return!a.checked}});a.validator=function(b,c){this.settings=a.extend(true,{},a.validator.defaults,b);this.currentForm=c;this.init()};a.validator.format=function(b,c){if(arguments.length==1)return function(){var c=a.makeArray(arguments);c.unshift(b);return a.validator.format.apply(this,c)};if(arguments.length>2&&c.constructor!=Array){c=a.makeArray(arguments).slice(1)}if(c.constructor!=Array){c=[c]}a.each(c,function(a,c){b=b.replace(new RegExp("\\{"+a+"\\}","g"),c)});return b};a.extend(a.validator,{defaults:{messages:{},groups:{},rules:{},errorClass:"error",validClass:"valid",errorElement:"label",focusInvalid:true,errorContainer:a([]),errorLabelContainer:a([]),onsubmit:true,ignore:"",ignoreTitle:false,onfocusin:function(a,b){this.lastActive=a;if(this.settings.focusCleanup&&!this.blockFocusCleanup){this.settings.unhighlight&&this.settings.unhighlight.call(this,a,this.settings.errorClass,this.settings.validClass);this.addWrapper(this.errorsFor(a)).hide()}},onfocusout:function(a,b){if(!this.checkable(a)&&(a.name in this.submitted||!this.optional(a))){this.element(a)}},onkeyup:function(a,b){if(a.name in this.submitted||a==this.lastElement){this.element(a)}},onclick:function(a,b){if(a.name in this.submitted)this.element(a);else if(a.parentNode.name in this.submitted)this.element(a.parentNode)},highlight:function(b,c,d){if(b.type==="radio"){this.findByName(b.name).addClass(c).removeClass(d)}else{a(b).addClass(c).removeClass(d)}},unhighlight:function(b,c,d){if(b.type==="radio"){this.findByName(b.name).removeClass(c).addClass(d)}else{a(b).removeClass(c).addClass(d)}}},setDefaults:function(b){a.extend(a.validator.defaults,b)},messages:{required:"Required Field.",remote:"Existing Value.",email:"Invalid E-mail address.",website:"Wrong Url.",websitehttp:"Wrong Url.",cod_fisc:"",p_iva:"",url:"Wrong Url.",equalTo:"Passwords do not match.",date:"Invalid date.",dateISO:"Please enter a valid date (ISO).",number:"Only Numbers",digits:"",data:"Invalid date",creditcard:"",accept:"Extension is not accepted.",ora:"",no_space:"",maxlength:a.validator.format("Maximum {0} characters."),minlength:a.validator.format("At least {0} characters."),rangelength:a.validator.format("Only characters between {0} and {1} of length ."),range:a.validator.format("Only values ​​between {0} and {1}."),max:a.validator.format("Only values ​​less than or equal to {0}."),min:a.validator.format("Only values ​​greater than or equal to {0}."),least_one:a.validator.format("Fill in at least {0} field correctly.")},autoCreateRanges:false,prototype:{init:function(){function d(b){var c=a.data(this[0].form,"validator"),d="on"+b.type.replace(/^validate/,"");c.settings[d]&&c.settings[d].call(c,this[0],b)}this.labelContainer=a(this.settings.errorLabelContainer);this.errorContext=this.labelContainer.length&&this.labelContainer||a(this.currentForm);this.containers=a(this.settings.errorContainer).add(this.settings.errorLabelContainer);this.submitted={};this.valueCache={};this.pendingRequest=0;this.pending={};this.invalid={};this.reset();var b=this.groups={};a.each(this.settings.groups,function(c,d){a.each(d.split(/\s/),function(a,d){b[d]=c})});var c=this.settings.rules;a.each(c,function(b,d){c[b]=a.validator.normalizeRule(d)});a(this.currentForm).validateDelegate("[type='text'], [type='password'], [type='file'], select, textarea, "+"[type='number'], [type='search'] ,[type='tel'], [type='url'], "+"[type='email'], [type='datetime'], [type='date'], [type='month'], "+"[type='week'], [type='time'], [type='datetime-local'], "+"[type='range'], [type='color'] ","focusin focusout keyup",d).validateDelegate("[type='radio'], [type='checkbox'], select, option","click",d);if(this.settings.invalidHandler)a(this.currentForm).bind("invalid-form.validate",this.settings.invalidHandler)},form:function(){this.checkForm();a.extend(this.submitted,this.errorMap);this.invalid=a.extend({},this.errorMap);if(!this.valid())a(this.currentForm).triggerHandler("invalid-form",[this]);this.showErrors();return this.valid()},checkForm:function(){this.prepareForm();for(var a=0,b=this.currentElements=this.elements();b[a];a++){this.check(b[a])}return this.valid()},element:function(b){b=this.validationTargetFor(this.clean(b));this.lastElement=b;this.prepareElement(b);this.currentElements=a(b);var c=this.check(b);if(c){delete this.invalid[b.name]}else{this.invalid[b.name]=true}if(!this.numberOfInvalids()){this.toHide=this.toHide.add(this.containers)}this.showErrors();return c},showErrors:function(b){if(b){a.extend(this.errorMap,b);this.errorList=[];for(var c in b){this.errorList.push({message:b[c],element:this.findByName(c)[0]})}this.successList=a.grep(this.successList,function(a){return!(a.name in b)})}this.settings.showErrors?this.settings.showErrors.call(this,this.errorMap,this.errorList):this.defaultShowErrors()},resetForm:function(){if(a.fn.resetForm)a(this.currentForm).resetForm();this.submitted={};this.lastElement=null;this.prepareForm();this.hideErrors();this.elements().removeClass(this.settings.errorClass)},numberOfInvalids:function(){return this.objectLength(this.invalid)},objectLength:function(a){var b=0;for(var c in a)b++;return b},hideErrors:function(){this.addWrapper(this.toHide).hide()},valid:function(){return this.size()==0},size:function(){return this.errorList.length},focusInvalid:function(){if(this.settings.focusInvalid){try{a(this.findLastActive()||this.errorList.length&&this.errorList[0].element||[]).filter(":visible").focus().trigger("focusin")}catch(b){}}},findLastActive:function(){var b=this.lastActive;return b&&a.grep(this.errorList,function(a){return a.element.name==b.name}).length==1&&b},elements:function(){var b=this,c={};return a(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, [disabled]").not(this.settings.ignore).filter(function(){!this.name&&b.settings.debug&&window.console&&console.error("%o has no name assigned",this);if(this.name in c||!b.objectLength(a(this).rules()))return false;c[this.name]=true;return true})},clean:function(b){return a(b)[0]},errors:function(){return a(this.settings.errorElement+"."+this.settings.errorClass,this.errorContext)},reset:function(){this.successList=[];this.errorList=[];this.errorMap={};this.toShow=a([]);this.toHide=a([]);this.currentElements=a([])},prepareForm:function(){this.reset();this.toHide=this.errors().add(this.containers)},prepareElement:function(a){this.reset();this.toHide=this.errorsFor(a)},check:function(b){b=this.validationTargetFor(this.clean(b));var c=a(b).rules();var d=false;for(var e in c){var f={method:e,parameters:c[e]};try{var g=a.validator.methods[e].call(this,b.value.replace(/\r/g,""),b,f.parameters);if(g=="dependency-mismatch"){d=true;continue}d=false;if(g=="pending"){this.toHide=this.toHide.not(this.errorsFor(b));return}if(!g){this.formatAndAdd(b,f);return false}}catch(h){this.settings.debug&&window.console&&console.log("exception occured when checking element "+b.id+", check the '"+f.method+"' method",h);throw h}}if(d)return;if(this.objectLength(c))this.successList.push(b);return true},customMetaMessage:function(b,c){if(!a.metadata)return;var d=this.settings.meta?a(b).metadata()[this.settings.meta]:a(b).metadata();return d&&d.messages&&d.messages[c]},customMessage:function(a,b){var c=this.settings.messages[a];return c&&(c.constructor==String?c:c[b])},findDefined:function(){for(var a=0;a<arguments.length;a++){if(arguments[a]!==undefined)return arguments[a]}return undefined},defaultMessage:function(b,c){return this.findDefined(this.customMessage(b.name,c),this.customMetaMessage(b,c),!this.settings.ignoreTitle&&b.title||undefined,a.validator.messages[c],"<strong>Warning: No message defined for "+b.name+"</strong>")},formatAndAdd:function(a,b){var c=this.defaultMessage(a,b.method),d=/\$?\{(\d+)\}/g;if(typeof c=="function"){c=c.call(this,b.parameters,a)}else if(d.test(c)){c=jQuery.format(c.replace(d,"{$1}"),b.parameters)}this.errorList.push({message:c,element:a});this.errorMap[a.name]=c;this.submitted[a.name]=c},addWrapper:function(a){if(this.settings.wrapper)a=a.add(a.parent(this.settings.wrapper));return a},defaultShowErrors:function(){for(var a=0;this.errorList[a];a++){var b=this.errorList[a];this.settings.highlight&&this.settings.highlight.call(this,b.element,this.settings.errorClass,this.settings.validClass);this.showLabel(b.element,b.message)}if(this.errorList.length){this.toShow=this.toShow.add(this.containers)}if(this.settings.success){for(var a=0;this.successList[a];a++){this.showLabel(this.successList[a])}}if(this.settings.unhighlight){for(var a=0,c=this.validElements();c[a];a++){this.settings.unhighlight.call(this,c[a],this.settings.errorClass,this.settings.validClass)}}this.toHide=this.toHide.not(this.toShow);this.hideErrors();this.addWrapper(this.toShow).show()},validElements:function(){return this.currentElements.not(this.invalidElements())},invalidElements:function(){return a(this.errorList).map(function(){return this.element})},showLabel:function(b,c){var d=this.errorsFor(b);if(d.length){d.removeClass(this.settings.validClass).addClass(this.settings.errorClass);d.attr("generated")&&d.html(c)}else{d=a("<"+this.settings.errorElement+"/>").attr({"for":this.idOrName(b),generated:true}).addClass(this.settings.errorClass).html(c||"");if(this.settings.wrapper){d=d.hide().show().wrap("<"+this.settings.wrapper+"/>").parent()}if(!this.labelContainer.append(d).length)this.settings.errorPlacement?this.settings.errorPlacement(d,a(b)):d.insertAfter(b)}if(!c&&this.settings.success){d.text("");typeof this.settings.success=="string"?d.addClass(this.settings.success):this.settings.success(d)}this.toShow=this.toShow.add(d)},errorsFor:function(b){var c=this.idOrName(b);return this.errors().filter(function(){return a(this).attr("for")==c})},idOrName:function(a){return this.groups[a.name]||(this.checkable(a)?a.name:a.id||a.name)},validationTargetFor:function(a){if(this.checkable(a)){a=this.findByName(a.name).not(this.settings.ignore)[0]}return a},checkable:function(a){return/radio|checkbox/i.test(a.type)},findByName:function(b){var c=this.currentForm;return a(document.getElementsByName(b)).map(function(a,d){return d.form==c&&d.name==b&&d||null})},getLength:function(b,c){switch(c.nodeName.toLowerCase()){case"select":return a("option:selected",c).length;case"input":if(this.checkable(c))return this.findByName(c.name).filter(":checked").length}return b.length},depend:function(a,b){return this.dependTypes[typeof a]?this.dependTypes[typeof a](a,b):true},dependTypes:{"boolean":function(a,b){return a},string:function(b,c){return!!a(b,c.form).length},"function":function(a,b){return a(b)}},optional:function(b){return!a.validator.methods.required.call(this,a.trim(b.value),b)&&"dependency-mismatch"},startRequest:function(a){if(!this.pending[a.name]){this.pendingRequest++;this.pending[a.name]=true}},stopRequest:function(b,c){this.pendingRequest--;if(this.pendingRequest<0)this.pendingRequest=0;delete this.pending[b.name];if(c&&this.pendingRequest==0&&this.formSubmitted&&this.form()){a(this.currentForm).submit();this.formSubmitted=false}else if(!c&&this.pendingRequest==0&&this.formSubmitted){a(this.currentForm).triggerHandler("invalid-form",[this]);this.formSubmitted=false}},previousValue:function(b){return a.data(b,"previousValue")||a.data(b,"previousValue",{old:null,valid:true,message:this.defaultMessage(b,"remote")})}},classRuleSettings:{required:{required:true},email:{email:true},website:{website:true},websitehttp:{websitehttp:true},cod_fisc:{cod_fisc:true},p_iva:{p_iva:true},url:{url:true},date:{date:true},dateISO:{dateISO:true},dateDE:{dateDE:true},number:{number:true},numberDE:{numberDE:true},digits:{digits:true},data:{data:true},ora:{ora:true},creditcard:{creditcard:true},no_space:{no_space:true}},addClassRules:function(b,c){b.constructor==String?this.classRuleSettings[b]=c:a.extend(this.classRuleSettings,b)},classRules:function(b){var c={};var d=a(b).attr("class");d&&a.each(d.split(" "),function(){if(this in a.validator.classRuleSettings){a.extend(c,a.validator.classRuleSettings[this])}});return c},attributeRules:function(b){var c={};var d=a(b);for(var e in a.validator.methods){var f;if(e==="required"&&typeof a.fn.prop==="function"){f=d.prop(e)}else{f=d.attr(e)}if(f){c[e]=f}else if(d[0].getAttribute("type")===e){c[e]=true}}if(c.maxlength&&/-1|2147483647|524288/.test(c.maxlength)){delete c.maxlength}return c},metadataRules:function(b){if(!a.metadata)return{};var c=a.data(b.form,"validator").settings.meta;return c?a(b).metadata()[c]:a(b).metadata()},staticRules:function(b){var c={};var d=a.data(b.form,"validator");if(d.settings.rules){c=a.validator.normalizeRule(d.settings.rules[b.name])||{}}return c},normalizeRules:function(b,c){a.each(b,function(d,e){if(e===false){delete b[d];return}if(e.param||e.depends){var f=true;switch(typeof e.depends){case"string":f=!!a(e.depends,c.form).length;break;case"function":f=e.depends.call(c,c);break}if(f){b[d]=e.param!==undefined?e.param:true}else{delete b[d]}}});a.each(b,function(d,e){b[d]=a.isFunction(e)?e(c):e});a.each(["minlength","maxlength","min","max"],function(){if(b[this]){b[this]=Number(b[this])}});a.each(["rangelength","range"],function(){if(b[this]){b[this]=[Number(b[this][0]),Number(b[this][1])]}});if(a.validator.autoCreateRanges){if(b.min&&b.max){b.range=[b.min,b.max];delete b.min;delete b.max}if(b.minlength&&b.maxlength){b.rangelength=[b.minlength,b.maxlength];delete b.minlength;delete b.maxlength}}if(b.messages){delete b.messages}return b},normalizeRule:function(b){if(typeof b=="string"){var c={};a.each(b.split(/\s/),function(){c[this]=true});b=c}return b},addMethod:function(b,c,d){a.validator.methods[b]=c;a.validator.messages[b]=d!=undefined?d:a.validator.messages[b];if(c.length<3){a.validator.addClassRules(b,a.validator.normalizeRule(b))}},methods:{required:function(b,c,d){if(!this.depend(d,c))return"dependency-mismatch";switch(c.nodeName.toLowerCase()){case"select":var e=a(c).val();return e&&e.length>0;case"input":if(this.checkable(c))return this.getLength(b,c)>0;default:return a.trim(b).length>0}},remote:function(b,c,d){if(this.optional(c))return"dependency-mismatch";var e=this.previousValue(c);if(!this.settings.messages[c.name])this.settings.messages[c.name]={};e.originalMessage=this.settings.messages[c.name].remote;this.settings.messages[c.name].remote=e.message;d=typeof d=="string"&&{url:d}||d;if(this.pending[c.name]){return"pending"}if(e.old===b){return e.valid}e.old=b;var f=this;this.startRequest(c);var g={};g[c.name]=b;a.ajax(a.extend(true,{url:d,mode:"abort",port:"validate"+c.name,dataType:"json",data:g,success:function(d){f.settings.messages[c.name].remote=e.originalMessage;var g=d===true;if(g){var h=f.formSubmitted;f.prepareElement(c);f.formSubmitted=h;f.successList.push(c);f.showErrors()}else{var i={};var j=d||f.defaultMessage(c,"remote");i[c.name]=e.message=a.isFunction(j)?j(b):j;f.showErrors(i)}e.valid=g;f.stopRequest(c,g)}},d));return"pending"},minlength:function(b,c,d){return this.optional(c)||this.getLength(a.trim(b),c)>=d},maxlength:function(b,c,d){return this.optional(c)||this.getLength(a.trim(b),c)<=d},rangelength:function(b,c,d){var e=this.getLength(a.trim(b),c);return this.optional(c)||e>=d[0]&&e<=d[1]},min:function(a,b,c){return this.optional(b)||a>=c},max:function(a,b,c){return this.optional(b)||a<=c},range:function(a,b,c){return this.optional(b)||a>=c[0]&&a<=c[1]},email:function(a,b){return this.optional(b)||/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(a)},url:function(a,b){return this.optional(b)||/^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(a)},date:function(a,b){return this.optional(b)||!/Invalid|NaN/.test(new Date(a))},dateISO:function(a,b){return this.optional(b)||/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(a)},number:function(a,b){return this.optional(b)||/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(a)},digits:function(a,b){return this.optional(b)||/^\d+$/.test(a)},data:function(a,b){return this.optional(b)||/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/.test(a)||/^([\__]+)([\/]+)([\__]+)([\/]+)([\____]+)$/.test(a)},ora:function(a,b){return this.optional(b)||/^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/.test(a)||/^([\__]+)([\:]+)([\__]+)$/.test(a)},website:function(a,b){return this.optional(b)||/^((http|https):\/\/(www\.)?|www\.)[a-zA-Z0-9\_\-]+\.([a-zA-Z]{2,4}|[a-zA-Z]{2}\.[a-zA-Z]{2})(\/[a-zA-Z0-9\-\._\?\&=,'\+%\$#~]*)*$/.test(a)},websitehttp:function(a,b){return this.optional(b)||/^(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?$/.test(a)},cod_fisc:function(a,b){return this.optional(b)||/^[A-Za-z]{6}[0-9LMNPQRSTUV]{2}[A-Za-z]{1}[0-9LMNPQRSTUV]{2}[A-Za-z]{1}[0-9LMNPQRSTUV]{3}[A -Za-z]{1}$/.test(a)},p_iva:function(a,b){return this.optional(b)||/^\d{5}\d{6}$/.test(a)},creditcard:function(a,b){if(this.optional(b))return"dependency-mismatch";if(/[^0-9 -]+/.test(a))return false;var c=0,d=0,e=false;a=a.replace(/\D/g,"");for(var f=a.length-1;f>=0;f--){var g=a.charAt(f);var d=parseInt(g,10);if(e){if((d*=2)>9)d-=9}c+=d;e=!e}return c%10==0},no_space:function(a,b){return this.optional(b)||/^\S+$/i.test(a)},accept:function(a,b,c){c=typeof c=="string"?c.replace(/,/g,"|"):"png|jpe?g|gif";return this.optional(b)||a.match(new RegExp(".("+c+")$","i"))},equalTo:function(b,c,d){var e=a(d).unbind(".validate-equalTo").bind("blur.validate-equalTo",function(){a(c).valid()});return b==e.val()}}});a.format=a.validator.format})(jQuery);(function(a){var b={};if(a.ajaxPrefilter){a.ajaxPrefilter(function(a,c,d){var e=a.port;if(a.mode=="abort"){if(b[e]){b[e].abort()}b[e]=d}})}else{var c=a.ajax;a.ajax=function(d){var e=("mode"in d?d:a.ajaxSettings).mode,f=("port"in d?d:a.ajaxSettings).port;if(e=="abort"){if(b[f]){b[f].abort()}return b[f]=c.apply(this,arguments)}return c.apply(this,arguments)}}})(jQuery);(function(a){if(!jQuery.event.special.focusin&&!jQuery.event.special.focusout&&document.addEventListener){a.each({focus:"focusin",blur:"focusout"},function(b,c){function d(b){b=a.event.fix(b);b.type=c;return a.event.handle.call(this,b)}a.event.special[c]={setup:function(){this.addEventListener(b,d,true)},teardown:function(){this.removeEventListener(b,d,true)},handler:function(b){arguments[0]=a.event.fix(b);arguments[0].type=c;return a.event.handle.apply(this,arguments)}}})}a.extend(a.fn,{validateDelegate:function(b,c,d){return this.bind(c,function(c){var e=a(c.target);if(e.is(b)){return d.apply(e,arguments)}})}})})(jQuery);
/************* additional metod to validate multiple e-mail addresses comma separated (no space)***********/
jQuery.validator.addMethod("multiemail", function(value, element) {
                         if (this.optional(element)) // return true on optional element 
                         return true; 
                          var emails = value.split( new RegExp( "\\s*,\\s*", "gi" ) ); 
                           valid = true; 
                            for(var i in emails) { 
                                value = emails[i]; 
                                valid=valid && jQuery.validator.methods.email.call(this, value, element); 
                            } 
                            return valid; 
},jQuery.validator.messages.email+' or multiple addresses separate by comma');
/************* additional metod to validate date like mm/dd/yyyy ***********/
jQuery.validator.addMethod("datemmddyyyy", function(value, element) {
		return this.optional(element) || /^(?=\d)(?:(?:(?:(?:(?:0?[13578]|1[02])(\/|-|\.)31)\1|(?:(?:0?[1,3-9]|1[0-2])(\/|-|\.)(?:29|30)\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})|(?:0?2(\/|-|\.)29\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))|(?:(?:0?[1-9])|(?:1[0-2]))(\/|-|\.)(?:0?[1-9]|1\d|2[0-8])\4(?:(?:1[6-9]|[2-9]\d)?\d{2}))($|\ (?=\d)))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\ [AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/.test(value) || /^([\__]+)([\/]+)([\__]+)([\/]+)([\____]+)$/.test(value);
},jQuery.validator.messages.data );
/************* additional metod to validate date like dd/mm/yyyy ***********/
jQuery.validator.addMethod("dateddmmyyyy", function(value, element) {
		return this.optional(element) || /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/.test(value) || /^([\__]+)([\/]+)([\__]+)([\/]+)([\____]+)$/.test(value);
},jQuery.validator.messages.data );
/***************** additional method for least one checkbox field **********/
jQuery.validator.addMethod("require_from_group", function(value, element, options) {
	var validator = this;
	var selector = options[1];
	var validOrNot = $(selector, element.form).filter(function() {
		return $(this).val();
	}).length >= options[0];

	if(!$(element).data('being_validated')) {
		var fields = $(selector, element.form);
		fields.data('being_validated', true);
	    $(element.form).valid();
		fields.data('being_validated', false);
	}
	return validOrNot;
},jQuery.validator.messages.least_one );

/***************************** adapted to bootstrap *********************************************************/
$.extend($.validator.prototype, {
  showLabel: function(element, message) {
    var label = this.errorsFor( element );

    if (label.length == 0) {
      var railsGenerated = $(element).next('span.help-inline');
      if (railsGenerated.length) {
        railsGenerated.attr('for', this.idOrName(element))
        railsGenerated.attr('generated', 'true');
        label = railsGenerated;
      }
    }

    if (label.length) {
      // refresh error/success class
      label.removeClass(this.settings.validClass).addClass(this.settings.errorClass);
      // check if we have a generated label, replace the message then
      label.attr('generated') && label.html(message);
    } else {
      // create label
      label = $('<' + this.settings.errorElement + '/>')
        .attr({'for':  this.idOrName(element), generated: true})
        .addClass(this.settings.errorClass)
        .addClass('help-inline')
        .html(message || '');
      if (this.settings.wrapper) {
        // make sure the element is visible, even in IE
        // actually showing the wrapped element is handled elsewhere
        label = label.hide().show().wrap('<' + this.settings.wrapper + '/>').parent();
      }
      if (!this.labelContainer.append(label).length)
        this.settings.errorPlacement
          ? this.settings.errorPlacement(label, $(element))
          : label.insertAfter(element);
    }
    if (!message && this.settings.success) {
      label.text('');
      typeof this.settings.success == 'string'
        ? label.addClass(this.settings.success)
        : this.settings.success(label);
    }
    this.toShow = this.toShow.add(label);
  }
});
$.extend($.validator.defaults, {
    errorClass: 'error',
    validClass: 'success',
	errorPlacement: function (error, input) {
	   $(input).closest('.controls').append(error);
	},
    highlight: function(element, errorClass, validClass) {
      if (element.type === 'radio') {
        this.findByName(element.name).closest('.control-group').removeClass(validClass).addClass(errorClass);
      } else {
        $(element).closest('.control-group').removeClass(validClass).addClass(errorClass);
      }
    },
    unhighlight: function(element, errorClass, validClass) {
      if (element.type === 'radio') {
        this.findByName(element.name).closest('.control-group').removeClass(errorClass).addClass(validClass);
      } else {
        $(element).closest('.control-group').removeClass(errorClass).addClass(validClass);
      }
    }
  });
if (typeof check_if_jsIn_exists == 'function') {  
/***************************** /adapted to bootstrap *********************************************************/  
        jQuery.extend(jQuery.validator.messages, {
		   required:__('validator_required'),
		   remote:__('validator_remote'),
		   email:__('validator_email'),
		   website:__('validator_website'),
		   websitehttp:__('validator_websitehttp'),
		   url:__('validator_url'),
		   equalTo:__('validator_equalTo'),
		   date:__('validator_date'),
		   dateISO:__('validator_dateISO'),
		   number:__('validator_number'),
		   data:__('validator_data'),
		   accept:__('validator_accept'),
		   maxlength:jQuery.validator.format(__('validator_maxlength')),
		   minlength:jQuery.validator.format(__('validator_minlength')),
		   rangelength:jQuery.validator.format(__('validator_rangelength')),
		   range:jQuery.validator.format(__('validator_range')),
		   max:jQuery.validator.format(__('validator_max')),
		   min:jQuery.validator.format(__('validator_min')),
		   least_one:jQuery.validator.format(__('validator_least_one'))	
		}); 
}else{
        jQuery.extend(jQuery.validator.messages, {	
		   required:'Required Field.',
		   remote:'Existing Value.',
		   email:'Invalid E-mail address.',
		   website:'Wrong Url.',
		   websitehttp:'Wrong Url.',
		   url:'Wrong Url.',
		   equalTo:'Passwords do not match.',
		   date:'Invalid date.',
		   dateISO:'Please enter a valid date (ISO).',
		   number:'Only Numbers',
		   data:'Invalid date',
		   accept:'Extension is not accepted.',
		   maxlength:jQuery.validator.format('Maximum {0} characters.'),
		   minlength:jQuery.validator.format('At least {0} characters.'),
		   rangelength:jQuery.validator.format('Only characters between {0} and {1} of length.'),
		   range:jQuery.validator.format('Only values ​​between {0} and {1}.'),
		   max:jQuery.validator.format('Only values ​​less than or equal to {0}.'),
		   min:jQuery.validator.format('Only values ​​greater than or equal to {0}.'),
		   least_one:jQuery.validator.format('Fill in at least {0} field correctly.')		   
		}); 			
}
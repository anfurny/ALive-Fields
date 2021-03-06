/*!
 * ALive Fields V 1.0
 * http://alexrohde.com/
 *
 * Copyright 2011, Alex Rohde
 * Licensed under the GPL Version 2 license.
 *
 * Includes jquery.js, jqueryUI.js
 * http://jquery.com/ , http://jqueryui.com
 * Copyright 2011, John Resig
 *
 * Last Revision: 
 * Date: Oct 11 2011 2:00PM
 */

/**
* @class AcAppendbox A basic control for loading and appending to fields that can be represented as simple strings. Is initialized with an &lt;input&gt; (or &lt;textarea&gt;) tag.
* @extends AcTextbox
* @requires AcControls.js and its dependencies
*/
if (typeof(handleError) == "undefined")
    handleError = alert;
        
if (typeof(AcTextbox) == "undefined" )
    handleError("AcAppendbox requires AcTextbox to be included");

AcAppendbox = function (field,table,pkey,loadable,savable,dependentFields)
{
 AcTextbox.call(this, field,table,pkey,loadable,savable,dependentFields); //call parent constructor. Must be done this way-ish
}

AcAppendbox.prototype = new AcTextbox();  // Here's where the inheritance occurs
AcAppendbox.prototype.constructor=AcAppendbox; // Otherwise instances of AcAppendbox would have a constructor of AcTextbox
AcAppendbox.prototype.parent = AcTextbox.prototype;


AcAppendbox.prototype.initialize = function(pkey)
{
 AcTextbox.prototype.initialize.call(this, pkey);    

 this.correspondingElement.keyup(this, function (param) {
      return param.data.handleKeyup(param);
      } ) ; 
 this.lock('loading');
}

////////////////////////////////////////////////////////
/**
* @description Called AFTER a key is pressed. 
*/
AcAppendbox.prototype.handleKeyup = function()
{
    if (! this.minimalValue)
        return false;
        
    var x = this.getValue();
    var sepChar = String.fromCharCode(167);
    
 //This part is in case they added enough characters to make deletes not go below thresh-hold

    if (x.indexOf(sepChar ) == -1)
        return this.setValue(this.minimalValue + sepChar + ' ');
        
    else if (x.indexOf(sepChar ) != this.minimalValue.length)
        return this.setValue(this.minimalValue + sepChar  + "" + x.substr(x.indexOf(sepChar)+2));
    else if (x.substring(x.indexOf(sepChar), x.indexOf(sepChar) + 2) != String.fromCharCode(167) + " ")
        return this.setValue(this.minimalValue + sepChar  + " " + x.substr(x.indexOf(sepChar)+1));
        
}
////////////////////////////////////////////////////////

AcAppendbox.prototype.afterLoad = function()
{
    var sepChar = String.fromCharCode(167);
    this.minimalValue = this.getValue();//important space
    this.setValue(this.getValue() + sepChar + ' '); //divider
    this.unlock('loading');    
}
////////////////////////////////////////////////////////

AcAppendbox.prototype.getValueForSave = function()
{
    var sepChar = String.fromCharCode(167);
    var result = this.getValue();
    
    this.setValue(this.minimalValue + sepChar + ' ' + result.substr(result.indexOf(sepChar)+2));
    
    return this.minimalValue + ' ' + result.substr(result.indexOf(sepChar)+2);
}

AcAppendbox.prototype.getAppend = function()
{
    var sepChar = String.fromCharCode(167);
    var result = this.getValue();
    
    this.setValue(this.minimalValue + sepChar + ' ' + result.substr(result.indexOf(sepChar)+2));
    
    return  result.substr(result.indexOf(sepChar)+2);
}

AcAppendbox.prototype.saveField = function(primaryKeyData )
{
    if (!this.getAppend().length)
        return; //no change in field.
        
    AcField.prototype.saveField.call(this, primaryKeyData); //breakpoint this
        
    if (this.pkeyField == "DispatchID")
        {
        setFieldAjax([["DispatchID",primaryKeyData], ["ActionID", "24"], ["StaffID", CurrentUserID()], ["HistDateTime", mssqlTimestamp()], ["HistNotes", this.correspondingField + " Appended: " + this.getAppend() ]], [] , "DispatchHistory", "insert");

//        setFieldAjax(fields, pkeys, table, "INSERT");
        }//Now declare function CurrentUSER  and test this.
    
}

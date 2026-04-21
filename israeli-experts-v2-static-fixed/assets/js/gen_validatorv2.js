/*
  -------------------------------------------------------------------------
	                    JavaScript Form Validator 
                                Version 2.0.2
	Copyright 2003 JavaScript-coder.com. All rights reserved.
	You use this script in your Web pages, provided these opening credit
    lines are kept intact.
	The Form validation script is distributed free from JavaScript-Coder.com

	You may please add a link to JavaScript-Coder.com, 
	making it easy for others to find this script.
	Checkout the Give a link and Get a link page:
	http://www.javascript-coder.com/links/how-to-link.php

    You may not reprint or redistribute this code without permission from 
    JavaScript-Coder.com.
	
	JavaScript Coder
	It precisely codes what you imagine!
	Grab your copy here:
		http://www.javascript-coder.com/
    -------------------------------------------------------------------------  
*/

var lastErrorObj = null;

function Validator(frmname)
{
  	this.formobj=document.forms[frmname];
	if(!this.formobj)
	{
	  	alert("BUG: couldnot get Form object "+frmname);
		return;
	}
	

/*	if(this.formobj.onsubmit)
	{
	 	this.formobj.old_onsubmit = this.formobj.onsubmit;
	 	this.formobj.onsubmit=null;
	}
	else
	{
	 	this.formobj.old_onsubmit = null;
	}
	
	this.formobj.onsubmit=form_submit_handler;
*/
	this.validate = validate;
	this.addValidation = add_validation;
	this.setAddnlValidationFunction=set_addnl_vfunction;
	this.clearAllValidations = clear_all_validations;
	this.getLastErrorField = get_lastErrorField;
}

function set_addnl_vfunction(functionname)
{
  	this.formobj.addnlvalidation = functionname;
}

function clear_all_validations()
{
	for(var itr=0;itr < this.formobj.elements.length;itr++)
	{
		this.formobj.elements[itr].validationset = null;
	}
}

function validate ()
{
	return (form_submit_handler (this.formobj));
}

function form_submit_handler(form)
{
	if (form == undefined)
		form = this;

	for(var itr=0;itr < form.elements.length;itr++)
	{
		if(form.elements[itr].validationset &&
	   !form.elements[itr].validationset.validate())
		{
		  return false;
		}
	}
	if(this.addnlvalidation)
	{
	  str =" var ret = "+this.addnlvalidation+"()";
	  eval(str);
    if(!ret) return ret;
	}
	return true;
}

function add_validation (itemname,descriptor,errstr,emptystr)
{
  	if(!this.formobj)
	{
	  	alert("BUG: the form object is not set properly");
		return;
	}
	
	var itemobj = this.formobj[itemname];
  	if(!itemobj)
	{
	  	alert("BUG: Could not get the input object named: "+itemname);
		return;
	}
	
	if(!itemobj.validationset)
	{
	  itemobj.validationset = new ValidationSet(itemobj);
	}
	
  	itemobj.validationset.add(descriptor,errstr,emptystr);
}

function ValidationDesc (inputitem,desc,error,empty)
{
  	this.desc=desc;
	this.error=error;
	this.empty=empty;
	this.itemobj = inputitem;
	this.validate=vdesc_validate;
}

function vdesc_validate()
{
 	if(!V2validateData(this.desc,this.itemobj,this.error,this.empty))
 	{
   	  	this.itemobj.focus();
		lastErrorObj = this.itemobj;
		return false;
 	}
 	return true;
}

function get_lastErrorField ()
{
	return lastErrorObj;
}


function ValidationSet(inputitem)
{
    this.vSet=new Array();
	this.add= add_validationdesc;
	this.validate= vset_validate;
	this.itemobj = inputitem;
}

function add_validationdesc(desc,error,empty)
{
  	this.vSet[this.vSet.length]= 
	new ValidationDesc(this.itemobj,desc,error,empty);
}

function vset_validate()
{
  	for(var itr=0;itr<this.vSet.length;itr++)
	{
	   	if(!this.vSet[itr].validate())
		{
			return false;
		}
	}
	return true;
}

function validateEmailv2(email)
{
	// a very simple email validation checking. 
	// you can add more complex email checking if it helps 

    if(email.length <= 0)
	{
	  return true;
	}
	
    var splitted = email.match(/^(.+)@(.+)$/);
    if(splitted == null) return false;
    if(splitted[1] != null )
    {
      var regexp_user=/^\"?[\w-_\.]*\"?$/;
      if(splitted[1].match(regexp_user) == null) return false;
    }
    if(splitted[2] != null)
    {
      var regexp_domain=/^[\w-\.]*\.[A-Za-z]{2,4}$/;
      if(splitted[2].match(regexp_domain) == null) 
      {
	    var regexp_ip =/^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
	    if(splitted[2].match(regexp_ip) == null) return false;
      }// if
      return true;
    }
	return false;
}

function TZValidator(id)
{
	var sID = String(id);
	if ((sID.length != 9) || (isNaN(sID)))
		return false;
	var counter = 0, incNum;
	for (var i = 0; i < 9; i++)
	{
		incNum = Number(sID.charAt(i));
		incNum *= (i % 2) + 1;
		if (incNum > 9)
			incNum -= 9;
		counter += incNum;
	}
	return (counter % 10 == 0);
}

function V2validateData(strValidateStr,objValue,strError,strEmpty) 
{ 
    var epos = strValidateStr.indexOf("="); 
    var  command  = ""; 
    var  cmdvalue = ""; 
    if(epos >= 0) 
    { 
     command  = strValidateStr.substring(0,epos); 
     cmdvalue = strValidateStr.substr(epos+1); 
    } 
    else 
    { 
     command = strValidateStr; 
    } 
    switch(command) 
    { 
        case "req": 
        case "required": 
         { 
           if(eval(objValue.value.length) == 0 || (strEmpty != "" && objValue.value == strEmpty)) 
           { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : Required Field"; 
              }//if 
	    	  objValue.focus();
              alert(strError); 
              return false; 
           }//if 
           break;             
         }//case required 
        case "maxlength": 
        case "maxlen": 
          { 
             if(eval(objValue.value.length) >  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : "+cmdvalue+" characters maximum "; 
               }//if 
	    	   objValue.focus();
               alert(strError); // + "\n[Current length = " + objValue.value.length + " ]"); 
               return false; 
             }//if 
             break; 
          }//case maxlen 
        case "minlength": 
        case "minlen": 
           { 
             if(eval(objValue.value.length) <  eval(cmdvalue) && eval(objValue.value.length) > 0) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : " + cmdvalue + " characters minimum  "; 
               }//if               
	    	   objValue.focus();
               alert(strError); // + "\n[Current length = " + objValue.value.length + " ]"); 
               return false;                 
             }//if 
             break; 
            }//case minlen 
        case "alnum": 
        case "alphanumeric": 
           { 
              var charpos = objValue.value.search(/^\w+$/); 
              if(objValue.value.length > 0 &&  charpos == -1) 
              { 
               if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only alpha-numeric characters allowed "; 
                }//if 
	    	    objValue.focus();
                alert(strError); // + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 
              break; 
           }//case alphanumeric 
        case "num": 
        case "numeric": 
           { 
              var charpos = objValue.value.search(/^\d+$/); 
              if(objValue.value.length > 0 &&  charpos == -1) 
              { 
                if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only digits allowed "; 
                }//if               
	    	    objValue.focus();
                alert(strError);
				//+ "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 
              break;               
           }//numeric 
        case "phone": 
           { 
			if (objValue.value == "" || (strEmpty != "" && objValue.value == strEmpty)) return true;
			if (objValue.value.indexOf("-") != -1)
			{
				regexp1  = new RegExp("0[23489]-[0-9]{7}$");
				regexp2  = new RegExp("05[0-9]-[0-9]{7}$");
				regexp3  = new RegExp("07-[0-9]{8}$");
				regexp4  = new RegExp("077-[0-9]{7}$");
				regexp5  = new RegExp("01[2789]-[0-9]{7}$");
				regexp6  = new RegExp("1-700-[0-9]{6}$");
				regexp7  = new RegExp("1-800-[0-9]{6}$");
				regexp8  = new RegExp("1-700-[0-9]{2}-[0-9]{2}-[0-9]{2}$");
				regexp9  = new RegExp("1-800-[0-9]{2}-[0-9]{2}-[0-9]{2}$");
				regexp10 = new RegExp("1-700-[0-9]{3}-[0-9]{3}$");
				regexp11 = new RegExp("1-800-[0-9]{3}-[0-9]{3}$");

				if (!regexp1.test(objValue.value) &&
					!regexp2.test(objValue.value) &&
					!regexp3.test(objValue.value) &&
					!regexp4.test(objValue.value) &&
					!regexp5.test(objValue.value) &&
					!regexp6.test(objValue.value) &&
					!regexp7.test(objValue.value) &&
					!regexp8.test(objValue.value) &&
					!regexp9.test(objValue.value) &&
					!regexp10.test(objValue.value) &&
					!regexp11.test(objValue.value))
				{
	    	   	    objValue.focus();
	                alert(strError);
                	return false; 
				}
			}
			else
			{
				regexp1 = new RegExp("0[234895][0-9]{7}$");
				regexp2 = new RegExp("0[57][0-9]{8}$");
				regexp3 = new RegExp("01[2789][0-9]{7}$");
				regexp4 = new RegExp("1[78]00[0-9]{6}$");

				if (!regexp1.test(objValue.value) &&
				    !regexp2.test(objValue.value) &&
				    !regexp3.test(objValue.value) &&
				    !regexp4.test(objValue.value))
				{
	    	   		objValue.focus();
	                alert(strError);
                	return false; 
				}
			}
              break;               
           }//phone 
        case "homephone": 
           { 
			if (objValue.value == "") return true;
			if (objValue.value.indexOf("-") != -1)
			{
				regexp1  = new RegExp("0[23489]-[0-9]{7}$");
				regexp2  = new RegExp("07-[0-9]{8}$");
				regexp3  = new RegExp("077-[0-9]{7}$");
				regexp4  = new RegExp("01[2789]-[0-9]{7}$");
				regexp5  = new RegExp("1-700-[0-9]{6}$");
				regexp6  = new RegExp("1-800-[0-9]{6}$");
				regexp7  = new RegExp("1-700-[0-9]{2}-[0-9]{2}-[0-9]{2}$");
				regexp8  = new RegExp("1-800-[0-9]{2}-[0-9]{2}-[0-9]{2}$");
				regexp9  = new RegExp("1-700-[0-9]{3}-[0-9]{3}$");
				regexp10 = new RegExp("1-800-[0-9]{3}-[0-9]{3}$");

				if (!regexp1.test(objValue.value) &&
					!regexp2.test(objValue.value) &&
					!regexp3.test(objValue.value) &&
					!regexp4.test(objValue.value) &&
					!regexp5.test(objValue.value) &&
					!regexp6.test(objValue.value) &&
					!regexp7.test(objValue.value) &&
					!regexp8.test(objValue.value) &&
					!regexp9.test(objValue.value) &&
					!regexp10.test(objValue.value))
				{
	    	   	    objValue.focus();
	                alert(strError);
                	return false; 
				}
			}
			else
			{
				regexp1 = new RegExp("0[23489][0-9]{7}$");
				regexp2 = new RegExp("01[2789][0-9]{7}$");
				regexp3 = new RegExp("1[78]00[0-9]{6}$");
				regexp4  = new RegExp("077[0-9]{7}$");

				if (!regexp1.test(objValue.value) &&
				    !regexp2.test(objValue.value) &&
				    !regexp3.test(objValue.value) &&
				    !regexp4.test(objValue.value))
				{
	    	   	    objValue.focus();
	                alert(strError);
                	return false; 
				}
			}
              break;               
           }//phone 
        case "mobile": 
           { 
			if (objValue.value == "") return true;
			if (objValue.value.indexOf("-") != -1)
			{
				regexp1 = new RegExp("05[0-9]-[0-9]{7}$");

				if (!regexp1.test(objValue.value))
				{
	    	   		objValue.focus();
	                alert(strError);
                	return false; 
				}
			}
			else
			{
				regexp1 = new RegExp("05[0-9]{7}$");
				regexp2 = new RegExp("0[57][0-9]{8}$");

				if (!regexp1.test(objValue.value) &&
				    !regexp2.test(objValue.value))
				{
	    	   		objValue.focus();
	                alert(strError);
                	return false; 
				}
			}
              break;               
           }//phone 
        case "globalphone": 
           { 
		if (objValue.value == "") return true;
		regexp1 = new RegExp("[0-9\-\+\.]+");
		if (!regexp1.test(objValue.value))
		{
	    	   		objValue.focus();
	                alert(strError);
       	        	return false; 
		}
		break;               
           }//gloablphone 
        case "alphabetic": 
        case "alpha": 
           { 
			  var regexp1 = new RegExp("[A-Za-zא-ת]+");
			  var regexp2 = new RegExp("[0-9\-\+\.]+");
              if(objValue.value.length > 0 && (!regexp1.test(objValue.value) || regexp2.test(objValue.value)))
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": Only alphabetic characters allowed "; 
                }//if                             
	    	   	objValue.focus();
                alert(strError);
				//+ "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 
              break; 
           }//alpha 
		case "alnumhyphen":
			{
              var charpos = objValue.value.search(/^[a-zA-Z0-9-_]+$/); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = objValue.name+": characters allowed are A-Z,a-z,0-9,- and _"; 
                }//if                             
	    	   	objValue.focus();
                alert(strError);
				//+ "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 			
			break;
			}
        case "email": 
          { 
			   if (strEmpty != "" && objValue.value == strEmpty) return true;
               if(!validateEmailv2(objValue.value)) 
               { 
                 if(!strError || strError.length ==0) 
                 { 
                    strError = objValue.name+": Enter a valid Email address "; 
                 }//if                                               
	    	   	 objValue.focus();
                 alert(strError); 
                 return false; 
               }//if 
           break; 
          }//case email 
        case "lt": 
        case "lessthan": 
         { 
            if(isNaN(objValue.value)) 
            { 
	    	  objValue.focus();
              alert(objValue.name+": Should be a number "); 
              return false; 
            }//if 
            if(eval(objValue.value) >=  eval(cmdvalue)) 
            { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : value should be less than "+ cmdvalue; 
              }//if               
	    	  objValue.focus();
              alert(strError); 
              return false;                 
             }//if             
            break; 
         }//case lessthan 
        case "gt": 
        case "greaterthan": 
         { 
            if(isNaN(objValue.value)) 
            { 
	    	  objValue.focus();
              alert(objValue.name+": Should be a number "); 
              return false; 
            }//if 
             if(eval(objValue.value) <=  eval(cmdvalue)) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = objValue.name + " : value should be greater than "+ cmdvalue; 
               } //if               
	    	   objValue.focus();
               alert(strError); 
               return false;                 
             }//if             
            break; 
         }//case greaterthan 
        case "regexp": 
         { 
		 	if(objValue.value.length > 0)
			{
	            if(!objValue.value.match(cmdvalue)) 
	            { 
	              if(!strError || strError.length ==0) 
	              { 
	                strError = objValue.name+": Invalid characters found "; 
	              }//if                                                               
	    	  	  objValue.focus();
	              alert(strError); 
	              return false;                   
	            }//if 
			}
           break; 
         }//case regexp 
        case "dontselect": 
         { 
            if(objValue.selectedIndex == null) 
            { 
	    	  objValue.focus();
              alert("BUG: dontselect command for non-select Item"); 
              return false; 
            } 
            if(objValue.selectedIndex == eval(cmdvalue)) 
            { 
             if(!strError || strError.length ==0) 
              { 
              strError = objValue.name+": Please Select one option "; 
              }//if                                                               
	    	  objValue.focus();
              alert(strError); 
              return false;                                   
             } 
             break; 
         }//case dontselect 
        case "tz": 
          { 
               if(!TZValidator(objValue.value)) 
               { 
                 if(!strError || strError.length ==0) 
                 { 
                    strError = objValue.name+": Enter a valid TZ "; 
                 }//if                                               
	    	  	 objValue.focus();
                 alert(strError); 
                 return false; 
               }//if 
           break; 
          }//case tz 
        case "checkbox": 
         { 
           if(objValue.checked == false) 
           { 
              if(!strError || strError.length ==0) 
              { 
                strError = objValue.name + " : Required Field"; 
              }//if 
	    	  objValue.focus();
              alert(strError); 
              return false; 
           }//if 
           break;             
         }//case required 
    }//switch 
    return true; 
}
/*
	Copyright 2003 JavaScript-coder.com. All rights reserved.
*/

<?PHP

// to do: move each of these to their own files and use autoloaders. 

class AcCheckbox extends AcField 
{
function get_field_type_for_javascript()
	{
	return "AcCheckbox"	;
	}	
	
function do_js_includes_for_this_control()
	{  //Unique to AcField
		AcField::include_js_file(AcField::$path_to_jquery);			
		AcField::include_js_file(Acfield::$path_to_controls . "/AcControls.js");	
		AcField::include_js_file(Acfield::$path_to_controls . "/AcCheckbox/AcCheckbox.js");			
	}
}


class AcDatebox extends AcField
{
function get_field_type_for_javascript()
	{
	return "AcDatebox"	;
	}	
	
	function do_js_includes_for_this_control()
	{  //Unique to AcField
		AcField::include_js_file(AcField::$path_to_jquery);		
		AcField::include_js_file(AcField::$path_to_jqueryui);		
		AcField::include_js_file(Acfield::$path_to_controls . "/AcControls.js");	
		AcField::include_js_file(Acfield::$path_to_controls . "/AcTextbox/AcTextbox.js");	//not a typo
		AcField::include_js_file(Acfield::$path_to_controls . "/AcDatebox/AcDatebox.js");			
	}
}

class AcTextbox extends AcField
{
function get_field_type_for_javascript()
	{
	return "AcTextbox"	;
	}	
	
	function do_js_includes_for_this_control()
	{  //Unique to AcField
		AcField::include_js_file(AcField::$path_to_jquery);		
		AcField::include_js_file(Acfield::$path_to_controls . "/AcControls.js");	
		AcField::include_js_file(Acfield::$path_to_controls . "/AcTextbox/AcTextbox.js");	//not a typo
	}
}

/***************************************************************
*  Copyright notice
*  
*  (c) 2006 Wolfgang Zenker (zenker@punkt.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is 
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
* 
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
* 
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/** 
 * Javascript functions for web form template handler (part of the library extension 'pt_tools')
 *
 * $Id: tx_pttools_formTemplateHandler.js,v 1.4 2008/08/15 16:20:53 ry96 Exp $
 *
 * @author  Wolfgang Zenker <zenker@punkt.de>
 * @since   2006-05-18
 */ 

function tx_pttools_formTemplateHandler_setchoice(fname)
{
	var selname = "choices-" + fname;
	var selector = document.getElementById(selname);
	document.getElementById(fname).value
		= selector.options[selector.selectedIndex].text;
	return true;
}

function tx_pttools_formTemplateHandler_chooser(fname, firstempty, sval)
{
	document.writeln('<img src="typo3conf/ext/pt_tools/res/img/left.gif" />');
	document.write('<select onChange=\'return tx_pttools_formTemplateHandler_setchoice("');
	document.write(fname);
	document.write('");\' id="choices-');
	document.write(fname);
	document.write('" name="choices-');
	document.write(fname);
	document.writeln('" size="1">');
	if (firstempty)
	{
		document.writeln('<option> </option>');
	}
	for (i = 3; i < arguments.length; i++)
	{
		document.write('<option');
		if (sval == arguments[i])
		{
			document.write(' selected');
		}
		document.write('> ');
		document.write(arguments[i]);
		document.writeln('</option>');
	}
	document.writeln('</select>');
}

function tx_pttools_formTemplateHandler_resetForm(button)
{
	var form = button.form;
	for (var i = 0; i < form.elements.length; i++)
	{
		if (form.elements[i].disabled || form.elements[i].readonly)
		{
			continue;
		}
		switch (form.elements[i].type)
		{
			case "checkbox":
			case "radio":
				form.elements[i].checked = false;
				break;
			case "select-one":
			case "select-multiple":
				form.elements[i].selectedIndex = -1;
				break;
			case "password":
			case "text":
			case "textarea":
				form.elements[i].value = "";
				break;
		}
	}
	return false;
}

function tx_pttools_formTemplateHandler_setCheckboxes(button)
{
	var form = button.form;
	for (var i = 0; i < form.elements.length; i++)
	{
		if (form.elements[i].disabled || form.elements[i].readonly)
		{
			continue;
		}
		if (form.elements[i].type == "checkbox")
		{
			form.elements[i].checked = true;
		}
	}
	return false;
}

function tx_pttools_formTemplateHandler_invertCheckboxes(button)
{
	var form = button.form;
	for (var i = 0; i < form.elements.length; i++)
	{
		if (form.elements[i].disabled || form.elements[i].readonly)
		{
			continue;
		}
		if (form.elements[i].type == "checkbox")
		{
			c = form.elements[i].checked;
			form.elements[i].checked = ! c;
		}
	}
	return false;
}


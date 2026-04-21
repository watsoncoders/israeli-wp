function goPrint (divname, direction)
{
	html = "<html dir='" + direction + "'>" +
		   "<head>" +
		   "<title>Printing...</title>" +
		   "<link href='css/layouts.css' rel='stylesheet' type='text/css'>" +
		   "<link href='common.css' rel='stylesheet' type='text/css'>" +
		   "</head>" +
		   "<body onload='window.print();window.close()'>" +
			"<div align='right'>" +
				document.getElementById(divname).innerHTML + "<br/>" +
			"</div>" +
		   "</body>" +
	       "</html>";

	win = window.open  ('print.html', '_blank');
	win.document.open  ();
	win.document.write (html);
	win.document.close ();
}

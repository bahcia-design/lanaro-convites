/*
    jsinnerdoc.js
    Version: 1.00

    innerHTML Document Loader
    Powered by Willian Fernando Soares
    wildsoares@hotmail.com
    http://zttecnologias.com.br/williansoares
    http://twitter.com/wildsoares


    === Versions Note ===
    1.00 -> First version
*/

function loadDocument(path, elementID) {
  pDoc = null;
  if (window.XMLHttpRequest) {
    pDoc = new XMLHttpRequest(); // firefox, ie 7+
  } else if (window.ActiveXObject) {
    pDoc = new ActiveXObject("Microsoft.XMLHTTP"); // ie 6-
  }

  if (pDoc) {
  	 pDoc.open("GET",path,false);
  	 pDoc.send("");
  	 if ((pDoc.readyState==4)&&(pDoc.status==200)) {
  	   element = document.getElementById(elementID);
  	   if ((element)&&(element.tagName=="DIV")) { 	
  	     element.innerHTML = pDoc.responseText;
    	  return true;  	   	
  	   } else {
        return false;
  	   }
  	 } else {
      return false;
  	 }
  } else {
    return false;
  }
}
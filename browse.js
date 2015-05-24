function getData5(req,reqdata,docpart)
{
         var XMLHttpRequestObject = false;
         if (window.XMLHttpRequest)
         {
            XMLHttpRequestObject = new XMLHttpRequest();
         }
         else if (window.ActiveXObject)
         {
           XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
         }
         if (XMLHttpRequestObject)
         {
			if (req == "querysubsubproducts")
			{
				XMLHttpRequestObject.open("POST","querysubsubproducts.php");
                   XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                   XMLHttpRequestObject.onreadystatechange = function()
                   {
                       if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                       {
                            subproductchangeresponse(XMLHttpRequestObject.responseXML);
                            delete XMLHttpRequestObject;
                            XMLHttpRequestObject = null;
                       }
                   }
				   XMLHttpRequestObject.send("reqtype=" + req + "&" + reqdata);

			}
		 }
}

function subproductchangeresponse(xmldocument)
{
	var xmlresp = xmldocument.documentElement;
    var response = xmlresp.getElementsByTagName("response");
	var responsevalue = response[0].getAttribute("its");
	if (responsevalue = "success")
    {
		var rsubsubproducts = xmlresp.getElementsByTagName("subsubproduct");
		subsubproducts = new Array();
		for (var i = 0; i <rsubsubproducts.length ; i++)
		{
			subsubproducts[i] =rsubsubproducts[i].childNodes[0].nodeValue;
	    	subsubproductsids[i] = parseInt(rsubsubproducts[i].getAttribute("id"));	
		}
	}
	loadleftdata();
}
function subproductchange(subproduct)
{

	var selection = subproduct.options[subproduct.selectedIndex].value;
	selsubproduct = subproduct.selectedIndex;
	selsubsubproduct = 0;
	subsubproducts = null;
	if (selsubproduct == 0)
		loadleftdata();
	else
	{
		if (selproduct == 2)
		{
			document.searchform.search_string.value = subproducts[selsubproduct];
			getData("search", "search_string=" + subproducts[selsubproduct]); 
		        loadleftdata();
		}
		else
		{
		        getData5("querysubsubproducts","subproductid=" + selection);
		}
	}
}
function subsubproductchange(ssp)
{
	var selection = ssp.options[ssp.selectedIndex].value;
	selsubsubproduct = ssp.selectedIndex;
	if (selsubsubproduct != 0)
	{
			document.searchform.search_string.value = subsubproducts[selsubsubproduct];
		getData("search","search_string=" + subsubproducts[selsubsubproduct]);
	}
}

function highgetData(search,key)
{
	key = decodeURIComponent(key);
    document.searchform.search_string.value = key;
   getData(search,"search_string=" + encodeURIComponent(key));
}

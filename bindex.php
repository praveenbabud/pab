<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<link rel="shortcut icon" href="favicon.ico">
<title> Online Text Book Store
</title>
<script language = "javascript">

var name = "";
var email = "";
var hno = "";
var street = "";
var village = "";
var city = "";
var state = "";
var pincode = "";
var telephone = "";
var userat="";
var loginattempts = 0;
var products = new Array();
var productsids = new Array();
var subproducts = null; 
var subproductsids = new Array();
var subsubproducts = null; 
var subsubproductsids = new Array();
var selproduct = 0;
var selsubproduct = 0;
var selsubsubproduct = 0;
var newbookstitle = new Array();
var newbooksauthor = new Array();
var newbooksimage = new Array();
var newbookslp = new Array();
var newbooksop = new Array();
var newbooksisbn13 = new Array();
var linkvalue = new Array();
var linktitle = new Array();
products[0] = 'Select Product';
productsids[0] = 0;
products[1] = 'Text Books';
productsids[1] = 1;
/*products[2] = 'Competitive Exam Books';
productsids[2] = 2;*/
var shipto = "bangalore";
var currentpage = 0;
function cleardata()
{
name = "";
email = "";
hno = "";
street = "";
village = "";
city = "";
state = "";
pincode = "";
telephone = "";
userat="";
loginattempts = 0;
shipto = "bangalore";
}


function cartitem (image, listprice,ourprice,quantity,isbn13,title)
{
	this.image = image;
	this.title = title;
	this.isbn10 = 0;
	this.isbn13 = isbn13;
	this.listprice = listprice;
	this.ourprice = ourprice;
	this.quantity = quantity;
}
function LinkedList()
{
    this.head = null;
    this.add = addlistnodetohead;
    this.remove = removelistnode;
    this.addbefore = addlistnodebefore;
}
var cart = new LinkedList();
var vcart = null;
function isempty()
{
    if (this.head == null)
       return true;
    else
       return false;
}
function addlistnodebefore(data, bdata)
{

	listnode = new ListNode(data);
	var curr = this.head;
	var prev = null;
	if (this. head == null)
	{
		this.head = listnode;
	}
	else if (this.head.data.image == bdata.image)
	{
               listnode.next = this.head;
	       this.head = listnode;
	}
	else
	{
		var curr = this.head.next;
		var prev = this.head;
		while (curr != null)
		{
			if (curr.data.image == bdata.image)
			{
				listnode.next = curr;
				prev.next = listnode;
				return;
			}
			prev = curr;
			curr = curr.next;
		}

	}
}

function addlistnodetohead(data)
{
    listnode = new ListNode(data);
    if (this.head == null)
    {
         this.head = listnode;
    }
    else
    {
        listnode.next = this.head;
        this.head = listnode;
    }
}

function removelistnode(data)
{
    if (this.head != null)
    {
        if (this.head.data.isbn13 == data)
        {
            var tmp = this.head;
            this.head = this.head.next;
	    delete tmp.data;
	    delete tmp;
        }
        else
        {
            var prev = this.head;
            var curr = this.head.next;
            while (curr != null)
            {
                if (curr.data.isbn13 == data)
                {
                     break;
                }
                else
                {
                    prev = curr;
                    curr = curr.next;
                }
            }
            if (curr != null)
            {
                prev.next = curr.next;
		delete curr.data;
		delete curr;
            }
        }
    }
}


function ListNode(data)
{
    this.data = data;
    this.next = null;
}
function getorderstring()
{
	var orderstring = "";
	var isbndata = "";
	var curr = cart.head;
	var count = 1;
	while (curr != null)
	{
		var isbn = "";
		if (curr.data.isbn13 != "")
		{
				isbn = count + "isbn13";
				isbndata = curr.data.isbn13;
		}
		else if (curr.data.isbn10 != "")
		{
				isbn = count + "isbn10";
				isbndata = curr.data.isbn10;
		}
		var quantity = count + "quantity";
		var title = count + "title";
		var ourprice = count + "ourprice";
		var listprice = count + "listprice";
		if (orderstring == "")
		{
			orderstring = isbn + "=" + isbndata;
			orderstring = orderstring + "&" + quantity + "=" + curr.data.quantity + "&" + title + "=" + curr.data.title + "&" + ourprice + "=" + curr.data.ourprice + "&" + listprice + "=" + curr.data.listprice;
		}
		else
		{
			orderstring = orderstring + "&" + isbn + "=" + isbndata;
			orderstring = orderstring + "&" + quantity + "=" + curr.data.quantity + "&" + title + "=" + curr.data.title + "&" + ourprice + "=" + curr.data.ourprice + "&" + listprice + "=" + curr.data.listprice;

		}
		curr = curr.next;
		count = count + 1;
	}
	return orderstring;
}
function isvalidstring(input,type)
{
	var validchars = "";
	if (type == "text")
		validchars = "abcdefghijkl mnopqrstuvwxyz-_/#.0123456789";
	else if (type == "num")
		validchars = "0123456789";
	else if (type == "email")
	{
		var at = input.indexOf('@');
		var dot = input.lastIndexOf('.');
		if (at < 1 || dot < 1 || (dot - at) < 2)
			return false;
		else
			return true;
	}

	var strlen=input.length; 
	input = input.toLowerCase();
    for(var idx = 0; idx < strlen; idx++)
	{
		if(validchars.indexOf(input.charAt(idx))<0)
		{
    		return false;
     	}
	}
	return true;
}

function validateaddress(form)
{
	var textnode = "";
	if(form.name.value == "" || form.hno.value == "" || form.street.value == "" || form.village.value == "" || form.city.value == "" || form.state.value == "" || form.pincode.value == "" || form.telephone.value == "" || form.email.value == "")
	{
		textnode = "All fields are required to process your order.Please fill all of them. Thank You.";
	}
	else if (isvalidstring(form.name.value,"text") == false || isvalidstring(form.name.value,"text") == false || isvalidstring(form.name.value,"text") == false || isvalidstring(form.name.value,"text") == false || isvalidstring(form.name.value,"text") == false || isvalidstring(form.name.value,"text") == false)
	{
        textnode = "Invalid Characters in Address fields. a-z A-Z 0-9 . / _ #   and - are valid characters";
	
	}
	else if (isvalidstring(form.pincode.value,"num") == false)
	{
        textnode = "InValid Characters in Pincode " +  form.pincode.value + ". Use characters 0-9 for Pincode";
	}
	else if (isvalidstring(form.telephone.value,"num") == false)
	{
		textnode = "InValid Characters in Telephone " + form.telephone.value + ". Use characters 0-9 for Telephone";
	}
	else if (isvalidstring(form.email.value,"email") == false)
	{
		textnode = "Invalid E-Mail " + form.email.value + ". E-Mail should be of the form you@yourdomain.xyz";
	}
    if (textnode != "")
	{
		var adform = document.getElementById("adform");
		var adformerror = document.getElementById("adformerror");
		if (adformerror == null)
		{
			adformerror = document.createElement("p");
			adformerror.setAttribute("id","adformerror");
			adform.appendChild(adformerror);
		}
		adformerror.innerHTML = textnode;
		return false;
	}
	else
		return true;
}
function confirmorder(form)
{
	var textnode = "";
	if (validateaddress(form) == false)
	{
		return;
	}
	else
	{
		name = form.name.value;
		hno = form.hno.value;
	    street = form.street.value;
		village = form.village.value;
	    city = form.city.value;
	    state = form.state.value;
	    pincode = form.pincode.value;
	    telephone = form.telephone.value;
	    email = form.email.value;
	}

	userat = "";
	var paytype = ""; 
	var i = 0;
	for (i = 0; i < form.paytype.length; i++)
	{
		if (form.paytype[i].checked == true)
		{
			paytype = form.paytype[i].value;
			break;
		}
	}
    paytype = "cash"; 
	if (paytype == "cash")
	{
		getaddress(true);
		getData("confirmorder");

	}
	else if (paytype == "online")
	{
			var af = document.getElementById("addressform");
		var param = new Array ("accound_id","reference_no","amount","mode","description",
			"return_url","name","address","city","state","country","postal_code","phone","email", "ship_name","ship_address","ship_city","ship_state", "ship_country", "ship_postal_code", "ship_phone");
		var values = new Array("5880","ABCDEFGH123456","500","test","Two books of Computer Networks by tenunbum","http://174.129.75.89/web/response.php","Praveen");
			values[7] = af.hno.value + af.street.value + af.village.value;
			values[8] = af.city.value;
			values[9] = af.state.value;
			values[10] = "IND";
			values[11] = af.pincode.value;
			values[12] = af.telephone.value;
			values[13] = af.email.value;
			values[14] = af.name.value;
			values[15] = af.hno.value + af.street.value + af.village.value;
			values[16] = af.city.value;
			values[17] = af.state.value;
			values[18] = "IND";
			values[19] = af.pincode.value;
			values[20] = af.telephone.value;
		var form = document.createElement("form");
    	form.setAttribute("method", "POST");
		form.setAttribute("action", "nothing.php");
		/*		form.setAttribute("action", "https://secure.ebs.in/pg/ma/sale/pay/");*/

		var length = param.length;

		for(var i = 0; i <param.length; i++)
	   	{
        	var hiddenField = document.createElement("input");
        	hiddenField.setAttribute("type", "hidden");
        	hiddenField.setAttribute("name", param[i]);
        	hiddenField.setAttribute("value", values[i]);
        	form.appendChild(hiddenField);
    	}
    	document.body.appendChild(form);   
    	form.submit();
	}
}



function getaddress(disableconfirm)
{
	userat = "getaddress";
	var center = document.getElementById("center");
	var cartdiv = document.getElementById("centerdata");
	if (cartdiv != null)
	{
		cartdiv.parentNode.removeChild(cartdiv);
	}
	cartdiv = document.createElement("div");
	cartdiv.setAttribute("id", "centerdata");
	center.appendChild(cartdiv);

	var thehtml = "<div id=\"adform\"> <form id=\"addressform\"> <p class=\"boldatp\"> Shipping Address Please </p>" + "<table><tr><td>Name* </td>"+ "<td><input onKeyPress=\"checkEnter(event,'confirm',this.form)\" type=\"text\" name=\"name\" value=\"" + name + "\">" + "</td></tr>" + "<tr><td>House/Flat No*</td> " + "<td><input  onKeyPress=\"checkEnter(event,'confirm',this.form)\" type=\"text\" name=\"hno\" value=\"" + hno + "\">" + "</td></tr>" + "<tr><td>Street*</td>" + "<td><input  onKeyPress=\"checkEnter(event,'confirm',this.form)\" type=\"text\" name=\"street\" value=\"" + street + "\">" + "</td></tr>" + "<tr><td>Village/Area* </td>" + "<td> <input  onKeyPress=\"checkEnter(event,'confirm',this.form)\" type=\"text\" name=\"village\" value=\"" + village + "\">" + "</td></tr>" + "<tr><td>City/District* </td>" + "<td><input  onKeyPress=\"checkEnter(event,'confirm',this.form)\" type=\"text\" name=\"city\" value=\"" + city + "\">" + "</td></tr>" + "<tr><td>State* </td> " + "<td><input  onKeyPress=\"checkEnter(event,'confirm',this.form)\" type=\"text\" name=\"state\" value=\"" + state + "\">" + "</td></tr>" + "<tr><td>PinCode*</td> " + "<td><input  onKeyPress=\"checkEnter(event,'confirm',this.form)\" type=\"text\" name=\"pincode\" value=\"" + pincode + "\">" + "</td></tr>" + "<tr><td>Telephone*</td> "+ "<td><input  onKeyPress=\"checkEnter(event,'confirm',this.form)\" type=\"text\" name=\"telephone\" value=\"" + telephone + "\">" + "</td></tr>" + "<tr><td>E-Mail* </td>";
	if (email != "")
		thehtml = thehtml + "<td><input  onKeyPress=\"checkEnter(event,'confirm',this.form)\" type=\"text\" name=\"email\" value=\"" + email + "\" readonly>";
	else 
		thehtml = thehtml + "<td><input  onKeyPress=\"checkEnter(event,'confirm',this.form)\" type=\"text\" name=\"email\" value=\"" + email + "\">";
      thehtml = thehtml + "</td></tr></table><input type=\"radio\" name=\"paytype\" value=\"cash\" checked>Pay Cash On Delivery<br><br>";
	if (disableconfirm == true)
	{
		thehtml = thehtml + "<input type=\"button\" value=\"Please Wait\">" + "</form> </div>";
	}	
	else
	{
		thehtml = thehtml + "<input type=\"button\" value=\"Confirm Order\" onclick=\"confirmorder(this.form)\">" + "</form> </div>";
	}
	cartdiv.innerHTML = thehtml;

}

function process_xml_response(xmldocumenttext, xmldocument)
{
    var xmldoc = xmldocument.documentElement;
    var center = document.getElementById("center");
	var cartdiv = document.getElementById("centerdata");

	var thehtml = "";
	var booklist = xmldoc.getElementsByTagName("sbook");
	if(booklist.length == 0)
	{
		cartdiv.innerHTML = "<p class=\"atp\"> We could not find any matches for the search string <span class=\"blueatp\">" +  document.searchform.search_string.value + ".</span><br>Please try using different search string. <br>We are constantly trying to add to our inventory, incase you could not find a match now please try later. <br>Thank You for your support.</p>";

		return;
	}
		thehtml = thehtml + "<div style=\"width:90%\" class=\"atp\">";
		var totalpages = parseInt(xmldoc.getElementsByTagName("totalpages")[0].childNodes[0].nodeValue);

		var pagelist = xmldoc.getElementsByTagName("spage");
		for (var index = 0; index < pagelist.length; index = index + 1)
		{
            var pagestatus = pagelist[index].getAttribute("status");
			if (pagestatus == "cur")
			{
				currentpage = parseInt(pagelist[index].childNodes[0].nodeValue);
			}
		}
		thehtml = thehtml + "showing Page " + currentpage + " of " + totalpages + " Pages for " + "<span class=\"blueatp\">" + document.searchform.search_string.value + "</span></div>"; 
    if (booklist.length > 0)
    {
		/*thehtml = thehtml + "<hr width=\"90%\" align=\"left\">";*/

        for (index = 0; index < booklist.length; index = index + 1)
		{
			var tisbn13 = booklist[index].getElementsByTagName("isbn13")[0].childNodes[0].nodeValue;
			var imagepath =  booklist[index].getElementsByTagName("image")[0].childNodes[0].nodeValue;
			var title = booklist[index].getElementsByTagName("title")[0].childNodes[0].nodeValue;
			var author = booklist[index].getElementsByTagName("author")[0].childNodes[0].nodeValue;
			var listprice = booklist[index].getElementsByTagName("listprice")[0].childNodes[0].nodeValue;
			var ourprice = booklist[index].getElementsByTagName("ourprice")[0].childNodes[0].nodeValue;
			var save = parseInt(listprice) - parseInt(ourprice);
			var author2 = booklist[index].getElementsByTagName("author2");
			var author3 = booklist[index].getElementsByTagName("author3");
			var author4 = booklist[index].getElementsByTagName("author4");
			var edition = booklist[index].getElementsByTagName("edition");
			var publisher = booklist[index].getElementsByTagName("publisher");
			var boolblurb = parseInt(booklist[index].getElementsByTagName("boolblurb")[0].childNodes[0].nodeValue);
				
			thehtml = thehtml + "<table width=\"90%\">" + 
				"<tr>" +
				"<td align=\"left\">" + 
				"<img src=\"" + 
				imagepath + 
				"\"" + 
				" width=\"70px\" height=\"100px\"" +
				"/>" +
				"</td>" + 
				"<td>" + 
				"<p class=\"title\">" +
				title +
				" <span class=\"atp\">"; 
			if (edition.length > 0)
				thehtml = thehtml + edition[0].childNodes[0].nodeValue;
		        thehtml = thehtml + " by <span class=\"iatp\">" + 
				author ;
			if (author2.length > 0)
				thehtml = thehtml + " and " + author2[0].childNodes[0].nodeValue;
			if (author3.length > 0)
				thehtml = thehtml + " and " + author3[0].childNodes[0].nodeValue;
			if (author4.length > 0)
				thehtml = thehtml + " and " + author4[0].childNodes[0].nodeValue;
			if (publisher.length > 0)
				thehtml = thehtml +	"</span><br>" + "Publisher : <span class=\"blueatp\">" + publisher[0].childNodes[0].nodeValue + "</span><br>";
			else
				thehtml = thehtml + "</span><br>";	
		    thehtml = thehtml +	" List Price INR <span class=\"boldatp\">" + 
				listprice + 
				" </span> Our Price INR <span class=\"boldatp\">" + 
				ourprice + "</span> ";
			if (save != 0)
				thehtml = thehtml + "You Save INR <span class=\"boldatp\">" + 
				save + "</span>";
			thehtml = thehtml + "<br>" + 
				"ISBN 13 : " + 
				tisbn13 + 
				"</span><br>" + 
				"<span class=\"iatp\">" + 
				"In Stock. Order Now and receive in <span class=\"boldatp\">3-5 </span> business days</span><br><span class=\"blueatp\">Our Offer:Return the book with in one year and receive 45% money back.</span>" +
				"</p>" + 
				"</td>" + 
				"<td align=\"right\">" + 
				"<form>" + 
				"<input type=\"button\" value=\"Add To Cart\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"addtocart('" + imagepath + "'" + ",'"
                + listprice
                                                  + "','"
                                                  + ourprice
												  +"'"
												  +",'"
												  + tisbn13
												  +"','"
												  + title
												  + "'"
												  +")" + "\"" + ">" + "</form>" + "</td>" + "</tr>" + "</table>";
			if (boolblurb != 0)
			{
				thehtml = thehtml + "<div style=\"width:90%\" id=\"" + tisbn13 + "blurb" + "\"" + ">" + "<span id=\""  + tisbn13 + "blurbview" + "\"><span onmouseover=\"" + "this.style.cursor='pointer';\"" + " onclick=\"" + "getblurb('" + tisbn13 + "')\"" +  " class=\"blueatp\"" + ">" + "Click Here To Read Overview ..." + "</span></span><br><span class=\"atp\" id=\"" + tisbn13 + "blurbdata\"></span>" + "</div>";
			}
			thehtml = thehtml + "<hr width=\"90%\" align=\"left\">";
		}
		thehtml = thehtml + "<div style=\"width:90%\" class=\"atp\">";

		thehtml = thehtml + "<div style=\"float:left\";> showing Page " + currentpage + " of " + totalpages + " Pages for " + "<span class=\"blueatp\">" + document.searchform.search_string.value + "</span></div>"; 
        for (index = 0; index < pagelist.length; index = index + 1)
		{
			var pagevalue = pagelist[index].childNodes[0].nodeValue;
            var pagestatus = pagelist[index].getAttribute("status");
			if (pagestatus == "cur")
			{

				thehtml = thehtml + "<div class=\"pgpreviousnextactive\"  onmouseover=\"\">" + pagevalue + "</div>";
			}
			else if (pagestatus == "dis")
			{
				thehtml = thehtml + "<div class=\"pgpreviousnextshow\">" + pagevalue + "</div>";
			}
			else
			{
				var page = 0;
				if (pagevalue == "NEXT")
				{
					var npage = currentpage + 1;
					thehtml = thehtml + "<div class=\"pgpreviousnextshow\" onmouseover=\"this.style.border='1px solid #666666';this.style.cursor='pointer'\" onmouseout=\"this.style.border='1px solid #dddddd'\" onclick=\"getData('search','search_string=" + document.searchform.search_string.value + "&reqpage=" + npage + "')\">" + pagevalue + "</div>";
				}
				else if (pagevalue == "LAST")
				{
					thehtml = thehtml + "<div class=\"pgpreviousnextshow\" onmouseover=\"this.style.border='1px solid #666666';this.style.cursor='pointer'\" onmouseout=\"this.style.border='1px solid #dddddd'\" onclick=\"getData('search','search_string=" + document.searchform.search_string.value + "&reqpage=" + totalpages + "')\">" + pagevalue + "</div>";
				}
				else if (pagevalue == "FIRST")
				{
					var npage = 1;
					thehtml = thehtml + "<div class=\"pgpreviousnextshow\" onmouseover=\"this.style.border='1px solid #666666';this.style.cursor='pointer'\" onmouseout=\"this.style.border='1px solid #dddddd'\" onclick=\"getData('search','search_string=" + document.searchform.search_string.value + "&reqpage=" + npage + "')\">" + pagevalue + "</div>";
				}
				else if (pagevalue == "PREVIOUS")
				{
					var ppage = currentpage - 1;
					thehtml = thehtml + "<div class=\"pgpreviousnextshow\" onmouseover=\"this.style.border='1px solid #666666';this.style.cursor='pointer'\" onmouseout=\"this.style.border='1px solid #dddddd'\" onclick=\"getData('search','search_string=" + document.searchform.search_string.value + "&reqpage=" + ppage + "')\">" + pagevalue + "</div>";
				}
			}
		}
		thehtml =thehtml + "</div>";
	}
	cartdiv.innerHTML = thehtml;
}
function getblurb(isbn13)
{
	getData("getblurb", "isbn13=" + isbn13);
}
function process_helpdata_xml_response(xmldoc)
{
	var centerdata = document.getElementById("centerdata");
	centerdata.innerHTML = xmldoc;

}
function process_getblurbdata_xml_response(reqdata, xmldocument)
{
	/*var xmlresp = xmldocument.documentElement;
    var response = xmlresp.getElementsByTagName("response");
	var responsevalue = response[0].getAttribute("its");
	if (responsevalue == "success")
	{
		var isbn13 = xmlresp.getElementsByTagName("isbn13")[0].childNodes[0].nodeValue;
		var blurb = document.getElementById(isbn13 + "blurb");
		blurb.innerHTML = "<p class=\"atp\">" + xmlresp.getElementsByTagName("blurb")[0].childNodes[0].nodeValue + "</p>";
	
		var blurbdata = document.getElementById(isbn13 + "blurbdata");
		blurbdata.parentNode.removeChild(blurbdata);
		blurbdata = document.createElement("p");
		blurbdata.setAttribute("class", "bluebackatp");
		blurbdata.setAttribute("id", isbn13 + "blurbdata");
		var blurbtext = document.createTextNode(xmlresp.getElementsByTagName("blurb")[0].childNodes[0].nodeValue);
		blurbdata.appendChild(blurbtext);
		blurb.appendChild(blurbdata);
}*/
           var temp = new Array();
           temp = reqdata.split('=');
	
	var blurbdata = document.getElementById(temp[1] + "blurbdata");
	   blurbdata.innerHTML = xmldocument;
	   var blurbview = document.getElementById(temp[1] + "blurbview");
	   blurbview.innerHTML = "<span id=\""  + temp[1] + "blurbview" + "\" onmouseover=\"" + "this.style.cursor='pointer';\"" + " onclick=\"" + "closeblurb('" + temp[1] + "')\"" +  " class=\"title\"" + ">" + "Click Here To Close Overview ..." + "</span>";

}
function closeblurb(isbn13)
{
	var blurbdata = document.getElementById(isbn13 + "blurbdata");
	
	   blurbdata.innerHTML = "";
	   var blurbview = document.getElementById(isbn13 + "blurbview");
	   blurbview.innerHTML = "<span id=\""  + isbn13 + "blurbview" + "\" onmouseover=\"" + "this.style.cursor='pointer';\"" + " onclick=\"" + "getblurb('" + isbn13 + "')\"" +  " class=\"blueatp\"" + ">" + "Click Here To Read Overview ..." + "</span>";


}

function process_signindata_xml_response(xmldocument)
{
	var xmlresp = xmldocument.documentElement;
	var response = xmlresp.getElementsByTagName("response");
	email =  xmlresp.getElementsByTagName("email")[0].childNodes[0].nodeValue;
	var responsevalue = response[0].getAttribute("its");
	if (responsevalue == "success")
	{
		var lform = document.getElementById("loginform");
        if (lform != null)
        lform.parentNode.removeChild(lform);

        var hellouser = document.getElementById("logindata");

		var thehtml = "Hello " + email;

	hellouser.innerHTML = thehtml;

	var linlout = document.getElementById("loginlogout");
	linlout.innerHTML = "<span class=\"ublueatp\" onmouseover=\"this.style.cursor='pointer';this.style.textDecoration='none';\" onclick=\"allowlogout()\" onmouseout=\"this.style.textDecoration='underline';\">Logout</span>";
        
		if (userat == "getaddress")
		{
			getaddress(false);
		}
		else
		{
			getData("search");
		}
	}
	else
	{
		var textnode = xmlresp.getElementsByTagName("message")[0].childNodes[0].nodeValue;
		var loginform = document.getElementById("loginform");
			 var lferror = document.getElementById("loginformerror");
			 if (lferror != null)
				 lferror.parentNode.removeChild(lferror);
			lferror = document.createElement("p");
			lferror.setAttribute("id","loginformerror");
			loginform.appendChild(lferror);
			lferror.innerHTML = textnode;
	}
	
}
function getData(req,reqdata)
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
            if (req == "search")
			{

			  if (reqdata == null)
			  {
				  if (document.searchform.search_string.value == "")
					  return;
			  }
			  else
			  {
              	   if(reqdata == "search_string=")
					   return;

			  }
			  userat = "";
              XMLHttpRequestObject.open("POST","searchdata.php");
              XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
              XMLHttpRequestObject.onreadystatechange = function()
              {
                  if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                  {
                     process_xml_response(XMLHttpRequestObject.responseText,XMLHttpRequestObject.responseXML);
                      <!-- process_xml_response();   -->
                      delete XMLHttpRequestObject;
                      XMLHttpRequestObject = null;
                  }
			  }
			  if (reqdata == null)
			  {
              	XMLHttpRequestObject.send("search_string=" + document.searchform.search_string.value);
			  }
			  else
			  {
              	XMLHttpRequestObject.send(reqdata);

			  }
			}
			else if (req == "querysubproducts")
			{
				XMLHttpRequestObject.open("POST","querysubproducts.php");
                   XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                   XMLHttpRequestObject.onreadystatechange = function()
                   {
                       if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                       {
                            productchangeresponse(XMLHttpRequestObject.responseXML);
                            delete XMLHttpRequestObject;
                            XMLHttpRequestObject = null;
                       }
                   }
					   XMLHttpRequestObject.send("reqtype=" + req + "&" + reqdata);

			}
			else if (req == "querysubsubproducts")
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
            else if (req == "verifypasswordgetdata")
            {
                   XMLHttpRequestObject.open("POST","verifypassword.php");
                   XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                   XMLHttpRequestObject.onreadystatechange = function()
                   {
                       if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                       {
                            process_verifypasswordgetdata_xml_response(XMLHttpRequestObject.responseXML);
                            delete XMLHttpRequestObject;
                            XMLHttpRequestObject = null;
                       }
                   }
					   XMLHttpRequestObject.send("reqtype=" + req + "&" + reqdata);
			}
			else if (req == "help" || req == "privacypolicy" || req == "copyright" || req == "termsconditions")
			{
				
                   XMLHttpRequestObject.open("POST",req + ".php");
                   XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                   XMLHttpRequestObject.onreadystatechange = function()
                   {
                       if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                       {
                            process_helpdata_xml_response(XMLHttpRequestObject.responseText);
                            delete XMLHttpRequestObject;
                            XMLHttpRequestObject = null;
                       }
                   }
					   XMLHttpRequestObject.send();
			}
			else if (req == "signin")
			{
				XMLHttpRequestObject.open("POST","signin.php");
                XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                XMLHttpRequestObject.onreadystatechange = function()
                {
                	if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                    {
                    	process_signindata_xml_response(XMLHttpRequestObject.responseXML);
                        delete XMLHttpRequestObject;
                        XMLHttpRequestObject = null;
                     }
                }
				XMLHttpRequestObject.send("reqtype=" + req + "&" + reqdata);
			}
			else if (req == "forgotpassword")
		    {

                 XMLHttpRequestObject.open("POST","forgotpassword.php");
                 XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                 XMLHttpRequestObject.onreadystatechange = function()
                 {
                     if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                     {
                         process_forgotpassword_xml_response(XMLHttpRequestObject.responseText,reqdata);
                         delete XMLHttpRequestObject;
                         XMLHttpRequestObject = null;
                     }
                }
                XMLHttpRequestObject.send("reqtype=" + req + "&" + reqdata);
		    }
		    else if (req == "logout")
		    {

                 XMLHttpRequestObject.open("POST","logout.php");
                 XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                 XMLHttpRequestObject.onreadystatechange = function()
                 {
                     if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                     {
                         process_logoutdata_xml_response(XMLHttpRequestObject.responseXML);
                         delete XMLHttpRequestObject;
                         XMLHttpRequestObject = null;
                     }
                }
                XMLHttpRequestObject.send("reqtype=" + req + "&email=" + email);
		     }
             else if (req == "confirmorder")
			 {
				 XMLHttpRequestObject.open("POST","confirmorder.php");
                 XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                 XMLHttpRequestObject.onreadystatechange = function()
                 {
                     if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                     {
                         process_confirmorderdata_xml_response(XMLHttpRequestObject.responseXML);
                         delete XMLHttpRequestObject;
                         XMLHttpRequestObject = null;
                     }
                }
                XMLHttpRequestObject.send("reqtype=" + req + "&" + getorderstring() + "&" +getaddressstring() + "&shipto=" + shipto);

			 }
			 else if (req == "getblurb")
			 {
				 XMLHttpRequestObject.open("POST","getblurb.php");
                 XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                 XMLHttpRequestObject.onreadystatechange = function()
                 {
                     if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                     {
                         process_getblurbdata_xml_response(reqdata,XMLHttpRequestObject.responseText);
                         delete XMLHttpRequestObject;
                         XMLHttpRequestObject = null;
                     }
                }
                XMLHttpRequestObject.send("reqtype=" + req + "&" + reqdata);
			 }
			 else if (req == "myaccount")
			 {
				 XMLHttpRequestObject.open("POST","myaccount.php");
                 XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                 XMLHttpRequestObject.onreadystatechange = function()
                 {
                     if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                     {
                         process_myaccountdata_xml_response(XMLHttpRequestObject.responseXML);
                         delete XMLHttpRequestObject;
                         XMLHttpRequestObject = null;
                     }
                }
                XMLHttpRequestObject.send("reqtype=" + req + "&" + reqdata);
			 }
		 }
}
function getaddressstring()
{
	var addressstring = "";
	var af = document.getElementById("addressform");
	addressstring = "hno=" + af.hno.value + "&street=" + af.street.value + "&village=" + af.village.value + "&city=" + af.city.value + "&state=" + af.state.value + "&pincode=" + af.pincode.value + "&name=" + af.name.value +"&email=" + af.email.value + "&telephone=" +af.telephone.value;
	return addressstring;
}

function addtocartonload(image,listprice,ourprice,isbn13,title,quantity)
{
var item = new cartitem(image,listprice,ourprice,quantity,isbn13,title);
cart.add(item); 

}


function addtocart(newbook,listprice,ourprice,isbn13,title)
{
	var curr = cart.head;
	var found = 0;
	var item = new cartitem(newbook,listprice,ourprice,1,isbn13,title);
	while (curr != null)
	{
		if (isbn13 == curr.data.isbn13)
		{
				found = 1;
				item.quantity = parseInt(curr.data.quantity) + 1;
				if (item.quantity > 10 )
				{
                    item.quantity = 10;
				}
				break;
		}
		curr = curr.next;
	}
    if (found == 0)
	{	
        cart.add(item); 
	}
	else
	{
		justremovefromcart(isbn13);
        cart.add(item); 
	}
	showcart();
	if (found == 0)
		updatecartonserver("addtocart", "isbn13=" + item.isbn13 + "&quantity=" + item.quantity);
	else
		updatecartonserver("updatecart", "isbn13=" + item.isbn13 + "&quantity=" + item.quantity);

}

function updatecartonserver(req,reqdata)
{
				
         var XMLHttpRequestObject1 = false;
         if (window.XMLHttpRequest)
         {
            XMLHttpRequestObject1 = new XMLHttpRequest();
         }
         else if (window.ActiveXObject)
         {
           XMLHttpRequestObject1 = new ActiveXObject("Microsoft.XMLHTTP");
         }
         if (XMLHttpRequestObject1)
         {
             XMLHttpRequestObject1.open("POST","updatecartonserver.php");
             XMLHttpRequestObject1.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
             XMLHttpRequestObject1.onreadystatechange = function()
             {
                 if (XMLHttpRequestObject1.readyState == 4 &&
                           XMLHttpRequestObject1.status == 200)
                 {
                      delete XMLHttpRequestObject1;
                      XMLHttpRequestObject1 = null;
                 }
	     	}
	        if (req == "addtocart" || req == "updatecart")
	        {
             	  XMLHttpRequestObject1.send("reqtype=" + req + "&" + reqdata);
	     	}
	     	else if (req == "deletefromcart")
	     	{
				XMLHttpRequestObject1.send("reqtype=deletefromcart&" + "isbn13=" + reqdata);
	     	}
	     
	 	}
}
function movecartdown()
{
	var curr = cart.head;
	while (curr != null)
	{
		if (curr.data.image == vcart.image)
		{
			if (curr.next != null)
			{
				vcart = curr.next.data;
			}
	                break;		
		}
		curr = curr.next;
	}
	showcart();
}

function movecartup()
{
	var curr = cart.head;
	var prev = null;
	while (curr != null)
	{
		if (curr.data.image == vcart.image)
		{
			if (prev != null)
			{
				vcart = prev.data;
			}
	                break;		
		}
		prev = curr;
		curr = curr.next;
	}
	showcart();
}
function clearcenter()
{
		var centerdata = document.getElementById("centerdata");
		centerdata.innerHTML = "";
}

function allowlogout()
{
		var login = document.getElementById("login");
	   var logindata = document.getElementById("logindata");
		logindata.innerHTML = "Hello";
           	var linlout = document.getElementById("loginlogout");
		linlout.innerHTML = "<span class=\"ublueatp\" onmouseover=\"this.style.cursor='pointer';this.style.textDecoration='none';\" onclick=\"allowlogin(0,'','')\" onmouseout=\"this.style.textDecoration='underline';\">Login</span>";
		clearcenter();
        clearleftside();	
		clearrightside();
		loadhomedata();
		cleardata();
	getData("logout");
}
function forgotpassword()
{
	var logindiv = document.getElementById("login");
	var center = document.getElementById("center");
	var cartdiv = document.getElementById("centerdata");
	cartdiv.innerHTML = "<p class=\"atp\"> Your E-Mail Address. Your Password Will Be Sent To Your E-Mail Address.</p> <br><form> <input type=\"text\" name=\"email\"> <br><br> <input type=\"button\" value=\"Submit\" onclick=\"sendpassword(this.form)\"> </form>";
}
function sendpassword(forgotp)
{
	if (this.email.value != "")
	{
		getData('forgotpassword', "email=" + forgotp.email.value);
	}
}
function process_forgotpassword_xml_response(xml, reqdata)
{
	var centerdata = document.getElementById("centerdata");
	centerdata.innerHTML=xml;
}
function allowlogin(signin,useremail,userpassword)
{
	if(loginattempts > 2)
	{
			return;
	}
	
	var logindiv = document.getElementById("login");
	var center = document.getElementById("center");
    var cartdiv = document.getElementById("centerdata");
    if (cartdiv != null)
		cartdiv.parentNode.removeChild(cartdiv);
	var thehtml = "";
	
	cartdiv = document.createElement("div");
	cartdiv.setAttribute("id", "centerdata");
	center.appendChild(cartdiv);

	thehtml = thehtml + "<form id=\"loginform\"> <p> Enter Your E-Mail Address </p> <input type=\"text\" name=\"email\" value=\"" + useremail + "\">";

      
	if (signin == 0)
	{
			thehtml = thehtml + " <p> Enter Your Password </p> <input onKeyPress=\"checkEnter(event,'login',this.form)\" type=\"password\" name=\"password\" value=\"" + userpassword +"\">";
		thehtml = thehtml + "<span class=\"blueatp\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"forgotpassword()\">" + " " + "Forgot Password ? Click Here" + "</span>";
	}
 
	if (signin == 1)
	{
			thehtml = thehtml + " <p> Enter Your Password </p> <input type=\"password\" name=\"password\" value=\"" + userpassword +"\">";
		thehtml = thehtml + "<p> Re-Enter Your Password </p> <input onKeyPress=\"checkEnter(event,'login',this.form)\" type=\"password\" name=\"repassword\">";
		thehtml = thehtml + "<p> <input type=\"checkbox\" name=\"newuser\" checked onclick=\"areunewuser(this.form)\"> Are You A New User ? <span class=\"blueatp\"> UnCheck To Log In </span> </p>";
    thehtml = thehtml + "<input type=\"button\" value=\"Sign In\" onclick=\"processloginform(this.form)\"></form>";
	}
	else
	{
		thehtml = thehtml + "<p> <input type=\"checkbox\" name=\"newuser\" onclick=\"areunewuser(this.form)\"> Are You A New User ? <span class=\"blueatp\">Check To Sign In</span></p>";
    thehtml = thehtml + "<input type=\"button\" value=\"Log In\" onclick=\"processloginform(this.form)\"></form>";
	}
	cartdiv.innerHTML = thehtml;
}
function processloginform(form)
{
	if (loginattempts > 2)
	{var textnode = document.createTextNode("Only A Maximum Of Three Failed Attemps Are Allowed. Try After SomeTime");
			 var loginform = document.getElementById("loginform");
			 var lferror = document.getElementById("loginformerror");
			 if (lferror != null)
				 lferror.parentNode.removeChild(lferror);
			lferror = document.createElement("p");
			lferror.setAttribute("id","loginformerror");

			loginform.appendChild(lferror);
			lferror.appendChild(textnode);
			return;
	}
	if (form.newuser.checked == false)
	{
	   if (form.email.value != "" && form.password.value != "")
	   {	   
	   		var reqdata = "email=" + form.email.value + "&" + "password=" + form.password.value;
			getData("verifypasswordgetdata",reqdata);
	   }
	   else
	   {
		   if (form.email.value == "")
			   var textnode = "Please Enter Your E-Mail Id";
		   else
			   var textnode = "Please Enter Your Password";
			var loginform = document.getElementById("loginform");
			var lferror = document.getElementById("loginformerror");
			if (lferror != null)
				 lferror.parentNode.removeChild(lferror);
			lferror = document.createElement("p");
			lferror.setAttribute("id","loginformerror");

			loginform.appendChild(lferror);
			lferror.innerHTML = textnode;
	   }
	}
	else
	{
		if (form.password.value != "" && form.password.value == form.repassword.value && form.email.value != "")
		{

	   		var reqdata = "email=" + form.email.value + "&" + "password=" + form.password.value;
			getData("signin",reqdata);
		}
		else
		{
			if (form.email.value == "")
			   var textnode = "Please Enter Your E-Mail Id";
		    else if (form.password.value == "")
				var textnode = "Please Enter Your Password";
			else if (form.repassword.value == "")
				var textnode = "Please Re-Enter Your Password";
			else
				var textnode = "Passwords Are Not Same. Please Enter Again";
			
			var loginform = document.getElementById("loginform");
			var lferror = document.getElementById("loginformerror");
			if (lferror != null)
				 lferror.parentNode.removeChild(lferror);
			lferror = document.createElement("p");
			lferror.setAttribute("id","loginformerror");

			loginform.appendChild(lferror);
			lferror.innerHTML = textnode;
			
		}
	}
}
function areunewuser(form)
{
	if (form.newuser.checked == true)
	{
		allowlogin(1,form.email.value,form.password.value);
	}
	else
	{
		allowlogin(0,form.email.value,form.password.value);
	}
}
function process_myaccountdata_xml_response(xmldocument)
{
	var xmlresp = xmldocument.documentElement;
    var response = xmlresp.getElementsByTagName("response");
	var responsevalue = response[0].getAttribute("its");
	if (responsevalue == "success")
	{
		var centerdata = document.getElementById("centerdata");
		if (centerdata == null)
		{
			centerdata = document.createElement("div");
			centerdata.setAttribute("id","centerdata");
			var center = document.getElementById("center");
			center.appendChild(centerdata);
		}
		var thehtml = "";
		var orders = xmlresp.getElementsByTagName("order");
		var ind = 0;
		var thehtml = "<p class=\"uatp\" style=\"width:80%;\">";
		if (name != "")
		{
			thehtml = thehtml + "<span class=\"boldatp\">Personal Information </span> <br> <br>" + "Name: " + name + "<br>House/Flat Number: " + hno + "<br>Street: " + street + "<br>Area/Village: " + village + "<br>City/District: " + city + "<br>State: " + state + "<br>Pincode: " + pincode + "<br>Telephone: " + telephone + "<br>E-Mail: " + email + "<br></p><br>";
		}
		thehtml = thehtml + "<p class=\"uatp\" style=\"width:auto;\"> <span class=\"boldatp\">My Orders</span><hr>";
		for (ind = 0; ind < orders.length ;ind++)
		{
			var invoicenumber = orders[ind].getElementsByTagName("invoicenumber")[0].childNodes[0].nodeValue;
			thehtml = thehtml + "<span class=\"boldatp\">Invoice Number : " + invoicenumber + "</span><br>";
			var date1 = orders[ind].getElementsByTagName("date")[0].childNodes[0].nodeValue;
			thehtml = thehtml + "Order Date:" + date1 + "<br>";
			var orderstatus = orders[ind].getElementsByTagName("orderstatus")[0].childNodes[0].nodeValue;
			thehtml = thehtml + "Order Status : " + orderstatus + "<br>";
			var payment = orders[ind].getElementsByTagName("payment")[0].childNodes[0].nodeValue;
			thehtml = thehtml + "Payment : " + payment + "<br><span class=\"boldatp\">Items</span><br>";
			items = orders[ind].getElementsByTagName("item");
			var indj = 0;
			for (indj =0; indj < items.length ; indj++)
			{
				var title = items[indj].getElementsByTagName("title")[0].childNodes[0].nodeValue;
				var quantity = items[indj].getElementsByTagName("quantity")[0].childNodes[0].nodeValue;
				var price = items[indj].getElementsByTagName("price")[0].childNodes[0].nodeValue;
				thehtml = thehtml + quantity + " of " + title + " at Rs" + price + "<br>"; 
			}
			var total = orders[ind].getElementsByTagName("total")[0].childNodes[0].nodeValue;
			thehtml = thehtml + "<br><span class=\"boldatp\">Total Order INR " + total + "</span><br><hr>";
		}
		thehtml = thehtml + "</p>";
		centerdata.innerHTML = thehtml;
	}
}
function process_logoutdata_xml_response(xmldocument)
{
	var xmlresp = xmldocument.documentElement;

    var response = xmlresp.getElementsByTagName("response");
	var responsevalue = response[0].getAttribute("its");
	if (responsevalue == "success")
	{
       ;	
	}
}
function clearrightside()
{
	var realcartdata = document.getElementById("realcartdata");
	if (realcartdata != null)
		realcartdata.parentNode.removeChild(realcartdata);
		var confirmorderdata = document.getElementById("confirmorderdata");
		if (confirmorderdata != null)
		    confirmorderdata.parentNode.removeChild(confirmorderdata);
	    var carttotal = document.getElementById("carttotal");
		carttotal.innerHTML="";
		var cartitems = document.getElementById("cartitems");
		var cartitems = document.getElementById("cartitems");
		cartitems.innerHTML = "";
		cart.head = null;
}
function process_confirmorderdata_xml_response(xmldocument)
{

    var xmlresp = xmldocument.documentElement;
    var response = xmlresp.getElementsByTagName("response");
    var username = xmlresp.getElementsByTagName("email")[0].childNodes[0].nodeValue;
    userat="";
    email = username;
    var responsevalue = response[0].getAttribute("its");

    	
	var center = document.getElementById("center");
	var centerdata = document.getElementById("centerdata");
	if (centerdata != null)
	{
		centerdata.parentNode.removeChild(centerdata);
		centerdata = null;
	}
	centerdata = document.createElement("div");
	centerdata.setAttribute("id", "centerdata");
	center.appendChild(centerdata);
	if (responsevalue == "success")
	{
		var orderid = xmldocument.getElementsByTagName("orderid")[0].childNodes[0].nodeValue;
		var thehtml = "<p class=\"boldatp\">Thank You For Using Our Service <br> <br>Invoice Number : " + orderid + "</p>" + "<table class=\"atp\" width=\"80%\">";
		var curr = cart.head;
		var grandtotal = 0;
		var first = 0;
		thehtml = thehtml + "<tr> <td class=\"boldatp\">Title</td> <td class=\"boldatp\">Our Price</td> <td class=\"boldatp\">Quantity</td> <td class=\"boldatp\">Total</td> </tr>";
		while (curr != null)
		{
			var inttotal = curr.data.quantity * curr.data.ourprice;
			thehtml = thehtml + "<tr class=\"atp\"> <td>" + curr.data.title + "<br>ISBN13:" + curr.data.isbn13 + "<br><br></td> <td>" + curr.data.ourprice + "</td> <td>" + curr.data.quantity + "</td> <td>" + inttotal + "</td> </tr>" ;
			 	curr = curr.next;
			 	grandtotal = grandtotal + inttotal;
		}
		thehtml = thehtml + "</table>";
		var lowercity = city.toLowerCase();
		if (lowercity == "bangalore" || lowercity == "bengaluru" || lowercity == "banglore")
		{
			thehtml = thehtml + "<p class=\"atp\"> Shipping Cost INR 25 </p>";
			grandtotal = grandtotal + 25;
		}
		else
		{
			thehtml = thehtml + "<p class=\"atp\"> Shipping Cost INR 50 </p>";
			grandtotal = grandtotal + 50;
		}
		thehtml = thehtml + "<p class=\"boldatp\"> Grand Total INR " + grandtotal + "</p>";
		thehtml = thehtml + "<p class=\"atp\">Your Order Will be delivered in 3-5 Days</p>";
		thehtml = thehtml + "<p class=\"iatp\">A copy of Invoice has been sent to your e-mail address</p>";
		thehtml = thehtml + "<p> <span class=\"boldatp\">Shipping Address</span> <br><br><span class=\"atp\">";
		thehtml = thehtml + "Name:" + name + "<br>House/Flat:" + hno + "<br>Street:" + street + "<br>Area/Village:" + village + "<br>City:" + city + "<br>State:" + state + "<br>Pincode:" + pincode + "<br>India" + "</span></p> <p class=\"atp\"> For shipping/order related issues please email @ <a href=\"mailto:support@popabook.com\">support@popabook.com</a></p>";
		centerdata.innerHTML = thehtml;

		clearrightside();
	}
	else
	{
       var er = document.createElement("We are Sorry. We could not process your order. Please try again");
	   centerdata.appendChild(er);
	}
}
function process_verifypasswordgetdata_xml_response(xmldocument)
{
    var xmlresp = xmldocument.documentElement;
    var response = xmlresp.getElementsByTagName("response");
    var username = xmlresp.getElementsByTagName("email")[0].childNodes[0].nodeValue;
    email = username;
    var responsevalue = response[0].getAttribute("its");
    
    if (responsevalue == "success")
	{
		loginattempts = 0;
        var lform = document.getElementById("loginform");
        if (lform != null)
        lform.parentNode.removeChild(lform);

        var hellouser = document.getElementById("logindata");
	var thehtml = "Hello " + email ;

		var linlout = document.getElementById("loginlogout");
	linlout.innerHTML = "<span class=\"ublueatp\" onmouseover=\"this.style.cursor='pointer';this.style.textDecoration='none';\" onclick=\"allowlogout()\" onmouseout=\"this.style.textDecoration='underline';\">Logout</span>";

    hellouser.innerHTML = thehtml;
	var phno = xmlresp.getElementsByTagName("hno");
	if (phno[0].childNodes.length != 0)
		hno = phno[0].childNodes[0].nodeValue;
	var pstreet = xmlresp.getElementsByTagName("street");
	if (pstreet[0].childNodes.length != 0)
		street = pstreet[0].childNodes[0].nodeValue;
	pvillage = xmlresp.getElementsByTagName("village");
	if (pvillage[0].childNodes.length != 0)
		village = pvillage[0].childNodes[0].nodeValue;
	var pcity = xmlresp.getElementsByTagName("city");
	if (pcity[0].childNodes.length != 0)
		city = pcity[0].childNodes[0].nodeValue;

	var pstate = xmlresp.getElementsByTagName("state");
	if (pstate[0].childNodes.length != 0)
		state = pstate[0].childNodes[0].nodeValue;
	
	var ptelephone = xmlresp.getElementsByTagName("telephone");
	if (ptelephone[0].childNodes.length != 0)
		telephone = ptelephone[0].childNodes[0].nodeValue;

	var ppincode = xmlresp.getElementsByTagName("pincode");
	if (ppincode[0].childNodes.length != 0)
		pincode = ppincode[0].childNodes[0].nodeValue;
	var pname = xmlresp.getElementsByTagName("name");
	if (pname[0].childNodes.length != 0)
		name = pname[0].childNodes[0].nodeValue;

	if (userat == "getaddress")
	{
		getaddress();
	}
	else if (userat == "myaccount")
	{
		showuseraccount();
	}
	else
	{
		getData("search");
	}

    }
    else
	{
		loginattempts++;

		var textnode = "Attempt:" + loginattempts + " " +"Email and Password could not be matched. Try Again";
		var loginform = document.getElementById("loginform");
		 var lferror = document.getElementById("loginformerror");
			 if (lferror != null)
				 lferror.parentNode.removeChild(lferror);
			lferror = document.createElement("p");
			lferror.setAttribute("id","loginformerror");
			loginform.appendChild(lferror);
			lferror.innerHTML = textnode;
    }
}

function quantitychange(image, dropdownlist)
{
	var cu = cart.head;
    while (cu != null)
	{
		if (cu.data.image == image)
		{
				cu.data.quantity = dropdownlist.selectedIndex + 1;
				break;
		}
		cu = cu.next;
	}
    showcart();
	updatecartonserver("updatecart", "isbn13=" + cu.data.isbn13 + "&quantity=" + cu.data.quantity);
}



function showcart()
{
    var pcartdiv = document.getElementById("realcart");
    var cartdiv = document.getElementById("realcartdata");
    var numitems = 0;
    var upitems = 0;
	var downitems = 0;
	var thehtml = "";

    if (cartdiv != null)
	    cartdiv.parentNode.removeChild(cartdiv);
    cartdiv = document.createElement("div");
	cartdiv.setAttribute("id", "realcartdata");
	//cartdiv.setAttribute("style","max-height:50%;overflow:auto;");
	pcartdiv.appendChild(cartdiv);

	//cartdiv.innerHTML = thehtml;
   
    var curr = cart.head;
    if (curr != null)
    {
	    
    
    curr = cart.head;
    thehtml = thehtml + "<table class=\"atp\" width=\"100%\">"; 
    for (index = 0; index < 4 ; index = index)
    {
        if (curr != null)
		{
			thehtml = thehtml + "<tr>" + "<td>" + "<img height=\"50px\" width=\"35px\" src=\"" + curr.data.image + "\" title=\"" + curr.data.title + "\"  onmouseover=\"this.style.cursor='pointer';\" onclick=\"highgetData('search','" + curr.data.isbn13 + "')\">" + "</td>" + "<td style=\"text-decoration:line-through\"> " + curr.data.listprice + "</td>" + "<td>" + curr.data.ourprice + "</td>" + "<td>" +
				"<select onchange=\"quantitychange('" + curr.data.image + "',this)\" name=\"" + curr.data.image + "\">";
   
			for (i = 0; i < 10; i = i + 1)
			{
				var j = i + 1;
				if (j ==  curr.data.quantity)
					thehtml = thehtml + "<option selected>"+ j + "</option>";
				else
					thehtml = thehtml + "<option>"+ j + "</option>";
			}
			thehtml = thehtml + "</select>" + "</td>" + "<td>" + "<div onmouseover=\"this.style.cursor='pointer';\" onclick=\"removefromcart('" + curr.data.isbn13 + "')\">" + "<img src=\"delete.jpg\" title=\"DELETE\">" + "</div> </td>" + "</tr>"
	    curr = curr.next;
	}
        else	
        {
			break;
			thehtml = thehtml + "</table>"
        }
	}
	}
	cartdiv.innerHTML = thehtml;

    var carttotal = document.getElementById("carttotal");
    var pcarttotal = document.getElementById("pcarttotal");
    if (pcarttotal != null)
        pcarttotal.parentNode.removeChild(pcarttotal);
    var total = 0;
	curr = cart.head;
    var items = 0;
    var tlp = 0;
    while (curr != null)
    {
	    total = total + parseInt(curr.data.ourprice) * parseInt(curr.data.quantity);
	    tlp = tlp + parseInt(curr.data.listprice) * parseInt(curr.data.quantity);
		items = items + parseInt(curr.data.quantity);
		curr = curr.next;
    }
    var usave = tlp - total;
	if (items != 0)
	{
		var thehtml = "";
		var thehtml = "<p class=\"boldatp\">" + "Total is INR " + total ;
		if (usave != 0)
			thehtml = thehtml +  "<br> <span class=\"atp\">You Save INR " + usave + "</span>";
		thehtml = thehtml + "<br><br><span class=\"boldatp\">Ship To </span><select onchange=\"shiplocchange(this)\">";
		if (shipto == "bangalore")
		{
			thehtml = thehtml + "<option value=\"25\" selected>Bangalore</option><option value=\"50\">Rest Of India</option></select><br> <span class=\"atp\" id=\"shippingcost\" >Shipping Cost INR 25";
		    var gtotal = 25 + total;
			thehtml = thehtml + "<br><br><span class=\"boldatp\">Grand Total INR " + gtotal + "</span></span></p>";
		}
		else
		{
			thehtml = thehtml + "<option value=\"25\">Bangalore</option><option value=\"50\" selected>Rest Of India</option></select><br> <span  class=\"atp\" id=\"shippingcost\" >Shipping Cost INR 50";
		    var gtotal = 50 + total;
			thehtml = thehtml + "<br><br><span class=\"boldatp\"> Grand Total INR " + gtotal + "</span></span></p>";
		}

		carttotal.innerHTML = thehtml;

		var confirmorderdiv = document.getElementById("confirmorder");
		var confirmorderdatadiv = document.getElementById("confirmorderdata");
		if (confirmorderdatadiv != null)
			confirmorderdatadiv.parentNode.removeChild(confirmorderdatadiv);
		confirmorderdatadiv = document.createElement("div");
		confirmorderdatadiv.setAttribute("id", "confirmorderdata");
		confirmorderdiv.appendChild(confirmorderdatadiv);

			var thehtml = "<form> <input type=\"button\" value=\"Proceed To Bill\" onclick=\"getaddress(false)\" onmouseover=\"this.style.cursor='pointer';\"> </form>";	
			confirmorderdatadiv.innerHTML = thehtml;
	}
	else
	{
			carttotal.innerHTML = "";
			var confirmorderdatadiv = document.getElementById("confirmorderdata");
			if (confirmorderdatadiv != null)
		confirmorderdatadiv.parentNode.removeChild(confirmorderdatadiv);
	}


	var cartitemsdiv = document.getElementById("cartitems");
	if (items > 0)
	{
		var thehtml = "";
		thehtml = thehtml + "<p>Shopping Cart" + "<span class=\"atp\">" + "(" + items;
		if (items == 1)
			thehtml = thehtml + " " + "Item" + ")" + "</span></p>";
		else
			thehtml = thehtml + " " + "Items" + ")" + "</span></p>";
		cartitemsdiv.innerHTML = thehtml;

	}
	else
	{
		cartitemsdiv.innerHTML = "";
	}
}
function shiplocchange(ship)
{
	var shiploc = document.getElementById("shippingcost");
	var sel = parseInt(ship.options[ship.selectedIndex].value);
	if (sel == 25)
		shipto = "bangalore";
	else
		shipto = "restofindia";
	var thehtml = "Shipping Cost INR " + sel + "<br>";
	var curr = cart.head;
	var total = 0;
    while (curr != null)
    {
	    total = total + parseInt(curr.data.ourprice) * parseInt(curr.data.quantity);
		curr = curr.next;
	}
	var gt = total + parseInt(sel);
	thehtml = thehtml + "<br><span class=\"boldatp\">Grand Total INR " + gt + "</span>";
	shiploc.innerHTML = thehtml;
}
function showuseraccount()
{
	userat="myaccount";
	if (email != "")
	{
		getData("myaccount", "email=" + email);
	}
	else
	{
		allowlogin(0,'','');
	}
}

function justremovefromcart(isbn13)
{
   cart.remove(isbn13);
}

function removefromcart (isbn13)
{
   justremovefromcart(isbn13);
   showcart();
   updatecartonserver("deletefromcart", isbn13);
}
function productchangerequest(products)
{
	var selection = products.options[products.selectedIndex].value;
	selproduct = products.selectedIndex;
	selsubproduct = 0;
	selsubsubproduct = 0;
	subproducts = null;
	subsubproducts = null;
	if (selproduct == 0)
		loadleftdata();
	else
		getData("querysubproducts","productid=" + selection);
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
function productchangeresponse(xmldocument)
{
	var xmlresp = xmldocument.documentElement;
    var response = xmlresp.getElementsByTagName("response");
	var responsevalue = response[0].getAttribute("its");
	if (responsevalue = "success")
    {
		var rsubproducts = xmlresp.getElementsByTagName("subproduct");
		subproducts = new Array();
		for (var i = 0; i <rsubproducts.length ; i++)
		{
			subproducts[i] =rsubproducts[i].childNodes[0].nodeValue;
	    	subproductsids[i] = parseInt(rsubproducts[i].getAttribute("id"));	
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
		getData("querysubsubproducts","subproductid=" + selection);
}
function realsearch(product,field,subject)
{
	getData("search", "search_string=" + subject.options[subject.selectedIndex].value);
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

function loadleftdata()
{
	var left = document.getElementById("leftsidedata");
	var thehtml = "<p class=\"ublueatp\"><br><br>Advanced Search</p>";
	thehtml = thehtml + "<form><select style=\"width:95%\" onchange=\"productchangerequest(this)\">";
	for (var i = 0; i < products.length ;i++)
	{
		if (i == selproduct)
			thehtml = thehtml + "<option style=\"width:95%\" value=\"" + productsids[i] + "\" selected>" + products[i] + "</option>";
		else
			thehtml = thehtml + "<option style=\"width:95%\" value=\"" + productsids[i] + "\">" + products[i] + "</option>";
	}
	thehtml = thehtml + "</select></form>";
	if (subproducts != null)
	{
		thehtml = thehtml + "<p class=\"atp\">Ok. " + products[selproduct] + ", now</p>" + "<form><select style=\"width:95%\" onchange=\"subproductchange(this)\">";
		for (var j = 0; j < subproducts.length; j++)
		{
			if (j == selsubproduct)
				thehtml = thehtml + "<option style=\"width:95%\" value=\"" + subproductsids[j] + "\" selected>" + subproducts[j] + "</option>";
			else
				thehtml = thehtml + "<option style=\"width:95%\" value=\"" + subproductsids[j] + "\">" + subproducts[j] + "</option>";
		}
		thehtml = thehtml + "</select></form>";
	}
	if (subsubproducts != null)
	{
		thehtml = thehtml + "<p class=\"atp\">Ok. " + subproducts[selsubproduct] + ", now</p>" + "<form><select style=\"width:95%\" onchange=\"subsubproductchange(this)\">";
		for (var j = 0; j < subsubproducts.length; j++)
		{
			thehtml = thehtml + "<option style=\"width:95%\" value=\"" + subsubproductsids[j] + "\">" + subsubproducts[j] + "</option>";
		}
		thehtml = thehtml + "</select></form>";

	}
	left.innerHTML = thehtml;
}
function doitonload()
{

	loadleftdata();
	loadhomedata();
	loadscart();
	loaduserdata();
}
<?php
 require('dbconnect.php');
 $link = wrap_mysqli_connect();
 $session_id = session_id();
 echo "function loaduserdata() {";
 $found = 0;
if ($link != null)
 {
	 if (isset($_SESSION['email']))
	 {
		 $email = $_SESSION['email'];
		 $db_query = "select name, hno, street, village,city,state,pincode,telephone from customers where email like '$email'";
		 $db_result = mysqli_query($link, $db_query);
		if ($db_result == TRUE)
		 {
			 $db_row = mysqli_fetch_row($db_result);
			 if ($db_row != null)
			 {
				 $found = 1;
				 echo "email = '$email';";
				 echo "name = '$db_row[0]';";
				 echo "hno = '$db_row[1]';";
				 echo "street = '$db_row[2]';";
				 echo "village = '$db_row[3]';";
				 echo "city = '$db_row[4]';";
				 echo "state = '$db_row[5]';";
				 echo "pincode = '$db_row[6]';";
				 echo "telephone = '$db_row[7]';";
				 echo "var logindata = document.getElementById('logindata');";
				 echo "logindata.innerHTML='Hello $email';";
				 echo "var loginlogout = document.getElementById('loginlogout');";
				 echo "loginlogout.innerHTML = \"<span class=\\\"ublueatp\\\" onmouseover=\\\"this.style.cursor='pointer';this.style.textDecoration='none';\\\" onclick=\\\"allowlogout()\\\" onmouseout=\\\"this.style.textDecoration='underline';\\\">Logout</span>\";";
			 }
 		}
	 }
 }
 if ($found == 0)
 {
     echo "var logindata = document.getElementById(\"logindata\");";
				 echo "logindata.innerHTML=\"Hello\";";
				 echo "var loginlogout = document.getElementById(\"loginlogout\");";
				 echo "loginlogout.innerHTML = \"<span class=\\\"ublueatp\\\" onmouseover=\\\"this.style.cursor='pointer';this.style.textDecoration='none';\\\" onclick=\\\"allowlogin(0,'','')\\\" onmouseout=\\\"this.style.textDecoration='underline';\\\">Login</span>\";";
 }


 echo "}";
 echo "function loadscart() {";
 
  if ($link != null)
  {
    $db_query = "select quantity, isbn13 from shopping_carts where session like '$session_id'";
    $db_result = mysqli_query($link, $db_query);
    if ($db_result == TRUE)
	{
	   $index = 0;
	   $db_row = mysqli_fetch_row($db_result);
	   while ($db_row != null)
	   {
		   $db_query1 = "select title,ourprice,listprice from book_inventory where isbn13 like '$db_row[1]'";
		   $db_result1 = mysqli_query($link, $db_query1);
		   if ($db_result1 == TRUE)
		   {
			   $db_row1 = mysqli_fetch_row($db_result1);
			   if ($db_row1 != null)
			   {
				   echo "addtocartonload('" . $db_row[1] . ".jpg'," . $db_row1[2] . ",$db_row1[1],'" . $db_row[1] . "','$db_row1[0]',$db_row[0]);";
			   }
		   }
		   $db_row = mysqli_fetch_row($db_result);
	   }
	   echo "showcart();";
	}
  }

echo "}";
echo "function loadhomedata() {";
      
       if ($link != null)
	   {
       $db_query = "select isbn13, title, author1,listprice, ourprice from book_inventory order by bookid desc limit 0,4";
       $db_result = mysqli_query($link, $db_query);
       if ($db_result == TRUE)
	   {
		   $index = 0;
		   $db_row = mysqli_fetch_row($db_result);
		   while ($db_row != null)
		   {
			   echo "newbookstitle[$index] = \"$db_row[1]\";";
			   echo "newbooksimage[$index] = '$db_row[0]" . ".jpg';";
			   echo "newbooksisbn13[$index] = '$db_row[0]';";
			   echo "newbooksauthor[$index] = '$db_row[2]';";
			   echo "newbookslp[$index] = $db_row[3];";
			   echo "newbooksop[$index] = $db_row[4];";
			   $index = $index + 1;
			   $db_row = mysqli_fetch_row($db_result);
		   }
	   }
	   $db_query = "select link,title from links";
	   $db_result = mysqli_query($link, $db_query);
	   if ($db_result == TRUE)
	   {
		   $db_row = mysqli_fetch_row($db_result);
		   $index = 0;
		   	while ($db_row != null)
			{
			   echo "linkvalue[$index] = '$db_row[0]';";
			   echo "linktitle[$index] = '$db_row[1]';";
			   $db_row = mysqli_fetch_row($db_result);
			   $index = $index + 1;
			}
	   }
	   }
?>

	var centerdata = document.getElementById("centerdata");
	   var thehtml = "<p class=\"ublueatp\"> New Books In The Store </p>";
	   thehtml = thehtml + "<table cellspacing=\"10\" width=\"95%\"><tr>";
    for (var index = 0; index < newbooksimage.length ; index++)
	{
    	thehtml = thehtml + "<td width=\"25%\"><img  onmouseover=\"this.style.cursor='pointer';\" onclick=\"highgetData('search','" + newbooksisbn13[index] + "')\"" + "width=\"70px\" height=\"100px\" src=\"" + newbooksimage[index] + "\" title=\"" + newbookstitle[index] + "\">" + "</td>";
	}
	thehtml = thehtml + "</tr><tr>";
    for (var index = 0; index < newbooksauthor.length ; index++)
	{
		thehtml = thehtml + "<td class=\"sblueatp\" width=\"25%\" valign=\"top\">" + newbookstitle[index] + "</td>";
	}
	thehtml = thehtml + "</tr><tr>";
    for (var index = 0; index < newbooksauthor.length ; index++)
	{
			thehtml = thehtml + "<td class=\"satp\">" + newbooksauthor[index] + "</td>";
	}
	thehtml = thehtml + "</tr><tr>";
    for (var index = 0; index < newbooksimage.length ; index++)
	{
		thehtml = thehtml + "<td width=\"25%\">"  + "List Price INR <span class=\"boldatp\">" + newbookslp[index] + "</span><br>Our Price INR <span class=\"boldatp\">" + newbooksop[index] + "</span></td>";
	}
	thehtml = thehtml + "</tr></table><hr align=\"left\" width=\"95%\">";
	thehtml = thehtml + "<p class=\"blueatp\"> Whats Interesting! </p>";



    for (var index = 0; index < linkvalue.length ; index++)
    {
		thehtml = thehtml + "<p class=\"atp\"> <a href=\"" + linkvalue[index] + "\">" + linktitle[index] + "</a></p>";
	}
    centerdata.innerHTML = thehtml;
    var footer = document.getElementById("footer");
    footer.innerHTML = " <hr><span class=\"blueatp\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"loadhomedata()\">Home</span> | <span class=\"blueatp\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"getData('copyright','')\"> Copyright </span> | <span class=\"blueatp\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"getData('termsconditions')\"> Terms and Conditions</span> | <span class=\"blueatp\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"getData('privacypolicy')\"> Privacy Policy</span>";

}
function highgetData(search,key)
{
    document.searchform.search_string.value = key;
   getData(search,"search_string=" + key);
}
function clearleftside()
{
	selproduct = 0;
	selsubproduct = 0;
	selsubsubproduct = 0;
	subproducts = null;
	subsubproducts = null;
	var leftsidedata = document.getElementById("leftsidedata");
	leftsidedata.innerHTML = "";

	loadleftdata();
}
function contactus()
{
	var centerdata = document.getElementById("centerdata");
	var thehtml = "<p class=\"boldatp\"> Support/Order Related Issues </p><p class=\"atp\">Monday to Saturday (10:00 AM to 6:00 PM)<br>E-Mail:<a href=\"mailto:support@popabook.com\">support@PopAbooK.com</a><br><p><br><p class=\"boldatp\"> Feedback and Suggestions</p><p class=\"atp\"> E-Mail:<a href=\"mailto:feedback@popabook.com\">feedback@PopAbooK.com</a><p><br> <p class=\"boldatp\"> Our Address </p> <p class=\"atp\"># 58/A <br>24th Cross, 18th Main<br>H.S.R. Layout, Sector-3 <br> Bangalore-560102<br> India <br></p> <p class=\"boldatp\">Business</p><p class=\"atp\">E-Mail:<a href=\"mailto:praveen@popabook.com\">praveen@PopAbooK.com</a> </p> <p> <a href=\"http://www.twitter.com/PopAbooK\"><img src=\"http://twitter-badges.s3.amazonaws.com/follow_bird_us-a.png\" alt=\"Follow PopAbooK on Twitter\"/></a></p>";
		centerdata.innerHTML = thehtml;
}
function donothing()
{
	;
}
function checkEnter(e,page,form)
{
   	
     var characterCode; 

	 if(e && e.which)
	 { 
		e = e;
		characterCode = e.which;
	}
	 else
	 {
		characterCode = e.keyCode;
	 }

	 if(characterCode == 13){ 
		 if (page == "search")
		 {
         	getData('search');
		 }
		 else if (page == "login")
		 {
			 processloginform(form);
		 }
		 else if (page == "confirm")
		 {
			 confirmorder(form);
		 }
		return false;
}
else{
return true;
}

}


</script>
<style type="text/css">

body {
	font-family: sans-serif;
	height:100%;
}


#maincenter {
width:60%;
text-align: left;
padding-left: 5px;
font-family:"lucida sans",verdana,sans-serif;
font-weight:lighter;
font-size:small;
}

.atp {
font-family:verdana,georgia;
font-weight:lighter;
font-size:small;
color:black;
}
.sblueatp {
font-family:verdana,georgia;
font-weight:lighter;
font-size:x-small;
color:blue;
}
.satp {
font-family:verdana,georgia;
font-weight:lighter;
font-size:x-small;
color:black;
}
.iatp {
font-family:verdana,georgia;
font-weight:lighter;
font-size:small;
font-style:italic;
color:black;
}
.uatp {
font-family:verdana,georgia;
font-weight:lighter;
font-size:small;
text-decoration:underline;
}
.logoatp {
font-family:verdana,georgia;
font-weight:bold;
font-size:large;
}


.bluebackatp {
font-family:verdana,georgia;
font-weight:lighter;
font-size:small;
background-color:#f0f0ff;
}
.blueatp {
font-family:verdana,georgia;
font-weight:lighter;
font-size:small;
color:blue;
}
.ublueatp {
font-family:verdana,georgia;
font-weight:lighter;
font-size:small;
color:blue;
text-decoration:underline;
}

.boldatp {
font-family:verdana,georgia;
font-weight:bold;
font-size:small;
}
.title {
font-family:verdana,georgia;
font-weight:bold;
font-size:small;
color:blue;
}
#leftside {
text-align: left;
width:15%;
}

#rightside {
width:18%;
padding-left:1px;
padding-right:1px;

}

.pgshow {
   width:3%;
   height:3%;
   border: solid 1px #DDDDDD;
   font-family: sans-serif;
   margin-right:2px;


   float:right;
}
.pgactive {
     width:3%;
   height:3%;

   font-family: sans-serif;
   margin-right:2px;


   float:right;
}
.pgpreviousnextshow {

   height:3%;

   font-family: sans-serif;
   margin-right:8px;
   border: solid 1px #DDDDDD;
   float:right;
}

.pgpreviousnextactive {

   height:3%;

   font-family: sans-serif;
   margin-right:8px;

 border: solid 1px #666666;
   float:right;
}

</style>

</head>

<body onload="doitonload()">
<table width="100%">
<tr>
<td><img src="popabook.jpg" onmouseover="this.style.cursor='pointer';" onclick="loadhomedata()"></td>
<td align="center">
<form name="searchform" >
<input type="text" size="50" value="" name="search_string" onKeyPress="return checkEnter(event,'search')"/>
<input class="blueatp" type="button" id="searchbutton" name="searchbutton1"value="search" onclick="getData('search')" />
</form></td>
<td class="atp" align="left" width="20%">
<table> 

<tr><td><span class="ublueatp" onmouseover="this.style.cursor='pointer'; this.style.textDecoration ='none';" onclick="loadhomedata()" onmouseout="this.style.textDecoration ='underline'">Home</span> | <span class="ublueatp" onclick="contactus()" onmouseover="this.style.cursor='pointer'; this.style.textDecoration ='none';" onclick="myaccount()" onmouseout="this.style.textDecoration ='underline'">Contact Us</span> | <span class="ublueatp" onmouseover="this.style.cursor='pointer'; this.style.textDecoration ='none';" onclick="getData('help')" onmouseout="this.style.textDecoration ='underline'"> Help </span></td></tr>
<tr><td><span id="login" class="atp"><span id="logindata">
Hello
</span> </span</td></tr>

<tr><td>
 <span id="loginlogout">Login</span> | <span class="ublueatp" onclick="showuseraccount()" onmouseover="this.style.cursor='pointer'; this.style.textDecoration ='none';" onclick="myaccount()" onmouseout="this.style.textDecoration ='underline'">Your Account</span>
</td></tr>
</table>
</td>

</tr>

</table>
<hr>
<div style="width:100%">
<table width="100%" height="100%">
<tr>
<td id="leftside" valign="top">

<div id="leftsidedata" height=\"80%\" class=\"atp\">
</div>
</td>
<td id="maincenter" valign="top">

<div id="center">
<div id="centerdata"> </div>
</div>
</td>
<td id="rightside" valign="top">
<div class="atp">
<div id="cartitems" style="font-weight:bold">

</div>

<div id="realcart" style="height:50%;overflow:auto;"> </div>
<div id="carttotal">
</div>

<div id="confirmorder">
</div>


 
      



</div>
</td>
</tr>
</table>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<div id="footer" style="text-align:center;">

</div>
<div id="securityseal" style="text-align:center;">
<span id="siteseal"><script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=w9FOkLHyzZ066mrdpFmp7sXeOeOj9XTsRUqcveO7edaoQ74M6nPhnBN"></script><br/><a style="font-family: arial; font-size: 9px" href="http://help.godaddy.com" target="_blank">Go Daddy</a></span>
</div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-12128118-1");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>


</html>

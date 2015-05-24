var name = "";
var testbase = "www.popabook.com/";
var email = "";
var city = "";
var state = "";
var pincode = "";
var telephone = "";
var userat="home";
var address="";
var loginattempts = 0;
var currentpage = 0;
var pabtimer = null;
var shipcost = 0;
var userathtml = "";
function cartitem (image, listprice,ourprice,quantity,isbn13,title,shiptime,seller)
{
	this.image = image;
	this.title = title;
	this.isbn10 = 0;
	this.isbn13 = isbn13;
	this.listprice = listprice;
	this.ourprice = ourprice;
	this.quantity = quantity;
	this.shiptime = shiptime;
	this.seller = seller;
}

function isempty()
{
    if (this.head == null)
       return true;
    else
       return false;
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
function LinkedList()
{
    this.head = null;
    this.add = addlistnodetohead;
    this.remove = removelistnode;
}
var cart = new LinkedList();
var wishlist = new LinkedList();
var wishitem = null;

var jsfiles = new Array();
function loadfile(filename)
{
	/*var i = 0;
	for (i = 0 ; i < jsfiles.length; i++)
	{
		if (jsfiles[i] == filename)
			break;
	}
	if (i == jsfiles.length)
	{
		var fileref=document.createElement('script')
  		fileref.setAttribute("type","text/javascript")
  		fileref.setAttribute("src", filename)
		document.getElementsByTagName("head")[0].appendChild(fileref);
	}*/;
}
function addloadfile(filename)
{
		jsfiles[jsfiles.length] = filename;
}
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
userat="home";
loginattempts = 0;
shipto = "bangalore";
address = "";
}
function allowlogin(signin,useremail,userpassword)
{
	if(loginattempts > 2)
	{
			return;
	}
	
    var cartdiv = document.getElementById("centerdata");
    if (userathtml != null)
	    userathtml = cartdiv.innerHTML;
	var thehtml = "<div class=\"atp\" style=\"padding-left:200px;\">";
	
	thehtml = thehtml + "<br><br><form id=\"loginform\"> <p class=\"logoatp\"> Enter Your E-Mail Address </p> <input type=\"text\" name=\"email\" value=\"" + useremail + "\">";

      
	if (signin == 0)
	{
			thehtml = thehtml + " <p class=\"logoatp\"> Enter Your Password </p> <input onKeyPress=\"checkEnter(event,'login',this.form)\" type=\"password\" name=\"password\" value=\"" + userpassword +"\">";
		thehtml = thehtml + "<span class=\"blueatp\" onmouseover=\"this.style.cursor='pointer';\" onclick=\"forgotpassword()\">" + " " + "Forgot Password ? Click Here" + "</span>";
	}
 
	if (signin == 1)
	{
			thehtml = thehtml + " <p class=\"logoatp\"> Enter Your Password </p> <input type=\"password\" name=\"password\" value=\"" + userpassword +"\">";
		thehtml = thehtml + "<p class=\"logoatp\"> Re-Enter Your Password </p> <input onKeyPress=\"checkEnter(event,'login',this.form)\" type=\"password\" name=\"repassword\">";
		thehtml = thehtml + "<p> <input type=\"checkbox\" name=\"newuser\" checked onclick=\"areunewuser(this.form)\"> Are You A New User ? <span class=\"blueatp\"> UnCheck To Log In </span> </p>";
    thehtml = thehtml + "<div> <a href=\"javascript:void(0)\" onclick=\"processloginform()\"><img src=\"http://www.popabook.com/signin.jpg\"></a></div></form>";
	}
	else
	{
		thehtml = thehtml + "<p> <input type=\"checkbox\" name=\"newuser\" onclick=\"areunewuser(this.form)\"> Are You A New User ? <span class=\"blueatp\">Check To Sign In</span></p>";
    thehtml = thehtml + "<div> <a href=\"javascript:void(0)\" onclick=\"processloginform()\"><img src=\"http://www.popabook.com/login.jpg\"></a></div></form>";
	}
	thehtml = thehtml + "<br><br><div id=\"loginformerror\"></div></div>";
	cartdiv.innerHTML = thehtml;
}


function display_response_at_center(xmldoc)
{
	var centerdata = document.getElementById("centerdata");
	centerdata.innerHTML = xmldoc;

}

function getData(req,reqdata,docpart)
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
				userat = "search";

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
              XMLHttpRequestObject.open("POST", testbase + "searchdata.php");
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
              	XMLHttpRequestObject.send("search_string=" + encodeURIComponent(document.searchform.search_string.value));
			  }
			  else
			  {
              	XMLHttpRequestObject.send(reqdata);

			  }
                          loading();
                          
			}
			 else if (req == "home")
			 {
				 XMLHttpRequestObject.open("POST",testbase + "home.php");
                 XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                 XMLHttpRequestObject.onreadystatechange = function()
                 {
                     if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                     {
                         process_homedata_xml_response(XMLHttpRequestObject.responseText);
                         delete XMLHttpRequestObject;
                         XMLHttpRequestObject = null;
                     		}
                	}
                	XMLHttpRequestObject.send();
			 }
			else if (req == "help" || req == "privacypolicy" || req == "copyright" || req == "termsconditions")
			{
				
                   XMLHttpRequestObject.open("POST",      testbase  + req + ".php");
                   XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                   XMLHttpRequestObject.onreadystatechange = function()
                   {
                       if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                       {
                            display_response_at_center(XMLHttpRequestObject.responseText);
                            delete XMLHttpRequestObject;
                            XMLHttpRequestObject = null;
                       }
                   }
					   XMLHttpRequestObject.send();
			}
			else if (req == "getbook")
		   	{
				XMLHttpRequestObject.open("POST", testbase + "getbook.php");
                   XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                   XMLHttpRequestObject.onreadystatechange = function()
                   {
                       if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                       {
                            getbookresponse(XMLHttpRequestObject.responseText);
                            delete XMLHttpRequestObject;
                            XMLHttpRequestObject = null;
                            FB.XFBML.parse();
                       }
                   }
					   XMLHttpRequestObject.send("reqtype=" + req + "&" + reqdata);
                             userat = "getbook";
                             loading();

			}
			else if (req == "browse")
		   	{
				XMLHttpRequestObject.open("POST",testbase + "browse.php");
                   XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                   XMLHttpRequestObject.onreadystatechange = function()
                   {
                       if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                       {
                            display_response_at_center(XMLHttpRequestObject.responseText);
                            delete XMLHttpRequestObject;
                            XMLHttpRequestObject = null;
                       }
                   }
				   XMLHttpRequestObject.send("reqtype=" + req + "&" + reqdata);
                           userat = "browse";
                           loading();
			}
			else if (req == "getusedbooks")
		   	{
				XMLHttpRequestObject.open("POST",testbase + "getusedbooks.php");
                   XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                   XMLHttpRequestObject.onreadystatechange = function()
                   {
                       if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                       {
                            getusedbooksresponse(reqdata,XMLHttpRequestObject.responseText);
                            delete XMLHttpRequestObject;
                            XMLHttpRequestObject = null;
                       }
                   }
				   XMLHttpRequestObject.send("reqtype=" + req + "&" + reqdata);
			}
                        else if (req == "getreviews")
                        {
                                XMLHttpRequestObject.open("POST",testbase + "getreviews.php");
                   XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                   XMLHttpRequestObject.onreadystatechange = function()
                   {
                       if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                       {
                            getreviewsresponse(reqdata,XMLHttpRequestObject.responseText);
                            delete XMLHttpRequestObject;
                            XMLHttpRequestObject = null;
                            FB.XFBML.parse();
                       }
                   }
                                   XMLHttpRequestObject.send("reqtype=" + req + "&" + reqdata);
                        }
                        else if (req == "getviewallbook")
                        {
                                XMLHttpRequestObject.open("POST",testbase + "getviewallbook.php");
                   XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                   XMLHttpRequestObject.onreadystatechange = function()
                   {
                       if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                       {
                            process_viewallbookresponse(reqdata, XMLHttpRequestObject.responseText);
                            delete XMLHttpRequestObject;
                            XMLHttpRequestObject = null;
                            FB.XFBML.parse();
                       }
                   }
                                   XMLHttpRequestObject.send("reqtype=" + req + "&" + reqdata);
                        }


		}
}
function getusedbooksresponse(reqdata, htmldocument)
{
           var temp = new Array();
           temp = reqdata.split('=');
	
	var blurbdata = document.getElementById(temp[1] + "blurbdata");
	   blurbdata.innerHTML = "<br>" + htmldocument;

}
function getreviewsresponse(reqdata, htmldocument)
{
           var temp = new Array();
           temp = reqdata.split('=');
	
	var blurbdata = document.getElementById(temp[1] + "blurbdata");
	   blurbdata.innerHTML = "<br>" + htmldocument;

}
function getbookresponse(htmlresponse)
{
	var centerdata = document.getElementById("centerdata");
	centerdata.innerHTML = htmlresponse;
}
function getbook(type,key)
{
	getData("getbook","search_string=" + key);
}
function process_xml_response(xmldocumenttext, xmldocument)
{
    var cartdiv = document.getElementById("centerdata");
    cartdiv.innerHTML = xmldocumenttext;
    return;
}
function process_homedata_xml_response(text)
{
	var centerdata = document.getElementById("centerdata");
	centerdata.innerHTML = text;
}

function clearcenter()
{
		var centerdata = document.getElementById("centerdata");
		centerdata.innerHTML = "";
}

function clearrightside()
{
	var scartdiv = document.getElementById("rightsidecart");
	var lc = scartdiv.lastChild;
		while (lc != null)
		{
			scartdiv.removeChild(lc);
			lc = scartdiv.lastChild;
		}
		scartdiv.style.lineHeight = '0';
		cart.head = null;
		wishlist.head = null;
}
function showuseraccount(myaccount,input)
{
	userat = "myaccount";
	if (email != "")
	{
		getData4(myaccount, "email=" + email + "&" + input);
	}
	else
	{
		allowlogin(0,'','');
	}
}
function getusedbooks(isbn13)
{
	getData('getusedbooks','isbn13=' + isbn13);
        var header = document.getElementById(isbn13 + "header");
	var usedbooks = document.getElementById(isbn13 + "usedbooks");
	var ubcount = usedbooks.innerHTML;
	var bookoverview = document.getElementById(isbn13 + "overview");

	var headertxt = "<table width=\"100%\"> <tr><td width=\"20%\">&nbsp;";
	if (bookoverview != null)
		headertxt = headertxt + "<a id=\"" + isbn13 + "overview\" href=\"javascript:void(0)\" onclick=\"getblurb('" + isbn13 + "')\" class=\"blueatp\">Read Overview</a>&nbsp;</td>";
	else
		headertxt = headertxt + "&nbsp;</td>";
	headertxt = headertxt + "<td width=\"20%\" align=\"center\"><a href=\"javascript:void(0)\" onclick=\"getreviews('" + isbn13 + "')\">Friend's Reviews</a></td><td width=\"20%\" align=\"center\"> <a href=\"javascript:void(0)\" onclick=\"getsimilarbooks('" + isbn13 + "')\">Similar Books</a></td><td width=\"20%\" align=\"center\">";
		headertxt = headertxt + "<a href=\"javascript:void(0)\" id=\"" + isbn13 + "usedbooks\" class=\"blueatp\" onclick=\"closeblurb('" + isbn13 + "')\">Hide " + ubcount + "</a></td>";
        headertxt = headertxt + "<td width=\"20%\" align=\"right\"><a href=\"javascript:void(0)\" onclick=\"getviewallbook('" + isbn13 + "')\">View All</a></td></tr></table>";
	header.innerHTML = headertxt;
}
function getreviews(isbn13)
{
	getData('getreviews','isbn13=' + isbn13);
        var header = document.getElementById(isbn13 + "header");
	var usedbooks = document.getElementById(isbn13 + "usedbooks");
	var ubcount = usedbooks.innerHTML;
	var bookoverview = document.getElementById(isbn13 + "overview");

	var headertxt = "<table width=\"100%\"> <tr><td width=\"20%\">&nbsp;";
	if (bookoverview != null)
		headertxt = headertxt + "<a id=\"" + isbn13 + "overview\" href=\"javascript:void(0)\" onclick=\"getblurb('" + isbn13 + "')\" class=\"blueatp\">Read Overview</a>&nbsp;</td>";
	else
		headertxt = headertxt + "&nbsp;</td>";
	headertxt = headertxt + "<td width=\"20%\" align=\"center\"><a href=\"javascript:void(0)\" onclick=\"closeblurb('" + isbn13 + "')\">Hide Reviews</a></td><td width=\"20%\" align=\"center\"> <a href=\"javascript:void(0)\" onclick=\"getsimilarbooks('" + isbn13 + "')\">Similar Books</a></td><td width=\"20%\" align=\"center\">";
	if (ubcount == "0 Used Books")
	{
		headertxt = headertxt + "<span id=\"" + isbn13 + "usedbooks\" class=\"blueatp\">0 Used Books</span></td>";
	}
	else
	{
               	ubcount = ubcount.replace("Hide ","");
		headertxt = headertxt + "<a href=\"javascript:void(0)\" id=\"" + isbn13 + "usedbooks\" class=\"blueatp\" onclick=\"getusedbooks('" + isbn13 + "')\">" + ubcount + "</a></td>";
	}
        headertxt = headertxt + "<td width=\"20%\" align=\"right\"><a href=\"javascript:void(0)\" onclick=\"getviewallbook('" + isbn13 + "')\">View All</a></td></tr></table>";
	header.innerHTML = headertxt;
	var blurbdata = document.getElementById(isbn13 + "blurbdata");
	blurbdata.innerHTML = "<br><br><div style=\"text-align:center;\"><img src=\"http://www.popabook.com/loading.gif\"></div><br><br>";
}
function getviewallbook(isbn13)
{
	getData('getviewallbook','isbn13=' + isbn13);
        var header = document.getElementById(isbn13 + "header");
	var usedbooks = document.getElementById(isbn13 + "usedbooks");
	var ubcount = usedbooks.innerHTML;
	var bookoverview = document.getElementById(isbn13 + "overview");

	var headertxt = "<table width=\"100%\"> <tr><td width=\"20%\">&nbsp;";
	if (bookoverview != null)
		headertxt = headertxt + "<a id=\"" + isbn13 + "overview\" href=\"javascript:void(0)\" onclick=\"getblurb('" + isbn13 + "')\" class=\"blueatp\">Read Overview</a>&nbsp;</td>";
	else
		headertxt = headertxt + "&nbsp;</td>";
	headertxt = headertxt + "<td width=\"20%\" align=\"center\"><a href=\"javascript:void(0)\" onclick=\"getreviews('" + isbn13 + "')\">Friend's Reviews</a></td><td width=\"20%\" align=\"center\"> <a href=\"javascript:void(0)\" onclick=\"getsimilarbooks('" + isbn13 + "')\">Similar Books</a></td><td width=\"20%\" align=\"center\">";
	if (ubcount == "0 Used Books")
	{
		headertxt = headertxt + "<span id=\"" + isbn13 + "usedbooks\" class=\"blueatp\">0 Used Books</span></td>";
	}
	else
	{
               	ubcount = ubcount.replace("Hide ","");
		headertxt = headertxt + "<a href=\"javascript:void(0)\" id=\"" + isbn13 + "usedbooks\" class=\"blueatp\" onclick=\"getusedbooks('" + isbn13 + "')\">" + ubcount + "</a></td>";
	}
        headertxt = headertxt + "<td width=\"20%\" align=\"right\"><a href=\"javascript:void(0)\" onclick=\"closeblurb('" + isbn13 + "')\">Hide All</a></td></tr></table>";
	header.innerHTML = headertxt;
	var blurbdata = document.getElementById(isbn13 + "blurbdata");
	blurbdata.innerHTML = "<br><br><div style=\"text-align:center;\"><img src=\"http://www.popabook.com/loading.gif\"></div><br><br>";
}


function loadleftdata()
{
	var left = document.getElementById("leftsidedata");
	var thehtml = "<div class=\"box\"> <div class=\"boxh\"><span class=\"logoatp\"><br>Browse All<br><br></span></div><br><br>";
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
	thehtml = thehtml + "<br><br></div>";
	left.innerHTML = thehtml;
}
function doitonload()
{
	/*loadleftdata(); */
        if ("https:" == document.location.protocol)
            testbase = "https://" + testbase;
        else
            testbase = "http://" + testbase;
            
	loadwishlist();
	loadscart();
	loaduserdata();
	loadfile("search.js");
        loadfile ("browse.js");
	loadfile("login.js");

}

function highgetData(search,key)
{
	key = decodeURIComponent(key);
    document.searchform.search_string.value = key;
   getData(search,"search_string=" + encodeURIComponent(key));
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

	/*loadleftdata();*/
}
function contactus()
{
	var centerdata = document.getElementById("centerdata");
	var thehtml = "<div style=\"padding-left:20px;\"><br><br><p class=\"boldatp\"> Support/Order Related Issues </p><p class=\"atp\">Monday to Saturday (10:00 AM to 6:00 PM)<br>Phone: +919538851062<br>E-Mail:<a href=\"mailto:support@popabook.com\">support@PopAbooK.com</a><br><p><br><p class=\"boldatp\"> Feedback and Suggestions</p><p class=\"atp\"> E-Mail:<a href=\"mailto:feedback@popabook.com\">feedback@PopAbooK.com</a><p><br> <p class=\"boldatp\"> Our Address </p> <p class=\"atp\"># 58/A <br>24th Cross, 18th Main<br>H.S.R. Layout, Sector-3 <br> Bangalore-560102<br> India <br></p> <p class=\"boldatp\">Business</p><p class=\"atp\">E-Mail:<a href=\"mailto:praveen@popabook.com\">praveen@PopAbooK.com</a> </p> <p> <a href=\"http://www.twitter.com/PopAbooK\"><img src=\"http://www.popabook.com/follow_bird_us-a.png\" alt=\"Follow PopAbooK on Twitter\"/></a></p></div>";
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
                 else if (page == "salesaccount")
                 {
                       updatesalesaccount(form);
                 }
                 else if (page == "sellbooks")
                 {
                      sellbook(form);
                 }
                            if (e.preventDefault) e.preventDefault();
       if (e.stopPropagation) e.stopPropagation();
		return false;
}
else{
return true;
}

}
function browse()
{
	/* var browselink = document.getElementById("browselink");
	browselink.innerHTML = "<a href=\"javascript:void(0);\"  onclick=\"unbrowse();getData('browse','browse=browseall')\" style=\"color:#000000;\">Browse Books</a>";
	browselink.style.borderTop='1px solid #2b60de';
	browselink.style.borderLeft='1px solid #2b60de';
	browselink.style.backgroundColor='#ffffff'; */
	var browse = document.getElementById("browseoptions");
	browse.style.zIndex = 3;
	
}
function unbrowse()
{
	/* var browselink = document.getElementById("browselink");
	browselink.innerHTML = "<a href=\"javascript:void(0);\"  onclick=\"unbrowse();getData('browse','browse=browseall')\" style=\"color:#ffffff;\">Browse Books</a>&nbsp;<img src=\"http://www.popabook.com/dd.jpg\" onmouseover=\"this.style.cursor='pointer';starttimer()\" onmouseout=\"stoptimer()\">";
	browselink.style.backgroundColor='#2b60de';
	browselink.style.borderTop='';
	browselink.style.borderLeft=''; */
		var browse = document.getElementById("browseoptions");
		browse.style.zIndex = -1;
}
function starttimer()
{
	pabtimer = setTimeout('browse()',500);
}
function stoptimer()
{
  clearTimeout(pabtimer);
}


function getaddress(disableconfirm)
{
	userat = "getaddress";
	var cartdiv = document.getElementById("centerdata");

	var thehtml = "<div id=\"adform\" class=\"atp\" style=\"padding-left:200px;width:500px\"> <form id=\"addressform\" method=\"POST\" action=\"https://secure.ebs.in/pg/ma/sale/pay/\"> <p class=\"logoatp\"> <br>Shipping Address Please </p>" + 
		        "<input name=\"account_id\" type=\"hidden\" value=\"6116\">  <input name=\"return_url\" type=\"hidden\" value=\"http://www.popabook.com/response.php?DR={DR}\" /> <input name=\"mode\" type=\"hidden\" value=\"LIVE\"/> <input name=\"reference_no\" type=\"hidden\" value=\"empty\"/> <input name=\"amount\" type=\"hidden\" value=\"empty\"/> <input name=\"description\" type=\"hidden\" /> <input name=\"name\" type=\"hidden\"/><input name=\"address\" type=\"hidden\"/> <input name=\"city\" type=\"hidden\"/>  <input name=\"postal_code\" type=\"hidden\"/> <input name=\"phone\" type=\"hidden\"/> <input name=\"state\" type=\"hidden\"/> <input name=\"country\" type=\"hidden\"/>"+ "<table><tr><td>Name* </td>" + "<td><input onKeyPress=\"checkEnter(event,'confirm',this.form)\" name=\"ship_name\" type=\"text\" value=\"" + name + "\" />" + "</td></tr>" + "<tr><td> Address* </td>" + "<td> <textarea name=\"ship_address\" rows=\"3\" >" + address + "</textarea> </td></tr>" + "<tr><td> City/District* </td> <td>  <input onKeyPress=\"checkEnter(event,'confirm',this.form)\" name=\"ship_city\" type=\"text\" value=\"" + city + "\"/></td></tr>" +  "<tr><td> PinCode* </td> <td>    <input onKeyPress=\"checkEnter(event,'confirm',this.form)\" name=\"ship_postal_code\" type=\"text\" value=\"" + pincode + "\"/> </td></tr> " +  "<tr><td>Telephone* </td> <td> <input onKeyPress=\"checkEnter(event,'confirm',this.form)\" name=\"ship_phone\" type=\"text\" value=\"" + telephone + "\"/> </td> </tr> " + " <tr><td>E-Mail* </td>";
	if (email != "")
           thehtml = thehtml + "<td>  <input onKeyPress=\"checkEnter(event,'confirm',this.form)\" name=\"email\" type=\"text\" value=\"" + email + "\" readonly/> </td>";
	else
           thehtml = thehtml + "<td>  <input onKeyPress=\"checkEnter(event,'confirm',this.form)\" name=\"email\" type=\"text\" value=\"" + email + "\" /> </td>";
	       thehtml = thehtml + "</tr>" + "<tr><td>State*</td><td> <input onKeyPress=\"checkEnter(event,'confirm',this.form)\" name=\"ship_state\" type=\"text\" value=\"" + state + "\"/> </td></tr>" +"<tr><td>Country* </td><td> <input onKeyPress=\"checkEnter(event,'confirm',this.form)\" type=\"text\" name=\"ship_country\" value=\"India\" readonly/> </td></tr>" + "</table>";
	 /*      thehtml = thehtml + "<br><br><input type=\"radio\" name=\"paytype\" value=\"cash\" checked>Pay Cash On Delivery<br><br>";*/

      thehtml = thehtml + "<table width=\"90%\"><tr><td width=\"80%\">";
      /* <input type=\"radio\" name=\"paytype\" value=\"cash\">Pay Cash On Delivery<br><br>";*/
       thehtml = thehtml + "<input type=\"radio\" name=\"paytype\" value=\"online\" checked>Pay using Credit Cards, NetBanking, Debit Cards and Cash Cards<br><br></td>";
       thehtml = thehtml + "<td> <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td><a href=\"http://www.ebs.in/\" target=\"_blank\"><img src=\"http://www.popabook.com/EBS_Flasher1.gif\" title=\"EBS\" border=\"0\"></a></td></tr></table></td></tr></table>";
	if (disableconfirm == true)
	{
              thehtml = thehtml + "<table width=\"90%\"> <tr> <td><div> <img alt=\"Please Wait ...\" src=\"http://www.popabook.com/pw.jpg\"></div></td></tr><tr><td class=\"satp\"><br>By clicking on Confirm & Pay button above, you agree to PopAbooK.com Terms and Conditions and agree to have read and understood the Privacy Policy.</td></tr></table>" + "</form> </div>";
	}	
	else
	{
		thehtml = thehtml +"<table width=\"90%\"> <tr> <td><div><a href=\"javascript:void(0)\"><img alt=\"Confirm & Pay\" src=\"http://www.popabook.com/buttoncp.jpg\" style=\"color:#ffffff;\"  onclick=\"confirmorder()\"></a></div></td></tr><tr><td class=\"satp\"><br>By clicking on Confirm & Pay button above, you agree to PopAbooK.com Terms and Conditions and agree to have read and understood the Privacy Policy.</td></tr></table>" + "</form> </div>"; 
	}
	cartdiv.innerHTML = thehtml;

}


function open_win()
{
	width = screen.width;
	height = screen.height;
	left = width - 200 - width/2;
	down = height - 200 - height/2;
	window.open("help.php#usedbooks","_blank","width=400,height=400,left=" + left + ",top=" + down + ",resizable=yes,scrollbars=yes");
}
function getsimilarbooks(isbn13)
{
   getData1("getsimilarbooks", "isbn13=" + isbn13);
	var header = document.getElementById(isbn13 + "header");
	var usedbooks = document.getElementById(isbn13 + "usedbooks");
	var ubcount = usedbooks.innerHTML;
	var bookoverview = document.getElementById(isbn13 + "overview");

	var headertxt = "<table width=\"100%\"> <tr><td width=\"20%\">&nbsp;";
	if (bookoverview != null)
		headertxt = headertxt + "<a id=\"" + isbn13 + "overview\" href=\"javascript:void(0)\" onclick=\"getblurb('" + isbn13 + "')\" class=\"blueatp\">Read Overview</a>&nbsp;</td>";
	else
		headertxt = headertxt + "&nbsp;</td>";
	headertxt = headertxt + "<td width=\"20%\" align=\"center\"><a href=\"javascript:void(0)\" onclick=\"getreviews('" + isbn13 + "')\">Friend's Reviews</a></td><td width=\"20%\" align=\"center\"> <a href=\"javascript:void(0)\" onclick=\"closeblurb('" + isbn13 + "')\">Hide Similar Books</a></td><td width=\"20%\" align=\"center\">";
	if (ubcount == "0 Used Books")
	{
		headertxt = headertxt + "<span id=\"" + isbn13 + "usedbooks\" class=\"blueatp\">0 Used Books</span></td>";
	}
	else
	{
               	ubcount = ubcount.replace("Hide ","");
		headertxt = headertxt + "<a href=\"javascript:void(0)\" id=\"" + isbn13 + "usedbooks\" class=\"blueatp\" onclick=\"getusedbooks('" + isbn13 + "')\">" + ubcount + "</a></td>";
	}
        headertxt = headertxt + "<td width=\"20%\" align=\"right\"><a href=\"javascript:void(0)\" onclick=\"getviewallbook('" + isbn13 + "')\">View All</a></td></tr></table>";
	header.innerHTML = headertxt;
	var blurbdata = document.getElementById(isbn13 + "blurbdata");
	blurbdata.innerHTML = "<br><br><div style=\"text-align:center;\"><img src=\"http://www.popabook.com/loading.gif\"></div><br><br>";
}
function getblurb(isbn13)
{
	getData1("getblurb", "isbn13=" + isbn13);
	var header = document.getElementById(isbn13 + "header");
	var usedbooks = document.getElementById(isbn13 + "usedbooks");
	var ubcount = usedbooks.innerHTML;
	var headertxt = "<table width=\"100%\"> <tr><td width=\"20%\">&nbsp;<a id=\"" + isbn13 + "overview\" href=\"javascript:void(0)\" onclick=\"closeblurb('" + isbn13 + "')\" class=\"blueatp\">Hide Overview</a></td><td width=\"20%\" align=\"center\"><a href=\"javascript:void(0)\" onclick=\"getreviews('" + isbn13 + "')\">Friend's Reviews</a></td><td width=\"20%\" align=\"center\"> <a href=\"javascript:void(0)\" onclick=\"getsimilarbooks('" + isbn13 + "')\">Similar Books</a></td><td width=\"20%\" align=\"center\">";
	if (ubcount == "0 Used Books")
	{
		headertxt = headertxt + "<span id=\"" + isbn13 + "usedbooks\" class=\"blueatp\">0 Used Books</span></td>";
	}
	else
	{
               	ubcount = ubcount.replace("Hide ","");
		headertxt = headertxt + "<a href=\"javascript:void(0)\" id=\"" + isbn13 + "usedbooks\" class=\"blueatp\" onclick=\"getusedbooks('" + isbn13 + "')\">" + ubcount + "</a></td>";
	}
        headertxt = headertxt + "<td width=\"20%\" align=\"right\"><a href=\"javascript:void(0)\" onclick=\"getviewallbook('" + isbn13 + "')\">View All</a></td></tr></table>";
	header.innerHTML = headertxt;
	var blurbdata = document.getElementById(isbn13 + "blurbdata");
	blurbdata.innerHTML = "<br><br><div style=\"text-align:center;\"><img src=\"http://www.popabook.com/loading.gif\"></div><br><br>";

}
function process_getblurbdata_xml_response(reqdata, xmldocument)
{
           var temp = new Array();
           temp = reqdata.split('=');
	
	var blurbdata = document.getElementById(temp[1] + "blurbdata");
	   blurbdata.innerHTML = "<br>" + xmldocument;
}
function process_viewallbookresponse(reqdata, xmldocument)
{
           var temp = new Array();
           temp = reqdata.split('=');
	
	var blurbdata = document.getElementById(temp[1] + "blurbdata");
	   blurbdata.innerHTML = "<br>" + xmldocument;
}
function closeblurb(isbn13)
{
	var blurbdata = document.getElementById(isbn13 + "blurbdata");
	blurbdata.innerHTML = "";
	var header = document.getElementById(isbn13 + "header");
	var usedbooks = document.getElementById(isbn13 + "usedbooks");
	var ubcount = usedbooks.innerHTML;
	var bookoverview = document.getElementById(isbn13 + "overview");

	var headertxt = "<table width=\"100%\"> <tr><td width=\"20%\">&nbsp;";
	if (bookoverview != null)
		headertxt = headertxt + "<a id=\"" + isbn13 + "overview\" href=\"javascript:void(0)\" onclick=\"getblurb('" + isbn13 + "')\" class=\"blueatp\">Read Overview</a>&nbsp;</td>";
	else
		headertxt = headertxt + "&nbsp;</td>";
        headertxt = headertxt + "<td width=\"20%\" align=\"center\"><a href=\"javascript:void(0)\" onclick=\"getreviews('" + isbn13 + "')\">Friend's Reviews</a></td>";
	headertxt = headertxt + "<td width=\"20%\" align=\"center\"> <a href=\"javascript:void(0)\" onclick=\"getsimilarbooks('" + isbn13 + "')\">Similar Books</a></td><td width=\"20%\" align=\"center\">";
	if (ubcount == "0 Used Books")
	{
		headertxt = headertxt + "<span id=\"" + isbn13 + "usedbooks\" class=\"blueatp\">0 Used Books</span></td>";
	}
	else
	{
               	ubcount = ubcount.replace("Hide ","");
		headertxt = headertxt + "<a href=\"javascript:void(0)\" id=\"" + isbn13 + "usedbooks\" class=\"blueatp\" onclick=\"getusedbooks('" + isbn13 + "')\">" + ubcount + "</a></td>";
	}
        headertxt = headertxt + "<td width=\"20%\" align=\"right\"><a href=\"javascript:void(0)\" onclick=\"getviewallbook('" + isbn13 + "')\">View All</a></td></tr></table>";
	header.innerHTML = headertxt;
}

function getData1(req,reqdata,docpart)
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
			 if (req == "getblurb")
			 {
				 XMLHttpRequestObject.open("POST", testbase + "getblurb.php");
                 XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                 XMLHttpRequestObject.onreadystatechange = function()
                 {
                     if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                     {
                         process_getblurbdata_xml_response(reqdata,XMLHttpRequestObject.responseText);
                         delete XMLHttpRequestObject;
                         XMLHttpRequestObject = null;
                            FB.XFBML.parse();
                     }
                }
                XMLHttpRequestObject.send("reqtype=" + req + "&" + reqdata);
			 }
			 else if (req == "getsimilarbooks")
			 {
				 XMLHttpRequestObject.open("POST",testbase + "getsimilarbooks.php");
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
			 }
}
function addtocartonload(image,listprice,ourprice,isbn13,title,quantity,shiptime)
{
var item = new cartitem(image,listprice,ourprice,quantity,isbn13,title,shiptime,"");
cart.add(item); 

}
function addtowishlistonload(isbn13,title,image,listprice,ourprice,shiptime)
{
	title = unescape(title);
var item = new cartitem(image,listprice,ourprice,1,isbn13,title,shiptime);
wishlist.add(item); 
}
function addtowishlist(isbn13,title, listprice, ourprice, shiptime,image)
{
	var curr = wishlist.head;
	var found = 0;
	title = unescape(title);

	while (curr != null)
	{
		if (isbn13 == curr.data.isbn13)
		{
			found = 1;
				break;
		}
		curr = curr.next;
	}
    	if (found == 0)
	{	
		wishitem = new cartitem(image,listprice,ourprice,1,isbn13,title,shiptime,"");
		if (email != "")
		{
        		wishlist.add(wishitem); 
			updatewishlistonserver("addtowishlist","isbn13=" + wishitem.isbn13);
			showwishlist();
		}
		else
			allowlogin(0,"","");
	}
	else
		showwishlist();
}

function addtocart(newbook,listprice,ourprice,isbn13,title,shiptime,seller)
{
	var curr = cart.head;
	var found = 0;
	title = unescape(title);
	var temp = new Array();
	temp = isbn13.split('#');
	
	var item = new cartitem(newbook,listprice,ourprice,1,isbn13,title,shiptime,seller);
	while (curr != null)
	{
		if (isbn13 == curr.data.isbn13)
		{
				found = 1;
				if (temp.length == 1)
				item.quantity = parseInt(curr.data.quantity) + 1;
				else
				item.quantity = parseInt(curr.data.quantity);

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
function updatewishlistonserver(req,reqdata)
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
             XMLHttpRequestObject1.open("POST",testbase + "wishlistonserver.php");
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
	        if (req == "addtowishlist" || req == "removefromwishlist")
	        {
             	  XMLHttpRequestObject1.send("reqtype=" + req + "&" + reqdata + "&email=" + email);
	     	}
	     
	 }
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
             XMLHttpRequestObject1.open("POST",testbase + "updatecartonserver.php");
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

function quantitychange(isbn13, dropdownlist)
{
	var cu = cart.head;
    while (cu != null)
	{
		if (cu.data.isbn13 == isbn13)
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
    var scartdiv = document.getElementById("rightsidecart");
		scartdiv.style.lineHeight = 'normal';
    var total = 0;
    curr = cart.head;
    var items = 0;
    var tlp = 0;
    var thehtml = "";
    shipcost = 0;
     var pabtotal = 0;
    while (curr != null)
    {
	    total = total + parseInt(curr.data.ourprice) * parseInt(curr.data.quantity);
	    tlp = tlp + parseInt(curr.data.listprice) * parseInt(curr.data.quantity);
		items = items + parseInt(curr.data.quantity);
		curr = curr.next;
    }
    if (items > 0)
    {
    	thehtml = "<div class=\"pabscart\"><br>&nbsp;Shopping Cart" + "&nbsp;<span class=\"atp\">" + "(" + items;
		if (items == 1)
			thehtml = thehtml + " " + "Item" + ")" + "</span>";
		else
			thehtml = thehtml + " " + "Items" + ")" + "</span>";
		if (wishlist.head != null)
		thehtml = thehtml + "<br><span class=\"satp\" onclick=\"showwishlist()\"><a href=\"javascript:void(0)\"><br>(View Wish List)<br></a> </span>";
		else
			thehtml = thehtml + "<br>";
		curr = cart.head;
    	thehtml = thehtml + "<br><div class=\"pabborder\"></div><table class=\"atp\" width=\"100%\">"; 
    	while (curr != null)
    	{
		var sellinfo = "";
		var isbn = "";
		var lp = curr.data.listprice * curr.data.quantity;
		var op = curr.data.ourprice * curr.data.quantity;
           	var temp = new Array();
           	temp = curr.data.isbn13.split('#');
		if (temp.length == 2)
			isbn = temp[1];
		else
			isbn = curr.data.isbn13;

		        if (temp.length == 1)
			{
				pabtotal = pabtotal + parseInt(curr.data.ourprice) * parseInt(curr.data.quantity);
				thehtml = thehtml + "<tr>";
			}
			else
			{
                                shipcost = shipcost + 30;
				sellinfo = ". Sold by " + curr.data.seller;
				thehtml = thehtml + "<tr bgcolor=\"#ffeeff\">";
			}
			thehtml = thehtml + "<td>" + "<img height=\"100px\" width=\"70px\" src=\"http://www.popabook.com/optimage/" + curr.data.image + "\" title=\"" + curr.data.title + sellinfo + "\"  onmouseover=\"this.style.cursor='pointer';\" onclick=\"getbook('getbook','book/" + isbn + "')\">" + "</td>" + "<td style=\"text-decoration:line-through\" > " + lp + "</td>" + "<td>" + op + "</td>" + "<td>" +
				"<select onchange=\"quantitychange('" + curr.data.isbn13 + "',this)\" name=\"" + curr.data.image + "\">";
			if (sellinfo == "")
			{
   
			for (i = 0; i < 10; i = i + 1)
			{
				var j = i + 1;
				if (j ==  curr.data.quantity)
					thehtml = thehtml + "<option selected>"+ j + "</option>";
				else
					thehtml = thehtml + "<option>"+ j + "</option>";
			}
			}else
			{
				thehtml = thehtml + "<option selected>1</option>";
			}
			thehtml = thehtml + "</select>" + "</td>" + "<td>";
                        if (sellinfo == "")
                            thehtml = thehtml +  "<div><a href=\"javascript:void(0)\" onclick=\"movetowishlist('" + curr.data.isbn13 + "')\"><img src=\"http://www.popabook.com/movearrow.jpg\" alt=\"Move to Wish List\" title=\"Move to Wish List\"></a>" + "</div><br>";
                        thehtml = thehtml  + "<div><a href=\"javascript:void(0)\" onclick=\"removefromcart('" + curr.data.isbn13 + "')\"><img src=\"http://www.popabook.com/delete.jpg\" title=\"DELETE\"></a>" + "</div> </td>" + "</tr>"
	    	curr = curr.next;
	}
		thehtml = thehtml + "</table> <div class=\"pabborder\"></div>";
		if (pabtotal > 100)
		{
			if (shipcost == 0)
				thehtml = thehtml + "<br>&nbsp; Plus FREE Shipping";
			else 
				thehtml = thehtml + "<br> &nbsp;<span class=\"atp\">Shipping Cost Rs. </span>" + shipcost;
		}
		else 
		{
			if (pabtotal == 0)
				thehtml = thehtml + "<br> &nbsp;<span class=\"atp\">Shipping Cost Rs. </span>" + shipcost;
			else 
			{
				shipcost = shipcost + 30;
				thehtml = thehtml + "<br> &nbsp;<span class=\"atp\">Shipping Cost Rs. </span>" + shipcost;
			}
		}
                tlp = tlp + shipcost; total = total + shipcost;
    var usave = tlp - total;
		thehtml = thehtml + "<br><br><div class=\"pabborder\"></div><br>&nbsp;<span class=\"atp\">Total is Rs. <s>" + tlp + "</s>&nbsp;</span>" + total ;
    	var usave = tlp - total;
		if (usave > 0)
		{
			var savep = parseInt((usave * 100)/tlp);
			thehtml = thehtml +  "<br> &nbsp;<span class=\"atp\">You Save Rs. </span>" + usave + "(" +savep + "%)";
		}
	    thehtml = thehtml + "<br><br><div class=\"pabborder\"></div> &nbsp;<div> <a href=\"javascript:void(0)\"><img src=\"http://www.popabook.com/buttonptp.jpg\" onclick=\"getaddress(false)\"></a> </div>";
		thehtml = thehtml + "<br> </div>";
        scartdiv.innerHTML = thehtml;
	}
	else
	{
		var lc = scartdiv.lastChild;
		while (lc != null)
		{
			scartdiv.removeChild(lc);
			lc = scartdiv.lastChild;
		}
		scartdiv.style.lineHeight = '0';
		if (wishlist.head != null)
			showwishlist();
	}

}

function showwishlist()
{
    var scartdiv = document.getElementById("rightsidecart");
		scartdiv.style.lineHeight = 'normal';
    curr = wishlist.head;
    var items = 0;
    var thehtml = "";
    while (curr != null)
    {
		items = items + 1;
		curr = curr.next;
    }
    if (items > 0)
    {
    	thehtml = "<div class=\"pabscart\"><br>&nbsp;Wish List" + "&nbsp;<span class=\"atp\">" + "(" + items;
		if (items == 1)
			thehtml = thehtml + " " + "Item" + ")" + "</span>";
		else
			thehtml = thehtml + " " + "Items" + ")" + "</span>";
		if (cart.head != null)
		thehtml = thehtml + "<br><span class=\"satp\" onclick=\"showcart()\"><a href=\"javascript:void(0)\"><br>(View Shopping Cart)<br></a> </span>";
		else
			thehtml = thehtml + "<br>";
		curr = wishlist.head;
    	thehtml = thehtml + "<br><div class=\"pabborder\"></div><table class=\"atp\" width=\"100%\">"; 
    	while (curr != null)
    	{
		var lp = curr.data.listprice * curr.data.quantity;
		var op = curr.data.ourprice * curr.data.quantity;
		isbn = curr.data.isbn13;

				thehtml = thehtml + "<tr>";
			thehtml = thehtml + "<td>" + "<img height=\"100px\" width=\"70px\" src=\"http://www.popabook.com/optimage/" + curr.data.image + "\" title=\"" + curr.data.title + "\"  onmouseover=\"this.style.cursor='pointer';\" onclick=\"getbook('getbook','book/" + isbn + "')\">" + "</td>" + "<td style=\"text-decoration:line-through\" > " + lp + "</td>" + "<td>" + op + "</td><td>";
			if (curr.data.shiptime != "Out Of Stock")
			thehtml = thehtml + "<div onmouseover=\"this.style.cursor='pointer';\" onclick=\"movetocart('" + curr.data.isbn13 + "')\">" + "<img src=\"http://www.popabook.com/movearrow.jpg\"alt=\"Move to Shopping Cart\"  title=\"Move to Shopping Cart\"></div><br>" ;
			thehtml = thehtml + "<div onmouseover=\"this.style.cursor='pointer';\" onclick=\"removefromwishlist('" + curr.data.isbn13 + "')\">" + "<img src=\"http://www.popabook.com/delete.jpg\" title=\"DELETE\">" + "</div> </td>" + "</tr>"
	    	curr = curr.next;
	}
    }
    else
    {
		scartdiv.style.lineHeight = '0';

    }
    scartdiv.innerHTML = thehtml;
}
function shiplocchange(ship)
{
	var shiploc = document.getElementById("shippingcost");
	var sel = parseInt(ship.options[ship.selectedIndex].value);
	if (sel == 25)
		shipto = "bangalore";
	else
		shipto = "restofindia";
	var thehtml = "Shipping Cost Rs. " + sel + "<br>";
	var curr = cart.head;
	var total = 0;
    while (curr != null)
    {
	    total = total + parseInt(curr.data.ourprice) * parseInt(curr.data.quantity);
		curr = curr.next;
	}
	var gt = total + parseInt(sel);
	thehtml = thehtml + "<br><span class=\"boldatp\">Grand Total Rs. " + gt + "</span>";
	shiploc.innerHTML = thehtml;
}
function justremovefromcart(isbn13)
{
   cart.remove(isbn13);
}
function movetocart(isbn13)
{
	var curr = wishlist.head;
	while(curr != null)
	{
		if (curr.data.isbn13 == isbn13)
		{
			var image = curr.data.image; 
			var listprice = curr.data.listprice; 
			var ourprice =  curr.data.ourprice; 
			var title = curr.data.title ; 
			var shiptime =  curr.data.shiptime;
			updatewishlistonserver("removefromwishlist","isbn13=" + isbn13);
			wishlist.remove(isbn13);
			addtocart(image,listprice, ourprice, isbn13, title,shiptime,"");
			break;
		}
		curr = curr.next;
	}
}
function movetowishlist(isbn13)
{
	var curr = cart.head;
	while(curr != null)
	{
		if (curr.data.isbn13 == isbn13)
		{
			var image = curr.data.image; 
			var listprice = curr.data.listprice; 
			var ourprice =  curr.data.ourprice; 
			var title = curr.data.title ; 
			var shiptime =  curr.data.shiptime;
   			updatecartonserver("deletefromcart", isbn13);
			cart.remove(isbn13);
			addtowishlist(isbn13,title, listprice, ourprice, shiptime,image)
			break;
		}
		curr = curr.next;
	}
}
function removefromwishlist(isbn13)
{
	wishlist.remove(isbn13);
	if (wishlist.head == null)
		showcart();
	else
		showwishlist();
	updatewishlistonserver("removefromwishlist","isbn13=" + isbn13);

}

function removefromcart (isbn13)
{
   justremovefromcart(isbn13);
   showcart();
   if (cart.head == null && userat == "getaddress")
	   loadhomedata();

   updatecartonserver("deletefromcart", isbn13);
}
function getorderstring()
{
	var orderstring = "";
	var isbndata = "";
	var curr = cart.head;
	var count = 1;
	var finalshiptime = "0";
	while (curr != null)
	{
		var isbn = "";

		if (parseInt(finalshiptime) < parseInt(curr.data.shiptime))
			finalshiptime = curr.data.shiptime;

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
	orderstring = orderstring + "&shiptime=" + finalshiptime;
	return orderstring;
}
function sellbook(frm)
{
	if (validatesellbook(frm) == false)
		return;
        var input = "";
	var isbn13 = frm.elements[0].value;
	for(var i = 0; i < frm.elements.length ; i++){
            if (i == 0)
		input = frm.elements[i].name + "=" + frm.elements[i].value;
            else
		input = input + "&" + frm.elements[i].name + "=" + frm.elements[i].value;
	}
	showuseraccount("sellbooks", input);
}
function validatesellbook(frm){
	
	var aName = Array();
        aName['isbn13'] = "ISBN of Book";
	aName['dbc']='Describe BooK Condition';
	aName['saleprice']='Sale Price';
	aName['name']='Name';
	aName['address']='Address';
	aName['city']='City';
	aName['pincode']='Postal code';
	aName['telephone']='Telephone';
	aName['email'] = 'Email';
	aName['state']='State';
	aName['country']='Country';
        var isbn13 = frm.elements[0].value;
                if (isbn13 == "")
                {
			alert("Please Enter ISBN of the book");
                        frm.elements[0].focus();
			return false;
                }
                if(!validateNumeric(frm.elements[0].value)){
                                        alert("ISBN should be NUMERIC");
                        frm.elements[0].focus();
                        return false;
                }
	for(var i = 1; i < frm.elements.length ; i++){
		if((frm.elements[i].value.length == 0)){
					alert("Enter the " + aName[frm.elements[i].name]);
				frm.elements[i].focus();
				return false;
			}
			if((frm.elements[i].name == 'pincode'))
			{
			if(!validateNumeric(frm.elements[i].value)){
					alert("Postal code should be NUMERIC");
			frm.elements[i].focus();
			return false;
			}
			}	
			
			if((frm.elements[i].name =='telephone')){
			if(!validateNumeric(frm.elements[i].value)){
					alert("Enter a Valid CONTACT NUMBER");
			frm.elements[i].focus();
			return false;
			}
			}		
    	
    
	
		if((frm.elements[i].name == 'name'))
		{
		
		if(validateNumeric(frm.elements[i].value)){
					alert("Enter your Name");
			frm.elements[i].focus();
			return false;
			}
		}
	}  
	return true;
}
function validate(frm){
	
	var aName = Array();

	aName['ship_name']='Shipping Name';
	aName['ship_address']='Shipping Address';
	aName['ship_city']='Shipping City';
	aName['ship_postal_code']='Shipping Postal code';
	aName['ship_phone']='Shipping Phone';
	aName['email'] = 'Email';
	aName['ship_state']='Shipping State';
	aName['ship_country']='Shipping Country';

	for(var i = 13; i < frm.elements.length ; i++){
		if((frm.elements[i].value.length == 0)){
					alert("Enter the " + aName[frm.elements[i].name]);
				frm.elements[i].focus();
				return false;
			}
			if((frm.elements[i].name == 'ship_postal_code'))
			{
			if(!validateNumeric(frm.elements[i].value)){
					alert("Postal code should be NUMERIC");
			frm.elements[i].focus();
			return false;
			}
			}	
			
			if((frm.elements[i].name =='ship_phone')){
			if(!validateNumeric(frm.elements[i].value)){
					alert("Enter a Valid CONTACT NUMBER");
			frm.elements[i].focus();
			return false;
			}
			}		
    	
    
	
		if((frm.elements[i].name == 'ship_name'))
		{
		
		if(validateNumeric(frm.elements[i].value)){
					alert("Enter your Name");
			frm.elements[i].focus();
			return false;
			}
		}
		
				
		if(frm.elements[i].name=='ship_postal_code'){
			if(!validateNumeric(frm.elements[i].value)){
					alert("Postal code should be NUMERIC");
			frm.elements[i].focus();
			return false;
			}
			}		
    
			
							
		if(frm.elements[i].name == 'email'){
				if(!validateEmail(frm.elements[i].value)){
					alert("Invalid input for " + aName[frm.elements[i].name]);
					frm.elements[i].focus();
					return false;
				}		
			}
			
	}  
	return true;
}

	function validateNumeric(numValue){
		if (!numValue.toString().match(/^[-]?\d*\.?\d*$/)) 
				return false;
		return true;		
	}

function validateEmail(email) {
    //Validating the email field
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	//"
    if (! email.match(re)) {
        return (false);
    }
    return(true);
}


Array.prototype.inArray = function (value)
// Returns true if the passed value is found in the
// array.  Returns false if it is not.
{
    var i;
    for (i=0; i < this.length; i++) {
        // Matches identical (===), not just similar (==).
        if (this[i] === value) {
            return true;
        }
    }
    return false;
};


function confirmorder()
{
	var form = document.getElementById("addressform");
	var textnode = "";
	if (validate(form) == false)
	{
		return;
	}
	else
	{
	    name = form.ship_name.value;
	    address = form.ship_address.value;
	    city = form.ship_city.value;
	    state = form.ship_state.value;
	    pincode = form.ship_postal_code.value;
	    telephone = form.ship_phone.value;
	    email = form.email.value;
	}

	userat = "";
	paymode = ""; 
	var i = 0;
	paymode = "online";
	/*paymode = "cash";*/
	if (paymode == "cash")
	{
		/*form.orderbutton.value = "Processing Order Please Wait"; 
		form.orderbutton.disabled = true; */
		getData2("confirmorder");
		getaddress(true);

	}
	else if (paymode == "online")
	{
		/*form.orderbutton.value = "Processing Order Please Wait";
		form.orderbutton.disabled = true; */
		getData2("confirmorder");
		getaddress(true);
    	   /*document.body.appendChild(form);   
    	   form.submit();*/
	}
}

function getData2(req,reqdata,docpart)
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
             if (req == "confirmorder")
			 {
				 XMLHttpRequestObject.open("POST",testbase + "confirmorder.php");
                 XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		 	var af = document.getElementById("addressform");
                 XMLHttpRequestObject.onreadystatechange = function()
                 {
                     if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                     {
                         process_confirmorderdata_xml_response(XMLHttpRequestObject.responseXML,XMLHttpRequestObject.responseText);
                         delete XMLHttpRequestObject;
                         XMLHttpRequestObject = null;
                     }
                }
		   XMLHttpRequestObject.send("reqtype=" + req + "&" + getorderstring() + "&" +getaddressstring() + "&shipcost=" + shipcost);

			 }
		 }
}
function getaddressstring()
{
	var addressstring = "";
	var af = document.getElementById("addressform");
	addressstring = "address=" + encodeURIComponent(af.ship_address.value) +  "&city=" + af.ship_city.value + "&state=" + af.ship_state.value + "&pincode=" + af.ship_postal_code.value + "&name=" + af.ship_name.value +"&email=" + af.email.value + "&telephone=" +af.ship_phone.value;
		addressstring = addressstring + "&paymode=" + paymode;
	return addressstring;
}

function process_confirmorderdata_xml_response(xmldocument, htmlresponse)
{

  

    	var af = document.getElementById("addressform");	
	var centerdata = document.getElementById("centerdata");
	if (paymode == "cash")
	{
		centerdata.innerHTML = htmlresponse;
		clearrightside();
		return;
	}
	  var xmlresp = xmldocument.documentElement;
    var response = xmlresp.getElementsByTagName("response");
    var username = xmlresp.getElementsByTagName("email")[0].childNodes[0].nodeValue;
    userat="";
    email = username;
    var responsevalue = response[0].getAttribute("its");
	if (responsevalue == "success")
	{
		var curr = cart.head;
		var grandtotal = 0;
		var first = 0;
		var orderid = xmldocument.getElementsByTagName("orderid")[0].childNodes[0].nodeValue;
			if (paymode == "online")
			{
			    var items = 0;
                            var ubshipcost = 0;
                            var nbcost = 0;
			    while (curr != null)
		            {
			          var inttotal = curr.data.quantity * curr.data.ourprice;
			 	  grandtotal = grandtotal + inttotal;
                                  if (curr.data.isbn13.indexOf("#") != -1)
                                  {
                                     ubshipcost = ubshipcost + 30;
                                  }
                                  else
                                  {
                                     nbcost = nbcost + inttotal;
                                  }
				  items = items + 1;
			 	  curr = curr.next;
		            }
			    if (nbcost <= 100)
				    grandtotal = grandtotal + 30;
			    grandtotal = grandtotal + ubshipcost;
			    af.ship_country.value = "IND";
				af.reference_no.value = orderid;
				af.amount.value = grandtotal;
				af.description.value = items + " Books";
				af.name.value = af.ship_name.value;
				af.address.value = af.ship_address.value;
				af.city.value = af.ship_city.value;
				af.state.value = af.ship_state.value;
				af.postal_code.value = af.ship_postal_code.value;
				af.country.value = af.ship_country.value;
				af.phone.value = af.ship_phone.value;
				af.submit();
				return;
			}
		
		var thehtml = "<p class=\"boldatp\">Thank You For Using Our Service <br> <br>Invoice Number : " + orderid + "</p>" + "<table class=\"atp\" width=\"80%\">";
		thehtml = thehtml + "<tr> <td class=\"boldatp\">Title</td> <td class=\"boldatp\">Our Price</td> <td class=\"boldatp\">Quantity</td> <td class=\"boldatp\">Total</td> </tr>";
		while (curr != null)
		{
			var inttotal = curr.data.quantity * curr.data.ourprice;
			thehtml = thehtml + "<tr class=\"atp\"> <td>" + curr.data.title + "<br>ISBN:" + curr.data.isbn13 + "<br><br></td> <td>" + curr.data.ourprice + "</td> <td>" + curr.data.quantity + "</td> <td>" + inttotal + "</td> </tr>" ;
			 	curr = curr.next;
			 	grandtotal = grandtotal + inttotal;
		}
		thehtml = thehtml + "</table>";

		var lowercity = city.toLowerCase();
		if (lowercity == "bangalore" || lowercity == "bengaluru" || lowercity == "banglore")
		{
			thehtml = thehtml + "<p class=\"atp\"> Shipping Cost Rs. 25 </p>";
			grandtotal = grandtotal + 25;
		}
		else
		{
			thehtml = thehtml + "<p class=\"atp\"> Shipping Cost Rs. 50 </p>";
			grandtotal = grandtotal + 50;
		}
		thehtml = thehtml + "<p class=\"boldatp\"> Grand Total Rs. " + grandtotal + "</p>";
		thehtml = thehtml + "<p class=\"atp\">Your Order Will be delivered in 3-5 Days</p>";
		thehtml = thehtml + "<p class=\"iatp\">A copy of Invoice has been sent to your e-mail address</p>";
		thehtml = thehtml + "<p> <span class=\"boldatp\">Shipping Address</span> <br><br><span class=\"atp\">";
		thehtml = thehtml + "Name:" + name + "<br>Address:" +address + "<br>City:" + city + "<br>State:" + state + "<br>Pincode:" + pincode + "<br>India" + "</span></p> <p class=\"atp\"> For shipping/order related issues please email @ <a href=\"mailto:support@popabook.com\">support@popabook.com</a></p>";
		centerdata.innerHTML = thehtml;

		clearrightside();
	}
	else
	{
    		var msg = xmlresp.getElementsByTagName("message")[0].childNodes[0].nodeValue;
                if (msg.indexOf("Out Of Stock;") == 0)
                {
                     var tarr = msg.split(";");
                     var curr = cart.head;
                     while(curr != null)
                     {
                          if(curr.data.isbn13 == tarr[1])
                          {
	   			var crdata = "Book : " + curr.data.title;
                                if (curr.data.seller != "")
                                    crdata = crdata + " sold by " + curr.data.seller;
                                crdata = crdata + " has been purchased by another customer few seconds ago. We have updated the shopping cart accordingly. Please verify and proceed to bill.";
                                
	   			centerdata.innerHTML = "<div class=\"atp\" style=\"padding-left:10px;\"> <br><br><br>" + crdata + "</div>";
                          }
                          curr = curr.next;
                     }
                     removefromcart(tarr[1]);
                }
                else
                {
	   		centerdata.innerHTML = "We are Sorry. We could not process your order. Please try again";
                }
	}
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

		var thehtml = "Hello " + email + "&nbsp;";

	hellouser.innerHTML = thehtml;

	var linlout = document.getElementById("loginlogout");
	linlout.innerHTML = "<span class=\"ublueatp\" onmouseover=\"this.style.cursor='pointer';this.style.textDecoration='none';\" onclick=\"allowlogout()\" onmouseout=\"this.style.textDecoration='underline';\">Logout</span>";

	        if (userat == "getaddress")
	{
		getaddress();
	}
	else if (userat == "myaccount")
	{
		showuseraccount('myaccount');
	}
        else if (userat == "home")
        {
              loadhomedata();
        }
	else
	{
		var cartdiv = document.getElementById("centerdata");
	    cartdiv.innerHTML = userathtml;
	    userathtml = "";
	}
	if (wishitem != null)
	{
		addtowishlist(wishitem.isbn13, wishitem.title, wishitem.listprice, wishitem.ourprice, wishitem.shiptime,wishitem.image, "");
		wishitem = null;
	}if (userat == "getaddress")
	{
		getaddress();
	}
	else if (userat == "myaccount")
	{
		showuseraccount('myaccount');
	}
        else if (userat == "home")
        {
              loadhomedata();
        }
	else
	{
		var cartdiv = document.getElementById("centerdata");
	    cartdiv.innerHTML = userathtml;
	    userathtml = "";
	}
	if (wishitem != null)
	{
		addtowishlist(wishitem.isbn13, wishitem.title, wishitem.listprice, wishitem.ourprice, wishitem.shiptime,wishitem.image, "");
		wishitem = null;
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
function getData4(req,reqdata,docpart)
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
            if (req == "verifypasswordgetdata")
            {
                   XMLHttpRequestObject.open("POST",testbase + "verifypassword.php");
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
                         loading();
			}
			else if (req == "signin")
			{
				XMLHttpRequestObject.open("POST",testbase + "signin.php");
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
                      loading();
			}
			else if (req == "forgotpassword")
		    {

                 XMLHttpRequestObject.open("POST",testbase + "forgotpassword.php");
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

                 XMLHttpRequestObject.open("POST",testbase + "logout.php");
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
			 else if (req == "myaccount" || req == "myaccountorders" || req=="sellbooks" || req=="salesaccount")
			 {
				 XMLHttpRequestObject.open("POST",testbase + req + ".php");
                 XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                 XMLHttpRequestObject.onreadystatechange = function()
                 {
                     if (XMLHttpRequestObject.readyState == 4 &&
                           XMLHttpRequestObject.status == 200)
                     {
                         process_myaccountdata_xml_response(XMLHttpRequestObject.responseXML,XMLHttpRequestObject.responseText);
                         delete XMLHttpRequestObject;
                         XMLHttpRequestObject = null;
                     }
                }
                XMLHttpRequestObject.send("reqtype=" + req + "&" + reqdata);
                loading();
			 }
		 }
}
function allowlogout()
{
		var login = document.getElementById("login");
	   var logindata = document.getElementById("logindata");
		logindata.innerHTML = "";
           	var linlout = document.getElementById("loginlogout");
		linlout.innerHTML = "<span class=\"ublueatp\" onmouseover=\"this.style.cursor='pointer';this.style.textDecoration='none';\" onclick=\"allowlogin(0,'','')\" onmouseout=\"this.style.textDecoration='underline';\">Login</span>";
		clearcenter();
		clearrightside();
		loadhomedata();
		cleardata();
	getData4("logout");
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
		getData4('forgotpassword', "email=" + forgotp.email.value);
	}
}
function process_forgotpassword_xml_response(xml, reqdata)
{
	var centerdata = document.getElementById("centerdata");
	centerdata.innerHTML=xml;
}
function processloginform()
{
	var form = document.getElementById("loginform");
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
			getData4("verifypasswordgetdata",reqdata);
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
			getData4("signin",reqdata);
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
function process_myaccountdata_xml_response(xmldocument, htmlresponse)
{
	loadfile("sell.js");

		var centerdata = document.getElementById("centerdata");
		
		centerdata.innerHTML = htmlresponse;
		return;
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
	var thehtml = "Hello " + email + "&nbsp;";

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
	var paddress = xmlresp.getElementsByTagName("address");
	if (paddress[0].childNodes.length != 0)
		address = decodeURIComponent(paddress[0].childNodes[0].nodeValue);

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
	var wishlist = xmlresp.getElementsByTagName("wishitem");
	var index = 0;
	var isbn13; var title; var image; var listprice; var ourprice; var shiptime;
	for (index = 0; index < wishlist.length; index = index + 1)
	{
		isbn13 = wishlist[index].getElementsByTagName("isbn13")[0].childNodes[0].nodeValue;
		title = wishlist[index].getElementsByTagName("title")[0].childNodes[0].nodeValue;
		image = wishlist[index].getElementsByTagName("image")[0].childNodes[0].nodeValue;
		listprice = wishlist[index].getElementsByTagName("listprice")[0].childNodes[0].nodeValue;
		ourprice = wishlist[index].getElementsByTagName("ourprice")[0].childNodes[0].nodeValue;
		shiptime = wishlist[index].getElementsByTagName("shiptime")[0].childNodes[0].nodeValue;
		title = unescape(title);
		addtowishlistonload(isbn13, title,image,listprice,ourprice,shiptime);
	}
	if (userat == "getaddress")
	{
		getaddress();
	}
	else if (userat == "myaccount")
	{
		showuseraccount('myaccount');
	}
        else if (userat == "home")
        {
              loadhomedata();
        }
	else
	{
		var cartdiv = document.getElementById("centerdata");
	    cartdiv.innerHTML = userathtml;
	    userathtml = "";
	}
	if (wishitem != null)
	{
		addtowishlist(wishitem.isbn13, wishitem.title, wishitem.listprice, wishitem.ourprice, wishitem.shiptime,wishitem.image, "");
		wishitem = null;
	}
	else if (index > 0)
		showcart();

    }
    else
    {
	     var message = xmlresp.getElementsByTagName("message")[0].childNodes[0].nodeValue;
		loginattempts++;

	     /*var textnode = "Attempt:" + loginattempts + " " +"Email and Password could not be matched. Try Again";*/
		var textnode = "Attempt:" + loginattempts + " " + message;
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
function overlink(link)
{
	link.style.backgroundColor='#ffddff';
	link.style.textDecoration='underline';
	link.style.cursor='pointer'; 
}
function outoflink(link)
{
	link.style.backgroundColor='#ffffff';
	link.style.textDecoration='none';

}
function bbsearch(cat)
{
	getData('search', 'search_string=' + cat);
	unbrowse();
	window.scrollTo(0,0);
}
function bbbrowse(cat)
{
	getData('browse', 'browse=' + cat);
	unbrowse();
	window.scrollTo(0,0);
}
function loading()
{
    var height = screen.height;
    var width = screen.width/2  - 250 - 33;
    var thehtml = "<div style=\"text-align:left;position:absolute;top:200px;left:" + width + "px;width:33px;height:33px;\"><img src=\"http://www.popabook.com/loading.gif\"></div>";
    var ldata = document.getElementById("loginformerror");
    if (ldata != null)
    {
     ldata.innerHTML = thehtml;
    }
    else
    {
    var cdata = document.getElementById("centerdata");
     cdata.innerHTML = cdata.innerHTML + thehtml;
    }
	window.scrollTo(0,0);
}
function loadhomedata() {

        userat = "home";
        getData("home");
        loading();
}
function updatesalesaccount(formid)
{
		 var saform = document.getElementById(formid);
		 var inputs = "";
		 if (saform.courier.value != "" && saform.trackingid.value != "")
		 {
		 	inputs = inputs + "isbn13=" + formid + "&courier=" + encodeURIComponent(saform.courier.value) + "&trackingid=" + encodeURIComponent(saform.trackingid.value) ;
		 	showuseraccount('salesaccount',inputs);
		 }
}


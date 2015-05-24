<html>
<head>
<script language = "javascript">
function process_xml_response()
{
	var center = document.getElementById("center");
	center.innerHTML= "<div id=\"centerdata\"> <hr width=\"90%\" align=\"left\"></hr><table width=\"90%\"><tr><td align=\"left\"> <img src=\"9780593054277.jpg\"></td> </tr> </table> </div>";


}
</script>
<style type="text/css">

body {
	font-family: sans-serif;
}


#maincenter {
position: absolute;
top:10%;
left: 20%;
width: 60%;
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
#leftside {
text-align: left;
position: absolute;
top:10%;
width:15%;
float:left;
}

#rightside {
position: absolute;
top:10%
left: 80%;
width:18%;
padding-left:1px;
padding-right:1px;
float:right;

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


   float:right;
}

</style>
</head>

<body width="100%">
<table width="100%">
<tr>
<td class="logoatp">
<table width="20%">
<tr> <td>
PopAbooK.com
</td></tr>
</table>
</td>
<td> &nbsp; </td>
<td class="atp" align="left">

<div id="login" class="atp">
<div id="logindata">
Hello <span id="loginlogout" class="ublueatp" onmouseover="this.style.cursor='pointer';this.style.textDecoration='none';" onclick="allowlogin(0,'','')" onmouseout="this.style.textDecoration='underline';">Login</span>
</div>
</div>
</td>
<td align="left">
<span class="ublueatp" onmouseover="this.style.cursor='pointer'; this.style.textDecoration ='none';" onclick="myaccount()" onmouseout="this.style.textDecoration ='underline'">My Account</span> | <span class="ublueatp" onmouseover="this.style.cursor='pointer'; this.style.textDecoration ='none';" onclick="myaccount()" onmouseout="this.style.textDecoration ='underline'"> Help </span>
</td>

</tr>
</table>

<br>
<br>
<div style="width:100%;">

<div style="float:left; width:15%;"id="leftside" align="right" class="atp">

<p class="atp"> Whats New </p>
</div>
<div id="maincenter">
<div align="center">
<form name="searchform">
<input type="text" size="50" value="" name="search_string"/>
<input type="button" id="searchbutton" value="search" onclick="process_xml_response()" />
</form> 
</div>
<div id="center">
</div>
</div>
<div style="float:right;width:20%" id="rightside" align="left" class="atp">
H
<div id="cartitems" style="font-weight:bold">

</div>

<div id="carttotal">
</div>

<div id="confirmorder">
</div>
<div id="realcart" style="height:50%;overflow:auto;">


</div> 
      
</div>
</body>

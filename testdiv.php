<html>
<head>
<title> Welcome to voco
</title>
<script language = "javascript">

function addtocart(newbook)
{
    /*var newtr = document.createElement("tr");
    var newtd = document.createElement("td");
    var newimg = document.createElement("img");
    newimg.setAttribute("src", '9780141012117.jpg');
    newtr.appendChild(newtd);
    newtd.appendChild(newimg);
    var carttable = document.getElementById("addtocarttable");
    carttable.appendChild(newtr);*/
   var cartdiv = document.getElementById("cart");
var newimg = document.createElement("img");
    newimg.setAttribute("src", newbook);
   
/*   var newimg = document.createElement("img");
    newimg.setAttribute("src", '9780141012117.jpg');
    */

   cartdiv.appendChild(newimg);    
   
}
</script>
<style type="text/css">

body {
	font-family: sans-serif;
}
#allcontent {
position: absolute;
width:100%;
margin-left: auto;
margin-right: auto;
padding-top: 20px;
padding-bottom: 20px;

}

#searchbox {
position: absolute;
top: 0px;
left: 0px;
width: 100%;

}

#searchresults {
position: absolute;
top: 40px;
left: 20%;
width: 60%;
text-align: left;
border-left-color: black;
border-left-width: 1px;
border-left-style: ridge;
padding-left: 5px;
}
#whatsnew {
position: absolute;
top: 40px;
left: 0px;
width: 20%;
}
#cart {
position: absolute;
top: 40px;
left: 80%;
width: 20%;
border-left-color: black;
border-left-width: 1px;
border-left-style: ridge;
padding-left: 5px;
}
</style>
</head>
<center>
<body>


<div id="whatsnew">
<p> <a href=""> Whats New</a> </p>
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz </p>
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz </p>
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz </p><p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz </p>
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz </p>
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz </p>
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz </p>
</div>

<div id="searchresults">
<p> Search Results</p>
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz </p><p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz </p>
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz <p>
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz <p>
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz <p>
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz <p>
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz <p>
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz <p>
</div>

<div id="cart">
<p> cart </p>
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz </p>
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz </p>
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz </p> 
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz </p>      
<p> abcdefghijklmnopqrstuvwxyz abcdefghijklmnopqrstuvwxyz </p>

</div>










</body>
</center>

</html>
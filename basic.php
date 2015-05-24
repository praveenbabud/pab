<html>
<head>
<title> Welcome to voco
</title>
<script language = "javascript">

       function getdata()
       {
           document.write("onclick works");
       }
       function getone()
       {
           var xyz = document.getElementById("google4");
           xyz.style.cursor = 'pointer';
           xyz.style.border = "1px solid #666666";
       }
</script>
<style type="text/css">

body {
	font-family: sans-serif;
}

.pagination {
   width:3%;
   height:3%;
   border:solid 2px #DDDDDD;
   font-family: sans-serif;
   background-color:#ff0000;
   float:left;
}
.pgshow {
     width:3%;
   height:3%;
   border: solid 1px #DDDDDD;
   font-family: sans-serif;
   margin-right:2px;


   float:left;
}

ul{border:0; margin:0; padding:0;}

#pagination-flickr li{
border:0; margin:0; padding:0;
font-size:11px;
list-style:none;
}
#pagination-flickr a{
border:solid 1px #DDDDDD;
margin-right:2px;
}
#pagination-flickr .previous-off,
#pagination-flickr .next-off {
color:#666666;
display:block;
float:left;
font-weight:bold;
padding:3px 4px;
}
#pagination-flickr .next a,
#pagination-flickr .previous a {
font-weight:bold;
border:solid 1px #FFFFFF;
}
#pagination-flickr .active{
color:#ff0084;
font-weight:bold;
display:block;
float:left;
padding:4px 6px;
}
#pagination-flickr a:link,
#pagination-flickr a:visited {
color:#0063e3;
display:block;
float:left;
padding:3px 6px;
text-decoration:none;
}
#pagination-flickr a:hover{
border:solid 1px #666666;
}

</style>
</head>
<center>
<body>

<div id="google" class="pgshow" onmouseover="this.style.cursor='pointer'" onclick="getdata()">
1
</div>
<div id="google1" class="pgshow" onmouseover="this.style.cursor='pointer'" >
2
</div>
<div id="google2" class="pgshow" onmouseover="this.style.cursor='pointer'">
3
</div>
<div id="google4" class="pgshow" onmouseover="this.style.border='1px solid #666666';this.style.cursor='pointer' " onmouseout="this.style.border='1px solid #dddddd'">
4
</div>

  <ul id="pagination-flickr">
<li class="previous-off">«Previous</li>
<li class="active">1</li>
<li><a>2</a></li>
<li><a href="?page=3">3</a></li>
<li><a href="?page=4">4</a></li>
<li><a href="?page=5">5</a></li>
<li><a href="?page=6">6</a></li>
<li><a href="?page=7">7</a></li>
<li class="next"><a href="?page=2">Next »</a></li>
</ul>

</body>
</center>

</html>

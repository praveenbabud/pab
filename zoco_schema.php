<html>
<head>
</head>
<body>


<?php

if ($_POST['isbn10'] != "")
{
	$link = mysqli_connect("localhost", "root", "","zoco");
	$isbn10 = $_POST['isbn10'];
	$isbn13 = $_POST['isbn13'];
	$title = $_POST['title'];
	$author1 = $_POST['author1'];
	$author2 = $_POST['author2'];
	$author3 = $_POST['author3'];
	$author4 = $_POST['author4'];
	$listprice = $_POST['listprice'];
	$discount = $_POST['discount'];
	$quantity = $_POST['quantity'];
	$publisher = $_POST['publisher'];
	$edition = $_POST['edition'];
	$yearofprint = $_POST['yearofprint'];
	$blurb = $_POST['blurb'];
	$db_query = "insert into book_inventory (isbn_10, isbn_13, title, author_1, author_2,author_3,author_4, list_price, discount, quantity, publisher, edition, year_of_printing, blurb) values ('$isbn10', '$isbn13', '$title', '$author1', '$author2', '$author3', '$author4', '$listprice', '$discount', '$quantity', '$publisher', '$edition', '$yearofprint', '$blurb')";
        $db_result = mysqli_query($link, $db_query);
	if ($db_result == TRUE)
	{
	    echo "Insert Successfull";
	    echo "<br>";
	    echo "isbn10 = " . $isbn10;
	    echo "<br>";
	    echo "isbn13 = " . $isbn13;
	    echo "<br>";
	    echo "title = " . $title;
	    echo "<br>";
	    echo "author1 = " . $author1;
	    echo "<br>";
	    echo "author2 = " . $author2;
	    echo "<br>";
	    echo "author3 = " . $author3;
	    echo "<br>";
	    echo "author4 = " . $author4;
	    echo "<br>";
	    echo "listprice = " . $listprice;
	    echo "<br>";
	    echo "discount = " . $discount;
	    echo "<br>";
            echo "quantity = " . $quantity;
	    echo "<br>";
	    echo "publisher = " . $publisher;
	    echo "<br>";
	    echo "edition = " . $edition;
	    echo "<br>";
	    echo "yearofprint = " . $yearofprint;
	    echo "<br>";
	    echo "blurb = " . $blurb;
	    echo "<br>";
	}
}



?>

<form action="input.php" method="POST">
<p> isbn 10 <input type="text" name="isbn10"> </p>
<p> isbn 13 <input type="text" name="isbn13"> </p>

<p> title  <input type="text" name="title"> </p>
<p> author1 <input type="text" name="author1"> </p>
<p> author2 <input type="text" name="author2"> </p>
<p> author3 <input type="text" name="author3"> </p>
<p> author4 <input type="text" name="author4"> </p>
<p> listprice <input type="text" name="listprice"> </p>
<p> discount<input type="text" name="discount"> </p>
<p> quantity<input type="text" name="quantity"> </p>
<p> publisher <input type="text" name="publisher"> </p>
<p> edition <input type="text" name="edition"> </p>
<p> yearofprint <input type="text" name="yearofprint"> </p>

<p> blurb <input type="text" name="blurb"> </p>
<input type="submit" name="submit" value="submit">
</form>
</body>
</html>

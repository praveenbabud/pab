<?php
header("Content-type: text/xml");
echo "<?xml version=\"1.0\" ?";
echo ">";

$titles = array("The Augmentative Indian", "Poverty and Games","Amarthya Sen", "Dan Brown Lost Symbol", "The Idea of Justice", "Fooled By Randomness");

$authors = array("Praveen", "Praveen", "Praveen", "Praveen", "Praveen", "Praveen");

$isbn13 = array("9780141012117", "9780195649543", "9780195665284", "9780593054277", "9780195651102", "9780141031484");

$images = array("9780141012117.jpg", "9780195649543.jpg", "9780195665284.jpg", "9780593054277.jpg", "9780195651102.jpg", "9780141031484.jpg");

$listprice = array("500","500","500","500","500","500");

$ourprice = array("400","400","400","400","400","400");

$count = count($titles);

echo "<data>";
echo "<books>";

for ($i = 0; $i < $count ; $i= $i + 1)
{
		echo "<sbook>";
		echo "<title>" . $titles[$i] . "</title>";
		echo "<author>" . $authors[$i] . "</author>";
		echo "<isbn13>" . $isbn13[$i] . "</isbn13>";
		echo "<image>" . $images[$i] . "</image>";
		echo "<listprice>" . $listprice[$i] . "</listprice>";
		echo "<ourprice>" . $ourprice[$i] . "</ourprice>";
		echo "</sbook>";
}
echo "</books>";
echo "<pagination><spage>NEXT &gt;&gt;</spage><spage>5</spage><spage>4</spage><spage current=\"yes\">3</spage><spage>2</spage><spage>1</spage><spage>&lt;&lt; PREVIOUS</spage></pagination>";
echo "</data>";
?>

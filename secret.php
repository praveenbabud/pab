<?php
header("Content-type: text/xml");
echo "<?xml version=\"1.0\" ?";
echo ">";
echo "<data>";
echo "<books>";
	echo "<sbook>";
		echo "<title>";
			echo "The Augmentative Indian";
		echo "</title>";
		echo "<author>";
			echo "Praveen";
		echo "</author>";
		echo "<isbn>";
			echo "9780141012117";
			echo "9780141012117";
		echo "</isbn>";
echo "<image>9780141012117.jpg</image><listprice>500</listprice><discount>20</discount><ourprice>400</ourprice></sbook><sbook><title>Poverty and Games</title><author>Praveen</author><isbn>9780195649543</isbn><image>9780195649543.jpg</image><listprice>500</listprice><discount>20</discount><ourprice>400</ourprice></sbook><sbook><title>Amarthya Sen</title><author>Praveen</author><isbn>9780195665284</isbn><image>9780195665284.jpg</image><listprice>500</listprice><discount>20</discount><ourprice>400</ourprice></sbook><sbook><title>Dan BROWN lost symbol</title><author>Praveen</author><isbn>9780593054277</isbn><image>9780593054277.jpg</image><listprice>500</listprice><discount>20</discount><ourprice>400</ourprice></sbook><sbook><title>The Idea of Justice</title><author>Praveen</author><isbn>9780195651102</isbn><image>9780195651102.jpg</image><listprice>500</listprice><discount>20</discount><ourprice>400</ourprice></sbook>";
echo "<sbook><title>Fooled by Randomness</title><author>Praveen</author><isbn>9780141031484</isbn><image>9780141031484.jpg</image><listprice>500</listprice><discount>20</discount><ourprice>400</ourprice></sbook></books>";

echo "<pagination><spage>NEXT &gt;&gt;</spage><spage>5</spage><spage>4</spage><spage current=\"yes\">3</spage><spage>2</spage><spage>1</spage><spage>&lt;&lt; PREVIOUS</spage></pagination>";
echo "</data>";
?>

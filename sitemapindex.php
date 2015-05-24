<?php
header("Content-type: text/xml");
                $keylines = file("sitemapindex.xml");
                foreach($keylines as $line)
                {
                        echo $line;
                }

?>

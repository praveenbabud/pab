<?php
set_time_limit(0);
header("Content-type: text/xml");
header("Content-Encoding: gzip");
                $keylines = file("newreleases.xml.gz");
                foreach($keylines as $line)
                {
                        echo $line;
                }

?>

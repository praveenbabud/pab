<?php

$isbn13 = array();
$isbnnoimage = array();
$keys = array();
                   $keyslines = "";
$link = mysqli_connect('localhost', 'root', 'zulu1PopAbooK', 'zoco');
$keylines = file("pabstores.data");
foreach($keylines as $line)
{
                $escape = "\r\n=";
                $param = strtok ($line, $escape);
                $param = trim($param);

                if ($param == "###")
                {
                     $books =  array();
                     echo "url = $url \n";
                     echo "title = $title\n";
                     echo "keys = $keyslines\n";
                     $books = searchbooksforpabstore($link, $keys);
                     $keys = array();
                     nowcpabstore($link, $url, $title, $books);
                     $keyslines = "";
                }
                $value = strtok($escape);
                $value = trim($value);
                if ($param == "url")
                     $url = $value;
                if ($param == "title")
                     $title = $value;
                if ($param == "key")
                {
                     $keyid = count($keys);
                     $keys[$keyid] = $value;
                     $keyslines = $keyslines . " " . $value;
                }
                 
}

function searchbooksforpabstore($link, $searchkeys)
{
        $escape = ", ;:.\/\"'\n\r-()!*?&\t";
        $books = array();
        $ranks = array();
        $finalbooks = array();
   for ($nk = 0 ; $nk < count($searchkeys); $nk = $nk + 1)
   {
        $newtbr = array();
        $kctbr = array();
        $tok = strtok($searchkeys[$nk], $escape);
        $numtok = 0;
        while ($tok != null)
        {
                if ($tok == "s")
                {
                $tok = strtok($escape);
                        continue;
                }
                $alttok = "";
                $toklen = strlen($tok);
                if (strrpos($tok,"s") == ($toklen - 1))
                {
                        for ($istr = 0 ; $istr < ($toklen - 1); $istr = $istr + 1)
                        {
                                $alttok = $alttok . $tok[$istr];
                        }
                }
                else
                {
                        $alttok = $tok . "s";
                }
                $db_query = "select keywordid from keywords where keyword like '$tok' or keyword like '$alttok'";
                $db_result = mysqli_query($link, $db_query);
                if ($db_result == TRUE)
                {
                        $num_rows = mysqli_num_rows($db_result);
                        if ($num_rows > 0)
                        {
                                $keywordid1 = 0;
                                $keywordid2 = 0;
                                if ($num_rows == 2)
                                {
                                        $db_row = mysqli_fetch_row($db_result);
                                        $keywordid1 = $db_row[0];
                                        $db_row = mysqli_fetch_row($db_result);
                                        $keywordid2 = $db_row[0];
                                        $db_query = "select bookid, rank from keyword_book_map where keywordid = $keywordid1 or keywordid = $keywordid2";
                                }
                                else
                                {
                                        $db_row = mysqli_fetch_row($db_result);
                                        $keywordid1 = $db_row[0];
                                        $db_query = "select bookid, rank from keyword_book_map where keywordid = $keywordid1";
                                }
                                $tmparraybooks = array();

                                $db_result = mysqli_query($link, $db_query);
                                if ($db_result == TRUE)
                                {
                                        $num_rows = mysqli_num_rows($db_result);
                                        if ($num_rows > 0)
                                        {

                                                $trows = 0;
                                                while($trows != $num_rows)
                                                {
                                                        $dbrow = mysqli_fetch_row($db_result);
                                                        if(isset($newtbr[$dbrow[0]]) == TRUE)
                                                        {
                                                                $newtbr[$dbrow[0]] = $newtbr[$dbrow[0]] + $dbrow[1];
                                                        }
                                                        else
                                                        {
                                                                $newtbr[$dbrow[0]] = $dbrow[1];
                                                        }
                                                         if (isset($tmparraybooks[$dbrow[0]]) == FALSE)
                                                        {
                                                              $tmparraybooks[$dbrow[0]] = 1;
                                                              if (isset($kctbr[$dbrow[0]]) == TRUE)
                                                               $kctbr[$dbrow[0]] = $kctbr[$dbrow[0]] + 1;
                                                              else
                                                                $kctbr[$dbrow[0]] = 1;
                                                        }

                                                        $trows = $trows + 1;
                                                }
                                        }
                                }
                        }
                        $numtok = $numtok + 1;
                }
        $tok = strtok($escape);
        }
        foreach ($newtbr as $key => $value)
        {
             if ($kctbr[$key] == $numtok)
             {
                if (isset($finalbooks[$key]) == TRUE)
                     $finalbooks[$key] = $finalbooks[$key] + $value;
                else
                     $finalbooks[$key] = $value;
             }
        }
        $newtbr = array();
        $kctbr = array();
     }
     arsort($finalbooks);
     $books = array();
     foreach ($finalbooks as $key => $value)
     {
              $bk = count($books);
              $books[$bk] = $key;
     }
        return $books;
}

function nowcpabstore($link, $url, $title, $books)
{

   $urlname = $title;
   $url = strtolower($url);
   $bkcount = count($books);
   $altbooks = array();
   $altotherbooks = array();
   $bkcount = $bkcount - 1;
   for ($ij = 0; $ij <= $bkcount;  $ij = $ij + 1)
   {
          $db_query = "select quantity from inventory where bookid=$books[$ij]";
          $db_result = mysqli_query($link, $db_query);
          if ($db_result == TRUE)
          {
              $num_rows = mysqli_num_rows($db_result);
              if ($num_rows >  0)
              {
                 $altbookscount = count($altbooks);
                 $altbooks[$altbookscount] = $books[$ij];
              }
              else
              {
                 $altotherbookscount = count($altotherbooks);
                 $altotherbooks[$altotherbookscount] = $books[$ij];
              }
          }
              else
              {
                 $altotherbookscount = count($altotherbooks);
                 $altotherbooks[$altotherbookscount] = $books[$ij];
              }
   }
   $books = array_merge($altbooks, $altotherbooks);
   $db_query = "insert into affiliates (email, linkurl, storeurl, storename) values('$url', '$url', '$url', '$urlname')";
   $db_result = mysqli_query($link, $db_query);
   if ($db_result == TRUE)
   {
      $db_query = "select affid from affiliates where email like '$url'";
      $db_result = mysqli_query($link, $db_query);
      if ($db_result == TRUE)
      {
          $db_row = mysqli_fetch_row($db_result);
          if ($db_row != NULL)
          {
              $affid = $db_row[0];
              $from = count($books);
              if ($from > 100)
                     $from = 99;
              else
                     $from = $from - 1;
              for ($i = $from ; $i >= 0; $i = $i - 1)
                       addbooktostore($link, $affid, $books[$i]); 
          }
      } 
   }
}
function addbooktostore($link, $affid, $bookid)
{
    echo "Trying to add $bookid to $affid \n";
    $db_query = "select isbn13 from book_inventory where bookid=$bookid";
    $db_result = mysqli_query($link, $db_query);
    if ($db_result == TRUE)
    {
        $db_row = mysqli_fetch_row($db_result);
        if ($db_row != NULL)
        {
             $isbn13 = $db_row[0];
             $db_query = "insert into booksinstore (affid, bookid, isbn13,dt) values ($affid, $bookid, '$isbn13', now())";
             $db_result = mysqli_query($link, $db_query);
             if ($db_result == TRUE)
               echo "Added $isbn13 to $affid \n";
        }
    }
}

?>

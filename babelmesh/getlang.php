<?php
    parse_str($_SERVER["QUERY_STRING"], $query);
    if (is_array($query)) {
      extract($query);
    }
	
	  require "../include/header.php";

    $db_name = 'crosslan';
    $db_object = db_connect($db_name);
	  $table = $lang."_mesh";
		$lang = strtoupper($lang);
		$lang_col = $lang."_DESCRIPTOR";
		$eng_col = "US_DESCRIPTOR";
		
		$qu = isset($qu)? trim(urldecode($qu)):'';
		$qu = mb_strtoupper($qu);
		$quO = $qu;
		if (($lang == 'ARA') || ($lang == 'CHN') || ($lang == 'KOR') || ($lang == 'JPN') || ($lang == 'RUS') ){
				$qu = addslashes(mb_convert_encoding($qu,"UTF-8", "CP1252"));
		}
		$query = "select ".$lang_col." as a from ".$table." where ".$lang_col." like '$qu%' group by a order by a limit 10";
		//$result = mysqli_query($db_object, $query) or die("Query failed : " . mysqli_error($db_object));// 
		$result = mysqli_query($db_object, $query); 
    if (!$result) die ("Language '".urlencode($lang)."' not supported.");

		$make = "sendRPCDone(frameElement, \"".urlencode($quO)."\", new Array(";
		$make2 = "";
    $i = 1;
		while ($line= mysqli_fetch_array($result)) {
			if ($i>1) {
		    $make .= ", ";
				$make2 .= ", ";
			}
			if ((isset($newflag) && $newflag == 1) && (mysqli_affected_rows($db_object) != 0)) {
				$make .= "\"".$oldqu.mb_convert_encoding($line["a"], "CP1252" , "UTF-8")."\"";
			}
			else {
			 $make .= "\"".mb_convert_encoding($line["a"], "CP1252" , "UTF-8")."\"";
			}
			$make2 .="\"\"";
			$i++; 
		}
		$make .= "), new Array(".$make2."), new Array(\"\"));";
		echo $make;
?>

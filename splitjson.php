<?php

	echo "\n###### JSON SPLITTER ######\n";

	if (!isset($argv[1]) || !isset($argv[2])) {
		if (!isset($argv[1])) {
			echo "Err: Provide number of fragments as a first argument!\n";
		}
		if (!isset($argv[2])) {
			echo "Err: Provide PATH as a second argument!\n";
		}
		exit();
	}

	$numOfFragments = $argv[1];
	$path = $argv[2];

	$json = file_get_contents($path);
	if ($json == false) exit();
	$json = json_decode($json, true);

	$remainder = sizeof($json) % $numOfFragments;
	$iteratorStopPoint = (int)(sizeof($json) / $numOfFragments);

	// JSON goes here
	$splits = array();

	for ($i = 0; $i < $numOfFragments; $i++) {
		$jsonFragment = array();
		$newIteratorStopPoint = $iteratorStopPoint * $i + $iteratorStopPoint;
		$zStart = $iteratorStopPoint * $i;
		if ($i == $numOfFragments-1) {
			$newIteratorStopPoint += $remainder;
		}
		for ($z = $zStart; $z < $newIteratorStopPoint; $z++) {
			$jsonFragment[] = $json[$z];
		}
		$splits[$i] = $jsonFragment;
	}

	if (sizeof($splits) > 0) {
		$i = 1;
		$dir = "json_files";
		if (!file_exists($dir)) {
			mkdir($dir);
		}
		foreach ($splits as $split) {
			$file = fopen("json_files/json".$i.".json", "w");
			$jsonRaw = json_encode($split);
			fwrite($file, $jsonRaw);
			fclose($file);
			$i++;
		}
	}

	echo "DONE!";

?>

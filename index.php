<?
$ourFile = 'acess_log';

if(!file_exists($ourFile))exit("Файл не найден"); 

$readFileByLine = fopen($ourFile, 'r');

while (!feof($readFileByLine)) {
	$readFileByLineExplode = fgets($readFileByLine) . '<br>';
	/*кол-во строк*/
	$countStr[] = $readFileByLineExplode;

	/*находим url*/
	preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $readFileByLineExplode, $matchUrl);
	/*выводим все url*/
	$dataUrl[] = $matchUrl[0][0];

	/*находим статусы*/
	preg_match('#\s[0-9]{3}\s#', $readFileByLineExplode, $matchStatusCode);
	$datamatchStatusCode[] = $matchStatusCode;

}

/*Кол-во строк*/
function countStr($arr_str) {
	return count($arr_str);
}

/*Кол-во уникальных url*/
function countUrl_unique($arr_url) {
	$resultUniqueUrl = array_unique($arr_url);
	return count($resultUniqueUrl);
}

/*Какие статусы и сколько*/
function countStatusUnique($datamatchStatusCode) {
	/*статусы в интежер*/
	foreach ($datamatchStatusCode as $key => $value) {
		$valueStatus = (int) $value[0];
		$valueStatusArray[] = $valueStatus;
	}

	/*уникальные статусы*/
	$resultUniqueStatus = array_unique($valueStatusArray);

	$valueStatusString = implode(" ", $valueStatusArray);

	/*формируем данные статус : кол-во*/
	foreach ($resultUniqueStatus as $key => $value) {
		$countStatusString = mb_substr_count($valueStatusString, $value);
		$resultUniqueStatus = $value . " : " . $countStatusString;
		$resultUniqueStatusArray[] = $resultUniqueStatus;
	}

	$resultUniqueStatusString = implode(", ", $resultUniqueStatusArray);
	return $resultUniqueStatusString;

}


$arrOutput = ['views' => countStr($countStr), 'urls' => countUrl_unique($dataUrl), 'statusCodes' => countStatusUnique($datamatchStatusCode)];

$json = json_encode($arrOutput);

var_dump($json);

fclose($readFileByLine);
?>
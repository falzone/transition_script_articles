<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Transition</title>
</head>
<body>

<?php

$pdo = new PDO("mysql:host=localhost;dbname=citynet_articles", "root", "root", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
// var_dump(get_class_methods($pdo));

$sql = "INSERT INTO articles SET name=?, author=?, source=?, day=?, month=?, year=?, content=?";
$statement = $pdo->prepare($sql);

$content = file_get_contents('articles.html');

$DOM = new DOMDocument();
$DOM->loadHTML($content);

$fieldset = $DOM->getElementsByTagName('fieldset');

// echo '<pre>';
// print_r($fieldset);
// echo  '</pre>';

foreach ($fieldset as $item_id => $item_qty){

	$fieldsetTrimmed = trim($fieldset->item($item_id)->nodeValue);
	$fieldsetTrimmed = explode(PHP_EOL, $fieldsetTrimmed);

	//Zaglavie
	$title = $fieldsetTrimmed[0];

	// Avtor - izchistvane
	$author_keywordRemoved = str_replace("публикувал:", "", $fieldsetTrimmed[1]);
	$author = trim($author_keywordRemoved);

	// Iztochnik - izchistvane
	$garbageRemoved = str_replace("Посети сайта", "", $fieldsetTrimmed[2]);
	$source_keywordRemoved = str_replace("източник:", "", $garbageRemoved);

	$source = trim($source_keywordRemoved);

	//Date - oformqne
	preg_match_all('/[0-9]+/', $fieldsetTrimmed[4], $digits);
	$strings = preg_replace('/[^\p{Cyrillic}]/u', '', $fieldsetTrimmed[4]);
	$day = $digits[0][0];
	// $day = htmlentities($day, ENT_COMPAT, 'utf-8');
	$month = $strings;
	$year = $digits[0][1];
	// $year = htmlentities($year, ENT_COMPAT, 'utf-8');


	//Article - sgystqvane
	$articleContainer = "";
	for ($i = 7; $i < sizeof($fieldsetTrimmed); $i++) {
		if ($fieldsetTrimmed[$i] !== '') {
			$articleContainer .= "<p>" . trim($fieldsetTrimmed[$i]) . "</p>";
		}
	}
	$article = $articleContainer;

	// echo $month . "<br />";

	$data = array($title, $author, $source, $day, $month, $year, $article);
	$result = $statement->execute($data);
}

if($result){
	echo "Successful";
} else{
	echo "Error";
}

die();

?>

</body>
</html>
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

// set the PDO error mode to exception
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT content FROM articles WHERE id=?";
// $sql2 = "UPDATE articles SET content=? WHERE id=?";

// Prepare statement
$statement = $pdo->prepare($sql);



// echo '<pre>';
// print_r($result['content']);
// echo  '</pre>';
for ($i=2; $i < 53; $i++) { 
	$statement->execute([$i]);
	$result = $statement->fetch();

	$replace_p_tags = preg_replace("/<\/p><p>/is", "<br /><br />", $result['content']);
	$remove_opened_p_tags = preg_replace("/<p>/", "", $replace_p_tags);
	$remove_closed_p_tags = preg_replace("/<\/p>/", "", $remove_opened_p_tags);
	// echo $remove_closed_p_tags;

	// $sql2 = "UPDATE articles SET content = ? WHERE id = ?";
	$update = $pdo->prepare($sql2)->execute([$remove_closed_p_tags, $i]);
}

// foreach ($fieldset as $field){

// 	$data = array($title, $author, $source, $day, $month, $year, $article);

// 	// execute the query
// 	$result = $statement->execute($data);
// }

if($update){
	echo "Successful";
} else{
	echo "Error";
}

die();

?>

</body>
</html>
<?php
/*try{
	$connect = new PDO("mysql:host=localhost;dbname=".'socialmedia', 'root', '');
}
catch(PDOException $e) {
	echo "Error: ".$e->getMessage();
}

if (isset($_POST['query'])) {
	$request = trim(mysql_real_escape_string($_GET['query']));
	if($request != "") {
		$request = "%".$request."%";
		$data = array();
		$stmt = $connect->prepare("SELECT * FROM wallposts WHERE post_content LIKE ?");
		$stmt->bindValue(1, $request, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$data[] = stripslashes(strip_tags($row['post_content']));
		}
	}
	echo json_encode($data);
	exit();
}*/

$places = array("Rustenburg CBD", 
				"Rustenburg West", 
				"Rustenburg East",
				"Rustenburg North",
				"Rustenburg Squire", 
				"Waterfall Mall",
				"Platinum Squire Mall",
				"Tlhabane", 
				"Tlhabane West", 
				"Geelhout Park", 
				"Geelhout Park Ext. 1",
				"Geelhout Park Ext. 2", 
				"Phokeng",
				"Luka",
				"Sun City",
				"Pretoria CBD",
				"Pretoria East",
				"Pretoria West",
				"Pretoria North",
				"Centurion",
				"Midrand",
				"Johannesburg CBD",
				"Johannesburg East",
				"Johannesburg West",
				"Johannesburg North",
				"Johannesburg South",
				"KFC Kenturky",
				"Chicken Licken",
				"Nando's",
				"MacDonald's",
				"Wimpy Restuarant",
				"Fish &amp; Chips",
				"Burger King",
				"Yankees Burger",
				"LaDolcè Restuarant",
				"Panarotis Pizza",
				"Debonairs Pizza",
				"Romans Pizza",
				"Ocean Basket",
				"Capetown Fish Market",
				"University Campus");
sort($places);
if(isset($_GET['getquery'])) {
	if(empty($_GET['query'])) {
		echo "<li><span class='fa fa-times-circle'></span> No match found.</li>";
		return;
	}
	$search_word = strtolower($_GET['query']);
	$len = strlen($search_word);
	$data = "";
	foreach($places as $name) :
		if(stristr($search_word, substr($name, 0, $len))) {
			// $emphasizedWord = "<strong>".$search_word."</strong>";
			// $name = str_replace($search_word, $emphasizedWord, $name);
			$data .= "<li data-placename='{$name}' onclick='actionSelect(this)'><span class='fa fa-map-marker'></span> ".$name."</li>";
			// break;
		}
	endforeach;
	echo json_encode($data);
	exit();
}
else {
	echo "<li><span class='fa fa-times-circle'></span> No match found!</li>";
}

?>
<?php
/**
* 
*/
/*if(in_array('user_model.php', scandir('../model/'))) {
	require_once('../model/user_model.php');
	// require_once('../model/posts_model.php');
}*/

class Posts extends Users { //extends Users
	public $location = null;
	public $postContent = null;
	protected $email = null;
	private $uid = null;
	private $urlpath = null;
	protected $conn = null;
	protected $queryterm;

	public function __construct() {
		/*global $conn;
		$this->conn = $conn;*/
		parent::__construct();
	}

	public function getWallPosts($start, $limit) {
		$stmt = $this->conn->prepare("SELECT a.post_id, a.email, a.post_content, a.image_url, a.created, b.email, b.imageUrl, CONCAT(b.firstname,' ',b.lastname) AS username
										FROM wallposts a LEFT JOIN users b 
										ON a.email = b.email
										ORDER BY a.created DESC LIMIT ?,?");
		$stmt->bindValue(1, $start, PDO::PARAM_INT);
		$stmt->bindValue(2, $limit, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		else {
			return false;
		}
	}

	public function saveWallPost($userID, $contentText, $imagePath) {
		$this->uid = $userID;
		$this->email = trim($_SESSION['authorize']);
		$this->postContent = $contentText;
		$this->urlpath = $imagePath;
		return $this->setWallPost();
	}

	private function setWallPost() {
		$stmt = $this->conn->prepare("INSERT INTO wallposts (post_id, email, post_content, image_url) VALUES (:postID, :email, :textContent, :imagePath)");
		$stmt->bindValue(':postID', $this->uid, PDO::PARAM_STR);
		$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
		$stmt->bindValue(':textContent', $this->postContent, PDO::PARAM_STR);
		$stmt->bindValue(':imagePath', $this->urlpath, PDO::PARAM_STR);
		try {
			$this->conn->beginTransaction();
			$response = $stmt->execute();
			$this->conn->commit();
			if($response) {
				return true;
			}
			else {
				return false;
			}
			
		}
		catch(PDOException $e) {
			$this->conn->rollBack();
	 	  	echo "Error: " .$e->getMessage();
		}
	}

	public function setLocation($userID, $placeData) {
		$this->uid = $userID;
		$this->location = $placeData;
		return $this->savePlace();
	}
	private function savePlace() {
		$stmt = $this->conn->prepare("INSERT INTO places (user_id, location) VALUES (:userID, :place)");
		$stmt->bindValue(':userID', $this->uid, PDO::PARAM_INT);
		$stmt->bindValue(':place', $this->location, PDO::PARAM_STR);
		try {
			$this->conn->beginTransaction();
			$results = $stmt->execute();
			$this->conn->commit();
			if($results) {
				return true;
			} else {
				return false;
			}
		}
		catch(PDOException $e) {
			$this->conn->rollBack();
			echo "Error: ".$e->getMessage();
		}
	}

	public function updateActivities($userId, $activityContent) {
		$this->uid = $userId;
		$this->post_content = $activityContent;
		return $this->setActivity();
	}

	private function setActivity() {
		$stmt = $this->conn->prepare("INSERT INTO activities (user_id, activity_post) VALUES (:userID, :activity_data)");
		$stmt->bindValue(':userID', $this->uid, PDO::PARAM_INT);
		$stmt->bindValue(':activity_data', $this->post_content, PDO::PARAM_STR);
		try {
			$this->conn->beginTransaction();
			$results = $stmt->execute();
			$this->conn->commit();
			if($results) {
				return true;
			} else {
				return false;
			}
		}
		catch(PDOException $e) {
			$this->conn->rollBack();
			echo "Error: ".$e->getMessage();
		}
	}

	public function getAllActivies() {
		$stmt = $this->conn->prepare("SELECT * FROM activities ORDER BY created DESC");
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		else {
			return false;
		}
	}

	public function getPlaces($params=null) {
		$this->email = (isset($_SESSION['authorize'])) ? $_SESSION['authorize'] : null; //if session is set, return value
		// $this->uid = $params;
		$stmt = $this->conn->prepare("SELECT a.user_id, a.location, a.created, b.id, b.email
									  FROM places a LEFT JOIN users b
									  ON a.user_id = b.id
									  WHERE b.email = :userID ORDER BY a.created DESC");
		$stmt->bindValue(':userID', $this->email, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		else {
			return false;
		}
	}

	public function search($searchTerm) {
		$string = "%".$searchTerm."%";
		$this->queryterm = $string;
		return $this->doSearch();
	}

	protected function doSearch() {
		$stmt = $this->conn->prepare("SELECT a.post_id, a.email, a.post_content, a.image_url, a.created, b.email, b.imageUrl, CONCAT(b.firstname,' ',b.lastname) AS username
										FROM wallposts a LEFT JOIN users b 
										ON a.email = b.email
										WHERE a.post_content LIKE ?");
		$stmt->bindValue(1, $this->queryterm, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		else {
			return false;
		}
	}


}
if(class_exists('Posts')) {
	$post = new Posts();
}
/*$row = $post->getPlaces("1");
echo count($row->location);*/
/*$stmt = $conn->prepare("SELECT a.post_id, a.email, a.post_content, a.image_url, a.created, b.email, b.imageUrl, CONCAT(b.firstname,' ',b.lastname) AS username
						FROM wallposts a LEFT JOIN users b 
						ON a.email = b.email
						ORDER BY a.created DESC");
$stmt->execute();
$rows = $stmt->fetchAll();*/
// $rows = $post->getWallPosts();
//print_r($rows);
/*foreach($rows as $data) {
	echo $data['post_content']."<br>";
}*/

?>
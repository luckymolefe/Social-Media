<?php
/**
* 
*/
$basepath = dirname(__DIR__);

if(in_array('connect.php', scandir($basepath.'/config/'))) {
	if(file_exists($basepath.'/config/connect.php') && is_file($basepath.'/config/connect.php')) {
		require_once($basepath.'/config/connect.php');
		require_once('helper_model.php');
	} else {
		echo "Sorry fails to include connection files.";
	}
}

session_start();
class Users extends Helper {
	protected $firstname;
	protected $lastname;
	protected $email = null;
	private $uid;
	private $password = "";
	private $urlpath = null;
	protected $conn = null;
	protected $hashdata = null;
	private $token;
	protected $status;

	public function __construct() {
		global $conn;
		return $this->conn = $conn;
	}

	public function page_protected() {
		// session_start();
		if (!isset($_SESSION['authorize'])) {
			header("Location: login.php");
		}
	}

	public function isLoggedOn() {
		if(isset($_SESSION['loggedOn']) && $_SESSION['loggedOn'] == true) {
			return true;
		}
		else {
			return false;
		}
	}

	public function isUserExists($email) {
		$this->email = $email;
		$data = $this->validateAccount();
		if($data['email'] == $this->email) {
			return true;
		} else {
			return false;
		}
	}

	private function validateAccount() {
		$stmt = $this->conn->prepare("SELECT email FROM users WHERE email = :email LIMIT 0,1");
		$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $row = $stmt->fetch(PDO::FETCH_ASSOC);
		}
	}

	private function isAccountActive($email) {
		//user should activate account after registration
		$this->email = $email;
		$stmt = $this->conn->prepare("SELECT status FROM users WHERE email = ? LIMIT 0,1");
		$stmt->bindValue(1, $this->email);
		if($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	public function login($email, $password) {
		$this->email = $email;
		$this->password = $password;
		return $this->userLogin();
	}

	private function userLogin() {
		if($this->isUserExists($this->email)) {
			$stmt = $this->conn->prepare("SELECT email, password, status FROM users WHERE email = ? AND password = ? LIMIT 0,1");
			$stmt->bindValue(1, $this->email, PDO::PARAM_STR);
			$stmt->bindValue(2, $this->getHashKey($this->password), PDO::PARAM_STR);
			$stmt->execute();
			if($stmt->rowCount() > 0) {
				$data = $stmt->fetch(PDO::FETCH_OBJ);
				if($data->status == 1) {
					$_SESSION['authorize'] = $this->email;
					$_SESSION['loggedOn'] = true;
					$this->updateUserBrowserActivity(); //set new time for autoLogout session
					#$this->runCron(); //call the cronJob to run once after login.
					return "success"; //$data = $stmt->fetch(PDO::FETCH_OBJ);
				}
				else {
					return "activate";
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function signup($firstname, $lastname, $email, $password) {
		$this->firstname = $firstname;
		$this->lastname = $lastname;
		$this->email = $email;
		$this->password = $password;
		$this->urlpath = "profile/avatars/avatar_192px.png";
		return $this->userSignup();
	}

	private function userSignup() {
		if($this->isUserExists($this->email)) {
			return false;
		}
		else {
				$this->status = 0;
				$stmt = $this->conn->prepare("INSERT INTO users (firstname, lastname, email, password, imageUrl, status) VALUES (?, ?, ?, ?, ?, ?)");
				$stmt->bindValue(1, $this->firstname, PDO::PARAM_STR);
				$stmt->bindValue(2, $this->lastname, PDO::PARAM_STR);
				$stmt->bindValue(3, $this->email, PDO::PARAM_STR);
				$stmt->bindValue(4, $this->getHashKey($this->password), PDO::PARAM_STR);
				$stmt->bindValue(5, $this->urlpath, PDO::PARAM_STR);
				$stmt->bindValue(6, $this->status, PDO::PARAM_STR);
			try {
				$this->conn->beginTransaction();
				$stmt->execute();
				$lastInsertId = $this->conn->lastInsertId();
				$this->conn->commit();
				if($lastInsertId > 0) {
					$this->urlpath = "backgrounds/img17.jpg";
					$stmt = $this->conn->prepare("INSERT INTO images (pic_id, email, urlpath) VALUES (:uid, :email, :urlpath)");
					$stmt->bindValue(':uid', $lastInsertId, PDO::PARAM_INT);
					$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
					$stmt->bindValue(':urlpath', $this->urlpath, PDO::PARAM_STR);
					if($stmt->execute()) {
						$expiryDate = date('d-m-Y', strtotime(date('d-m-Y')+1)); //set expiry date
						$token = $this->email.$expiryDate.microtime(true); //salt your token
						$this->token = $this->getHashKey($token);
						$stmt =  $this->conn->prepare("INSERT INTO recovery (id, email, data, expiryDate) VALUES (?, ?, ?, ?)");
						$stmt->bindValue(1, $lastInsertId, PDO::PARAM_INT);
						$stmt->bindValue(2, $this->getHashKey($this->email), PDO::PARAM_STR);
						$stmt->bindValue(3, $this->token, PDO::PARAM_STR);
						$stmt->bindValue(1, $expiryDate);
						if($stmt->execite()) {
							$verifyUrl ="../confirm/true/".urlencode($this->getHashKey($this->email))."/".urlencode($this->token); //www.pentest.dev  $_SERVER['HTTP_HOST'].
							$username = $this->firstname." ".$this->lastname;
							if(!is_dir('temp')) { //directory doesn't exist create new one, else don't create another.
								mkdir('temp'); //create new directory
							}
							$fp = fopen("temp/activation.html", 'w');  //temporary email written to file
							  fwrite($fp, $this->activationMailTemplate($username, $verifyUrl));
							fclose($fp);
							return true;
						}
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
			}
			catch(PDOException $e) {
				$this->conn->rollBack();
		 	  	echo "Error: " .$e->getMessage();
			}
		}
	}

	public function accountActivation($email, $tokendata) { //called when user clicks link from their email inbox
		$email = urldecode($email);
		$tokendata = urldecode($tokendata);
		$stmt =  $this->conn->prepare("SELECT * FROM recovery WHERE email = ? AND data = ?");
		$stmt->bindValue(1, $email, PDO::PARAM_STR);
		$stmt->bindValue(2, $tokendata, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			$row = $stmt->fetch(PDO::FETCH_OBJ); //get id from recovery table
			$this->status = 1; //set status to on
			$this->uid = $row->id;
			$stmt = $this->conn->prepare("UPDATE users SET status = ? WHERE id = ?");
			$stmt->bindValue(1, $this->status, PDO::PARAM_INT);
			$stmt->bindValue(2, $this->uid, PDO::PARAM_INT);
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	private function activationMailTemplate($username, $verifyUrl) { //this email is sent when the user registered successfully
		$curYear = date('d-m-Y');
		$htmldata = <<<HTMLDATA
		<title>Password Request</title>
		<div class="email-background" style="background-color: #eee; padding: 10px;">
			<div class="email-container" style="max-width: 500px;background-color: #fff;font-family: sans-serif;margin: 0 auto;overflow: hidden;border-radius: 5px;text-align: center;">
				<h1 style="color: #72bcd4;">Account Activation</h1>

				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					Hello {$username}, welcome to our site.
				</p>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					To have full access to the awesome application please take a moment to activate your account as this also is to confirm
					your email address as well.
				</p>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					Feel free to contact us <a href="mailto:support@company.com">support@company.com</a> if you have any trouble in the process of activating your account.
				</p>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					Click activate button below to activate your account.
				</p>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">Thank You for registering with us.</p>
				<div class="cta" style="margin: 20px;font-weight: bolder;">
					<a href="{$verifyUrl}" style="text-decoration: none;display: inline-block;background-color: #82b74b;color: #fff; transition: all .5s;padding: 10px 20px 10px;border-radius: 5px;border: solid 1px #eee;">Activate account</a>
				</div>
			</div>
			<div class="footer" style="background-color: none;padding: 20px;font-size: 10px;font-family: sans-serif;text-align: center;">
				<a href="#">123 Str, City</a> | <a href="#">Visit Us Here</a><br>
				<span>&copy;{$curYear} All rights reserved.</span>
			</div>
		</div>
HTMLDATA;
		return $htmldata;
	}

	public function getHashKey($data) {
		return self::hashKey($data);
	}

	public function updatePassword($oldPassword, $newPassword, $email) {
		$this->email = $email;
		$this->password = $oldPassword;
		$stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE password = ? AND email = ?");
		$stmt->bindValue(1, $this->getHashKey($newPassword), PDO::PARAM_STR);
		$stmt->bindValue(2, $this->getHashKey($this->password), PDO::PARAM_STR);
		$stmt->bindValue(3, $this->email, PDO::PARAM_STR);
		if($stmt->execute()) {
			return true;
		}
		else {
			return false;
		}
	}

	public function updateAccount($id, $firstname, $lastname, $email) {
		$this->uid = $id;
		$this->firstname = $firstname;
		$this->lastname = $lastname;
		$this->email = $email;
		$stmt = $this->conn->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE id = ?");
		$stmt->bindValue(1, $this->firstname, PDO::PARAM_STR);
		$stmt->bindValue(2, $this->lastname, PDO::PARAM_STR);
		$stmt->bindValue(3, $this->email, PDO::PARAM_STR);
		$stmt->bindValue(4, $this->uid, PDO::PARAM_INT);
		if($stmt->execute()) {
			return true;
		}
		else {
			return false;
		}
	}

	protected function genNewPassword() {
		return self::tokenKey();
	}

	public function emailtemplate($username, $curDate, $expiryDate, $url) {
		$curYear = date('Y');
		$htmldata = <<<HTMLDATA
		<title>Password Request</title>
		<div class="email-background" style="background-color: #eee; padding: 10px;">
			<div class="email-container" style="max-width: 500px;background-color: #fff;font-family: sans-serif;margin: 0 auto;overflow: hidden;border-radius: 5px;text-align: center;">
				<h1 style="color: #72bcd4;">Password Request Reset</h1>
				<!-- <a href="#" style="color: #3087F5;text-decoration: none;">
					<img src="error404.jpg" style="max-width: 100%;" alt="">
				</a> -->
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					Hi {$username},<br>
					Have you requested to reset your password on <small>{$curDate}</small> ?.<br>
				</p>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					This request is valid for 3 days, starting from the day this request was made. Expires on: <small>{$expiryDate}</small>.
				</p>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					If you did not request to reset your password please do not click on the link, simply ignore this email or delete it.
					Click below to reset.
				</p>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">Thank You</p>
				<div class="cta" style="margin: 20px;font-weight: bolder;">
					<a href="{$url}" style="text-decoration: none;display: inline-block;background-color: #72bcd4;color: #fff; transition: all .5s;padding: 10px 20px 10px;border-radius: 5px;border: solid 1px #eee;">Reset password</a>
				</div>
			</div>
			<div class="footer" style="background-color: none;padding: 20px;font-size: 10px;font-family: sans-serif;text-align: center;">
				<a href="#">123 Str, City</a> | <a href="#">Visit Us Here</a><br>
				<span>&copy;{$curYear} All rights reserved.</span>
			</div>
		</div>
HTMLDATA;
		return $htmldata;
	}

	public function requestPassword($email) {
		$this->email = $email;
		if($this->isUserExists($this->email)) {
			$response = $this->getUserDetails($this->email); //get user details to get user_id
			$this->uid = $response->id;
			$expFormat = mktime(date("H"), date("i"), date("s"), date("m"), date("d")+3, date("Y"));  //add 3days from the current date, so set expiry date
			$expiryDate = date("d-m-Y", $expFormat); //format date
			$token = $this->email.$expiryDate.microtime(true); //salt your token
			$this->token = $this->getHashKey($token); //create a unique token, fusing email & expiry date and Hash it.
			$stmt = $this->conn->prepare("INSERT INTO recovery (id, email, data, expiryDate) VALUES (?, ?, ?, ?)");
			$stmt->bindValue(1, $this->uid);
			$stmt->bindValue(2, $this->getHashKey($this->email));
			$stmt->bindValue(3, $this->token);
			$stmt->bindValue(4, $expiryDate);
			try{
				$this->conn->beginTransaction();
				$results = $stmt->execute();
				$this->conn->commit();
				if($results) {
					//now write an email message with link for password reset
				/*	$message = "Hi ".$response->username.", <br><br>Have you requested to reset your password, please click <a href='../controller.php?processReset=true&email=".urlencode(base64_encode($this->email))."&token=".urlencode($this->token)."'>reset password</a>.<br>
					This request is valid for three 3 days, starting from the day this request was made.<br>If you did not request to reset your password please do not click 
					the link, simply ignore this email or delete it.<br><br><br>Kind Regards
					<br>IT Support.<br><a href='mailto:support@company.com'>support@company.com</a>";*/
					//write data to temp file, since email host is not available
					if(!is_dir('temp')) { //directory doesn't exist create new one, else don't create another.
						mkdir('temp'); //create new directory
					}
					/*$fp = fopen("temp/email.html", 'w');
					    fwrite($fp, "<title>Password Request</title><div>Requested: ".date("d-m-Y g:i A")." <br>Expires in: ".$expiryDate.
					    	"<br><br>".htmlspecialchars_decode($message)."<br></div>");
					fclose($fp);*/
					$curDate = date('d-m-Y \a\t g:i A');
					// $url = "../controller.php?processReset=true&email=".urlencode(base64_encode($this->email))."&token=".urlencode($this->token);
					$url ="../verifyreset/true/".urlencode(base64_encode($this->email))."/".urlencode($this->token); //www.pentest.dev  $_SERVER['HTTP_HOST'].
					$fp = fopen("temp/email.html", 'w');
					  fwrite($fp, $this->emailtemplate($response->username, $curDate, $expiryDate, $url));
					fclose($fp);
					return true;
				}
			}
			catch(PDOException $e) {
				$this->conn->rollBack();
				echo "Error: ".$e->getMessage();
			}
		} else {
			return false;
		}
	}

	public function newPasswordtemplate($username, $new_password) {
		$currDate = date("d-m-Y g:i A");
		$data = <<<HTMLDATA
		<title>New Password</title>
		<div class="email-background" style="background-color: #eee; padding: 10px; height: 650px;">
			<div class="email-container" style="max-width: 500px;background-color: #fff;font-family: sans-serif;margin: 0 auto;overflow: hidden;border-radius: 5px;text-align: center;">
				<h1 style="color: #72bcd4;">New Password</h1>
				<!-- <a href="#" style="color: #3087F5;text-decoration: none;">
					<img src="error404.jpg" style="max-width: 100%;" alt="">
				</a> -->
				<small style="color: #666;">{$currDate}</small>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					Hi {$username},<br><br>
					Your password was successfully reset.<br>Please copy &amp; keep your password safe, do not show it any one.
				</p>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					Use this new password to login and it is recommended to change your password after you login.
				</p>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					This is your New password: 
					<strong style="border: 1px solid #72bcd4;color: #405d27;border-radius: 2px;padding: 5px 15px;">{$new_password}</strong>
				</p>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">Thank You</p>
				<br><br>
			</div>
			<div class="footer" style="background-color: none;padding: 20px;font-size: 10px;font-family: sans-serif;text-align: center;">
				<a href="#">123 Str, City</a> | <a href="#">Visit Us Here</a><br>
				<span>&copy;2017 All rights reserved.</span>
			</div>
		</div>
HTMLDATA;
		return $data;
	}

	public function resetPassword($email, $keydata) {
		$this->email = urldecode(base64_decode($email));
		$this->token = urldecode($keydata);
		$stmt = $this->conn->prepare("SELECT * FROM recovery WHERE email = ? AND data = ?"); //check if token sent match the one in DB
		$stmt->bindValue(1, $this->getHashKey($this->email), PDO::PARAM_STR);
		$stmt->bindValue(2, $this->token, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			$this->password = $this->genNewPassword(); //call method to generate new password
			$stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE email = ?");
			$stmt->bindValue(1, $this->getHashKey($this->password), PDO::PARAM_STR); //hash new password before saving to database
			$stmt->bindValue(2, $this->email, PDO::PARAM_STR);
			if($stmt->execute()) {
				$stmt = $this->conn->prepare("DELETE FROM recovery WHERE email = ?"); //now delete from temp recovery database
				$stmt->bindValue(1, $this->getHashKey($this->email), PDO::PARAM_STR);
				if($stmt->execute()) {
					$response = $this->getUserDetails($this->email);
					if(!is_dir('temp')) { //directory doesn't exist create new one, else don't create another.
						mkdir('temp'); //create new directory
					}
					//now write the final new email letter, with user new password
					/*$message = "Hi ".$response->username.", <br><br>Your password was successfully reset. Please copy &amp; keep your password safe, do not show it to any one.<br>
					<p>This is your New Password: <strong>".urlencode($this->password)."</strong></p>
					Use this password to login and it is recommended to change your password after you login.
					<br><br><br>Kind Regards<br>IT Support.<br><a href='mailto:support@company.com'>support@company.com</a>";*/
					// fwrite($fp, "<title>New Password</title><div>".date("d-m-Y g:i A")." <br>".htmlspecialchars_decode($message)."<br></div>");
					$fp = fopen("temp/newpass.html", 'w');
				      fwrite($fp, $this->newPasswordtemplate($response->username, $this->password));
				    fclose($fp);
					return true;
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	public function updateProfileCover($tableName, $updateColumn, $recordRef, $emailValue, $filePath) {
		$this->email = $emailValue; //$_SESSION['authorize'];
		$this->urlpath = $filePath;
		$stmt = $this->conn->prepare("UPDATE `$tableName` SET `$updateColumn` = ? WHERE `$recordRef` = ?");
		$stmt->bindValue(1, $this->urlpath, PDO::PARAM_STR);
		$stmt->bindValue(2, $this->email, PDO::PARAM_STR);
		if($stmt->execute()) {
			return true; //$cover_img = $stmt->fetch(PDO::FETCH_OBJ);
		} else {
			return false;
		}
	}

	public function getUserProfile($params=null) {
		$this->email = (isset($_SESSION['authorize'])) ? $_SESSION['authorize'] : $params; //if session is set, return value
		$stmt = $this->conn->prepare("SELECT a.id, CONCAT(a.firstname,' ',a.lastname) as username, a.firstname, a.lastname, a.email, a.imageUrl, a.status, b.pic_id, b.urlpath
								FROM users a LEFT JOIN images b
								ON a.id = b.pic_id
								WHERE a.email = :email");
		$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetch(PDO::FETCH_OBJ);
		} else {
			return false;
		}
	}

	public function getAllUsers() {
		$stmt = $this->conn->prepare("SELECT id, CONCAT(firstname,' ',lastname) AS username, email, imageUrl, status FROM users");
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $results = $stmt->fetchAll(PDO::FETCH_OBJ); //$stmt->fetchAll(PDO::FETCH_OBJ);
		} else {
			return false;
		}
	}

	public function getUserDetails($params) {
		$this->email = $params;
		$stmt = $this->conn->prepare("SELECT id, CONCAT(firstname,' ',lastname) AS username, email, imageUrl FROM users WHERE email = ?");
		$stmt->bindValue(1, $this->email, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetch(PDO::FETCH_OBJ);
		} else {
			return false;
		}
	}

	public function filterUsers($querydata) {
		$this->firstname = "%".$querydata."%";
		$this->lastname = "%".$querydata."%";
		$stmt = $this->conn->prepare("SELECT a.id, CONCAT(a.firstname,' ',a.lastname) as username, a.firstname, a.lastname, a.email, a.imageUrl, a.status, b.pic_id, b.urlpath
								FROM users a LEFT JOIN images b
								ON a.id = b.pic_id
								WHERE a.firstname LIKE ? OR a.lastname LIKE ? ORDER BY a.firstname");
		$stmt->bindValue(1, $this->firstname, PDO::PARAM_STR);
		$stmt->bindValue(2, $this->lastname, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		else {
			return false;
		}
	}

	public function updateUserBrowserActivity() {
		return $_SESSION['active_time'] = time();
	}

}
if(class_exists('Users')) {
	$user = new Users();
}
/*$results = $user->getAllUsers();
echo $results->username;
foreach($results as $row) {
	echo $row->email."<br>";
}*/

?>
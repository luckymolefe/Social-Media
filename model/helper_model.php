<?php
/**
* helper class model
*/
/*if(in_array('user_model.php', scandir('../model/'))) {
	if(file_exists('../model/user_model.php') && is_file('../model/user_model.php')) {
		require_once('../model/user_model.php');
	}
}*/

class Helper {
	private $data="";
	private $length;
	private $chars;

	public function hashKey($data) {
		$this->data = $data;
		return $this->generateHash();
	}

	private function generateHash() {
		return sha1($this->data);
		// return password_hash('dataToHash', PASSWORD_BCRYPT);
	}

	//return random generated strings randomString()
	public function tokenKey() {
		//set length and string of values
		$this->length = 6;
		$this->chars = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		return $this->randomGenerate();
	}
	//process the random strings
	private function randomGenerate() {
	    if($this->length > 0) {
	        $len_chars = (strlen($this->chars) - 1);
	        $the_chars = $this->chars{rand(0, $len_chars)};
	        for ($i = 1; $i < $this->length; $i = strlen($the_chars))
	        {
	            $r = $this->chars{rand(0, $len_chars)};
	            if ($r != $the_chars{$i - 1}) $the_chars .=  $r;
	        }
	        return $the_chars;
	    }
	}

	public function timeDiff($old_time) { //formats time stamps on posted items
		$difference =strtotime(date('Y-m-d')) - strtotime($old_time);
		$days_past = floor($difference / (60 * 60 * 24));
		if($days_past <= 0) {
			$day = "Today";
		}
		else if( $days_past == 1) {
			$day = "Yesterday";
		}
		else if( $days_past > 1 && $days_past < 7) {
			$day = $days_past." Days ago";
		}
		else if( $days_past >= 7 && $days_past <= 13) {
			$day = "1W";
		}
		else if( $days_past == 14) {
			$day = "2W";
		}
		else if( $days_past > 14 && $days_past < 30) {
			$day = "Weeks ago";
		}
		else if( $days_past == 30 || $days_past == 31) {
			$day = "1M";
		}
		else if( $days_past > 31) {
			$day = "Months ago";
		}
		else if( $days_past > 365) {
			$day = "Years ago";
		}
		return $day;
	}

} /*class END*/

if(class_exists('Helper')) {
	$hash = new Helper();
	if(method_exists($hash, 'hashKey')) {
		if(is_callable('hashKey')) {
			return call_user_method('hashKey', $hash);
		}
	}
	if(method_exists($hash, 'tokenKey')) {
		if(is_callable('tokenKey')) {
			return call_user_method('tokenKey', $hash);
		}
	}
} 

/*$params[] ='Lucky Molefe';
$params[] = date('d-m-Y \a\t g:i A');
$params[] ='2017-09-14';
$params[] ='controller.php?processreset=true&email='.$hash->hashKey(base64_decode('luckmolf@company.com')).'&token='.$hash->hashKey('luckmolf@company.com');
 $hash->emailtemplate($params[0], $params[1], $params[2], $params[3]);*/

// echo $hash->hashKey("Lucky's");
// echo $hash->tokenKey();

?>
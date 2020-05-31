<?php
// require_once('user_model.php');
/**
* 
*/
class CronJob extends Users {
	protected $expiryDate = null;
	protected $data = null;

	function __construct(){
		parent::__construct(); //instantiate parent construct
	}

	public function runCron() {
		$_SESSION['crontime'] = time(); //after it has run, set new update time for cron job to run again.
		return $this->updateCronJob();
	}

	private function doCronJob() {
		$stmt = $this->conn->prepare("SELECT * FROM recovery ORDER BY expiryDate");
		if($stmt->execute()) {
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		else {
			return false;
		}
	}

	/*private function updateCronJob() {
		$this->data = $this->doCronJob(); //get all data from recovery table
		if($this->data) {
			// $this->expiryDate = date('Y-m-d');
			foreach($this->data as $row) : //extract all data
				$stmt = $this->conn->prepare("DELETE FROM recovery WHERE expiryDate < NOW()"); //where expiry data is lessthan current date
				// $stmt->bindValue(1, $this->expiryDate);
				if($stmt->execute()) {
					return true;
				} else {
					return false;
				}
			endforeach;
		}
		else {
			return false;
		}
	}*/

	private function updateCronJob() {
		$this->data = $this->doCronJob();
		if($this->data) {
			foreach($this->data as $row) {
				$difference = strtotime(date('Y-m-d')) - strtotime($row->expiryDate);
				$daysOld = floor($difference / (60 * 60 * 24));
				if($daysOld >= 3) {
					$stmt = $this->conn->prepare("DELETE FROM recovery WHERE expiryDate = ?");
					$stmt->bindValue(1, $row->expiryDate);
					if($stmt->execute()) {
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
		}
		else {
			return false;
		}
	}
}

if(class_exists('CronJob')) {
	$cron = new CronJob();
}
	/*if(is_a($cron, 'CronJob') && method_exists($cron, 'runCron')) {

		foreach($cron->runCron() as $row) {

			$difference =strtotime(date('Y-m-d')) - strtotime($row->expiryDate); //minus current data from old date
			$days_past = floor($difference / (60 * 60 * 24)); //multiply 60sec->1min * 60->120min *24hours to get days 

			if($days_past >= 3) { #compare if days after formula are greater or equal to 3days //if(date('Y-m-d') > $row->expiryDate) {
				echo "date is older";
			} else {
				echo "date is newer";
			}
		}
	}*/


?>
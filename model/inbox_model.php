<?php
/**
* 
*/
class Inbox extends Users {
	protected $conn = null;
	public $sender = null;
	public $names = null;
	public $recipient = null;
	private $inboxMessage;
	protected $sent_date;
	protected $status = null;
	
	public function __construct() {
		/*global $conn;
		$this->conn = $conn;*/
		parent::__construct(); //instantiate parent construct
	}

	public function sendMessage($sender_email, $sender_names, $recipient_email, $messageBody) { //send inbox message
		$this->sender = $sender_email;
		$this->names = $sender_names;
		$this->recipient = $recipient_email;
		$this->inboxMessage = $messageBody;
		if($sender_email === $recipient_email) {
			return $data = "You cannot send to yourself!"; //trying to loop message, send to email matching sender's email Addr
		}
		else {
			if($this->isUserExists($recipient_email)) { //check if email exists
				return $this->saveMessage(); //finally send email if everything is correct
			} else {
				return $data = "Recipient email does not exist!"; //if trying to send message to email that does not exists
			}
		}
	}

	protected function saveMessage() {
		$stmt = $this->conn->prepare("INSERT INTO inbox (recipient_email, sender_email, names, message) VALUES (?, ?, ?, ?)");
		$stmt->bindValue(1, $this->recipient, PDO::PARAM_STR);
		$stmt->bindValue(2, $this->sender, PDO::PARAM_STR);
		$stmt->bindValue(3, $this->names, PDO::PARAM_STR);
		$stmt->bindValue(4, $this->inboxMessage, PDO::PARAM_STR);
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
			echo "Error: ".$e->getMessage();
		}
	}

	public function getunreadMessages($email) { //get all unread messages		
		$this->status = 0; //messaged flagged 0 means its unread
		$this->recipient = $email;
		$stmt = $this->conn->prepare("SELECT * FROM inbox WHERE recipient_email = ? AND message_status = ? ORDER BY created DESC");
		$stmt->bindValue(1, $this->recipient, PDO::PARAM_INT);
		$stmt->bindValue(2, $this->status, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		else {
			return false;
		}
	}

	public function getreadMessages($email) { //get all read messages
		$this->status = 1; //messaged flagged 1 means it was read
		$this->recipient = $email;
		$stmt = $this->conn->prepare("SELECT * FROM inbox WHERE recipient_email = ? AND message_status = ? ORDER BY created DESC");
		$stmt->bindValue(1, $this->recipient, PDO::PARAM_INT);
		$stmt->bindValue(2, $this->status, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		else {
			return false;
		}
	}

	public function getAllMessages($email) { //get All Messages
		$this->recipient = $email;
		$this->status = 2; //select all where status is lessthan < 2
		$stmt = $this->conn->prepare("SELECT * FROM inbox WHERE recipient_email = ? AND message_status < ? ORDER BY created DESC");
		$stmt->bindValue(1, $this->recipient, PDO::PARAM_STR);
		$stmt->bindValue(2, $this->status, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		else {
			return false;
		}
	}

	public function markMessageRead($dated) { //open an unread message
		$this->send_date = $dated;
		$this->status = 1; //flag message as read on open
		$stmt = $this->conn->prepare("UPDATE inbox SET message_status = ? WHERE created = ?");
		$stmt->bindValue(1, $this->status, PDO::PARAM_INT); //set status to 1, message is read
		$stmt->bindValue(2, $this->send_date, PDO::PARAM_STR);
		if($stmt->execute()) {
			return true;
		}
		else {
			return false;
		}
	}

	public function getArchivedMessages($email) { //get all messages flagged as archived
		$this->status = 2; //messaged flagged 1 means it was read
		$this->recipient = $email;
		$stmt = $this->conn->prepare("SELECT * FROM inbox WHERE recipient_email = ? AND message_status = ? ORDER BY created DESC");
		$stmt->bindValue(1, $this->recipient, PDO::PARAM_STR);
		$stmt->bindValue(2, $this->status, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		else {
			return false;
		}
	}

	public function archiveMessage($dated) { //set message flag as archive
		$this->send_date = $dated;
		$this->status = 2; //flag message as read on open
		$stmt = $this->conn->prepare("UPDATE inbox SET message_status = ? WHERE created = ?");
		$stmt->bindValue(1, $this->status, PDO::PARAM_INT); //set status to 1, message is read
		$stmt->bindValue(2, $this->send_date, PDO::PARAM_STR);
		if($stmt->execute()) {
			return true;
		}
		else {
			return false;
		}
	}

	public function restoreMessage($dated) { //restore message from archive, set it to 1 as unread
		$this->send_date = $dated;
		$this->status = 1; //flag message as read on open
		$stmt = $this->conn->prepare("UPDATE inbox SET message_status = ? WHERE created = ?");
		$stmt->bindValue(1, $this->status, PDO::PARAM_INT); //set status to 1, message is read
		$stmt->bindValue(2, $this->send_date, PDO::PARAM_STR);
		if($stmt->execute()) {
			return true;
		}
		else {
			return false;
		}
	}

	public function formatDate($post_date) { //format timestamps on messages
		$formatted_post_date = date('Y-m-d', strtotime($post_date));
		$difference =strtotime(date('Y-m-d')) - strtotime($formatted_post_date);
		$days_past = floor($difference / (60 * 60 * 24));

		if($days_past <= 0) {
			$day = "Today ".date('\a\t H:i A', strtotime($post_date));
		}
		else if( $days_past == 1) {
			$day = "Yesterday ".date('\a\t H:i A', strtotime($post_date));
		}
		else {
			$day = date('D, d M \a\t H:i A', strtotime($post_date));
		}
		return $day;
	}

}

if(class_exists('Inbox')) {
	$msg = new Inbox();
}

?>
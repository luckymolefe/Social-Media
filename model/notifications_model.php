<?php
/**
* Author: Lucky Molefe
* 
*/
class Notifications extends Posts { //extends Posts
	protected $status = null;
	protected $from;
	protected $to;
	protected $email = null;
	private $dateCreated;

	public function __construct() {
		parent::__construct();
	}

	public function sendInvitation($user_id, $inviteTo, $inviteFrom) {
		$this->uid = intval($user_id);
		$this->to = $inviteTo;
		$this->from = $inviteFrom;
		$this->status = 0;
		return $this->setInvitation();
	}

	private function setInvitation() {
		$stmt = $this->conn->prepare("INSERT INTO invitations (user_id, invite_to, invite_by, invitation_status) VALUES (?, ?, ?, ?)");
		$stmt->bindValue(1, $this->uid, PDO::PARAM_INT);
		$stmt->bindValue(2, $this->to, PDO::PARAM_STR);
		$stmt->bindValue(3, $this->from, PDO::PARAM_STR);
		$stmt->bindValue(4, $this->status, PDO::PARAM_INT);
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

	public function getInvitations($user_email) {
		// $this->uid = (int)$user_id;
		$this->email = $user_email;
		$this->status = 0;
		$stmt = $this->conn->prepare("SELECT * FROM invitations WHERE invite_to = :email AND invitation_status = :status");
		$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
		$stmt->bindValue(':status', $this->status, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		} else {
			return false;
		}
	}

	public function acceptInvite($user_email, $timeStamp) {
		$this->email = $user_email;
		$this->status = 1;
		$this->dateCreated = $timeStamp;
		$stmt = $this->conn->prepare("UPDATE invitations SET invitation_status = ? WHERE invite_to = ? AND created = ?");
		$stmt->bindValue(1, $this->status, PDO::PARAM_INT);
		$stmt->bindValue(2, $this->email, PDO::PARAM_STR);
		$stmt->bindValue(3, $this->dateCreated, PDO::PARAM_STR);
		if($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	public function getFriendsList($authorized) {
		$this->email = $authorized; //email of user who is logged-in and requesting a friends list
		$this->status = 1; //where friend request status is 1, means accepted added to friends list
		$stmt = $this->conn->prepare("SELECT * FROM invitations WHERE invite_to = :email AND invitation_status = :status ORDER BY created");
		$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
		$stmt->bindValue(':status', $this->status, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		} else {
			return false;
		}
	}

	public function getAllFriends($email) {
		 $this->email = $email;
		$stmt = $this->conn->prepare("SELECT * FROM invitations WHERE invite_to = ? ORDER BY created");
		$stmt->bindValue(1, $this->email, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		} else {
			return false;
		}
	}
}

$notifyObj = new Notifications();

/*if($alerts->sendInvitation(1)) {
	echo "Success!";
} else {
	echo "Failed";
}*/

?>
<?php 
require_once('inc/db.php');
class USER{

	private $conn;
	private $userID;
	public $access_level = 0;
	private $user_name;
	private $email;
	private $first_name;
	private $last_name;

	function __construct(){
		$database = new DATABASE();
		$db = $database->dbConnection();
		$this->conn = $db;	

	}
	public function runQuery($sql){
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}

	public function register($fname,$lname,$uname,$umail,$upass,$uaccess){
		try{
			$options = [
			    'cost' => 11,
			];
			$new_password = password_hash($upass, PASSWORD_DEFAULT,$options);
			
			$stmt = $this->conn->prepare("INSERT INTO users(first_name,last_name,user_name,user_email,user_pass,user_access) 
		                                               VALUES(:fname, :lname, :uname, :umail, :upass, :uaccess)");
												  
			$stmt->bindparam(":fname", $fname);
			$stmt->bindparam(":lname", $lname);
			$stmt->bindparam(":uname", $uname);
			$stmt->bindparam(":umail", $umail);
			$stmt->bindparam(":upass", $new_password);										  
			$stmt->bindparam(":uaccess", $uaccess);										  
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}				
	}

	public function updateUser($uid,$fname,$lname,$uname,$umail,$upass,$uaccess){
		try{
			if($upass != ''){
				$options = [
			    'cost' => 11,
				];
				$new_password = password_hash($upass, PASSWORD_DEFAULT,$options);
				$stmt = $this->conn->prepare("UPDATE users SET first_name=:fname, last_name=:lname,user_name=:uname,user_email=:umail,user_pass=:upass,user_access=:uaccess 
					WHERE user_id=:uid");
			}else{
				$stmt = $this->conn->prepare("UPDATE users SET first_name=:fname, last_name=:lname,user_name=:uname,user_email=:umail,user_access=:uaccess 
					WHERE user_id=:uid");
			}
			
			$stmt->bindparam(":fname", $fname);
			$stmt->bindparam(":lname", $lname);
			$stmt->bindparam(":uname", $uname);
			$stmt->bindparam(":umail", $umail);
			if($upass != ''){
				$stmt->bindparam(":upass", $new_password);
			}									  
			$stmt->bindparam(":uaccess", $uaccess);	
			$stmt->bindparam(":uid", $uid);
			$stmt->execute();	
			
			return $stmt;

		}
		catch(PDOException $e){
			echo $e->getMessage();
		}	
	}

	public function deleteUser($uid){
		try{

			$stmt = $this->conn->prepare("DELETE FROM users WHERE user_id=:uid");
			$stmt->bindparam(":uid", $uid);
			$stmt->execute();	
			
			return $stmt;			
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	public function doLogin($umail,$upass){
		try{
			$stmt = $this->conn->prepare("SELECT user_id, user_email, user_pass, user_access FROM users WHERE user_email=:umail ");
			$stmt->execute(array(':umail'=>$umail));

			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if($stmt->rowCount() == 1){
				if(password_verify($upass, $userRow['user_pass'])){
					$_SESSION['user_session'] = $userRow['user_id'];
					if($userRow['user_access'] == 2){
						$_SESSION['access_level'] = 2;
						$access_level = 2;
					}
					
					return true;
				}else{
					return false;
				}
			}
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	public function is_admin($id){
		
		$stmt = $this->runQuery("SELECT user_access FROM users WHERE user_id=:id");
		$stmt->execute(array(':id'=>$id));
		$uaccess=$stmt->fetch(PDO::FETCH_ASSOC);

		if($stmt->rowCount() == 1){
			$acc_level = $uaccess['user_access'];
			if($acc_level == 2){
				return true;
			}
		}
		

	}
	public function is_loggedin(){
		if(isset($_SESSION['user_session'])){
			
			return true;
		}
	}

	public function doLogout(){
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}

	public function get_all_users(){
		$stmt = $this->runQuery("SELECT user_id, user_name, first_name, last_name, user_email, user_access FROM users");
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}

	public function redirect($url){
		header("Location: $url");
	}

	public function getUserName(){
		return $this->user_name;
	}	

	public function GetUserNameById($uid){
		$stmt = $this->runQuery("SELECT first_name, last_name FROM users WHERE user_id=:uid");
		$stmt->execute(array(':uid'=>$uid));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$result = $result['first_name'].' '.$result['last_name'];
		return $result;
	}

	public function getEmail(){
		return $this->email;
	}	
	public function setEmail($email){
		$this->email = $email;
	}

	public function getFirstName(){
		return $this->first_name;
	}	
	public function setFirstName($first_name){
		$this->first_name = $first_name;
	}

	public function getLastName(){
		return $this->last_name;
	}	
	public function setLastName($last_name){
		$this->last_name = $last_name;
	}
}
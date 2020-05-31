<?php
require_once("credentials.php");

try {
	$conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);
}

catch(PDOException $e){
	//echo "Error: " .$e->getMessage();
	echo "<div class='container'>
        <div class='row'>
          <div class='col-md-6 col-md-offset-3'>
            <div class='alert alert-danger' role='alert'>
             <span class='glyphicon glyphicon-warning-sign'></span>
             <strong>Error occured:</strong> ".$e->getMessage()."<br/>
                Contact: <a href='mailto:admin@localhost.com'>administrator</a>
            </div>
          </div>
        </div>
      </div>";
}

?>
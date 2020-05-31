<?php
// sleep(1);
$email = (isset($_GET['email'])) ? $_GET['email'] : '';
$senderDir = (isset($_GET['sender'])) ? $_GET['sender'] : '';
session_start(); //start a session since we cannot import the whole controller file.
$_SESSION['active_time'] = time(); //set new time session, expand its life.
if(isset($_POST['uploadChange'])) {
   $emailValue = $_POST['email'];
   $dirname = $_POST['senderDir'];

   if($dirname=="profile") {
    $table_name = "users";
    $table_column_update = "imageUrl";
    $table_column_ref = "email";
   }
   else {
    $table_name = "images";
    $table_column_update = "urlpath";
    $table_column_ref = "email";
   }

      if($_FILES['mediafile']['error'] > 0) {
       /* echo "<div class='alert alert-danger'>
                <span class='glyphicon glyphicon-remove'></span> 
                  <strong>Problem:</strong>
             ";*/
        switch ($_FILES['mediafile']['error']) {
          case 1:
            echo "File exceeded upload max filesize.";
            break;
          case 2:
            echo "File exceeded max file size. ";
            break;
          case 3:
            echo "File only partially uploaded.";
            break;
          case 4:
            echo "No file uploaded. Please go back try again.";
            break;
          case 6:
            echo "Cannot upload file: No temp directory specified.";
            break;
          case 7:
            echo "Upload failed: Cannot write to disk.";//</div>";
            break;

          default:
            echo "Please contact web administrator.";
            break;
        }
        exit;
      }
      
      //Does the file have the right MIME type?
      if(($_FILES['mediafile']['type'] != 'image/jpeg') && ($_FILES['mediafile']['type'] != 'image/png')) {
         echo "<script>alert('Failed to upload. Incorrect image file type.')</script>";
         echo "<script>window.open('newwelcome.php','_self')</script>";
      }

      //define file path put the file where we'd like it
      $upfile = "{$dirname}/".$_FILES['mediafile']['name']; // "profile/"

      if(is_uploaded_file($_FILES['mediafile']['tmp_name'])) {
        require_once('controller.php');
          $results = $user->updateProfileCover($table_name, $table_column_update, $table_column_ref, $emailValue, $upfile);
          if($results == "true") {
            echo "<script>alert('Image uploaded successfully...')</script>";
            header("Location: newwelcome.php");
          } else {
            echo "<script>alert('Failed to save path to database...')</script>";
            echo "<script>window.open('newwelcome.php','_self')</script>";
          }
          /*$update = $user->uploadImage($upfile, $id);
          if ($update) {
            header("Location: newwelcome.php?action=updated");
          }
          else
          {
            echo "<script>alert('Unable to update mediafile.')</script>";
          }*/
      }

      if (!move_uploaded_file($_FILES['mediafile']['tmp_name'], $upfile)) {
          echo "<div class='container'>
                  <div class='alert alert-danger'>
                    Problem: Could not move file to destination directory.
                  </div>
                </div>";
          exit;
      }
      else
      {
        echo "<div class='container'>
                <div class='alert alert-danger'>
                  Problem: Possible file upload attack. Filename: <strong>".$_FILES['mediafile']['name']."</strong>
                </div>
              </div>";
        exit;
      }
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Upload File</title>
  <link rel="stylesheet" type="text/css" href="styles/animate.css">
  <style type="text/css">
    .panel {
      margin-top: -200px;
      animation: drop 1s ease forwards;
    }
    /*form > .btn {
      position: fixed;
      left: 340px;
    }*/
    @keyframes drop {
      0% {}
      70% { transform: translateY(370px) } /*170*/
      100% { transform: translateY(350px) } /*150*/
    }
    .pop-upload-hide {
      animation: popoff 1s ease forwards;
    }
    @keyframes popoff {
      0%  { transform: translateY(370px) }
      100%{ transform: translateY(-200px) }
    }
    .hr-ruler {
      max-width: 100%;
      border-top: thin solid #eee;
      margin-top: 5px;
      padding-bottom: 15px;
    }
  </style>
   <script type="text/javascript">
    $('.cancel').click(function(event) {
      $('.panel').addClass('pop-upload-hide'); //remove
      $('.layer').delay(200).fadeOut('slow'); 
    });
    $('#uploadprofile').click(function() {
      var cancel = confirm('Continue upload?.');
      if(cancel===false) {
        return false;
      }
      else if($('input[type=file]').val() == "") {
        $('input[type=file]').addClass('errorHighlight');
        alert("Missing file to upload, please try again!");
        return false;
      }
    });
   </script>
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <div class="panel panel-info">
        <div class="panel-body">
          <button type="button" class="close cancel" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
          </button>
          <h4><center>Upload Image</center></h4>
        <div class="hr-ruler"></div>
          <label class="form-label">Select (<?php echo $senderDir; ?>) <span class="fa fa-image"></span> image to upload&hellip;</label>
          <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="email" value="<?php echo $email; ?>">
          	  <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
              <input type="hidden" name="senderDir" value="<?php echo $senderDir; ?>">
          	  <input type="file" class="form-control" name="mediafile">
          	  <div class="text-danger"><strong>NOTE:<em> Maximum file size is (2MB)</em></strong></div>
        <div class="hr-ruler"></div>
            <center>
              <button type="submit" class="btn btn-success" id="uploadprofile" name="uploadChange">Upload <span class="fa fa-upload"></span></button>
              <button type="button" class="btn btn-default cancel">Cancel <span class="glyphicon glyphicon-floppy-remove"></span></button>
            </center>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
<?php //$_GET['photo']=true; ?>

<?php if(isset($_GET['photo'])) { ?>
<div id="uploaded_file"></div>
	 <form action="controller.php" method="post" name="multiple_upload_form" id="multiple_upload_form" enctype="multipart/form-data" >
    	<!-- <input type="hidden" name="image_form_submit" value="1"/> -->
      <!-- <label class="form-label">Select <span class="fa fa-image"></span> image to upload:</label> -->
      <!-- <input type="hidden" name="MAX_FILE_SIZE" value="2000000"> -->
      <input type="file" class="form-control" name="images" id="fileUpload" autofocus />
      <input type="hidden" name="userId" id="userId" value="<?php echo $_GET['uid']; ?>">
      <input type="hidden" name="profileImage" id="profImg" value="<?php echo $_GET['profileImage']; ?>">
      <input type="hidden" name="profileName" id="profName" value="<?php echo $_GET['profileName']; ?>">
      <textarea name="contentMessage" id="contentMessage" class="form-control" rows="1" placeholder="add caption here..."></textarea>
      <center>
        <button type="reset" class="btn btn-sm btn-default cancelForm">Cancel <span class="glyphicon glyphicon-floppy-remove"></span></button>
        <button type="button" class="btn btn-sm btn-success submit" name="upload">Post <span class="fa fa-upload"></span></button>
      </center>
    </form> <!-- onClick="uploadPostImage()" -->

  <script type="text/javascript">
    $('#contentMessage').css('resize', 'none'); //disable textarea resizing
    $('.submit').attr('disabled', true);
     $('#fileUpload').focus();
    $('#fileUpload').on('blur', function() {
      if($('#fileUpload').val() != "") {
        $('.submit').attr('disabled', false);
      }
    });

    $('#fileUpload').click(function() {
      if($('#fileUpload').hasClass('errorHighlight')) {
        $('#fileUpload').removeClass('errorHighlight');
      }
    });

    $('.submit').on('click', function() {
      if($('#fileUpload').val() == "") {
        $('#fileUpload').addClass('errorHighlight');
        alert('Please select file to upload and post');
        return false;
      }
      var formdata = new FormData(); //instantiate formData object
      var mediaFileName = document.getElementById('fileUpload').files[0]; //get the first File value from the form

      formdata.append("wallUpload", "true"); //then append all form information data to be send via ajax server request
      formdata.append("mediaUpload", mediaFileName);
      formdata.append("contentMessage", $('#contentMessage').val());
      formdata.append("userId", $('#userId').val());
      formdata.append("profileImage", $('#profImg').val().trim());
      formdata.append("profileName", $('#profName').val().trim());
      
      $.ajax({
        url: "controller.php",
        type: "POST",
        data: formdata,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
          $('<div class="activity"></div>').html('<center>Loading...</center>').prependTo('.activities'); //prepare to update activities side-bar
          $('<div class="post-item-card animated slideInUp"></div>').html("<center><strong><span class='fa fa-refresh fa-pulse fa-2x'></span> Posting...</center></strong>").prependTo('.posts');
          $('.submit').text('posting...');
        },
        success: function(jsonDataResponse) {
          // alert(jsonDataResponse);
          jasonData = JSON.parse(jsonDataResponse);
          $('.post-item-card:first').html(jasonData.wallPostFile);
          $(".activity:first").html(jasonData.recentActivity);
          $('.submit').html('Post <span class="fa fa-upload"></span>');
          $('.itemSlider').fadeOut('slow');
          $('#publish-data').focus();
        },
        error: function() {
          alert("Error 404 Url not found");
          $('.submit').html('Post <span class="fa fa-upload"></span>');
        }
      });

    });

    $('.cancelForm').click(function() {
      $('.itemSlider').fadeOut('slow');
      $('#publish-data').focus();
    });

    /*function uploadPostImage() {
      var results = document.getElementById('uploaded_file');
      var mediaFileName = document.getElementById('fileUpload').files[0];

      var formdata = new FormData();
      formdata.append("upload", "true");
      formdata.append("mediaUpload", mediaFileName);
      
      results.innerHTML = "Loading...";

      xhr = new XMLHttpRequest();
      xhr.open("POST", "controller.php", true);

      xhr.onreadystatechange = function() {
        if(xhr.readyState == 4 && xhr.status == 200) {
          jasonData = JSON.parse(xhr.responseText);
          results.innerHTML = jasonData.postImage;
        }
        if(xhr.status == 404) {
          results.innerHTML = "Error 404 Url not found!.";
        }
      }
      setTimeout(function() { //delay for 1000 miliseconds before sending to server
        xhr.send(formdata);
      }, 1000);

    }*/
    
  </script>

<?php } ?>
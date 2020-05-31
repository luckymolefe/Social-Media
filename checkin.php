<?php if(isset($_GET['checkin'])) { 
	$uid = $_GET['uid'];
	$profileImage = $_GET['profileImage'];
	$profileName = $_GET['profileName'];
?>
<div class="checkin">
	<form>
        <div class="input-group input-group-lg">
			<span class="input-group-addon" id="sizing-addon1">@</span>
			<input type="text" class="form-control" id="checkinPlace" autocomplete="off" placeholder="type name of the place" autofocus ondblclick="getPlace()">
			<div class="input-group-btn">
				<button type="button" id="shareCheckin" class="btn btn-primary"><span class="fa fa-map-marker"></span> check-in</button>
			</div>
		</div>
	</form>
	<div id="pop-errMsg" class="text-danger"></div>
</div>
<?php } ?>
<script type="text/javascript">
$('#checkinPlace').focus();

/************************************/
/*$('#checkinPlace').on('click',function() {
	$.post("places.php", {"getplace":"true"}, function(responsedata) {
			$('.layer').html(responsedata).show();
		}
	});
});*/
function getPlace() {
	$('.layer').css('background-color','rgba(0, 0, 0, 0.6)'); //change color for this specific request
	$('.layer').show();
	var xhr = new XMLHttpRequest();
	var urldata = "places.php?getplace=true";
	xhr.open("GET", urldata, true);
	xhr.onreadystatechange = function() {
		if(xhr.readyState == 4 && xhr.status == 200) {
			$('.layer').html(xhr.responseText);
		}
	}
	xhr.send();
}
/***********************************/

$('#shareCheckin').attr('disabled', true);
	$('#checkinPlace').on('keyup',function() {
		if($('#checkinPlace').val() == "" || $('#checkinPlace').val() == " ") {
			$('#shareCheckin').attr('disabled', true);
		} else {
			$('#shareCheckin').attr('disabled', false);
		}
	});
	$('#shareCheckin').click(function() {
		var checkinPlace = $('#checkinPlace').val().trim();
		if(checkinPlace=="") {
			$('#pop-errMsg').removeClass('text-primary').addClass('text-danger').html('<em><center><strong>Please type name of place first!.</strong></center></em>');
			$('#shareCheckin').attr('disabled', true);
			return false;
		}
		/*$('#publish-data').val('');
		$('#publish-data').val('checked-in @'+checkinPlace);
		$('.testSlide').fadeOut('slow');*/
		var userId = "<?php (!empty($uid)) ? print $uid : print null; ?>";
		var profImg = "<?php (!empty($profileImage)) ? print $profileImage : print null; ?>";
		var profName = "<?php (!empty($profileName)) ? print $profileName : print null; ?>";
		var dataString = "checkin=true&location_data="+checkinPlace+"&uid="+userId+"&profImg="+profImg+"&profName="+profName;
		$.ajax({
			url: 'controller.php',
			type: 'GET',
			data: dataString,
			cache: false,
			beforeSend: function() {
				$('<div class="activity"></div>').html('<center>Loading...</center>').prependTo('.activities');
				$('#pop-errMsg').html('<em><center><strong>Please wait processing...</strong></center></em>').removeClass('text-danger').addClass('text-primary');
				$('<div class="post-item-card"></div>').html('<center><strong>Please wait...</strong></center>').prependTo('.posts');
			},
			success: function(responsedata) {
				// alert(responsedata);
				// $("div:last").html(responsedata).appendTo('.activities');
				// var Objdata = JSON.stringify(responsedata);
				var jObjdata = JSON.parse(responsedata);

				$(".activity:first").html(jObjdata['check_ins']); //.append('.activities');
				// var jdata = jdata.message; //.replace(new RegExp('@', 'g'), '- at <span class="fa fa-map-marker text-info"></span> ');
				/*$("div:last").html('<a href="#testActivity"><div class="media"><img src="profile/girlcover.jpg" class="media-object"><div class="media-body"><span class="media-heading">Nicole Bouw</span><span>'+jdata+'</span></div></div></a>').appendTo('.activities');*/
				$('.post-item-card:first').html(jObjdata['post_card']);

				$('#checkinPlace').val(''); //set the messagebox to empty
				$('.itemSlider').fadeOut('slow'); //now hide the panel if successful
			},
			error: function() {
				$('#pop-errMsg').html("<em><center><strong>Error 404: Url not found!</strong></center></em>");
			}
		});

	});
</script>
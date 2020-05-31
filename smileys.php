
<?php

if(isset($_GET['smiley'])) {
	// sleep(2);
	$data = scandir('emoticons/');
	foreach($data as $imgObj) :
		if($imgObj != '.' && $imgObj != '..') : //skip directory up level navigation
?>
<a href="<?php echo $imgObj; ?>" class="emoticon">
	<img src="<?php echo 'emoticons/'.$imgObj; ?>" title="<?php echo $imgObj; ?>" width="25px" />
</a>
<?php 
		endif; 
	endforeach;
}

?>
<script type="text/javascript">
	$('.emoticon').click(function(e) {
		e.preventDefault();
		var emot = $(this).attr('href');
		var newEmot = emot.replace(new RegExp('.png', 'g'), '');
		$('#publish-data').val(function(index,value) {
			return value+" ["+newEmot+"] "; // ["+newEmot+"]"
		});
		$('.itemSlider').fadeOut('slow');
		$('#publish-data').focus();
		$('#publish-post').attr('disabled', false); //enable the post button
	});
</script>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Responsive Email</title>
	</head>
	<body>

		<div class="email-background" style="background-color: #eee; padding: 10px; height: 650px;">
			<div class="email-container" style="max-width: 500px;background-color: #fff;font-family: sans-serif;margin: 0 auto;overflow: hidden;border-radius: 5px;text-align: center;">
				<h1 style="color: #72bcd4;">New Password</h1>
				<!-- <a href="#" style="color: #3087F5;text-decoration: none;">
					<img src="error404.jpg" style="max-width: 100%;" alt="">
				</a> -->
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					Hi <?php echo $username="Lucky Molefe"; ?>,<br><br>
					Your password was successfully reset.<br>Please copy &amp; keep your password safe, do not show it any one.
				</p>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					Use this new password to login and it is recommended to change your password after you login.
				</p>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">
					This is your New password: 
					<strong style="border: 1px solid #72bcd4;color: #405d27;border-radius: 2px;padding: 5px 15px;"><?php echo $new_pass="oUzevY"; ?></strong>
				</p>
				<p style="margin: 20px;font-size: 18px;font-weight: 300px;color: #666;line-height: 1.5;text-align: left;">Thank You</p>
				<!-- <div class="cta" style="margin: 20px;font-weight: bolder;">
					<a href="<?php #echo $url; ?>" style="text-decoration: none;display: inline-block;background-color: #72bcd4;color: #fff; transition: all .5s;padding: 10px 20px 10px;border-radius: 5px;border: solid 1px #eee;">Reset password</a>
				</div> -->
			</div>
			<div class="footer" style="background-color: none;padding: 20px;font-size: 10px;font-family: sans-serif;text-align: center;">
				<a href="#">123 Str, City</a> | <a href="#">Visit Us Here</a><br>
				<span>&copy;2017 All rights reserved.</span>
			</div>
		</div>

	</body>
</html>
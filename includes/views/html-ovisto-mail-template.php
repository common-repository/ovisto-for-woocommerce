<?php
/**
 * Mail Template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1"> <!-- So that mobile will display zoomed in -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- enable media queries for windows phone 8 -->
		<meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->
	</head>
	<body style="margin:0; padding:0;" bgcolor="#F0F0F0" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	    <table style="font-family: Verdana,sans-serif; font-size: 11px; color: #374953; width: 600px;">
			<tr>
				<th>Name:</th>
				<td><?php echo $displayName ?></td>
			</tr>

			<tr>
				<th>Email:</th>
				<td><?php echo $email  ?></td>,
			</tr>

			<tr>
				<th>URL:</th>
				<td><?php echo $url  ?></td>
			</tr>

			<tr>
				<th>Phone:</th>
				<td><?php echo $tel  ?></td>
			</tr>

			<tr>
				<th>Company:</th>
				<td><?php echo $company  ?></td>
			</tr>

			<tr>
				<th>Message:</th>
				<td><?php echo $message  ?></td>
			</tr>
		</table>
	</body>
</html>


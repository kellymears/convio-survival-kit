<?php require ('convio-survival-toolkit/convio-survival-toolkit.php'); ?>

<?php
// instantiate
$convio_survival = new convio_survival;

// you can connect using properties defined in class definition
$convio_survival->connect_to_convio();

// this is the controller call
$convio_survival->process_method();
?>

<!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title>Convio Survival Toolkit</title>
  <link rel="stylesheet" href="foundation4/css/foundation.css" />
  <script src="foundation4/js/vendor/custom.modernizr.js"></script>
</head>

<body>

  <div class="row">
		<div class="large-12 columns">
			<h1>Convio Survival Toolkit</h1>
			<h2 class="subheader">A PHP Utility to Help Make Convio Suck Less</h2>
			<hr />
		</div>
	</div>

	<div class="row">

		<div class="row">
			
			<div class="large-9 columns">
				
				<h3>Getting Started</h3>

				<p>Instantiate the class and feel the raw power:</p>
				<p><kbd>$convio_survival = new convio_survival;</kbd></p>

				<p>Draw a hexagram, creating an eternal, timeless seal with the dark lord himself:</p>
				<p><kbd>$convio_survival->connect_to_convio($host,$short_name,$api_key,$login,$password);</kbd></p>

				<p>Ensure your dominion by securing the Ancient powers of the API</p>
				<p><kbd>$convio_survival->process_method();</kbd></p>

			</div>

			<div class="large-3 columns">
				<div class="row">
					<div class="panel callout radius">
						<p>requires: simpledom, convio-open-api</p>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="large-6 columns">
				
				<h2>API methods</h2>

				<div class="panel">
					<h4>add_to_group</h4>
					<p>Problem it solves: easily adding a fucking person to a fucking group.</p>
					<p><kbd>convio-survival-toolkit.php?<strong>method=add_to_group</strong>&<strong>group_id={group_id}</strong>&<strong>email={email}</strong></kbd></p>
				</div>

				<div class="panel">
					<h4>remove_from_group</h4>
					<p>Problem it solves: easily removing a fucking person to a fucking group.</p>
					<kbd>convio-survival-toolkit.php?<strong>method=remove_from_group</strong>&<strong>group_id={group_id}</strong>&<strong>email={email}</strong></kbd></p>
				</div>

				<div class="panel">
					<h4>update_email</h4>
					<p>Problem it solves: updating someone's email.</p>
					<kbd>convio-survival-toolkit.php?<strong>method=update_email</strong>&<strong>cons_id={constituent_id}</strong>&<strong>email={email}</strong></kbd></p>
				</div>

				<div class="panel">
					<h4>get_congressperson</h4>
					<p>ever wanted easy contact information for a representative? all you need is a zip code.</p>
					<kbd>convio-survival-toolkit.php?<strong>method=get_congressperson</strong>&<strong>zip={zip}</strong></kbd></p>
				</div>

			</div>

			<div class="large-6 columns">
				
				<h2>Do It With Code</h2>
				
				<div class="panel">
					<h4>add_to_group</h4>
					<p>Problem it solves: easily adding a fucking person to a fucking group. <strong>In code.</strong></p>
					<p><kbd>$convio_survival->add_to_group($email, $group_id);</kbd></p>
				</div>

				<div class="panel">
					<h4>remove_from_group</h4>
					<p>Problem it solves: easily removing a fucking person from a fucking group. <strong>In code.</strong></p>
					<p><kbd>$convio_survival->remove_from_group($email, $group_id);</kbd></p>
				</div>

				<div class="panel">
					<h4>update_email</h4>
					<p><kbd>$convio_survival->update_email($email, $cons_id);</kbd></p>
				</div>

				<div class="panel">
					<h4>get_congressperson</h4>
					<p><kbd>$convio_survival->get_congressperson($zip);</kbd></p>
					<p>so, for example<br /><kbd>$convio_survival->get_congressperson(26241);</kbd> returns:</p>
					<pre><?php print_r($convio_survival->get_congressperson(26241)); ?></pre>
				</div>
				

			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<h2>Output</h2>
				<p>This is a live page, try constructing an api call and see the output below.</p>
				<?php 
					echo "<h2>Messages</h2>";
					$convio_survival->return_messages();
					echo "<h2>Errors</h2>";
					$convio_survival->return_errors();

				?>
			</div>
		</div>

	<script>
  document.write('<script src=' +
  ('__proto__' in {} ? 'foundation4/js/vendor/zepto' : 'foundation4/js/vendor/jquery') +
  '.js><\/script>')
  </script>
  
  <script src="foundation4/js/foundation.min.js"></script>

    <script>
    $(document).foundation();
  </script>
</body>
</html>


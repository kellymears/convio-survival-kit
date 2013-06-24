<?php 

// -----------------------------------------------------------------------------
// include, instantiate, configure
// -----------------------------------------------------------------------------

// require the class
require ('convio-survival-toolkit.php');

// instantiate
$convio_survival = new convio_survival;

// configure connection details
$convio_survival->configure_convio($server,$short_name,$api_key,$login_name,$login_password);


// -----------------------------------------------------------------------------
// get constituent data
// -----------------------------------------------------------------------------

$constituent = $convio_survival->get_constituent('hello@kellymears.me');

/* 
	$constituent->cons_id;

	$constituent->name->first;
	$constituent->name->last;
    	$constituent->name->middle;

	$constituent->home_phone;
    	$constituent->email->primary_address;
    	$constituent->email->status;
    	$constituent->email->accepts_email;

    	$constituent->primary_address->state;
    	$constituent->primary_address->street1;
    	$constituent->primary_address->phone;

    	$constituent->lifetime_alert_response_count;
    	$constituent->curr_year_alert_response_count;
    	$constituent->prev_year_alert_response_count;
*/
    

// -----------------------------------------------------------------------------
// get congressional representative data
// -----------------------------------------------------------------------------

$congressperson = $convio_survival->get_congressperson($constituent->primary_address->state, $constituent->districts->cong_dist_id);


// -----------------------------------------------------------------------------
// add to group
// -----------------------------------------------------------------------------

// $add_result = $convio_survival->add_to_group('hello@kellymears.me',1000);
// this could be changed in the future to allow for passing an array of email addresses
// and group id designations...
// ... same for remove from group below


// -----------------------------------------------------------------------------
// remove from group
// -----------------------------------------------------------------------------

// $remove_result = $convio_survival->remove_from_group('hello@kellymears.me',1000);


// -----------------------------------------------------------------------------
// update email
// -----------------------------------------------------------------------------

// $update_email_result = $convio_survival->update_email($constituent->primary_address->email,'newemail@email.com');


// -----------------------------------------------------------------------------
// do api
// -----------------------------------------------------------------------------

// basic security (need auth implementation in convio-survival-toolkit.php SOON)
	
$convio_survival->do_api(); 
$convio_survival->do_api_output();


// -----------------------------------------------------------------------------
// debug methods
// -----------------------------------------------------------------------------
echo "<pre>";
$convio_survival->return_errors();
print_r($convio_survival->return_messages());
echo "</pre>";

?>

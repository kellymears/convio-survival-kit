<?php 
/*

:'######::::::'#######:::::'##::: ##::::'##::::'##::::'####:::::'#######::                            
'##... ##::::'##.... ##:::: ###:: ##:::: ##:::: ##::::. ##:::::'##.... ##:                            
 ##:::..::::: ##:::: ##:::: ####: ##:::: ##:::: ##::::: ##::::: ##:::: ##:                            
 ##:::::::::: ##:::: ##:::: ## ## ##:::: ##:::: ##::::: ##::::: ##:::: ##:                            
 ##:::::::::: ##:::: ##:::: ##. ####::::. ##:: ##:::::: ##::::: ##:::: ##:                            
 ##::: ##:::: ##:::: ##:::: ##:. ###:::::. ## ##::::::: ##::::: ##:::: ##:                            
. ######:::::. #######::::: ##::. ##::::::. ###:::::::'####::::. #######::                            
:......:::::::.......::::::..::::..::::::::...::::::::....::::::.......:::                            
:'######:::::'##::::'##::::'########:::::'##::::'##::::'####::::'##::::'##:::::::'###:::::::'##:::::::
'##... ##:::: ##:::: ##:::: ##.... ##:::: ##:::: ##::::. ##::::: ##:::: ##::::::'## ##:::::: ##:::::::
 ##:::..::::: ##:::: ##:::: ##:::: ##:::: ##:::: ##::::: ##::::: ##:::: ##:::::'##:. ##::::: ##:::::::
. ######::::: ##:::: ##:::: ########::::: ##:::: ##::::: ##::::: ##:::: ##::::'##:::. ##:::: ##:::::::
:..... ##:::: ##:::: ##:::: ##.. ##::::::. ##:: ##:::::: ##:::::. ##:: ##::::: #########:::: ##:::::::
'##::: ##:::: ##:::: ##:::: ##::. ##::::::. ## ##::::::: ##::::::. ## ##:::::: ##.... ##:::: ##:::::::
. ######:::::. #######::::: ##:::. ##::::::. ###:::::::'####::::::. ###::::::: ##:::: ##:::: ########:
:......:::::::.......::::::..:::::..::::::::...::::::::....::::::::...::::::::..:::::..:::::........::
'########:::::'#######::::::'#######:::::'##::::::::::'##:::'##::::'####::::'########:                
... ##..:::::'##.... ##::::'##.... ##:::: ##:::::::::: ##::'##:::::. ##:::::... ##..::                
::: ##::::::: ##:::: ##:::: ##:::: ##:::: ##:::::::::: ##:'##::::::: ##:::::::: ##::::                
::: ##::::::: ##:::: ##:::: ##:::: ##:::: ##:::::::::: #####:::::::: ##:::::::: ##::::                
::: ##::::::: ##:::: ##:::: ##:::: ##:::: ##:::::::::: ##. ##::::::: ##:::::::: ##::::                
::: ##::::::: ##:::: ##:::: ##:::: ##:::: ##:::::::::: ##:. ##:::::: ##:::::::: ##::::                
::: ##:::::::. #######:::::. #######::::: ########:::: ##::. ##::::'####::::::: ##::::                
:::..:::::::::.......:::::::.......::::::........:::::..::::..:::::....::::::::..:::::     

*/


require('simpledom.php');
require('convio-open-api.php');

if(!class_exists("convio_survival")) {
	
	class convio_survival {
			
		public $convio_config = array(); // stores convio connection information
		public $convio_api; 

		public $sunlight_api;

		public $params = array(); // URI parameters
		public $errors = array(); // for displaying errors to user
		public $messages = array(); // for displaying messages to user

		public $constituent; // THE WHOLE POINT OF THIS WHOLE THING, CONVIO

		/* construct array of URI params, connect to convio */
		/*************************************************/
		
		function __construct() {

			// convio
			$this->convio_config['host'] = 'secure3.convio.net'; 
			$this->convio_config['api_key'] = 'api_key'; 
			$this->convio_config['login'] = 'kellymears'; 
			$this->convio_config['password'] = 'Q!W@E#R$T%Y^'; 
			$this->convio_config['short_name'] = 'sdc'; 

			// sunlight
			$this->sunlight_api = '2df0837d05584e2198aaa1a5feb772d4';

			// get uri params
			if ( isset ( $_GET['method'] ) ) $this->params['method'] = $_GET['method'];
			if ( isset ( $_GET['email'] ) ) $this->params['email'] = $_GET['email'];
			if ( isset ( $_GET['group_id'] ) ) $this->params['group_id'] = $_GET['group_id'];
			if ( isset ( $_GET['cons_id'] ) ) $this->params['cons_id'] = $_GET['cons_id']; 
			if ( isset ( $_GET['alert_id'] ) ) $this->params['alert_id'] = $_GET['alert_id']; 
			if ( isset ( $_GET['zip'] ) ) $this->params['zip'] = $_GET['zip']; 
		
		}

		/* connect to convio using their PHP wrapper */
		/*************************************************/
		
		public function connect_to_convio($host = null, $short_name = null, $api_key = null, $login = null, $password = null) {

			if(class_exists('convio_open_api')) $this->convio_api = new convio_open_api;
				else $errors[] = "connect_to_convio(): could not load convio_open_api class from convio-open-api.php";

			if($host === null) {
				if(isset($this->convio_config['host'])) $this->convio_api->host = $this->convio_config['host'];
			}  else $this->convio_api->host = $host;

			if($short_name === null) {
				if(isset($this->convio_config['short_name'])) $this->convio_api->short_name = $this->convio_config['short_name'];
			}  else $this->convio_api->short_name = $short_name;

			if($api_key === null) {
				if(isset($this->convio_config['api_key'])) $this->convio_api->api_key = $this->convio_config['api_key'];
			} else $this->convio_api->api_key = $api_key;

			if($login === null) {
				if(isset($this->convio_config['login'])) $this->convio_api->login_name = $this->convio_config['login'];
			} else $this->convio_api->login_name = $login;

			if($password === null) {
				if(isset($this->convio_config['password'])) $this->convio_api->login_password = $this->convio_config['password'];
			} else $this->convio_api->login_password = $password;

			if(!isset($this->convio_api->host)) $this->errors[] = 'connect_to_convio(): no host';
			if(!isset($this->convio_api->short_name)) $this->errors[] = 'connect_to_convio(): no short_name';
			if(!isset($this->convio_api->api_key)) $this->errors[] = 'connect_to_convio(): no api_key';
			if(!isset($this->convio_api->login_name)) $this->errors[] = 'connect_to_convio(): no login_name';
			if(!isset($this->convio_api->login_password)) $this->errors[] = 'connect_to_convio(): no login_password';

			if(!$this->errors) $this->messages[] = 'convio configured successfully ...we hope.';


		}

		/* CONTROLLER */
		/*************************************************/
		public function process_method() {
			if( $this->params['method'] == 'add_to_group' ) $this->add_to_group($this->params['email'],$this->params['group_id']); 
				elseif ( $this->params['method'] == 'remove_from_group' ) $this->remove_from_group($this->params['email'],$this->params['group_id']);
				elseif ( $this->params['method'] == 'update_email' ) $this->update_email($this->params['email'], $this->params['cons_id']); 
				elseif ( $this->params['method'] == 'get_congressperson') $this->get_congressperson($this->params['zip']);
				elseif ( $this->params['method'] == 'take_action') $this->take_action($this->params['alert_id'], $this->params['email'], $this->params['phone']);  
					else $this->errors[] = "process_method(): no method param or invalid method param"; 
		}

		/* add user to group based on email */ 
		/*************************************************/
		public function add_to_group($email, $group_id) {
			if( (isset($email)) && (isset($group_id)) ) {
				$convio_data['add_group_ids'] = $group_id;
				$convio_data['primary_email'] = $email;
				$response = $this->convio_api->call('SRConsAPI_createOrUpdate', $convio_data);
				if($response) $this->messages[] = $response;
					else $this->errors[] = 'add_to_group(): no response from convio';
			} else $this->errors[] = "add_to_group(): missing primary email or group_id params";
		}

		/* remove user from group based on email */ 
		/*************************************************/
		private function remove_from_group($email, $group_id) {
			if( (isset($email)) && (isset($group_id)) ) {
				$convio_data['remove_group_ids'] = $group_id;
				$convio_data['primary_email'] = $email;
				$response = $this->convio_api->call('SRConsAPI_createOrUpdate', $convio_data);
				if($response) $this->messages[] = $response;
					else $this->errors[] = 'remove_from_grup(): no response from convio';
			} else $this->errors[] = "remove_from_group(): missing primary email or group_id params";
		}

		/* update user email */ 
		/*************************************************/
		private function update_email($email, $cons_id) {
			if( (isset($email)) && (isset($cons_id)) ) {
				$convio_data['cons_id'] = $cons_id;
				$convio_data['primary_email'] = $email;
				$response = $this->convio_api->call('SRConsAPI_createOrUpdate', $convio_data);
				if($response) {
					print "<pre>";
						print_r($response);
					print "</pre>";
				} else $this->errors[] = 'update_email(): no response from convio';
			} else $this->errors[] = "update_email(): missing primary email or group_id params";
		}

		/* get legislative contact information */ 
		/*************************************************/
		
		public function get_congressperson($zip, $api_call = 0) {
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://congress.api.sunlightfoundation.com/districts/locate?zip=' . $zip .'&apikey='. $this->sunlight_api);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$ch_d = json_decode(curl_exec($ch));
			curl_close($ch);

			if($ch_d->results) {
				$this->constituent['congressperson']['state'] = $ch_d->results[0]->state;
				$this->constituent['congressperson']['district'] = $ch_d->results[0]->district;
			} else $this->errors[] = 'get_legislative_contact(): could not get state or district data';

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://congress.api.sunlightfoundation.com/legislators?district='. $this->constituent['congressperson']['district'] .'&state='. $this->constituent['congressperson']['state'] .'&apikey='. $this->sunlight_api);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$ch_d = json_decode(curl_exec($ch));
			curl_close($ch);

			if($ch_d->results) {
				$this->constituent['congressperson']['first_name'] = $ch_d->results[0]->first_name;
				$this->constituent['congressperson']['last_name'] = $ch_d->results[0]->last_name;
				$this->constituent['congressperson']['gender'] = $ch_d->results[0]->gender;
				$this->constituent['congressperson']['party'] = $ch_d->results[0]->party;
				$this->constituent['congressperson']['twitter_id'] = $ch_d->results[0]->twitter_id;
				$this->constituent['congressperson']['twitter_link'] = 'http://twitter.com/'. $ch_d->results[0]->twitter_id;
				$this->constituent['congressperson']['facebook_id'] = $ch_d->results[0]->facebook_id;
				$this->constituent['congressperson']['facebook_link'] = 'http://facebook.com/'. $this->constituent['congressperson']['facebook_id'];
				$this->constituent['congressperson']['phone'] = $ch_d->results[0]->phone;
				$this->constituent['congressperson']['website_link'] = $ch_d->results[0]->website;
				$this->constituent['congressperson']['govtrack_id'] = $ch_d->results[0]->govtrack_id;
				$this->constituent['congressperson']['thomas_id'] = $ch_d->results[0]->thomas_id;
				$this->constituent['congressperson']['votesmart_id'] = $ch_d->results[0]->votesmart_id;
				$this->constituent['congressperson']['fec_ids'] = $ch_d->results[0]->fec_ids;
			} else $this->errors[] = 'get_legislative_contact(): could not get legislators contact data';

			return $this->constituent['congressperson'];

		}


		/* get constituent information */ 
		/*************************************************/
		
		private function get_constituent() {

		}

		/* advocacy */ 
		/*************************************************/
		
		private function take_action($alert_id = null) {

			$convio_data['alert_type'] = 'action';
			$convio_data['subject'] = 'subject';

			if($alert_id === null) $convio_data['alert_id'] = $this->params['alert_id']; 
				elseif(isset($alert_id)) $convio_data['alert_id'] = $alert_id;
					else $this->errors[] = 'take_action(): missing alert_id';

				if($this->params['email']) $convio_data['email'] = $this->params['email'];
					else $this->errors[] = 'take_action(): missing email';

				if($this->params['phone']) $convio_data['phone'] = $this->params['phone'];
					else $this->errors[] = 'take_action(): missing phone';

			$response = $this->convio_api->call('SRAdvocacyAPI_takeAction', $convio_data);
			$messages[] = $response;
		}

		/* get bill data */
		/*************************************************/
		
		private function get_bill_data() {
			// ....


			// ....
		}

		/* error reporting */
		/*************************************************/
		
		public function return_errors() {
			if(!empty($this->errors)) return $this->errors;
				else print "no errors, you mensch.";

			$this->messages[] = 'return_errors() called';
		}

		public function return_messages() {
			$this->messages[] = 'return_messages() called.. constituent record follows:';
			$this->messages[] = $this->constituent;
			if(!empty($this->messages)) return $this->messages;
				else print "convio survival toolkit remains unopened.";

		}
	}
}

/**
* For the brave souls who get this far: You are the chosen ones,
* the valiant knights of programming who toil away, without rest,
* fixing our most awful code. To you, true saviors, secret diamonds,
* I say this: never gonna give you up, never gonna let you down,
* never gonna run around and desert you. Never gonna make you cry,
* never gonna say goodbye. Never gonna tell a lie and hurt you.
*/

/*
          _____                     __          
        _/ ____\  __ __     ____   |  | __      
        \   __\  |  |  \  _/ ___\  |  |/ /      
         |  |    |  |  /  \  \___  |    <       
         |__|    |____/    \___  > |__|_ \      
                               \/       \/      
                                                
           ___.__.   ____    __ __              
          <   |  |  /  _ \  |  |  \             
           \___  | (  <_> ) |  |  /             
           / ____|  \____/  |____/              
           \/                                   
                                   .__          
  ____     ____     ____   ___  __ |__|   ____  
_/ ___\   /  _ \   /    \  \  \/ / |  |  /  _ \ 
\  \___  (  <_> ) |   |  \  \   /  |  | (  <_> )
 \___  >  \____/  |___|  /   \_/   |__|  \____/ 
     \/                \/                       

*/

?>

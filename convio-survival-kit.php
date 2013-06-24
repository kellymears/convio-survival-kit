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

	--------------------------------------------------------------------------

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301 USA

	--------------------------------------------------------------------------

*/

error_reporting(E_ALL);

require_once('simpledom.php'); //simpledom
require_once('convio-open-api.php'); // convio supplied php wrapper

if(!class_exists("convio_survival")) {
	
	class convio_survival {
			
		public $convio_config = array(); // stores convio connection information
		public $convio_api; 

		public $sunlight_api;

		public $params = array(); // URI parameters
		public $errors = array(); // for displaying errors to user
		public $messages = array(); // for displaying messages to user

		public $constituent; // THE WHOLE POINT OF THIS WHOLE THING, CONVIO
		public $api_output;
		
		function __construct() {

			// sunlight
			$this->sunlight_api = '2df0837d05584e2198aaa1a5feb772d4';

			// uri self destruction
			if ( isset ( $_GET['method'] ) ) $this->params['method'] = $_GET['method'];
			if ( isset ( $_GET['json'] ) ) $this->params['json'] = $_GET['json'];
			if ( isset ( $_GET['email'] ) ) $this->params['email'] = $_GET['email'];
			if ( isset ( $_GET['group_id'] ) ) $this->params['group_id'] = $_GET['group_id'];
			if ( isset ( $_GET['cons_id'] ) ) $this->params['cons_id'] = $_GET['cons_id']; 
			if ( isset ( $_GET['alert_id'] ) ) $this->params['alert_id'] = $_GET['alert_id']; 
			if ( isset ( $_GET['zip'] ) ) $this->params['zip'] = $_GET['zip']; 
		
			$this->messages[] = 'convio_survival instantiated';

		}

		/* connect to convio using their PHP wrapper */
		/*************************************************/
		
		public function configure_convio($host = null, $short_name = null, $api_key = null, $login = null, $password = null) {

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
		public function do_api() {
			
			if( $this->params['method'] == 'add_to_group' ) 
				$this->add_to_group($this->params['email'],$this->params['group_id']); 

			elseif ( $this->params['method'] == 'remove_from_group' ) 
				$this->remove_from_group($this->params['email'],$this->params['group_id']);

			elseif ( $this->params['method'] == 'update_email' ) 
				$this->update_email($this->params['email'], $this->params['cons_id']); 

			elseif ( $this->params['method'] == 'estimate_congressperson') 
				$this->estimate_congressperson($this->params['zip']);

			elseif ( $this->params['method'] == 'take_action') 
				$this->take_action($this->params['alert_id'], $this->params['email'], $this->params['phone']);  

			elseif ( $this->params['method'] == 'get_cons' ) 
				$this->get_constituent($this->params['cons']); 

			elseif ( $this->params['method'] == 'get_cons_congressperson' ) 
				$this->get_constituent_congressperson($this->params['email']);
					
					else $this->errors[] = "process_method(): no method param or invalid method param"; 
		}

		/* get constituent */
		/*************************************************/
		public function get_constituent($email) {
			if( isset($email) ) {
				$this->messages[] = "get_constituent() is called";
				$convio_data['primary_email'] = $email;
				$response = $this->convio_api->call('SRConsAPI_getUser', $convio_data);
				if($response) {
					foreach($response as $resp_obj) {
						if($this->params['method'] == 'get_cons') return json_encode($resp_obj);
						$this->messages[] = 'constituent data follows:';
						$this->messages[] = $resp_obj;
						return $resp_obj;
					}
				} else $this->errors[] = 'get_user(): no response from convio';
			} else $this->errors[] = "get_user(): missing primary email param";
		}

		/* add user to group based on email */ 
		/*************************************************/
		public function add_to_group($email, $group_id) {
			if( (isset($email)) && (isset($group_id)) ) {
				$convio_data['add_group_ids'] = $group_id;
				$convio_data['primary_email'] = $email;
				$response = $this->convio_api->call('SRConsAPI_createOrUpdate', $convio_data);
				if($response) {
					$this->messages[] = $response;
					return $response;
				} else $this->errors[] = 'add_to_group(): no response from convio';
			} else $this->errors[] = "add_to_group(): missing primary email or group_id params";
		}

		/* remove user from group based on email */ 
		/*************************************************/
		private function remove_from_group($email, $group_id) {
			if( (isset($email)) && (isset($group_id)) ) {
				$convio_data['remove_group_ids'] = $group_id;
				$convio_data['primary_email'] = $email;
				$response = $this->convio_api->call('SRConsAPI_createOrUpdate', $convio_data);
				if($response) {
					$this->messages[] = $response;
					return $response;
				} else $this->errors[] = 'remove_from_grup(): no response from convio';
			} else $this->errors[] = "remove_from_group(): missing primary email or group_id params";
		}

		/* get_action_count */ 
		/*************************************************/
		function get_action_count($alert_id) {
			
			$this->convio_data['alert_id'] = $alert_id;
			$this->convio_data['alert_type'] = 'action';
			
			$response = $this->convio_api->call('SRAdvocacyAPI_getAdvocacyAlert', $this->convio_data);
			if($response) $this->messages[] = "getting action count";
				else $this->errors[] = "get_action_count(): no result from convio";
			foreach($response as $resp_obj) {
				$this->messages[] = $resp_obj->alert->interactionCount .' signers of '. $alert_id;
				return $resp_obj->alert->interactionCount;
			}
			
		}

		/* update user email */ 
		/*************************************************/
		private function update_email($email, $cons_id) {
			if( (isset($email)) && (isset($cons_id)) ) {
				$convio_data['cons_id'] = $cons_id;
				$convio_data['primary_email'] = $email;
				$response = $this->convio_api->call('SRConsAPI_createOrUpdate', $convio_data);
				if($response) $this->messages[] = $response;
					else $this->errors[] = 'update_email(): no response from convio';
			} else $this->errors[] = "update_email(): missing primary email or group_id params";
		}

		/* get constituent congressional representative */
		/*************************************************/
		public function get_constituent_congressperson($email) {
			if( isset($email) ) {
				$this->messages[] = "get_constituent_congressperson() is called";
				$record = $this->get_constituent($email);
				if($record) {
					$congressperson = $this->get_congressperson($record->primary_address->state, $record->primary_address->cong_dist_id);
					if($this->params['json'] == 'true') $this->api_output[] = json_encode($congressperson);
						else return $congressperson;
				} else $this->errors[] = "get_constituent_congressperson(): call to get_constituent()";
			} else $this->errors[] = "get_constituent_congressperson(): missing primary email param";
		}
		
		/* get legislative contact information */ 
		/*************************************************/
		public function get_congressperson($state,$district) {

			$messages[] = "get_congressperson is called";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://congress.api.sunlightfoundation.com/legislators?district='. $district .'&state='. $state .'&apikey='. $this->sunlight_api);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$ch_d = json_decode(curl_exec($ch));
			curl_close($ch);

			if($ch_d->results) {
				$congressperson['first_name'] = $ch_d->results[0]->first_name;
				$congressperson['last_name'] = $ch_d->results[0]->last_name;
				$congressperson['gender'] = $ch_d->results[0]->gender;
				$congressperson['party'] = $ch_d->results[0]->party;
				$congressperson['twitter_id'] = $ch_d->results[0]->twitter_id;
				$congressperson['twitter_link'] = 'http://twitter.com/'. $ch_d->results[0]->twitter_id;
				$congressperson['facebook_id'] = $ch_d->results[0]->facebook_id;
				$congressperson['facebook_link'] = 'http://facebook.com/'. $congressperson['facebook_id'];
				$congressperson['phone'] = $ch_d->results[0]->phone;
				$congressperson['website_link'] = $ch_d->results[0]->website;
				$congressperson['govtrack_id'] = $ch_d->results[0]->govtrack_id;
				$congressperson['thomas_id'] = $ch_d->results[0]->thomas_id;
				$congressperson['votesmart_id'] = $ch_d->results[0]->votesmart_id;
				$congressperson['fec_ids'] = $ch_d->results[0]->fec_ids;
				$this->messages[] = "received congressional contact data:";
			} else $this->errors[] = 'get_congressperson(): could not get legislators contact data';

			if($congressperson) {
				$this->messages[] = $congressperson;
				return $congressperson;
			} else $this->errors[] = 'get_congressperson(): could not get legislators contact data';

		}

		/* no address? no problem! well, kind of a problem */ 
		/*************************************************/
		public function estimate_congressperson($zip) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://congress.api.sunlightfoundation.com/districts/locate?zip=' . $zip .'&apikey='. $this->sunlight_api);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$ch_d = json_decode(curl_exec($ch));
			curl_close($ch);

			if($ch_d->results) {
				$congressperson['state'] = $ch_d->results[0]->state;
				$congressperson['district'] = $ch_d->results[0]->district;
				$this->messages[] = "received state and district data estimation:";
				$this->messages[] = $congressperson;
				$this->api_output[] = $this->get_congressperson($congressperson['state'],$congressperson['district']);
			} else $this->errors[] = 'estimate_district(): could not get state or district data';
		}

		/* take action */ 
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
			$messages[] = "convio call made, response follows:";
			$messages[] = $response;
		}

		/* errors, messages, api output */
		/*************************************************/
		
		public function return_errors() {
			$this->messages[] = 'return_errors() called';
			if(!empty($this->errors)) return $this->errors;
				else print "no errors, you mensch.";
		}

		public function return_messages() {
			$this->messages[] = 'return_messages() called.';
			if(!empty($this->messages)) return $this->messages;
				else print "convio survival toolkit remains unopened.";

		}

		public function do_api_output() {
			foreach($this->api_output as $output) { 
				$display = $output; 
			}
			if($display) {
				$this->messages[] = 'api output printed';
				print stripslashes($display);
			}
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

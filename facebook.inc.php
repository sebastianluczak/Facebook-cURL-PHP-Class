<?php
/*
 * FB login and session handler class
 * requires: PHP>=5.x, cURL, fwrite permissions for cookies.txt by Apache user
 * 
 * 2012 Sebastian Łuczak <sebastian.m.luczak@gmail.com>
 * MIT/BSD licensed
 * 
 */
    Class Facebook {
    	protected $_base_url = "http://www.facebook.com/login.php?login_attempt=1";
		protected $_post_url = "http://www.facebook.com/ajax/messaging/send.php?__a=1";
		protected $_agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7";
		protected $_curl;
		protected $_fb_dtsg;
		
		public function __construct(){
			$this->_curl = curl_init();
		}
		
		public function __destruct(){
			$curl = $this->_curl;
			curl_close($curl);
			$this->_curl = NULL;
			$this->_fb_dtsg = NULL;
		}
		
		public function login($username,$password){
			$curl = $this->_curl;
			$agent = $this->_agent;
			
			$reffer = "http://www.facebook.com/login.php";
			$ch = $curl;
			curl_setopt($ch, CURLOPT_URL,"https://login.facebook.com/login.php");
			curl_setopt($ch, CURLOPT_USERAGENT, $agent);
			curl_setopt($ch, CURLOPT_COOKIEFILE, "./cookies.txt");
			curl_setopt($ch, CURLOPT_COOKIEJAR, "./cookies.txt");
			curl_setopt($ch, CURLOPT_REFERER, $reffer);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "charset_test=%E2%82%AC%2C%C2%B4%2C%E2%82%AC%2C%C2%B4%2C%E6%B0%B4%2C%D0%94%2C%D0%84&version=1.0&return_session=0&charset_test=%E2%82%AC%2C%C2%B4%2C%E2%82%AC%2C%C2%B4%2C%E6%B0%B4%2C%D0%94%2C%D0%84&email=".$username."&pass=".$password."");
			$html = curl_exec($ch);
			$this->SetDtsg($html);
			return $html;
		}
		
		/*
		 * Sends private message to user on Facebook
		 * @param {INT} fbuser_id ID of FB user that we want to send PM
		 * @param {String} message_body Full text of message
		 * @param {INT} user_id Our own ID of currently logged user
		 */
		public function send_message($fbuser_id,$message_body,$user_id){
			$ch = $this->_curl;
			$dtsg = $this->GetDtsg();
			$agent = $this->_agent;
			$reffer = "http://www.facebook.com/";
			curl_setopt($ch, CURLOPT_URL,$this->_post_url);
			curl_setopt($ch, CURLOPT_USERAGENT, $agent);
			curl_setopt($ch, CURLOPT_COOKIEFILE, "./cookies.txt");
			curl_setopt($ch, CURLOPT_COOKIEJAR, "./cookies.txt");
			curl_setopt($ch, CURLOPT_REFERER, $reffer);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POSTREDIR, 2);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("application/x-www-form-urlencoded"));
			curl_setopt($ch, CURLOPT_POSTFIELDS, "forward_msgs=&body=".urlencode($message_body)."&action=send&recipients[0]=".$fbuser_id."&force_sms=false&__user=".$user_id."&phstamp=165816812097926886125&fb_dtsg=".$dtsg);
			$html = curl_exec($ch);
			return $html;
		}

		public function post_status(){
			
		}

		private function SetDtsg($source){
			$fbDtsg = substr($source, strpos($source, "name=\"fb_dtsg\""));
  			$fbDtsg = substr($fbDtsg, strpos($fbDtsg, "value=") + 7);
  			$fbDtsg = substr($fbDtsg, 0, strpos($fbDtsg, "\""));
			$this->_fb_dtsg = $fbDtsg;
		}

		private function GetDtsg(){
			return $this->_fb_dtsg;
		}

		public function retrieve_URL($url){
			// function to retrieve url with logged FB user via cURL
			$curl = $this->_curl;
			// here operate with $curl variable
			$something = "test";
			return $something;
		}
		
    }
?>
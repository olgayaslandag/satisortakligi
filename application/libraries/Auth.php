<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Auth {

	public function __construct(){

		if( !isset($_SESSION["login"]) || !$_SESSION["login"] ){

			if(isset($_SERVER['REDIRECT_QUERY_STRING'])){
				if( !strstr($_SERVER['REDIRECT_QUERY_STRING'], 'User/login') ){
					redirect(base_url('User/login'), 'refresh');
				}
			} else {
				redirect(base_url('User/login'), 'refresh');
			}

		} else {

			if(isset($_SERVER['REDIRECT_QUERY_STRING'])){
				if( strstr($_SERVER['REDIRECT_QUERY_STRING'], 'User/login') ){
					redirect(base_url(), 'refresh');
				}
			}

		}


	}
	
}
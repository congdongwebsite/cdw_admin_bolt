<?php

defined( 'ABSPATH' ) || exit;

class DirectAdminClient {
	private $host;

	public function __construct() {
		$this->host = rtrim( DA_HOST, '/' );

	}

	private function request( $endpoint, $method = 'GET', $data = [] ) {
		$url = $this->host . $endpoint;
		$ch  = curl_init();

		write_syslog( __METHOD__ . " request payload: $url payload: " . print_r( $data, true ) );

		if ( $method === 'GET' && ! empty( $data ) ) {
			$url .= '?' . http_build_query( $data );
		}

		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_USERPWD, DA_USER . ":" . DA_PASSWORD );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );

		if ( $method === 'POST' ) {
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $data ) );
		}

		$response = curl_exec( $ch );

		if ( curl_errno( $ch ) ) {
			throw new Exception( "cURL Error: " . curl_error( $ch ) );
		}

		curl_close( $ch );
		parse_str( $response, $result );

		return $result;
	}

	public function listAdmins() {
		return $this->request( "/CMD_API_SHOW_ADMINS" );
	}

	public function listResellerPackages() {
		return $this->request( "/CMD_API_PACKAGES_RESELLER" );
	}


	public function listResellers() {
		return $this->request( "/CMD_API_SHOW_RESELLERS" );
	}


	public function listIPs() {
		return $this->request( "/CMD_API_SHOW_RESELLER_IPS" );
	}

	public function changeResellerPassword( $username, $newPassword ) {
		$data = [
			'username' => $username,
			'passwd'   => $newPassword,
			'passwd2'  => $newPassword,
			'api'      => 'yes',
		];

		$response = $this->request( "/CMD_API_USER_PASSWD", 'POST', $data );
		error_log( '[changeResellerPassword] Response: ' . print_r( $response, true ) );

		return $response;
	}

	public function generateUniqueUsername( $name ) {
		$primary_domain = 'hosting.local';

		$args  = [
			'post_type'      => 'customer-hosting',
			'posts_per_page' => - 1,
			'fields'         => 'ids',
		];
		$posts = get_posts( $args );

		$created_usernames = [];
		foreach ( $posts as $post_id ) {
			$username = get_post_meta( $post_id, 'user', true );
			if ( $username ) {
				$created_usernames[] = strtolower( $username );
			}
		}

		$base_username = strtolower( preg_replace( '/[^a-z0-9]/i', '', $name ) );
		$username      = $base_username;
		$counter       = 1;

		while ( in_array( $username, $created_usernames, true ) ) {
			$username = $base_username . $counter;
			$counter ++;
		}

		$full_domain = $username . $primary_domain;

		return [
			'username' => $username,
			'domain'   => $full_domain,
		];
	}


	public function createReseller( $username, $email, $passwd, $domain, $package, $extra = [] ) {
		$data = [
			'action'   => 'create',
			'add'      => 'Submit',
			'username' => $username,
			'email'    => $email,
			'passwd'   => $passwd,
			'passwd2'  => $passwd,
			'domain'   => $domain,
			'package'  => $package
		];

		$data = array_merge( $data, $extra );

		$response = $this->request( '/CMD_API_ACCOUNT_RESELLER', 'POST', $data );
		error_log( '[createReseller] Response: ' . print_r( $response, true ) );

		return $response;
	}
}

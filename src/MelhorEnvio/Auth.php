<?php
	namespace MelhorEnvio;

	use Exception;

	class Auth {
		private const URL_AUTH = 'https://www.melhorenvio.com.br/api/v2/me';
		protected static $auth = false;
		private static $api_token;

		public static function init($api_token) {
			self::$api_token = $api_token;
		}

		protected static function http_header() {
			return ['content-type: application/json',
			        'cache-control: no-cache',
			        'accept: application/json',
			        'authorization: ' . self::$api_token];
		}

		/**
		 * Realiza a autenticação no servidor da Melhor Envio
		 *
		 * @return mixed
		 * @throws Exception
		 */
		protected static function auth() {
			if (empty(self::$api_token)) {
				throw new Exception('API_TOKEN deve ser informada... chamar função init($api_token)');
			}

			if (strpos(self::$api_token, 'Bearer ') !== 0) {
				throw new Exception('API_TOKEN deve iniciar com "Bearer "');
			}

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, self::URL_AUTH);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($ch, CURLOPT_ENCODING, '');
			curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_HTTPHEADER, self::http_header());

			$result = json_decode(curl_exec($ch), true);
			$err = curl_error($ch);
			curl_close($ch);

			if ($err || isset($result['message']) && $result['message'] === 'Unauthenticated.') {
				self::$auth = false;

				throw new Exception(['name' => 'ERROR em autenticação Melhor Envio', 'error' => $err . json_encode($result), 'vars' => get_defined_vars()]);
			} else {
				self::$auth = true;

				return $result;
			}
		}
	}
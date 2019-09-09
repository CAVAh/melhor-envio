<?php
	namespace MelhorEnvio;

	class Auth {
		private const URL_AUTH = 'https://www.melhorenvio.com.br/api/v2/me';
		protected static $auth = false;
		public static $api_token;

		public static function init($api_token) {
			self::$api_token = $api_token;
		}

		/**
		 * Realiza a autenticação no servidor da Melhor Envio
		 *
		 * @return mixed
		 * @throws MelhorEnvioException
		 */
		protected static function authenticate() {
			if (empty(self::$api_token)) {
				throw new MelhorEnvioException('API_TOKEN deve ser informada... chamar função init($api_token)');
			}

			if (strpos(self::$api_token, 'Bearer ') !== 0) {
				throw new MelhorEnvioException('API_TOKEN deve iniciar com "Bearer "');
			}

			list($result, $err) = Util::curl(self::URL_AUTH, 'GET');

			if ($err || isset($result['message']) && stripos($result['message'], 'unauthenticated') !== false) {
				self::$auth = false;

				throw new MelhorEnvioException(['name' => 'ERROR em autenticação Melhor Envio', 'error' => $err . json_encode($result), 'vars' => get_defined_vars()]);
			} else {
				self::$auth = true;

				return $result;
			}
		}
	}
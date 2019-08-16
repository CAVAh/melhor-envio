<?php
	namespace MelhorEnvio;

	use Exception;

	class MelhorEnvio {
		private const URL_AUTH = 'https://www.melhorenvio.com.br/api/v2/me';
		private const URL_CALCULATE = 'https://www.melhorenvio.com.br/api/v2/me/shipment/calculate';
		private static $api_token;
		private static $autenticado = false;
		private static $result;

		public static function init($api_token) {
			self::$api_token = $api_token;
		}

		private static function http_header() {
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
		private static function autenticar() {
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
				self::$autenticado = false;

				throw new Exception(['name' => 'ERROR em autenticação Melhor Envio', 'error' => $err . json_encode($result), 'vars' => get_defined_vars()]);
			} else {
				self::$autenticado = true;

				return $result;
			}
		}

		/**
		 * @param From         $from     Endereço do Remetente
		 * @param To           $to       Endereço do Destinatário
		 * @param Product[]    $products Lista de Produtos
		 * @param Package|null $package  Pacote
		 * @param Options|null $options  Opções de Envio
		 *
		 * @return bool|mixed
		 *
		 * @throws Exception
		 */
		public static function calcularFrete($from, $to, $products, $package = null, $options = null) {
			if (empty(self::$api_token)) {
				throw new Exception('API_TOKEN deve ser informada... chamar função init($api_token)');
			}

			if (strpos(self::$api_token, 'Bearer ') !== 0) {
				throw new Exception('API_TOKEN deve iniciar com "Bearer "');
			}

			if (!self::$autenticado) {
				self::autenticar();
			}

			if (is_null($options)) {
				$options = new Options();
			}

			$oJson = ['from' => array_filter($from->__toArray()), 'to' => array_filter($to->__toArray())];

			if (!is_null($package)) {
				$oJson['package'] = array_filter($package->__toArray());
			}

			if (!is_null($products)) {
				foreach ($products as $product) {
					$product->convertWeightInKG();
					$product->convertLengthInCM();
					$oJson['products'][] = array_filter($product->__toArray());
				}
			}

			$oJson['options'] = $options->__toArray();

			if (!is_null($options->getServices())) {
				$oJson['services'] = $options->getServices();
			}

			$json = json_encode($oJson);
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, self::URL_CALCULATE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_ENCODING, '');
			curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_HTTPHEADER, self::http_header());
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

			$result = curl_exec($ch);
			$err = curl_error($ch);

			curl_close($ch);

			if ($err) {
				throw new Exception(['name' => 'Error cURL Melhor Envio', 'error' => $err, 'result' => $result, 'vars' => get_defined_vars()]);
			} else {
				$rJson = json_decode($result, true);
				$count = count($rJson);
				$companies = $options->getCompanies();
				$filter_company = !is_null($companies);

				for ($i = 0; $i < $count; $i++) {
					if (isset($rJson[$i]['error'])) {
						unset($rJson[$i]);
					} elseif ($filter_company) {
						if (!in_array($rJson[$i]['company']['id'], $options->getCompanies())) {
							unset($rJson[$i]);
						}
					}
				}

				if(empty($rJson)) {
					throw new Exception(['name' => 'Error JSON Melhor Envio', 'error' => $err, 'result' => $result, 'vars' => get_defined_vars()]);
				}

				self::$result = $rJson;

				return $rJson;
			}
		}

		public static function getResult() {
			return self::$result;
		}
		
		public static function getSimpleResult() {
			$result = self::getResult();
			$simple_result = [];

			foreach ($result as $item) {
				$simple_result[] = [
					'company' => $item['company']['name'] . ' ' . $item['name'],
					'price' => $item['price'],
					'currency' => $item['currency'],
					'delivery' => [
						'min' => $item['delivery_range']['min'],
						'max' => $item['delivery_range']['max']
					]
				];
			}

			return $simple_result;
		}
	}
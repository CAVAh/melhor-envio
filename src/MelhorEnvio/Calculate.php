<?php
	namespace MelhorEnvio;

	class Calculate extends Auth {
		private const URL_CALCULATE = 'https://www.melhorenvio.com.br/api/v2/me/shipment/calculate';
		private static $result;

		/**
		 * @param From         $from     Endereço do Remetente
		 * @param To           $to       Endereço do Destinatário
		 * @param Product[]    $products Lista de Produtos
		 * @param Package|null $package  Pacote
		 * @param Options|null $options  Opções de Envio
		 *
		 * @return bool|mixed
		 *
		 * @throws MelhorEnvioException
		 */
		public static function calcularFrete($from, $to, $products, $package = null, $options = null) {
			if (!parent::$auth) {
				parent::authenticate();
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
					Util::convertProductWeightInKG($product);
					Util::convertProductLengthInCM($product);
					$oJson['products'][] = array_filter($product->__toArray());
				}
			}

			$oJson['options'] = $options->__toArray();

			if (!is_null($options->getServices())) {
				$oJson['services'] = $options->getServices();
			}

			$json = json_encode($oJson);
			list($result, $err) = Util::curl(self::URL_CALCULATE, 'POST', $json);

			if ($err) {
				throw new MelhorEnvioException(['name' => 'Error cURL Melhor Envio', 'error' => $err, 'result' => $result, 'vars' => get_defined_vars()]);
			}

			$count = count($result);
			$companies = $options->getCompanies();
			$filter_company = !is_null($companies);

			for ($i = 0; $i < $count; $i++) {
				if (isset($result[$i]['error']) || $filter_company && !in_array($result[$i]['company']['name'], $options->getCompanies())) {
					unset($result[$i]);
				}
			}

			if(empty($result)) {
				throw new MelhorEnvioException(['name' => 'Error JSON Melhor Envio', 'error' => $err, 'result' => $result, 'vars' => get_defined_vars()]);
			}

			self::$result = $result;

			return $result;
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
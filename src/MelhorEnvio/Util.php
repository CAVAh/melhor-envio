<?php
	namespace MelhorEnvio;

	class Util {
		private static function http_header() {
			return ['content-type: application/json',
				'cache-control: no-cache',
				'accept: application/json',
				'authorization: ' . Auth::$api_token];
		}

		public static function curl($url, $method, $data_post = '') {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
			curl_setopt($ch, CURLOPT_ENCODING, '');
			curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_HTTPHEADER, self::http_header());

			if($method === 'POST' && !empty($data_post)) {
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_post);
			}

			$result = json_decode(curl_exec($ch), true);
			$err = curl_error($ch);
			curl_close($ch);

			return [$result, $err];
		}

		/**
		 * Converte o peso para KG de acordo com a unidade atual.
		 *
		 * @param float|int  $productWeight      Peso do produto
		 * @param string     $productWeightClass Unidade de peso do produto
		 *
		 * @return float|int Peso em KG
		 */
		public static function convertWeightKG($productWeight, $productWeightClass) {
			switch ($productWeightClass) {
				case "kg":
				case "l":
					$coversionRate = 1;
					break;
				case "g":
				case "ml":
					$coversionRate = 0.001;
					break;
				case "lb":
					$coversionRate = 0.453592;
					break;
				case "oz":
					$coversionRate = 0.0283495231;
					break;
				default:
					$coversionRate = 1;
			}

			return $productWeight * $coversionRate;
		}

		/**
		 * Converte a dimensão (largura, altura e comprimento) para CM de acordo com a unidade atual.
		 *
		 * @param float|int  $productLength      Dimensão do produto
		 * @param string     $productLengthClass Unidade de dimensão do produto
		 *
		 * @return float|int Dimensão em CM
		 */
		public static function convertLengthCM($productLength, $productLengthClass) {
			switch ($productLengthClass) {
				case "in":
					$coversionRate = 2.54;
					break;
				case "mm":
					$coversionRate = 0.1;
					break;
				case "cm":
					$coversionRate = 1;
					break;
				default:
					$coversionRate = 1;
			}

			return $productLength * $coversionRate;
		}

		/**
		 * Faz a conversão do peso para KG.
		 *
		 * @param Product $product Produto a ser convertido
		 */
		public static final function convertProductWeightInKG(Product &$product) {
			$product->setWeight(self::convertWeightKG($product->getWeight(), $product->getWeightClass()));
			$product->setWeightClass('kg');
		}

		/**
		 * Faz a conversão das dimensões para CM.
		 *
		 * @param Product $product Produto a ser convertido
		 */
		public static final function convertProductLengthInCM(Product &$product) {
			$length_class = $product->getLengthClass();

			$product->setWidth(ceil(self::convertLengthCM($product->getWidth(), $length_class)));
			$product->setHeight(ceil(self::convertLengthCM($product->getHeight(), $length_class)));
			$product->setlength(ceil(self::convertLengthCM($product->getLength(), $length_class)));
			$product->setLengthClass('cm');
		}
	}

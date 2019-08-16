<?php
	/**
	 * Created by Lucas Cava.
	 * User: CAVA
	 * Date: 16/08/2019
	 * Time: 00:48
	 */

	namespace MelhorEnvio;

	class Product {
		public $id; // opcional
		public $weight; // KG
		public $weight_class; // KG
		public $width; // CM
		public $height; // CM
		public $length; // CM
		public $length_class; // CM
		public $quantity; // opcional, padrão 1
		public $insurance_value; // reais

		/**
		 * Product constructor.
		 *
		 * @param mixed     $product_id      Código do Produto
		 * @param float     $weight          Peso
		 * @param string    $weight_class    kg (quilograma), g (gramas), lb (libras), oz (onça), l (litros), ml (mililitros)
		 * @param int       $width           Largura
		 * @param int       $height          Altura
		 * @param int       $length          Comprimento
		 * @param string    $length_class    cm (centímetro), mm (milímetro), in (polegada)
		 * @param int       $quantity        Quantidade
		 * @param float|int $insurance_value Valor Segurado/Declarado
		 */
		public function __construct($product_id, $weight, $weight_class, $width, $height, $length, $length_class, $quantity = 1, $insurance_value = 0) {
			$this->id = $product_id;
			$this->weight = $weight;
			$this->setWeightClass($weight_class);
			$this->width = $width;
			$this->height = $height;
			$this->length = $length;
			$this->setLengthClass($length_class);
			$this->quantity = $quantity;
			$this->insurance_value = $insurance_value;
		}

		/**
		 * @return mixed
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * @param mixed $id
		 */
		public function setId($id) {
			$this->id = $id;
		}

		/**
		 * @return float
		 */
		public function getWeight() {
			return $this->weight;
		}

		/**
		 * @param float $weight
		 */
		public function setWeight($weight) {
			$this->weight = $weight;
		}

		/**
		 * @return mixed
		 */
		public function getWeightClass() {
			return $this->weight_class;
		}

		/**
		 * @param mixed $weight_class
		 */
		public function setWeightClass($weight_class) {
			$this->weight_class = strtolower($weight_class);
		}

		/**
		 * @return int
		 */
		public function getWidth() {
			return $this->width;
		}

		/**
		 * @param int $width
		 */
		public function setWidth($width) {
			$this->width = $width;
		}

		/**
		 * @return int
		 */
		public function getHeight() {
			return $this->height;
		}

		/**
		 * @param int $height
		 */
		public function setHeight($height) {
			$this->height = $height;
		}

		/**
		 * @return int
		 */
		public function getLength() {
			return $this->length;
		}

		/**
		 * @param int $length
		 */
		public function setLength($length) {
			$this->length = $length;
		}

		/**
		 * @return mixed
		 */
		public function getLengthClass() {
			return $this->length_class;
		}

		/**
		 * @param mixed $length_class
		 */
		public function setLengthClass($length_class) {
			$this->length_class = strtolower($length_class);
		}

		/**
		 * @return int
		 */
		public function getQuantity() {
			return $this->quantity;
		}

		/**
		 * @param int $quantity
		 */
		public function setQuantity($quantity) {
			$this->quantity = $quantity;
		}

		/**
		 * @return float|int
		 */
		public function getInsuranceValue() {
			return $this->insurance_value;
		}

		/**
		 * @param float|int $insurance_value
		 */
		public function setInsuranceValue($insurance_value) {
			$this->insurance_value = $insurance_value;
		}

		/**
		 * Faz a conversão do peso para KG.
		 */
		public final function convertWeightInKG() {
			$this->weight = self::convertWeightKG($this->weight, $this->weight_class);
			$this->weight_class = 'kg';
		}

		/**
		 * Faz a conversão das dimensões para CM.
		 */
		public final function convertLengthInCM() {
			$this->width = ceil(self::convertLengthCM($this->width, $this->length_class));
			$this->height = ceil(self::convertLengthCM($this->height, $this->length_class));
			$this->length = ceil(self::convertLengthCM($this->length, $this->length_class));
			$this->length_class = 'cm';
		}

		/**
		 * Converte o peso para KG de acordo com a unidade atual.
		 *
		 * @param float|int  $productWeight      Peso do produto
		 * @param string     $productWeightClass Unidade de peso do produto
		 *
		 * @return float|int Peso em KG
		 */
		private static function convertWeightKG($productWeight, $productWeightClass) {
			$coversionRate = 1;

			switch ($productWeightClass) {
				case "kg":
					$coversionRate = 1;
					break;
				case "g":
					$coversionRate = 0.001;
					break;
				case "lb":
					$coversionRate = 0.453592;
					break;
				case "oz":
					$coversionRate = 0.0283495231;
					break;
				case "l":
					$coversionRate = 1;
					break;
				case "ml":
					$coversionRate = 0.001;
					break;
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
		private static function convertLengthCM($productLength, $productLengthClass) {
			$coversionRate = 1;

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
			}

			return $productLength * $coversionRate;
		}

		/**
		 * @return array
		 */
		public function __toArray() {
			$array = get_object_vars($this);

			unset($array['weight_class']);
			unset($array['length_class']);

			return $array;
		}
	}


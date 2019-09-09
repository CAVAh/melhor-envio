<?php
	/**
	 * Created by Lucas Cava.
	 * User: CAVA
	 * Date: 16/08/2019
	 * Time: 00:48
	 */

	namespace MelhorEnvio;

	class Product {
		private $id; // opcional
		private $weight; // KG
		private $weight_class; // KG
		private $width; // CM
		private $height; // CM
		private $length; // CM
		private $length_class; // CM
		private $quantity; // opcional, padrão 1
		private $insurance_value; // reais

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
		 * @return array
		 */
		public function __toArray() {
			$array = get_object_vars($this);

			unset($array['weight_class']);
			unset($array['length_class']);

			return $array;
		}
	}


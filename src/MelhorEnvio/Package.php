<?php
	/**
	 * Created by Lucas Cava.
	 * User: CAVA
	 * Date: 16/08/2019
	 * Time: 01:10
	 */

	namespace MelhorEnvio;

	class Package {
		public $weight; // kg
		public $width; // cm
		public $height; // cm
		public $length; // cm

		/**
		 * Product constructor.
		 *
		 * @param float      $weight          Peso em KG
		 * @param int        $width           Largura em CM
		 * @param int        $height          Altura em CM
		 * @param int        $length          Comprimento em CM
		 */
		public function __construct($weight, $width, $height, $length) {
			$this->weight = $weight;
			$this->width = $width;
			$this->height = $height;
			$this->length = $length;
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

		public function __toArray() {
			return get_object_vars($this);
		}
	}
<?php
	/**
	 * Created by Lucas Cava.
	 * User: CAVA
	 * Date: 16/08/2019
	 * Time: 01:00
	 */

	namespace MelhorEnvio;

	class Endereco {
		protected $postal_code;
		protected $address;
		protected $number;

		/**
		 * Endereco.class constructor.
		 *
		 * @param string      $postal_code CEP
		 * @param string      $address     Endereço
		 * @param string|null $number      Número
		 */
		public function __construct($postal_code, $address = null, $number = null) {
			$this->setPostalCode($postal_code);
			$this->address = $address;
			$this->number = $number;
		}

		/**
		 * @return string
		 */
		public function getPostalCode() {
			return $this->postal_code;
		}

		/**
		 * @param string $postal_code
		 */
		public function setPostalCode($postal_code) {
			$this->postal_code = preg_replace('/[^0-9]/', '', $postal_code);
		}

		/**
		 * @return string
		 */
		public function getAddress() {
			return $this->address;
		}

		/**
		 * @param string $address
		 */
		public function setAddress($address) {
			$this->address = $address;
		}

		/**
		 * @return null|string
		 */
		public function getNumber() {
			return $this->number;
		}

		/**
		 * @param null|string $number
		 */
		public function setNumber($number) {
			$this->number = $number;
		}
	}
<?php
	/**
	 * Created by Lucas Cava.
	 * User: CAVA
	 * Date: 16/08/2019
	 * Time: 01:04
	 */

	namespace MelhorEnvio;

	class Options {
		private $companies; // Companias filtradas por exemplo: ['Correios']
		private $insurance_value; // quando for calculado o frete com uma lista de produtos e o mesmo contendo seu respectivo valor segurado, não é necessário informar este campo
		private $receipt; // Aviso de recebimento (AR)
		private $own_hand; // Mão própria
		private $collect; // Coleta
		private $services; // Serviços filtrados (por exemplo: "1,2") -> diferente de company

		/**
		 * Options.class constructor.
		 *
		 * @param array|null  $companies       Filtrar Companias
		 * @param string|null $services        Filtrar Serviços
		 * @param float|null  $insurance_value Valor Segurado
		 * @param bool        $receipt         Aviso de Recebimento (AR)
		 * @param bool        $own_hand        Mão Própria
		 * @param bool        $collect         Coleta
		 */
		public function __construct($companies = null, $services = null, $insurance_value = null, $receipt = false, $own_hand = false, $collect = false) {
			$this->companies = $companies;
			$this->services = $services;
			$this->insurance_value = $insurance_value;
			$this->receipt = $receipt;
			$this->own_hand = $own_hand;
			$this->collect = $collect;
		}

		/**
		 * @return array|null
		 */
		public function getCompanies() {
			return $this->companies;
		}

		/**
		 * @param array|null $companies
		 */
		public function setCompanies($companies) {
			$this->companies = $companies;
		}

		/**
		 * @return float|null
		 */
		public function getInsuranceValue() {
			return $this->insurance_value;
		}

		/**
		 * @param float|null $insurance_value
		 */
		public function setInsuranceValue($insurance_value) {
			$this->insurance_value = $insurance_value;
		}

		/**
		 * @return bool
		 */
		public function isReceipt() {
			return $this->receipt;
		}

		/**
		 * @param bool $receipt
		 */
		public function setReceipt($receipt) {
			$this->receipt = $receipt;
		}

		/**
		 * @return bool
		 */
		public function isOwnHand() {
			return $this->own_hand;
		}

		/**
		 * @param bool $own_hand
		 */
		public function setOwnHand($own_hand) {
			$this->own_hand = $own_hand;
		}

		/**
		 * @return bool
		 */
		public function isCollect() {
			return $this->collect;
		}

		/**
		 * @param bool $collect
		 */
		public function setCollect($collect) {
			$this->collect = $collect;
		}

		/**
		 * @return null|string
		 */
		public function getServices() {
			return $this->services;
		}

		/**
		 * @param null|string $services
		 */
		public function setServices($services) {
			$this->services = $services;
		}
		
		public function __toArray() {
			$array = get_object_vars($this);
			
			if(is_null($this->insurance_value)) {
				unset($array['insurance_value']);
			}

			unset($array['services']);
			unset($array['company']);

			return $array;
		}
	}
<?php
	/**
	 * Created by Lucas Cava.
	 * User: CAVA
	 * Date: 05/08/2018
	 * Time: 01:04
	 */

	namespace MelhorEnvio;

	class To extends Endereco {
		public function __toArray(){
			return get_object_vars($this);
		}
	}
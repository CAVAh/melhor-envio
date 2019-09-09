<?php
	/**
	 * Created by Lucas Cava.
	 * User: CAVA
	 * Date: 16/08/2019
	 * Time: 01:03
	 */

	namespace MelhorEnvio;

	class From extends Endereco {
		public function __toArray(){
			return get_object_vars($this);
		}
	}
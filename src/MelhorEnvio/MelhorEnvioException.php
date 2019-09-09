<?php
	namespace MelhorEnvio;

	use Exception;

	class MelhorEnvioException extends Exception {
		// Redefine a exceção de forma que a mensagem não seja opcional
		public function __construct($message, $code = 0, Exception $previous = null) {
			if(!is_string($message)) {
				$message = json_encode($message);
			}

			// garante que tudo está corretamente inicializado
			parent::__construct($message, $code, $previous);
		}

		// Personaliza a apresentação do objeto como string
		public function __toString() {
			return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
		}
	}
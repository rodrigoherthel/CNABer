<?php

namespace App\Http\Controllers\Retorno;
use App\Http\Controllers\Retorno\RetornoAbstract;
use \CnabPHP;

class Retorno extends RetornoAbstract{
	public function __construct($conteudo){    
		parent::__construct($conteudo);
	}
}
?>

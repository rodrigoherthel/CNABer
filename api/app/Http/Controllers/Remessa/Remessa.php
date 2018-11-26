<?php
namespace App\Http\Controllers\Remessa;
use App\Http\Controllers\Remessa\RemessaAbstract;
use \CnabPHP;
class Remessa extends RemessaAbstract{
	public function __construct($banco,$layout,$data){    
		parent::__construct($banco,$layout,$data);
	}
}
?>
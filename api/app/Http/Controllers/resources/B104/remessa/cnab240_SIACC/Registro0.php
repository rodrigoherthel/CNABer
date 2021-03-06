<?php
namespace App\Http\Controllers\resources\B104\remessa\cnab240_SIACC;
use App\Http\Controllers\resources\generico\remessa\cnab240\Generico0;

class Registro0 extends Generico0
{
	protected $meta = array(
		'codigo_banco'=>array(
			'tamanho'=>3,
			'default'=>'104',
			'tipo'=>'int',
			'required'=>true),
		'lote_servico'=>array(
			'tamanho'=>4,
			'default'=>'0000',
			'tipo'=>'int',
			'required'=>true),
		'codigo_registro'=>array(
			'tamanho'=>1,
			'default'=>'0',
			'tipo'=>'int',
			'required'=>true),
		'filler1'=>array(
			'tamanho'=>9,
			'default'=>' ',
			'tipo'=>'alfa',
			'required'=>true),
		'tipo_inscricao'=>array(
			'tamanho'=>1,
			'default'=>'',
			'tipo'=>'int',
			'required'=>true),
		'int_inscricao'=>array(
			'tamanho'=>14,
			'default'=>'',
			'tipo'=>'int',
			'required'=>true),
		'codigo_convenio'=>array(
			'tamanho'=>6,
			'default'=>'',
			'tipo'=>'int',
			'required'=>true),
		'parametro_transmissao'=>array(
			'tamanho'=>2,
			'default'=>'',
			'tipo'=>'int',
			'required'=>true),
		'ambiente_cliente'=>array(
			'tamanho'=>1,
			'default'=>'P',
			'tipo'=>'alfa',
			'required'=>true),
		'ambiente_caixa'=>array(
			'tamanho'=>1,
			'default'=>' ',
			'tipo'=>'alfa',
			'required'=>true),
		'origem_aplicativo'=>array(
			'tamanho'=>3,
			'default'=>' ',
			'tipo'=>'alfa',
			'required'=>true),
		'int_versao'=>array(
			'tamanho'=>4,
			'default'=>'0',
			'tipo'=>'int',
			'required'=>true),
		'filler2'=>array(
			'tamanho'=>3,
			'default'=>' ',
			'tipo'=>'alfa',
			'required'=>true),
		'agencia'=>array(
			'tamanho'=>5,
			'default'=>'',
			'tipo'=>'int',
			'required'=>true),
		'agencia_dv'=>array(
			'tamanho'=>1,
			'default'=>'',
			'tipo'=>'int','required'=>true),
		'conta'=>array(
			'tamanho'=>12,
			'default'=>'',
			'tipo'=>'int',
			'required'=>true),
		'conta_dv'=>array(
			'tamanho'=>1,
			'default'=>'',
			'tipo'=>'alfa',
			'required'=>true),
		'agencia_conta_dv'=>array(
			'tamanho'=>1,
			'default'=>'',
			'tipo'=>'alfa',
			'required'=>true),
		'nome_empresa'=>array(
			'tamanho'=>30,
			'default'=>'',
			'tipo'=>'alfa',
			'required'=>true),
		'nome_banco'=>array(
			'tamanho'=>30,
			'default'=>'CAIXA',
			'tipo'=>'alfa',
			'required'=>true),
		'filler3'=>array(
			'tamanho'=>10,
			'default'=>' ',
			'tipo'=>'alfa',
			'required'=>true),
		'codigo_remessa'=>array(
			'tamanho'=>1,
			'default'=>'1',
			'tipo'=>'int',
			'required'=>true),
		'data_geracao'=>array(
			'tamanho'=>8,
			'default'=>'',// nao informar a data na instancia��o - gerada dinamicamente
			'tipo'=>'int',
			'required'=>true),
		'hora_geracao'=>array(
			'tamanho'=>6,
			'default'=>'',// nao informar a data na instancia��o - gerada dinamicamente
			'tipo'=>'int',
			'required'=>true),
		'NSA'=>array(
			'tamanho'=>6,
			'default'=>'',
			'tipo'=>'int',
			'required'=>true),
		'versao_layout'=>array(
			'tamanho'=>3,
			'default'=>'080',
			'tipo'=>'int',
			'required'=>true),
		'densidade_gravacao'=>array(
			'tamanho'=>5,
			'default'=>'01600',
			'tipo'=>'int',
			'required'=>true),
		'reservado_banco'=>array(
			'tamanho'=>20,
			'default'=>' ',
			'tipo'=>'alfa',
			'required'=>true),
		'reservado_empresa'=>array(
			'tamanho'=>20,
			'default'=>' ',
			'tipo'=>'alfa',
			'required'=>true),
		'uso_febraban'=>array(
			'tamanho'=>11,
			'default'=>' ',
			'tipo'=>'alfa',
			'required'=>true),
		'indet_cobranca'=>array(
			'tamanho'=>3,
			'default'=>' ',
			'tipo'=>'alfa',
			'required'=>true),
		'uso_van'=>array(
			'tamanho'=>3,
			'default'=>' ',
			'tipo'=>'int',
			'required'=>true),
		'tipo_servico'=>array(
			'tamanho'=>2,
			'default'=>' ',
			'tipo'=>'alfa',
			'required'=>true),
		'ocorrencia_sem_papel'=>array(
			'tamanho'=>10,
			'default'=>' ',
			'tipo'=>'alfa',
			'required'=>true),
	);
	public function __construct($data = null)
	{
		parent::__construct($data);
	}
}

?>

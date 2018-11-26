<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Remessa\Remessa;
use App\Http\Controllers\Retorno\Retorno;
use Illuminate\Http\Request;

class APIController extends Controller
{
  public function LideryRemessa(Request $request)
  {
    try {
      $charges = $request->json()->all();
      if (count($charges) > 0) {
        $fileRemessa = new Remessa(
          $request->header('codigo-banco'),
          $request->header('tipo-cnab'),
          array(
            'nome_empresa' => $request->header('nome-empresa'),
            'tipo_inscricao' => $request->header('tipo-inscricao'),
            'numero_inscricao' => $request->header('numero-inscricao'),
            'agencia' => $request->header('agencia'),
            'agencia_dv' => $request->header('agencia-dv'),
            'conta' => $request->header('conta'),
            'conta_dv' => $request->header('conta-dv'),
            'posto' => $request->header('posto'),
            'codigo_beneficiario' => $request->header('codigo-beneficiario'),
            'codigo_beneficiario_dv' => $request->header('codigo-beneficiario-dv'),
            'numero_sequencial_arquivo' => $request->header('numero-sequencial-arquivo'),
            'situacao_arquivo' => $request->header('situacao-arquivo'),
            'mensagem_1' => $request->header('mensagem-1'),
          )
        );
        $lote = $fileRemessa->addLote(array('tipo_servico' => 1)); // tipo_servico  = 1 para cobrança registrada, 2 para sem registro
        foreach ($charges as $charge) {
          $lote->inserirDetalhe($charge, array('tipo_servico' => 1));
        }
        $lenght = strlen($fileRemessa->getText());
        $filename = date('dmYHis') . ".ret";
        return response($fileRemessa->getText(), 200)->withHeaders([
          'Content-Disposition' => 'attachment; filename="' . $filename . '"',
          'Content-Type' => 'text/plain',
          'Content-Length' => $lenght,
          'Connection' => 'close',
        ]);
        unset($fileRemessa);
      } else {
        return response("Must have JSON data in body request to convert CNAB format", 400);
      }
    } catch (Exception $e) {
      return response('Missing data for generation. Error: '.$e, 500);
    }
  }

  public function LideryRetorno(Request $request)
  {
    try {
      $fileUploaded = $request->file('File');
      if ($fileUploaded) {
        if ($fileUploaded->getMimeType() == "text/plain") {
          $fileContentRetorno = new Retorno(file_get_contents($fileUploaded));
          $records = $fileContentRetorno->getRegistros();
          $payments[] = "";
          $count = 0;
          foreach ($records as $record) {
            if ($record->R3U->codigo_movimento == 6) {
              $payments[$count] = array(
                "id" => $count,
                "nossoNumero" => $record->nosso_numero,
                "valorRecebido" => $record->vlr_pago,
                "dataPagamento" => $record->R3U->data_ocorrencia,
                "carteira" => $record->carteira,
                "vlr_juros_multa" => $record->valor,
                "vlr_desconto" => $record->R3U->vlr_desconto
              );
              $count++;
            }
          }
          unset($fileContentRetorno);
          return response(json_encode($payments), 200);
        } else {
          return response('File is not  text  format', 500);
        }
      } else {
        return response('File not found', 500);
      }

    } catch (Exception $e) {
      return response('File is not in CNAB format. Error: ' . $e, 500);
    }
  }
}

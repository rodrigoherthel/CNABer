# O que é CNAB
Com o CNAB, empresas mantêm o banco de dados atualizados, já que os arquivos que são intercambiados e compensados pelo padrão de comunicação dão baixa automática nos boletos pagos pelos clientes, o que facilita muito o dia a dia, já que sem esta opção cada documento teria que ser digitado manualmente.
O processo é simples e fácil, e os arquivos podem ser enviados ou recebidos de diversas formas, mas geralmente é realizado pelo site do banco. A empresa pode emitir seus boletos utilizando um arquivo remessa pelo site da instituição financeira escolhida ou ela pode emitir diretamente os boletos. O cliente recebe e paga. Com a compensação efetivada, a empresa certifica diariamente pelo site bancário se existem arquivos retorno, o que lhe possibilita processar diretamente no software financeiro os arquivos que foram baixados pelo site, o que gera a baixa dos boletos liquidados. Tudo é realizado em meio digital. E os arquivos não podem ser compactados ao serem encaminhados ao banco.

## Como foi feito
* API em código PHP com o Micro Framework LUMEM
* Todo o código de de geração e leitura para o formato FEBRABAN foram copiados/extraídos do projeto CnabPHP disponível em https://github.com/QuilhaSoft/OpenCnabPHP sob a liçenca do tipo "MIT License"

## Como Executar
* Entre na pasta "api" e execute o comando: php -S localhost:8000 -t public
* Para remessa vocês deverá fazer requisições POST para:
* REMESSA - http://localhost:8000/v1/remessa
* RETORNO - http://localhost:8000/v1/retorno

# Gerando arquivos de Remessa (.REM)
O arquivo de remesssa equivale ao arquivo no formato CNAB/FEBRABAN contendo dados do pagamentos desejados. Este arquivo deverá ser enviado para o banco.
* Você deverá efetuar uma requisição POST na rota /v1/remessa.
* No header renvie os dados do banco e especificações do arquivo que deverão compor o cabeçalho.
* No corpo, no formato JSON, envie um JSON contendo cada cobrança/boleto a ser gerado.

## Campos Header
* codigo-banco - 341 (itau)
* tipo-cnab - cnab240 ou cnab400
* nome-empresa - <Nome da empresa>
* tipo-inscricao - 1 para cpf ou 2 para cnpj
* numero-inscricao - cpf ou cnpj completo
* agencia - número da agencia sem o digito verificador
* agencia-dv - somente o digito verificador da agencia
* conta - número da conta
* conta-dv - digito verificador da conta
* posto - codigo forncecido pelo sicredi obs: como o dv da agencia não é informado eu armazeno no banco de dados essa valor no dv da agencia
* codigo-beneficiario - codigo fornecido pelo banco
* codigo-beneficiario-dv -  codigo fornecido pelo banco
* numero-sequencial-arquivo - sequencial do arquivo um numero novo para cada arquivo gerado
* situacao-arquivo - use T para teste e P para producao
* mensagem-1 - Mensagem personalizada para todos os boletos (suportado somente para SICOOB cnab400, mudar o numero 1 para 2,3,4,5 para incluir mais mensagens)

## Campos Json
* codigo_movimento - 1 = Entrada de título, para outras opções ver nota explicativa C004 manual Cnab_SIGCB na pasta docs
* nosso_numero - numero sequencial de boleto
* seu_numero - se nao informado usarei o nosso numero 
* carteira_banco - codigo da carteira ex: 109, RG esse vai o nome da carteira no banco
* cod_carteira - I para a maioria das carteiras do itau
* especie_titulo- NP"ou informar dm e sera convertido para codigo em qualquer laytou conferir em especie.php
* valor - Valor do boleto como float valido em php
* emissao_boleto - tipo de emissao do boleto informar 2 para emissao pelo beneficiario e 1 para emissao pelo banco
* protestar - 1 = Protestar com (Prazo) dias, 3 = Devolver após (Prazo) dias
* prazo_protesto - Informar o numero de dias apos o vencimento para iniciar o protesto
* nome_pagador- O Pagador é o cliente, preste atenção nos campos abaixo
* tipo_inscricao - campo fixo, escreva '1' se for pessoa fisica, 2 se for pessoa juridica
* numero_inscricao - cpf ou ncpj do pagador
* endereco_pagador - endereço do cliente
* bairro_pagador - bairro do cliente
* cep_pagador - CEP do cliente com hífem
* cidade_pagador - cidade do cliente
* uf_pagador - UF do cliente (2 caracteres)
* data_vencimento - Formato YYYY-MM-DD
* data_emissao -  Formato YYYY-MM-DD
* vlr_juros - Valor do juros de 1 dia (ex: 0.15)
* data_desconto - Formato YYYY-MM-DD
* vlr_desconto - Valor do desconto
* baixar - codigo para indicar o tipo de baixa '1' (Baixar/ Devolver) ou '2' (Não Baixar / Não Devolver)
* prazo_baixa- prazo de dias para o cliente pagar após o vencimento
* mensagem - Mensagem personalizada para boletos específicos (ex: Aniversário)
* email_pagador - email do cliente
* data_multa - Formato YYYY-MM-DD
* vlr_multa - valor da multa

## Exemplo Request HTTP
```HTTP
POST /v1/remessa HTTP/1.1
Host: localhost:8000
Content-Type: application/json
codigo-banco: 341
tipo-cnab: cnab400
nome-empresa: NOME DA EMPRESA
tipo-inscricao: 1
numero-inscricao: 123.122.123-56
agencia: 3300
agencia-dv: 1
conta: 3264
conta-dv: 0
posto: 87
codigo-beneficiario: 10668
codigo-beneficiario-dv: 87
numero-sequencial-arquivo: 1
situacao-arquivo: T
mensagem-1: teste
Cache-Control: no-cache

 [
  {
   "codigo_movimento":1,
   "nosso_numero":50,
   "seu_numero":43,
   "carteira_banco":109,
   "cod_carteira":"01",
   "especie_titulo":"NP",
   "valor":100,
   "emissao_boleto":2,
   "protestar":3,
   "prazo_protesto":5,
   "nome_pagador":"RODRIGO LUIZ DE PAULA",
   "tipo_inscricao":1,
   "numero_inscricao":"123.122.123-56",
   "endereco_pagador":"Rua dos developers,123 sl 103",
   "bairro_pagador":"Bairro da insonia",
   "cep_pagador":"12345-123",
   "cidade_pagador":"Londrina",
   "uf_pagador":"PR",
   "data_vencimento":"2018-04-09",
   "data_emissao":"2018-04-09",
   "vlr_juros":0.15,
   "data_desconto":"2016-04-09",
   "vlr_desconto":"0",
   "baixar":1,
   "prazo_baixa":90,
   "mensagem":"JUROS de R$0,15 ao dia\nN\u00e3o receber apos 30 dias",
   "email_pagador":"rogerio@ciatec.net",
   "data_multa":"2016-04-09",
   "vlr_multa":30
  },
   {
   "codigo_movimento":1,
   "nosso_numero":50,
   "seu_numero":43,
   "carteira_banco":109,
   "cod_carteira":"01",
   "especie_titulo":"NP",
   "valor":100,
   "emissao_boleto":2,
   "protestar":3,
   "prazo_protesto":5,
   "nome_pagador":"RODRIGO LUIZ DE PAULA",
   "tipo_inscricao":1,
   "numero_inscricao":"123.122.123-56",
   "endereco_pagador":"Rua dos developers,123 sl 103",
   "bairro_pagador":"Bairro da insonia",
   "cep_pagador":"12345-123",
   "cidade_pagador":"Londrina",
   "uf_pagador":"PR",
   "data_vencimento":"2018-04-09",
   "data_emissao":"2018-04-09",
   "vlr_juros":0.15,
   "data_desconto":"2016-04-09",
   "vlr_desconto":"0",
   "baixar":1,
   "prazo_baixa":90,
   "mensagem":"JUROS de R$0,15 ao dia\nN\u00e3o receber apos 30 dias",
   "email_pagador":"rogerio@ciatec.net",
   "data_multa":"2016-04-09",
   "vlr_multa":30
  },
   {
   "codigo_movimento":1,
   "nosso_numero":50,
   "seu_numero":43,
   "carteira_banco":109,
   "cod_carteira":"01",
   "especie_titulo":"NP",
   "valor":100,
   "emissao_boleto":2,
   "protestar":3,
   "prazo_protesto":5,
   "nome_pagador":"RODRIGO LUIZ DE PAULA",
   "tipo_inscricao":1,
   "numero_inscricao":"123.122.123-56",
   "endereco_pagador":"Rua dos developers,123 sl 103",
   "bairro_pagador":"Bairro da insonia",
   "cep_pagador":"12345-123",
   "cidade_pagador":"Londrina",
   "uf_pagador":"PR",
   "data_vencimento":"2018-04-09",
   "data_emissao":"2018-04-09",
   "vlr_juros":0.15,
   "data_desconto":"2016-04-09",
   "vlr_desconto":"0",
   "baixar":1,
   "prazo_baixa":90,
   "mensagem":"JUROS de R$0,15 ao dia\nN\u00e3o receber apos 30 dias",
   "email_pagador":"rogerio@ciatec.net",
   "data_multa":"2016-04-09",
   "vlr_multa":30
  },
   {
   "codigo_movimento":1,
   "nosso_numero":50,
   "seu_numero":43,
   "carteira_banco":109,
   "cod_carteira":"01",
   "especie_titulo":"NP",
   "valor":100,
   "emissao_boleto":2,
   "protestar":3,
   "prazo_protesto":5,
   "nome_pagador":"RODRIGO LUIZ DE PAULA",
   "tipo_inscricao":1,
   "numero_inscricao":"123.122.123-56",
   "endereco_pagador":"Rua dos developers,123 sl 103",
   "bairro_pagador":"Bairro da insonia",
   "cep_pagador":"12345-123",
   "cidade_pagador":"Londrina",
   "uf_pagador":"PR",
   "data_vencimento":"2018-04-09",
   "data_emissao":"2018-04-09",
   "vlr_juros":0.15,
   "data_desconto":"2016-04-09",
   "vlr_desconto":"0",
   "baixar":1,
   "prazo_baixa":90,
   "mensagem":"JUROS de R$0,15 ao dia\nN\u00e3o receber apos 30 dias",
   "email_pagador":"rogerio@ciatec.net",
   "data_multa":"2016-04-09",
   "vlr_multa":30
  }
]
```
## Exemplo Request PHP 
```php
<?php

$request = new HttpRequest();
$request->setUrl('http://localhost:8000/v1/remessa');
$request->setMethod(HTTP_METH_POST);

$request->setHeaders(array(
  'cache-control' => 'no-cache',
  'mensagem-1' => 'teste',
  'situacao-arquivo' => 'T',
  'numero-sequencial-arquivo' => '1',
  'codigo-beneficiario-dv' => '87',
  'codigo-beneficiario' => '10668',
  'posto' => '87',
  'conta-dv' => '0',
  'conta' => '3264',
  'agencia-dv' => '1',
  'agencia' => '3300',
  'numero-inscricao' => '123.122.123-56',
  'tipo-inscricao' => '1',
  'nome-empresa' => 'NOME DA EMPRESA',
  'tipo-cnab' => 'cnab400',
  'codigo-banco' => '341'
));

$request->setBody('[
  {
   "codigo_movimento":1,
   "nosso_numero":50,
   "seu_numero":43,
   "carteira_banco":109,
   "cod_carteira":"01",
   "especie_titulo":"NP",
   "valor":100,
   "emissao_boleto":2,
   "protestar":3,
   "prazo_protesto":5,
   "nome_pagador":"MARIA JOANA DA SILVA",
   "tipo_inscricao":1,
   "numero_inscricao":"123.122.123-56",
   "endereco_pagador":"Rua dos developers,10 sl 101",
   "bairro_pagador":"Vila Cloris",
   "cep_pagador":"12345-123",
   "cidade_pagador":"Londrina",
   "uf_pagador":"PR",
   "data_vencimento":"2018-04-09",
   "data_emissao":"2018-04-09",
   "vlr_juros":0.15,
   "data_desconto":"2016-04-09",
   "vlr_desconto":"0",
   "baixar":1,
   "prazo_baixa":90,
   "mensagem":"JUROS de R$0,15 ao dia\\nN\\u00e3o receber apos 30 dias",
   "email_pagador":"rogerio@ciatec.net",
   "data_multa":"2016-04-09",
   "vlr_multa":30
  },
   {
   "codigo_movimento":1,
   "nosso_numero":50,
   "seu_numero":43,
   "carteira_banco":109,
   "cod_carteira":"01",
   "especie_titulo":"NP",
   "valor":100,
   "emissao_boleto":2,
   "protestar":3,
   "prazo_protesto":5,
   "nome_pagador":"RODRIGO LUIZ DE PAULA",
   "tipo_inscricao":1,
   "numero_inscricao":"123.122.123-56",
   "endereco_pagador":"Rua dos developers,123 sl 103",
   "bairro_pagador":"Bairro da insonia",
   "cep_pagador":"12345-123",
   "cidade_pagador":"Londrina",
   "uf_pagador":"PR",
   "data_vencimento":"2018-04-09",
   "data_emissao":"2018-04-09",
   "vlr_juros":0.15,
   "data_desconto":"2016-04-09",
   "vlr_desconto":"0",
   "baixar":1,
   "prazo_baixa":90,
   "mensagem":"JUROS de R$0,15 ao dia\\nN\\u00e3o receber apos 30 dias",
   "email_pagador":"rogerio@ciatec.net",
   "data_multa":"2016-04-09",
   "vlr_multa":30
  },
   {
   "codigo_movimento":1,
   "nosso_numero":50,
   "seu_numero":43,
   "carteira_banco":109,
   "cod_carteira":"01",
   "especie_titulo":"NP",
   "valor":100,
   "emissao_boleto":2,
   "protestar":3,
   "prazo_protesto":5,
   "nome_pagador":"RODRIGO HERHTHEL",
   "tipo_inscricao":1,
   "numero_inscricao":"123.122.123-56",
   "endereco_pagador":"Rua dos developers,123 sl 103",
   "bairro_pagador":"Bairro da insonia",
   "cep_pagador":"12345-123",
   "cidade_pagador":"Londrina",
   "uf_pagador":"PR",
   "data_vencimento":"2018-04-09",
   "data_emissao":"2018-04-09",
   "vlr_juros":0.15,
   "data_desconto":"2016-04-09",
   "vlr_desconto":"0",
   "baixar":1,
   "prazo_baixa":90,
   "mensagem":"JUROS de R$0,15 ao dia\\nN\\u00e3o receber apos 30 dias",
   "email_pagador":"rogerio@ciatec.net",
   "data_multa":"2016-04-09",
   "vlr_multa":30
  },
   {
   "codigo_movimento":1,
   "nosso_numero":50,
   "seu_numero":43,
   "carteira_banco":109,
   "cod_carteira":"01",
   "especie_titulo":"NP",
   "valor":100,
   "emissao_boleto":2,
   "protestar":3,
   "prazo_protesto":5,
   "nome_pagador":"JOAO DAS COVES",
   "tipo_inscricao":1,
   "numero_inscricao":"123.122.123-56",
   "endereco_pagador":"Rua dos developers,123 sl 103",
   "bairro_pagador":"Bairro da insonia",
   "cep_pagador":"12345-123",
   "cidade_pagador":"Belo Horizonte",
   "uf_pagador":"MG",
   "data_vencimento":"2018-04-09",
   "data_emissao":"2018-04-09",
   "vlr_juros":0.15,
   "data_desconto":"2016-04-09",
   "vlr_desconto":"0",
   "baixar":1,
   "prazo_baixa":90,
   "mensagem":"JUROS de R$0,15 ao dia\\nN\\u00e3o receber apos 30 dias",
   "email_pagador":"rodrigoherthel@xpto.com",
   "data_multa":"2018-01-09",
   "vlr_multa":30
  }
]');

try {
  $response = $request->send();

  echo $response->getBody();
} catch (HttpException $ex) {
  echo $ex;
}
```

# Lendo arquivo de retorno (.RET)
O arquivo de retorno equivale ao arquivo no formato CNAB/FEBRABAN contendo os pagamentos efetuados. Este arquivo é enviado pelo banco.
* Você deverá efetuar uma requisição POST na rota /v1/retorno.
* No corpo da requisição faça o upload do arquivo recebido (extensão .RET).
* Você receberá como resposta um JSON todos os pagamentos efetuados.

##Exemplo Retorno JSON
```json
[
  {
    "id": 0,
    "nossoNumero": 231327,
    "valorRecebido": 209.97,
    "dataPagamento": "2013-06-20",
    "carteira": 109,
    "vlr_juros_multa": 389.75,
    "vlr_desconto": 176.45
  },
  {
    "id": 1,
    "nossoNumero": 211842,
    "valorRecebido": 392.09,
    "dataPagamento": "2013-06-20",
    "carteira": 109,
    "vlr_juros_multa": 395.41,
    "vlr_desconto": 0
  },
  {
    "id": 2,
    "nossoNumero": 237636,
    "valorRecebido": 340.67,
    "dataPagamento": "2013-06-20",
    "carteira": 109,
    "vlr_juros_multa": 545.6,
    "vlr_desconto": 201.6
  }
]
```

## Exemplo Request HTTP
```HTTP
POST /v1/retorno HTTP/1.1
Host: localhost:8000
Cache-Control: no-cache
Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW
------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="File"; filename="retorno_cnab400_itau.ret"
Content-Type: 
------WebKitFormBoundary7MA4YWxkTrZu0gW--
```

## Exemplo Request PHP 
```php
<?php

$request = new HttpRequest();
$request->setUrl('http://localhost:8000/v1/retorno');
$request->setMethod(HTTP_METH_POST);

$request->setHeaders(array(
  'cache-control' => 'no-cache',
  'content-type' => 'multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW'
));

$request->setBody('------WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="File"; filename="retorno_cnab400_itau.ret"
Content-Type: false
------WebKitFormBoundary7MA4YWxkTrZu0gW--');

try {
  $response = $request->send();

  echo $response->getBody();
} catch (HttpException $ex) {
  echo $ex;
}
```
MIT License
Copyright (c) 2018 Rodrigo Herthel

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
 The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

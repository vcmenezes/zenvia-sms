<?php

namespace Solidos\ZenviaSms\Commands;

use Illuminate\Console\Command;
use Solidos\ZenviaSms\Services\ZenviaService;
use Throwable;

class SendSmsTest extends Command
{
    protected $signature = 'zenvia:sms {number} {text=Teste Mensagem}';

    protected $description = 'Envia um sms de teste para o numero passado';

    /**
     * @throws Throwable
     */
    public function handle(): void
    {
        try {
            $this->info('Iniciando envio de SMS para ' . $this->argument('number'));

            $zenvia = new ZenviaService(config('zenvia.account'), config('zenvia.password'));

            $zenvia->setNumber($this->argument('number'))
                ->setText($this->argument('text'))
                ->send();

            $this->info('SMS enviado para ' . $this->argument('number'));
        } catch (Throwable $exception) {
            $this->error('Erro: ' . $exception->getMessage());
            $this->error('Code: ' . $exception->getCode());
            throw $exception;
        }

//        try {
//            $zenvia = new ZenviaService(config('zenvia.account'), config('zenvia.password'));
//
//            $zenvia->setNumber(['5548998425179', '5565993429018'])
//                ->setText('Mensagem Teste - Zenvia - Sólidos')
//                ->send();
//        } catch (Throwable $exception) {
//            $this->error('Erro: ' . $exception->getMessage());
//            $this->error('Code: ' . $exception->getCode());
//            throw $exception;
//        }
    }
}

<?php

namespace Menezes\ZenviaSms\Commands;

use Illuminate\Console\Command;
use Menezes\ZenviaSms\Exceptions\AuthenticationNotFoundedException;
use Menezes\ZenviaSms\Exceptions\FieldMissingException;
use Menezes\ZenviaSms\Services\ZenviaService;
use Throwable;

class SendSmsTest extends Command
{
    protected $signature = 'zenvia:sms {number} {text=Teste Mensagem}';

    protected $description = 'Envia um sms de teste para o numero passado';

    /**
     * @throws AuthenticationNotFoundedException
     * @throws FieldMissingException
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
        }

//        try {
//            $zenvia = new ZenviaService(config('zenvia.account'), config('zenvia.password'));
//
//            $zenvia->setNumber('5541999999999')
//                ->setNumber(['5541999999999', '5541999999999'])
//                ->setNumber(collect(['5541999999999', '5541999999999']))
//                ->setText('Mensagem Teste')
//                ->send();
//        } catch (Throwable $exception) {
//            throw $exception;
//        }
    }
}

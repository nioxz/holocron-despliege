<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Document;

class PetarCreated extends Notification
{
    use Queueable;

    public $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    // Definimos que el canal es la BASE DE DATOS ('database')
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    // Aquí definimos qué datos se guardan en la alerta
    public function toArray(object $notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'message'     => '🚨 Nuevo PETAR de Alto Riesgo creado por ' . $this->document->user->name,
            'url'         => route('supervisor.inbox'),
            'icon'        => 'warning'
        ];
    }
}
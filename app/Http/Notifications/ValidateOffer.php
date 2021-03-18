<?php

namespace App\Notifications;

use App\Entities\Oferta;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ValidateOffer extends Notification
{
    use Queueable;
    protected $offer;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($offer)
    {
        $this->offer = $offer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = "/login";
        $oferta = Oferta::find($this->offer);
        return (new MailMessage)
                    ->subject('Nova oferta')
                    ->greeting('Hola')
                    ->line("Ha arribat una oferta de treball de l'empresa ".$oferta->Empresa->nombre.". Les seues dades són:")
                    ->line(" - Descripció de l'oferta: ".$oferta->descripcion)
                    ->line(" - Lloc demandat: ".$oferta->puesto)
                    ->line(" - Tipus de contracte: ".$oferta->tipo_contrato)
//                    ->line("Contacte: ".$oferta->contacto)
//                    ->line("Telèfon: ".$oferta->telefono)
//                    ->line("Email: ".$oferta->email)
                    ->action("Veure l'oferta", url($url));

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

<?php

namespace App\Notifications;


use App\Services\CustomMailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class OfferStudent extends Notification
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
        $oferta = $this->offer;
        $alumnos = [];

        foreach ($oferta->Alumnos as $alumno){
            $alumnos[] = '* '.$alumno->fullName . ' (' . $alumno->user->email.')';
        }
        return (new CustomMailMessage)
                    ->subject('Nou candidat per a la teu oferta de treball del CIPFP Batoi')
                    ->greeting('Hola '.$oferta->contacto)
                    ->line("Un candidat nou s'ha inscrit a la teua oferta de treball '".$oferta->descripcion."'.")
                    ->line("Estos són els alumnes que ja han mostrat interes:")
                    ->line($alumnos)
                    ->action("Veure l'oferta", url($url))
                    ->salutation('Salutacions.');
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

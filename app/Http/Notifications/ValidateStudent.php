<?php

namespace App\Notifications;

use App\Entities\Alumno;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ValidateStudent extends Notification
{
    use Queueable;
    protected $ciclo;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ciclo)
    {
        $this->ciclo =$ciclo;
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
        return (new MailMessage)
                    ->subject('Validar estudiant')
                    ->greeting('Hola')
                    ->line("L'alumne ".Alumno::find($this->ciclo->pivot->id_alumno)->fullName)
                    ->line("vol que el valides el cicle ".$this->ciclo->vCiclo)
                    ->line(     "Cursat l'any: ".$this->ciclo->pivot->any)
                    ->action("Valida el cicle de l'alumne", url($url));
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

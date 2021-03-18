<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetRequest extends Notification implements ShouldQueue
{
    use Queueable;
    protected $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        $url = url('/resetPassword/'.$this->token);
        return (new MailMessage)
            ->subject('Canvi de contrasenya')
            ->greeting('Hola')
            ->line('Has rebut aquest email perquè hem rebut una sol·licitut per a resetejar la teua contrasenya.')
            ->line('Si has fet eixa sol·licitut polsa el botó de baix i introdueix en la pantalla que apareixerà la nova contrasenya')
            ->action('Reset Password', url($url))
            ->line('Si no has fet eixa sol·licitut no cal que facis res més.');
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

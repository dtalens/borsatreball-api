<?php


namespace App\Services;
use Illuminate\Notifications\Messages\MailMessage;

class CustomMailMessage extends MailMessage {

    protected function formatLine($lines) {
        if (is_array($lines)) {
            foreach ($lines as $line) {
                $this->with($line);
            }
        } else {
            // Just return without removing new lines.
            return trim($lines);
        }

    }

}


<?php

namespace App\Mail;

use DeepCopy\Exception\PropertyException;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BaseMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $email_from_address;
    protected $app_name;

    public function __construct()
    {
        $this->email_from_address = cache('config')->email_from_address || env('MAIL_FROM_ADDRESS');
        $this->app_name = cache('config')->app_name || env('APP_NAME');
        $this->isValid();
    }

    private function isValid()
    {
        $passes = collect(get_class_vars(get_class($this)))->every(function ($attr) {
            return !empty($attr);
        });
        if (!$passes)
            throw new PropertyException('There are some missing properties on Mailable Class');
    }
}

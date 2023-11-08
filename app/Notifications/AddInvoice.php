<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\invoices;

class AddInvoice extends Notification
{
    use Queueable;
    private $id_invoices;

    /**
     * Create a new notification instance.
     */
    public function __construct($id_invoices)
    {
        $this->id_invoices = $id_invoices;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url= 'http://127.0.0.1:8000/invoiceDetails/'.$this->id_invoices;
        return (new MailMessage)
                    ->subject('اضافه فاتوره جديده')
                    ->line('اضافه فاتوره جديده')
                    ->action('عرض الفاتوره', $url)
                    ->line('شكرا لاستخدامك برنامج الفواتير');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class CronController extends Controller
{

    public function sendOrder ()
    {
        $order = Order::query()->where('sended', 0)->first();
        if(empty($order)) die;
        $order->sended = 1;
        $order->save();

        $html = "
            <h1>Заявка №" . $order->id . "</h1>
            <p>Форма: " . $order->title . "</p>
            <p>Имя: " . $order->name . "</p>
            <p>Телефон " . $order->phone . "</p>
        ";
        if(!empty($order->email)) $html .= "<p>Email: " . $order->email . "</p>";
        if(!empty($order->data)) $html .= "<p>Данные: " . $order->data . "</p>";
        $html .= "<p>Дата: " . date('d.m.Y H:i', strtotime($order->created_at)) . "</p>";

        //$emails = ['info@iondigital.de'];
        foreach ($emails as $email) {
            $t = Mail::html( $html, function( $message ) use ($order, $email) {
                $message->subject('Новая заявка № ' . $order->id . ' на сайте ' . env('APP_URL'))->to($email);
            });
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;

class ContactController extends Controller
{
    /**
     * Store a newly created contact message in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            // Enviar correo al administrador
            Mail::to(config('mail.from.address'))
                ->send(new ContactMessage($validated));

            return redirect()->back()->with('success', 'Mensaje enviado correctamente. Nos pondremos en contacto pronto.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al enviar el mensaje. Por favor intenta de nuevo.');
        }
    }
}

@component('mail::message')
# Nuevo Mensaje de Contacto

Tienes un nuevo mensaje de contacto desde la plataforma de reservas.

## Detalles del Remitente

**Nombre:** {{ $name }}

**Correo Electrónico:** {{ $email }}

@if($phone)
**Teléfono:** {{ $phone }}
@endif

**Asunto:** {{ $subject }}

## Mensaje

{{ $message }}

---

*Este es un mensaje automático. Por favor responde directamente al correo del remitente o usa la plataforma de contacto para comunicarte.*

@endcomponent

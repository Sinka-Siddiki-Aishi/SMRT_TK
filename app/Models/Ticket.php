<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\SvgWriter;


class Ticket extends Model
{
   protected $fillable = [
       'ticket_number',
       'booking_id',
       'user_id',
       'event_id',
       'ticket_type',
       'qr_code',
       'status',
       'used_at',
       'seat_number',
       'section',
   ];


   protected $casts = [
       'used_at' => 'datetime',
   ];


   // Relationships
   public function booking()
   {
       return $this->belongsTo(Booking::class);
   }


   public function user()
   {
       return $this->belongsTo(User::class);
   }


   public function event()
   {
       return $this->belongsTo(Event::class);
   }


   // Helper methods
   public function markAsUsed()
   {
       $this->update([
           'status' => 'used',
           'used_at' => now(),
       ]);
   }


   public function isValid()
   {
       return $this->status === 'active' &&
              $this->event->date > now();
   }


   public function getQRCodeUrl()
   {
       return route('tickets.verify', $this->qr_code);
   }


   public function generateQRCode($size = 200)
   {
       $builder = new Builder(
           writer: new SvgWriter(),
           data: $this->getQRCodeUrl(),
           size: $size,
           margin: 10
       );

       $result = $builder->build();

       return 'data:image/svg+xml;base64,' . base64_encode($result->getString());
   }
}






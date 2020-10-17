<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Reservation;

class Book extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getPathAttribute(){
        return "/books/".$this->id;
    }

    public function setAuthorIdAttribute($author){
        $this->attributes['author_id'] = (Author::firstOrCreate([
            'name'=> $author,
        ]))->id;
    }

    public function reservations(){
        return $this->hasMany(Reservation::class);
    }

    public function checkout(User $user){
        $this->reservations()->create([
            'user_id' => $user->id,
            'checked_out_at'=> now(),
        ]);
    }

    public function checkin(User $user){
        $reservation = $this->reservations()
            ->where('user_id', $user->id)
            ->whereNotNull('checked_out_at')
            ->whereNull('checked_in_at')
            ->first();
        if(is_null($reservation)){
            return 0;
        }
        $reservation->update([
            'checked_in_at'=> now(),
        ]);
    }
}

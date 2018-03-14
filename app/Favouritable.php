<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

trait Favouritable {
    public function favourites(){
        return $this->morphMany(Favourite::class, 'favourited');
    }

    public function favourite(){
        $attributes = ['user_id' => auth()->id()];
        if(!$this->favourites()->where($attributes)->exists()){
            return $this->favourites()->create($attributes);
        }        
    }
    public function isFavourited(){
        return $this->favourites()->where('user_id', auth()->id())->exists();
    }
    public function getFavouritesCountAttribute(){
        return $this->favourites->count();
    }
}
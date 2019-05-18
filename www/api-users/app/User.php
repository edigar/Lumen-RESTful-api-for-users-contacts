<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    protected $perPage = 10;
    protected $appends = ['links'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'celphone_number', 'phone_number', 'instagram_url', 'facebook_url', 'twitter_url'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id',
    ];

    public function setNameAttribute(string $name) {
        $this->attributes['name'] = mb_convert_case($name, MB_CASE_TITLE);
    }

    public function getLinksAttribute() {
        return ['self' => '/api/users/' . $this->id];
    }
}

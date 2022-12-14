<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getData()
    {
        $data = json_decode($this->data);
        if (!property_exists($data, 'info')) {
            $data->info = new \StdClass;
        }
        if (!property_exists($data, 'public')) {
            $data->public = new \StdClass;
        }

        if (!property_exists($data->info, 'name')) {
            $data->info->name = $this->name;
        }
        return $data;
    }

    public static function findByLoginID($login_id)
    {
        return User::where('ids', '@>', json_encode($login_id))->first();
    }

    public function isServiceUserPublic($service_user)
    {
        $public = $this->getData()->public;
        if (is_scalar($service_user)) {
            $service_user = ServiceUser::find($service_user);
        }
        if (property_exists($public, $service_user->id)) {
            return $public->{$service_user->id};
        }
        return $service_user->service->getData()->public;
    }

    public function updateServiceUsers()
    {
        $d = $this->getData();
        if (!property_exists($d, 'service_users')) {
            $d->service_users = [];
        }
        $ids = json_decode($this->ids);
        foreach (ServiceUser::searchByIds($ids) as $su) {
            $d->service_users[] = $su->id;
        }
        $this->data = json_encode($d);
        $this->save();
    }
}

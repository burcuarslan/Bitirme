<?php

    namespace App\Models;

    // use Illuminate\Contracts\Auth\MustVerifyEmail;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Laravel\Sanctum\HasApiTokens;
    use Tymon\JWTAuth\Contracts\JWTSubject;

    class User extends Authenticatable implements JWTSubject
    {
        use HasApiTokens, HasFactory, Notifiable;

        protected $table      = 'users';
        protected $primaryKey = 'id';
        public    $timestamps = false;
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
//            'password',
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

        public function orders()
        {
            return $this->hasMany('App\Models\Order');
        }

        public function statistic()
        {
            return $this->hasOne('App\Models\Statistic');
        }

        public function wallet()
        {
            return $this->belongsTo('App\Models\Wallet');
        }

        public function prices()
        {
            return $this->hasMany('App\Models\Price');
        }

        public function getJWTIdentifier()
        {
            return $this->getKey();
        }

        public function getJWTCustomClaims()
        {
            return [
                'userID'      => $this->userID,
                'userType'    => $this->userType,
            ];
        }
    }

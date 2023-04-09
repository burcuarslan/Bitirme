<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class Order extends Model
    {
        use HasFactory;

        protected $table      = 'orders';
        protected $primaryKey = 'id';
        public    $timestamps = false;

        protected $fillable = [
            'recipientId',
            'providerId',
            'priceId',
            'status',
            'createdAt',
            'completedAt',
            'canceledAt',
            'isWin',
            'isSuccess',
            'description',
        ];
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        public function price():HasMany
        {
            return $this->hasMany(Price::class,'id','priceId');
        }

    }

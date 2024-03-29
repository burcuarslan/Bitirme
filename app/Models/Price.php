<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\DB;

    class Price extends Model
    {
        use HasFactory;

        protected $table      = 'prices';
        protected $primaryKey = 'id';
        public    $timestamps = false;
        protected $fillable   = [
            'providerId',
            'categoryId',
            'pricePerMinute',
            'priceSecond',
            'price',
        ];

        public function user()
        {
            return $this->belongsTo(User::class);
        }

        public function match()
        {
            return $this->belongsTo(MatchCategory::class);
        }

        public function orders()
        {
            return $this->hasMany(Order::class, 'priceId', 'id');
        }

        public static function getAllPrices()
        {
            $allPrices = DB::table('prices')
                           ->join('users', 'users.id', '=', 'prices.providerId')
                           ->join('match_categories', 'match_categories.id', '=', 'prices.categoryId')
                           ->select('prices.*', 'users.userName as providerName', 'match_categories.categoryName as categoryName')
                           ->get();
            return $allPrices;
        }

        public static function meWithoutAllPrices($userId)
        {
            $allPrices = DB::table('prices')
                           ->join('users', 'users.id', '=', 'prices.providerId')
                           ->join('match_categories', 'match_categories.id', '=', 'prices.categoryId')
                           ->select('prices.*', 'users.userName as providerName', 'match_categories.categoryName as categoryName')
                           ->whereNot('prices.providerId', $userId)
                            ->where('prices.isActive', true)
                           ->get();
            return $allPrices;
        }

        public static function getByPriceCount($userId)
        {
            try {
                $priceCount = DB::table('prices')
                                ->where('providerId', $userId)
                                ->count();
                return $priceCount;
            } catch (\Exception $e) {
                return $e->getMessage();
            }

        }

        public static function getById($userId, $priceId)
        {
            try {
                $price = DB::table('prices')
                           ->where('id', $priceId)
                           ->where('providerId', $userId)
                           ->first();
                return $price;
            } catch (\Exception $e) {
                return $e->getMessage();
            }

        }

        public static function updatePrice($price, $isActive)
        {
//        dd($price->id);
            $temp = (bool)$isActive;
            try {
                $updatedPrice = DB::table('prices')
                                  ->where('id', $price->id)
                                  ->update([
                                               'isActive' => $temp,
                                           ]);
                return $updatedPrice;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }

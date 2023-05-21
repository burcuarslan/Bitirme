<?php

    namespace Database\Seeders;

    // use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;
    use PhpParser\Node\Expr\Array_;

    class DatabaseSeeder extends Seeder
    {
        /**
         * Seed the application's database.
         *
         * @return void
         */
        public function run()
        {
            // \App\Models\User::factory(10)->create();

            // \App\Models\User::factory()->create([
            //     'name' => 'Test User',
            //     'email' => 'test@example.com',
            // ]);
            $categoryList = ['spike rush', 'competitive', 'escalation', 'deathmatch', 'replication', 'unrated'];
            foreach ($categoryList as $category) {
                \App\Models\MatchCategory::create([
                    'categoryName' => $category,
                ]);
            }
        }
    }

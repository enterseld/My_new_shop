<?php

namespace Database\Seeders;

use App\Models\CommentsReplies;
use App\Models\ProductComments;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductComments::create([
            
            'comment' => 'daafvacv',
            'rating' => '4',
            'product_id' => '1'

        ]);
        ProductComments::create([
            
            'comment' => 'testdaafvacvafavascz',
            'rating' => '3',
            'product_id' => '1'

        ]);
        CommentsReplies::create([
            
            'reply' => 'testdaafvacvafavascz',

            'product_comments_id' => '2'

        ]);
    }
}

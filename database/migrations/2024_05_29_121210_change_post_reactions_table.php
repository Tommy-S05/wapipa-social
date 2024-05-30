<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
//        Schema::table('post_reactions', function (Blueprint $table) {
//            $table->dropForeign(['post_id']);
//            $table->dropColumn('post_id');
//            $table->after('user_id', function (Blueprint $table) {
//                $table->morphs('reactable');
//            });
//            // Change the column type from string to enum
//            $table->enum('reaction', [
//                'like',
//                'dislike',
//                'love',
//                'haha',
//                'wow',
//                'sad',
//                'angry',
//                'care',
//                'support',
//                'thankful',
//                'pray',
//                'celebrate',
//            ])->change();
//        });
//
//        Schema::rename('post_reactions', 'reactions');
//
//        DB::table('reactions')->update(['reactable_type' => 'App\Models\Post']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
//        Schema::rename('reactions', 'post_reactions');
//
//        Schema::table('post_reactions', function (Blueprint $table) {
//            $table->foreignId('post_id')->after('id')->constrained();
//            $table->dropMorphs('reactable');
//            $table->string('reaction')->change();
//        });
    }
};

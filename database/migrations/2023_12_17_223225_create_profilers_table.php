<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profilers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('word_id')->nullable();
            $table->unsignedBigInteger('word_nrzestawu')->nullable();
            $table->timestamps();

            

            // Dodanie klucza obcego
            // $table->dropForeign('profilers_word_id_foreign');
            // $table->foreign('word_id')->references('id')->on('words');

            // $table->unsignedBigInteger('word_id')->nullable();
            // // $table->foreign('word_id')->references('id')->on('word');

            // $table->unsignedBigInteger('word_nrzestawu')->nullable();
            // // $table->foreign('word_nrzestawu')->references('id')->on('word');

            // tyle kolumn ilu użytkowników
            // $users = User::count();
            // $table->string('nazwa_pola_'.$user);

            // $users = User::all();
            // // $users = User::count();
            // // $users = 5;
            // // for($x=0;$x<$users;$x++){
            // foreach($users as $user){
            //     $table->string('kolumna_'.$user);
            // }

            // // for($x=1;$x<$users;$x++){
            // //     $table->string('cos_'.$x);
            // // }
        });
    }

    public function down()
    {
        Schema::dropIfExists('profilers');
    }
};

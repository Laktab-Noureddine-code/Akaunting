<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('template')->nullable()->after('footer');
            $table->string('color')->nullable()->after('template');
        });
    }
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('template');
            $table->dropColumn('color');
        });
    }
};

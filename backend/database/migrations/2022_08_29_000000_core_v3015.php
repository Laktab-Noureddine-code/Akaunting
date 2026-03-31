<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up()
    {
        Schema::table('documents', function(Blueprint $table) {
            $table->index('contact_id');
        });
        Schema::table('user_companies', function(Blueprint $table) {
            $table->index('user_id');
            $table->index('company_id');
        });
        Schema::table('user_roles', function(Blueprint $table) {
            $table->index('user_id');
            $table->index('role_id');
        });
        Schema::table('transactions', function(Blueprint $table) {
            $table->index('number');
        });
        Schema::table('roles', function(Blueprint $table) {
            $table->index('name');
        });
    }
    public function down()
    {
    }
};

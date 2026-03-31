<?php
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up()
    {
        if (! Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }
        Schema::table('document_items', function(Blueprint $table) {
            $table->double('quantity', 12, 2)->change();
        });
        Schema::table('reconciliations', function (Blueprint $table) {
            $table->text('transactions')->nullable()->after('closing_balance');
        });
    }
    public function down()
    {
    }
};

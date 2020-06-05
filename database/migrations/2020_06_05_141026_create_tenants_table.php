<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $tableNames = config('dweller.table_names');

        // creates the tenants table
        Schema::create(
            $tableNames['tenants_table'],
            static function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('slug')->unique();
                $table->boolean('is_solo')->default(false);
                $table->timestamps();
            }
        );

        // update the users table
        Schema::table(
            $tableNames['users_table'],
            static function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable();
            }
        );

        // makes user e-mails unique per tenant, so a user
        // can belong to multiple tenants if necessary

        $userCanBelongToMultipleTenants = config('dweller.multiple_tenants_per_email_address');

        if ($userCanBelongToMultipleTenants) {
            Schema::table(
                $tableNames['users_table'],
                static function (Blueprint $table) use ($tableNames) {
                    $table->dropUnique($tableNames['users_table'].'_email_unique');
                    $table->unique(['tenant_id', 'email']);
                }
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        $tableNames = config('dweller.table_names');

        // update the users table
        Schema::table(
            $tableNames['users_table'],
            static function (Blueprint $table) {
                $table->dropColumn('tenant_id');
            }
        );

        $emailAddressCanBelongToMultipleTenants = config('dweller.multiple_tenants_per_email_address');

        if ($emailAddressCanBelongToMultipleTenants) {
            Schema::table(
                $tableNames['users_table'],
                static function (Blueprint $table) use ($tableNames) {
                    $table->dropUnique($tableNames['users_table'].'_tenant_id_email_unique');
                    $table->string('email')->unique()->change();
                }
            );
        }

        // drop last because of foreign constraints
        Schema::dropIfExists($tableNames['tenants_table']);
    }
}

<?php

namespace Tests;

use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\MySqlGrammar;
use Illuminate\Database\Schema\Grammars\PostgresGrammar;
use Illuminate\Database\Schema\Grammars\SQLiteGrammar;
use Illuminate\Database\Schema\Grammars\SqlServerGrammar;
use JarrodTomholt\BelongsToMacro\BelongsToMacroServiceProvider;

class DatabaseBlueprintMacroTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            BelongsToMacroServiceProvider::class,
        ];
    }

    /** @test */
    public function it_creates_a_foreignIdFor_with_index_and_constraints_for_mysql(): void
    {
        $base = new Blueprint('posts', function ($table) {
            $table->belongsTo('Illuminate\Foundation\Auth\User');
        });

        $connection = $this->mock(Connection::class);

        $blueprint = clone $base;

        $this->assertEquals([
            'alter table `posts` add `user_id` bigint unsigned not null',
            'alter table `posts` add constraint `posts_user_id_foreign` foreign key (`user_id`) references `users` (`id`)',
            'alter table `posts` add index `posts_user_id_index`(`user_id`)',
        ], $blueprint->toSql($connection, new MySqlGrammar));
    }

    /** @test */
    public function it_creates_a_foreignIdFor_with_index_and_constraints_for_postgres(): void
    {
        $base = new Blueprint('posts', function ($table) {
            $table->belongsTo('Illuminate\Foundation\Auth\User');
        });

        $connection = $this->mock(Connection::class);

        $blueprint = clone $base;

        $this->assertEquals([
            'alter table "posts" add column "user_id" bigint not null',
            'alter table "posts" add constraint "posts_user_id_foreign" foreign key ("user_id") references "users" ("id")',
            'create index "posts_user_id_index" on "posts" ("user_id")',
        ], $blueprint->toSql($connection, new PostgresGrammar));
    }

    /** @test */
    public function it_creates_a_foreignIdFor_with_index_and_constraints_for_sqlserver(): void
    {
        $base = new Blueprint('posts', function ($table) {
            $table->belongsTo('Illuminate\Foundation\Auth\User');
        });

        $connection = $this->mock(Connection::class);

        $blueprint = clone $base;

        $this->assertEquals([
            'alter table "posts" add "user_id" bigint not null',
            'alter table "posts" add constraint "posts_user_id_foreign" foreign key ("user_id") references "users" ("id")',
            'create index "posts_user_id_index" on "posts" ("user_id")',
        ], $blueprint->toSql($connection, new SqlServerGrammar));
    }

    /** @test */
    public function it_creates_a_foreignIdFor_with_index_sqlite(): void
    {
        $base = new Blueprint('posts', function ($table) {
            $table->belongsTo('Illuminate\Foundation\Auth\User');
        });

        $connection = $this->mock(Connection::class);

        $blueprint = clone $base;

        $this->assertEquals([
            'alter table "posts" add column "user_id" integer not null',
            'create index "posts_user_id_index" on "posts" ("user_id")',
        ], $blueprint->toSql($connection, new SQLiteGrammar));
    }

    /** @test */
    public function it_creates_a_nullable_foreignIdFor_with_index_and_constraints_for_mysql(): void
    {
        $base = new Blueprint('posts', function ($table) {
            $table->belongsToOrNull('Illuminate\Foundation\Auth\User');
        });

        $connection = $this->mock(Connection::class);

        $blueprint = clone $base;

        $this->assertEquals([
            'alter table `posts` add `user_id` bigint unsigned null',
            'alter table `posts` add constraint `posts_user_id_foreign` foreign key (`user_id`) references `users` (`id`)',
            'alter table `posts` add index `posts_user_id_index`(`user_id`)',
        ], $blueprint->toSql($connection, new MySqlGrammar));
    }

    /** @test */
    public function it_creates_a_nullable_foreignIdFor_with_index_and_constraints_for_postgres(): void
    {
        $base = new Blueprint('posts', function ($table) {
            $table->belongsToOrNull('Illuminate\Foundation\Auth\User');
        });

        $connection = $this->mock(Connection::class);

        $blueprint = clone $base;

        $this->assertEquals([
            'alter table "posts" add column "user_id" bigint null',
            'alter table "posts" add constraint "posts_user_id_foreign" foreign key ("user_id") references "users" ("id")',
            'create index "posts_user_id_index" on "posts" ("user_id")',
        ], $blueprint->toSql($connection, new PostgresGrammar));
    }

    /** @test */
    public function it_creates_a_nullable_foreignIdFor_with_index_and_constraints_for_sqlserver(): void
    {
        $base = new Blueprint('posts', function ($table) {
            $table->belongsToOrNull('Illuminate\Foundation\Auth\User');
        });

        $connection = $this->mock(Connection::class);

        $blueprint = clone $base;

        $this->assertEquals([
            'alter table "posts" add "user_id" bigint null',
            'alter table "posts" add constraint "posts_user_id_foreign" foreign key ("user_id") references "users" ("id")',
            'create index "posts_user_id_index" on "posts" ("user_id")',
        ], $blueprint->toSql($connection, new SqlServerGrammar));
    }

    /** @test */
    public function it_creates_a_nullable_foreignIdFor_with_index_for_sqlite(): void
    {
        $base = new Blueprint('posts', function ($table) {
            $table->belongsToOrNull('Illuminate\Foundation\Auth\User');
        });

        $connection = $this->mock(Connection::class);

        $blueprint = clone $base;

        $this->assertEquals([
            'alter table "posts" add column "user_id" integer',
            'create index "posts_user_id_index" on "posts" ("user_id")',
        ], $blueprint->toSql($connection, new SQLiteGrammar));
    }

    /** @test */
    public function it_allows_an_optional_column_name_to_be_specified_in_belongsTo_macro(): void
    {
        $base = new Blueprint('posts', function ($table) {
            $table->belongsTo('Illuminate\Foundation\Auth\User', 'foo_id');
        });

        $connection = $this->mock(Connection::class);

        $blueprint = clone $base;

        $this->assertEquals([
            'alter table `posts` add `foo_id` bigint unsigned not null',
            'alter table `posts` add constraint `posts_foo_id_foreign` foreign key (`foo_id`) references `foos` (`id`)',
            'alter table `posts` add index `posts_foo_id_index`(`foo_id`)',
        ], $blueprint->toSql($connection, new MySqlGrammar));
    }

    /** @test */
    public function it_allows_an_optional_column_name_to_be_specified_in_belongsToOrNull_macro(): void
    {
        $base = new Blueprint('posts', function ($table) {
            $table->belongsToOrNull('Illuminate\Foundation\Auth\User', 'foo_id');
        });

        $connection = $this->mock(Connection::class);

        $blueprint = clone $base;

        $this->assertEquals([
            'alter table `posts` add `foo_id` bigint unsigned null',
            'alter table `posts` add constraint `posts_foo_id_foreign` foreign key (`foo_id`) references `foos` (`id`)',
            'alter table `posts` add index `posts_foo_id_index`(`foo_id`)',
        ], $blueprint->toSql($connection, new MySqlGrammar));
    }
}
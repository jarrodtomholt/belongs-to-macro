<?php

namespace JarrodTomholt\BelongsToMacro;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;

class BelongsToMacroServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blueprint::macro('belongsTo', function (String $model, Null|String $column = null) {
            /* @var Blueprint $this */
            return $this->foreignIdFor($model, $column)->index()->constrained();
        });

        Blueprint::macro('belongsToOrNull', function (String $model, Null|String $column = null) {
            /* @var Blueprint $this */
            return $this->foreignIdFor($model, $column)->nullable()->index()->constrained();
        });
    }
}

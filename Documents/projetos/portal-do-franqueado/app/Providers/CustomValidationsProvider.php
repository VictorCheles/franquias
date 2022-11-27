<?php

namespace App\Providers;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class CustomValidationsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('date_range', function ($attribute, $value, $parameters, $validator) {
            try {
                list($first, $last) = explode(' - ', $value);

                return Carbon::createFromFormat('d/m/Y', $first)->lte(Carbon::createFromFormat('d/m/Y', $last));
            } catch (\Exception $exception) {
                return false;
            }
        });

        Validator::extend('cpf', function ($attribute, $value, $parameters, $validator) {
            $sanitize = function ($value) {
                return str_replace(['-', '.'], '', $value);
            };

            $value = $sanitize($value);

            $c = preg_replace('/\D/', '', $value);
            if (strlen($c) != 11 || preg_match("/^{$c[0]}{11}$/", $c)) {
                return false;
            }
            for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);
            if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
                return false;
            }
            for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);
            if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
                return false;
            }

            return true;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

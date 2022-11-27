<?php

if (!function_exists('unmaskMoney')) {
    function unmaskMoney($value)
    {
        $value = str_replace(['R$ ', '.'], '', $value);
        $value = str_replace(',', '.', $value);

        return $value;
    }
}

if (!function_exists('maskMoney')) {
    function maskMoney($value, $decimals = 2)
    {
        return 'R$ ' . number_format($value, $decimals, ',', '.');
    }
}

if (!function_exists('makeFileName')) {
    function makeFileName($request, $key)
    {
        $ext = $request->file($key)->getClientOriginalExtension();

        return str_slug(str_replace('.' . $ext, '', $request->file($key)->getClientOriginalName()) . '-' . microtime()) . '.' . $ext;
    }
}

if (!function_exists('calendario')) {
    function calendario($mes, $ano)
    {
        $days = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
        $first_day = date('w', strtotime($ano . '/' . $mes . '/' . '1'));
        $cal = [];
        $row = 0;
        $col = 0;

        for ($i = 0; $i < $first_day; $i++) {
            $cal[$row][$col] = '&nbsp;';
            $col++;
        }

        for ($i = 1; $i <= $days; $i++) {
            $cal[$row][$col++] = $i;
            if ($col == 7) {
                $col = 0;
                $row++;
            }
        }

        for ($i = $col; $i < 7; $i++) {
            $cal[$row][$col++] = '&nbsp;';
        }

        return $cal;
    }
}

if (!function_exists('callFormSelectMacro')) {
    function callMacroFormSelect()
    {
        \Form::macro('selectWithDisabledOption', function ($name, $list = [], $selected = null, $options = [], $disabled = []) {

            // When building a select box the "value" attribute is really the selected one
            // so we will use that when checking the model or session for a value which
            // should provide a convenient method of re-populating the forms on post.
            $selected = $this->getValueAttribute($name, $selected);

            $options['id'] = $this->getIdAttribute($name, $options);

            if (!isset($options['name'])) {
                $options['name'] = $name;
            }

            // We will simply loop through the options and build an HTML value for each of
            // them until we have an array of HTML declarations. Then we will join them
            // all together into one single HTML element that can be put on the form.
            $html = [];

            if (isset($options['placeholder'])) {
                $html[] = $this->placeholderOption($options['placeholder'], $selected);
                unset($options['placeholder']);
            }

            foreach ($list as $value => $display) {
                if (in_array($value, $disabled)) {
                    $html[] = '<option disabled value>' . $display . ' - (Você já esteve aqui!!)</option>';
                } else {
                    $html[] = $this->getSelectOption($display, $value, $selected);
                }
            }

            // Once we have all of this HTML, we can join this into a single element after
            // formatting the attributes into an HTML "attributes" string, then we will
            // build out a final select statement, which will contain all the values.
            $options = $this->html->attributes($options);

            $list = implode('', $html);

            return $this->toHtmlString("<select{$options}>{$list}</select>");
        });
    }
}

if (!function_exists('rankingTrophies')) {
    function rankingTrophies($ranking, $size = 12)
    {
        $colors = [
            1 => '#FFD700', #gold
            2 => '#c0c0c0', #silver
            3 => '#cd7f32',  #bronze
        ];
        if (in_array($ranking, array_keys($colors))) {
            return '<i class="fa fa-trophy fa-2x" style="color: ' . $colors[$ranking] . ';"></i>';
        } else {
            return $ranking;
        }
    }
}

if (!function_exists('menuActive')) {
    function menuActive(\Illuminate\Support\Collection $routes)
    {
        return $routes->search(url()->current(), true) !== false ? 'active' : '';
//        if($find)
//        {
//            return 'active';
//        }
//        else
//        {
//            $active = false;
//            $routes->each(function ($route) use (&$active){
//                if(str_is($route. '*', url()->current()))
//                {
//                    $active = true;
//                }
//            });
//
//            return $active ? 'active' : '';
//        }
    }
}

if (!function_exists('monthName')) {
    function monthName($month)
    {
        $months = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',
        ];

        return $months[$month];
    }
}

if (!function_exists('paginationCounters')) {
    function paginationCounters($paginator, $max_per_page = 15, $page_param = 'page')
    {
        ob_start();
        if (Request::get($page_param) == 1 or !Request::get($page_param)) {
            printf('%d - %d / %d', $paginator->count() ? '1' : '0', $max_per_page + ($paginator->count() - $max_per_page), $paginator->total());
        } else {
            printf('%d - %d / %d', $max_per_page * (Request::get($page_param) - 1) + 1, $max_per_page * Request::get($page_param) + ($paginator->count() - $max_per_page), $paginator->total());
        }

        return ob_get_clean();
    }
}

if (!function_exists('urlClienteOculto')) {
    function urlClienteOculto()
    {
        $full_url = url()->current();
        if (str_contains($full_url, 'www')) {
            $domain_oculto = 'www.' . str_replace('cupons.', '', implode('.', [env('APP_SUBDOMAIN_AVALIADOR_OCULTO'), env('APP_DOMAIN')]));
        } else {
            $domain_oculto = str_replace('cupons.', '', implode('.', [env('APP_SUBDOMAIN_AVALIADOR_OCULTO'), env('APP_DOMAIN')]));
        }

        if (!strpos('https://', $domain_oculto)) {
            $domain_oculto = 'https://' . $domain_oculto;
        }

        return $domain_oculto;
    }
}

if (!function_exists('urlCupons')) {
    function urlCupons()
    {
        $full_url = url()->current();
        if (str_contains($full_url, 'www')) {
            $domain_cupons = 'www.' . env('APP_DOMAIN');
        } else {
            $domain_cupons = env('APP_DOMAIN');
        }

        if (!strpos('https://', $domain_cupons)) {
            $domain_cupons = 'https://' . $domain_cupons;
        }

        return $domain_cupons;
    }
}

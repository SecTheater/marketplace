<?php

use SecTheater\Marketplace\Exceptions\UndefinedMethodException;
if (!function_exists('package_path')) {
    function package_path($path = null)
    {
        return base_path('vendor/sectheater/marketplace/src').DIRECTORY_SEPARATOR.ltrim($path, '/');
    }
}
if (!function_exists('model_exists')) {
    function model_exists($name)
    {
        return File::exists(str_replace('\\', DIRECTORY_SEPARATOR, base_path(lcfirst(ltrim(config('market.models.namespace'), '\\'))).ucfirst($name).'.php'));
    }
}
if (!function_exists('repository_exists')) {
    function repository_exists($name)
    {
        return File::exists(package_path('Repositories/' . $name . 'Repository.php'));
    }
}
if (!function_exists('package_version')) {
    function package_version($packageName)
    {
        $file = base_path('composer.lock');
        $packages = json_decode(file_get_contents($file), true)['packages'];
        foreach ($packages as $package) {
            if (explode('/', $package['name'])[1] == $packageName) {
                return $package['version'];
            }
        }
        throw new \Exception('Package Does not exist', 500);
    }
}
if (!function_exists('repository')) {
    function repository($name)
    {
        $models = config('market.models.package');
        $name = str_replace('Repository', '', $name);
        $model = model($name);
        if (!in_array(strtolower($name) , config('market.repositories.user'))) {
            $repository = '\\SecTheater\\Marketplace\\Repositories\\' . ucfirst($name) . 'Repository';
        }
        if (in_array(strtolower($name) , config('market.repositories.user'))) {
            $repository = config('market.repositories.user')[strtolower($name)];
        }
        if (!isset($repository)) {
            throw new \Exception('Repository does not exist');
        }
        return new $repository($model);
    }
}
if (!function_exists('market_model_exists')) {
    function market_model_exists($name)
    {   
        if (str_contains($name, 'Eloquent')) {
            return File::exists(__DIR__.'/../Models/'.ucfirst($name).'.php');
        }
        return File::exists(__DIR__.'/../Models/Eloquent'.ucfirst($name).'.php');
    }
}
if (!function_exists('model')) {
    function model(string $name, array $attributes = [])
    {
        $name = ucfirst(str_replace('Eloquent', '', $name));

        if (File::exists(str_replace('\\', DIRECTORY_SEPARATOR, base_path(lcfirst(ltrim(config('market.models.namespace'), '\\'))).$name.'.php'))) {
            if (array_key_exists(lcfirst($name), config('market.models.user'))) {
                $model = config('market.models.user')[lcfirst($name)];
            } elseif (model_exists($name)) {
                $model = config('market.models.namespace').$name;
            } else {
                $model = config('market.models.package')[lcfirst($name)];
            }
            return new $model($attributes);
        } elseif (File::exists(__DIR__.'/../Models/Eloquent'.$name.'.php')) {
            $model = '\\SecTheater\\Marketplace\\Models\\Eloquent'. $name;
            return new $model($attributes);
        }
        throw new UndefinedMethodException("Model $name Does not exist", 500);
    }
}

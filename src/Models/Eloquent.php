<?php
namespace SecTheater\Marketplace\Models;
use Illuminate\Database\Eloquent\Model;
class Eloquent extends Model
{
    protected $guarded = ['id'];
    public function __get($name)
    {
        if (!property_exists(static::class, $name) && str_contains($name, 'Model')) {
            $model = str_replace('Model', '', $name);
            if (model_exists($model) && app()->environment() != 'testing') {
                $key = 'market.models.user.'.$model;
                $value = config('market.models.namespace').ucfirst($model);
                config([$key => $value]);
                $this->{$name} = $value;
            } elseif (market_model_exists($model)) {
                $this->{$name} = config('market.models.package.'.snake_case($model));
            }
            return $this->{$name} ?? null;
        }
        return parent::__get($name);
    }
    public function __set($key, $value)
    {
        if ($this->getAttribute($key) || \Schema::hasColumn($this->table, $key)) {
            parent::__set($key, $value);
            return;
        }
        $this->{$key} = $value;
    }
}
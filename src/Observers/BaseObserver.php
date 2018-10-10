<?php
namespace SecTheater\Marketplace\Observers;
use Illuminate\Database\Eloquent\Model;
class BaseObserver
{
    protected $grammar = [
        'slug'            => 'str_slug',
        'title'           => 'title_case',
        'studly'          => 'studly_case',
        'firstUpper'      => 'ucfirst',
        'firstLower'      => 'lcfirst',
        'allUpper'        => 'strtoupper',
        'allLower'        => 'strtolower',
        'startsWithUpper' => 'ucwords',
    ];
    protected function fireObserversListeners(Model $model)
    {
        foreach ($model->observers as $field => $listener) {
            if (!$this->hasListenerOption($listener) && !$this->isExistingFunction($listener)) {
                throw new \Exception("$listener Action neither an available option nor  an existing as a function.");
            }
            $this->fireListener($field, $listener, $model);
        }
    }
    protected function fireListener($field, $listener, $model)
    {
        if ($this->isExistingFunction($listener)) {
            $model->{$field} = snake_case($listener)($model->{$field});
        }
        if ($this->hasListenerOption($listener)) {
            $model->{$field} = $this->grammar[$listener]($model->{$field});
        }
    }
    protected function isExistingFunction($listener)
    {
        return function_exists(snake_case($listener));
    }
    protected function hasListenerOption($listener)
    {
        return array_key_exists($listener, $this->grammar);
    }
    public function fillWithAttributeOrOriginal(Model $model)
    {
        foreach ($model->getFillable() as $fillable) {
            $model->$fillable = $model->getAttributes()[$fillable] ?? $model->getOriginal($fillable);
        }
    }
}
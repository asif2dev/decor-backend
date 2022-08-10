<?php

namespace App\Http\DTO;

use App\Models\Professional as ProfessionalModel;
use App\Support\Str;
use Illuminate\Database\Eloquent\Model;
use ReflectionObject;
use ReflectionProperty;

class Factory
{
    public function fromModel(ProfessionalModel $model, string $class): Professional
    {
        /** @var Professional $class */
        $class = new $class();

        return $this->fillObject($model, $class);
    }

    public function fillObject(Model $model, Professional $class): Professional
    {
        $vars = (new ReflectionObject($class))->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($vars as $property) {
            /** @var ReflectionProperty $property */
            $modelKey = Str::snake($property->name);
            if(method_exists($class, $property->name)) {
                $class->{$property->name} = $class->{$property->name}($model);
            } else {
                $class->{$property->name}  = $model->{$modelKey};
            }
        }

        return $class;
    }
}

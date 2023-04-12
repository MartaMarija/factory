<?php

namespace App\Models;

trait HasTimestamps
{
    public function update(): static
    {
        $primaryKeyValue = $this->{static::$primaryKeyName};
        if (!isset($primaryKeyValue)) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = date('Y-m-d H:i:s');
        return parent::update();
    }
}
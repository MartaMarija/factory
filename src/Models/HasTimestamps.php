<?php

namespace App\Models;

trait HasTimestamps
{
    public function save(): static
    {
        $this->created_at = date('Y-m-d H:i:s');
        return parent::save();
    }
    
    public function update(): static
    {
        $primaryKeyValue = $this->{static::$primaryKeyName};
        if (!isset($primaryKeyValue)) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = date('Y-m-d H:i:s');
        return parent::update();
    }
    
    public function softDelete(): static
    {
        if ($this->deleted_at != null) {
            return $this;
        }
        $this->deleted_at = date('Y-m-d H:i:s');
        return parent::update();
    }
}

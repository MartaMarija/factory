<?php

namespace App\Models;

trait HasTimestamps
{
    public function save(): static
    {
        $now = $this->dateFormat();
        $this->created_at = $now;
        $this->updated_at = $now;
        return parent::save();
    }
    
    public function update(): static
    {
        $now = $this->dateFormat();
        $primaryKeyValue = $this->{static::$primaryKeyName};
        if (!isset($primaryKeyValue)) {
            $this->created_at = $now;
        }
        $this->updated_at = $now;
        return parent::update();
    }
    
    public function softDelete(): static
    {
        if ($this->deleted_at != null) {
            return $this;
        }
        $now = $this->dateFormat();
        $this->updated_at = $now;
        $this->deleted_at = $now;
        return parent::update();
    }
    
    protected function dateFormat(): string
    {
        return date('Y-m-d H:i:s');
    }
}

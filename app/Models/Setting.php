<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'label',
    ];

    /**
     * Get the value attribute.
     *
     * @param  string  $value
     * @return mixed
     */
    public function getValueAttribute($value)
    {
        if ($this->type === 'boolean') {
            return (bool) $value;
        }

        if ($this->type === 'json') {
            return json_decode($value, true);
        }

        return $value;
    }

    /**
     * Set the value attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setValueAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['value'] = json_encode($value);
            $this->attributes['type'] = 'json';
            return;
        }

        if (is_bool($value)) {
            $this->attributes['value'] = $value ? 1 : 0;
            $this->attributes['type'] = 'boolean';
            return;
        }

        $this->attributes['value'] = $value;
    }
}

<?php

namespace App\Traits;

trait HasEncodedId
{
    /**
     * Get the encoded ID for use in public URLs
     * 
     * @return string
     */
    public function getEncodedId()
    {
        $encodedId = base64_encode('club-' . $this->id . '-' . time());

        $encodedId = str_replace(['+', '/', '='], ['-', '_', ''], $encodedId);

        return $encodedId;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'encoded_id';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if ($field && $field !== 'encoded_id') {
            return parent::resolveRouteBinding($value, $field);
        }

        try {
            $value = str_replace(['-', '_'], ['+', '/'], $value);
            $paddingLength = strlen($value) % 4;
            if ($paddingLength) {
                $value .= str_repeat('=', 4 - $paddingLength);
            }

            $decoded = base64_decode($value);

            if (preg_match('/^club-(\d+)-\d+$/', $decoded, $matches)) {
                $id = $matches[1];
                return $this->where('id', $id)->first();
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }
}

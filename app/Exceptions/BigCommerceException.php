<?php

namespace App\Exceptions;

class BigCommerceException extends BaseException
{
    protected string $name = 'big_commerce';

    /**
     * @param  string  $message
     * @return string
     */
    protected function parseMessageFromJson(string $message): string
    {
        if (is_string($message) && is_array(json_decode($message, true))) {
            $decoded = json_decode($message, true);

            if (data_get($decoded, '0.message')) {
                return $this->formatErrorMessage(data_get($decoded, '0.message'), data_get($decoded, '0.status'));
            }

            if (data_get($decoded, 'title')) {
                return $this->formatErrorMessage(data_get($decoded, 'title'), data_get($decoded, 'status'));
            }
        }

        return $message;
    }

    /**
     * BigCommerce Resource Conflict Error.
     * This normally occurs when you try to create a duplicate resource
     * @return array|mixed
     */
    public function isConflictedResource()
    {
        return data_get($this->getContext(), 'big_commerce_error.0.status') === 409;
    }
}

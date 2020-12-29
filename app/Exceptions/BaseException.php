<?php

namespace App\Exceptions;

use Exception;

abstract class BaseException extends Exception
{
    /**
     * BaseException Constructor.
     * @param  string  $message
     * @param  array  $context
     */
    public function __construct($message = "", array $context = [])
    {
        $this->context = $context;

        parent::__construct($message);
    }

    /**
     * Provide the Exception with more context,
     * such as payloads and helpful debugging data
     * @var array
     */
    protected array $context = [];

    /**
     * Exception Name
     * @var string
     */
    protected string $name = 'base';

    /**
     * @param $message
     * @param $status
     * @return string
     */
    protected function formatErrorMessage($message, $status)
    {
        return sprintf('%s: %s', $status, $message);
    }

    /**
     * Get the Error Context.
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set additional error context for cleaner errors in Sentry.
     * @param array $context
     * @return $this
     */
    public function setContext(array $context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @param  string  $key
     * @param  string  $value
     * @return array|mixed
     */
    public function addContext(string $key, array $value)
    {
        return data_set($this->context, $key, $value);
    }

    /**
     * Set additional context for Sentry
     * Documentation: https://docs.sentry.io/platforms/php/.
     */
    protected function setSentryScope()
    {
        $contexts = collect($this->context);

        // \Sentry\configureScope(function (\Sentry\State\Scope $scope) use ($contexts) : void {
        //     $scope->setTag($this->name, true);
        //     $scope->setExtra('payload', $contexts->toArray());
        //
        //     // Attach the current user to the request
        //     if (auth('sanctum')->check()) {
        //         $scope->setUser([
        //             'id' => auth('sanctum')->user()->id,
        //             'email' => auth('sanctum')->user()->email,
        //         ]);
        //     }
        //
        // });

        if (app()->bound('sentry')) {
            app('sentry')->captureException($this);
        }
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        $this->setSentryScope();
    }

    /**
     * Handle the error Rendering.
     * @param $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            return $this->handleAjax();
        }

        return redirect()->back()
            ->withInput()
            ->withErrors($this->getMessage());
    }

    /**
     * Handle an ajax response.
     */
    private function handleAjax()
    {
        return response()->json([
            'message' => $this->getMessage(),
            'errors' => $this->getContext(),
        ], $this->context[$this->name.'_error']['status'] ?? 500);
    }
}

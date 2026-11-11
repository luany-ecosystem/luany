<?php

namespace App\Exceptions;

use Luany\Core\Http\Response;
use Luany\Framework\Exceptions\Handler as BaseHandler;

class Handler extends BaseHandler
{
    /**
     * Exception classes that should not be logged.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected array $dontReport = [];

    /**
     * Render errors with the application's views (views/errors/{status}.lte).
     * In debug mode server errors show the framework's debug page instead.
     */
    public function render(\Throwable $e): Response
    {
        $status = $this->statusCodeFor($e);

        if ($status >= 500 && $this->debug) {
            return parent::render($e);
        }

        $view = $status === 404 ? 'errors.404' : ($status >= 500 ? 'errors.500' : null);

        if ($view === null) {
            return parent::render($e);
        }

        return Response::make(view($view), $status)->withHeaders($this->headersFor($e));
    }
}

<?php

namespace JsonMapper\Exception;

/**
 * Wrapping exception for all exceptions that occur during mapping process
 * Wrapped exception accessible under getPrevious() function.
 *
 */
class JsonMapperException extends \Exception
{
    private $path;

    public function __construct(\Exception $exception, $path)
    {
        parent::__construct($exception->getMessage(), 0, $exception);
        $this->path = $path;
    }

    /**
     * Returns mapping context path set when the exception occurred
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}

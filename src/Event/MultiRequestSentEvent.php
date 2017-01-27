<?php

/*
 * This file is part of the Ivory Http Adapter package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\HttpAdapter\Event;

use Ivory\HttpAdapter\HttpAdapterException;
use Ivory\HttpAdapter\HttpAdapterInterface;
use Ivory\HttpAdapter\Message\ResponseInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class MultiRequestSentEvent extends AbstractEvent
{
    /**
     * @var ResponseInterface[]
     */
    private $responses;

    /**
     * @var HttpAdapterException[]
     */
    private $exceptions = [];

    /**
     * @param HttpAdapterInterface $httpAdapter
     * @param ResponseInterface[]  $responses
     */
    public function __construct(HttpAdapterInterface $httpAdapter, array $responses)
    {
        parent::__construct($httpAdapter);

        $this->setResponses($responses);
    }

    public function clearResponses()
    {
        $this->responses = [];
    }

    /**
     * @return bool
     */
    public function hasResponses()
    {
        return !empty($this->responses);
    }

    /**
     * @return ResponseInterface[]
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * @param ResponseInterface[] $responses
     */
    public function setResponses(array $responses)
    {
        $this->clearResponses();
        $this->addResponses($responses);
    }

    /**
     * @param ResponseInterface[] $responses
     */
    public function addResponses(array $responses)
    {
        foreach ($responses as $response) {
            $this->addResponse($response);
        }
    }

    /**
     * @param ResponseInterface[] $responses
     */
    public function removeResponses(array $responses)
    {
        foreach ($responses as $response) {
            $this->removeResponse($response);
        }
    }

    /**
     * @param ResponseInterface $response
     *
     * @return bool
     */
    public function hasResponse(ResponseInterface $response)
    {
        return array_search($response, $this->responses, true) !== false;
    }

    /**
     * @param ResponseInterface $response
     */
    public function addResponse(ResponseInterface $response)
    {
        $this->responses[] = $response;
    }

    /**
     * @param ResponseInterface $response
     */
    public function removeResponse(ResponseInterface $response)
    {
        unset($this->responses[array_search($response, $this->responses, true)]);
        $this->responses = array_values($this->responses);
    }

    public function clearExceptions()
    {
        $this->exceptions = [];
    }

    /**
     * @return bool
     */
    public function hasExceptions()
    {
        return !empty($this->exceptions);
    }

    /**
     * @return HttpAdapterException[]
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }

    /**
     * @param HttpAdapterException[] $exceptions
     */
    public function setExceptions(array $exceptions)
    {
        $this->clearExceptions();
        $this->addExceptions($exceptions);
    }

    /**
     * @param HttpAdapterException[] $exceptions
     */
    public function addExceptions(array $exceptions)
    {
        foreach ($exceptions as $exception) {
            $this->addException($exception);
        }
    }

    /**
     * @param HttpAdapterException[] $exceptions
     */
    public function removeExceptions(array $exceptions)
    {
        foreach ($exceptions as $exception) {
            $this->removeException($exception);
        }
    }

    /**
     * @param HttpAdapterException $exception
     *
     * @return bool
     */
    public function hasException(HttpAdapterException $exception)
    {
        return array_search($exception, $this->exceptions, true) !== false;
    }

    /**
     * @param HttpAdapterException $exception
     */
    public function addException(HttpAdapterException $exception)
    {
        $this->exceptions[] = $exception;
    }

    /**
     * @param HttpAdapterException $exception
     */
    public function removeException(HttpAdapterException $exception)
    {
        unset($this->exceptions[array_search($exception, $this->exceptions, true)]);
        $this->exceptions = array_values($this->exceptions);
    }
}

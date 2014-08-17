<?php

/*
 * This file is part of the Ivory Http Adapter package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\HttpAdapter\Event\Subscriber;

use Ivory\HttpAdapter\Event\Events;
use Ivory\HttpAdapter\Event\Subscriber\CookieSubscriber;

/**
 * Cookie subscriber test.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class CookieSubscriberTest extends AbstractSubscriberTest
{
    /** @var \Ivory\HttpAdapter\Event\Subscriber\CookieSubscriber */
    protected $cookieSubscriber;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cookieSubscriber = new CookieSubscriber();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->cookieSubscriber);
    }

    public function setDefaultState()
    {
        $this->assertInstanceOf('Ivory\HttpAdapter\Event\Cookie\CookieJar', $this->cookieSubscriber->getCookieJar());
    }

    public function testInitialState()
    {
        $this->cookieSubscriber = new CookieSubscriber($cookieJar = $this->createCookieJarMock());

        $this->assertSame($cookieJar, $this->cookieSubscriber->getCookieJar());
    }

    public function testSetCookieJar()
    {
        $this->cookieSubscriber->setCookieJar($cookieJar = $this->createCookieJarMock());

        $this->assertSame($cookieJar, $this->cookieSubscriber->getCookieJar());
    }

    public function testSubscribedEvents()
    {
        $events = CookieSubscriber::getSubscribedEvents();

        $this->assertArrayHasKey(Events::PRE_SEND, $events);
        $this->assertSame('onPreSend', $events[Events::PRE_SEND]);

        $this->assertArrayHasKey(Events::POST_SEND, $events);
        $this->assertSame('onPostSend', $events[Events::POST_SEND]);
    }

    public function testPreSendEvent()
    {
        $request = $this->createRequest();

        $this->cookieSubscriber->setCookieJar($cookieJar = $this->createCookieJarMock());

        $cookieJar
            ->expects($this->once())
            ->method('populate')
            ->with($this->identicalTo($request));

        $this->cookieSubscriber->onPreSend($this->createPreSendEvent(null, $request));
    }

    public function testPostSendEvent()
    {
        $request = $this->createRequest();
        $response = $this->createResponse();

        $this->cookieSubscriber->setCookieJar($cookieJar = $this->createCookieJarMock());

        $cookieJar
            ->expects($this->once())
            ->method('extract')
            ->with($this->identicalTo($request), $this->identicalTo($response));

        $this->cookieSubscriber->onPostSend($this->createPostSendEvent(null, $request, $response));
    }

    /**
     * Creates a cookie jar mock.
     *
     * @return \Ivory\HttpAdapter\Event\Cookie\CookieJarInterface|\PHPUnit_Framework_MockObject_MockObject The cookie jar mock.
     */
    protected function createCookieJarMock()
    {
        return $this->getMock('Ivory\HttpAdapter\Event\Cookie\CookieJarInterface');
    }
}

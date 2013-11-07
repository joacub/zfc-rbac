<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace ZfcRbac\View\Strategy;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use ZfcRbac\Exception\UnauthorizedException;
use ZfcRbac\Options\UnauthorizedStrategyOptions;

/**
 * This strategy renders a specific template when a user is unauthorized
 */
class UnauthorizedStrategy extends AbstractListenerAggregate
{
    /**
     * @var UnauthorizedStrategyOptions
     */
    protected $options;

    /**
     * Constructor
     *
     * @param UnauthorizedStrategyOptions $options
     */
    public function __construct(UnauthorizedStrategyOptions $options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onError'));
    }

    /**
     * @param  MvcEvent $event
     * @return void
     */
    public function onError(MvcEvent $event)
    {
        // @TODO: is checking for UnauthorizedException is a good idea?

        // Do nothing if no error or if response is not HTTP response
        if (!$error = $event->getError()
            || !($exception = $event->getParam('exception') instanceof UnauthorizedException)
            || ($result = $event->getResult() instanceof HttpResponse)
            || !($response = $event->getResponse() instanceof HttpResponse)
        ) {
            return;
        }

        // @TODO: pass error variables

        $model = new ViewModel();
        $model->setTemplate($this->options->getTemplate());

        $response = new HttpResponse();
        $response->setStatusCode($this->options->getStatusCode());

        $event->setResult($model);
        $event->setResponse($response);
    }
}
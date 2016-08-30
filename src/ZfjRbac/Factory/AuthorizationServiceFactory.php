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

namespace ZfjRbac\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfjRbac\Service\AuthorizationService;

/**
 * Factory to create the authorization service
 *
 * @author  Michaël Gallego <mic.gallego@gmail.com>
 * @license MIT
 */
class AuthorizationServiceFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var \Rbac\Rbac $rbac */
        $rbac = $container->get('Rbac\Rbac');

        /* @var \ZfjRbac\Service\RoleService $roleService */
        $roleService = $container->get('ZfjRbac\Service\RoleService');

        /* @var \ZfjRbac\Assertion\AssertionPluginManager $assertionPluginManager */
        $assertionPluginManager = $container->get('ZfjRbac\Assertion\AssertionPluginManager');

        /* @var \ZfjRbac\Options\ModuleOptions $moduleOptions */
        $moduleOptions = $container->get('ZfjRbac\Options\ModuleOptions');

        $authorizationService = new AuthorizationService($rbac, $roleService, $assertionPluginManager);
        $authorizationService->setAssertions($moduleOptions->getAssertionMap());

        return $authorizationService;
    }


}

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

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZfjRbac\Exception\RuntimeException;
use ZfjRbac\Service\RoleService;

/**
 * Factory to create the role service
 *
 * @author  Michaël Gallego <mic.gallego@gmail.com>
 * @license MIT
 */
class RoleServiceFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return RoleService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var \ZfjRbac\Options\ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get('ZfjRbac\Options\ModuleOptions');

        /* @var \ZfjRbac\Identity\IdentityProviderInterface $identityProvider */
        $identityProvider = $serviceLocator->get($moduleOptions->getIdentityProvider());

        $roleProviderConfig = $moduleOptions->getRoleProvider();

        if (empty($roleProviderConfig)) {
            throw new RuntimeException('No role provider has been set for ZfjRbac');
        }

        /* @var \ZfjRbac\Role\RoleProviderPluginManager $pluginManager */
        $pluginManager = $serviceLocator->get('ZfjRbac\Role\RoleProviderPluginManager');

        /* @var \ZfjRbac\Role\RoleProviderInterface $roleProvider */
        $roleProvider = $pluginManager->get(key($roleProviderConfig), current($roleProviderConfig));

        /* @var \Rbac\Traversal\Strategy\TraversalStrategyInterface $traversalStrategy */
        $traversalStrategy = $serviceLocator->get('Rbac\Rbac')->getTraversalStrategy();

        $roleService = new RoleService($identityProvider, $roleProvider, $traversalStrategy);
        $roleService->setGuestRole($moduleOptions->getGuestRole());

        return $roleService;
    }
}

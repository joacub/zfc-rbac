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

namespace ZfjRbac\Role;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Factory\InvokableFactory;
use ZfjRbac\Exception;
use ZfjRbac\Factory\ObjectRepositoryRoleProviderFactory;

/**
 * Plugin manager to create role providers
 *
 * @method RoleProviderInterface get($name)
 *
 * @author  Michaël Gallego <mic.gallego@gmail.com>
 * @license MIT
 */
class RoleProviderPluginManager extends AbstractPluginManager
{
    /**
     * @var array
     */
    protected $factories = [
        InMemoryRoleProvider::class         => InvokableFactory::class,
        ObjectRepositoryRoleProvider::class => ObjectRepositoryRoleProviderFactory::class
    ];

    /**
     * {@inheritDoc}
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof RoleProviderInterface) {
            return; // we're okay
        }

        throw new Exception\RuntimeException(sprintf(
            'Role provider must implement "ZfcRbac\Role\RoleProviderInterface", but "%s" was given',
            is_object($plugin) ? get_class($plugin) : gettype($plugin)
        ));
    }

    protected $serviceLocator;

    public function setServiceLocator(ContainerInterface $container)
    {
        $this->serviceLocator = $container;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}

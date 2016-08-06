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

namespace ZfjRbacTest\Factory;

use Zend\ServiceManager\ServiceManager;
use ZfjRbac\Factory\GuardsFactory;
use ZfjRbac\Guard\GuardPluginManager;
use ZfjRbac\Options\ModuleOptions;

/**
 * @covers \ZfjRbac\Factory\GuardsFactory
 */
class GuardsFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $moduleOptions = new ModuleOptions([
            'guards' => [
                'ZfjRbac\Guard\RouteGuard' => [
                    'admin/*' => 'role1'
                ],
                'ZfjRbac\Guard\RoutePermissionsGuard' => [
                    'admin/post' => 'post.manage'
                ],
                'ZfjRbac\Guard\ControllerGuard' => [[
                    'controller' => 'MyController',
                    'actions'    => ['index', 'edit'],
                    'roles'      => ['role']
                ]],
                'ZfjRbac\Guard\ControllerPermissionsGuard' => [[
                    'controller'  => 'PostController',
                    'actions'     => ['index', 'edit'],
                    'permissions' => ['post.read']
                ]]
            ]
        ]);

        $pluginManager = new GuardPluginManager();

        $serviceManager = new ServiceManager();
        $serviceManager->setService('ZfjRbac\Options\ModuleOptions', $moduleOptions);
        $serviceManager->setService('ZfjRbac\Guard\GuardPluginManager', $pluginManager);
        $serviceManager->setService(
            'ZfjRbac\Service\RoleService',
            $this->getMock('ZfjRbac\Service\RoleService', [], [], '', false)
        );
        $serviceManager->setService(
            'ZfjRbac\Service\AuthorizationService',
            $this->getMock('ZfjRbac\Service\AuthorizationServiceInterface', [], [], '', false)
        );

        $pluginManager->setServiceLocator($serviceManager);

        $factory = new GuardsFactory();
        $guards  = $factory->createService($serviceManager);

        $this->assertInternalType('array', $guards);

        $this->assertCount(4, $guards);
        $this->assertInstanceOf('ZfjRbac\Guard\RouteGuard', $guards[0]);
        $this->assertInstanceOf('ZfjRbac\Guard\RoutePermissionsGuard', $guards[1]);
        $this->assertInstanceOf('ZfjRbac\Guard\ControllerGuard', $guards[2]);
        $this->assertInstanceOf('ZfjRbac\Guard\ControllerPermissionsGuard', $guards[3]);
    }

    public function testReturnArrayIfNoConfig()
    {
        $moduleOptions = new ModuleOptions([
            'guards' => []
        ]);

        $pluginManager = new GuardPluginManager();

        $serviceManager = new ServiceManager();
        $serviceManager->setService('ZfjRbac\Options\ModuleOptions', $moduleOptions);
        $pluginManager->setServiceLocator($serviceManager);

        $factory = new GuardsFactory();
        $guards  = $factory->createService($serviceManager);

        $this->assertInternalType('array', $guards);

        $this->assertEmpty($guards);
    }
}

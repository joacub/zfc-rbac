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
use ZfjRbac\Factory\ControllerPermissionsGuardFactory;
use ZfjRbac\Guard\GuardInterface;
use ZfjRbac\Guard\GuardPluginManager;
use ZfjRbac\Options\ModuleOptions;

/**
 * @covers \ZfjRbac\Factory\ControllerPermissionsGuardFactory
 */
class ControllerPermissionsGuardFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $creationOptions = [
            'route' => 'permission'
        ];

        $options = new ModuleOptions([
            'identity_provider' => 'ZfjRbac\Identity\AuthenticationProvider',
            'guards'            => [
                'ZfjRbac\Guard\ControllerPermissionsGuard' => $creationOptions
            ],
            'protection_policy' => GuardInterface::POLICY_ALLOW,
        ]);

        $serviceManager = new ServiceManager();
        $serviceManager->setService('ZfjRbac\Options\ModuleOptions', $options);
        $serviceManager->setService(
            'ZfjRbac\Service\AuthorizationService',
            $this->getMock('ZfjRbac\Service\AuthorizationService', [], [], '', false)
        );

        $pluginManager = new GuardPluginManager();
        $pluginManager->setServiceLocator($serviceManager);

        $factory    = new ControllerPermissionsGuardFactory();
        $guard = $factory->createService($pluginManager);

        $this->assertInstanceOf('ZfjRbac\Guard\ControllerPermissionsGuard', $guard);
        $this->assertEquals(GuardInterface::POLICY_ALLOW, $guard->getProtectionPolicy());
    }
}

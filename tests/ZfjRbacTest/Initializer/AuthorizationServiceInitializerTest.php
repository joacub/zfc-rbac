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
namespace ZfjRbacTest\Initializer;

use ZfjRbac\Initializer\AuthorizationServiceInitializer;

/**
 * @covers  \ZfjRbac\Initializer\AuthorizationServiceInitializer
 * @author  Aeneas Rekkas
 * @license MIT License
 */
class AuthorizationServiceInitializerTest extends \PHPUnit_Framework_TestCase
{
    public function testInitializer()
    {
        $authServiceClassName = 'ZfjRbac\Service\AuthorizationService';
        $initializer          = new AuthorizationServiceInitializer();
        $instance             = new AuthorizationAwareFake();
        $serviceLocator       = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $authorizationService = $this->getMock('ZfjRbac\Service\AuthorizationService', [], [], '', false);

        $serviceLocator->expects($this->once())
            ->method('get')
            ->with($authServiceClassName)
            ->will($this->returnValue($authorizationService));

        $initializer->initialize($instance, $serviceLocator);

        $this->assertEquals($authorizationService, $instance->getAuthorizationService());
    }
}

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

return [
    'service_manager' => [
        'invokables' => [
            'ZfjRbac\Collector\RbacCollector' => 'ZfjRbac\Collector\RbacCollector',
        ],

        'factories' => [
            /* Factories that do not map to a class */
            'ZfjRbac\Guards' => 'ZfjRbac\Factory\GuardsFactory',

            /* Factories that map to a class */
            'Rbac\Rbac'                                       => 'ZfjRbac\Factory\RbacFactory',
            'ZfjRbac\Assertion\AssertionPluginManager'        => 'ZfjRbac\Factory\AssertionPluginManagerFactory',
            'ZfjRbac\Guard\GuardPluginManager'                => 'ZfjRbac\Factory\GuardPluginManagerFactory',
            'ZfjRbac\Identity\AuthenticationIdentityProvider' => 'ZfjRbac\Factory\AuthenticationIdentityProviderFactory',
            'ZfjRbac\Options\ModuleOptions'                   => 'ZfjRbac\Factory\ModuleOptionsFactory',
            'ZfjRbac\Role\RoleProviderPluginManager'          => 'ZfjRbac\Factory\RoleProviderPluginManagerFactory',
            'ZfjRbac\Service\AuthorizationService'            => 'ZfjRbac\Factory\AuthorizationServiceFactory',
            'ZfjRbac\Service\RoleService'                     => 'ZfjRbac\Factory\RoleServiceFactory',
            'ZfjRbac\View\Strategy\RedirectStrategy'          => 'ZfjRbac\Factory\RedirectStrategyFactory',
            'ZfjRbac\View\Strategy\UnauthorizedStrategy'      => 'ZfjRbac\Factory\UnauthorizedStrategyFactory',
        ],
    ],

    'view_helpers' => [
        'factories' => [
            'ZfjRbac\View\Helper\IsGranted' => 'ZfjRbac\Factory\IsGrantedViewHelperFactory',
            'ZfjRbac\View\Helper\HasRole'   => 'ZfjRbac\Factory\HasRoleViewHelperFactory'
        ],
        'aliases' => [
            'isGranted' => 'ZfjRbac\View\Helper\IsGranted',
            'hasRole'   => 'ZfjRbac\View\Helper\HasRole'
        ]
    ],

    'controller_plugins' => [
        'factories' => [
            'ZfjRbac\Mvc\Controller\Plugin\IsGranted' => 'ZfjRbac\Factory\IsGrantedPluginFactory'
        ],
        'aliases' => [
            'isGranted' => 'ZfjRbac\Mvc\Controller\Plugin\IsGranted'
        ]
    ],

    'view_manager' => [
        'template_map' => [
            'error/403'                             => __DIR__ . '/../view/error/403.phtml',
            'zend-developer-tools/toolbar/zfc-rbac' => __DIR__ . '/../view/zend-developer-tools/toolbar/zfc-rbac.phtml'
        ]
    ],

    'zenddevelopertools' => [
        'profiler' => [
            'collectors' => [
                'zfc_rbac' => 'ZfjRbac\Collector\RbacCollector',
            ],
        ],
        'toolbar' => [
            'entries' => [
                'zfc_rbac' => 'zend-developer-tools/toolbar/zfc-rbac',
            ],
        ],
    ],

    'zfc_rbac' => [
        // Guard plugin manager
        'guard_manager' => [],

        // Role provider plugin manager
        'role_provider_manager' => [],

        // Assertion plugin manager
        'assertion_manager' => []
    ]
];

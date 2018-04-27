<?php

return [

    // Project related constants
    'global' => [
        'site' => [
            'name' => 'SavoCodes',
            'tinyName' => 'SC',
            'version' => '1.0', // For internal code comparison (if any)
        ],

        'encryption' => [
            'keySize'               => 128,
            'passPhraseKey'         => 'abcdef', // Is used to derived pseudo-random
            'SaltValueKey'          => 'hijklm', // Is used along with passphrase to generate password
            'NoOfIteration'         => 3, // Number of Iterations to hash password
            'InitializationVector'  => 'HR$2pIjHR$2pIj12', // This value is required to encrypt the first block of plaintext data
        ]
    ],

    // Related to web-services
    'api' => [
        'config' => [
            'allowSingleDeviceLogin'    => false,
            'sendHiddenLogoutPush'      => false,

            'defaultPaginationLimit'    => 20,
        ],

        'separator' => '-,-',

        'global' => [
            'formats' => [
                'date' => 'm/d/Y',
                'time' => 'H:i',
                'datetime' => 'j M, Y H:i',
            ],
        ],
    ],

    // Related to backend

    // Directory Constants
    'back' => [

        'theme' => [
            'configuration' => [
                'show_navigation_messages'      => true,
                'show_navigation_notifications' => false,
                'show_navigation_flags'         => false,
            ],

            'modules' => [
                'date_format'       => 'j F, Y',
                'datetime_format'   => 'j M Y, h:i:s A',
                'time_format'       => 'h:i:s A',

                'tiny_loader'       => 'backend/assets/dist/img/tiny-loader.gif',
            ],
        ],

        'sidebar' => [
            'menu' => [
                [
                    'label' => 'Dashboard',
                    'path'  => '/dashboard',
                    'icon'  => 'fa fa-dashboard',
                ],
                'stores' => [
                    'label'     => 'Stores',
                    'path'      => '/stores',
                    'regexPath' => '%(/stores(/edit/\d+|/index|/create)?)%',
                    'icon'      => 'fa fa-building',
                    'submenu'   => [
                        [
                            'label'     => 'All Stores',
                            'path'      => '/stores/index',
                            'icon'      => 'fa fa-building',
                            'regexPath' => '%/stores(/index|/create|/edit/\d+|/detail/\d+)?$%',
                        ],
                    ],
                ],
                'coupons' => [
                    'label'     => 'Coupons',
                    'path'      => '/coupons',
                    'regexPath' => '%(/coupons(/edit/\d+|/index|/create)?)%',
                    'icon'      => 'fa fa-tag',
                    'submenu'   => [
                        [
                            'label'     => 'All Coupons',
                            'path'      => '/coupons/index',
                            'icon'      => 'fa fa-tag',
                            'regexPath' => '%/coupons(/index|/create|/edit/\d+|/detail/\d+)$%',
                        ],
                    ],
                ],
                'payment' => [
                    'label'     => 'Payment Management',
                    'path'      => '/payments',
                    'regexPath' => '%(/payments(/index|/detail/\d+)?)%',
                    'icon'      => 'fa fa-credit-card',
                    'submenu'   => [
                        [
                            'label'     => 'All Hospitals',
                            'path'      => '/payments/index',
                            'icon'      => 'fa fa-credit-card',
                            'regexPath' => '%(/payments(/index|/detail/\d+)?)%',
                        ],
//
                    ],
                ],
                'networks' => [
                    'label' => 'Networks',
                    'path' => '/networks/index',
                    'regexPath' => '%(/networks(/index|/detail/\d+)?)%',
                    'icon' => 'fa fa-list-alt',
                    'submenu' => [
                        [
                            'label' => 'All Networks',
                            'path' => '/networks/index',
                            'regexPath' => '%/networks(/index|/detail/\d+)$%',
                            'icon' => 'fa fa-list-alt',
                        ],
                    ],
                ],
                'imports' => [
                    'label' => 'Imports',
                    'path' => '/imports/index',
                    'regexPath' => '%(/imports(/index|/detail/\d+)?)%',
                    'icon' => 'fa fa-cloud-upload',
                    'submenu' => [
                        [
                            'label' => 'Imports',
                            'path' => '/imports/index',
                            'regexPath' => '%/imports(/index|/history/\d+)$%',
                            'icon' => 'fa fa-cloud-upload',
                        ],
                        [
                            'label' => 'History',
                            'path' => '/imports/history',
                            'regexPath' => '%/imports(/index|/history/\d+)$%',
                            'icon' => 'fa fa-history',
                        ],
                    ],
                ],
                'reports' => [
                    'label' => 'Reports & Analytics',
                    'path' => '/reports',
                    'regexPath' => '%(/reports(/dashboard|/car/statistics|/popular/driver)?)%',
                    'icon' => 'fa fa-bar-chart',
                    'submenu' => [
                        [
                            'label' => 'Referrals',
                            'path' => '/reports/referrals',
                            'regexPath' => '%(/reports/referrals)$%',
                            'icon' => 'fa fa-dashboard',
                        ]
                    ],
                ],
                'settings' => [
                    'label' => 'SYSTEM SETTINGS',
                    'type' => 'heading',
                ],
                'editprofile' => [
                    'label' => 'Profile Setting',
                    'path' => '/system/edit-profile',
                    'regexPath' => '%(/system/edit-profile)%',
                    'icon' => 'fa fa-pencil-square-o',
                ],

                // Dummy Menu
                // Key will help to identify whether to display this menu on specific role based user or not (optional)
                'dummyEntry' => [
                    'label' => 'Dummy Entry',
                    'path' => '/dummy-entry',
                    'regexPath' => '%(/dummy-entry(/edit/\d+|/create)?)%',
                    'icon' => 'fa fa-users',
                    'submenu' => [
                        [
                            'label' => 'View All',
                            'path' => '/dummy-entry/index',
                            'icon' => 'fa fa-users',
                        ],
                        [
                            'label' => 'Create',
                            'path' => '/dummy-entry/create',
                            'icon' => 'fa fa-users',
                            'regexPath' => false,
                        ],
                    ],
                    'populate' => false,
                ],
            ],
        ],
    ],

    // Related to frontend
    'front' => [
        'dir' => [
            'profilePicPath'    =>  'frontend/images/profile/',
            'userDocumentsPath' =>  'frontend/users/documents/',
        ],

        'default' => [
            'profilePic'        =>  'default.jpg',
        ],
    ],

];

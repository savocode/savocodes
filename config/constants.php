<?php

return [

    // Project related constants
    'global' => [
        'site' => [
            'name' => 'LifeCare',
            'tinyName' => 'LC',
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
                'users' => [
                    'label'     => 'Users',
                    'path'      => '/users',
                    'regexPath' => '%(/users(/edit/\d+|/verification|/index)?)|(/user-stats(/detail/\d+|/index)?)%',
                    'icon'      => 'fa fa-users',
                    'submenu'   => [
                        [
                            'label'     => 'All Users',
                            'path'      => '/users/index',
                            'icon'      => 'fa fa-user',
                            'regexPath' => '%/users(/index|/detail/\d+|/purchases/\d+)?|(/user-stats(/detail/\d+|/index)?)$%',
                        ],
                    ],
                ],
                'hospital_physician' => [
                    'label'     => 'Physicians',
                    'path'      => '/physicians',
                    'regexPath' => '%(/physicians(/edit/\d+|/verification|/index)?)|(/user-stats(/detail/\d+|/index)?)%',
                    'icon'      => 'fa fa-users',
                    'submenu'   => [
                        [
                            'label'     => 'All Physicians',
                            'path'      => '/physicians/index',
                            'icon'      => 'fa fa-user',
                            'regexPath' => '%/physicians(/index|/detail/\d+|/purchases/\d+)?|(/user-stats(/detail/\d+|/index)?)$%',
                        ],
                    ],
                ],
                'hospitals' => [
                    'label'     => 'Hospital Management',
                    'path'      => '/hospitals',
                    'regexPath' => '%(/hospital(/edit/\d+|/index|/create)?)%',
                    'icon'      => 'fa fa-medkit',
                    'submenu'   => [
                        [
                            'label'     => 'All Hospitals',
                            'path'      => '/hospitals/index',
                            'icon'      => 'fa fa-hospital-o',
                            'regexPath' => '%/hospitals((/index|/create|/detail/\d+|/edit/\d+|/\d+/employees|/\d+/employee/create|/\d+/employee/detail/\d+|/\d+/employee/edit/\d+)?)$%',
                        ],
//                        [
//                            'label' => 'Hospital Locations',
//                            'path'  => '/hospitals/location',
//                            'icon'  => 'fa fa-location-arrow',
//                            'regexPath' => false,
//                        ],
                    ],
                ],
                'referrals' => [
                    'label' => 'Referrals',
                    'path' => '/referrals',
                    'regexPath' => '%(/referrals(/index|/canceled)?)%',
                    'icon' => 'fa fa-user-plus',
                    'submenu' => [
                        [
                            'label' => 'All Referrals',
                            'path' => '/referrals/index',
                            'regexPath' => '%(/referrals/index|/detail/\d+)$%',
                            'icon' => 'fa fa-check-square-o',
                        ],
                    ],
                ],
                'professions' => [
                    'label' => 'Professions',
                    'path' => '/settings/profession',
                    'regexPath' => false,
                    'icon' => 'fa fa-yelp',

                ],
                'criteria' => [
                    'label' => 'Criteria',
                    'path' => '/settings/criteria',
                    'regexPath' => false,
                    'icon' => 'fa fa-star',

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

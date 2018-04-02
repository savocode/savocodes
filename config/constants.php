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
                'show_navigation_messages'      => false,
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
//                        [
//                            'label' => 'Un-Verified Users',
//                            'path'  => '/users/verified',
//                            'icon'  => 'fa fa-times',
//                            'regexPath' => false,
//                        ],
                    ],
                ],
                'hospitals' => [
                    'label'     => 'Hospital Management',
                    'path'      => '/hospitals',
                    'regexPath' => '%(/hospital(/edit/\d+|/verification|/index)?)|(/user-stats(/detail/\d+|/index)?)%',
                    'icon'      => 'fa fa-medkit',
                    'submenu'   => [
                        [
                            'label'     => 'All Hospitals',
                            'path'      => '/hospitals/index',
                            'icon'      => 'fa fa-hospital-o',
                            'regexPath' => '%/hospitals(/index|/detail/\d+|/create/\d+)?|(/user-stats(/detail/\d+|/index)?)$%',
                        ],
                        [
                            'label' => 'Hospital Locations',
                            'path'  => '/hospitals/location',
                            'icon'  => 'fa fa-location-arrow',
                            'regexPath' => false,
                        ],
                    ],
                ],
                'referrals' => [
                    'label' => 'Referrals',
                    'path' => '/referrals',
                    'regexPath' => '%(/referrals(/approved|/canceled)?)%',
                    'icon' => 'fa fa-user-plus',
                    'submenu' => [
                        [
                            'label' => 'Approved Referrals',
                            'path' => '/referrals/approved',
                            'regexPath' => '%(/referrals/approved)$%',
                            'icon' => 'fa fa-check-square-o',
                        ],
                        [
                            'label' => 'Rejected Referrals',
                            'path' => '/referrals/canceled',
                            'regexPath' => '%(/referrals/canceled)$%',
                            'icon' => 'fa fa-ban',
                        ],
                    ],
                ],
                'professions' => [
                    'label' => 'Professions',
                    'path' => '/professions/index',
                    'regexPath' => '%/professions(/index|/create|/edit/\d+)?$%',
                    'icon' => 'fa fa-yelp',
                ],
                'criteria' => [
                    'label' => 'Criteria',
                    'path' => '/criteria/index',
                    'regexPath' => '%(/reviews/index)$%',
                    'icon' => 'fa fa-star',
                ],
                'reports' => [
                    'label' => 'Reports & Analytics',
                    'path' => '/reports',
                    'regexPath' => '%(/reports(/dashboard|/car/statistics|/popular/driver)?)%',
                    'icon' => 'fa fa-bar-chart',
                    'submenu' => [
                        [
                            'label' => 'Dashboard',
                            'path' => '/reports/dashboard',
                            'regexPath' => '%(/reports/dashboard)$%',
                            'icon' => 'fa fa-dashboard',
                        ],
                        [
                            'label' => 'Car Statistics',
                            'path' => '/reports/car/statistics',
                            'regexPath' => '%(/reports/car/statistics)$%',
                            'icon' => 'fa fa-taxi',
                        ],
                        [
                            'label' => 'Popular Driver',
                            'path' => '/reports/popular/driver',
                            'regexPath' => '%(/reports/popular/driver)$%',
                            'icon' => 'fa fa-user',
                        ]
                    ],
                ],
                'settings' => [
                    'label' => 'SYSTEM SETTINGS',
                    'type' => 'heading',
                ],
                'editsettings' => [
                    'label' => 'Settings',
                    'path' => '/system/edit-settings',
                    'regexPath' => '%(/system/edit-settings)%',
                    'icon' => 'fa fa-cog',
                ],
                'schools' => [
                    'label' => 'Schools',
                    'path' => '/schools/index',
                    'regexPath' => '%/schools(/index|/create|/edit/\d+)?$%',
                    'icon' => 'fa fa-building',
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

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Leo Foundation Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for the Leo Foundation
    | platform including donation settings, beneficiary types, and more.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Application Details
    |--------------------------------------------------------------------------
    */
    'name' => env('APP_NAME', 'Leo Foundation'),
    'tagline' => 'Empowering Lives, Transforming Futures',

    /*
    |--------------------------------------------------------------------------
    | Beneficiary Types
    |--------------------------------------------------------------------------
    */
    'beneficiary_types' => [
        'orphan' => [
            'label' => 'Orphan',
            'color' => 'primary',
            'icon' => 'heroicon-o-heart',
        ],
        'patient' => [
            'label' => 'De-addiction Patient',
            'color' => 'warning',
            'icon' => 'heroicon-o-user-plus',
        ],
        'welfare_recipient' => [
            'label' => 'Welfare Recipient',
            'color' => 'success',
            'icon' => 'heroicon-o-hand-raised',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Beneficiary Status Options
    |--------------------------------------------------------------------------
    */
    'beneficiary_statuses' => [
        'active' => [
            'label' => 'Active',
            'color' => 'success',
        ],
        'inactive' => [
            'label' => 'Inactive',
            'color' => 'gray',
        ],
        'discharged' => [
            'label' => 'Discharged',
            'color' => 'info',
        ],
        'transferred' => [
            'label' => 'Transferred',
            'color' => 'warning',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Donation Settings
    |--------------------------------------------------------------------------
    */
    'donations' => [
        'currencies' => [
            'INR' => [
                'code' => 'INR',
                'symbol' => '₹',
                'name' => 'Indian Rupee',
                'min_amount' => 100,
            ],
            'USD' => [
                'code' => 'USD',
                'symbol' => '$',
                'name' => 'US Dollar',
                'min_amount' => 5,
            ],
            'GBP' => [
                'code' => 'GBP',
                'symbol' => '£',
                'name' => 'British Pound',
                'min_amount' => 5,
            ],
        ],
        'default_currency' => 'INR',
        'suggested_amounts' => [500, 1000, 2500, 5000, 10000],
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Gateways
    |--------------------------------------------------------------------------
    */
    'payment_gateways' => [
        'stripe' => [
            'enabled' => env('STRIPE_KEY') && env('STRIPE_SECRET'),
            'name' => 'Stripe',
            'currencies' => ['USD', 'GBP', 'INR'],
        ],
        'razorpay' => [
            'enabled' => env('RAZORPAY_KEY') && env('RAZORPAY_SECRET'),
            'name' => 'Razorpay',
            'currencies' => ['INR'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Media Platforms
    |--------------------------------------------------------------------------
    */
    'social_platforms' => [
        'facebook' => [
            'name' => 'Facebook',
            'icon' => 'heroicon-o-share',
            'color' => '#1877F2',
            'max_chars' => 63206,
            'supports_images' => true,
        ],
        'twitter' => [
            'name' => 'Twitter/X',
            'icon' => 'heroicon-o-chat-bubble-left',
            'color' => '#000000',
            'max_chars' => 280,
            'supports_images' => true,
        ],
        'linkedin' => [
            'name' => 'LinkedIn',
            'icon' => 'heroicon-o-briefcase',
            'color' => '#0A66C2',
            'max_chars' => 3000,
            'supports_images' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Inventory Categories
    |--------------------------------------------------------------------------
    */
    'inventory_categories' => [
        'food' => [
            'label' => 'Food & Groceries',
            'icon' => 'heroicon-o-shopping-bag',
        ],
        'medicine' => [
            'label' => 'Medicine & Healthcare',
            'icon' => 'heroicon-o-beaker',
        ],
        'clothing' => [
            'label' => 'Clothing',
            'icon' => 'heroicon-o-gift',
        ],
        'education' => [
            'label' => 'Education Materials',
            'icon' => 'heroicon-o-book-open',
        ],
        'household' => [
            'label' => 'Household Items',
            'icon' => 'heroicon-o-home',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Roles
    |--------------------------------------------------------------------------
    */
    'roles' => [
        'admin' => [
            'label' => 'Administrator',
            'color' => 'danger',
            'permissions' => ['*'],
        ],
        'staff' => [
            'label' => 'Staff Member',
            'color' => 'warning',
            'permissions' => ['view', 'create', 'edit'],
        ],
        'donor' => [
            'label' => 'Donor',
            'color' => 'success',
            'permissions' => ['view'],
        ],
    ],
];

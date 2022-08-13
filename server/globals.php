<?php
/* EXERCISE 3-1A and 3-1B */
/* GLOBAL CONSTANTS */
const COMPANY_LOGO = 'web-site-icon.jpg';
const COMPANY_NAME = 'ManchesterUnitedCanada.com';
const COMPANY_STREET_ADDRESS = '5340 St-Laurent';
const COMPANY_CITY = 'Montréal';
const COMPANY_PROVINCE = 'QC';
const COMPANY_COUNTRY = 'Canada';
const COMPANY_POSTAL_CODE = 'J0P 1T0';
const PHONE_NUMBER = '+1 (514)-345-6789';
const EMAIL = 'info@manchesterunitedcanada.com';
const PATH = 'products_images';
const VISITOR_LOG_FILE =  'visitors.log';
const LOG_DIRECTORY = 'log';
const MAX_LOGIN_ATTEMPS = 3;
const USER_IMAGES = 'user_images';




/* web page variable properties */
const DEFAULT_PAGE_DATA = [
    'lang' => 'en-CA',
    'title' => 'ManchesterUnitedCanada.com',
    'description' => 'Manchester United Merchandises for Canadians',
    'author' => 'Stéphane Lapointe',
    'content' => 'Default content - must be set before display !',
    'viewCounter' => 0
];

/*ROUTES */

const ROUTES = [
    'default' => 0,
    'login' => 1,
    'login-verify' => 2,
    'register' => 3,
    'register-verify' => 4,
    'logout' => 5,
    'download-file' => 50,
    'redirect-amazon' => 51,
    'product-list' => 100,
    'product-cataloge' => 101,
    'customers' => 400,
    'customers_json' => 420,
];

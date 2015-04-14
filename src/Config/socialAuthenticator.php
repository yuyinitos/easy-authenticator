<?php

return [

    // The model we use to interact with the database.
    'model' => 'Yuyinitos\SocialAuthenticator\Models\User',

    /**
     * You can require users who register to activate their account
     * via email. This is useful for many reasons, such as to prevent
     * spam. If you set this value to TRUE, you will then also need
     * to set up mailing as per laravel.com/docs/5.0/mail.
     * Once set up is done socialAuthenticator will take care of the rest.
     */
    'activation' => FALSE,
    // Set a subject line for the email.
    'email_subject' => 'Please activate your account.',
    
];

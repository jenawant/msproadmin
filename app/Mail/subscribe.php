<?php
declare(strict_types=1);
/**
 * This file is part of hyperf-ext/mail.
 *
 * @link     https://github.com/hyperf-ext/mail
 * @contact  eric@zhu.email
 * @license  https://github.com/hyperf-ext/mail/blob/master/LICENSE
 */

namespace App\Mail;

use HyperfExt\Contract\ShouldQueue;

/**
 * @example  Mail::to('new_user@msproadmin.com')->send(new subscribe());
 * @example  Mail::to(['user1@test.com', 'user2@test.com'])->queue(new subscribe());
 * @example  Mail::to(['user1@test.com', 'user2@test.com'])->later(new subscribe(), 10);
 */
class subscribe implements ShouldQueue
{
    /**
     * Create a new message instance.
     */
    public function __construct()
    {
    }

    /**
     * Build the message.
     */
    public function build()
    {
        //mail template
        $html = <<<ht
            <p>Hello, Welcome to use MsProAdmin!</p>    
        ht;
        return $this->subject("Welcome!")->htmlBody($html);
    }
}
<?php

namespace NFWP\Email;

class NFEmail
{
    /**
     * Array or comma-separated list of email addresses to send message.
     *
     * @var string|array
     */
    public $to;

    /**
     * Email subject
     *
     * @var string
     */
    public $subject;

    /**
     * Message contents
     *
     * @var string
     */
    public $message = '';

    /**
     * Additional headers.
     *
     * @var string|array
     */
    public $headers;

    /**
     * Files to attach.
     * @var string|array
     */
    public $attachments = [];

    /**
     * Send mail, similar to PHP’s mail
     *
     * @return bool - Whether the email contents were sent successfully.
     */
    public function send()
    {
        return wp_mail($this->to, $this->subject, $this->message, $this->headers, $this->attachments);
    }
}

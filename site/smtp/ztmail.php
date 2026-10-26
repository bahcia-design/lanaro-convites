<?php
/**
 * Safe contact mail adapter. Configure LANARO_CONTACT_EMAIL on the new host.
 * No SMTP password or other credential belongs in this file or in Git.
 */
function ztmail($body, $subject = 'Contato Site') {
    $recipient = getenv('LANARO_CONTACT_EMAIL');
    $sender = getenv('LANARO_MAIL_FROM');

    if (!$recipient || !filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $headers = "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\n";
    if ($sender && filter_var($sender, FILTER_VALIDATE_EMAIL)) {
        $headers .= 'From: ' . $sender . "\r\n";
    }

    return mail($recipient, $subject, $body, $headers);
}

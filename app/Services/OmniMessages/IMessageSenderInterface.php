<?php


namespace App\Services\OmniMessages;

use Illuminate\Support\Facades\Log;
use App\Exceptions\MessageException;

interface IMessageSenderInterface
{
    /**
     * Sends a message to the specified recipient.
     *
     * @param string $message   The content of the message to be sent.
     * @param string $recipient The identifier of the recipient (e.g., user ID, email).
     *
     * @return bool|string Returns true on successful send, or an error message string on failure.
     */
    public function sendMessage(string $message, string $recipient): bool|string;

    /**
     * Retrieves the current status of a sent message.
     *
     * @param string $messageId The unique identifier of the message.
     *
     * @return string The status of the message (e.g., sent, delivered, failed).
     */
    public function getSendStatus(string $messageId): string;

    /**
     * Sends a lengthy message to the specified recipient, handling any necessary segmentation.
     *
     * @param string $message   The lengthy content of the message to be sent.
     * @param string $recipient The identifier of the recipient (e.g., user ID, email).
     *
     * @return bool|string Returns true on successful send, or an error message string on failure.
     */
    public function sendLongMessage(string $message, string $recipient): bool|string;

    /**
     * Deletes a previously sent message based on its identifier.
     *
     * @param string $messageId The unique identifier of the message to be deleted.
     *
     * @return bool|string Returns true on successful deletion, or an error message string on failure.
     */
    public function deleteMessage(string $messageId): bool|string;

    /**
     * Validates the credentials required for sending messages through the service.
     *
     * @return bool Returns true if credentials are valid, false otherwise.
     */
    public function validateCredentials(): bool;

    /**
     * Retrieves the health status of the messaging channel.
     *
     * @return array An associative array containing health metrics and status information.
     */
    public function getChannelHealth(): array;
}

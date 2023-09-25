<?php

namespace Emkrawiec\CgrdAssessment\Views;

readonly class Notification
{
    private function __construct(
        private string              $message,
        private NotificationVariant $variant
    ) {
    }

    public static function success(string $text): Notification
    {
        return new self($text, NotificationVariant::SUCCESS);
    }

    public static function error(string $text): Notification
    {
        return new self($text, NotificationVariant::ERROR);
    }


    /**
     * @return array{message: string, variant: string}
     */
    public function toViewContext(): array
    {
        return array(
            'message' => $this->message,
            'variant' => $this->variant->name
        );
    }
}

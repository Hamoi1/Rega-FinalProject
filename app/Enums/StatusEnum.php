<?php

namespace App\Enums;

enum StatusEnum: string
{
    case Active = 'active';
    case Inactive = 'inactive';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getArraySelect(): array
    {
        return collect(self::cases())->map(fn($case): array => [
            'value' => $case->value,
            'label' => $case->getLabel(),
        ])->toArray();
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Active => __('words.active'),
            self::Inactive => __('words.inactive'),
            default => __('words.unknown'),
        };
    }

    public function getHtmlLabel(): string
    {
        return match ($this) {
            self::Active => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-sm bg-green-500 text-white">' . $this->getLabel() . '</span>',
            self::Inactive => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-sm bg-red-500 text-white">' . $this->getLabel() . '</span>',
            default => __('words.unknown'),
        };
    }
}

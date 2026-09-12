<?php

namespace App\Filament\Resources\Enrollments\Schemas;

use App\Enums\EnrollmentStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Enquiry')
                    ->columns(2)
                    ->schema([
                        Select::make('course_id')
                            ->label('Course')
                            ->relationship('course', 'title')
                            ->searchable()
                            ->preload()
                            ->placeholder('General enquiry (no course)'),
                        Select::make('status')
                            ->options(EnrollmentStatus::class)
                            ->default(EnrollmentStatus::Pending->value)
                            ->required(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->maxLength(180),
                        TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(40),
                        TextInput::make('company')
                            ->maxLength(150),
                        Textarea::make('message')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

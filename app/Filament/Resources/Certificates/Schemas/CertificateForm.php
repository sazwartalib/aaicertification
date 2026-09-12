<?php

namespace App\Filament\Resources\Certificates\Schemas;

use App\Enums\CertificateStatus;
use App\Models\Course;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CertificateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Certificate')
                    ->columns(2)
                    ->schema([
                        TextInput::make('certificate_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn (): string => 'AAI-'.now()->year.'-'.str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT))
                            ->helperText('The public reference used for verification.'),
                        Select::make('status')
                            ->options(CertificateStatus::class)
                            ->default(CertificateStatus::Valid->value)
                            ->required(),
                        TextInput::make('recipient_name')
                            ->required()
                            ->maxLength(150),
                        TextInput::make('ic_number')
                            ->label('IC / passport number')
                            ->required()
                            ->maxLength(30)
                            ->placeholder('901231-14-5566')
                            ->helperText('Uniquely identifies the certificate holder. Printed in full on the certificate; masked on public verification.'),
                        TextInput::make('grade')
                            ->placeholder('Pass / Merit / Distinction')
                            ->maxLength(60),
                        Select::make('course_id')
                            ->label('Course')
                            ->relationship('course', 'title')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set): void {
                                if ($state && ($course = Course::find($state))) {
                                    $set('course_title', $course->title);
                                }
                            }),
                        TextInput::make('course_title')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Printed on the certificate. Auto-filled from the course, but editable.'),
                        DatePicker::make('issued_at')
                            ->required()
                            ->native(false)
                            ->default(now()),
                        DatePicker::make('expires_at')
                            ->native(false)
                            ->helperText('Leave blank for a certificate that does not expire.'),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Enums\CourseLevel;
use App\Enums\DeliveryMode;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Course details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set): void {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Used in the public course URL.'),
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')->required(),
                                TextInput::make('slug')->required(),
                            ]),
                        TextInput::make('duration')
                            ->required()
                            ->placeholder('e.g. 3 days')
                            ->maxLength(60),
                        TextInput::make('summary')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->helperText('Short sentence shown on course cards and search results.'),
                        RichEditor::make('description')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Classification')
                    ->columns(3)
                    ->schema([
                        Select::make('level')
                            ->options(CourseLevel::class)
                            ->default(CourseLevel::Beginner->value)
                            ->required(),
                        Select::make('delivery_mode')
                            ->label('Delivery mode')
                            ->options(DeliveryMode::class)
                            ->default(DeliveryMode::Online->value)
                            ->required(),
                        TextInput::make('price')
                            ->numeric()
                            ->prefix('RM')
                            ->helperText('Leave blank for "On request".'),
                        TextInput::make('accreditation_body')
                            ->label('Accreditation body')
                            ->maxLength(120),
                        TextInput::make('sort_order')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),

                Section::make('Visibility & media')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(true)
                            ->helperText('Unpublished courses are hidden from the public site.'),
                        Toggle::make('is_featured')
                            ->label('Featured on home page'),
                        FileUpload::make('image_path')
                            ->label('Course image')
                            ->image()
                            ->directory('courses')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

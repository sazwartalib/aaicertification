<?php

namespace App\Filament\Resources\Courses\Tables;

use App\Enums\CourseLevel;
use App\Enums\DeliveryMode;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record): string => $record->category?->name ?? 'Uncategorised'),
                TextColumn::make('level')
                    ->badge()
                    ->sortable(),
                TextColumn::make('delivery_mode')
                    ->label('Delivery')
                    ->badge(),
                TextColumn::make('duration'),
                TextColumn::make('price')
                    ->money('MYR')
                    ->placeholder('On request')
                    ->sortable(),
                TextColumn::make('enrollments_count')
                    ->label('Enrolments')
                    ->counts('enrollments')
                    ->badge()
                    ->color('info'),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('level')
                    ->options(CourseLevel::class),
                SelectFilter::make('delivery_mode')
                    ->label('Delivery mode')
                    ->options(DeliveryMode::class),
                TernaryFilter::make('is_published')
                    ->label('Published'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

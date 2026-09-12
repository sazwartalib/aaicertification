<?php

namespace App\Filament\Resources\Certificates\Tables;

use App\Enums\CertificateStatus;
use App\Models\Certificate;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CertificatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('issued_at', 'desc')
            ->columns([
                TextColumn::make('certificate_number')
                    ->label('Number')
                    ->searchable()
                    ->copyable()
                    ->weight('medium'),
                TextColumn::make('recipient_name')
                    ->label('Recipient')
                    ->searchable(),
                TextColumn::make('ic_number')
                    ->label('IC / passport')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),
                TextColumn::make('course_title')
                    ->label('Programme')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('issued_at')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->label('Valid until')
                    ->date('d M Y')
                    ->placeholder('No expiry')
                    ->sortable()
                    ->color(fn ($record): ?string => match (true) {
                        $record->status === CertificateStatus::Valid && $record->isExpired() => 'danger',
                        $record->isExpiringSoon() => 'warning',
                        default => null,
                    })
                    ->icon(fn ($record): ?string => match (true) {
                        $record->status === CertificateStatus::Valid && $record->isExpired() => 'heroicon-m-exclamation-circle',
                        $record->isExpiringSoon() => 'heroicon-m-clock',
                        default => null,
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(CertificateStatus::class),
                SelectFilter::make('course')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload(),
                Filter::make('expiring_soon')
                    ->label('Expiring soon')
                    ->query(fn (Builder $query): Builder => $query->expiringSoon()),
            ])
            ->recordActions([
                Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn (Certificate $record): string => route('certificates.print', $record))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $modelLabel = 'Task';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->options(
                        User::where('role', 'user')
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn(string $operation): bool => $operation !== 'create'),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\Select::make('status')
                    ->options([
                        'complete' => 'Complete',
                        'incomplete' => 'Incomplete',
                    ])
                    ->required()
                    ->native(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn(Task $record): string => $record->title),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'complete' => 'success',
                        'incomplete' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                    ->label('Status'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'complete' => 'Complete',
                        'incomplete' => 'Incomplete',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('markComplete')
                    ->action(function (Task $record) {
                        $record->status = 'complete';
                        $record->save();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark Task as Complete')
                    ->modalDescription('Are you sure you want to mark this task as complete?')
                    ->modalSubmitActionLabel('Yes, mark as complete')
                    ->hidden(fn(Task $record): bool => $record->status === 'complete')
                    ->icon('heroicon-o-check'),

                Tables\Actions\Action::make('markIncomplete')
                    ->action(function (Task $record) {
                        $record->status = 'incomplete';
                        $record->save();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark Task as Incomplete')
                    ->modalDescription('Are you sure you want to mark this task as incomplete?')
                    ->modalSubmitActionLabel('Yes, mark as incomplete')
                    ->hidden(fn(Task $record): bool => $record->status === 'incomplete')
                    ->icon('heroicon-o-x-mark'),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markComplete')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->status = 'complete';
                                $record->save();
                            });
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->icon('heroicon-o-check'),

                    Tables\Actions\BulkAction::make('markIncomplete')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->status = 'incomplete';
                                $record->save();
                            });
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->icon('heroicon-o-x-mark'),

                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add relation managers if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

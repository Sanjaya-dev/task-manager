<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Task;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TaskResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TaskResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                    
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                    
                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                    
                Toggle::make('completed')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                            ->numeric()
                            ->sortable()
                            ->searchable(),
                TextColumn::make('title')
                            ->sortable()
                            ->searchable(),
                TextColumn::make('description')
                            ->sortable()
                            ->searchable(),
                TextColumn::make('completed')
                            ->sortable()
                            ->toggleable(),
                TextColumn::make('created_at')
                            ->dateTime()
                            ->sortable()
                            ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                            ->dateTime()
                            ->sortable()
                            ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                SelectFilter::make('user')
                            ->relationship('user', 'name'),
                Tables\Filters\Filter::make('completed')
                        ->query(fn(Builder $query): Builder => $query->where('completed', true))
                        ->toggle(),
                Tables\Filters\Filter::make('incompleted')
                        ->query(fn(Builder $query): Builder => $query->where('completed', false))
                        ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
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
}

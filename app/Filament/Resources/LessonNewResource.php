<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonNewResource\Pages;
use App\Filament\Resources\LessonNewResource\RelationManagers;
use App\Models\LessonNew;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LessonNewResource extends Resource
{
    protected static ?string $model = LessonNew::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListLessonNews::route('/'),
            'create' => Pages\CreateLessonNew::route('/create'),
            'view' => Pages\ViewLessonNew::route('/{record}'),
            'edit' => Pages\EditLessonNew::route('/{record}/edit'),
        ];
    }
}

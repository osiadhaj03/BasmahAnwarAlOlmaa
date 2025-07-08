<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonResource\Pages;
use App\Models\Lesson;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationLabel = 'الدروس';
    
    protected static ?string $modelLabel = 'درس';
    
    protected static ?string $pluralModelLabel = 'الدروس';
    
    protected static ?string $navigationGroup = 'إدارة التدريس';
    
    public static function getNavigationUrl(): string
    {
        // This resource is only for teacher panel, not admin panel
        return static::getUrl('index');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([                Forms\Components\TextInput::make('name')
                    ->label('اسم الدرس')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('مثال: الرياضيات'),
                    
                Forms\Components\TextInput::make('subject')
                    ->label('المادة')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Select::make('teacher_id')
                    ->label('المعلم')
                    ->options(User::where('role', 'teacher')->pluck('name', 'id'))
                    ->required()
                    ->default(function () {
                        // If current user is teacher, auto-select them
                        $user = auth()->user();
                        return $user && $user->role === 'teacher' ? $user->id : null;
                    })
                    ->disabled(function () {
                        // Teachers can't change the teacher field
                        return auth()->user()?->role === 'teacher';
                    }),
                    
                Forms\Components\Select::make('day_of_week')
                    ->label('يوم الأسبوع')
                    ->options([
                        'sunday' => 'الأحد',
                        'monday' => 'الإثنين',
                        'tuesday' => 'الثلاثاء',
                        'wednesday' => 'الأربعاء',
                        'thursday' => 'الخميس',
                        'friday' => 'الجمعة',
                        'saturday' => 'السبت',
                    ])
                    ->required(),
                    
                Forms\Components\TimePicker::make('start_time')
                    ->label('وقت البداية')
                    ->required(),
                      Forms\Components\TimePicker::make('end_time')
                    ->label('وقت النهاية')
                    ->required()
                    ->after('start_time'),
                    
                Forms\Components\Textarea::make('description')
                    ->label('وصف الدرس')
                    ->rows(3)
                    ->maxLength(1000),
                    
                Forms\Components\Select::make('students')
                    ->label('الطلاب')
                    ->multiple()
                    ->relationship('students', 'name')
                    ->options(User::where('role', 'student')->pluck('name', 'id'))
                    ->preload(),
            ]);
    }    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الدرس')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('subject')
                    ->label('المادة')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('المعلم')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('day_of_week')
                    ->label('يوم الأسبوع')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'sunday' => 'الأحد',
                        'monday' => 'الإثنين',
                        'tuesday' => 'الثلاثاء',
                        'wednesday' => 'الأربعاء',
                        'thursday' => 'الخميس',
                        'friday' => 'الجمعة',
                        'saturday' => 'السبت',
                        default => $state,
                    })
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('start_time')
                    ->label('وقت البداية')
                    ->time('H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('end_time')
                    ->label('وقت النهاية')
                    ->time('H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('students_count')
                    ->label('عدد الطلاب')
                    ->counts('students'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('day_of_week')
                    ->label('يوم الأسبوع')
                    ->options([
                        'sunday' => 'الأحد',
                        'monday' => 'الإثنين',
                        'tuesday' => 'الثلاثاء',
                        'wednesday' => 'الأربعاء',
                        'thursday' => 'الخميس',
                        'friday' => 'الجمعة',
                        'saturday' => 'السبت',
                    ]),
                    
                Tables\Filters\SelectFilter::make('teacher')
                    ->label('المعلم')
                    ->relationship('teacher', 'name')
                    ->visible(fn () => auth()->user()?->role === 'admin'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make()->label('تعديل'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف'),
                ]),
            ]);
    }
    
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Teachers can only see their own lessons
        if (auth()->user()?->role === 'teacher') {
            $query->where('teacher_id', auth()->id());
        }
        
        return $query;
    }
    
    public static function canCreate(): bool
    {
        $user = auth()->user();
        return $user && in_array($user->role, ['admin', 'teacher']);
    }
    
    public static function canEdit($record): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        if ($user->role === 'admin') {
            return true;
        }
        
        if ($user->role === 'teacher') {
            return $record->teacher_id === $user->id;
        }
        
        return false;
    }
    
    public static function canDelete($record): bool
    {
        return static::canEdit($record);
    }
    
    public static function canView($record): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        if ($user->role === 'admin') {
            return true;
        }
        
        if ($user->role === 'teacher') {
            return $record->teacher_id === $user->id;
        }
        
        return false;
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
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'view' => Pages\ViewLesson::route('/{record}'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}

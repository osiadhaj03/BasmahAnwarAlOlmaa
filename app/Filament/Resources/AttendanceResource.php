<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use App\Models\Lesson;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    
    protected static ?string $navigationLabel = 'الحضور';
    
    protected static ?string $modelLabel = 'حضور';
    
    protected static ?string $pluralModelLabel = 'الحضور';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('lesson_id')
                    ->label('الدرس')
                    ->options(function () {
                        $user = auth()->user();
                        if ($user?->role === 'admin') {
                            return Lesson::with('teacher')
                                ->get()
                                ->mapWithKeys(fn($lesson) => [
                                    $lesson->id => $lesson->subject . ' - ' . $lesson->teacher->name
                                ]);
                        } elseif ($user?->role === 'teacher') {
                            return Lesson::where('teacher_id', $user->id)
                                ->get()
                                ->mapWithKeys(fn($lesson) => [
                                    $lesson->id => $lesson->subject
                                ]);
                        }
                        return [];
                    })
                    ->required()
                    ->searchable()
                    ->preload(),
                    
                Forms\Components\Select::make('student_id')
                    ->label('الطالب')
                    ->options(function (callable $get) {
                        $lessonId = $get('lesson_id');
                        if (!$lessonId) {
                            return [];
                        }
                        
                        $lesson = Lesson::with('students')->find($lessonId);
                        if (!$lesson) {
                            return [];
                        }
                        
                        return $lesson->students->pluck('name', 'id');
                    })
                    ->required()
                    ->searchable()
                    ->reactive(),
                    
                Forms\Components\DatePicker::make('date')
                    ->label('التاريخ')
                    ->required()
                    ->default(now()),
                    
                Forms\Components\Select::make('status')
                    ->label('حالة الحضور')
                    ->options([
                        'present' => 'حاضر',
                        'absent' => 'غائب',
                        'late' => 'متأخر',
                        'excused' => 'غياب بعذر',
                    ])
                    ->required()
                    ->default('present'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lesson.subject')
                    ->label('المادة')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('lesson.teacher.name')
                    ->label('المعلم')
                    ->searchable()
                    ->sortable()
                    ->visible(fn () => auth()->user()?->role === 'admin'),
                    
                Tables\Columns\TextColumn::make('student.name')
                    ->label('الطالب')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('date')
                    ->label('التاريخ')
                    ->date('Y-m-d')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('الحضور')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'present' => 'حاضر',
                        'absent' => 'غائب',
                        'late' => 'متأخر',
                        'excused' => 'غياب بعذر',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'present' => 'success',
                        'late' => 'warning',
                        'excused' => 'info',
                        'absent' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('حالة الحضور')
                    ->options([
                        'present' => 'حاضر',
                        'absent' => 'غائب',
                        'late' => 'متأخر',
                        'excused' => 'غياب بعذر',
                    ]),
                    
                Tables\Filters\SelectFilter::make('lesson')
                    ->label('الدرس')
                    ->relationship('lesson', 'subject')
                    ->searchable()
                    ->preload(),
                    
                Tables\Filters\SelectFilter::make('student')
                    ->label('الطالب')
                    ->relationship('student', 'name')
                    ->searchable()
                    ->preload(),
                    
                Tables\Filters\Filter::make('date_range')
                    ->label('فترة زمنية')
                    ->form([
                        Forms\Components\DatePicker::make('from_date')
                            ->label('من تاريخ'),
                        Forms\Components\DatePicker::make('to_date')
                            ->label('إلى تاريخ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['to_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make()->label('تعديل'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف'),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }
    
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        
        // Teachers can only see attendance for their lessons
        if ($user?->role === 'teacher') {
            $query->whereHas('lesson', function (Builder $lessonQuery) use ($user) {
                $lessonQuery->where('teacher_id', $user->id);
            });
        }
        
        return $query;
    }
    
    public static function canCreate(): bool
    {
        $user = auth()->user();
        return $user && in_array($user->role, ['admin', 'teacher']);
    }
    
    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        if ($user->role === 'admin') {
            return true;
        }
        
        if ($user->role === 'teacher') {
            return $record->lesson->teacher_id === $user->id;
        }
        
        return false;
    }
    
    public static function canDelete(Model $record): bool
    {
        return static::canEdit($record);
    }
    
    public static function canView(Model $record): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        if ($user->role === 'admin') {
            return true;
        }
        
        if ($user->role === 'teacher') {
            return $record->lesson->teacher_id === $user->id;
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'view' => Pages\ViewAttendance::route('/{record}'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->heading('1st Sales section')
                    ->relationship('sale')
                    ->statePath('firstSection')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->default(fake()->word()),
                    ]),

                Forms\Components\Section::make()
                    ->heading('User')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->default(fake()->name()),

                        Forms\Components\TextInput::make('email')
                            ->required()
                            ->maxLength(255)
                            ->email()
                            ->unique(User::class, 'email', ignoreRecord: true)
                            ->default(fake()->unique()->safeEmail()),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->autocomplete(false)
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->default('password'),
                    ]),

                Forms\Components\Section::make()
                    ->heading('2nd Sales section')
                    ->relationship('sale')
                    ->statePath('secondSection')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->default(fake()->randomFloat()),
                    ]),
            ]);
    }

    /**
     * Working example below
     */

//    public static function form(Form $form): Form
//    {
//        return $form
//            ->schema([
//                Forms\Components\Section::make()
//                    ->heading('Single Sales section')
//                    ->relationship('sale')
//                    ->statePath('firstSection')
//                    ->schema([
//                        Forms\Components\TextInput::make('name')
//                            ->default(fake()->word()),
//                        Forms\Components\TextInput::make('price')
//                            ->default(fake()->randomFloat()),
//                    ]),
//
//                Forms\Components\Section::make()
//                    ->heading('User')
//                    ->schema([
//                        Forms\Components\TextInput::make('name')
//                            ->required()
//                            ->maxLength(255)
//                            ->default(fake()->name()),
//
//                        Forms\Components\TextInput::make('email')
//                            ->required()
//                            ->maxLength(255)
//                            ->email()
//                            ->unique(User::class, 'email', ignoreRecord: true)
//                            ->default(fake()->unique()->safeEmail()),
//
//                        Forms\Components\TextInput::make('password')
//                            ->password()
//                            ->autocomplete(false)
//                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
//                            ->dehydrated(fn ($state) => filled($state))
//                            ->required(fn (string $context): bool => $context === 'create')
//                            ->default('password'),
//                    ]),
//            ]);
//    }

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

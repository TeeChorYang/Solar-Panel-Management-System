<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Installation;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InstallationResource\Pages;
use App\Filament\Resources\InstallationResource\RelationManagers;

class InstallationResource extends Resource
{
    protected static ?string $model = Installation::class;

    protected static ?string $navigationIcon = 'heroicon-o-check';

    protected static ?string $navigationLabel = 'Installations';

    protected static ?string $modelLabel = 'Installation';

    protected static ?string $navigationGroup = 'Information';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->label('Order ID')
                    ->required()
                    ->options(
                        Order::all()->pluck('id', 'id')
                    )
                    ->searchable(),
                Forms\Components\Select::make('manager_id')
                    ->label('Manager')
                    ->required()
                    ->options(
                        User::all()->where('type', 'manager')->pluck('name', 'id')
                    )
                    ->searchable(),
                Forms\Components\DatePicker::make('schedule_date')
                    ->label('Schedule Date')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->required()
                    ->default('scheduled')
                    ->options(config('staticdata.installation_status'))
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('manager.name')
                    ->label('Manager')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('schedule_date')
                    ->label('Schedule Date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListInstallations::route('/'),
            'create' => Pages\CreateInstallation::route('/create'),
            'edit' => Pages\EditInstallation::route('/{record}/edit'),
        ];
    }
}